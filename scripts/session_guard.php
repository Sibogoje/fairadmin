<?php
/**
 * Idle timeout, online tracking and password expiry for staff sessions.
 *
 * Password expiry: realuzer.password_changed_at records when the password was
 * last set. PASSWORD_MAX_AGE after that, every page sends the user to
 * change_password.php until they choose a new one.
 *
 * Runs on every request that loads scripts/connection.php with an active,
 * logged-in session:
 *   - logs the user out after SESSION_IDLE_TIMEOUT seconds without a request,
 *     marks them offline and records `logout_auto` in the audit trail;
 *   - otherwise records realuzer.last_seen and session = 1 (at most once a minute);
 *   - marks other users offline (with `logout_auto`) whose last request is older
 *     than the timeout, e.g. people who closed the browser without logging out.
 *
 * Times in realuzer are stored in SAST, like last_login.
 */
require_once __DIR__ . '/audit.php';

if (!defined('SESSION_IDLE_TIMEOUT')) {
    define('SESSION_IDLE_TIMEOUT', 25 * 60);
}
// Passwords must be changed this long after they were last set (DateTime modifier).
if (!defined('PASSWORD_MAX_AGE')) {
    define('PASSWORD_MAX_AGE', '+3 months');
}
// Show a reminder this many days before the password expires.
if (!defined('PASSWORD_WARN_DAYS')) {
    define('PASSWORD_WARN_DAYS', 7);
}
// password_changed_at value that means "must change at next login" (set when an admin sets a password).
if (!defined('PASSWORD_EXPIRED_MARKER')) {
    define('PASSWORD_EXPIRED_MARKER', '2000-01-01 00:00:00');
}

if (!function_exists('session_guard_now')) {
    function session_guard_now(): DateTime
    {
        return new DateTime('now', new DateTimeZone('Africa/Johannesburg'));
    }
}

if (!function_exists('session_guard_minutes')) {
    function session_guard_minutes(): int
    {
        return (int)round(SESSION_IDLE_TIMEOUT / 60);
    }
}

if (!function_exists('session_guard_is_ajax')) {
    function session_guard_is_ajax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }
}

if (!function_exists('session_guard_script_is')) {
    /** True when the running script is one of these files in the project root. */
    function session_guard_script_is(array $rootFiles): bool
    {
        // Compare full paths so fund/index.php, membership/index.php etc. do not match.
        $script = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
        if ($script === false) {
            return false;
        }
        $root = dirname(__DIR__);
        foreach ($rootFiles as $file) {
            if ($script === realpath($root . DIRECTORY_SEPARATOR . $file)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('session_guard_exempt')) {
    function session_guard_exempt(): bool
    {
        // The root login form, login handler and logout manage the session themselves.
        return session_guard_script_is(['index.php', 'parse.php', 'logout.php']);
    }
}

if (!function_exists('session_guard_prepare')) {
    /** Prepare a statement on realuzer, adding the tracking columns first if they are missing. */
    function session_guard_prepare(mysqli $conn, string $sql): mysqli_stmt
    {
        try {
            return $conn->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            if ((int)$e->getCode() !== 1054) { // unknown column
                throw $e;
            }
            $conn->query("ALTER TABLE `realuzer`
                ADD COLUMN IF NOT EXISTS `last_seen` DATETIME NULL,
                ADD COLUMN IF NOT EXISTS `password_changed_at` DATETIME NULL");
            return $conn->prepare($sql);
        }
    }
}

if (!function_exists('session_guard_password_expires_at')) {
    /**
     * When the user's password expires (unix time). A user with no recorded
     * change date starts the clock now, so existing users are not locked out
     * the moment this feature is deployed.
     */
    function session_guard_password_expires_at(mysqli $conn, string $userId): int
    {
        $st = session_guard_prepare($conn, "SELECT `password_changed_at` FROM `realuzer` WHERE `id` = ?");
        $st->bind_param('s', $userId);
        $st->execute();
        $changedAt = $st->get_result()->fetch_assoc()['password_changed_at'] ?? null;
        $st->close();

        $tz = new DateTimeZone('Africa/Johannesburg');
        if ($changedAt === null) {
            $changedAt = session_guard_now()->format('Y-m-d H:i:s');
            $init = $conn->prepare("UPDATE `realuzer` SET `password_changed_at` = ? WHERE `id` = ? AND `password_changed_at` IS NULL");
            $init->bind_param('ss', $changedAt, $userId);
            $init->execute();
            $init->close();
        }
        return (new DateTime($changedAt, $tz))->modify(PASSWORD_MAX_AGE)->getTimestamp();
    }
}

if (!function_exists('session_guard_password_days_left')) {
    /** Days until the current user's password expires (a part day counts as 1), or null if unknown. */
    function session_guard_password_days_left(): ?int
    {
        if (!isset($_SESSION['password_expires_at'])) {
            return null;
        }
        return max(0, (int)ceil(((int)$_SESSION['password_expires_at'] - time()) / 86400));
    }
}

if (!function_exists('session_guard_require_password_change')) {
    /** Send a user whose password has expired to the Change Password page. */
    function session_guard_require_password_change(): void
    {
        // These pages stay usable so the user can change the password or leave.
        if (session_guard_script_is(['change_password.php', 'logout.php', 'session_ping.php'])) {
            return;
        }
        $message = 'Your password has expired. Please change it before continuing.';
        if (session_guard_is_ajax()) {
            echo json_encode([
                'statusCode' => 201,
                'datas' => $message,
                'error' => $message,
                'rerror' => $message,
                'retrieveerror' => $message,
                'passwordExpired' => true,
            ]);
        } elseif (!headers_sent()) {
            header('Location: ' . app_url('change_password.php?expired=1'));
        } else {
            $url = htmlspecialchars(app_url('change_password.php?expired=1'), ENT_QUOTES);
            echo '<script>location.href=' . json_encode(app_url('change_password.php?expired=1')) . ';</script>'
               . '<p>' . htmlspecialchars($message) . ' <a href="' . $url . '">Change password</a></p>';
        }
        exit;
    }
}

if (!function_exists('session_guard_sweep')) {
    /** Mark users offline whose last request is older than the idle timeout. */
    function session_guard_sweep(mysqli $conn): void
    {
        try {
            $cutoff = session_guard_now()->modify('-' . SESSION_IDLE_TIMEOUT . ' seconds')->format('Y-m-d H:i:s');
            $find = session_guard_prepare($conn,
                "SELECT `id`, `username`, `last_seen` FROM `realuzer`
                 WHERE `session` = 1
                   AND COALESCE(DATE_FORMAT(`last_seen`, '%Y-%m-%d %H:%i:%s'), `last_login`, '') < ?");
            $find->bind_param('s', $cutoff);
            $find->execute();
            $stale = $find->get_result()->fetch_all(MYSQLI_ASSOC);
            $find->close();

            $off = $conn->prepare("UPDATE `realuzer` SET `session` = 0 WHERE `id` = ? AND `session` = 1");
            foreach ($stale as $u) {
                $off->bind_param('s', $u['id']);
                $off->execute();
                if ($off->affected_rows === 1) { // another request may have done it already
                    audit_trail_log(
                        $conn,
                        'logout_auto',
                        'Marked offline after ' . session_guard_minutes() . ' minutes of inactivity'
                            . ($u['last_seen'] ? ' (last seen ' . $u['last_seen'] . ' SAST)' : ''),
                        ['username' => (string)$u['username'], 'route' => '', 'ip' => '']
                    );
                }
            }
            $off->close();
        } catch (Throwable $e) {
            error_log('Session sweep failed: ' . $e->getMessage());
        }
    }
}

if (!function_exists('session_guard_expire')) {
    /** Log the current user out for inactivity and stop the request. */
    function session_guard_expire(mysqli $conn): void
    {
        $userId = (string)($_SESSION['xid'] ?? '');
        try {
            if ($userId !== '') {
                $st = $conn->prepare("UPDATE `realuzer` SET `session` = 0 WHERE `id` = ?");
                $st->bind_param('s', $userId);
                $st->execute();
                $st->close();
            }
        } catch (Throwable $e) {
            error_log('Session expire update failed: ' . $e->getMessage());
        }
        audit_trail_log($conn, 'logout_auto', 'Logged out automatically after ' . session_guard_minutes() . ' minutes of inactivity');

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();

        $message = 'You were logged out after ' . session_guard_minutes() . ' minutes of inactivity. Please log in again.';
        if (session_guard_is_ajax()) {
            echo json_encode([
                'statusCode' => 201,
                'datas' => $message,
                'error' => $message,
                'rerror' => $message,
                'retrieveerror' => $message,
                'sessionExpired' => true,
            ]);
        } elseif (!headers_sent()) {
            header('Location: ' . app_url('index.php?timeout=1'));
        } else {
            // Page output already started (connection loaded late): redirect from the browser.
            $url = htmlspecialchars(app_url('index.php?timeout=1'), ENT_QUOTES);
            echo '<script>location.href=' . json_encode(app_url('index.php?timeout=1')) . ';</script>'
               . '<p>' . htmlspecialchars($message) . ' <a href="' . $url . '">Log in</a></p>';
        }
        exit;
    }
}

if (!function_exists('session_guard_check')) {
    function session_guard_check(mysqli $conn): void
    {
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;

        if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['zid']) || session_guard_exempt()) {
            return;
        }

        $now = time();
        $last = (int)($_SESSION['last_activity'] ?? $now);
        if ($now - $last > SESSION_IDLE_TIMEOUT) {
            session_guard_expire($conn); // exits
        }
        $_SESSION['last_activity'] = $now;

        // Record "online" and tidy up idle users at most once a minute per session.
        if ($now - (int)($_SESSION['session_guard_db_at'] ?? 0) >= 60) {
            $_SESSION['session_guard_db_at'] = $now;
            try {
                $userId = (string)($_SESSION['xid'] ?? '');
                if ($userId !== '') {
                    $seenAt = session_guard_now()->format('Y-m-d H:i:s');
                    $st = session_guard_prepare($conn, "UPDATE `realuzer` SET `session` = 1, `last_seen` = ? WHERE `id` = ?");
                    $st->bind_param('ss', $seenAt, $userId);
                    $st->execute();
                    $st->close();
                }
            } catch (Throwable $e) {
                error_log('Session last_seen update failed: ' . $e->getMessage());
            }
            session_guard_sweep($conn);
        }

        // Password age: look it up once per session (and after a change), then enforce.
        $userId = (string)($_SESSION['xid'] ?? '');
        if ($userId !== '' && !isset($_SESSION['password_expires_at'])) {
            try {
                $_SESSION['password_expires_at'] = session_guard_password_expires_at($conn, $userId);
            } catch (Throwable $e) {
                error_log('Password age check failed: ' . $e->getMessage());
            }
        }
        if (isset($_SESSION['password_expires_at']) && time() >= (int)$_SESSION['password_expires_at']) {
            session_guard_require_password_change(); // exits unless on an allowed page
        }

        // Action endpoints ask for the session lock to be released once checked.
        if (!empty($GLOBALS['session_guard_release_lock'])) {
            session_write_close();
        }
    }
}

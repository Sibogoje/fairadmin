<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/audit.php';
// reason=idle is sent by the inactivity timer in header.php
$isIdleLogout = ($_GET['reason'] ?? '') === 'idle';
$loginUrl = APP_URL . 'index.php' . ($isIdleLogout ? '?timeout=1' : '');

if(isset($_SESSION['zid']))
{
    $session = 0;
    $userid = isset($_SESSION['xid']) ? $_SESSION['xid'] : null;
    $username = audit_trail_username();

    // update server session flag (last_login is recorded at login in parse.php)
    if ($userid) {
        $updatesession = $conn->prepare("UPDATE realuzer SET session=? WHERE id=? ");
        $updatesession->bind_param("ss", $session, $userid);
        $updatesession->execute();
    }

    if ($isIdleLogout) {
        audit_trail_log($conn, 'logout_auto', 'Logged out automatically after ' . session_guard_minutes() . ' minutes of inactivity');
    } else {
        audit_trail_log($conn, 'logout', 'User logged out: ' . $username);
    }

    // Send no-cache headers to prevent back-button showing cached pages
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

    // Clear session data and cookie
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_unset();
    session_destroy();

    header('Location: ' . $loginUrl);
    exit();
}

// Already logged out (e.g. the server timed the session out first): go to login.
header('Location: ' . $loginUrl);
exit();
?>
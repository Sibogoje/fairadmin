<?php
// Change your own password. Users are sent here when their password is older
// than PASSWORD_MAX_AGE (see scripts/session_guard.php); it can also be opened
// any time from the profile menu.
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once __DIR__ . '/scripts/connection.php'; // idle check runs here
require_once __DIR__ . '/scripts/access_control.php';
require_once __DIR__ . '/scripts/audit.php';

if (empty($_SESSION['zid']) || empty($_SESSION['xid'])) {
    header('Location: ' . app_url('index.php'));
    exit();
}

$userId = (string)$_SESSION['xid'];
$homeUrl = access_control_is_allowed($_SESSION['role'] ?? '', '/dash.php')
    ? app_url('dash.php')
    : app_url('membership/profileaccount.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = (string)($_POST['current_password'] ?? '');
    $new     = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    $st = $conn->prepare("SELECT `password` FROM `realuzer` WHERE `id` = ?");
    $st->bind_param('s', $userId);
    $st->execute();
    $storedHash = $st->get_result()->fetch_assoc()['password'] ?? null;
    $st->close();

    // md5 matches what parse.php checks at login.
    if ($current === '' || $new === '' || $confirm === '') {
        $error = 'Please fill in all three fields.';
    } elseif ($storedHash === null || !hash_equals((string)$storedHash, md5($current))) {
        $error = 'Your current password is not correct.';
    } elseif (strlen($new) < 8 || !preg_match('/[A-Za-z]/', $new) || !preg_match('/[0-9]/', $new)) {
        $error = 'The new password must be at least 8 characters and include a letter and a number.';
    } elseif ($new !== $confirm) {
        $error = 'The new passwords do not match.';
    } elseif (hash_equals((string)$storedHash, md5($new))) {
        $error = 'The new password must be different from your current password.';
    } else {
        $changedAt = session_guard_now()->format('Y-m-d H:i:s');
        $newHash = md5($new);
        $up = session_guard_prepare($conn, "UPDATE `realuzer` SET `password` = ?, `password_changed_at` = ? WHERE `id` = ?");
        $up->bind_param('sss', $newHash, $changedAt, $userId);
        $up->execute();
        $up->close();

        audit_trail_log($conn, 'password_change', 'User changed their own password');
        $_SESSION['password_expires_at'] = session_guard_password_expires_at($conn, $userId);
        header('Location: ' . app_url('change_password.php?done=1'));
        exit();
    }
}

$expired = isset($_SESSION['password_expires_at']) && time() >= (int)$_SESSION['password_expires_at'];
$daysLeft = session_guard_password_days_left();
$done = isset($_GET['done']) && !$expired;
$expiresOn = isset($_SESSION['password_expires_at'])
    ? (new DateTime('@' . (int)$_SESSION['password_expires_at']))->setTimezone(new DateTimeZone('Africa/Johannesburg'))->format('j F Y')
    : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fairlife — Change Password</title>
  <link href="<?= app_url('logo.png') ?>" rel="icon">
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,600,700&display=swap" rel="stylesheet">
  <link href="<?= app_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= app_url('assets/css/style.css') ?>" rel="stylesheet">
  <link href="<?= app_url('assets/css/login.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
  <main class="d-flex vh-100 align-items-center justify-content-center">
    <div class="card shadow-sm w-100" style="max-width:440px">
      <div class="card-body p-4">
        <div class="text-center mb-3">
          <img src="<?= app_url('logo.png') ?>" alt="FairLife" class="login-logo mb-2">
          <h4 class="mb-0">Change Password</h4>
          <small class="text-muted">Signed in as <?= htmlspecialchars((string)($_SESSION['user'] ?? '')) ?></small>
        </div>

<?php if ($done): ?>
        <div class="alert alert-success small" role="alert">
          Your password has been changed. The next change is due by <?= htmlspecialchars($expiresOn) ?>.
        </div>
        <div class="d-grid">
          <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn btn-warning">Continue</a>
        </div>
<?php else: ?>
<?php if ($expired): ?>
        <div class="alert alert-warning small" role="alert">
          Your password has expired. Passwords must be changed every 3 months. Please choose a new one to continue.
        </div>
<?php elseif ($daysLeft !== null && $daysLeft <= PASSWORD_WARN_DAYS): ?>
        <div class="alert alert-info small" role="alert">
          Your password expires in <?= (int)$daysLeft ?> day<?= $daysLeft === 1 ? '' : 's' ?>.
        </div>
<?php endif; ?>
<?php if ($error !== ''): ?>
        <div class="alert alert-danger small" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

        <form method="post" action="<?= app_url('change_password.php') ?>" autocomplete="off">
          <div class="mb-3 form-floating">
            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current password" autocomplete="current-password" required>
            <label for="current_password">Current password</label>
          </div>
          <div class="mb-3 form-floating">
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password" autocomplete="new-password" required minlength="8">
            <label for="new_password">New password</label>
          </div>
          <div class="mb-1 form-floating">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password" autocomplete="new-password" required minlength="8">
            <label for="confirm_password">Confirm new password</label>
          </div>
          <div class="form-text mb-3">At least 8 characters, with a letter and a number.</div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning">Change password</button>
<?php if (!$expired): ?>
            <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn btn-link btn-sm">Cancel</a>
<?php endif; ?>
            <a href="<?= app_url('logout.php') ?>" class="btn btn-link btn-sm text-muted">Sign out</a>
          </div>
        </form>
<?php endif; ?>
      </div>
    </div>
  </main>
  <script src="<?= app_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>

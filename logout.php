<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/audit.php';
if(isset($_SESSION['zid']))
{
    $session = 0;
    $userid = isset($_SESSION['xid']) ? $_SESSION['xid'] : null;
    $username = audit_trail_username();

    // update server session flag
    if ($userid) {
        date_default_timezone_set('Africa/Johannesburg');
        $login = date('Y-m-d H:i:s');
        $updatesession = $conn->prepare("UPDATE realuzer SET session=?, last_login=? WHERE id=? ");
        $updatesession->bind_param("sss", $session, $login, $userid);
        $updatesession->execute();
    }

    audit_trail_log($conn, 'logout', 'User logged out: ' . $username);

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

    header('Location: ' . APP_URL . 'index.php');
    exit();
}

?>
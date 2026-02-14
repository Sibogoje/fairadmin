<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
session_start();
require_once 'scripts/connection.php';
if(isset($_SESSION['zid']))
{
    $session = 0;
    $userid = isset($_SESSION['xid']) ? $_SESSION['xid'] : null;

    // update server session flag
    if ($userid) {
        date_default_timezone_set('Africa/Johannesburg');
        $login = date('Y-m-d H:i:s');
        $updatesession = $conn->prepare("UPDATE realuzer SET session=?, last_login=? WHERE id=? ");
        $updatesession->bind_param("sss", $session, $login, $userid);
        $updatesession->execute();
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

    header('Location: APP_URLindex.php');
    exit();
}

?>
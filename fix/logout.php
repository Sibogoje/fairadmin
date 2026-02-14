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
$sessionid = $_SESSION['zid'];
$userid = $_SESSION['xid'];
echo $userid." ".$sessionid;
date_default_timezone_set('Africa/Johannesburg');
$login = date('Y-m-d H:m:s');
$updatesession = $conn->prepare("UPDATE realuzer SET session=?, last_login=? WHERE id=? ");
$updatesession->bind_param("sss", $session, $login, $userid);
$updatesession->execute();

    $_SESSION=array();
    unset($_SESSION);
    session_destroy();

header('Location: ' . APP_URL . 'index.php');
}

?>
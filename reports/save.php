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
require_once '../scripts/connection.php';
echo "fdvfvdfdfv";
if (isset($_POST['sub'])){
	

	
$commenta = $_POST['editor']; 


$stmt = $conn->prepare("INSERT INTO `u747325399_fairlife`.`comments` 
(
  `comment`
  )
VALUES(
?
  );");


$stmt->bind_param("s", $commenta);
if (!$stmt->execute() ) 
$stmt->close();
}else{
	//
}
?>
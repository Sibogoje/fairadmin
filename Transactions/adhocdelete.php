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
if(count($_POST)>0){
$rr = $_POST['id'];

$deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments` WHERE `adhocPaymentID`=? ");
$deleteadhoc->bind_param("s", $rr);

if ($deleteadhoc->execute()) { 
$response = array(
					'statusCode'=>200,
					'rsuccess'=>"Adhoc Payment Delete Succesful"
					);
				echo json_encode($response);

} else {
  $response = array(
					'statusCode'=>201,
					'rerror'=>"Error: Adhoc Payment Delete Not Succesful "
					);
				echo json_encode($response);

}



}
?>
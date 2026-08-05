<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';
require_once __DIR__ . '/../scripts/audit.php';
if(count($_POST)>0){
$rr = $_POST['id'];

$deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments` WHERE `adhocPaymentID`=? ");
$deleteadhoc->bind_param("s", $rr);

if ($deleteadhoc->execute()) { 
audit_trail_log($conn, 'adhoc_stage_delete', 'Deleted staged adhoc payment ID ' . $rr);
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
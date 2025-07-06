<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];

$deleteadhoc = $conn->prepare("DELETE FROM `tbltempcapital` WHERE `capitalID`=? ");
$deleteadhoc->bind_param("s", $rr);

if ($deleteadhoc->execute()) { 
$response = array(
					'statusCode'=>200,
					'rsuccess'=>"Capital Introduction Delete Succesful"
					);
				echo json_encode($response);

} else {
  $response = array(
					'statusCode'=>201,
					'rerror'=>"Error: Capital Introduction Delete Not Succesful "
					);
				echo json_encode($response);

}



}
?>
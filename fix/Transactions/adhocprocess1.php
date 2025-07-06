<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];

$stmt = $conn->prepare("SELECT memberid FROM `clientr` where id = '$rr' " );
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$mm = $row['memberid']; 


$stmt2 = $conn->prepare("SELECT MemberNo FROM `tblmembers` where MemberID = '$mm' " );
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($result2->num_rows > 0) {
while($row2 = $result2->fetch_assoc()) {
        
$mm2 = $row2['MemberNo'];   

$title = "Request Notification";
$message = "Your request was accepted. Please keep checking under Pending Requests for updates.";
$dates = date('Y-m-d');

$deleteadhoc = $conn->prepare("DELETE FROM `clientr` WHERE `id`=? ");
$deleteadhoc->bind_param("s", $rr);

if ($deleteadhoc->execute()) { 
    
$insertnew = $conn->prepare("insert into `notifications` (

  `memberid`,
  `title`,
  `message`,
  `date`
)VALUES
  (

    ?,
    ?,
    ?,
    ?
  );");
$insertnew->bind_param("ssss", 
$mm2, 
$title,
$message,
$dates

);
$insertnew->execute();
    
    
    
    
    
    
$response = array(
					'statusCode'=>200,
					'rsuccess'=>"Client Request Deleted"
					);
				echo json_encode($response);

} else {
  $response = array(
					'statusCode'=>201,
					'rerror'=>"Error: Client Request Deleted Not Succesful "
					);
				echo json_encode($response);

}



}
}else{
      $response = array(
					'statusCode'=>201,
					'rerror'=>"Error: Error Getting Member Information "
					);
				echo json_encode($response);
}

}

}}
?>
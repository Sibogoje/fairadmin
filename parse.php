<?php
session_start();
require_once 'scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
	 $user=$_POST['username'];
	 $pass=$_POST['password'];
$hashed_password = md5($pass);
	 

$stmt = $conn->prepare("SELECT * FROM `realuzer` WHERE `username`=? AND `password`=?");
$stmt->bind_param("ss", $user, $hashed_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$pazz = trim($row['password']);	
$id = trim($row['id']);
$role = trim($row['role']);


$_SESSION['user'] = $user;
$_SESSION['role'] = $role;
$_SESSION['zid'] = session_id();
$_SESSION['xid'] = $id;
$sessionid = session_id();
$session = 1;

$updatesession = $conn->prepare("UPDATE realuzer SET session=? WHERE id=?");
$updatesession->bind_param("ss", $session, $id);
$updatesession->execute();

$response = array(
					'statusCode'=>200,
					'success'=>"Allowed" 
					);
				echo json_encode($response);

}	 
}else{
		$response = array(
					'statusCode'=>201,
					'exception'=>"No user with this username exists" 
					);
				echo json_encode($response);   
}
    
}else{
		$response = array(
					'statusCode'=>202,
					'error'=>"Problem" 
					);
				echo json_encode($response);   
}	 
	 ?>
	
	
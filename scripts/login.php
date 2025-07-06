<?php
require_once 'connection.php';
if (isset($_POST['btlogin'])){
		$email=$_POST['username'];
		$password=$_POST['password'];
		
$stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
	exit('No rows');
} else {
	//exit('Found');
	header('Location: ../dash.php');
}

var_export($ages);
$stmt->close();
}	
?>
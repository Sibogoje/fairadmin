<?php
require_once __DIR__ . '/scripts/bootstrap.php';
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

			// Regenerate session id to prevent fixation
			session_regenerate_id(true);

			$_SESSION['user'] = $user;
			$_SESSION['role'] = $role;
			$_SESSION['zid'] = session_id();
			$_SESSION['xid'] = $id;
			$sessionid = session_id();
			$session = 1;

			// Update server-side session marker
			$updatesession = $conn->prepare("UPDATE realuzer SET session=? WHERE id=?");
			$updatesession->bind_param("ss", $session, $id);
			$updatesession->execute();

			// Ensure session cookie is HttpOnly and sent over HTTPS when available
			$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
			if (PHP_VERSION_ID >= 70300) {
				setcookie(session_name(), session_id(), [
					'expires' => 0,
					'path' => '/',
					'domain' => $_SERVER['HTTP_HOST'],
					'secure' => $secure,
					'httponly' => true,
					'samesite' => 'Lax'
				]);
			} else {
				setcookie(session_name(), session_id(), 0, '/', $_SERVER['HTTP_HOST'], $secure, true);
			}

			$response = array(
				'statusCode' => 200,
				'success' => "Allowed"
			);
			// Ensure session is written to storage before responding
			session_write_close();
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
	
	
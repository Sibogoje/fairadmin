<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/audit.php';
set_time_limit(0);

if(count($_POST) > 0){
	 $user = $_POST['username'];
	 $pass = $_POST['password'];
	 $hashed_password = md5($pass);

	 $stmt = $conn->prepare("SELECT * FROM `realuzer` WHERE `username`=? AND `password`=?");
	 $stmt->bind_param("ss", $user, $hashed_password);
	 $stmt->execute();
	 $result = $stmt->get_result();

	 if ($result->num_rows > 0) {
	 	while($row = $result->fetch_assoc()) {
	 		$id = trim($row['id']);
	 		$role = trim($row['role']);

	 		// Regenerate session id to prevent fixation
	 		session_regenerate_id(true);

	 		// Set session variables
	 		$_SESSION['user'] = $user;
	 		$_SESSION['role'] = $role;
	 		$_SESSION['zid'] = session_id();
	 		$_SESSION['xid'] = $id;
	 		$session = 1;

	 		// Update server-side session marker
	 		$updatesession = $conn->prepare("UPDATE realuzer SET session=? WHERE id=?");
	 		$updatesession->bind_param("ss", $session, $id);
	 		$updatesession->execute();

	 		audit_trail_log($conn, 'login', 'Authenticated with role: ' . $role);

	 		// Write session to disk before redirect
	 		session_write_close();
	 		
	 		// Redirect to dashboard
	 		header('Location: ' . APP_URL . 'dash.php');
	 		exit;
	 	}	 
	 } 

	audit_trail_log($conn, 'login_failed', 'Invalid login attempt for username: ' . $user);
}

// Invalid request or failed auth - redirect to login
session_write_close();
header('Location: ' . APP_URL . 'index.php');
exit;
?>
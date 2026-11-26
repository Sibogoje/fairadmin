<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
require_once __DIR__ . '/../scripts/access_control.php';
access_control_require_endpoint(['/reports/tt.php']);
require_once '../scripts/connection.php';
require_once __DIR__ . '/../scripts/audit.php';
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
if ($stmt->execute() ) {
audit_trail_log($conn, 'comment_create', 'Saved report comment');
}
$stmt->close();
}else{
	//
}
?>
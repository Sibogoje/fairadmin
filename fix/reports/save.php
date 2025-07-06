<?php
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
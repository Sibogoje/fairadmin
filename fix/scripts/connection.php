<?php
$servername = "89.116.53.194";
$username = "u747325399_fairfix";
$password = "Fairfix2";
$dbname   = 'u747325399_fairfix';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
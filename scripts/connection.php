<?php
//$servername = "srv1212.hstgr.io";
$username = "u747325399_fair2";
$password = "2Jznf67RS=";
$dbname   = 'u747325399_fair2';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
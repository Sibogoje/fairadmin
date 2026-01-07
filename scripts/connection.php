<?php
$servername = "148.163.121.29";
$username = "dxtustit_fair2";
$password = "Fairline@151022";
$dbname   = 'dxtustit_fair2';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
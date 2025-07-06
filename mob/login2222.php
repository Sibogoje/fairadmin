<?php
// Replace with your database credentials


// $hostname = "194.5.156.43";
// $username = "u747325399_fair2";
// $password = "Fairline@151022";
// $database = 'u747325399_fair2';


$conn = new mysqli("194.5.156.43", "u747325399_fair2", "Fairline@151022", "u747325399_fair2");

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Set error log file path
$errorLogPath = 'error.log';
error_log("Error: " . $mysqli->error, 3, $errorLogPath);

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', $errorLogPath);

// Get the username and password from the request
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

// Check if the user exists in the database
$sql = "SELECT * FROM MobUsers WHERE memberid='$username' AND password='$password'";
$result = $conn->query($sql);

// If the user exists, return success
if ($result->num_rows > 0) {
    echo "success";
} else {
    // The user does not exist, return failure
    echo "failure";
}

// Close the connection
$conn->close();

?>
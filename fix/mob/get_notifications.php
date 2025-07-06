<?php
// Replace with your database connection details
$servername = "194.5.156.43";
$username = "u747325399_fair2";
$password = "Fairline@151022";
$dbname = "u747325399_fair2";


// $hostname = "194.5.156.43";
// $username = "u747325399_fair2";
// $password = "Fairline@151022";
// $database = 'u747325399_fair2';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Set error log file path
$errorLogPath = 'error1.log';
error_log("Error: " . $mysqli->error, 3, $errorLogPath);

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', $errorLogPath);

//$conn = new mysqli("194.5.156.43", "u747325399_fair2", "Fairline@151022", "u747325399_fair2");

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve notifications
$sql = "SELECT id, title, message FROM notifications";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $notifications = array();

    while($row = $result->fetch_assoc()) {
        $notification = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'message' => $row['message']
        );

        array_push($notifications, $notification);
    }

    // Return notifications as JSON
    echo json_encode($notifications);
} else {
    // No notifications found
    echo "No notifications found.";
}

$conn->close();
?>

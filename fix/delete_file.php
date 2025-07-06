<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Ensure that the $_SESSION['user'] is set
if (!isset($_SESSION['user'])) {
    exit('User session not found.');
}

require_once 'scripts/connection.php';

// Check if the fileId is set in the POST request
if (isset($_POST['fileId'])) {
    $fileId = $_POST['fileId'];

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM `adhocfiles` WHERE `url` = ?");
    $stmt->bind_param("s", $fileId);

    // Perform the database deletion
    if ($stmt->execute()) {
        $response = array('status' => 'success');
    } else {
        $response = array('status' => 'error', 'message' => $stmt->error);
    }

    // Close the statement
    $stmt->close();
} else {
    $response = array('status' => 'error', 'message' => 'File ID not provided.');
}

// Close the database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

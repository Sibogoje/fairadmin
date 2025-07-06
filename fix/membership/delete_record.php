<?php
// Assuming $conn is already established
require_once '../scripts/connection.php';

// Check if the ID parameter is provided
if(isset($_GET['id'])) {
    // Sanitize the ID parameter to prevent SQL injection
    $memberID = $_GET['id'];
    
    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tblmembers WHERE MemberID = ?");
    $stmt->bind_param("i", $memberID); // assuming MemberID is an integer
    $stmt->execute();
    
    if($stmt->affected_rows > 0) {
        // Record deleted successfully
        echo json_encode(array("success" => true, "message" => "Record deleted successfully"));
    } else {
        // No record found with the provided ID
        echo json_encode(array("success" => false, "message" => "No record found with the provided ID"));
    }
    
    // Close the prepared statement
    $stmt->close();
} else {
    // ID parameter not provided
    echo json_encode(array("success" => false, "message" => "No ID parameter provided"));
}

// Close the database connection
$conn->close();
?>

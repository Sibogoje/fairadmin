<?php
// fetch_file_list.php

// Include your database connection code here
require_once 'scripts/connection.php';
if (isset($_GET['memberId'])) {
    $memberId = $_GET['memberId'];
    $gg = 227;

    // Adjust the SQL query based on your database schema
    $selectFiles = $conn->prepare("SELECT url, name FROM adhocfiles WHERE member = ?");
    $selectFiles->bind_param('s', $memberId);
    $selectFiles->execute();
    $result = $selectFiles->get_result();

    $fileList = array();
    while ($row = $result->fetch_assoc()) {
        $fileList[] = $row;
    }

    echo json_encode($fileList);
}
?>

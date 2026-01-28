<?php
$servername = "148.163.121.29";
$username = "dxtustit_fair2";
$password = "Fairline@151022";
$dbname   = 'dxtustit_fair2';

// Make mysqli throw exceptions so we can catch them
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Log the detailed error for debugging and return a generic message to the browser
    error_log("DB connect error: " . $e->getMessage());
    http_response_code(500);
    die("Database connection error. Please try again later.");
}

//
?>
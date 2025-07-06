<?php
session_start(); // Start the session.
include('config.php');

if (isset($_SESSION['memberno'])) {
    try {
        $memberno = $_SESSION['memberno'];
        $logoutTime = time(); // Get the current timestamp.

        // Update last_login and online status in the MobUsers table.
        $stmt = $pdo->prepare("UPDATE MobUsers SET last_login = NOW(), online = 0 WHERE memberid = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();

        // Destroy the session and redirect to the login page.
        session_destroy();
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // No valid session, redirect to the login page.
    header("Location: login.php");
    exit();
}
?>

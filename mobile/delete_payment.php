<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
session_start(); // Start the session on the index page.
include('config.php');
if (isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    try {
        // Delete the row from the database
        $stmt = $pdo->prepare("DELETE FROM tbltempadhocpayments WHERE adhocPaymentID = :payment_id");
        $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
        $stmt->execute();
        
        echo "Row deleted successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

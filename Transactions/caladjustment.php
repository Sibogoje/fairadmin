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
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../scripts/connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['zid'])) {
    ob_clean();
    echo json_encode(['statusCode' => 401, 'error' => 'Unauthorized']);
    exit;
}

if (isset($_POST['adjustmentAmount'])) {
    $adjustmentAmount = floatval($_POST['adjustmentAmount']);
    $adjustmentDate = isset($_POST['adjustmentDate']) ? $_POST['adjustmentDate'] : date('Y-m-d');
    $totalBalance = 0;
    $members = [];
    $result = $conn->query("SELECT memberID, NewBalance FROM balances WHERE Term = 0 AND NewBalance > 0");
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
        $totalBalance += $row['NewBalance'];
    }
    $success = 0;
    $fail = 0;
    foreach ($members as $member) {
        $portion = ($member['NewBalance'] / $totalBalance) * $adjustmentAmount;
        $newBalance = $member['NewBalance'] - $portion;
    $stmt = $conn->prepare("INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $date = $adjustmentDate;
    $transactionTypeID = 13;
    $yearMonth = date('Y-m', strtotime($date));
    $details = 'Adjustment for - ' . $yearMonth;
    $credit = 0;
    $comments = 'Adjustment for - ' . $yearMonth;
    $stmt->bind_param('sisssddds', $date, $transactionTypeID, $member['memberID'], $details, $credit, $member['NewBalance'], $portion, $newBalance, $comments);
        if ($stmt->execute()) {
            $success++;
            $conn->query("UPDATE balances SET NewBalance = $newBalance WHERE memberID = '{$member['memberID']}'");
        } else {
            $fail++;
        }
        $stmt->close();
    }
    $message = "Bulk adjustment complete. Success: $success, Failed: $fail.";
    ob_clean();
    echo json_encode(['statusCode' => 200, 'dones' => $message]);
    exit;
} else {
    ob_clean();
    echo json_encode(['statusCode' => 400, 'error' => 'No adjustment amount provided']);
    exit;
}
?>

<?php
require_once 'scripts/connection.php';

$from = '2025-08-01';
$to = '2025-08-31';

// Check total transactions in the period
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM tblmemberaccounts WHERE DATE(TransactionDate) BETWEEN ? AND ?");
$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo "Total transactions in $from to $to: " . $row['total'] . "\n";

// Check by type
$types = [2, 5, 6, 7, 12];
foreach ($types as $type) {
    $stmt = $conn->prepare("SELECT SUM(Amount) as sum_amt FROM tblmemberaccounts WHERE DATE(TransactionDate) BETWEEN ? AND ? AND TransactionTypeID = ?");
    $stmt->bind_param("ssi", $from, $to, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo "Type $type sum: " . ($row['sum_amt'] ?? 0) . "\n";
}

// Check some sample dates
$stmt = $conn->prepare("SELECT TransactionDate, TransactionTypeID, Amount FROM tblmemberaccounts ORDER BY TransactionDate DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
echo "Recent transactions:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['TransactionDate'] . " - Type: " . $row['TransactionTypeID'] . " - Amount: " . $row['Amount'] . "\n";
}
?>
<?php
require_once '../scripts/connection.php';

// Safely extract POST data
$ii = $_POST['single'] ?? [];
$d1 = $_POST['date1'] ?? '';
$d2 = $_POST['date2'] ?? '';

// Ensure date range is not empty
if (empty($d1) || empty($d2)) {
    die('Date range is required.');
}

// Handle input array
$mntharray = array_map('intval', $ii); // sanitize to integers
$memberList = implode(',', $mntharray);

// Build WHERE clause
if (in_array("all", $ii)) {
    $choose = "`transaction_type1_view` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
} else {
    $choose = "`transaction_type1_view` WHERE MemberNo IN ($memberList) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Important: wrap `Amount Paid` in backticks if it was used in the view
    $sql = "SELECT MemberNo, Name, Surname, DOB, Gender, TransactionDate, `Amount Paid` AS AmountPaid FROM $choose";
    $query = $conn->query($sql);

    if ($query && $query->num_rows > 0) {
        $delimiter = ",";
        $filename = "Capital Introduction Report " . date('Y-m-d') . ".csv";

        // open memory stream
        $f = fopen('php://memory', 'w');

        // Set CSV headers
        $headers = ["Member No", "Name", "Surname", "DOB", "Gender", "Transaction Date", "Amount Paid"];
        fputcsv($f, $headers, $delimiter);

        // Loop through query results and write to file
        while ($row = $query->fetch_assoc()) {
            $line = [
                $row['MemberNo'],
                $row['Name'],
                $row['Surname'],
                $row['DOB'],
                $row['Gender'],
                $row['TransactionDate'],
                number_format($row['AmountPaid'], 2)
            ];
            fputcsv($f, $line, $delimiter);
        }

        // Reset pointer and output the CSV
        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        fpassthru($f);
        exit;
    } else {
        die("No results found.");
    }
}
?>

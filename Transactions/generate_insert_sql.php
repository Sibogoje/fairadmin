<?php
/**
 * Create a complete SQL script from the CSV file
 * This file will be executed directly in the database
 */

$csv_file = __DIR__ . '/../JAN 2026 ADHOCS.csv';

// Read CSV
$rows = [];
if (($handle = fopen($csv_file, 'r')) !== FALSE) {
    $header = fgetcsv($handle, 2000, ',');
    while (($data = fgetcsv($handle, 2000, ',')) !== FALSE) {
        if (!empty($data[0])) {
            $rows[] = $data;
        }
    }
    fclose($handle);
}

echo "Read " . count($rows) . " rows from CSV\n";

// Start SQL script
$sql = "-- =====================================================\n";
$sql .= "-- INSERT ADHOC TRANSACTIONS - JAN 2026\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Total Records: " . count($rows) . "\n";
$sql .= "-- =====================================================\n\n";

// Step 1: Create temp table and insert data
$sql .= "BEGIN;\n\n";
$sql .= "-- Step 1: Insert CSV data\n";
$sql .= "INSERT INTO `tbltempadhocpayments` (\n";
$sql .= "  `MemberID`,\n";
$sql .= "  `Name`,\n";
$sql .= "  `PaymentDate`,\n";
$sql .= "  `Details`,\n";
$sql .= "  `AdHocPayment`,\n";
$sql .= "  `Comments`\n";
$sql .= ")\n";
$sql .= "SELECT \n";
$sql .= "  tm.memberID,\n";
$sql .= "  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,\n";
$sql .= "  STR_TO_DATE(tcsv.TransactionDate, '%d/%m/%Y') AS PaymentDate,\n";
$sql .= "  'Ad Hoc Payment' AS Details,\n";
$sql .= "  CAST(REPLACE(REPLACE(TRIM(tcsv.Amount), ' ', ''), ',', '') AS DECIMAL(12,2)) AS AdHocPayment,\n";
$sql .= "  tcsv.Comments\n";
$sql .= "FROM (\n";

// Build the CSV data as a VALUES clause
$values = [];
foreach ($rows as $row) {
    $memberNo = $row[0];
    $transDate = $row[1];
    $comments = addslashes($row[2]);
    $amount = $row[3];
    
    $values[] = "  ('{$memberNo}', '{$transDate}', '{$comments}', '{$amount}')";
}

$sql .= "  SELECT * FROM (VALUES \n";
$sql .= implode(",\n", $values) . "\n";
$sql .= "  ) AS tcsv(MemberNo, TransactionDate, Comments, Amount)\n";
$sql .= ") tcsv\n";
$sql .= "LEFT JOIN tblmembers tm ON CAST(tcsv.MemberNo AS UNSIGNED) = CAST(tm.MemberNo AS UNSIGNED)\n";
$sql .= "WHERE tm.memberID IS NOT NULL;\n\n";

// Step 2: Commit
$sql .= "COMMIT;\n\n";

// Step 3: Verify
$sql .= "-- Verification\n";
$sql .= "SELECT COUNT(*) AS total_inserted FROM `tbltempadhocpayments` WHERE PaymentDate >= '2026-01-01';\n";

// Output to file
$output_file = 'insert_adhocs_complete_' . date('YmdHis') . '.sql';
$bytes = file_put_contents($output_file, $sql);

echo "SQL script generated!\n";
echo "File: $output_file\n";
echo "Size: $bytes bytes\n";
echo "\nTo execute:\n";
echo "1. Open your database client (Workbench, Navicat, DBeaver, etc.)\n";
echo "2. Open the file: $output_file\n";
echo "3. Execute the script\n";

?>

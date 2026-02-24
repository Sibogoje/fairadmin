<?php
/**
 * CREATE DIRECT INSERT STATEMENTS FOR ADHOCS
 * Read CSV and generate individual INSERT statements
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

echo "Processing " . count($rows) . " rows from CSV...\n\n";

// Output SQL
$output = "-- =====================================================\n";
$output .= "-- INSERT ADHOC TRANSACTIONS - JAN 2026\n";
$output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$output .= "-- Total Records: " . count($rows) . "\n";
$output .= "-- =====================================================\n\n";

$output .= "START TRANSACTION;\n\n";

// Generate INSERT statements for each row
$insert_count = 0;
foreach ($rows as $idx => $row) {
    $memberNo = $row[0];
    $transDate = $row[1];
    $comments = addslashes($row[2]);
    $amount = $row[3];
    
    $output .= "INSERT INTO `tbltempadhocpayments` (\n";
    $output .= "  `MemberID`,\n";
    $output .= "  `Name`,\n";
    $output .= "  `PaymentDate`,\n";
    $output .= "  `Details`,\n";
    $output .= "  `AdHocPayment`,\n";
    $output .= "  `Comments`\n";
    $output .= ")\n";
    $output .= "SELECT \n";
    $output .= "  tm.memberID,\n";
    $output .= "  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,\n";
    $output .= "  STR_TO_DATE('{$transDate}', '%d/%m/%Y'),\n";
    $output .= "  'Ad Hoc Payment',\n";
    $output .= "  CAST(REPLACE(REPLACE('{$amount}', ' ', ''), ',', '') AS DECIMAL(12,2)),\n";
    $output .= "  '{$comments}'\n";
    $output .= "FROM tblmembers tm \n";
    $output .= "WHERE tm.MemberNo = '{$memberNo}' \n";
    $output .= "LIMIT 1;\n\n";
    
    $insert_count++;
}

$output .= "COMMIT;\n\n";

$output .= "-- Verification\n";
$output .= "SELECT COUNT(*) AS adhoc_records_inserted \n";
$output .= "FROM `tbltempadhocpayments` \n";
$output .= "WHERE PaymentDate >= '2026-01-01';\n";

// Write to file
$sql_file = __DIR__ . '/adhocs_' . date('YmdHis') . '.sql';
file_put_contents($sql_file, $output);

echo "SQL script created successfully!\n";
echo "File: " . basename($sql_file) . "\n";
echo "Location: " . $sql_file . "\n";
echo "Records to insert: $insert_count\n\n";

// Show first few lines
$lines = explode("\n", $output);
echo "First 30 lines:\n";
echo implode("\n", array_slice($lines, 0, 30)) . "\n\n...";

?>

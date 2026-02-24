<?php
/**
 * Generate SQL INSERT statements from adhocs CSV
 * This script reads the CSV and outputs a complete SQL file
 */

$csv_file = 'JAN 2026 ADHOCS.csv';

// Read CSV file
$rows = [];
if (($handle = fopen($csv_file, 'r')) !== FALSE) {
    $header = fgetcsv($handle, 1000, ',');
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $rows[] = $data;
    }
    fclose($handle);
}

// Generate SQL script
$sql = "-- =====================================================\n";
$sql .= "-- INSERT ADHOC TRANSACTIONS FROM JAN 2026\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- =====================================================\n\n";

// Step 1: Create temp table
$sql .= "-- Step 1: Create temporary table for CSV data\n";
$sql .= "CREATE TEMPORARY TABLE IF NOT EXISTS temp_adhocs (\n";
$sql .= "  MemberNo VARCHAR(20),\n";
$sql .= "  TransactionDate VARCHAR(20),\n";
$sql .= "  Comments VARCHAR(500),\n";
$sql .= "  Amount VARCHAR(50)\n";
$sql .= ");\n\n";

// Step 2: Insert temp data
$sql .= "-- Step 2: Insert CSV data into temp table\n";
$sql .= "INSERT INTO temp_adhocs (MemberNo, TransactionDate, Comments, Amount) VALUES\n";

$insert_values = [];
foreach ($rows as $index => $row) {
    $memberNo = trim($row[0]);
    $transDate = trim($row[1]);
    $comments = addslashes(trim($row[2]));
    $amount = trim($row[3]);
    
    // Skip empty rows or invalid data
    if (empty($memberNo) || empty($transDate) || empty($amount)) {
        continue;
    }
    
    $insert_values[] = "('$memberNo', '$transDate', '$comments', '$amount')";
}

$sql .= implode(",\n", $insert_values) . ";\n\n";

// Step 3: Insert main adhoc transactions
$sql .= "-- Step 3: Insert main adhoc transactions (TransactionTypeID = 4)\n";
$sql .= "INSERT INTO `tblmemberaccounts` (\n";
$sql .= "  `TransactionDate`,\n";
$sql .= "  `TransactionTypeID`,\n";
$sql .= "  `memberID`,\n";
$sql .= "  `Details`,\n";
$sql .= "  `Credit`,\n";
$sql .= "  `StartingBalance`,\n";
$sql .= "  `Amount`,\n";
$sql .= "  `NewBalance`,\n";
$sql .= "  `Comments`\n";
$sql .= ")\n";
$sql .= "SELECT \n";
$sql .= "  STR_TO_DATE(ta.TransactionDate, '%d/%m/%Y') AS TransactionDate,\n";
$sql .= "  4 AS TransactionTypeID,\n";
$sql .= "  tm.memberID,\n";
$sql .= "  'Ad Hoc Payment' AS Details,\n";
$sql .= "  0 AS Credit,\n";
$sql .= "  COALESCE(bal.NewBalance, 0) AS StartingBalance,\n";
$sql .= "  CAST(REPLACE(REPLACE(TRIM(ta.Amount), ' ', ''), ',', '') AS DECIMAL(12,2)) AS Amount,\n";
$sql .= "  COALESCE(bal.NewBalance, 0) - CAST(REPLACE(REPLACE(TRIM(ta.Amount), ' ', ''), ',', '') AS DECIMAL(12,2)) AS NewBalance,\n";
$sql .= "  ta.Comments\n";
$sql .= "FROM \n";
$sql .= "  temp_adhocs ta\n";
$sql .= "LEFT JOIN \n";
$sql .= "  `tblmembers` tm ON CAST(ta.MemberNo AS UNSIGNED) = CAST(tm.MemberNo AS UNSIGNED)\n";
$sql .= "LEFT JOIN \n";
$sql .= "  `balances` bal ON tm.memberID = bal.memberID\n";
$sql .= "WHERE \n";
$sql .= "  tm.memberID IS NOT NULL;\n\n";

// Step 4: Insert transaction fees
$sql .= "-- Step 4: Insert transaction fee records (TransactionTypeID = 5, Fee = 2%)\n";
$sql .= "INSERT INTO `tblmemberaccounts` (\n";
$sql .= "  `TransactionDate`,\n";
$sql .= "  `TransactionTypeID`,\n";
$sql .= "  `memberID`,\n";
$sql .= "  `Details`,\n";
$sql .= "  `Credit`,\n";
$sql .= "  `StartingBalance`,\n";
$sql .= "  `Amount`,\n";
$sql .= "  `NewBalance`,\n";
$sql .= "  `Comments`\n";
$sql .= ")\n";
$sql .= "SELECT \n";
$sql .= "  tma.`TransactionDate`,\n";
$sql .= "  5 AS TransactionTypeID,\n";
$sql .= "  tma.`memberID`,\n";
$sql .= "  'Ad Hoc Transaction Fee' AS Details,\n";
$sql .= "  0 AS Credit,\n";
$sql .= "  tma.`NewBalance` AS StartingBalance,\n";
$sql .= "  ROUND((tma.`Amount` * 0.02), 2) AS FeeAmount,\n";
$sql .= "  ROUND(tma.`NewBalance` - (tma.`Amount` * 0.02), 2) AS NewBalance,\n";
$sql .= "  CONCAT('Fee for: ', tma.`Comments`)\n";
$sql .= "FROM \n";
$sql .= "  `tblmemberaccounts` tma\n";
$sql .= "WHERE \n";
$sql .= "  tma.`TransactionTypeID` = 4\n";
$sql .= "  AND DATE(tma.`TransactionDate`) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)\n";
$sql .= "  AND NOT EXISTS (\n";
$sql .= "    SELECT 1 FROM `tblmemberaccounts` tma2 \n";
$sql .= "    WHERE tma2.`TransactionTypeID` = 5 \n";
$sql .= "    AND tma2.`memberID` = tma.`memberID`\n";
$sql .= "    AND DATE(tma2.`TransactionDate`) = DATE(tma.`TransactionDate`)\n";
$sql .= "  );\n\n";

// Step 5: Verification
$sql .= "-- Step 5: Verify data was inserted\n";
$sql .= "SELECT \n";
$sql .= "  COUNT(*) as total_transactions,\n";
$sql .= "  SUM(CASE WHEN TransactionTypeID = 4 THEN 1 ELSE 0 END) AS adhoc_count,\n";
$sql .= "  SUM(CASE WHEN TransactionTypeID = 5 THEN 1 ELSE 0 END) AS fee_count,\n";
$sql .= "  SUM(Amount) AS total_amount\n";
$sql .= "FROM `tblmemberaccounts` \n";
$sql .= "WHERE TransactionTypeID IN (4, 5)\n";
$sql .= "AND DATE(TransactionDate) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY);\n";

// Output to file
$output_file = 'insert_adhocs_' . date('YmdHis') . '.sql';
file_put_contents($output_file, $sql);

echo "SQL script generated successfully!\n";
echo "File: $output_file\n";
echo "Total rows processed: " . count($insert_values) . "\n";
echo "\nYou can now:\n";
echo "1. Open the SQL file in your database client\n";
echo "2. Copy and paste the contents into your query editor\n";
echo "3. Execute the script\n";
?>

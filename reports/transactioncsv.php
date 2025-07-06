<?php
require_once '../scripts/connection.php';

$ii = $_POST['single'];
$d1 = $_POST['date1'];
$d2 = $_POST['date2'];

foreach ($ii as $a) {
    $mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 = str_replace(array('[', ']'), '', $mntharray1);

if (in_array("all", $mntharray)) {
    $choose = "`term_report2` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
} else {
    $choose = "`term_report2` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
}

if (count($_POST) > 0) {
    $query = $conn->query("SELECT 
        MemberNo, 
        MemberSurname, 
        MemberFirstname, 
        TerminationDate, 
        TransactionDate, 
        Details, 
        Comments, 
        StartingBalance, 
        Amount, 
        NewBalance 
    FROM " . $choose);

    if ($query->num_rows > 0) {
        $delimiter = ",";
        $filename = "Terminations_Report_" . date('Y-m-d') . ".csv";

        // Create a file pointer
        $f = fopen('php://memory', 'w');

        // Set column headers
        $fields = array('MemberNo', 'MemberSurname', 'MemberFirstname', 'TerminationDate', 'TransactionDate', 'Details', 'Comments', 'StartingBalance', 'Amount', 'NewBalance');
        fputcsv($f, $fields, $delimiter);

        // Output each row of the data, format line as CSV and write to file pointer
        while ($row = $query->fetch_assoc()) {
            $lineData = array($row['MemberNo'], $row['MemberSurname'], $row['MemberFirstname'], $row['TerminationDate'], $row['TransactionDate'], $row['Details'], $row['Comments'], $row['StartingBalance'], $row['Amount'], $row['NewBalance']);
            fputcsv($f, $lineData, $delimiter);
        }

        // Move back to the beginning of the file
        fseek($f, 0);

        // Set headers to download file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Output all remaining data on the file pointer
        fpassthru($f);
    }
    exit;
}
?>

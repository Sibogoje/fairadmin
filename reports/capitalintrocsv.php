<?php
require_once '../scripts/connection.php';
$ii      = $_POST['single'];
$d1      = $_POST['date1'];
$d2      = $_POST['date2'];
$mntharray = [];
foreach ($ii as $a) {
    $mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 = str_replace(['[',']'], '', $mntharray1);

// build choose clause for transaction_type1_view
if (in_array("all", $mntharray)) {
    $choose = "`transaction_type1_view` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
} else {
    $choose = "`transaction_type1_view` WHERE MemberNo IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
}

if (count($_POST) > 0) {
    // Select the new columns
    $sql = "SELECT MemberNo, Name, Surname, DOB, Gender, TransactionDate, AmountPaid FROM $choose";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $delimiter = ",";
        $filename  = "Capital Introduction Report " . date('Y-m-d') . ".csv";

        $f = fopen('php://memory', 'w');
        // Set column headers to match new fields
        $fields = ["Member No","Name","Surname","DOB","Gender","Transaction Date","Amount Paid"];
        fputcsv($f, $fields, $delimiter);

        // Output each row
        while ($row = $query->fetch_assoc()) {
            $lineData = [
                $row['MemberNo'],
                $row['Name'],
                $row['Surname'],
                $row['DOB'],
                $row['Gender'],
                $row['TransactionDate'],
                number_format($row['AmountPaid'], 2)
            ];
            fputcsv($f, $lineData, $delimiter);
        }

        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        fpassthru($f);
    }
    exit;
}
?>
    
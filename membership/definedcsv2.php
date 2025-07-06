<?php
require_once '../scripts/connection.php';

$single = $_POST['single'];
$d1 = $_POST['date1'];
$d2 = $_POST['date2'];

if ($single == 'all') {
    $stmt = $conn->prepare("
        SELECT 
            m.`MemberNo`,
            m.`membersurname`,
            m.`memberfirstname`,
            m.`DateOfBirth`,
            m.`gender`,
            m.`DateAccountOpened`,
            a.newbalance
        FROM 
            `u747325399_fair2`.`tblmembers` m
        JOIN (
            SELECT 
                t.*,
                ROW_NUMBER() OVER (PARTITION BY t.`memberID` ORDER BY t.`accountsID` DESC) AS row_num
            FROM 
                `u747325399_fair2`.`tblmemberaccounts` t
            WHERE 
                t.`TransactionDate` BETWEEN ? AND ? -- Adjust date range here
        ) a ON m.`memberID` = a.`memberID` AND a.row_num = 1
    ");

    $stmt->bind_param("ss", $d1, $d2);
} else if ($single != 'all') {
    $stmt = $conn->prepare("
        SELECT 
            m.`MemberNo`,
            m.`membersurname`,
            m.`memberfirstname`,
            m.`DateOfBirth`,
            m.`gender`,
            m.`DateAccountOpened`,
            a.newbalance
        FROM 
            `u747325399_fair2`.`tblmembers` m
        JOIN (
            SELECT 
                t.*,
                ROW_NUMBER() OVER (PARTITION BY t.`memberID` ORDER BY t.`accountsID` DESC) AS row_num
            FROM 
                `u747325399_fair2`.`tblmemberaccounts` t
            WHERE 
                t.`TransactionDate` BETWEEN ? AND ? 
                AND t.memberid = ?
        ) a ON m.`memberID` = a.`memberID` AND a.row_num = 1
    ");

    $stmt->bind_param("sss", $d1, $d2, $single);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $delimiter = ",";
    $filename = $single . " Transaction History " . date('Y-m-d') . ".csv";

    // Create a file pointer
    $f = fopen('php://memory', 'w');

    // Set column headers
    $fields = array('MemberNo', 'Surname', 'Firstname', 'DateOfBirth', 'Gender', 'DateAccountOpened', 'NewBalance');
    fputcsv($f, $fields, $delimiter);

    // Output each row of the data, format line as CSV and write to file pointer
    while ($row = $result->fetch_assoc()) {
        $lineData = array(
            $row['MemberNo'],
            $row['membersurname'],
            $row['memberfirstname'],
            $row['DateOfBirth'],
            $row['gender'],
            $row['DateAccountOpened'],
            number_format($row['newbalance'], 2)
        );
        fputcsv($f, $lineData, $delimiter);
    }

    // Move back to the beginning of the file
    fseek($f, 0);

    // Set headers to download file rather than display
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    // Output all remaining data on the file pointer
    fpassthru($f);
}

$stmt->close();
$conn->close();
?>

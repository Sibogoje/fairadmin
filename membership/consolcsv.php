<?php
require_once '../scripts/connection.php';

$ff = $_POST['single'] ?? '';
$d1 = $_POST['from'] ?? '';
$d2 = $_POST['to'] ?? '';

if (count($_POST) > 0) {
    $query = $conn->prepare("
        SELECT m.MemberID, m.MemberNo, m.MemberSurname, m.MemberFirstname, 
               m.Gender, m.DateOfBirth, m.ApprovedBenefit, m.DateAccountOpened
        FROM tblmembers AS m
        JOIN tbldeceased AS d ON m.DeceasedID = d.DeceasedID
        JOIN tblmemberaccounts AS ma ON m.MemberID = ma.memberID
        WHERE ma.TransactionDate BETWEEN ? AND ? AND d.RetirementFundID = ?
        GROUP BY m.MemberID
    ");
    $query->bind_param("sss", $d1, $d2, $ff);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $delimiter = ",";
        $filename = "Per Fund Report " . date('Y-m-d') . ".csv";
        $f = fopen('php://memory', 'w');

        // CSV column headers
        $headers = ["Member No", "Surname", "Name", "Gender", "D.O.B", "Acc Opened", "Amount Appr.", "Income", "Expenses", "Payments", "Other Transactions", "Balance"];
        fputcsv($f, $headers, $delimiter);

        while ($row = $result->fetch_assoc()) {
            $ii = $row['MemberID'];

            // Defaults
            $income = $expenses = $payments = $other = $balance = 0.00;

            // Get balance
            $stmtBal = $conn->prepare("SELECT NewBalance FROM balances WHERE memberID = ?");
            $stmtBal->bind_param("i", $ii);
            $stmtBal->execute();
            $resBal = $stmtBal->get_result();
            if ($resBal->num_rows > 0) {
                $balance = $resBal->fetch_assoc()['NewBalance'] ?? 0.00;
            }

            // Income (TransactionTypeID = 8)
            $income = getSumByType($conn, $ii, $d1, $d2, [8]);

            // Expenses (2,5,6,7)
            $expenses = getSumByType($conn, $ii, $d1, $d2, [2, 5, 6, 7]);

            // Payments (3,4)
            $payments = getSumByType($conn, $ii, $d1, $d2, [3, 4]);

            // Other (10)
            $other = getSumByType($conn, $ii, $d1, $d2, [10]);

            // Compile row
            $lineData = [
                $row['MemberNo'],
                $row['MemberSurname'],
                $row['MemberFirstname'],
                $row['Gender'],
                $row['DateOfBirth'],
                $row['DateAccountOpened'],
                number_format($row['ApprovedBenefit'] ?? 0, 2),
                number_format($income, 2),
                number_format($expenses, 2),
                number_format($payments, 2),
                number_format($other, 2),
                number_format($balance, 2)
            ];

            fputcsv($f, $lineData, $delimiter);
        }

        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        fpassthru($f);
    } else {
        echo "No results found.";
    }
    exit;
}

// Helper to get sum for transaction types
function getSumByType($conn, $memberID, $d1, $d2, $types) {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $types_str = str_repeat("i", count($types));
    $params = array_merge([$memberID, $d1, $d2], $types);

    $sql = "SELECT SUM(Amount) AS total FROM tblmemberaccounts 
            WHERE memberID = ? AND TransactionDate BETWEEN ? AND ? 
            AND TransactionTypeID IN ($placeholders)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) return 0;

    // Bind params dynamically
    $stmt->bind_param("iss" . $types_str, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc()['total'] ?? 0.00;
}
?>

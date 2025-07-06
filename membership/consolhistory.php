<?php
require_once '../scripts/connection.php';

$ff = $_POST['c_id'] ?? '';
$d1 = $_POST['from'] ?? '';
$d2 = $_POST['to'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmtz = $conn->prepare("
        SELECT
            m.MemberID,
            m.MemberNo,
            m.MemberSurname,
            m.MemberFirstname,
            m.MemberIDnumber,
            m.DeceasedID,
            m.Gender,
            m.DateOfBirth,
            m.ApprovedBenefit,
            m.DateAccountOpened,
            m.Terminated,
            m.MemberPostalAddress,
            d.RetirementFundID
        FROM tblmembers AS m
        JOIN tbldeceased AS d ON m.DeceasedID = d.DeceasedID
        JOIN tblmemberaccounts AS ma ON m.MemberID = ma.memberID
        WHERE ma.TransactionDate BETWEEN ? AND ?
          AND d.RetirementFundID = ?
        GROUP BY m.MemberID
    ");
    $stmtz->bind_param("ssi", $d1, $d2, $ff);
    $stmtz->execute();
    $resultz = $stmtz->get_result();

    if ($resultz->num_rows > 0) {
        while ($rowz = $resultz->fetch_assoc()) {
            $ii = $rowz['MemberID'];

            // --- Start HTML output per member
            ?>
            <div class="table-responsive">
                <table class="table datatable" id="free">
                    <thead>
                        <tr style="text-align:center; background:black; color:white;">
                            <th colspan="11"><?php echo htmlspecialchars($rowz['MemberFirstname'] . " " . $rowz['MemberSurname']); ?></th>
                        </tr>
                        <tr>
                            <th>Member No.</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Date of Joining</th>
                            <th>Initial Amount</th>
                            <th>Interest</th>
                            <th>Expenses</th>
                            <th>Payments</th>
                            <th>Latest Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get Financials
                        $initAmt = getAmount($conn, "SELECT StartingBalance FROM tblmemberaccounts WHERE memberID=? AND TransactionDate>=? ORDER BY TransactionDate ASC LIMIT 1", "is", [$ii, $d1], 'StartingBalance');
                        $interest = getAmount($conn, "SELECT SUM(Amount) AS sumAmt FROM tblmemberaccounts WHERE memberID=? AND TransactionTypeID=8 AND TransactionDate BETWEEN ? AND ?", "iss", [$ii, $d1, $d2], 'sumAmt');
                        $expenses = getAmount($conn, "SELECT SUM(Amount) AS sumAmt FROM tblmemberaccounts WHERE memberID=? AND TransactionTypeID IN (2,5,6,7) AND TransactionDate BETWEEN ? AND ?", "iss", [$ii, $d1, $d2], 'sumAmt');
                        $payments = getAmount($conn, "SELECT SUM(Amount) AS sumAmt FROM tblmemberaccounts WHERE memberID=? AND TransactionTypeID IN (3,4) AND TransactionDate BETWEEN ? AND ?", "iss", [$ii, $d1, $d2], 'sumAmt');
                        $latestBal = getAmount($conn, "SELECT NewBalance FROM tblmemberaccounts WHERE memberID=? AND TransactionDate<=? ORDER BY TransactionDate DESC LIMIT 1", "is", [$ii, $d2], 'NewBalance');
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rowz['MemberNo']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['MemberFirstname']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['MemberSurname']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['DateOfBirth']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['Gender']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['DateAccountOpened']); ?></td>
                            <td><?php echo number_format($rowz['ApprovedBenefit'] ?? 0, 2); ?></td>
                            <td><?php echo number_format($interest, 2); ?></td>
                            <td><?php echo number_format($expenses, 2); ?></td>
                            <td><?php echo number_format($payments, 2); ?></td>
                            <td><?php echo number_format($latestBal, 2); ?></td>
                        </tr>

                        <!-- Extra Sections: Expenses, Payments, Others -->
                        <?php
                        $expTotal = getAmount($conn, "SELECT SUM(Amount) AS TT3 FROM tblmemberaccounts WHERE TransactionTypeID IN (3,4) AND TransactionDate BETWEEN ? AND ? AND memberID = ?", "ssi", [$d1, $d2, $ii], 'TT3');
                        $otherTotal = getAmount($conn, "SELECT SUM(Amount) AS TT3 FROM tblmemberaccounts WHERE TransactionTypeID = 10 AND TransactionDate BETWEEN ? AND ? AND memberID = ?", "ssi", [$d1, $d2, $ii], 'TT3');
                        ?>
                        <tr>
                            <th colspan="6" style="text-align:right;">Payments Total:</th>
                            <td colspan="5"><?php echo "- E " . number_format($expTotal, 2); ?></td>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align:right;">Other Transactions:</th>
                            <td colspan="5"><?php echo "E " . number_format($otherTotal, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        } // end while
    } else {
        echo "0 results found.";
    }
} else {
    header("Location: ./");
    exit;
}

// Helper function to run and fetch a single value
function getAmount($conn, $sql, $types, $params, $field)
{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        return $res->fetch_assoc()[$field] ?? 0;
    }
    return 0;
}
?>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

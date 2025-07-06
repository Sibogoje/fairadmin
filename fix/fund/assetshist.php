<?php
require_once '../scripts/connection.php';

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

$ii = $_POST['single'];
$d1 = $_POST['date1'];
$d2 = $_POST['date2'];

$choose = "
assets mbr JOIN (
    SELECT memberID, MAX(accountsID) AS maxAccountsID
    FROM tblmemberaccounts
    WHERE TransactionDate BETWEEN '$d1' AND '$d2'
    GROUP BY memberID
) max_acc ON mbr.MemberID = max_acc.memberID
JOIN tblmemberaccounts acc ON acc.accountsID = max_acc.maxAccountsID
WHERE mbr.RetirementFundID = '$ii'
ORDER BY mbr.MemberID
";

if (count($_POST) > 0) {
    $stmt12 = $conn->prepare("SELECT SUM(acc.NewBalance) AS TSUM
    FROM " . $choose);

    if (!$stmt12) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt12->execute();
    $result12 = $stmt12->get_result();

    if ($result12->num_rows > 0) {
        $totalb = 0;
        while ($row12 = $result12->fetch_assoc()) {
            ?>
            <h5 class="card-title">Total Assets E <?php echo number_format($row12['TSUM'], 2); ?> </h5>
            <?php
        }
    } else {
        echo "NO Members found";
    }
} else {
    header('location: ./');
}

if (count($_POST) > 0) {
    $stmt12 = $conn->prepare("SELECT mbr.MemberNo, mbr.MemberSurname, mbr.MemberFirstname, mbr.Gender, mbr.DateOfBirth, mbr.RetirementFundID, mbr.ApprovedBenefit, acc.NewBalance
    FROM " . $choose);
    $stmt12->execute();
    $result12 = $stmt12->get_result();

    if ($result12->num_rows > 0) {
        $totalb = 0;
        ?>
        <!-- Table with stripped rows -->
        <div class="table-responsive">
            <table class="table table-striped datatable nowrap" id="free" style="width: 100%;">
                <thead>
                    <tr>
                        <th scope="col">M. No</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Name</th>
                        <th scope="col">Gender</th>
                        <th scope="col">DOB</th>
                        <th scope="col">A. Benefit</th>
                        <th scope="col">Balance</th>
                    </tr>
                </thead>
                <tbody id="gruu">
                    <?php
                    while ($row12 = $result12->fetch_assoc()) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo $row12['MemberNo']; ?></th>
                            <th scope="row"><?php echo $row12['MemberSurname']; ?></th>
                            <th scope="row"><?php echo $row12['MemberFirstname']; ?></th>
                            <th scope="row"><?php echo $row12['Gender']; ?></th>
                            <th scope="row"><?php echo $row12['DateOfBirth']; ?></th>
                            <th scope="row"><?php echo number_format($row12['ApprovedBenefit'], 2); ?></th>
                            <td><?php echo number_format($row12['NewBalance'], 2); ?></td>
                        </tr>
                        <?php
                        $totalb = $totalb + $row12['NewBalance'];
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo "NO Members found";
    }
    ?>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <?php
} else {
    header('location: ./');
}
?>
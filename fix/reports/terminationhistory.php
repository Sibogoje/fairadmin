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
    $stmt12 = $conn->prepare("SELECT MemberNo, MemberFirstname, MemberSurname, TerminationDate, TransactionDate, Details, Comments, StartingBalance, Amount, NewBalance FROM " . $choose);
    $stmt12->execute();
    $result12 = $stmt12->get_result();
    if ($result12->num_rows > 0) {
?>
        <div class="table-responsive">
            <table class="table datatable responsive" id="free">
                <thead>
                    <tr>
                        <th scope="col">Member No</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Termination Date</th>
                        <th scope="col">Transaction Date</th>
                        <th scope="col">Details</th>
                        <th scope="col">Comments</th>
                        <th scope="col">Opening Balance</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Closing Balance</th>
                    </tr>
                </thead>
                <tbody>
<?php
        while ($row12 = $result12->fetch_assoc()) {
?>
                    <tr>
                        <td><?php echo $row12['MemberNo']; ?></td>
                        <td><?php echo $row12['MemberFirstname'] . " " . $row12['MemberSurname']; ?></td>
                        <td><?php echo $row12['TerminationDate']; ?></td>
                        <td><?php echo $row12['TransactionDate']; ?></td>
                        <td><?php echo $row12['Details']; ?></td>
                        <td><?php echo $row12['Comments']; ?></td>
                        <td><?php echo number_format($row12['StartingBalance'], 2); ?></td>
                        <td><?php echo number_format($row12['Amount'], 2); ?></td>
                        <td><?php echo number_format($row12['NewBalance'], 2); ?></td>
                    </tr>
<?php
        }
?>
                </tbody>
            </table>
        </div>
        <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<?php
    } else {
        echo "0 results";
    }
} else {
    header('location: ./');
}
?>

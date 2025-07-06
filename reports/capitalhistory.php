<?php
require_once '../scripts/connection.php';

$ii = $_POST['single'] ?? [];
$d1 = $_POST['date1'] ?? '';
$d2 = $_POST['date2'] ?? '';

$mntharray = [];
foreach ($ii as $a) {
    $mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 = str_replace(['[', ']'], '', $mntharray1);

if (in_array("all", $mntharray)) {
    $choose = "`transaction_type1_view` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
} else {
    $choose = "`transaction_type1_view` WHERE MemberNo IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT MemberNo, Name, Surname, DOB, Gender, TransactionDate, `Amount Paid` AS AmountPaid FROM $choose";
    $stmt12 = $conn->prepare($sql);

    if ($stmt12 && $stmt12->execute()) {
        $result12 = $stmt12->get_result();

        if ($result12->num_rows > 0) {
?>
<div class="table-responsive">
    <table class="table datatable" id="free">
        <thead>
            <tr>
                <th>Member No</th>
                <th>Name</th>
                <th>Surname</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Transaction Date</th>
                <th>Amount Paid</th>
            </tr>
        </thead>
        <tbody>
<?php
            while ($row12 = $result12->fetch_assoc()) {
?>
            <tr>
                <td><?php echo htmlspecialchars($row12['MemberNo']); ?></td>
                <td><?php echo htmlspecialchars($row12['Name']); ?></td>
                <td><?php echo htmlspecialchars($row12['Surname']); ?></td>
                <td><?php echo htmlspecialchars($row12['DOB']); ?></td>
                <td><?php echo htmlspecialchars($row12['Gender']); ?></td>
                <td><?php echo htmlspecialchars($row12['TransactionDate']); ?></td>
                <td><?php echo number_format($row12['AmountPaid'], 2); ?></td>
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
        echo "Failed to execute query.";
    }
} else {
    header('Location: ./');
    exit();
}
?>

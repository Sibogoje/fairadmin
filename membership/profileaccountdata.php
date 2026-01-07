<?php
require_once '../scripts/connection.php';

if(count($_POST) > 0 && isset($_POST['c_id'])){
    $ii = $_POST['c_id'];
    
    // Use proper prepared statement with parameter binding
    $stmt = $conn->prepare("SELECT * FROM memberz2 WHERE MemberID = ?");
    $stmt->bind_param("i", $ii);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // DEBUG: log or print how many rows we got
    error_log("profileaccountdata: memberz2 rows for ID {$ii}: {$result->num_rows}");
    echo "<!-- memberz2 rows: {$result->num_rows} -->";

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
?>
<table class="table datatable" id="free">
    <thead>
        <tr>
            <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
        </tr>
        <tr style="text-align: center; background: black; color: white;">
            <th scope="col" colspan="6">MEMBER DETAILS</th>
        </tr>
        <tr>
            <th scope="col" style="vertical-align: top;">Full Name</th>
            <td scope="col"><?php echo htmlspecialchars($row['MemberFirstname']." ".$row['MemberSurname']); ?></td>
            <th scope="col" style="vertical-align: top;">MemberNo</th>
            <td scope="col"><?php echo htmlspecialchars($row['MemberNo']); ?></td>
            <th scope="col" style="vertical-align: top;">FundName</th>
            <td scope="col"><?php echo htmlspecialchars($row['FundName']); ?></td>
        </tr>
        
        <tr>
            <th scope="col" style="vertical-align: top;">Date of Birth</th>
            <td scope="col"><?php echo htmlspecialchars($row['DateOfBirth']); ?></td>
            <th scope="col" style="vertical-align: top;">Account Opened</th>
            <td scope="col"><?php echo htmlspecialchars($row['DateAccountOpened']); ?></td>
            <th scope="col" style="vertical-align: top;">Gender</th>
            <td scope="col"><?php echo htmlspecialchars($row['Gender']); ?></td>
        </tr>
        
        <tr>
            <th scope="col" style="vertical-align: top;">Approved Benefit</th>
            <td scope="col"><?php echo htmlspecialchars($row['ApprovedBenefit']); ?></td>
            <th scope="col" style="vertical-align: top;">Terminated</th>
            <td scope="col"><?php echo htmlspecialchars($row['Terminated']); ?></td>
            <th scope="col" style="vertical-align: top;">Balance</th>
            <?php
            // Fix SQL injection for balance query
            $stmt12 = $conn->prepare("SELECT `NewBalance` FROM `balances` WHERE `memberID` = ?");
            $stmt12->bind_param("i", $ii);
            $stmt12->execute();
            $result12 = $stmt12->get_result();
            if ($result12->num_rows > 0) {
                while($row12 = $result12->fetch_assoc()) {
            ?>
            <td scope="col" style="font-weight: bold;"><?php echo "E ". number_format($row12['NewBalance'], 2); ?></td>
            <?php
                }
            } else {
            ?>
            <td scope="col" style="font-weight: bold;">No data</td>
            <?php
            }
            ?>
        </tr>
        <tr style="text-align: center; background: black; color: white;">
            <th scope="col" colspan="6">Transaction Statement</th>
        </tr>
    </thead>
</table>

<?php
        }
    } else {
        // show a message if no member record was found
        echo "<p style='color:red;'>No member details found for ID {$ii}.</p>";
    }
    
    // Fix SQL injection for transaction query
    $stmt_trans = $conn->prepare("SELECT 
        `accountsID`,
        `TransactionDate`,
        `TransactionTypeID`,
        `memberID`,
        `Details`,
        `Credit`,
        `StartingBalance`,
        `Amount`,
        `NewBalance`,
        `Comments`  
        FROM `tblmemberaccounts` 
        WHERE `memberID` = ? 
        ORDER BY TransactionDate DESC, accountsID DESC");
    $stmt_trans->bind_param("i", $ii);
    $stmt_trans->execute();
    $result_trans = $stmt_trans->get_result();
    
    if ($result_trans->num_rows > 0) {
?>
<table class="table datatable" id="free">
    <thead>
        <tr>
            <th scope="col">TransactionDate</th>
            <th scope="col">Details</th>
            <th scope="col">Type</th>
            <th scope="col">Comments</th>
            <th scope="col">Prev balance</th>
            <th scope="col">Amount</th>
            <th scope="col">NewBalance</th>
        </tr>
    </thead>
    <tbody>
<?php
        while($row_trans = $result_trans->fetch_assoc()) {
            $tyes = ($row_trans['Credit'] == "1") ? "Credit" : "Debit";
?>
        <tr>
            <th scope="row"><?php echo htmlspecialchars($row_trans['TransactionDate']); ?></th>
            <td><?php echo htmlspecialchars($row_trans['Details']); ?></td>
            <td><?php echo htmlspecialchars($tyes); ?></td>
            <td><?php echo htmlspecialchars($row_trans['Comments']); ?></td>
            <td><?php echo number_format($row_trans['StartingBalance'], 2); ?></td>
            <td><?php echo number_format($row_trans['Amount'], 2); ?></td>
            <td><?php echo number_format($row_trans['NewBalance'], 2); ?></td>
        </tr>
<?php
        }
?>
    </tbody>
</table>
<?php
    } else {
        echo "0 results";
    }
?>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<?php
} else {
    header('location: ./');
    exit();
}
?>






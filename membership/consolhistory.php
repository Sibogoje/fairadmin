<?php
require_once '../scripts/connection.php';
$ff = $_POST['c_id'] ?? '';
$d1 = $_POST['from'] ?? '';
$d2 = $_POST['to'] ?? '';

if (count($_POST) > 0) {
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
    FROM
      `tblmembers` AS m
    JOIN
      `tbldeceased` AS d ON m.DeceasedID = d.DeceasedID
    JOIN
      `tblmemberaccounts` AS ma ON m.MemberID = ma.memberID
    WHERE
      ma.TransactionDate BETWEEN '$d1' AND '$d2'
      AND d.RetirementFundID = '$ff'
    GROUP BY
      m.MemberID;
     ");
    $stmtz->execute();
    $resultz = $stmtz->get_result();
    if ($resultz->num_rows > 0) {
        while ($rowz = $resultz->fetch_assoc()) {
            $ii = $rowz['MemberID'];
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
                        <tr>
                            <td><?php echo htmlspecialchars($rowz['MemberNo']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['MemberFirstname']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['MemberSurname']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['DateOfBirth']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['Gender']); ?></td>
                            <td><?php echo htmlspecialchars($rowz['DateAccountOpened']); ?></td>
                            <?php
                            // initial amount at period start
                            $stmtInit = $conn->prepare(
                                "SELECT StartingBalance 
                                   FROM tblmemberaccounts 
                                   WHERE memberID=? AND TransactionDate>=? 
                                   ORDER BY TransactionDate ASC LIMIT 1"
                            );
                            $stmtInit->bind_param("is", $ii, $d1);
                            $stmtInit->execute();
                            $rInit = $stmtInit->get_result();
                            $initAmt = $rInit->num_rows ? $rInit->fetch_assoc()['StartingBalance'] : 0;

                            // interest (TransactionTypeID=8)
                            $stmtInt = $conn->prepare(
                                "SELECT SUM(Amount) AS sumAmt 
                                   FROM tblmemberaccounts 
                                   WHERE memberID=? AND TransactionTypeID=8 
                                     AND TransactionDate BETWEEN ? AND ?"
                            );
                            $stmtInt->bind_param("iss", $ii, $d1, $d2);
                            $stmtInt->execute();
                            $rInt = $stmtInt->get_result();
                            $interest = $rInt->fetch_assoc()['sumAmt'] ?? 0;

                            // expenses (2,5,6,7)
                            $stmtExp = $conn->prepare(
                                "SELECT SUM(Amount) AS sumAmt 
                                   FROM tblmemberaccounts 
                                   WHERE memberID=? AND TransactionTypeID IN (2,5,6,7) 
                                     AND TransactionDate BETWEEN ? AND ?"
                            );
                            $stmtExp->bind_param("iss", $ii, $d1, $d2);
                            $stmtExp->execute();
                            $rExp = $stmtExp->get_result();
                            $expenses = $rExp->fetch_assoc()['sumAmt'] ?? 0;

                            // payments (3,4)
                            $stmtPay = $conn->prepare(
                                "SELECT SUM(Amount) AS sumAmt 
                                   FROM tblmemberaccounts 
                                   WHERE memberID=? AND TransactionTypeID IN (3,4) 
                                     AND TransactionDate BETWEEN ? AND ?"
                            );
                            $stmtPay->bind_param("iss", $ii, $d1, $d2);
                            $stmtPay->execute();
                            $rPay = $stmtPay->get_result();
                            $payments = $rPay->fetch_assoc()['sumAmt'] ?? 0;

                            // latest balance at period end
                            $stmtBal = $conn->prepare(
                                "SELECT NewBalance 
                                   FROM tblmemberaccounts 
                                   WHERE memberID=? AND TransactionDate<=? 
                                   ORDER BY TransactionDate DESC LIMIT 1"
                            );
                            $stmtBal->bind_param("is", $ii, $d2);
                            $stmtBal->execute();
                            $rBal = $stmtBal->get_result();
                            $latestBal = $rBal->num_rows ? $rBal->fetch_assoc()['NewBalance'] : 0;
                            ?>
                            <td><?php echo number_format($initAmt, 2); ?></td>
                            <td><?php echo number_format($interest, 2); ?></td>
                            <td><?php echo number_format($expenses, 2); ?></td>
                            <td><?php echo number_format($payments, 2); ?></td>
                            <td><?php echo number_format($latestBal, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
<?php
        }  // end while $rowz
    } else {
        echo "0 results";
    }
} else {
    header('location: ./');
    exit();
}
?>
                  
                   
                    <tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Expenses/Costs</th>
                    <td scope="col" colspan="3" style="text-align: right;"><?php echo "- E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>

 <?php	}
	?>

<?php	} else {
echo "0 results";	}    
 
 
 
 
     
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('3', '4') 
AND (`TransactionDate` BETWEEN '$d1' AND '$d2') AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
	
                  
                   
                    <tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Payments</th>
                    <td scope="col" colspan="3" style="text-align: right;"><?php echo "- E ".number_format($row12['TT3'], 2); ?></td>
                    </tr>

 <?php	}
	?>

<?php	} else {
echo "0 results";	}    
 
 
     
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '10' AND (`TransactionDate` BETWEEN '$d1' AND '$d2') AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
	
                   
                    <tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Other Transactions</th>
                    <td scope="col" colspan="3" style="text-align: right;"><?php echo "E ".number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
<?php	} else {
echo "0 results";	}    
 
?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						




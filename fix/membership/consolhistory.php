
<?php
require_once '../scripts/connection.php';
$ff = $_POST['c_id'];
$d1=$_POST['from'];
$d2=$_POST['to'];


if(count($_POST)>0){
    
    
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
  `u747325399_fair2`.`tblmembers` AS m
JOIN
  `u747325399_fair2`.`tbldeceased` AS d ON m.DeceasedID = d.DeceasedID
JOIN
  `u747325399_fair2`.`tblmemberaccounts` AS ma ON m.MemberID = ma.memberID
WHERE
  ma.TransactionDate BETWEEN '$d1' AND '$d2'
  AND d.RetirementFundID = '$ff'
GROUP BY
  m.MemberID;
 ");
						$stmtz->execute();
						$resultz = $stmtz->get_result();
						if ($resultz->num_rows > 0) {
						    while($rowz = $resultz->fetch_assoc()) {   
    
    $ii = $rowz['MemberID'];
        

						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6"><?php echo $rowz['MemberFirstname']." ".$rowz['MemberSurname']; ?></th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">FundID</th>
                    <td scope="col"><?php echo $rowz['RetirementFundID']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><?php echo $rowz['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">Gender</th>
					<td scope="col"><?php echo $rowz['Gender']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><?php echo $rowz['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><?php echo $rowz['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Postal Address</th>
					<td scope="col"><?php echo $rowz['MemberPostalAddress']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"  style="font-weight: bold;"><?php echo "E ". number_format($rowz['ApprovedBenefit'], 2); ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><?php echo $rowz['Terminated']; ?></td>
					 <th scope="col" style="vertical-align: top;">Balance</th>

					 <?php
					 $stmt12 = $conn->prepare("SELECT `NewBalance` from `balances` where  `memberID` = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
?>
			<td scope="col" style="font-weight: bold;"><?php echo "E ". number_format($row12['NewBalance'], 2); ?></td>
<?php
							}
						    
						}else{
								?>
								<td scope="col" style="font-weight: bold;"><?php echo "No Balance";?></td>
								<?php	
							}
?>		
				</tr>
				<tr style="text-align: center; background: white; color: black;">
                    <th scope="col" colspan="6">Account Summary   [<?php echo date('d-M-Y')?>]</th>
                   
                    </tr>	
				
					
<?php


}    


    
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '8' AND (`TransactionDate` BETWEEN '$d1' AND '$d2') AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>

                  
                   
                    <tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Income Earned</th>
                    <td scope="col" colspan="3" style="text-align: right;"><?php echo "E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>

 <?php	}
	?>

<?php	} else {
echo "No Results";
}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('2','5', '6','7' ) AND (`TransactionDate` BETWEEN '$d1' AND '$d2') AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
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


						    
						    
}
						
} else {
  header('location: ./');
}






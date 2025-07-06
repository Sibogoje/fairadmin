
<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];


if(count($_POST)>0){
    
        
$stmt = $conn->prepare("SELECT * from `tblmembers` where `MemberID` = '$ii' ");
						$stmt->execute();
						$result = $stmt->get_result();
						if ($result->num_rows > 0) {
						    while($row = $result->fetch_assoc()) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                    	<tr style="text-align: center; background: black; color: white;">
                        <th scope="col" colspan="6">SUMMARY STATEMENT</th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">MEMBER DETAILS</th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Full Name</th>
                    <td scope="col"><?php echo $row['MemberFirstname']." ".$row['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><?php echo $row['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">National ID</th>
					<td scope="col"><?php echo $row['MemberIDnumber']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><?php echo $row['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><?php echo $row['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Postal Address</th>
					<td scope="col"><?php echo $row['MemberPostalAddress']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"  style="font-weight: bold;"><?php echo "E ". number_format($row['ApprovedBenefit'], 2); ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><?php echo $row['Terminated']; ?></td>
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
							}}else{
								?>
								<td scope="col" style="font-weight: bold;"><?php echo "No data";?></td>
								<?php	
							}
?>				 
					
					</tr>
				<tr style="text-align: center; background: white; color: black;">
                    <th scope="col" colspan="6">Account Summary   [<?php echo date('d-M-Y')?>]</th>
                   
                    </tr>	
<?php					
}}    
    
    
$stmt13 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '8' AND memberID = '$ii' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>

                   
                    <tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Income Earned</th>
                    <td scope="col" colspan="3" style="text-align: right;"><?php echo "E ". number_format($row13['TT3'], 2); ?></td>
                    </tr>

 <?php	}
	?>

<?php	} else {
echo "0 results";	}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('2','5', '6','7' ) AND memberID = '$ii' ");
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
 
 
 
 
     
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('3', '4') AND memberID = '$ii' ");
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
 
 
     
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '10' AND memberID = '$ii' ");
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

<?php						
						
} else {
  header('location: ./');
}






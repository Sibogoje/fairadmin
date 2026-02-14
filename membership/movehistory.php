
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];


if(count($_POST)>0){
    
        
$stmt = $conn->prepare("SELECT * from memberz2 where MemberID = '$ii' ");
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
                    <th scope="col" colspan="6">MEMBER DETAILS</th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Full Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['MemberFirstname']." ".$row['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">FundName</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['FundName']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Gender</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['Gender']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"  style="font-weight: bold;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "E ". number_format($row['ApprovedBenefit'], 2); ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['Terminated']; ?></td>
					 <th scope="col" style="vertical-align: top;">Balance</th>
					 <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
$stmt12 = $conn->prepare("SELECT `NewBalance` from `balances` where  `memberID` = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
?>
			<td scope="col" style="font-weight: bold;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "E ". number_format($row12['NewBalance'], 2); ?></td>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}else{
								?>
								<td scope="col" style="font-weight: bold;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "No data";?></td>
								<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
?>		
				</tr>
				<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">Account Summary   [<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo date('d-M-Y')?>]</th>
                   
                    </tr>	
					</thead>
					</table>
					</div>
					
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}    
    
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '1' AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Initial Transfer In</th>
                    <td scope="col" style="text-align: right;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
	?>
</table>
</div>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
} else {
echo "0 results";	}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '2' AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Transfer In Fee</th>
                    <td scope="col" style="text-align: right;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
	?>
</table>
</div>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
} else {
echo "0 results";	}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('9' ) AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Additional Capital</th>
                    <td scope="col" style="text-align: right;"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo " E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
	?>
</table>
</div>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
} else {
echo "0 results";	}    
 
 

?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
} else {
  header('location: ./');
}






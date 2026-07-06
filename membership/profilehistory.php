
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];

$fund = "";
?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">MEMBER DETAILS</th>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * from tblmembers where MemberNo = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 ?>
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Full Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['MemberFirstname']." ".$row12['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">National ID</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['MemberIDnumber']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Postal Address</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['MemberPostalAddress']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row12['ApprovedBenefit']; ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
if($row12['Terminated'] == "0") echo "No"; else echo "Yes" ; ?></td>
					<th scope="col"  style="vertical-align: top;">Balance:</th>
					<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
$stmt14 = $conn->prepare("SELECT `NewBalance` from `balances` where  `M_ID` = '$ii' ");
						$stmt14->execute();
						$result14 = $stmt14->get_result();
						if ($result14->num_rows > 0) {
						    while($row14 = $result14->fetch_assoc()) {
?>
			
			 
			  <td scope="col"  style=""><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "E ". number_format($row14['NewBalance'], 2); ?></td>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}else{
								?>
							<td scope="col"  style=""><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo "No data";?></td>
								<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
?>			
					
					</tr>
	<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
$deceadid = $row12['DeceasedID'];					    
	$stmt13 = $conn->prepare("SELECT * from tbldeceased where DeceasedID = '$deceadid' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 ?>
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6" >DECEASED DETAILS</th>
                   </tr>
                   
					<tr>
                    <th scope="col" style="vertical-align: top;">Deceased Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['DeceasedSurname']." ".$row13['DeceasedFirstnames']; ?></td>
                    <th scope="col" style="vertical-align: top;" >Deceased ID</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo !empty($row13['DeceasedIDnumber']) ? $row13['DeceasedIDnumber'] : $row13['DeceasedID']; ?></td>
					<th scope="col" style="vertical-align: top;">Deceased Date Of Death</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['DateOfDeath']; ?></td>
					</tr>
			<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
$fund = $row13['RetirementFundID'];	
						    }}
	$gurad = $row12['GuardianID'];					    
	$stmt13 = $conn->prepare("SELECT * from tblguardians where GuardianID = '$gurad' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 ?>
						    
					<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">GUARDIAN DETAILS</th>
                   </tr>
					
				    <tr>
                    <th scope="col" style="vertical-align: top;">Guardian Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['GuardianSurname']." ".$row12['GuardianFirstNames']; ?></td>
                    <th scope="col" style="vertical-align: top;">Guardian ID</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo !empty($row13['GuardianIDno']) ? $row13['GuardianIDno'] : $row13['GuardianID']; ?></td>
					<th scope="col" style="vertical-align: top;">Guardian Contacts</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['GuardianCell']; ?></td>
					</tr>
		<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}
	$kin = $row12['NextOfKinID'];					    
	$stmt13 = $conn->prepare("SELECT * from tblnextofkin where NextOfKinID = '$kin' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 ?>				
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">NEXT OF KIN DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">N. Kin Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['KinSurname']." ".$row12['KinFirstNames']; ?></td>
                    <th scope="col" style="vertical-align: top;">N. Kin Email</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['KinEmail']; ?></td>
					<th scope="col" style="vertical-align: top;">N. Kin Cell</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['KinCell']; ?></td>
					</tr>
	<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}
	$empid = $row12['employerID'];					    
	$stmt13 = $conn->prepare("SELECT * from tblemployers where employerID = '$kin' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 ?>						
					<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">EMPLOYER DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">Employer Name</th>
                    <td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['EmployerName']; ?></td>
                    <th scope="col" style="vertical-align: top;">Employer Contact Person</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['EmployerContactPerson']; ?></td>
					<th scope="col" style="vertical-align: top;">Contact</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['EmployerCell']; ?></td>
					</tr>
					
	<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}
				    
	$stmt13 = $conn->prepare("SELECT * from tblretirementfunds where RetirementFundID = '$fund' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						 ?>					
						<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">FUND DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">Fund Name</th>
                    <td scope="col" ><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['FundName']; ?></td>
                    <th scope="col" style="vertical-align: top;">Fund Contact Person</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['FundContact']; ?></td>
					<th scope="col" style="vertical-align: top;">Contact</th>
					<td scope="col"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row13['FundTelNo']; ?></td>
					</tr>
					
					
                 
                </thead>
            
				
          
		   <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}}	}
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
?>




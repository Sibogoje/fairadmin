
<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];

$deceasedid = "";
$fundid = "";

if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * from tblmembers where MemberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6" colspan="6">CERTIFICATE OF EXISTENCE</th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Member Name</th>
                    <td scope="col"><?php echo $row12['MemberNo']."  ".$row12['MemberFirstname']." ".$row12['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">ID No</th>
					<td scope="col"><?php echo $row12['MemberIDnumber']; ?></td>
					<th scope="col" style="vertical-align: top;">date of Birth</th>
					<td scope="col"><?php echo $row12['DateOfBirth']; ?></td>
					</tr>
					
					
					
						<tr>
                    <th scope="col" colspan="4" style="vertical-align: top; text-align: left;">If no loger studying, Please state occupation here: </th>
                    <td scope="col" colspan="2" >____________________________________________________</td>
                  
				
					</tr>
					
				<tr style="text-align: center; background: white; color: black;">
                    <th scope="col" colspan="6" >If beneficiary is 21 years/older, he/she must complete this section</th>
                   </tr>
                   
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">ID NO</th>
                    <td scope="col" colspan="2"><?php echo $row12['MemberIDnumber']; ?></td>
                    <th scope="col" colspan="1" style="vertical-align: top;" >Date of Birth</th>
					<td scope="col" colspan="2"><?php echo $row12['DateOfBirth']; ?></td>
				
					</tr>
					<tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">________________________________________</th>
                    <td scope="col" colspan="3" style="font-weight: bold;"><?php echo $row12['MemberFirstname']." ".$row12['MemberSurname']; ?></td>
                    
				
					</tr>
					
					<tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Signature of Beneficiary</th>
                    <td scope="col" colspan="3">Name and Surname</td>
                    
				
					</tr>
					
					<tr>
                    
                    <td scope="col" colspan="6">By signing here you confirm that you are the beneficiary as listed on the form, and that all the information supplied is correct.
                    <br>
                    Supply original certified copy of your Identity Document or Passport</td>
                    
				
					</tr>
					
					<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">GUARDIAN DETAILS</th>
                   </tr>
                   
                   
                   
                   <tr style="text-align: center; background: white; color: black;">
                    <th scope="col" colspan="6" >If beneficiary is younger than 18, the Guardian/Caregiver must complete this section</th>
                   </tr>
                   
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">ID NO</th>
                    <td scope="col" colspan="2"><?php echo $row12['MemberIDnumber']; ?></td>
                    <th scope="col" colspan="1" style="vertical-align: top;" >Date of Birth</th>
					<td scope="col" colspan="1"><?php echo $row12['DateOfBirth']; ?></td>
				
					</tr>
					<tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">________________________________________</th>
                    <td scope="col" colspan="3" style="font-weight: bold;"><?php echo $row12['GuardianSurname']." ".$row12['GuardianFirstNames']; ?></td>
                    
				
					</tr>
					
					<tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">Signature of Guardian/Caregiver</th>
                    <td scope="col" colspan="3">Name and Surname of Guardian/Caregiver</td>
                    
				
					</tr>
					
					<tr>
                    
                    <td scope="col" colspan="6">By signing here you confirm that you are the Guardian/Caregiver as listed on the form, and that all the information supplied is correct.
                    <br>
                    Supply original certified copy of your Identity Document or Passport</td>
                    
				
					</tr>
					
				    
					
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6"></th>
                   </tr>
                   
                  <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;">Beneficiary</th>
					
					<th scope="col" colspan="2" style="vertical-align: top;">Guardian</th>
					
					</tr>
					  <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Address</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					 <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Telephone (Home)</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					
					 <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Telephone (Work)</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					 <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Cell</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					 <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Email</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					 <tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Home Language</th>
                    
                    <th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					<th scope="col" colspan="2" style="vertical-align: top;"></th>
					
					</tr>
					
                   
                 
					
						<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6">FUND DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" colapsn="6" style="vertical-align: top;">Who else can we contact if you are not avaialble such as family member or neighbour</th>
                    
					</tr>
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Who else can we contact if you are not avaialble such as family member or neighbour</th>
                    <td scope="col" ><?php echo $row12['FundName']; ?></td>
                    <th scope="col" style="vertical-align: top;">Fund Contact Person</th>
					<td scope="col"><?php echo $row12['FundContact']; ?></td>
					<th scope="col" style="vertical-align: top;">Contact</th>
					<td scope="col"><?php echo $row12['FundTelNo']; ?></td>
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
						
} else {
  header('location: ./');
}





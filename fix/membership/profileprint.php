
<?php
include '../scripts/connection.php';
$ii = $_POST['MemberID'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile Print</title></title>
 
	 <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	

   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>



footer {
  font-size: 14px;
  color: black;
  text-align: center;
}

@page {
  size: A4;
  margin: 11mm 17mm 17mm 17mm;
 
}

@media print {
   
  footer {
    position: fixed;
    bottom: 0;
    display: flex;
align-items: center;
justify-content: center;

  }

  .content-block, p {
    page-break-inside: avoid;
  }

  html, body {
    width: 210mm;
    height: 297mm;
  }
}

@media print {
@page {
           margin-top: 0;
           margin-bottom: 0;
         }
         body  {
           padding-top: 72px;
           padding-bottom: 72px ;
         }

}
.table {
	font-family:'Arial';
  
 

  
}
#bottom {
   
 display: flex;
align-items: center;
justify-content: center;
}

@media print
{
    html
    {
		font-size: 17px;
       
    }
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 8px;
  text-align: left;
}
}

@media print
{
    html
    {
        zoom: 90%;
    }
}

</style>

</head>

<body>
    
<?php

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
                    <th scope="col" colspan="2" style="">Full Name: <span style="font-weight: normal;"><?php echo $row12['MemberFirstname']." ".$row12['MemberSurname']; ?></span></th>
                    <th scope="col" colspan="2" style="">MemberNo: <span style="font-weight: normal;"><?php echo $row12['MemberNo']; ?></span></th>
				    <th scope="col" colspan="2" style="">National ID: <span style="font-weight: normal;"><?php echo $row12['MemberIDnumber']; ?></span></th>
					
					</tr>
					
					
					<tr>
                    <th colspan="2" scope="col" style="">Date of Birth: <span style="font-weight: normal;"><?php echo $row12['DateOfBirth']; ?></span></th>
                    <th colspan="2" scope="col" style="">Account Opened: <span style="font-weight: normal;"><?php echo $row12['DateAccountOpened']; ?></span></th>
					<th colspan="2" scope="col" style="">Postal Address: <span style="font-weight: normal;"> <?php echo $row12['MemberPostalAddress']; ?></span></th>
				
					</tr>
					
							<tr>
                    <th colspan="3" scope="col" style="">Approved Benefit: E <span style="font-weight: normal;"><?php echo $row12['ApprovedBenefit']; ?></span></th>
                    
                    
					<th colspan="3" scope="col"  style="">Balance: 
					<?php
					 $stmt14 = $conn->prepare("SELECT `NewBalance` from `balances` where  `M_ID` = '$ii' ");
						$stmt14->execute();
						$result14 = $stmt14->get_result();
						if ($result14->num_rows > 0) {
						    while($row14 = $result14->fetch_assoc()) {
?>
			
			 
			 <span style="font-weight: normal;"><?php echo "E ". number_format($row14['NewBalance'], 2); ?></span>
<?php
							}}else{
								?>
							<span style="font-weight: normal;"><?php echo "No data";?></span>
								<?php	
							}
?>			
					</th>
					</tr>
	<?php
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
                    <th colspan="2" scope="col" style="">Deceased Name: <span style="font-weight: normal;"><?php echo $row13['DeceasedSurname']." ".$row13['DeceasedFirstnames']; ?></span></th>
                    <th colspan="2" scope="col" style="" >Deceased ID: <span style="font-weight: normal;"><?php echo $row13['DeceasedID']; ?></span></th>
					<th colspan="2" scope="col" style="">Deceased Date Of Death: <span style="font-weight: normal;"><?php echo $row13['DateOfDeath']; ?></span></th>
					
					</tr>
			<?php
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
                    <th colspan="2" scope="col" style="">Guardian Name: <span style="font-weight: normal;"><?php echo $row13['GuardianSurname']." ".$row12['GuardianFirstNames']; ?></span></th>
                    <th colspan="2" scope="col" style="">Guardian ID: <span style="font-weight: normal;"><?php echo $row13['GuardianID']; ?></span></th>
					<th colspan="2" scope="col" style="">Guardian Contacts: <span style="font-weight: normal;"><?php echo $row13['GuardianCell']; ?></span></th>
					
					</tr>
		<?php
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
                    <th colspan="2" scope="col" style="">N. Kin Name: <span style="font-weight: normal;"><?php echo $row13['KinSurname']." ".$row12['KinFirstNames']; ?></span></th>
                    <th colspan="2" scope="col" style="">N. Kin Email: <span style="font-weight: normal;"><?php echo $row13['KinEmail']; ?></span></th>
					<th colspan="2" scope="col" style="">N. Kin Cell: <span style="font-weight: normal;"><?php echo $row13['KinCell']; ?></span></th>
					
					</tr>
	<?php
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
                    <th colspan="2" scope="col" style="">Employer Name: <span style="font-weight: normal;"><?php echo $row13['EmployerName']; ?></span></th>
                    <th colspan="2" scope="col" style="">Employer Contact Person: <span style="font-weight: normal;"><?php echo $row13['EmployerContactPerson']; ?></span></th>
					<th colspan="2" scope="col" style="">Contact: <span style="font-weight: normal;"><?php echo $row13['EmployerCell']; ?></span></th>
					
					</tr>
					
	<?php
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
                    <th scope="col" colspan="2" style="">Fund Name: <span style="font-weight: normal;"><?php echo $row13['FundName']; ?></span></th>
                    <th scope="col" colspan="2" style="">Fund Contact Person: <span style="font-weight: normal;"><?php echo $row13['FundContact']; ?></span></th>
				    <th scope="col" colspan="2" style="">Contact: <span style="font-weight: normal;"><?php echo $row13['FundTelNo']; ?></span></th>
					
					</tr>
					
					
                 
                </thead>
            
				
          
		   <?php }}	}
						?>
				
						 </table>						<?php	} else {
						  echo "0 results";	} 
?>

  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}

?>
<footer>
     <img src="footer.PNG" width="100%">
    </footer>  
<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>	
</body>

</html>



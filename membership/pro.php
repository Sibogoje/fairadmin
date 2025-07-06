<?php
include '../scripts/connection.php';
$ii = $_POST['memberID'];
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
  font-size: 10px;
  color: black;
  text-align: center;
}

@page {
  size: A4;
  margin: 11mm 17mm 27mm 17mm;
 
}

@media print {
    .hed{
             background-color: black;
         }

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
            margin-right: 15px;
            margin-left: 15px;
         }
         body  {
           padding-top: 72px;
           padding-bottom: 72px ;
         }
         .hed{
             background-color: black;
         }
         
         thead {
             background-color: black;
         }
         .table {
	font-family:'Arial';
	border: 1px;
  
}

}
.table {
	font-family:'Arial';
	border: 1px;
  
}
#bottom {
   
 display: flex;
align-items: center;
justify-content: center;
}
td {

  
}

.hed {
    
    padding: 10px;
}




@media print
{
    html
    {
		font-size: 18px;
		margin: 10px;
       
    }
    table {
        border: solid #000 !important;
        border-width: 1px 0 0 1px !important;
    }
    th, td {
        border: solid #000 !important;
        border-width: 0 1px 1px 0 !important;
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
                    <th scope="col" style="vertical-align: top;" colspan="2">Member Name:<br><span style="font-weight: normal;"> <?php echo $row12['MemberNo']."  ".$row12['MemberFirstname']." ".$row12['MemberSurname']; ?></span></th>
                    
                    <th scope="col" style="vertical-align: top;" colspan="2">ID No: <br><span style="font-weight: normal;"><?php echo $row12['MemberIDnumber']; ?></span></th>
				
					<th scope="col" style="vertical-align: top;" colspan="2">date of Birth: <br><span style="font-weight: normal;"> <?php echo $row12['DateOfBirth']; ?> </span></th>
					
					</tr>
					
					
					
						<tr>
                    <th scope="col" colspan="6" style="vertical-align: top; text-align: left;">If no loger studying, Please state occupation here: <br></th>
                 
                  
				
					</tr>
					
				<tr style="text-align: center; background: white; color: black;">
                    <th scope="col" colspan="6" >If beneficiary is 21 years/older, he/she must complete this section</th>
                   </tr>
 <!--                  
					<tr>
                    <th scope="col" colspan="3" style="vertical-align: top;">ID NO: <br> <span style="font-weight: normal;"><?php echo $row12['MemberIDnumber']; ?></span></th>
                    <th scope="col" colspan="3" style="vertical-align: top;" >Date of Birth: <br> <span style="font-weight: normal;"><?php echo $row12['DateOfBirth']; ?></span></th>
                    </tr>
-->					
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Signature of Beneficiary</th>
                    <td scope="col" colspan="4" style="font-weight: bold;">Name and Surname</td>
                    </tr>
                    
                    
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;"> </th>
                    <td scope="col" colspan="4" style="font-weight: normal;"><?php echo $row12['MemberFirstname']." ".$row12['MemberSurname']; ?></td>
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
                    <th scope="col" colspan="2" style="vertical-align: top;">Guardian ID NO: <br> <span style="font-weight: normal;"><?php echo $row12['MemberIDnumber']; ?></span></th>
                    <th scope="col" colspan="4" style="vertical-align: top;" >Date of Birth: <br> <span style="font-weight: normal;"><?php echo $row12['DateOfBirth']; ?></span></th>
					</tr>
					
					
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">Signature of Guardian/Caregiver</th>
                    <td scope="col" colspan="4" style="font-weight: bold;">Full Name</td>
                    </tr>
					
					<tr>
                    <th scope="col" colspan="2" style="vertical-align: top;">  </th>
                    <td scope="col" colspan="4" style="font-weight: bold;"><?php echo $row12['GuardianSurname']." ".$row12['GuardianFirstNames']; ?></td>
                    </tr>
					
					
					
					<tr>
                    
                    <td scope="col" colspan="6">By signing here you confirm that you are the Guardian/Caregiver as listed on the form, and that all the information supplied is correct.
                    Supply original certified copy of your Identity Document or Passport</td>
                   </tr>
					
				    
					
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" style="font-weight: bold;" colspan="6"> CONTACT DETAILS</th>
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
                    <th scope="col" colspan="2" style="vertical-align: top;">Alternate Cell</th>
                    
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
<?php 

$did = $row12['DeceasedID'];

//echo $did;
$stmt13 = $conn->prepare("SELECT RetirementFundID from tbldeceased where DeceasedID = '$did' ");
						$stmt13->execute();
						$result13 = $stmt13->get_result();
						if ($result13->num_rows > 0) {
						    while($row13 = $result13->fetch_assoc()) {
						  // output data of each row
						  
						  $fundid = $row13['RetirementFundID'];
						  
					//	  echo $fundid;
						  
			$stmt14 = $conn->prepare("SELECT FundName, FundContact, FundTelNo from tblretirementfunds where RetirementFundID = ' $fundid' ");
						$stmt14->execute();
						$result14 = $stmt14->get_result();
						if ($result14->num_rows > 0) {
						    while($row14 = $result14->fetch_assoc()) {
						  // output data of each row
						  ?>
					
			    <tr>
                 <td scope="col" colspan="2" style="font-weight: bold;">Fund Name: <br> <span style="font-weight: normal;"><?php echo $row14['FundName']; ?></span></td>
                    <th scope="col" colspan="2" style="vertical-align: top;">Fund Contact Person: <br> <span style="font-weight: normal;"><?php echo $row14['FundContact']; ?></span></th>
					<th scope="col" colspan="2" style="vertical-align: top;">Contact: <br> <span style="font-weight: normal;"><?php echo $row14['FundTelNo']; ?></span></th>
				</tr>
				<?php  }}  }}	?>
					
                 
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
?>

  
<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>	
</body>

</html>






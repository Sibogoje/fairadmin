
<?php
require_once '../scripts/connection.php';
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
td {

  
}

.hed {
    
    padding: 10px;
}




@media print
{
    html
    {
		font-size: 15px;
       
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
    
<?
if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * from profile where MemberNo = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 ?>
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6" class="hed">MEMBER DETAILS</th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Full Name</th>
                    <td scope="col"><? echo $row12['MemberFirstname']." ".$row12['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><? echo $row12['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">National ID</th>
					<td scope="col"><? echo $row12['MemberIDnumber']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><? echo $row12['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><? echo $row12['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Postal Address</th>
					<td scope="col"><? echo $row12['MemberPostalAddress']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"><? echo $row12['ApprovedBenefit']; ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><? echo $row12['Terminated']; ?></td>
					 <th scope="col" style="vertical-align: top;">Balance</th>
					<td scope="col"><? echo $row12['balance']; ?></td>
					</tr>
					
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6"class="hed" >DECEASED DETAILS</th>
                   </tr>
                   
					<tr>
                    <th scope="col" style="vertical-align: top;">Deceased Name</th>
                    <td scope="col"><? echo $row12['DeceasedSurname']." ".$row12['DeceasedFirstnames']; ?></td>
                    <th scope="col" style="vertical-align: top;" >Deceased ID</th>
					<td scope="col"><? echo $row12['DeceasedID']; ?></td>
					<th scope="col" style="vertical-align: top;">Deceased Date Of Death</th>
					<td scope="col"><? echo $row12['DateOfDeath']; ?></td>
					</tr>
					
					<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6" class="hed">GUARDIAN DETAILS</th>
                   </tr>
					
				    <tr>
                    <th scope="col" style="vertical-align: top;">Guardian Name</th>
                    <td scope="col"><? echo $row12['GuardianSurname']." ".$row12['GuardianFirstNames']; ?></td>
                    <th scope="col" style="vertical-align: top;">Guardian ID</th>
					<td scope="col"><? echo $row12['GuardianID']; ?></td>
					<th scope="col" style="vertical-align: top;">Guardian Contacts</th>
					<td scope="col"><? echo $row12['GuardianCell']; ?></td>
					</tr>
					
				<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6" class="hed">NEXT OF KIN DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">N. Kin Name</th>
                    <td scope="col"><? echo $row12['KinSurname']." ".$row12['KinFirstNames']; ?></td>
                    <th scope="col" style="vertical-align: top;">N. Kin Email</th>
					<td scope="col"><? echo $row12['KinEmail']; ?></td>
					<th scope="col" style="vertical-align: top;">N. Kin Cell</th>
					<td scope="col"><? echo $row12['KinCell']; ?></td>
					</tr>
					
					<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6" class="hed">EMPLOYER DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">Employer Name</th>
                    <td scope="col"><? echo $row12['EmployerName']; ?></td>
                    <th scope="col" style="vertical-align: top;">Employer Contact Person</th>
					<td scope="col"><? echo $row12['EmployerContactPerson']; ?></td>
					<th scope="col" style="vertical-align: top;">Contact</th>
					<td scope="col"><? echo $row12['EmployerTel']; ?></td>
					</tr>
					
					
						<tr style="text-align: center; background: grey; color: white;">
                    <th scope="col" colspan="6" class="hed">FUND DETAILS</th>
                   </tr>
                   
                  <tr>
                    <th scope="col" style="vertical-align: top;">Fund Name</th>
                    <td scope="col" ><? echo $row12['FundName']; ?></td>
                    <th scope="col" style="vertical-align: top;">Fund Contact Person</th>
					<td scope="col"><? echo $row12['FundContact']; ?></td>
					<th scope="col" style="vertical-align: top;">Contact</th>
					<td scope="col"><? echo $row12['FundTelNo']; ?></td>
					</tr>
					
					
                 
                </thead>
            
				
          
		   <?php	}
						?>
				
						 </table>
						<?php	} else {
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



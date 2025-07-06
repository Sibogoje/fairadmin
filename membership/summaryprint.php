
<?php
require_once '../scripts/connection.php';
$ii = $_POST['MemberID'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $ii;?> Summary Print</title></title>
 
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
  text-align: left;
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
align-items: left;
justify-content: left;

  }

  .content-block, p {
    page-break-inside: avoid;
  }

  html, body {
    width: 210mm;
    height: 297mm;
    font-size: 19px;
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
#bottom {
   
 display: flex;
align-items: left;
justify-content: left;
}
td {

  
}



@media print
{
    html
    {
		font-size: 22px;
       
    }
    
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 12px;
  font-size: 20;
  font-family:'Arial';

}
.hds {
    background: black;
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
if(count($_POST)>0){
        
$stmt = $conn->prepare("SELECT * from `tblmembers` where `MemberID` = '$ii' ");
						$stmt->execute();
						$result = $stmt->get_result();
						if ($result->num_rows > 0) {
						    while($row = $result->fetch_assoc()) {
						  // output data of each row
						 ?>
		<table class="table datatable"  id="free" width="100%" style="text-align: left;">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                    <tr  style="text-align: center; ">
                        <th class="hds" scope="col" colspan="6">SUMMARY STATEMENT</th>
                   
                    </tr>
                   	<tr style="text-align: left; background: black; color: white;">
                    <th scope="col" colspan="6">MEMBER DETAILS</th>
                   
                    </tr>
                    <tr width="100%">
                    <th scope="col" colspan="3" style="vertical-align: top; horizontal-align: left;">Full Name: <span style="font-weight: normal;"><?php echo $row['MemberFirstname']." ".$row['MemberSurname']; ?>   
                    <th scope="col" colspan="3" style="vertical-align: top;">MemberNo:  <span style="font-weight: normal;"><?php echo $row['MemberNo']; ?></th></span></th>
                   </tr>
                   
                   
                   <tr>
                   <th scope="col" colspan="3" style="vertical-align: top; horizontal-align: left;">National ID:  <span style="font-weight: normal;"><?php echo $row['MemberIDnumber']; ?></span></th>
			       <th scope="col" colspan="3" style="vertical-align: top;">Date of Birth :  <span style="font-weight: normal;"><?php echo $row['DateOfBirth']; ?></span></th>
				   </tr>
					
					
					<tr>
					    <th scope="col" colspan="3" style="vertical-align: top;">Account Opened:  <span style="font-weight: normal;"><?php echo $row['DateAccountOpened']; ?></span></th>
					    <th scope="col" colspan="3" style="vertical-align: top;">Postal Address: <span style="font-weight: normal;"> <?php echo $row['MemberPostalAddress']; ?></span></th>
					   
					</tr>
					
					<tr>
        <th scope="col" colspan="3" style="vertical-align: top;">Approved Benefit:  <span style="font-weight: normal;"><?php echo "E ". number_format($row['ApprovedBenefit'], 2); ?></span></th>
                    
                    
					
					 
           <?php
					 $stmt12 = $conn->prepare("SELECT `NewBalance` from `balances` where  `memberID` = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
?>
			
			<th scope="col" colspan="3" style="vertical-align: top;">Balance:  <span style="font-weight: normal;"><?php echo "E ". number_format($row12['NewBalance'], 2); ?></span></th>
<?php
							}}else{
								?>
							<th scope="col" colspan="3" style="vertical-align: top;">Balance:  <span style="font-weight: normal;"><?php echo "No data";?></span></th>
								<?php	
							}
?>			
					</tr>
				<tr style="text-align: left; background: black; color: white; border: 1px solid black;" class="headerr">
				   
                    <th scope="col" colspan="6">Account Summary   [<?php echo date('d-M-Y')?>]</th>
                   
                    </tr>	
					</thead>
					</table>
					
<?php


}}    
    
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '8' AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
		<table class="table datatable"  id="free" width="100%" style="padding: 10px; border: 1px solid black;">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top; text-align: left;">Income Earned</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". number_format($row12['TT3'], 2); ?></td>
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
                    <th scope="col" style="vertical-align: top; text-align: left;">Expenses/Costs</th>
                    <td scope="col" style="text-align: right;"><?php echo "- E ". number_format($row12['TT3'], 2); ?></td>
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
                    <th scope="col" style="vertical-align: top; text-align: left;">Payments</th>
                    <td scope="col" style="text-align: right;"><?php echo "- E ".number_format($row12['TT3'], 2); ?></td>
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
                    <th scope="col" style="vertical-align: top; text-align: left;">Other Transactions</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ".number_format($row12['TT3'], 2); ?></td>
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



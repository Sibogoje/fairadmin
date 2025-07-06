
<?php
require_once '../scripts/connection.php';
$ii = $_POST['MemberID'];
$d1=$_POST['date1'];
$d2=$_POST['date2'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $ii; ?></title></title>
 
	 <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	

   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>

@page {
  size: A4;
  margin: 11mm 17mm 17mm 17mm;
 
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





@media print {
@page {
           margin-top: 0;
           margin-bottom: 0;
         }
         body  {
           padding-top: 72px;
           padding-bottom: 79px ;
         }
.footer {
    position: fixed;
    bottom: 0;
    display: flex;
align-items: center;
justify-content: center;

  }        


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
		font-size: 16px;
       
    }
    table, tr, th, td{
        border: 1px solid black;
  border-collapse: collapse;
  padding: 5px;
  text-align: left;
  
    }
}

@media print
{
    html
    {
        zoom: 100%;
    }
}

</style>

</head>

<body>
    
<?php
$ii = $_POST['MemberID'];
$d1=$_POST['date1'];
$d2=$_POST['date2'];

$member = mysqli_query($conn, "SELECT * FROM tblmembers WHERE MemberID = '$ii'");
$memberrow = mysqli_fetch_array($member);
$membername = $memberrow['MemberFirstname'] . " " . $memberrow['MemberSurname'];
$approvedbenefit = $memberrow['ApprovedBenefit'];
$memberno = $memberrow['MemberNo'];


$balance = mysqli_query($conn, "SELECT NewBalance FROM balances WHERE memberID = '$ii'");
$balancerow = mysqli_fetch_array($balance);
$runbalance = $balancerow['NewBalance'] ;


if(count($_POST)>0){

?>
		<table class="table datatable"  id="free">
		
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">Beneficiary Report</th>
                   
                    </tr>
                    <tr>
                    <th colspan="2" >FullName: <span class=""><?php echo $membername; ?></span></th>
                    
                    <th colspan="2" >MemberNo: <span class="inside"><?php echo $memberno; ?></span></th>
					
					<th colspan="2" >Balance : <span class="inside"><?php echo $runbalance; ?></span></th>
					
					</tr>
										
				<tr style="text-align: center; background: black; color: white;">
                    <th width="100%" colspan="6">Beneficiary Report Summary</th>
                   
                    </tr>	
			

					
<?php
   
    
$ttopening = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '1'  "); 
$ttopeningresult = mysqli_fetch_assoc($ttopening); 
$ttopeningrow = $ttopeningresult['Opening'];

$ttin = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '2'  "); 
$ttinresult = mysqli_fetch_assoc($ttin); 
$ttinrow = $ttinresult['Opening'];

$ttregular = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '3'  "); 
$ttregularresult = mysqli_fetch_assoc($ttregular); 
$ttregularrow = $ttregularresult['Opening'];

$ttadhoc = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '4'  "); 
$ttadhocresult = mysqli_fetch_assoc($ttadhoc); 
$ttadhocgrow = $ttadhocresult['Opening'];

$ttfee = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '5'  "); 
$ttfeeresult = mysqli_fetch_assoc($ttfee); 
$ttfeegrow = $ttfeeresult['Opening'];

$ttmonthly = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '6'  "); 
$ttmonthlyresult = mysqli_fetch_assoc($ttmonthly); 
$ttmonthlyrow = $ttmonthlyresult['Opening'];

$ttadmin = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '7'  "); 
$ttadminresult = mysqli_fetch_assoc($ttadmin); 
$ttadminrow = $ttadminresult['Opening'];

$ttint = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '8'  "); 
$ttintresult = mysqli_fetch_assoc($ttint); 
$ttintrow = $ttintresult['Opening'];

$ttadd = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '9'  "); 
$ttaddresult = mysqli_fetch_assoc($ttadd); 
$ttaddrow = $ttaddresult['Opening'];

$ttother = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$ii' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '10'  "); 
$ttotherresult = mysqli_fetch_assoc($ttother); 
$ttotherrow = $ttotherresult['Opening'];
?>




<tr>    
<th colspan="3">Opening Amount</th>
<td colspan="3"><?php echo "E ". $ttopeningrow; ?></td>
</tr>

<tr>    
<th colspan="3">Transfer In Fee</th>
<td colspan="3"><?php echo "E -". $ttinrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Regular Payments</th>
<td colspan="3"><?php echo "E -". $$ttregularrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Adhoc Payments</th>
<td colspan="3"><?php echo "E -". $ttadhocgrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Transaction Fees</th>
<td colspan="3"><?php echo "E -". $ttfeegrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Fixed Monthly Fess</th>
<td colspan="3"><?php echo "E -". $ttmonthlyrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Admin Fees</th>
<td colspan="3"><?php echo "E -". $ttadminrow; ?></td>
</tr>

<tr>    
<th colspan="3">Total Interest Allocated</th>
<td colspan="3"><?php echo "E ". $ttintrow; ?></td>
</tr>

<tr>    
<th colspan="3">Additonal Capital</th>
<td colspan="3"><?php echo "E ". $ttaddrow; ?></td>
</tr>

<tr>    
<th colspan="3">Other Transactions</th>
<td colspan="3"><?php echo "E -". $ttotherrow; ?></td>
</tr>

</table>
<div class="footer">
<img src="footer.PNG" width="100%">  
</div>

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



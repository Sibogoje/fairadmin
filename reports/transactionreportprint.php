
<?php
require_once '../scripts/connection.php';
$ii = $_POST['MemberID'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Transaction Report Print</title>
 
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
  margin: 17mm 17mm 17mm 17mm;
  padding: 15px;
 
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
    font-size: 16px;
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
		font-size: 17px;
       
    }
    
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 8px;
  font-size: 14;
  font-family:'Arial';

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


		<table class="table datatable"  id="free" width="100%" style="text-align: left;">
			<thead>
                  <tr>
                    <th scope="col" colspan="7"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	
					</thead>
					
			<?php
			
			
	$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];
	 $type = $_POST['transtype'];


foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );

//echo $mntharray1;
$name = array($mntharray2);

// Build query based on transaction type and members
if (in_array("all", $mntharray)){
  if ($type == "all") {
    $choose = "`tblmemberaccounts` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";  
  } else {
    $choose = "`tblmemberaccounts` WHERE TransactionTypeID = '$type' AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";  
  }
}else{
  if ($type == "all") {
    $choose = "`tblmemberaccounts` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";   
  } else {
    $choose = "`tblmemberaccounts` WHERE TransactionTypeID = '$type' AND `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";   
  }
}

		 
		  
			
$stmt = $conn->prepare("SELECT 
tblmemberaccounts.MemberNo, 
tblmembers.MemberSurname, 
tblmembers.MemberFirstname, 
tblmemberaccounts.TransactionDate, 
tblmemberaccounts.Details, 
tblmemberaccounts.Amount,
tbltransactiontypes.TransactionType
FROM ".$choose." 
LEFT JOIN tblmembers ON tblmemberaccounts.MemberNo = tblmembers.MemberNo
LEFT JOIN tbltransactiontypes ON tblmemberaccounts.TransactionTypeID = tbltransactiontypes.TransactionTypeID
ORDER BY tblmemberaccounts.TransactionDate DESC");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row

?>
<tr>
<th scope="col" colspan="7" style="text-align: center;"><h4><u>TRANSACTION REPORT</u></h4></th>

</tr>
<tr>
<th scope="col" colspan="7" style="text-align: center;">From: <?php echo $d1; ?> &nbsp;&nbsp;&nbsp; To: <?php echo $d2; ?></th>

</tr>
<tr>
<th scope="col">MemberNo </th>
<th scope="col">Surname</th>
<th scope="col">Firstname</th>
<th scope="col">Transaction Date</th>
<th scope="col">Transaction Type</th>
<th scope="col">Details</th>
<th scope="col">Amount</th>
</tr>

<?php

$totalAmount = 0;
while($row = $result->fetch_assoc()) {
	$totalAmount += $row['Amount'];
?>
<tbody>
	<tr>
	<td><?php echo $row['MemberNo']; ?></td>
	<td><?php echo $row['MemberSurname']; ?></td>
	<td><?php echo $row['MemberFirstname']; ?></td>
	<td><?php echo $row['TransactionDate']; ?></td>
	<td><?php echo $row['TransactionType']; ?></td>
	<td><?php echo $row['Details']; ?></td>
	<td style="text-align: right;">E <?php echo number_format($row['Amount'], 2); ?></td>
	</tr>

<?php
}
?>
<tr>
<td colspan="6" style="text-align: right;"><strong>TOTAL:</strong></td>
<td style="text-align: right;"><strong>E <?php echo number_format($totalAmount, 2); ?></strong></td>
</tr>
</tbody>


<?php
}else{
    echo "No transactions found";
}
?>

</table>
<footer>
       		<table width="100%" id="bottom" >
       			<tr>
  					<td style="width: 33%; text-align: left; border: none;">
  						<div id="bottom"> PRINTED ON <span id="datetime"> <?php echo "".date("Y-m-d")." ".date("h:i:sa");  ?> </span> </div>
  					</td>

  					<td style="width: 33%; text-align: center; border: none;">
   					  <div id="bottom"> WWW.FAIRLIFE.CO.SZ</div>
  					</td>

  					<td style="width: 33%; text-align: right; border: none;">
   						 <div id="bottom"><script type="text/javascript"> document.write("Page " + page + " of " + pages )</script></div>
  					</td>
  				</tr>
       		</table>
       </footer>

	
	  
			
	

<script>

window.onload = function() { window.print(); }

</script>	
			
	
</body>

</html>
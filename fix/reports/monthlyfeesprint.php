
<?php
require_once '../scripts/connection.php';
$ii = $_POST['MemberID'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $ii;?> Monthly & Admin Fees Print</title></title>
 
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
    font-size: 12px;
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
		font-size: 21px;
       
    }
    
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 8px;
  
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
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	
					</thead>
					
			<?php
			
			
	$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];



foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );

//echo $mntharray1;
$name = array($mntharray2);

if (in_array("all", $mntharray)){
 // echo $mntharray2;
  //console("HHHH");
   $choose = "`admin_fixed_fees` WHERE DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC";  
}else{
  
 $choose = "`admin_fixed_fees` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT MemberNo, Name, Surname, Details, TransactionDate, Amount FROM ".$choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable responsive no-wrap"  id="free" style="margin-top: 20px; width: 100%; text-align: left;">
			<thead>
			    <tr>
			        <br>
			    </tr>
			    <TR style="border: 0px;">
			        <td colspan="4"  style="border: 0px; padding: 0mm 0mm 0mm 10mm;">
			        <H2><span style="text-align: left;">ADMIN FEES</span> </H2>
			    </td>
			        <td colspan="3"  style="border: 0px; padding: 0mm 10mm 0mm 0mm; text-align: right;">
			        <span style=" font-size: 12px;">Period: <?php echo $d1." - ".$d2; ?></span></H2>
			    </td>
			    </TR>
			   
                  <tr>
                     
                    <th scope="col">Member No</th>
                    <th scope="col">Name </th>
                    <th scope="col">Surname</th>
                   <th scope="col">Details</th>
                   <th scope="col">Date</th>
					<th scope="col">Amount</th>

                  </tr>
                </thead>
                <tbody style="margin-top: 20mm;">
				
          
 <?php
 $count = 1;
						while($row12 = $result12->fetch_assoc()) {
						
?>	

<tr>
                   
                    <th scope="row"><?php echo $row12['MemberNo']; ?></th>
                    <th scope="row"><?php echo $row12['Name'];?></th>
                    <th><?php echo $row12['Surname']; ?></th>
                    <th><?php echo $row12['Details']; ?></th>
                   <td><?php echo $row12['TransactionDate']; ?></td>
                    <td><?php echo  number_format($row12['Amount'], 2);  ?></td>

				
                  </tr>
                  
                  
				   
<?php						
			$count++;
			$AMOUNT = $AMOUNT + $row12['Amount'];
			}
						?>
						</tbody>
						 </table>
						 
						 <h3 style="text-align: right;">Total Admin Fees: E <?php echo number_format($AMOUNT, 2) ?></h3>
						 </div>
						<?php
						
						} else {
						  echo "No Data Found";



						} 
?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}
			
			
			?>
			</table>
					
<?php


   
    



?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						


?>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>	
</body>

</html>



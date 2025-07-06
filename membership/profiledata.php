<?php
session_start();
include "../database/conn.php";


if (!empty($_POST["prints"])) {

    $id=$_POST['MemberNo'];

	
foreach ($months as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );
   // $inyanga = "AND month = '$months'";
   $inyanga = "AND month in ({$mntharray2})  ";
    $clientquery = "WHERE tenant = '$clients' ";
    $sterr = "AND station = '$station'";	
if ($station == "" || $clients=="" || $months=="" ){
echo	"<script>alert('There was an error!!'); window.location='../reports.php'";

  
}else{

	
  

 
  if ($clients == "all"){
      $clientquery = "WHERE house_code!='all' ";
  }

   
    if ($months == "all"){
      $inyanga = "AND month !='all' ";
  }

    if ($station == "all"){
      $sterr = "AND station !='all' ";
  }


//$result1 = mysqli_query($conn,"SELECT * station FROM `invoices` $clientquery.' '.$inyanga ");

?>
   
			


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $tnt." ".$month; ?></title>
 
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
margin-left: 40%;
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
    border: 3px solid black;
  border-collapse: collapse;
  
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
        zoom: 60%;
    }
}

</style>

</head>

<body style="">


<div class="container"  style="width: 100% height: 100%">




    <div class="table" style="width: 100%">
        <table class="table">
             <col style="width: 12.5%;" />
                <col style="width: 25%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
          
            <tbody>
			 <tr>
				    
                    <td colspan="7" style="border-left: 0px; border-right: 0px; border-top: 0px; margin-top: 1px;">
					<img class="img-responsive" style="" src="report.PNG">
					</td>
					 
                </tr>
			
			 <tr>
				    
                    <td colspan="3" style="font-weight: bold; border: 0px; font-size: 20px;">
					Regional Code: <?php echo $station; ?>
					</td>
					 <td colspan="3" style="font-weight: bold; border: 0px; font-size: 20px;">
					Statement
					</td>
					<td colspan="3" style="font-weight: bold; text-align: right; border: 0px; font-size: 20px;">
					<?php echo "Date: ". date("d/m/Y"); ?>
					</td>
                    
                </tr>
                <tr>
                    
                    
                    <th style="border: 1;">House No:</th>
                    <th style="border: 1;">Occupant</th>
                    <th style="border: 1;">Invoice No</th>
                    <th style="border: 1;">Bill/month</th>
                    <th style="border: 1;">Electricity</th>
                    
                    <th style="border: 1;">Total</th>
                    <th style="border: 1;">Cumulative Total</th>
                </tr>
                
                <?php 
$result1 = mysqli_query($conn,"SELECT * FROM `invoices`  $clientquery $inyanga $sterr ORDER BY `tenant`");
              // echo $clientquery.$inyanga.$sterr;
if ($result1->num_rows > 0) {

while($row = $result1->fetch_assoc()) {
    
    
  $invoicenumber = $row['invoicenumber'];
   $water = $row['water_charge'];
     $electricty = $row['electricity_charge'];
      $house = $row['tenant'];
      $housec = $row['house_code'];
       $monthz = $row['month'];
       
                        $wp =  $row['wbill'];
						$ep = $row['ebill'];
						$t = $row['tbill'];
						
						
						
                     $w_units =  $row['w_units'];
					
						$e_units =  $row['e_units'];
						$electricity_charge =  $row['electricity_charge'];
						$water_charge =  $row['water_charge'];
			
						   						
						
							   
						   $B1 = 00.0;
						    $B2 = 00.0;
							 $B3 = 00.0;
							  $B4 = 00.0;
							  
							  $SB1 = 0.00;
							  $SB2 = 0.00;
							  $SB3 = 0.00;
							 $SB4 = 0.00;
							 $temp0 = 0;
							 $temp1 = 0;
							 $temp2 = 0;
							 $temp3 = 0;
							 
							 $tempa0 = 0;
							 $tempa1 = 0;
							 $tempa2 = 0;
							 $tempa3 = 0;
							 
							 $w = 0.0;
                             $vw = 0;
                             $bn = 0;
							 
							 
if ($e_units == 0){
	
	$w = 0.0;
	$vw = 0;
	 $bn = 0;
	
}else if ($e_units<101 && $e_units>0){
	$w = $electricity_charge;
	 $bn = 180.00;
	
}else{
	
	$vw = 	($e_units - 100) * 1.8;
	$w = $vw + 180;
	$bn = 180.00;
	
}
							 
							 
							  
if ($w_units < 11){
	
	$temp0 = $w_units;	
    $B1 = 71.21;
	
	$subtotal = $B1 + 82.62;
	$total = $subtotal + $w;
}else if ($w_units > 10 && $w_units < 16){
	$temp0 = 10;
	$B1 = 71.21;
	
$RR = $w_units;

$temp1 = $RR - $temp0;




	
	$B2 = $temp1 * 18.55; 
	
	$subtotal = $B1 + $B2 + 82.62;
	$total = $subtotal + $w;
	
}else if ($w_units > 15 && $w_units < 51  ){
	
	$temp0 = 10;
	$B1 = 71.21;
	
	
	$temp1 = 5;
	$B2 = $temp1 * 18.55;
	
	$temp2 = $w_units - 15;
	$B3 = $temp2 * 27.93; 
	
	
	
$RR = $w_units;

$RR = $RR - $temp0;
$RR = $RR - $temp1;

	$subtotal = $B1 + $B2 + $B3 + 82.62;
	$total = $subtotal + $w;
    
    
}else {
	
	$temp0 = 10;
	$B1 = 71.21;
	
	
	$temp1 = 5;
	$B2 = $temp1 * 18.55;
	
	$temp2 = 34;
	$B3 = $temp2 * 27.93; 
	
	
	
$RR = $w_units;

$RR = $RR - $temp0;
$RR = $RR - $temp1;
$temp3 = $RR - $temp2;

	
//	$temp3 = $electricity_units - 50;
	$B4 = $temp3 * 31.87;    
	
	$subtotal = $B1 + $B2 + $B3 + $B4 + 82.62;
	$total = $subtotal + $w;
    
	
	
}


							 
							 
$result4 = mysqli_query($conn,"SELECT tcode FROM `house` WHERE housecode='$housec'");
              // echo $clientquery.$inyanga.$sterr;
if ($result4->num_rows > 0) {

while($row4 = $result4->fetch_assoc()) {

       
?>
                
                <tr>
				    
                    <td style="border: 1;"><?php echo  $housec; ?></td>
                    <td style="border: 1;"><?php echo  $row4['tcode']; ?></td>
                    <td style="border: 1;"><?php echo  $invoicenumber; ?></td>
                    <td style="border: 1;"><?php echo  $monthz; ?></td>
                    <td style="border: 1;"><?php echo  $w; ?></td>
                   
                    <?php $tt1 = $w; ?>
                    <td style="border: 1;"><?php echo  $tt1; ?></td>
                     <?php $tt2 = $tt2 + $tt1; ?>
                    <td style="border: 1;"><?php echo  $tt2; ?></td>
               
                </tr>
               
                
                
                <?php } }} ?>
                <tr>
				    
                    <td style="border: 1; font-weight: bold; text-align: right;" colspan="6">Grand Total</td>
                  
                 
                    <td style="border: 1; font-weight: bold;"><?php echo  $tt2; ?></td>
               
                </tr>
            </tbody>
        </table>
    </div>


   

   


	</div>
 

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
	$(document).ready(function () {
 window.print();
});
</script>
 </div>
  </div>
   </div>
    </form>  
	
<footer>
      <h3 id="bottom" style="">ISO Standard Compliant</h3>
    </footer>  
	
</body>

</html>

 <?php 
} else {
    
    echo("No Results: " . $mysqli -> error);
//  echo	"<script>alert('There seems to be problem with selected Query, Please match House with right Month');
//window.location='../reports.php';
//</script>";
 
}
    
    
}
} else {  
//echo	"<script>alert('There was an error!!'); window.location='../reports.php'";
 echo("Error 2: " . $mysqli -> error);
}
?>
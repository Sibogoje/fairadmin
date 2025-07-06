
<?php
require_once '../scripts/connection.php';
$from = $_POST['date1'];
$to = $_POST['date2'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fees Print</title></title>
 
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



@media print
{
    html
    {
		font-size:19px;
       
    }
    table, tr, th, td {
        border: 1px solid black;
  border-collapse: collapse;
  padding: 11px;
    }
    td, th {
        
        border: 0;
        
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
 
 $sum = 0;   
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND `TransactionTypeID` = 2  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  
						   $sum = $sum + $row12['TT'] ;
						 ?>
		<table class="table datatable"  id="free" width="100%">
			<thead>
                  <tr>
                    <th scope="col" colspan="4"><img src="../header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="4">FEES STATEMENT From <?php echo $from."  To  ".$to; ?></th>
                   
                    </tr>
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">Transfer In Fees</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $row12['TT']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
<?php	} else {
echo "0 results";	}



$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT2` from `tblmemberaccounts` where DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND `TransactionTypeID` = 5  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT2'] ;
						 ?>
		<table class="table datatable"  id="free" width="100%">
			<thead>
                  
                   
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">Transaction Fees</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $row12['TT2']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
<?php	} else {
echo "0 results";	}






$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND Details LIKE '% Monthly Fee%'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT3'] ;
						 ?>
		<table class="table datatable"  id="free"  width="100%">
			<thead>
                  
                   
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">Monthly Fees</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $row12['TT3']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
<?php	} else {
echo "0 results";	}



$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT4` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND `TransactionTypeID` = 7  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT4'] ;
						 ?>
		<table class="table datatable"  id="free" width="100%">
			<thead>
                  
                   
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">Admin Fees</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $row12['TT4']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
<?php	} else {
echo "0 results";	}



$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT5` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND TransactionTypeID = '12'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT5'] ;
						 ?>
		<table class="table datatable"  id="free" width="100%">
			<thead>
                  
                   
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">Termination Fees</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $row12['TT5']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
<?php	} else {
echo "0 results";	}






?>

<table class="table datatable"  id="free" width="100%">
			<thead>
                  
                   
                    <tr>
                    <th colspan="2" scope="col" style="vertical-align: top; text-align: left;">TOTAL</th>
                    <td colspan="2" scope="col" style="text-align: right;"><?php echo "E ". $sum; ?></td>
                    </tr>
</thead>



  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}
?>
<footer>
     <img src="../footer.PNG" width="100%">
    </footer>  
<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>	
</body>

</html>



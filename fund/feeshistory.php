
<?php
require_once '../scripts/connection.php';

$from = $_POST['from'];
$to = $_POST['to'];


if(count($_POST)>0){
 
 $sum = 0;   
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND TransactionTypeID = '2'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  
						   $sum = $sum + $row12['TT'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="2"><img src="../header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="2">FEES STATEMENT From <?php echo $from."  To  ".$to; ?></th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Transfer In Fees</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $row12['TT']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
<?php	} else {
echo "0 results";	}



$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT2` from `tblmemberaccounts` where DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND TransactionTypeID = '5'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT2'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Transaction Fees</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $row12['TT2']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
<?php	} else {
echo "0 results";	}






$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND TransactionTypeID = '6'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Monthly Fees</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $row12['TT3']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
<?php	} else {
echo "0 results";	}


$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT4` from `tblmemberaccounts` where  DATE(TransactionDate) BETWEEN '$from'  AND '$to' AND TransactionTypeID = '7'  ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						  $sum = $sum + $row12['TT4'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Admin Fees</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $row12['TT4']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
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
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Termination Fees</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $row12['TT5']; ?></td>
                    </tr>
</thead>
 <?php	}
	?>
</table>
</div>
<?php	} else {
echo "No Terminated Acccounts";	}



?>


<div class="table-responsive">
<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">TOTAL</th>
                    <td scope="col" style="text-align: right;"><?php echo "E ". $sum; ?></td>
                    </tr>
</thead>
</table>
</div>

  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}





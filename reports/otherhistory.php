
<?php
require_once '../scripts/connection.php';
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
   $choose = "`tblmemberaccounts` WHERE TransactionTypeID IN ('10') AND  DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";  
}else{
  
 $choose = "`tblmemberaccounts` WHERE TransactionTypeID IN ('10') AND  `memberID` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT   
`accountsID`,
`TransactionDate`,
  `TransactionTypeID`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`,
  `Comments`  FROM ".$choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                       <th scope="col">Member ID</th>
                    <th scope="col">TransactionDate</th>
                    <th scope="col">Details</th>
                    <th scope="col">Type</th>
                    <th scope="col">Comments</th>
					<th scope="col">Prev balance</th>
					<th scope="col">Amount</th>
					<th scope="col">NewBalance</th>
                  </tr>
                </thead>
                <tbody>
				
          
		   <?php
						while($row12 = $result12->fetch_assoc()) {
							$tyes = "";
							if ($row12['Credit'] == "1"){
								$tyes = "Cr";
							}else {
								$tyes = "Dr";
								
							}
						//	number_format($num, 2);
							
?>							
<tr>
     <th scope="row"><?php echo $row12['memberID']; ?></th>
                    <th scope="row"><?php echo $row12['TransactionDate']; ?></th>
                    <td><?php echo $row12['Details']; ?></td>
                    <td><?php echo $tyes; ?></td>
                    <td><?php echo $row12['Comments']; ?></td>
                    <td><?php echo number_format($row12['StartingBalance'], 2); ?></td>
					<td><?php echo  number_format($row12['Amount'], 2);  ?></td>
					<td><?php echo number_format($row12['NewBalance'], 2); ?></td>
					<td>
			  
					</td>
                  </tr>
				   
<?php						
						}
						?>
						</tbody>
						 </table>
						 </div>
						<?php
						
						} else {
						  echo "0 results";



						} 
?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}





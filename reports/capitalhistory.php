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
   $choose = "`transaction_type1_view` WHERE DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC";  
}else{
  
 $choose = "`transaction_type1_view` WHERE MemberNo IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT MemberNo, Name, Surname, DOB, Gender, TransactionDate, AmountPaid FROM " . $choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col">Member No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">DOB</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Transaction Date</th>
                    <th scope="col">Amount Paid</th>
                  </tr>
                </thead>
                <tbody>
				
          
		   <?php
						while($row12 = $result12->fetch_assoc()) {
							$statement = $conn->prepare("SELECT MemberNo FROM `tblmembers` WHERE `memberID` = ?");
							$statement->bind_param("s", $row12['memberID']);
							$statement->execute();
							$result = $statement->get_result();
							$row = $result->fetch_assoc();
							$statement->close();
							$memberID = $row['MemberNo'];
						
							$tyes = "";
							if ($row12['Credit'] == "1"){
								$tyes = "Cr";
							}else {
								$tyes = "Dr";
								
							}
						//	number_format($num, 2);
							
?>							
<tr>
     <th scope="row"><?php echo $memberID; ?></th>
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





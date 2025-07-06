
<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];
	 $d1=$_POST['from'];
	 $d2=$_POST['to'];
if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT   
`TransactionDate`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`
  FROM `alltransactions` WHERE `RetirementFundID` ='$ii' AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
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
								$tyes = "Credit";
							}else {
								$tyes = "Debit";
								
							}
						//	number_format($num, 2);
							
?>							
<tr>
                    <th scope="row"><?php echo $row12['TransactionDate']; ?></th>
                    <td><?php echo $row12['Details']; ?></td>
                    <td><?php echo $tyes; ?></td>
                    <td><?php  ?></td>
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





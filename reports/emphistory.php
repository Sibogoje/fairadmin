
<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];
	 $d1=$_POST['from'];
	 $d2=$_POST['to'];
if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT memberID, Surname, Firstname, ApprovedBenefit, NewBalance  FROM `fundsum` WHERE `EmployerID` ='$ii' AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Name</th>
                    <th scope="col">Approved Benefit</th>
                    <th scope="col">Payments</th>
					<th scope="col">Fees</th>
					<th scope="col">Interests</th>
					<th scope="col">Balance</th>
                  </tr>
                </thead>
                <tbody>
				
          
		   <?php
						while($row12 = $result12->fetch_assoc()) {
							
?>							
<tr>
                    <th scope="row"><?php echo $row12['memberID']; ?></th>
                    <td><?php echo $row12['Surname']; ?></td>
                    <td><?php echo $row12['Firstname']; ?></td>
                    <td><?php echo $row12['ApprovedBenefit']; ?></td>
                    <td><?php  ?></td>
					<td><?php  ?></td>
					<td><?php  ?></td>
					<td><?php echo $row12['NewBalance']; ?></td>
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
?>

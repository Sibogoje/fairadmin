
<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];
if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * FROM `tblmemberaccounts` WHERE `memberID` ='$ii' ORDER BY `TransactionDate` DESC");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						  echo "<option value=''>Select for Member ".$ii."</option>";
						while($row12 = $result12->fetch_assoc()) {
						?>
					<option value="<?php echo $row12['accountsID']; ?>"><?php echo "Date:   ".$row12['TransactionDate']. "        Amount:   ". $row12['Amount']. "         Type: ".$row12['Details']; ?></option>
						<?php   
						
						
						
						}
						} else {
						//  echo "0 results";
						} 
} else {
  header('location: ./');
}







<?php
require_once '../scripts/connection.php';
$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];
	 $type = $_POST['paytype'];


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
   $choose = "consol_balances WHERE DATE(FirstTransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY period DESC";  
}else{
  
 $choose = "consol_balances WHERE memberID IN ({$mntharray2}) AND DATE(FirstTransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY period DESC";   
}

/*
if(count($_POST)>0){
$stmt11 = $conn->prepare("SELECT MemberNo, MemberFirstname, MemberSurname  FROM tblmembers where MemberID = '$ii' ");
						$stmt11->execute();
						$result11 = $stmt11->get_result();
						if ($result11->num_rows > 0) {
						    	while($row11 = $result11->fetch_assoc()) {
						    	    
						    	   $mmno =  $row11['MemberNo'];
						    	   $name = $row11['MemberFirstname'];
						    	   $surname = $row11['MemberSurname'];
						    	    
						    	    
*/						    
						    
					


if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * FROM ".$choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable responsive"  id="free">
			<thead>
                  <tr>
                    <th scope="col">Member No</th>
                    <th scope="col">Name </th>
                    <th scope="col">Surname</th>
                    <th scope="col">Period</th>
                    <th scope="col">OpeningBalance</th>
					<th scope="col">ClosingBalance</th>

                  </tr>
                </thead>
                <tbody>
				
          
 <?php
						while($row12 = $result12->fetch_assoc()) {
						    
						    
						$statement = $conn->prepare("SELECT MemberNo, MemberFirstname, MemberSurname  FROM `tblmembers` WHERE `memberID` = ?");
						$statement->bind_param("s", $row12['memberID']);
											  $statement->execute();
											  $result = $statement->get_result();
											  $rowB = $result->fetch_assoc();
											  $statement->close();
						    	    
						    	   $mmno =  $rowB['MemberNo'];
						    	   $name = $rowB['MemberFirstname'];
						    	   $surname = $rowB['MemberSurname'];						    
						    
						    
						
?>							
<tr>
                    <td><?php echo $mmno; ?></td>
                    <th scope="row"><?php echo $name; ?></th>
                    <th><?php echo $surname;  ?></th>
                    <td><?php echo $row12['period']; ?></td>
                    <td><?php echo  number_format($row12['OpeningBalance'], 2); ?></td>
                    <td><?php echo  number_format($row12['ClosingBalance'], 2);  ?></td>

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
						  echo "No Data Found";



						} 
//}}}
						
?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}
?>




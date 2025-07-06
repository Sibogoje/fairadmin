
<?php
require_once '../scripts/connection.php';
$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];

	 $zer = 0;





	 
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
   $choose = "`regularpays` WHERE DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";  
}else{
  
 $choose = "`regularpays` WHERE  `memberID` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT * FROM ".$choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						
						  // output data of each row
						 ?>
					
              
					<tbody id="gruu">
          
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
                    <th scope="row"><?php echo $row12['MemberNo']; ?></th>
                    <th scope="row"><?php echo $row12['TransactionDate']; ?></th>
					<th scope="row"><?php echo $row12['MemberSurname']."".$row12['MemberFirstname']; ?></th>
                    
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
						<?php

} else {
	echo "NO Members found";
 } 

?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}
?>




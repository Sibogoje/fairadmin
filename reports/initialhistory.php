
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
   $choose = "`initialfees` WHERE DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";  
}else{
  
 $choose = "`initialfees` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT MemberNo, Name, Surname, ApprovedBenefit, Transactiodate, Amount FROM ".$choose);
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
                    <th scope="col">ApprovedBenefit</th>
                    <th scope="col">Date</th>
					<th scope="col">Amount</th>

                  </tr>
                </thead>
                <tbody>
				
          
 <?php
						while($row12 = $result12->fetch_assoc()) {
						
?>							
<tr>
                    <th scope="row"><?php echo $row12['MemberNo']; ?></th>
                    <th scope="row"><?php echo $row12['Name'];?></th>
                    <td><?php echo $row12['Surname']; ?></td>
                    <td><?php echo number_format($row12['ApprovedBenefit'], 2); ?></td>
                    <td><?php echo $row12['Transactiodate']; ?></td>
                    <td><?php echo  number_format($row12['Amount'], 2);  ?></td>

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
?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php						
						
} else {
  header('location: ./');
}
?>




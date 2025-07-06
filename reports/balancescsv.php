
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



if(count($_POST)>0){
$query = $conn->query("SELECT 
*
FROM ".$choose);

if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = $a." ". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 

    // Set column headers 
    $fields = array('MemberNo', 'Name', 'Surname', 'Period',  'OpeningBalance',  'ClosingBalance'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
        
        	$statement = $conn->prepare("SELECT MemberNo, MemberFirstname, MemberSurname  FROM `tblmembers` WHERE `memberID` = ?");
						$statement->bind_param("s", $row['memberID']);
											  $statement->execute();
											  $result = $statement->get_result();
											  $rowB = $result->fetch_assoc();
											  $statement->close();
						    	    
						    	   $mmno =  $rowB['MemberNo'];
						    	   $name = $rowB['MemberFirstname'];
						    	   $surname = $rowB['MemberSurname'];
        

        $lineData = array($mmno, $name, $surname, $row['period'],  $row['OpeningBalance'], $row['ClosingBalance']); 
        fputcsv($f, $lineData, $delimiter); 
    } 


    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
   
} 
exit; 
	 }
?>



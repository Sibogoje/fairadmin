
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

if (in_array("all", $mntharray)){

   $choose = "`tblmemberaccounts` WHERE TransactionTypeID IN ('4') AND  DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";  
}else{
  
 $choose = "`tblmemberaccounts` WHERE TransactionTypeID IN ('4') AND  `memberID` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC, accountsID DESC";   
}


if(count($_POST)>0){
// Fetch records from database 
$query = $conn->query("SELECT   
`TransactionDate`,
  `TransactionTypeID`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`,
  `Comments`
  FROM  ".$choose);

 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "ADHOC REPORT  ". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
      $tyes = "";
    // Set column headers 
    $fields = array("MemberID", "TransactionDate", "Detail s", "Type s", "Comment s", 'Prev balance', 'Amoun t', 'NewBalanc e'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
      //  $status = ($row['status'] == 1)?'Active':'Inactive'; 
      $statement = $conn->prepare("SELECT MemberNo FROM `tblmembers` WHERE `memberID` = ?");
      $statement->bind_param("s", $row['memberID']);
							$statement->execute();
							$result = $statement->get_result();
							$rowB = $result->fetch_assoc();
							$statement->close();
							//$memberID = $rowB['MemberNo'];
     
							if ($row['Credit'] == "1"){
								$tyes = "Credit";
							}else {
								$tyes = "Debit";
								
							}
        $lineData = array($rowB['MemberNo'],$row['TransactionDate'], $row['Details'], $tyes, $row['Comments'], $row['StartingBalance'], $row['Amount'], $row['NewBalance']); 
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



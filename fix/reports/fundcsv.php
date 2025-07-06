<?php 
 
// Load the database configuration file 
require_once '../scripts/connection.php'; 
 $ii = $_POST['MemberID'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];
	 
	 if(count($_POST)>0){
// Fetch records from database 
$query = $conn->query("SELECT  
 `memberID`, 
`TransactionDate`,
  `TransactionTypeID`,
  `Amount`,
  `NewBalance`
  FROM `fundsums` WHERE `RetirementFundID` ='$ii' AND DATE(TransactionDate) BETWEEN '$d1'  AND '$d2'  ORDER BY TransactionDate DESC"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "FundID ". $ii ." ". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
      $tyes = "";
    // Set column headers 
    $fields = array('memberID', 'TransactionDate', 'TransactionTypeID', 'Amount', 'NewBalance'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
      //  $status = ($row['status'] == 1)?'Active':'Inactive'; 
     
        $lineData = array($row['memberID'], $row['TransactionDate'], $row['TransactionTypeID'], $row['Amount'], $row['NewBalance']); 
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
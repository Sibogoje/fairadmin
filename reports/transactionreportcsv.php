
<?php
require_once '../scripts/connection.php';
$ii = $_POST['single'];
$d1=$_POST['date1'];
$d2=$_POST['date2'];
$type = $_POST['transtype'];


foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );

//echo $mntharray1;
$name = array($mntharray2);

// Build query based on transaction type and members
if (in_array("all", $mntharray)){
  if ($type == "all") {
    $choose = "`tblmemberaccounts` WHERE DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";  
  } else {
    $choose = "`tblmemberaccounts` WHERE TransactionTypeID = '$type' AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";  
  }
}else{
  if ($type == "all") {
    $choose = "`tblmemberaccounts` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";   
  } else {
    $choose = "`tblmemberaccounts` WHERE TransactionTypeID = '$type' AND `MemberNo` IN ({$mntharray2}) AND DATE(TransactionDate) BETWEEN '$d1' AND '$d2' ORDER BY TransactionDate DESC";   
  }
}



if(count($_POST)>0){
$query = $conn->query("SELECT 
tblmemberaccounts.MemberNo, 
tblmembers.MemberSurname, 
tblmembers.MemberFirstname, 
tblmemberaccounts.TransactionDate, 
tblmemberaccounts.Details, 
tblmemberaccounts.Amount,
tbltransactiontypes.TransactionType
FROM ".$choose." 
LEFT JOIN tblmembers ON tblmemberaccounts.MemberNo = tblmembers.MemberNo
LEFT JOIN tbltransactiontypes ON tblmemberaccounts.TransactionTypeID = tbltransactiontypes.TransactionTypeID
ORDER BY tblmemberaccounts.TransactionDate DESC");

if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "Transaction_Report_". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 

    // Set column headers 
    $fields = array('MemberNo', 'Surname', 'Firstname', 'TransactionDate', 'Transaction Type', 'Details', 'Amount'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 

        $lineData = array($row['MemberNo'], $row['MemberSurname'], $row['MemberFirstname'], $row['TransactionDate'], $row['TransactionType'], $row['Details'], $row['Amount']); 
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



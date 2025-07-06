
<?php
require_once '../scripts/connection.php';
$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];

   $choose = "
   assets mbr JOIN (
    SELECT memberID, MAX(accountsID) AS maxAccountsID
    FROM tblmemberaccounts
    WHERE TransactionDate BETWEEN '$d1' AND '$d2'
    GROUP BY memberID
) max_acc ON mbr.MemberID = max_acc.memberID
JOIN tblmemberaccounts acc ON acc.accountsID = max_acc.maxAccountsID
WHERE mbr.RetirementFundID = '$ii'
ORDER BY mbr.MemberID
   ";  

if(count($_POST)>0){
// Fetch records from database 
$query = $conn->query("SELECT mbr.MemberNo, mbr.MemberSurname, mbr.MemberFirstname, mbr.Gender, mbr.DateOfBirth, mbr.RetirementFundID, mbr.ApprovedBenefit, acc.NewBalance
  FROM  ".$choose);

 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "Assets Report  ". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
      $tyes = "";
    // Set column headers 
    $fields = array("MemberNo", "Surname", "Name", "Gender", "DOB", "Approved Benefit", "Balance"); 
    fputcsv($f, $fields, $delimiter); 

    while($row = $query->fetch_assoc()){ 

							if ($row['Credit'] == "1"){
								$tyes = "Credit";
							}else {
								$tyes = "Debit";
								}
        $lineData = array($row['MemberNo'],$row['MemberSurname'], $row['MemberFirstname'], $row['Gender'], $row['DateOfBirth'], $row['ApprovedBenefit'],  $row['NewBalance']); 
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



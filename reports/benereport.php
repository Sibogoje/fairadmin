<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
	 $id=$_POST['MemberID'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];


////////////////////retrieve deceaced id
$ttfundsresult = mysqli_query($conn, "SELECT COUNT(DISTINCT `memberID`) AS 'memberID' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '1'  "); 
$ttfundsrow = mysqli_fetch_assoc($ttfundsresult); 
$ttfundmembers = $ttfundsrow['memberID'];

$ttfundsresult = mysqli_query($conn, "SELECT COUNT(DISTINCT `memberID`) AS 'memberID' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '11'  "); 
$ttfundsrow = mysqli_fetch_assoc($ttfundsresult); 
$ClientsExit = $ClientsExit['memberID'];

$ttopening = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '1'  "); 
$ttopeningresult = mysqli_fetch_assoc($ttopening); 
$ttopeningrow = $ttopeningresult['Opening'];

$ttin = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '2'  "); 
$ttinresult = mysqli_fetch_assoc($ttin); 
$ttinrow = $ttinresult['Opening'];

$ttregular = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '3'  "); 
$ttregularresult = mysqli_fetch_assoc($ttregular); 
$ttregularrow = $ttregularresult['Opening'];

$ttadhoc = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '4'  "); 
$ttadhocresult = mysqli_fetch_assoc($ttadhoc); 
$ttadhocgrow = $ttadhocresult['Opening'];

$ttfee = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '5'  "); 
$ttfeeresult = mysqli_fetch_assoc($ttfee); 
$ttfeegrow = $ttfeeresult['Opening'];

$ttmonthly = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '6'  "); 
$ttmonthlyresult = mysqli_fetch_assoc($ttmonthly); 
$ttmonthlyrow = $ttmonthlyresult['Opening'];

$ttadmin = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '7'  "); 
$ttadminresult = mysqli_fetch_assoc($ttadmin); 
$ttadminrow = $ttadminresult['Opening'];

$ttint = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '8'  "); 
$ttintresult = mysqli_fetch_assoc($ttint); 
$ttintrow = $ttintresult['Opening'];

$ttadd = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '9'  "); 
$ttaddresult = mysqli_fetch_assoc($ttadd); 
$ttaddrow = $ttaddresult['Opening'];

$ttother = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Opening' FROM `tblmemberaccounts` WHERE `memberID` = '$id' AND DATE(`TransactionDate`) BETWEEN '$d1'  AND '$d2' AND `TransactionTypeID` = '10'  "); 
$ttotherresult = mysqli_fetch_assoc($ttother); 
$ttotherrow = $ttotherresult['Opening'];
	
$response2 = array(
	'statusCode'=>200,
	'ttfundmembers'=>$ttfundmembers,
	'ttopeningrow'=>$ttopeningrow,
	'ttotherrow'=>$ttotherrow,
	'ttaddrow'=>$ttaddrow,
	'ttintrow'=>$ttintrow,
	'ttadminrow'=>$ttadminrow,
	'ttfeegrow'=>$ttfeegrow,
	'ttadhocgrow'=>$ttadhocgrow,
	'ttregularrow'=>$ttregularrow,
	'ttinrow'=>$ttinrow,
	'ttmonthlyrow'=>$ttmonthlyrow,
	'ClientsExit'=>$ClientsExit

	
	
	
	);
	echo json_encode($response2);
}

?>
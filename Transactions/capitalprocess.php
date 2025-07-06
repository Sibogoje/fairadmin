<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];
/////////////retrieve adhoc record for corresponding adhoc id ///////////////////////////////////
$stmt = $conn->prepare("SELECT * FROM `tbltempcapital` WHERE `capitalID`=?");
$stmt->bind_param("s", $rr);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$adhocPaymentID = $row['CapitalID'];
$MemberID = $row['MemberID'];
$PaymentDate = $row['PaymentDate'];
$Details = $row['Details'];
$AdHocPayment = $row['Amount'];
$Comments = $row['Comments'];
$pdate = date("Y-m-d");	
$TransactionTypeID = 9;
$Credit = 1;

$stmtb = $conn->prepare("SELECT Newbalance FROM `balances` WHERE memberID=?");
$stmtb->bind_param("s", $MemberID);
$stmtb->execute();
$resultb = $stmtb->get_result();
if ($resultb->num_rows > 0) {
while($rowb = $resultb->fetch_assoc()) {
	
	$prebalance = $rowb['NewBalance'];
	$newb = $prebalance + $AdHocPayment;
	
	
$insertnew = $conn->prepare("insert into `tblmemberaccounts` (

  `TransactionDate`,
  `TransactionTypeID`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`,
  `Comments`

)

VALUES
  (

    ?,
    ?,
    ?,
    ?,
	?,
	?,
	?,
	?,
	?
  );");
$insertnew->bind_param("sssssssss", 
$pdate, 
$TransactionTypeID,
$MemberID,
$Details,
$Credit,
$prebalance,
$AdHocPayment,
$newb,
$Comments

);
$insertnew->execute();
$Credit = 0;
$TransactionTypeID = 5;
$AdHocPayment1 = ($AdHocPayment * 0.01);
$newbb = $newb - $AdHocPayment1;
$Details = "Additional Capital Fee";
$insertnew->bind_param("sssssssss", 
$pdate, 
$TransactionTypeID,
$MemberID,
$Details,
$Credit,
$newb,
$AdHocPayment1,
$newbb,
$Comments

);
$insertnew->execute();

$deleteadhoc = $conn->prepare("DELETE FROM `tbltempcapital` WHERE `capitalID`=? ");
$deleteadhoc->bind_param("s", $adhocPaymentID);
$deleteadhoc->execute();
	}
	$response = array(
	'statusCode'=>200,
	'datas'=>"Adhoc Payment for Member =".$MemberID." of".$AdHocPayment." was processed Succefully"
	);
	echo json_encode($response);
	
}
}

}else{
		$response = array(
	'statusCode'=>201,
	'datas'=>"No pending  Transaction"
	);
	echo json_encode($response);
}

}else{
	$response = array(
	'statusCode'=>201,
	'datas'=>"Exception: Contact Admin"
	);
	echo json_encode($response);
}


?>
<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];
/////////////retrieve adhoc record for corresponding adhoc id ///////////////////////////////////

$stmtb = $conn->prepare("SELECT * FROM `member_fees`");
//$stmtb->bind_param("s", $MemberID);
$stmtb->execute();
$resultb = $stmtb->get_result();
if ($resultb->num_rows > 0) {
while($rowb = $resultb->fetch_assoc()) {
	//$prebalance = $rowb['NewBalance'];
	$monthlyfee = $rowb['FixedMonthlyFee'];
	$prebalance = $rowb['balance'];
	$adminpercent = $rowb['AdminPercent'];
	$newb1 = $prebalance - $adminpercent;
	//$newb = $newb1  - $monthlyfee;
	$MemberID = $rowb['MemberID'];
	$pdate = date("d-m-Y");	
	
	$Details = "Admin Fees";
	$TransactionTypeID = 7;	
	$Credit = 0;
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
$Amount,
$newb,
$Comments,

);
$insertnew->execute();
$deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments1` WHERE adhocPaymentID=? ");
$deleteadhoc->bind_param("s", $adhocPaymentID);
$deleteadhoc->execute();
}
	$response = array(
	'statusCode'=>200,
	'datas'=>"Adhoc Payment for Member =".$MemberID." of".$AdHocPayment." was processed Succefully"
	);
	echo json_encode($response);
	
}else{
	
$stmtb = $conn->prepare("SELECT ApprovedBenefit FROM `tblmembers1` WHERE MemberNo=?");
$stmtb->bind_param("s", $MemberID);
$stmtb->execute();
$resultb = $stmtb->get_result();
if ($resultb->num_rows > 0) {
while($rowb = $resultb->fetch_assoc()) {
	
	$prebalance = $rowb['ApprovedBenefit'];
	$newb = $prebalance - $AdHocPayment;
	
	
$insertnew = $conn->prepare("insert into `u747325399_fairlife`.`tblMemberAccounts1` (

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
$PaymentDate, 
$TransactionTypeID,
$MemberID,
$Details,
$Credit,
$prebalance,
$AdHocPayment,
$newb,
$Comments,

);
$insertnew->execute();

$deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments1` WHERE adhocPaymentID=? ");
$deleteadhoc->bind_param("s", $adhocPaymentID);
$deleteadhoc->execute();

}

	$response = array(
	'statusCode'=>200,
	'datas'=>"Adhoc Payment for Member =".$MemberID." of".$AdHocPayment." was processed Succefully"
	);
	echo json_encode($response);
	
}else{
	$response = array(
	'statusCode'=>201,
	'datas'=>"Member ".$MemberID." does not exist"
	);
	echo json_encode($response);
	
}

	
	
}

}


?>
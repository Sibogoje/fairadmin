<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = "0";
$val = 0;
$null = NULL;
$dates = $_POST['ddate'];
/////////////retrieve all records that are not terminated ///////////////////////////////////
$stmt = $conn->prepare("SELECT 
  `MemberID`, 
  `TransactionPercent`, 
  `FixedPaymentAmount`, 
  `balance` 
FROM 
  `member_fees` 
WHERE 
  `FixedPaymentAmount` > ? 
  ;
 ");
$stmt->bind_param("s", $val);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$MemberNo = $row['MemberID'];
$update_date = $dates;
$FixedPaymentAmount = $row['FixedPaymentAmount'];
$TransactionPercent = $row['TransactionPercent'];
$prevbalance = $row['balance'];


$Details = "Regular Payments";
$Credit = "0";
$Comments = "";

$TransactionTypeID = 3;

$newest = $prevbalance - $FixedPaymentAmount;

//////insert account transaction from adhoc/////////////////////////////////////////////////////////////

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
$update_date, 
$TransactionTypeID,
$MemberNo,
$Details,
$Credit,
$prevbalance,
$FixedPaymentAmount,
$newest,
$Comments
);

if($insertnew->execute()){


	$transactionfee = $FixedPaymentAmount * ($TransactionPercent/100);
	$newest1 = $newest - $transactionfee;
	$TransactionTypeID1 = 5;
	$Details2 = "Scheduled Transaction Fee";
	
	
	$insertnew->bind_param("sssssssss", 
	$update_date, 
	$TransactionTypeID1,
	$MemberNo,
	$Details2,
	$Credit,
	$newest,
	$transactionfee,
	$newest1,
	$Comments
	);
	$insertnew->execute();



}else{

	$response = array(
		'statusCode'=>201,
		'datas'=>"Error: Scheduled Payments were no Succesful"
		);
	echo json_encode($response);

}




}
$response = array(
	'statusCode'=>200,
	'datas'=>"Success: Scheduled Payments were successful"
	);
echo json_encode($response);


}else{
	$response = array(
		'statusCode'=>201,
		'datas'=>"Error: No Members Found"
		);
	echo json_encode($response);
}


}else{
	$response = array(
		'statusCode'=>201,
		'datas'=>"Error: Error Occured, Please Contact Developer"
		);
	echo json_encode($response);
}
?>			

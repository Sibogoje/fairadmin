<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
$rr = "0";
$r1 = $_POST['id'];
$val = 0.00;
$records = 0;
/////////////retrieve all records that are not terminated ///////////////////////////////////
$stmt = $conn->prepare("SELECT `MemberNo`, `ruuning_balance`, `date_balance_updated`, `FixedPaymentAmount` FROM `tblmembers1` WHERE `Terminated`=? AND `FixedPaymentAmount`>? ");
$stmt->bind_param("ss", $rr, $val);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$date_balance_updated = $row['date_balance_updated'];
	$ruuning_balance = $row['ruuning_balance'];
	$MemberNo = $row['MemberNo'];
	$FixedPaymentAmount = $row['FixedPaymentAmount'];
	$newbalance = $ruuning_balance - $FixedPaymentAmount;
	$update_date = date("d-m-Y");
    $PaymentDate = $update_date;
	$TransactionTypeID = 3;
	$Credit = 0;
	$Details = "Regular Payment";
	$Comments = "";
	$latest = 1;
	
	
////////update latest in accounts
$getaccounts = $conn->prepare("SELECT `AccountsID` FROM `tblMemberAccounts1` WHERE `latest`=? AND `memberID`=?");
$getaccounts->bind_param("ss", $latest, $MemberNo);
$getaccounts->execute();
$result1 = $getaccounts->get_result();
if ($result1->num_rows > 0) {
	
	while($row1 = $result1->fetch_assoc()) {
				/////////////////////////////////Insert into accounts table
$AccountsID = $row1['AccountsID'];

$setzero = 0;
$fr = 1;




$insertnew = $conn->prepare("insert into `u747325399_fairlife`.`tblMemberAccounts1` (

  `TransactionDate`,
  `TransactionTypeID`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`,
  `Comments`,
  `latest`
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
	?,
	?
  );");
$insertnew->bind_param("ssssssssss", 
$PaymentDate, 
$TransactionTypeID,
$MemberNo,
$Details,
$Credit,
$ruuning_balance,
$FixedPaymentAmount,
$newbalance,
$Comments,
$latest
);
$insertnew->execute();
						$updaterunning = $conn->prepare("UPDATE `tblmembers1` SET `ruuning_balance`=?, `date_balance_updated`=? WHERE `MemberNo`=? ");
						$updaterunning->bind_param("sss", $newbalance, $update_date,  $MemberNo);
						$updaterunning->execute();

						
						$updatetransactiontbl = $conn->prepare("UPDATE `tblMemberAccounts1` SET `latest`=? WHERE `AccountsID`=? AND `latest`=? ");
						$updatetransactiontbl->bind_param("sss", $setzero, $accountsID, $fr);
						$updatetransactiontbl->execute();
						$records = $records + 1;
	
}
}else{
/////////////////////////////////Insert into accounts table

$insertnew = $conn->prepare("insert into `u747325399_fairlife`.`tblMemberAccounts1` (

  `TransactionDate`,
  `TransactionTypeID`,
  `memberID`,
  `Details`,
  `Credit`,
  `StartingBalance`,
  `Amount`,
  `NewBalance`,
  `Comments`,
  `latest`
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
	?,
	?
  );");
$insertnew->bind_param("ssssssssss", 
$PaymentDate, 
$TransactionTypeID,
$MemberNo,
$Details,
$Credit,
$ruuning_balance,
$FixedPaymentAmount,
$newbalance,
$Comments,
$latest
);
$insertnew->execute();
						$updaterunning = $conn->prepare("UPDATE `tblmembers1` SET `ruuning_balance`=?, `date_balance_updated`=? WHERE `MemberNo`=? ");
						$updaterunning->bind_param("sss", $newbalance, $update_date,  $MemberNo);
						$updaterunning->execute();	
						$records = $records + 1;
						$updatetransactiontbl = $conn->prepare("UPDATE `tblMemberAccounts1` SET `latest`=? WHERE `AccountsID`=? AND `latest`=? ");
						$updatetransactiontbl->bind_param("sss", $setzero, $accountsID, $fr);
						$updatetransactiontbl->execute();
}	


	
	
	
}


$response = array(
					'statusCode'=>200,
					'datas'=>"Sucess: ".$records." Records Processed"
					);
				echo json_encode($response);

		
}
				
}else{
	
	$response = array(
					'statusCode'=>201,
					'datas'=>"Error: No Beneficiary Record Found"
					);
				echo json_encode($response);
}
	
	?>
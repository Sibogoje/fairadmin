<?php
require_once '../scripts/connection.php';
$id = $_GET[''];
$rr = "0";
$val = 0.00;
/////////////retrieve all records that are not terminated ///////////////////////////////////
$stmt = $conn->prepare("SELECT `MemberNo`, `FixedPaymentAmount`, `ApprovedBenefit`, `RetirementFundID` FROM `tblmembers1` WHERE `Terminated`=? AND `FixedPaymentAmount`>? ");
$stmt->bind_param("ss", $rr, $val);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$MemberNo = $row['MemberNo'];
$update_date = date("Y-m-d");
$PaymentDate = $update_date;
$Details = "Regular Payment";
$RetirementFundID = $row['RetirementFundID'];
$FixedPaymentAmount = $row['FixedPaymentAmount'];	
$ApprovedBenefit = $row['ApprovedBenefit'];
$Comments = "";	
$TransactionTypeID = 3;
$Credit = 0;

		$fetchtransactionrate = $conn->prepare("SELECT `TransactionPercent`, `AdminPercent` FROM `tblretirementfundfees1` WHERE RetirementFundID=? ");
		$fetchtransactionrate->bind_param("s", $RetirementFundID);
		$fetchtransactionrate->execute();
		$rate = $fetchtransactionrate->get_result();
		if ($rate->num_rows > 0) {
		while($raterow = $rate->fetch_assoc()) {
			
		$transactionpercent = $raterow['TransactionPercent'];	
		
			
			
////////////check latest balance in transaction table
$checkbalance = $conn->prepare("SELECT * FROM `qrybalance` WHERE memberID=?");
$checkbalance->bind_param("s", $MemberNo);
$checkbalance->execute();
$balresult = $checkbalance->get_result();
if ($balresult->num_rows > 0) {
while($balrow = $balresult->fetch_assoc()) {
$prevbalance = $balrow['NewBalance'];
$accountsID = $balrow['accountsID'];	

$newest = $prevbalance - $FixedPaymentAmount;

//////insert account transaction from adhoc/////////////////////////////////////////////////////////////

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
$MemberNo,
$Details,
$Credit,
$prevbalance,
$FixedPaymentAmount,
$newest,
$Comments
);
$insertnew->execute();


$transactionfee = $FixedPaymentAmount * ($transactionpercent/100);
$newest1 = $newest - $transactionfee;
$TransactionTypeID1 = 5;
$Details2 = "Scheduled Transaction Fee";


$insertnew->bind_param("sssssssss", 
$PaymentDate, 
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
}
}else{
///// insert if there is no old transaction ///////////////////////////////////////////////
$prebal = 0;	
$TransactionTypeID = 3;
$Credit = 0;
$newest = $ApprovedBenefit - $FixedPaymentAmount;
$latest = 0;
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
$MemberNo,
$Details,
$Credit,
$ApprovedBenefit,
$FixedPaymentAmount,
$newest,
$Comments

);
$insertnew->execute();	


$transactionfee = $FixedPaymentAmount * ($transactionpercent/100);
$newest1 = $newest - $transactionfee;
$TransactionTypeID1 = 5;
$Details2 = "Scheduled Transaction Fee";


$insertnew->bind_param("sssssssss", 
$PaymentDate, 
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

}
			
			
			
}
}else{
	/*
		$response = array(
					'statusCode'=>201,
					'datas'=>"Error: Fund Fees Not Found for ".$RetirementFundID
					);
				echo json_encode($response);
				*/
}
}
		$response = array(
					'statusCode'=>200,
					'datas'=>"Success: Transactions were successful"
					);
				echo json_encode($response);

}else{
		$response = array(
					'statusCode'=>201,
					'datas'=>"Error: No Members Found"
					);
				echo json_encode($response);
}




















/*

		$fetchtransactionrate = $conn->prepare("SELECT `TransactionPercent` FROM `tblretirementfundfees1` WHERE RetirementFundID=? ");
		$fetchtransactionrate->bind_param("s", $RetirementFundID);
		$fetchtransactionrate->execute();
		$rate = $fetchtransactionrate->get_result();
		if ($rate->num_rows > 0) {
		while($raterow = $rate->fetch_assoc()) {
			
			$transactionpercent = $raterow['TransactionPercent'];			
			$transactionfee = $AdHocPayment * ($transactionpercent/100);	

			
			$newest1 = $newest - $transactionfee;
			$TransactionTypeID = "5";
			$Details = "Transaction Fee";
			$latest = 1;
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
						$MemberID,
						$Details,
						$Credit,
						$newest,
						$transactionfee,
						$newest1,
						$Comments,
						$latest
						);
						$insertnew->execute();
						$update_date = date("d-m-Y");
						$updaterunning = $conn->prepare("UPDATE `tblmembers1` SET `ruuning_balance`=?, `date_balance_updated`=? WHERE `MemberNo`=? ");
						$updaterunning->bind_param("sss", $newest1, $update_date,  $MemberID);
						$updaterunning->execute();
						$response = array(
					'statusCode'=>200,
					'datas'=>$NewBalance
					);
				echo json_encode($response);
						
		}
		}else{
			$response = array(
					'statusCode'=>201,
					'datas'=>"Error: No fundfees linked to Fund"
					);
				echo json_encode($response);
		}
						
	}
	}else{
		$response = array(
					'statusCode'=>201,
					'datas'=>"Error: No Fund linked to Deceased"
					);
				echo json_encode($response);
	}

}
}else{
	$response = array(
					'statusCode'=>201,
					'datas'=>"Error: No Deceased linked to Beneficiary"
					);
				echo json_encode($response);
}


///delete adhoc record////////////////////////////////////////////////////////////////////////
$deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments1` WHERE adhocPaymentID=? ");
$deleteadhoc->bind_param("s", $adhocPaymentID);
$deleteadhoc->execute();

	
}	
}}
	

}else{
	
$response = array(
					'statusCode'=>201,
					'datas'=>$rr
					);
				echo json_encode($response);
}

function transaction()



{}
*/
?>
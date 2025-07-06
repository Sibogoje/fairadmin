<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
$rr = "0";
$r1 = $_POST['id'];
$val = 0.00;
$records = 0;
$term = 0;
/////////////retrieve all records that are not terminated ///////////////////////////////////
$stmt = $conn->prepare("SELECT `MemberID`, `balance`, `AdminPercent`, `FixedMonthlyFee`  FROM `member_fees` WHERE `balance` > 0  ");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$update_date = date("Y-m-d");
    $PaymentDate = $r1;	
	$MemberNo = $row['MemberID'];
	$balance = $row['balance'];
	$AdminPercent = $row['AdminPercent'];
	$FixedMonthlyFee = $row['FixedMonthlyFee'];
	

	$rr2 = strtotime($r1);

    $momth = date('m', $rr2);
    $days = 31;
    if ($momth == '01' || $momth == '03' || $momth == '05' || $momth == '07' || $momth == '08' || $momth == '10' || $momth == '12'){
        $days = 31;
    }else {
        $days = 30;
    }

/*	$perc = $AdminPercent/100;
	$ruuning_balance = $balance;
	$amount = (($perc * $ruuning_balance)/365)*$days;
	$afterminusadmin = $ruuning_balance - $amount;
	*/
	
$y = $balance;  
$percent = 1.5 / 100; 
$x = ($percent * $y / 365) * $days;
$afterminusadmin = $y - $x;


	
	//$date_balance_updated = $row['date_balance_updated'];

	$TransactionTypeID = 7;
	$TransactionTypeID2 = 6;
	$Credit = 0;
	$Details = "Admin Fees";
	$Details2 = "Fixed Monthly Fee";
	$Comments = "";
	$latest = 0;
	$latest2 = 1;
	
	
////////update latest in accounts

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
$PaymentDate, 
$TransactionTypeID,
$MemberNo,
$Details,
$Credit,
$y,
$x,
$afterminusadmin,
$Comments

);
$insertnew->execute();

	$amount2 = $FixedMonthlyFee;
	$finalbalance = $afterminusadmin - $amount2;
$insertnew->bind_param("sssssssss", 
$PaymentDate, 
$TransactionTypeID2,
$MemberNo,
$Details2,
$Credit,
$afterminusadmin,
$amount2,
$finalbalance,
$Comments

);
$insertnew->execute();
	
	
	
	


	
}
$response = array(
					'statusCode'=>200,
					'datas'=>"Success: Transactions succesful"
					);
				echo json_encode($response);

}else{



$response = array(
					'statusCode'=>201,
					'error'=>"Error: There was an error in retrieving Data"
					);
				echo json_encode($response);

		
}
				
}else{
	
	$response = array(
					'statusCode'=>203,
					'error2'=>"Error: Contact Administrator"
					);
				echo json_encode($response);
}
	
	?>
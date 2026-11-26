<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
require_once __DIR__ . '/../scripts/access_control.php';
access_control_require_endpoint(['/transactions/monthlyfees.php']);
require_once '../scripts/connection.php';
set_time_limit(0);
require_once __DIR__ . '/../scripts/audit.php';
if (!function_exists('mprocess_fail')) {
    function mprocess_fail($message)
    {
        echo json_encode(['statusCode' => 201, 'datas' => $message, 'error' => $message]);
        exit;
    }
}

if(count($_POST)>0){
$rr = "0";
$r1 = trim((string)($_POST['id'] ?? ''));
$val = 0.00;
$records = 0;
$term = 0;

$periodTs = strtotime($r1);
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $r1) || $periodTs === false) {
    mprocess_fail('Error: Please choose a valid date.');
}
$periodStart = date('Y-m-01', $periodTs);
$periodEnd   = date('Y-m-t', $periodTs);
$periodLabel = date('F Y', $periodTs);

// Only one monthly fee run at a time (guards against double clicks and two users).
$lockRow = $conn->query("SELECT GET_LOCK('fairlife_monthly_fees', 0) AS l")->fetch_assoc();
if ((int)($lockRow['l'] ?? 0) !== 1) {
    mprocess_fail('A monthly fee run is already in progress. Please wait for it to finish, then check the results.');
}

// Members who already have an admin fee or fixed monthly fee in this month are
// skipped, so running the fees twice for the same month can never charge twice.
$alreadyCharged = [];
$chk = $conn->prepare("SELECT DISTINCT `memberID` FROM `tblmemberaccounts` WHERE `TransactionTypeID` IN (6, 7) AND `TransactionDate` BETWEEN ? AND ?");
$chk->bind_param('ss', $periodStart, $periodEnd);
$chk->execute();
$chkResult = $chk->get_result();
while ($c = $chkResult->fetch_assoc()) {
    $alreadyCharged[(string)$c['memberID']] = true;
}
$chk->close();
$postedCount = 0;
$skippedCount = 0;

/////////////retrieve all records that are not terminated ///////////////////////////////////
$stmt = $conn->prepare("SELECT `MemberID`, `balance`, `AdminPercent`, `FixedMonthlyFee`  FROM `member_fees` where `Terminated` = '0' ");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$update_date = date("Y-m-d");
    $PaymentDate = $r1;
	$MemberNo = $row['MemberID'];
	if (isset($alreadyCharged[(string)$MemberNo])) {
		$skippedCount++;
		continue;
	}
	$postedCount++;
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
audit_trail_log($conn, 'monthly_fee_process', 'Posted monthly fee for member ' . $MemberNo . ' amount ' . $x);

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
audit_trail_log($conn, 'monthly_fee_process', 'Posted fixed monthly fee for member ' . $MemberNo . ' amount ' . $amount2);
	
	
	
	


	
}
$conn->query("DO RELEASE_LOCK('fairlife_monthly_fees')");
audit_trail_log($conn, 'monthly_fee_run', "Monthly fees for {$periodLabel} (date {$r1}): posted {$postedCount} members, skipped {$skippedCount} already charged");

if ($postedCount === 0) {
	mprocess_fail("Monthly fees for {$periodLabel} were already posted for all {$skippedCount} members. Nothing was posted.");
}
$message = "Success: Monthly fees for {$periodLabel} posted for {$postedCount} members.";
if ($skippedCount > 0) {
	$message .= " {$skippedCount} members already had fees for {$periodLabel} and were skipped.";
}
$response = array(
					'statusCode'=>200,
					'datas'=>$message
					);
				echo json_encode($response);

}else{

$conn->query("DO RELEASE_LOCK('fairlife_monthly_fees')");
mprocess_fail('Error: There was an error in retrieving Data');

}
				
}else{
	
	$response = array(
					'statusCode'=>203,
					'error2'=>"Error: Contact Administrator"
					);
				echo json_encode($response);
}
	
	?>
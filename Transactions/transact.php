<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
	 $id=$_POST['MemberID'];

////////////////////retrieve termination amount

$ttfundsresult = mysqli_query($conn, "SELECT MemberNo,  balance, TerminationFeePercent FROM `member_fees` WHERE MemberID = '$id'  "); 
$ttfundsrow = mysqli_fetch_assoc($ttfundsresult); 
$balance = $ttfundsrow['balance'];
$TerminationFeePercent = $ttfundsrow['TerminationFeePercent'];

$ttfundmembers1 = ($TerminationFeePercent/100) * $balance;
$ttfundmembers = $balance - $ttfundmembers1;
$response2 = array(
    'statusCode'=>200,
    'ttfundmemberszz'=>$ttfundmembers


 );
    echo json_encode($response2);
}

?>
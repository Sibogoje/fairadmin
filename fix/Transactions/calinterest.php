<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
	 $date=$_POST['dates'];
	 $amount=$_POST['amount'];
	 $sourceid=$_POST['MemberID'];

$term = 0;
$ttfundsresult = mysqli_query($conn, "SELECT SUM(`NewBalance`) AS `SS` FROM `balances` where `Term` = '0' "); 
$ttfundsrow = mysqli_fetch_assoc($ttfundsresult); 
$ttfunds = $ttfundsrow['SS'];


$stmt1 = $conn->prepare("SELECT `memberID`, `NewBalance` FROM `balances` where `Term` = '0' ");
				$stmt1->execute();
				$result1 = $stmt1->get_result();
				if ($result1->num_rows > 0) {
				while($row1 = $result1->fetch_assoc()) {
					$MemberNO = $row1['memberID'];
					$ruuning_balance = $row1['NewBalance'];
					
					$fraction = ($ruuning_balance * 100) / $ttfunds;
					//$franc = $fraction * 100;
					$interest = ($fraction /100 ) *  $amount;


					
					$Newbalance = $ruuning_balance + $interest;

					$TransactionTypeID = 8;
					
					$gg = strtotime($date);
					
					$gggg = $gg -1;
					
					$gg2 =  date('F ', $gggg);
					
					$Details = "Monthly Interest  ".$gg2." Allocated";
					$Credit = 1;
					
					$Comments = "";
					$latest = 0;
					
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
$date, 
$TransactionTypeID,
$MemberNO,
$Details,
$Credit,
$ruuning_balance,
$interest,
$Newbalance,
$Comments

);
$insertnew->execute();
////////////////////insert into Interest Table					
}
$response = array(
					'statusCode'=>200,
					'dones'=>"Interest Allocated"
					);	
					echo json_encode($response);	

}else{
					$response = array(
						'statusCode'=>202,
						'error'=>"No Members Found"
						);
					echo json_encode($response);
				}			




}
	
	?>
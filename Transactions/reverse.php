<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
$amount = $_POST['amount'];
$type = $_POST['type'];
$member = $_POST['member'];
$comments = $_POST['comments'];
$TransactionDate = date("Y-m-d");
$TransactionTypeID = 10;
$Details = "Reversal";

$stmt = $conn->prepare("SELECT `StartingBalance`, `NewBalance` FROM `balances` where `memberID`='$member' ");
//$stmt->bind_param("ssss", $id, $d1, $d2, $active);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$StartingBalance = $row['StartingBalance'];	
	$NewBalance = $row['NewBalance'];	
$newest =0;	
	$Credit = 3;	
	if ($type == 1){
		$newest = $NewBalance + $amount;
		$Credit = 1;
	}else if ($type == 0){
	$newest = $NewBalance - $amount;
$Credit = 0;	
	}
	
	
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
$TransactionDate, 
$TransactionTypeID,
$member,
$Details,
$Credit,
$NewBalance,
$amount,
$newest,
$Comments

);
$insertnew->execute();
	
}
$response = array(
					'statusCode'=>200,
					'rsuccess'=>"Reversal Transaction Processed successfully"
					);
				echo json_encode($response);

}else{
	
	$response = array(
					'statusCode'=>201,
					'rerror'=>"Error: There was an Error in Adding to Report"
					);
				echo json_encode($response);
}

}

	
	?>
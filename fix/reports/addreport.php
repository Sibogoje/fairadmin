<?php
require_once '../scripts/connection.php';
set_time_limit(0);
if(count($_POST)>0){
$id=$_POST['MemberID'];
$d1=$_POST['date1'];
$d2=$_POST['date2'];
$update_date = date("d-m-Y");
	 
$treg=$_POST['treg'];
$sreg=$_POST['sreg'];

$tint=$_POST['tint'];
$sint=$_POST['sint'];

$tmonfee=$_POST['tmonfee'];
$smonfee=$_POST['smonfee'];

$tadmin=$_POST['tadmin'];
$sadmin=$_POST['sadmin'];

$tadd=$_POST['tadd'];
$sadd=$_POST['sadd'];

$tother=$_POST['tother'];
$sother=$_POST['sother'];

$ttfees=$_POST['ttfees'];
$stfees=$_POST['stfees'];

$trunning=$_POST['trunning'];
$tapproved=$_POST['tapproved'];
$tmembers=$_POST['tmembers'];

$tadhco=$_POST['tadhco'];
$sadhoc=$_POST['sadhoc'];	 

					   
	 
$left = 0;
$active = 1;
$stmt = $conn->prepare("SELECT COUNT(`MemberNo`) AS `TT`
FROM `alltransactions` WHERE `RetirementFundID`=? AND TransactionDate >=? AND TransactionDate <=? AND `Terminated`=?");
$stmt->bind_param("ssss", $id, $d1, $d2, $active);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$left = $row['TT'];	
	

$insertnew = $conn->prepare("INSERT INTO `u747325399_fairlife`.`tblfundreport` (
  `from`,
  `to`,
  `fundid`,
  `approved`,
  `balance`,
  `tmembers`,
  `when`,
  `active`
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
	?
  );");
$insertnew->bind_param("ssssssss", 
$d1, 
$d2,
$id,
$tapproved,
$trunning,
$tmembers,
$update_date,
$left
);
$insertnew->execute();	

$type = "Regular";
$insertnew1 = $conn->prepare("INSERT INTO `u747325399_fairlife`.`tblfundreport1` (
`fundid`,
  `when`,
  `from`,
  `to`,
  `type`,
  `total`,
  `sum`
)

VALUES
  (

    ?,
    ?,
    ?,
    ?,
	?,
	?,
	?
  );");
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$treg,
$sreg,
);
$insertnew1->execute();
	
$type = "Adhoc";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$tadhco,
$sadhoc,
);
$insertnew1->execute();	

	
$type = "Tother";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$tother,
$sother,
);
$insertnew1->execute();		

$type = "Tfees";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$ttfees,
$stfees,
);
$insertnew1->execute();		

$type = "Admin";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$tadd,
$sadd,
);
$insertnew1->execute();		

$type = "Monthly";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$tmonfee,
$smonfee,
);
$insertnew1->execute();		

$type = "Interest";
$insertnew1->bind_param("sssssss", 
$id, 
$update_date,
$d1,
$d2,
$type,
$tint,
$sint,
);
$insertnew1->execute();
	
	
}
}

////////////////////retrieve deceaced id

	
$response = array(
					'statusCode'=>200,
					'rsuccess'=>"Added to for fundID  ".$id." Date ". $update_date
					);
				echo json_encode($response);
}else{
	
	$response = array(
					'statusCode'=>201,
					'rerror'=>"Error: There was an Error in Adding to Report"
					);
				echo json_encode($response);
}


	
	?>
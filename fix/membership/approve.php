<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];
/////////////retrieve adhoc record for corresponding adhoc id ///////////////////////////////////
$stmt = $conn->prepare("SELECT * FROM `tblmembers` WHERE `MemberID`=?");
$stmt->bind_param("s", $rr);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    $MemberID = $row['MemberID'];
    $approvedbenefit = $row['ApprovedBenefit'];
    $terminated  = 0;
    

    $TransactionTypeID = "1";
    $Details = "Opening Balance";
    $Credit = 1;
    $prebalance = 0.00;
    $amount = $approvedbenefit;
    $newb = $approvedbenefit;
    $Comments = "";
    
    $DateAccountOpened = date('Y-m-d');
    
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
    $DateAccountOpened, 
    $TransactionTypeID,
    $MemberID,
    $Details,
    $Credit,
    $prebalance,
    $amount,
    $newb,
    $Comments
    );


    if ($insertnew->execute()) { 
  ////////////////////////////////////////////////////////////////////////////      
  if( $approvedbenefit < 20000){

  
        $TransactionTypeID = "2";
        $Details = "Transfer In Fee";

        $Credit = 0;
        $prebalance = $amount;
        $amount = 0.00 ;
        $newb = $approvedbenefit - $amount;
        $Comments = "";
        
        
        $insertnew->bind_param("sssssssss", 
        $DateAccountOpened, 
        $TransactionTypeID,
        $MemberID,
        $Details,
        $Credit,
        $prebalance,
        $amount,
        $newb,
        $Comments
        
        );
        
        if ($insertnew->execute()) { 
            $updaterunning = $conn->prepare("UPDATE `tblmembers` SET `Terminated`=? WHERE `MemberID`=? ");
            $updaterunning->bind_param("ss", $terminated,  $MemberID);
            
            if ($updaterunning->execute()) { 
                $response = array(
                    'statusCode'=>200,
                    'datas'=>"Member Approved Succesfully"
                    );
                    echo json_encode($response);
             } else {
                $response = array(
                    'statusCode'=>212,
                    'datas'=>"Member Could not be Approved, Operation Reversed"
                    );
                    echo json_encode($response);
             }



         } else {

            $response = array(
                'statusCode'=>210,
                'datas'=>"Transaction In Fee could not be processed, Approval operation aborted"
                );
                echo json_encode($response);
            
         }

  }else{



        $TransactionTypeID = "2";
        $Details = "Transfer In Fee";

        $Credit = 0;
        $prebalance = $amount;
        $amount = $approvedbenefit * 0.01 ;
        $newb = $approvedbenefit - $amount;
        $Comments = "";
        
        
        $insertnew->bind_param("sssssssss", 
        $DateAccountOpened, 
        $TransactionTypeID,
        $MemberID,
        $Details,
        $Credit,
        $prebalance,
        $amount,
        $newb,
        $Comments
        
        );
        
        if ($insertnew->execute()) { 
            $updaterunning = $conn->prepare("UPDATE `tblmembers` SET `Terminated`=? WHERE `MemberID`=? ");
            $updaterunning->bind_param("ss", $terminated,  $MemberID);
            
            if ($updaterunning->execute()) { 
                $response = array(
                    'statusCode'=>200,
                    'datas'=>"Member Approved Succesfully"
                    );
                    echo json_encode($response);
             } else {
                $response = array(
                    'statusCode'=>212,
                    'datas'=>"Member Could not be Approved, Operation Reversed"
                    );
                    echo json_encode($response);
             }



         } else {

            $response = array(
                'statusCode'=>210,
                'datas'=>"Transaction In Fee could not be processed, Approval operation aborted"
                );
                echo json_encode($response);
            
         }
        }
////////////////////////////////////////////////////////////////
     } else {
        $response = array(
            'statusCode'=>211,
            'datas'=>"Approved Balance Could Not be Updated in Transactions, Approval operation aborted"
            );
            echo json_encode($response);
     }
  
    }
}else{

	$response = array(
        'statusCode'=>210,
        'datas'=>"Error Approving New Member, Approval operation aborted"
        );
        echo json_encode($response);

}
}
?>
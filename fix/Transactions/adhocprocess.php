<?php
require_once '../scripts/connection.php';
if(count($_POST)>0){
$rr = $_POST['id'];
/////////////retrieve adhoc record for corresponding adhoc id ///////////////////////////////////
$stmt = $conn->prepare("SELECT * FROM `tbltempadhocpayments` WHERE `adhocPaymentID`=?");
$stmt->bind_param("s", $rr);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $adhocPaymentID = $row['adhocPaymentID'];
        $MemberID = $row['MemberID'];
        $PaymentDate = $row['PaymentDate'];
        $Details = $row['Details'];
        $paydate = $row['PaymentDate'];
        $AdHocPayment = $row['AdHocPayment'];
        $Comments = $row['Comments'];
        $pdate = date("d-m-Y");	
        $TransactionTypeID = 4;
        $Credit = 0;

        $stmtb = $conn->prepare("SELECT NewBalance FROM `balances` WHERE memberID=?");
        $stmtb->bind_param("s", $MemberID);
        $stmtb->execute();
        $resultb = $stmtb->get_result();

        if ($resultb->num_rows > 0) {
            while($rowb = $resultb->fetch_assoc()) {
                $prebalance = $rowb['NewBalance'];
                $newb = $prebalance - $AdHocPayment;

                // Check if prebalance is less than 0
                if ($newb < 0) {
                    $response = array(
                        'statusCode' => 220,
                        'datas' => "Adhoc Payment for Member =" . $MemberID . " of " . $AdHocPayment . " was Not Successful due to insufficient balance"
                    );
                    echo json_encode($response);
                    exit; // Terminate the script
                }

                $insertnew = $conn->prepare("INSERT INTO `tblmemberaccounts` (
                    `TransactionDate`,
                    `TransactionTypeID`,
                    `memberID`,
                    `Details`,
                    `Credit`,
                    `StartingBalance`,
                    `Amount`,
                    `NewBalance`,
                    `Comments`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");

                $insertnew->bind_param("sssssssss", 
                    $PaymentDate, 
                    $TransactionTypeID,
                    $MemberID,
                    $Details,
                    $Credit,
                    $prebalance,
                    $AdHocPayment,
                    $newb,
                    $Comments
                );

                $insertnew->execute();

                $TransactionTypeID = 5;
                $AdHocPayment1 = ($AdHocPayment * 0.02);
                $newbb = $newb - $AdHocPayment1;
                $Details = "Adhoc Transaction Fee";

                $insertnew->bind_param("sssssssss", 
                    $PaymentDate, 
                    $TransactionTypeID,
                    $MemberID,
                    $Details,
                    $Credit,
                    $prebalance,
                    $AdHocPayment1,
                    $newbb,
                    $Comments
                );

                if ($insertnew->execute()) { 
                    $deleteadhoc = $conn->prepare("DELETE FROM `tbltempadhocpayments` WHERE adhocPaymentID=? ");
                    $deleteadhoc->bind_param("s", $adhocPaymentID);
                    $deleteadhoc->execute();

                    $response = array(
                        'statusCode' => 200,
                        'datas' => "Adhoc Payment for Member =" . $MemberID . " of " . $AdHocPayment . " was processed Successfully"
                    );
                    echo json_encode($response);
                } else {
                    $response = array(
                        'statusCode' => 220,
                        'datas' => "Adhoc Payment for Member =" . $MemberID . " of " . $AdHocPayment . " was Not Successful"
                    );
                    echo json_encode($response);
                }
            }
        } else {
            $response = array(
                'statusCode' => 210,
                'retrieveerror' => "Could not Retrieve Balance for member " . $MemberID
            );
            echo json_encode($response);
        }
    }
} else {
    $response = array(
        'statusCode' => 201,
        'datas' => "Member " . $MemberID . " does not exist"
    );
    echo json_encode($response);
}

}


?>
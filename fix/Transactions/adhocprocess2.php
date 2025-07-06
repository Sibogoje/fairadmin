<?php
require_once '../scripts/connection.php';

if (count($_POST) > 0) {
    //$rr = $_POST['id'];
    /////////////retrieve adhoc record for corresponding adhoc id ///////////////////////////////////
    $stmt = $conn->prepare("SELECT * FROM `tbltempadhocpayments` ");
    //$stmt->bind_param("s", $rr);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $adhocPaymentID = $row['adhocPaymentID'];
            $MemberID = $row['MemberID'];
            $PaymentDate = $row['PaymentDate'];
            $Details = $row['Details'];
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
                while ($rowb = $resultb->fetch_assoc()) {
                    $prebalance = $rowb['NewBalance'];
                    $newb = $prebalance - $AdHocPayment;

                    // Check if prebalance is less than 0
                    // if ($newb < 0) {
                    //     // Terminate the member
                    //     terminateMember($conn, $MemberID, $PaymentDate, $Details, $Comments);

                    //     $response = array(
                    //         'statusCode' => 220,
                    //         'datas' => "Adhoc Payment for Member = " . $MemberID . " of " . $AdHocPayment . " was Not Successful due to insufficient balance and member was terminated"
                    //     );
                    //     echo json_encode($response);
                    //     exit; // Terminate the script
                    // }

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

                        echo "Adhoc Payment for Member = " . $MemberID . " of " . $AdHocPayment . " was processed Successfully";

                    } else {
                        echo "Adhoc Payment for Member = " . $MemberID . " of " . $AdHocPayment . " was Not Successful";
                    }
                }
            } else {
                echo "Could not Retrieve Balance for member " . $MemberID;
            }
        }
    } else {
        $response = array(
            'statusCode' => 201,
            'datas' => "Member " . $MemberID . " does not exist"
        );
        echo json_encode($response);

        echo "Member " . $MemberID . " does not exist";
    }
}

function terminateMember($conn, $MemberID, $PaymentDate, $Details, $Comments) {
    $Credit = '';
    $stmt = $conn->prepare("SELECT balance, TerminationFeePercent FROM `member_fees` WHERE MemberID = ?");
    $stmt->bind_param("s", $MemberID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $balance = $row['balance'];
            $percent = $row['TerminationFeePercent'];

            $Amount = ($percent / 100) * $balance;
            $newbalance = $balance - $Amount;
            $TransactionTypeID = '12';
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
                $balance,
                $Amount,
                $newbalance,
                $Comments
            );

            $insertnew->execute();
            $TransactionTypeID = '11';
            $finalbalance = 0.00;
            $Detailsfinal = 'Final Transaction';

            $insertnew->bind_param("sssssssss",
                $PaymentDate,
                $TransactionTypeID,
                $MemberID,
                $Detailsfinal,
                $Credit,
                $newbalance,
                $newbalance,
                $finalbalance,
                $Comments
            );

            if ($insertnew->execute()) {
                $update = $conn->prepare("UPDATE tblmembers SET `Terminated` = '1', `TerminationDate` = ? WHERE MemberID = ?");
                $update->bind_param("ss", $PaymentDate, $MemberID);
                $update->execute();
                echo "<script> alert('The Member Was Terminated Successfully');
                window.location.href='adhoc.php';
                </script>";
            } else {
                echo "<script> alert('There was an Error Terminating the Member');
                window.location.href='adhoc.php';
                </script>";
            }
        }
    } else {
        echo "<script> alert('Member Not Found');
        window.location.href='adhoc.php';
        </script>";
    }
}
?>

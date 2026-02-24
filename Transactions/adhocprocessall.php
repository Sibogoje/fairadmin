
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
if (!isset($_SESSION['zid'])) {
    echo json_encode(['statusCode' => 401, 'datas' => 'Unauthorized']);
    exit;
}
require_once '../scripts/connection.php';

$stmt = $conn->prepare("SELECT * FROM tbltempadhocpayments order by adhocPaymentID ASC");
$stmt->execute();
$result = $stmt->get_result();

$success = 0;
$fail = 0;
$messages = [];

while ($row = $result->fetch_assoc()) {
    $adhocPaymentID = $row['adhocPaymentID'];
    $MemberID = $row['MemberID'];
    $PaymentDate = $row['PaymentDate'];
    $Details = $row['Details'];
    $AdHocPayment = $row['AdHocPayment'];
    $Comments = $row['Comments'];
    $TransactionTypeID = 4;
    $Credit = 0;

    $stmtb = $conn->prepare("SELECT NewBalance FROM balances WHERE memberID=?");
    $stmtb->bind_param("s", $MemberID);
    $stmtb->execute();
    $resultb = $stmtb->get_result();

    if ($resultb->num_rows > 0) {
        while($rowb = $resultb->fetch_assoc()) {
            $prebalance = $rowb['NewBalance'];
            $newb = $prebalance - $AdHocPayment;
            if ($newb < 0) {
                $fail++;
                $messages[] = "Adhoc Payment for Member = $MemberID of $AdHocPayment was Not Successful due to insufficient balance";
                continue;
            }

            $insertnew = $conn->prepare("INSERT INTO tblmemberaccounts (
                TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
                $deleteadhoc = $conn->prepare("DELETE FROM tbltempadhocpayments WHERE adhocPaymentID=?");
                $deleteadhoc->bind_param("s", $adhocPaymentID);
                $deleteadhoc->execute();
                $deleteadhoc->close();
                $success++;
            } else {
                $fail++;
                $messages[] = "Adhoc Payment for Member = $MemberID of $AdHocPayment was Not Successful";
            }
            $insertnew->close();
        }
    } else {
        $fail++;
        $messages[] = "Could not Retrieve Balance for member $MemberID";
    }
    $stmtb->close();
}

$stmt->close();
$conn->close();

if ($fail == 0) {
    echo json_encode(['statusCode' => 200, 'datas' => "Successfully processed $success payments."]);
} else {
    echo json_encode(['statusCode' => 201, 'datas' => $messages, 'successCount' => $success, 'failCount' => $fail]);
}
?>

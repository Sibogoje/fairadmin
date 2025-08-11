<?php
session_start();
require_once '../scripts/connection.php';
if (!isset($_SESSION['zid'])) {
    header('Location: https://grinpath.com/fairlife/index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $adjustmentAmount = floatval($_POST['adjustmentAmount']);
    $totalBalance = 0;
    $members = [];
    $result = $conn->query("SELECT memberID, NewBalance FROM balances WHERE Term = 0 AND NewBalance > 0");
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
        $totalBalance += $row['NewBalance'];
    }
    $success = 0;
    $fail = 0;
    foreach ($members as $member) {
        $portion = ($member['NewBalance'] / $totalBalance) * $adjustmentAmount;
        $newBalance = $member['NewBalance'] - $portion;
        $stmt = $conn->prepare("INSERT INTO tblmemberaccounts (TransactionDate, TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount, NewBalance, Comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $date = date('Y-m-d');
        $transactionTypeID = 13;
        $details = 'Adjustment';
        $credit = 0;
        $comments = 'Bulk adjustment';
        $stmt->bind_param('sisssddds', $date, $transactionTypeID, $member['memberID'], $details, $credit, $member['NewBalance'], $portion, $newBalance, $comments);
        if ($stmt->execute()) {
            $success++;
            $conn->query("UPDATE balances SET NewBalance = $newBalance WHERE memberID = '{$member['memberID']}'");
        } else {
            $fail++;
        }
        $stmt->close();
    }
    $msg = "Adjustment complete. Success: $success, Fail: $fail.";
    echo "<script>alert('$msg');window.location.href='adjustment.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Adjustment</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../header.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Bulk Adjustment</h1>
    </div>
    <div class="card col-lg-6 offset-lg-3">
        <div class="card-body">
            <h5 class="card-title">Enter Adjustment Amount</h5>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="adjustmentAmount" class="form-label">Adjustment Amount</label>
                    <input type="number" step="0.01" class="form-control" id="adjustmentAmount" name="adjustmentAmount" required>
                </div>
                <button type="submit" name="submit" class="btn btn-danger">Process Adjustment</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>

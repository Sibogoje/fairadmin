<?php
session_start(); // Start the session on the index page.
include('config.php');
$memberno = $_SESSION['memberno'];

//select MemberID from tblmembers where memberno = $memberno
$stmt = $pdo->prepare("SELECT MemberID, MemberNo, MemberFirstname, MemberSurname FROM tblmembers WHERE MemberNo = :memberno");
$stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
$stmt->execute();

$member = $stmt->fetch(PDO::FETCH_ASSOC);
//$memberno = $member['MemberNo'];
$fullname = $member['MemberFirstname'] . ' ' . $member['MemberSurname'];
$memberid = $member['MemberID'];

// Check if the user is logged in, if not then redirect to login page.
?>
<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regular | Fairlife</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/style.css?version=2">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css?version=3">
    <link rel="stylesheet" id="rtlStyle" href="#">
</head>

<body class="body-padding body-p-top">
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->
    <?php
    include 'header.php';
    ?>
    <!-- main content start -->
    <div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
            <h2>Regular Payments made</h2>
    </div>

    <div class="card-body">
<?php
                   // Fetch data from tbltempadhocpayments
$stmt = $pdo->prepare("SELECT * FROM tblmemberaccounts where MemberID = :memberno AND TransactionTypeID = '3' ORDER BY TransactionDate DESC");
$stmt->bindParam(':memberno', $memberid, PDO::PARAM_STR);
//$stmt->bindParam(':memberno', $memberid, PDO::PARAM_STR);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML table structure -->
<table class="table table-dashed table-hover digi-dataTable table-striped" id="componentDataTable">
    <thead>
        
        <tr>
          
            <th>Req. date</th>
            <th>Amount</th>
            <th>Reason</th>
           
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment): ?>
            <tr>
               
                <td><?= $payment['TransactionDate'] ?></td>
                <td><?= $payment['Amount'] ?></td>
                <td><?= $payment['Comments'] ?></td>
                
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- JavaScript function to delete a row -->
<script>
    function deleteRow(btn) {
        if (confirm("Are you sure you want to delete this row?")) {
            // Get the row containing the button that was clicked
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    }
</script>
<script>
    function deleteRow(btn) {
        if (confirm("Are you sure you want to delete this row?")) {
            var row = btn.parentNode.parentNode;
            var paymentId = row.getAttribute('data-payment-id');
            
            // Send an AJAX request to delete_payment.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_payment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "Row deleted successfully.") {
                        // Remove the row from the table if the delete was successful
                        row.parentNode.removeChild(row);
                    } else {
                        alert("Failed to delete row.");
                    }
                }
            };
            xhr.send('payment_id=' + paymentId);
        }
    }
</script>

    </div>



    </div>
    <!-- main content end -->
    
    

    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery-ui.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/moment.min.js"></script>
    <script src="assets/vendor/js/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
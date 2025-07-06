<?php
session_start(); // Start the session on the index page.
include('config.php');
$memberno = $_SESSION['memberno'];

if (isset($_SESSION['memberno'])) {
    // Check if the "online" status is set to 1 in the database.
    try {
        $memberno = $_SESSION['memberno'];

        // Check if the device is logged in (online).
        $stmt = $pdo->prepare("SELECT online FROM MobUsers WHERE memberid = :memberno");
        $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['online'] == 1) {
            // User is logged in, display the index page.
           // echo "Welcome to the index page!"; // You can include your index page content here.
        } else {
            // Device is not logged in, redirect to the login page.
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // No valid session, redirect to the login page.
    header("Location: login.php");
    exit();
}

// Check if the user is logged in, if not then redirect to login page.
?>
<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhoc | Fairlife</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/vendor/css/select2.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css">
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
    <!-- main content start -->
    <div class="main-content">
        <div class="dashboard-breadcrumb mb-25">

            <h2>Beneficiary Statement</h2>
        </div>
        <div class="row">
        <div class="col-lg-6">
            <div class="panel mb-25">
            <div class="panel-header">
                <h5>Make Request</h5>
            </div>
            <div class="panel-body">
                        <form id="statementForm">
                        <div class="col-sm-6 mb-20">
                                <label for="basicInput" class="form-label">From</label>
                                <input type="date" name="from" class="form-control" id="from" required>
                            </div>

                            <div class="col-sm-6 mb-20">
                                <label for="basicInput" class="form-label">To</label>
                                <input type="date" name="to" class="form-control" id="to" required>
                            </div>

                            <button type="button" id="downloadButton" class="btn btn-warning w-100">Download</button>
                        </form>

            </div>
        </div>
        </div>
        </div>
        </div>









    <!-- main content end -->



        <!-- footer start -->
        <div class="footer">
            <p>Copyright© <script>document.write(new Date().getFullYear())</script> All Rights Reserved By <span class="text-primary">FairLife</span></p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->
    
   <script>
    document.addEventListener("DOMContentLoaded", function () {
        const downloadButton = document.getElementById("downloadButton");
        const memberNo = <?php echo json_encode($_SESSION['memberno']); ?>; // Retrieve the memberno from PHP
        
        downloadButton.addEventListener("click", function () {
            const fromValue = document.getElementById("from").value;
            const toValue = document.getElementById("to").value;

            if (fromValue && toValue) {
                // Construct the URL with query parameters, including memberno
                const url = `statement1.php?from=${fromValue}&to=${toValue}&memberno=${memberNo}`;

                // Redirect to the generated URL
                window.location.href = url;
            } else {
                alert("Please select both 'From' and 'To' dates.");
            }
        });
    });
</script>


    <script src="assets/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="assets/vendor/js/jquery-ui.min.js"></script>
    <script src="assets/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/js/apexcharts.js"></script>
    <script src="assets/vendor/js/moment.min.js"></script>
    <script src="assets/vendor/js/daterangepicker.js"></script>
    <script src="assets/vendor/js/select2.min.js"></script>
    <script src="assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/select2-init.js"></script>
</body>

</html>
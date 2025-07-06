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
    <title>CRM Dashboard | Fairlife</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/daterangepicker.css">
    <link rel="stylesheet" href="assets/vendor/css/select2.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css?version=2">
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
<?php
try {
    // Establish a database connection and execute a query to calculate the total benefit.
    $stmt = $pdo->prepare("SELECT ApprovedBenefit AS TotalBenefit, `Terminated`, DateAccountOpened FROM tblmembers where MemberNo = :memberno");
    $stmt->bindParam(':memberno', $memberno, PDO::PARAM_STR);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $terminated = $result['Terminated'];

    if ($result && isset($result['TotalBenefit'])) {
        $totalBenefit = $result['TotalBenefit'];
       

        ?>

    <!-- main content start -->
    <div class="main-content">
        <div class="dashboard-breadcrumb mb-25">
            <h2><?php echo $memberno. " - Beneficiary Dashboard"; ?></h2>
            
        </div>
        <div class="row mb-25">
            <div class="col-lg-3 col-6 col-xs-12">
                <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="right">
                            <div class="part-icon text-light rounded">
                                <span><i class="fa-light fa-book"></i></span>
                            </div>
                        </div>
                        <div class="left">
                            <h3 class="fw-normal"><?php echo "E ". number_format($totalBenefit, 2, '.', ','); ?></h3>
                        </div>
                    </div>
                    <div class="progress-box">
                        <p class="d-flex justify-content-between mb-1">Total Approved Benefit <small></small></p>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 col-xs-12">
                <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="right">
                            <div class="part-icon text-light rounded">
                                <span><i class="fa fa-check-square"></i></span>
                            </div>
                        </div>
                        <div class="left">
                            <h3 class="fw-normal"><?php 
                        if ($terminated == 0) {
                            echo "Active";
                        } else {
                            echo "Terminated";
                        }
                        ?></h3>
                        </div>
                    </div>
                    <div class="progress-box">
                        <p class="d-flex justify-content-between mb-1">Account Status <small>
                        </small></p>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-6 col-xs-12">
                <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="right">
                            <div class="part-icon text-light rounded">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="left">
                            <h3 class="fw-normal"><?php echo $result['DateAccountOpened']; ?> </h3>
                        </div>
                    </div>
                    <div class="progress-box">
                        <p class="d-flex justify-content-between mb-1">Account Opened <small></small></p>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

<?php
          
        } else {
            // Handle the case where there is no data or an error occurred.
            echo "Total benefit not available.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

try {
    // Establish a database connection and execute a query to calculate the total benefit.
    $stmt1 = $pdo->prepare("SELECT NewBalance FROM balances where M_ID = :memberno");
    $stmt1->bindParam(':memberno', $memberno, PDO::PARAM_STR);

    $stmt1->execute();

    $balances = $stmt1->fetch(PDO::FETCH_ASSOC);
    $balance = $balances['NewBalance'];

        ?>

            <div class="col-lg-3 col-6 col-xs-12">
                <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="right">
                            <div class="part-icon text-light rounded">
                                <span><i class="fa fa-percent"></i></span>
                            </div>
                        </div>
                        <div class="left">
                            <h3 class="fw-normal"><?php echo "E ". number_format($balance, 2, '.', ','); ?> </h3>
                        </div>
                    </div>
                    <?php $perc = $balance/$totalBenefit * 100; ?>
                    <div class="progress-box">
                        <p class="d-flex justify-content-between mb-1">Percentage Balance <small><?php echo number_format($perc, 0)."%"; ?></small></p>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">

                        
                            <div class="progress-bar bg-danger" style="width: <?php echo $perc."%"; ?> "></div>
                        </div>
                    </div>
                </div>
            </div>
<?php        } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }


try {
    // Establish a database connection and execute a query to calculate the total benefit.
    $stmt2 = $pdo->prepare("SELECT SUM(`Amount`) as AmountT FROM regularpays where MemberNo = :memberno");
    $stmt2->bindParam(':memberno', $memberno, PDO::PARAM_STR);
    $stmt2->execute();
    $regtotal = 0.00;
    $regular = $stmt2->fetch(PDO::FETCH_ASSOC);
    $regtotal = $regular['AmountT'];
    if ( $regtotal == "") {
        $regtotal = 0.00;
    }

        ?>    
            <div class="col-lg-3 col-6 col-xs-12">
                <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="right">
                            <div class="part-icon text-light rounded">
                                <span><i class="fa-light fa-file"></i></span>
                            </div>
                        </div>
                        <div class="left">
                            <h3 class="fw-normal"><?php echo number_format($regtotal, 2); ?></h3>
                        </div>
                    </div>
                    <?php $percreg = $regtotal/$totalBenefit * 100; ?>
                    <div class="progress-box">
                        <p class="d-flex justify-content-between mb-1">Total Regular Payments % <small>
                        <?php echo number_format($percreg, 2)."%"; ?>
                        </small></p>
                        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-danger" style="width: <?php echo $percreg."%"; ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php        } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }



    try {
        // Establish a database connection and execute a query to calculate the total benefit.
        $stmt2 = $pdo->prepare("SELECT SUM(`Amount`) as AmountT FROM payments where MemberNo = :memberno");
        $stmt2->bindParam(':memberno', $memberno, PDO::PARAM_STR);
        $stmt2->execute();
        $adhoctotal = 0.00;
        $adhoc = $stmt2->fetch(PDO::FETCH_ASSOC);
        $adhoctotal = $adhoc['AmountT'];
        if ( $adhoctotal == "") {
            $adhoctotal = 0.00;
        }
    
            ?>    
                <div class="col-lg-3 col-6 col-xs-12">
                    <div class="dashboard-top-box d-block rounded border-0 panel-bg">
                        <div class="d-flex justify-content-between align-items-center mb-20">
                            <div class="right">
                                <div class="part-icon text-light rounded">
                                    <span><i class="fa-light fa-file"></i></span>
                                </div>
                            </div>
                            <div class="left">
                                <h3 class="fw-normal"><?php echo number_format($adhoctotal, 2); ?></h3>
                            </div>
                        </div>
                        <?php $percadhoc = $adhoctotal/$totalBenefit * 100; ?>
                        <div class="progress-box">
                            <p class="d-flex justify-content-between mb-1">Total Adhoc Payments % <small>
                            <?php echo number_format($percadhoc, 2)."%"; ?>
                            </small></p>
                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-danger" style="width: <?php echo $percadhoc."%"; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }




    
    ?>





        </div>










        <!-- footer start -->
        <div class="footer">
            <p>Copyright© <script>document.write(new Date().getFullYear())</script> All Rights Reserved By <span class="text-primary">FairLife</span></p>
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->
    
    <!-- Add Task Modal Start -->

    <!-- Add Task Modal End -->

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
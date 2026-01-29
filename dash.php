
<?php
// Enable errors temporarily to diagnose missing content
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(isset($_SESSION['zid']))
{
  $gg = isset($_SESSION['user']) ? $_SESSION['user'] : '';
  include 'scripts/connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="logo.png" rel="icon">
  <link href="logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/dashboard.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <?php 
  include 'header.php';
  ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Fairlife Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dash.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card h-100">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
<?php

// Total funds and average balance insight
$ttfundsresult = mysqli_query($conn, 'SELECT ttfunds FROM tt_funds'); 
$ttfundsrow = mysqli_fetch_assoc($ttfundsresult); 
$ttfunds = isset($ttfundsrow['ttfunds']) ? $ttfundsrow['ttfunds'] : 0;

$bal_summary = mysqli_query($conn, "SELECT SUM(`NewBalance`) as newb, COUNT(DISTINCT `memberID`) as accs FROM balances WHERE `Term` = '0'");
$bal_row = mysqli_fetch_assoc($bal_summary);
$balance_sum_all = isset($bal_row['newb']) ? $bal_row['newb'] : 0;
$accounts_count = isset($bal_row['accs']) ? (int)$bal_row['accs'] : 0;
$avg_balance = $accounts_count ? ($balance_sum_all / $accounts_count) : 0;


?>
                <div class="card-body">
                  <h5 class="card-title">Funds <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white">
                      <i class="ri ri-coins-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6 class="mb-0"><?php echo "Total E ".number_format($ttfunds,2); ?></h6>
                      <small class="text-muted">Avg balance per member: E <?php echo number_format($avg_balance,2); ?></small>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card h-100">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
<?php

$activeresult = mysqli_query($conn, "SELECT COUNT(`MemberNo`) as ttactive FROM tblmembers WHERE `Terminated` = '0'"); 
$activerow = mysqli_fetch_assoc($activeresult); 
$active = isset($activerow['ttactive']) ? (int)$activerow['ttactive'] : 0;

$terminatedresult = mysqli_query($conn, "SELECT COUNT(`MemberNo`) as ttterminated FROM tblmembers WHERE `Terminated` = '1'"); 
$terminatedrow = mysqli_fetch_assoc($terminatedresult); 
$terminated = isset($terminatedrow['ttterminated']) ? (int)$terminatedrow['ttterminated'] : 0;
$total_bens = $active + $terminated;
$active_pct = $total_bens ? round(($active / $total_bens) * 100, 1) : 0;
$terminated_pct = $total_bens ? round(($terminated / $total_bens) * 100, 1) : 0;
?>
                <div class="card-body">
                  <h5 class="card-title">Beneficiaries <span>| This Month</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                      <i class="ri ri-group-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6 class="mb-0"><?php echo "Total ". number_format($total_bens); ?></h6>
                      <small class="text-muted">Active: <span class="fw-bold text-success"><?php echo $active; ?></span> (<?php echo $active_pct; ?>%) &nbsp; Terminated: <span class="fw-bold text-danger"><?php echo $terminated; ?></span> (<?php echo $terminated_pct; ?>%)</small>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- BALANCES Card -->
            <div class="col-xxl-4 col-md-6">

              <div class="card info-card customers-card h-100">


                <div class="card-body">
                  <h5 class="card-title">Total Balances <span>| Today</span></h5>
<?php

// Total balances and low-balance insight
$balanceresult = mysqli_query($conn, "SELECT SUM(`NewBalance`) as newb FROM balances WHERE `Term` = '0'"); 
$balancerow = mysqli_fetch_assoc($balanceresult); 
$balance = isset($balancerow['newb']) ? $balancerow['newb'] : 0;

$lowcount_res = mysqli_query($conn, "SELECT COUNT(DISTINCT `memberID`) AS lowcount FROM balances WHERE NewBalance < '5000.00' AND Term = 0");
$lowrow = mysqli_fetch_assoc($lowcount_res);
$low_count = isset($lowrow['lowcount']) ? (int)$lowrow['lowcount'] : 0;

?>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                      <i class="ri ri-file-shred-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6 class="mb-0"><?php echo "E ".number_format($balance, 2); ?></h6>
                      <small class="text-muted"><?php echo number_format($low_count); ?> accounts below E 5,000</small>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End bALANCES Card -->
            
            
    <!-- Capital Intro Card -->         
 <div class="col-xxl-4 col-md-6">

              <div class="card info-card customers-card h-100">


                <div class="card-body">
                  <h5 class="card-title">Total Adhoc Fees <span>| Today</span></h5>
<?php

$balanceresult = mysqli_query($conn, "SELECT SUM(`Amount`) AS 'Adhoc' FROM tblmemberaccounts WHERE `TransactionTypeID` = '5' AND MONTH(TransactionDate) = '2' AND YEAR(TransactionDate) = '2023'    "); 
$balancerow = mysqli_fetch_assoc($balanceresult); 
$balance = $balancerow['Adhoc'];

?>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri ri-file-shred-line"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo "E ".number_format($balance, 2); ?></h6>
                      <span class="text-muted small pt-2 ps-1">Current Month</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Capital Intro Card -->
            

            <!-- Reports card removed per request -->



           

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">



                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Admin - Beneficiary Tables
                  </div>
                </div><!-- End activity item-->








              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Expenditure <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Requested', 'Approved']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'School Fees',
                          max: 6500
                        },
                        {
                          name: 'Exam Prep',
                          max: 16000
                        },
                        {
                          name: 'Food',
                          max: 30000
                        },
                        {
                          name: 'Support',
                          max: 38000
                        },
                        {
                          name: 'Transport',
                          max: 52000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Requested'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Approved'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
        

          <!-- News & Updates Traffic -->
        

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ --
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
<?php
}else{
    header('Location: index.php');
}

?>
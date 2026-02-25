<?php
require_once __DIR__ . '/scripts/bootstrap.php';
// Error reporting for development only
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Redirect if not logged in
if (!isset($_SESSION['zid'])) {
    header('Location: index.php');
    exit();
}

// Get user from session
$gg = $_SESSION['user'] ?? '';
require_once 'scripts/connection.php';

// Fetch dashboard data
$dashboardData = fetchDashboardData($conn);

// Fetch low balance members
$lowBalanceMembers = fetchLowBalanceMembers($conn, 50);

/**
 * Fetch all dashboard data in a single function
 */
function fetchDashboardData($conn) {
    $data = [];
    
    // Total funds and average balance
    $ttFundsResult = mysqli_query($conn, 'SELECT ttfunds FROM tt_funds');
    $ttFundsRow = mysqli_fetch_assoc($ttFundsResult);
    $data['total_funds'] = $ttFundsRow['ttfunds'] ?? 0;
    
    // Balance summary
    $balanceSummary = mysqli_query($conn, 
        "SELECT SUM(`NewBalance`) as newb, COUNT(DISTINCT `memberID`) as accs 
         FROM balances WHERE `Term` = '0'"
    );
    $balanceRow = mysqli_fetch_assoc($balanceSummary);
    $data['balance_sum_all'] = $balanceRow['newb'] ?? 0;
    $data['accounts_count'] = (int)($balanceRow['accs'] ?? 0);
    $data['avg_balance'] = $data['accounts_count'] ? 
        ($data['balance_sum_all'] / $data['accounts_count']) : 0;
    
    // Beneficiaries
    $activeResult = mysqli_query($conn, 
        "SELECT COUNT(`MemberNo`) as ttactive FROM tblmembers WHERE `Terminated` = '0'"
    );
    $activeRow = mysqli_fetch_assoc($activeResult);
    $data['active_count'] = (int)($activeRow['ttactive'] ?? 0);
    
    $terminatedResult = mysqli_query($conn, 
        "SELECT COUNT(`MemberNo`) as ttterminated FROM tblmembers WHERE `Terminated` = '1'"
    );
    $terminatedRow = mysqli_fetch_assoc($terminatedResult);
    $data['terminated_count'] = (int)($terminatedRow['ttterminated'] ?? 0);
    
    $data['total_beneficiaries'] = $data['active_count'] + $data['terminated_count'];
    $data['active_percentage'] = $data['total_beneficiaries'] ? 
        round(($data['active_count'] / $data['total_beneficiaries']) * 100, 1) : 0;
    $data['terminated_percentage'] = $data['total_beneficiaries'] ? 
        round(($data['terminated_count'] / $data['total_beneficiaries']) * 100, 1) : 0;
    
    // Low balance accounts
    $lowBalanceResult = mysqli_query($conn, 
        "SELECT COUNT(DISTINCT `memberID`) AS lowcount 
         FROM balances WHERE NewBalance < '5000.00' AND Term = 0"
    );
    $lowBalanceRow = mysqli_fetch_assoc($lowBalanceResult);
    $data['low_balance_count'] = (int)($lowBalanceRow['lowcount'] ?? 0);
    
    // Current month adhoc fees
    $currentMonth = date('n');
    $currentYear = date('Y');
    $adhocResult = mysqli_query($conn, 
        "SELECT SUM(`Amount`) AS 'Adhoc' 
         FROM tblmemberaccounts 
         WHERE `TransactionTypeID` = '5' 
         AND MONTH(TransactionDate) = '$currentMonth' 
         AND YEAR(TransactionDate) = '$currentYear'"
    );
    $adhocRow = mysqli_fetch_assoc($adhocResult);
    $data['current_month_adhoc'] = $adhocRow['Adhoc'] ?? 0;
    
    return $data;
}

/**
 * Format currency display
 */
function formatCurrency($amount) {
    return 'E ' . number_format($amount, 2);
}

/**
 * Format number with thousands separator
 */
function formatNumber($number) {
    return number_format($number);
}

/**
 * Fetch members with balance less than 5000
 */
function fetchLowBalanceMembers($conn, $limit = 20) {
    $query = "
        SELECT 
            m.MemberID,
            m.MemberNo,
            m.MemberSurname,
            m.MemberFirstname,
            b.NewBalance
        FROM tblmembers m
        INNER JOIN balances b ON m.MemberID = b.memberID
        WHERE b.NewBalance < 5000 
        AND b.Term = 0
        AND m.Terminated = 0
        ORDER BY b.NewBalance ASC
        LIMIT $limit
    ";
    
    $result = mysqli_query($conn, $query);
    $members = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
    }
    
    return $members;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fairlife Dashboard</title>
    
    <meta name="description" content="Fairlife Dashboard">
    <meta name="keywords" content="dashboard, management, fairlife">
    
    <!-- Favicons -->
    <link href="logo.png" rel="icon">
    <link href="logo.png" rel="apple-touch-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&family=Nunito:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
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
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Template Attribution -->
    <!--
    * Template Name: NiceAdmin - v2.2.2
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    -->
</head>

<body class="loading-dashboard">
    <!-- Header -->
    <?php
require_once __DIR__ . '/scripts/bootstrap.php';
include 'header.php'; ?>
    
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Fairlife Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dash.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        
        <section class="section dashboard">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        
                        <!-- Funds Card -->
                        <div class="col-lg-3 col-md-6">
                            <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo renderCard(
                                'Funds',
                                'Today',
                                'bg-warning',
                                'ri-coins-fill',
                                formatCurrency($dashboardData['total_funds']),
                                'Avg balance per member: ' . formatCurrency($dashboardData['avg_balance'])
                            ); ?>
                        </div>
                        
                        <!-- Beneficiaries Card -->
                        <div class="col-lg-3 col-md-6">
                            <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo renderCard(
                                'Beneficiaries',
                                'This Month',
                                'bg-success',
                                'ri-group-fill',
                                'Total ' . formatNumber($dashboardData['total_beneficiaries']),
                                sprintf(
                                    'Active: <span class="fw-bold text-success">%s</span> (%s%%) &nbsp; Terminated: <span class="fw-bold text-danger">%s</span> (%s%%)',
                                    formatNumber($dashboardData['active_count']),
                                    $dashboardData['active_percentage'],
                                    formatNumber($dashboardData['terminated_count']),
                                    $dashboardData['terminated_percentage']
                                )
                            ); ?>
                        </div>
                        
                        <!-- Total Balances Card -->
                        <div class="col-lg-3 col-md-6">
                            <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo renderCard(
                                'Total Balances',
                                'Today',
                                'bg-primary',
                                'ri-file-shred-line',
                                formatCurrency($dashboardData['balance_sum_all']),
                                formatNumber($dashboardData['low_balance_count']) . ' accounts below E 5,000'
                            ); ?>
                        </div>
                        
                        <!-- Adhoc Fees Card -->
                        <div class="col-lg-3 col-md-6">
                            <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo renderCard(
                                'Total Adhoc Fees',
                                'Today',
                                'bg-info',
                                'ri-file-shred-line',
                                formatCurrency($dashboardData['current_month_adhoc']),
                                'Current Month'
                            ); ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Low Balance Members Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-alert-fill text-warning"></i> Members with Balance Below E 5,000
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($lowBalanceMembers) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Member No</th>
                                                <th>Name</th>
                                                <th>Balance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lowBalanceMembers as $member): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-light text-dark">
                                                            <?php echo htmlspecialchars($member['MemberNo']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlspecialchars($member['MemberSurname'] . ', ' . $member['MemberFirstname']); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger">
                                                            E <?php echo number_format($member['NewBalance'], 2); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="membership/profile.php?id=<?php echo htmlspecialchars($member['MemberID']); ?>" 
                                                           class="btn btn-sm btn-primary" 
                                                           title="View Member Profile">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">
                                    Showing <?php echo count($lowBalanceMembers); ?> members with balance below E 5,000 
                                    (sorted by lowest balance first)
                                </small>
                            <?php else: ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle"></i>
                                    Great news! No members with balance below E 5,000 found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
    
    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.min.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    
    <!-- Dashboard JS (skeleton loaders, small UI helpers) -->
    <script src="assets/js/dashboard.js"></script>
    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/scripts/bootstrap.php';
/**
 * Render a dashboard card component
 */
function renderCard($title, $subtitle, $iconBgClass, $iconClass, $mainValue, $subValue) {
    ob_start();
    ?>
    <div class="card info-card sales-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo htmlspecialchars($title); ?> <span>| <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo htmlspecialchars($subtitle); ?></span></h5>
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
            </div>
        </div>
        
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo $iconBgClass; ?> text-white">
                    <i class="ri <?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo $iconClass; ?>"></i>
                </div>
                <div class="ps-3">
                    <div class="real">
                        <h6 class="mb-0"><?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo $mainValue; ?></h6>
                        <small class="text-muted"><?php
require_once __DIR__ . '/scripts/bootstrap.php';
echo $subValue; ?></small>
                    </div>
                    <div class="skeleton" aria-hidden="true">
                        <div class="skeleton-amount"></div>
                        <div class="skeleton-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
require_once __DIR__ . '/scripts/bootstrap.php';
return ob_get_clean();
}

// Close database connection
mysqli_close($conn);
?>
<?php
require_once __DIR__ . '/scripts/bootstrap.php';
require_once __DIR__ . '/scripts/connection.php';
require_once __DIR__ . '/scripts/access_control.php';
require_once __DIR__ . '/scripts/audit.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

$role = access_control_normalize_role($_SESSION['role'] ?? '');
$username = $gg ?? ($_SESSION['user'] ?? 'User');

$currentRoutePath = access_control_route_path();
if (!access_control_is_allowed($role, $currentRoutePath)) {
  access_control_forbidden();
}

$canRoute = static function (string $relativePath) use ($role): bool {
  return access_control_can_show_menu_item($role, '/' . ltrim($relativePath, '/'));
};

$showBeneficiary =
  $canRoute('membership/new.php') ||
  $canRoute('membership/') ||
  $canRoute('membership/benlist.php') ||
  $canRoute('membership/dnew.php') ||
  $canRoute('membership/deceased.php') ||
  $canRoute('membership/pending.php');

$showFunds =
  $canRoute('fund/fnew.php') ||
  $canRoute('fund/') ||
  $canRoute('fund/assets.php') ||
  $canRoute('fund/fundsummary.php') ||
  $canRoute('fund/enew.php') ||
  $canRoute('fund/employers.php');

$showTransactions =
  $canRoute('Transactions/clientr.php') ||
  $canRoute('Transactions/adhoc.php') ||
  $canRoute('Transactions/scheduled.php') ||
  $canRoute('Transactions/interest.php') ||
  $canRoute('Transactions/monthlyfees.php') ||
  $canRoute('Transactions/adjustment.php') ||
  $canRoute('Transactions/additionalcapital.php') ||
  $canRoute('Transactions/terminate.php') ||
  $canRoute('Transactions/othertransactions.php');

$showFiles = $canRoute('files.php') || $canRoute('viewfiles.php');

$showReports =
  $canRoute('membership/profile.php') ||
  $canRoute('membership/membersummary.php') ||
  $canRoute('membership/profileaccount.php') ||
  $canRoute('reports/beneficiaries.php') ||
  $canRoute('membership/existence.php') ||
  $canRoute('membership/membermove.php') ||
  $canRoute('membership/consolsummary.php') ||
  $canRoute('reports/funds.php') ||
  $canRoute('reports/initialfees.php') ||
  $canRoute('reports/payments.php') ||
  $canRoute('reports/balances.php') ||
  $canRoute('reports/deceased.php') ||
  $canRoute('fund/fundfeesreport.php') ||
  $canRoute('reports/scheduledreport.php') ||
  $canRoute('reports/transfees.php') ||
  $canRoute('reports/termination.php') ||
  $canRoute('reports/capitalintroductionreport.php') ||
  $canRoute('reports/adhocreport.php') ||
  $canRoute('reports/employers.php') ||
  $canRoute('reports/otherreport.php') ||
  $canRoute('reports/interestreport.php') ||
  $canRoute('reports/monthlyfeesreport.php');

$showSettings =
  $canRoute('settings/banks.php') ||
  $canRoute('settings/post.php') ||
  $canRoute('users/transaction.php') ||
  $canRoute('users/fees.php');

$showUsers = $canRoute('users/local2.php') || $canRoute('users/access_control_manager.php');

$notificationCount = 0;
$lowBalanceRows = [];

try {
  $countResult = mysqli_query(
    $conn,
    "SELECT COUNT(DISTINCT memberID) AS value_sum FROM balances WHERE NewBalance < 5000.00 AND Term = 0"
  );
  if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $notificationCount = (int)($countRow['value_sum'] ?? 0);
  }

  $stmt = $conn->prepare("SELECT balance, MemberNo FROM member_fees WHERE balance < 5000.00 AND Terminated = 0");
  $stmt->execute();
  $result = $stmt->get_result();
  while ($result && $row = $result->fetch_assoc()) {
    $lowBalanceRows[] = $row;
  }
  $stmt->close();
} catch (mysqli_sql_exception $e) {
  error_log('Header notification query error: ' . $e->getMessage());
  $notificationCount = 0;
  $lowBalanceRows = [];
}
?>
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="<?= app_url('dash.php') ?>" class="logo d-flex align-items-center">
      <img src="<?= app_url('logo.png') ?>" alt="">
      <span class="d-none d-lg-block">Fairlife</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="POST" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>

      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?= app_url('logo.png') ?>" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?= htmlspecialchars($username) ?></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li><hr class="dropdown-divider"></li>
          <li id="logout">
            <a class="dropdown-item d-flex align-items-center" href="<?= app_url('logout.php') ?>">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
        </ul>
      </li>

    </ul>
  </nav>
</header>

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <?php if (access_control_can_show_menu_item($role, '/dash.php')): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= app_url('dash.php') ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($showBeneficiary): ?>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="">
        <i class="bi bi-menu-button-wide"></i><span>Beneficiary</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('membership/new.php')): ?>
          <li><a href="<?= app_url('membership/new.php') ?>"><i class="bi bi-circle"></i><span>New Beneficiary</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/')): ?>
          <li><a href="<?= app_url('membership/') ?>"><i class="bi bi-circle"></i><span>All Beneficiaries</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/benlist.php')): ?>
          <li><a href="<?= app_url('membership/benlist.php') ?>"><i class="bi bi-circle"></i><span>Employer Beneficiary Lists</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/dnew.php')): ?>
          <li><a href="<?= app_url('membership/dnew.php') ?>"><i class="bi bi-circle"></i><span>New Deceased</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/deceased.php')): ?>
          <li><a href="<?= app_url('membership/deceased.php') ?>"><i class="bi bi-circle"></i><span>Deceased Profiles</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('membership/pending.php')): ?>
          <li><a href="<?= app_url('membership/pending.php') ?>"><i class="bi bi-circle"></i><span>Pending Approval</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($showFunds): ?>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-journal-text"></i><span>Funds Report</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('fund/fnew.php')): ?>
          <li><a href="<?= app_url('fund/fnew.php') ?>"><i class="bi bi-circle"></i><span>New Fund</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('fund/')): ?>
          <li><a href="<?= app_url('fund/') ?>"><i class="bi bi-circle"></i><span>All Funds</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('fund/assets.php')): ?>
          <li><a href="<?= app_url('fund/assets.php') ?>"><i class="bi bi-circle"></i><span>Fund Assets</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('fund/fundsummary.php')): ?>
          <li><a href="<?= app_url('fund/fundsummary.php') ?>"><i class="bi bi-circle"></i><span>Funds Summary</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('fund/enew.php')): ?>
          <li><a href="<?= app_url('fund/enew.php') ?>"><i class="bi bi-circle"></i><span>New Employer</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('fund/employers.php')): ?>
          <li><a href="<?= app_url('fund/employers.php') ?>"><i class="bi bi-circle"></i><span>All Employers</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($showTransactions): ?>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Transactions</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('Transactions/clientr.php')): ?>
          <li><a href="<?= app_url('Transactions/clientr.php') ?>"><i class="bi bi-circle"></i><span>Client Requests</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/adhoc.php')): ?>
          <li><a href="<?= app_url('Transactions/adhoc.php') ?>"><i class="bi bi-circle"></i><span>Adhoc Payments</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/scheduled.php')): ?>
          <li><a href="<?= app_url('Transactions/scheduled.php') ?>"><i class="bi bi-circle"></i><span>Scheduled Payments</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/interest.php')): ?>
          <li><a href="<?= app_url('Transactions/interest.php') ?>"><i class="bi bi-circle"></i><span>Interest Payment</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('Transactions/monthlyfees.php')): ?>
          <li><a href="<?= app_url('Transactions/monthlyfees.php') ?>"><i class="bi bi-circle"></i><span>Monthly Fees Payment</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/adjustment.php')): ?>
          <li><a href="<?= app_url('Transactions/adjustment.php') ?>"><i class="bi bi-circle"></i><span>Adjustment</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/additionalcapital.php')): ?>
          <li><a href="<?= app_url('Transactions/additionalcapital.php') ?>"><i class="bi bi-circle"></i><span>Additional Capital</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('Transactions/terminate.php')): ?>
          <li><a href="<?= app_url('Transactions/terminate.php') ?>"><i class="bi bi-circle"></i><span>Terminate Member</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('Transactions/othertransactions.php')): ?>
          <li><a href="<?= app_url('Transactions/othertransactions.php') ?>"><i class="bi bi-circle"></i><span>Other Transactions</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($canRoute('membership/newfile.php')): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= app_url('membership/newfile.php') ?>">
          <i class="bi bi-file-earmark-medical-fill"></i>
          <span>All Member Files</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($showFiles): ?>
    <li class="nav-heading">Files</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#files-nav" data-bs-toggle="collapse" href="">
        <i class="bi bi-files-alt"></i><span>Files</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="files-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('files.php')): ?>
          <li><a href="<?= app_url('files.php') ?>"><i class="bi bi-upload"></i><span>Uplaod Files</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('viewfiles.php')): ?>
          <li><a href="<?= app_url('viewfiles.php') ?>"><i class="bi bi-eye"></i><span>View Files</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($showReports): ?>
    <li class="nav-heading">Reports</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
        <i class="ri ri-todo-fill"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="reports-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('membership/profile.php')): ?>
          <li><a href="<?= app_url('membership/profile.php') ?>"><i class="bi bi-circle"></i><span>Benefit Statement</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/membersummary.php')): ?>
          <li><a href="<?= app_url('membership/membersummary.php') ?>"><i class="bi bi-circle"></i><span>Summary Statement</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/profileaccount.php')): ?>
          <li><a href="<?= app_url('membership/profileaccount.php') ?>"><i class="bi bi-circle"></i><span>Statement</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/beneficiaries.php')): ?>
          <li><a href="<?= app_url('reports/beneficiaries.php') ?>"><i class="bi bi-circle"></i><span>Beneficiary Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/existence.php')): ?>
          <li><a href="<?= app_url('membership/existence.php') ?>"><i class="bi bi-circle"></i><span>Existence Certificate</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('membership/membermove.php')): ?>
          <li><a href="<?= app_url('membership/membermove.php') ?>"><i class="bi bi-circle"></i><span>New Entrant Statement</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('membership/consolsummary.php')): ?>
          <li><a href="<?= app_url('membership/consolsummary.php') ?>"><i class="bi bi-circle"></i><span>Beneficiary List</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/funds.php')): ?>
          <li><a href="<?= app_url('reports/funds.php') ?>"><i class="bi bi-circle"></i><span>Funds</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/initialfees.php')): ?>
          <li><a href="<?= app_url('reports/initialfees.php') ?>"><i class="bi bi-circle"></i><span>Individual Initial Fees Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/payments.php')): ?>
          <li><a href="<?= app_url('reports/payments.php') ?>"><i class="bi bi-circle"></i><span>Individual Payments Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/balances.php')): ?>
          <li><a href="<?= app_url('reports/balances.php') ?>"><i class="bi bi-circle"></i><span>Individual Balances</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/deceased.php')): ?>
          <li><a href="<?= app_url('reports/deceased.php') ?>"><i class="bi bi-circle"></i><span>Deceased Member Report</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('fund/fundfeesreport.php')): ?>
          <li><a href="<?= app_url('fund/fundfeesreport.php') ?>"><i class="bi bi-circle"></i><span>Fees Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/scheduledreport.php')): ?>
          <li><a href="<?= app_url('reports/scheduledreport.php') ?>"><i class="bi bi-circle"></i><span>Scheduled Report</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('reports/transfees.php')): ?>
          <li><a href="<?= app_url('reports/transfees.php') ?>"><i class="bi bi-circle"></i><span>Transaction Fees Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/termination.php')): ?>
          <li><a href="<?= app_url('reports/termination.php') ?>"><i class="bi bi-circle"></i><span>Termination Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/capitalintroductionreport.php')): ?>
          <li><a href="<?= app_url('reports/capitalintroductionreport.php') ?>"><i class="bi bi-circle"></i><span>Capital Transfer In Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/adhocreport.php')): ?>
          <li><a href="<?= app_url('reports/adhocreport.php') ?>"><i class="bi bi-circle"></i><span>Adhoc Report</span></a></li>
        <?php endif; ?>

        <?php if ($canRoute('reports/employers.php')): ?>
          <li><a href="<?= app_url('reports/employers.php') ?>"><i class="bi bi-circle"></i><span>Employer</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/otherreport.php')): ?>
          <li><a href="<?= app_url('reports/otherreport.php') ?>"><i class="bi bi-circle"></i><span>Individual Other Transactions</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/interestreport.php')): ?>
          <li><a href="<?= app_url('reports/interestreport.php') ?>"><i class="bi bi-circle"></i><span>Individual Interest Report</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('reports/monthlyfeesreport.php')): ?>
          <li><a href="<?= app_url('reports/monthlyfeesreport.php') ?>"><i class="bi bi-circle"></i><span>Individual Admin & Monthly Fees</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($showSettings): ?>
    <li class="nav-heading">System Settings</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
        <i class="ri ri-tools-fill"></i><span>Configure System Constants</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php if ($canRoute('settings/banks.php')): ?>
          <li><a href="<?= app_url('settings/banks.php') ?>"><i class="bi bi-circle"></i><span>Banks</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('settings/post.php')): ?>
          <li><a href="<?= app_url('settings/post.php') ?>"><i class="bi bi-circle"></i><span>Post Offices</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('users/transaction.php')): ?>
          <li><a href="<?= app_url('users/transaction.php') ?>"><i class="bi bi-circle"></i><span>Transaction Types</span></a></li>
        <?php endif; ?>
        <?php if ($canRoute('users/fees.php')): ?>
          <li><a href="<?= app_url('users/fees.php') ?>"><i class="bi bi-circle"></i><span>Fees Types</span></a></li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; ?>

    <?php if ($showUsers): ?>
      <li class="nav-heading">Users Management</li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person-lines-fill"></i><span>System Users</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="users-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <?php if ($canRoute('users/local2.php')): ?>
            <li><a href="<?= app_url('users/local2.php') ?>"><i class="bi bi-circle"></i><span>Local System Users</span></a></li>
          <?php endif; ?>
          <?php if ($canRoute('users/access_control_manager.php')): ?>
            <li><a href="<?= app_url('users/access_control_manager.php') ?>"><i class="bi bi-circle"></i><span>Access Control Manager</span></a></li>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>

  </ul>
</aside>

<script>
function activityWatcher() {
    var secondsSinceLastActivity = 0;
    var maxInactivity = (60 * 25);

    setInterval(function () {
        secondsSinceLastActivity++;
        if (secondsSinceLastActivity > maxInactivity) {
            location.href = '<?= app_url('logout.php') ?>';
        }
    }, 1000);

    function activity() {
        secondsSinceLastActivity = 0;
    }

    var activityEvents = [
        'mousedown', 'mousemove', 'keydown',
        'scroll', 'touchstart'
    ];

    activityEvents.forEach(function (eventName) {
        document.addEventListener(eventName, activity, true);
    });
}

activityWatcher();
</script>

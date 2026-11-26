<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/access_control.php';

if (!isset($_SESSION['zid'])) {
    header('Location: ' . APP_URL . 'index.php');
    exit();
}

if (!access_control_is_allowed($_SESSION['role'] ?? '', '/audit_log.php')) {
    access_control_forbidden();
}

$gg = $_SESSION['user'];

// Optional filters: ?user=<username>&from=YYYY-MM-DD&to=YYYY-MM-DD
$filterUser = trim((string)($_GET['user'] ?? ''));
$filterFrom = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from'] ?? '') ? $_GET['from'] : '';
$filterTo   = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to'] ?? '') ? $_GET['to'] : '';

$where = [];
$types = '';
$params = [];

if ($filterUser !== '') {
    // Failed logins are logged under "guest" with the attempted username in
    // the details, so include those for this user as well.
    $where[] = "(`username` = ? OR (`action` = 'login_failed' AND `details` = ?))";
    $types .= 'ss';
    $params[] = $filterUser;
    $params[] = 'Invalid login attempt for username: ' . $filterUser;
}
if ($filterFrom !== '') {
    $where[] = "`created_at` >= ?";
    $types .= 's';
    $params[] = $filterFrom . ' 00:00:00';
}
if ($filterTo !== '') {
    $where[] = "`created_at` <= ?";
    $types .= 's';
    $params[] = $filterTo . ' 23:59:59';
}

$isFiltered = !empty($where);
$limit = $isFiltered ? 2000 : 200;

$sql = "SELECT `username`, `action`, `details`, `route`, `ip_address`, `created_at` FROM `audit_trail`"
     . ($isFiltered ? ' WHERE ' . implode(' AND ', $where) : '')
     . " ORDER BY `created_at` DESC, `id` DESC LIMIT " . $limit;

$auditStmt = $conn->prepare($sql);
if ($isFiltered) {
    $auditStmt->bind_param($types, ...$params);
}
$auditStmt->execute();
$auditResult = $auditStmt->get_result();
$rowCount = $auditResult ? $auditResult->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Audit Trail</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Audit Trail</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dash.php">Dashboard</a></li>
<?php if ($filterUser !== ''): ?>
        <li class="breadcrumb-item"><a href="users/local2.php">Users</a></li>
        <li class="breadcrumb-item"><a href="audit_log.php">Audit Trail</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($filterUser); ?></li>
<?php else: ?>
        <li class="breadcrumb-item active">Audit Trail</li>
<?php endif; ?>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">
<?php if ($filterUser !== ''): ?>
          Activity for <strong><?php echo htmlspecialchars($filterUser); ?></strong>
<?php else: ?>
          Recent Activity
<?php endif; ?>
          <span class="text-muted small">&mdash; <?php echo (int)$rowCount; ?> entr<?php echo $rowCount === 1 ? 'y' : 'ies'; ?><?php echo $rowCount >= $limit ? ' (showing latest ' . $limit . ')' : ''; ?></span>
        </h5>

        <form method="get" action="audit_log.php" class="row g-2 align-items-end mb-3">
<?php if ($filterUser !== ''): ?>
          <div class="col-sm-4 col-md-3">
            <label for="f-user" class="form-label small mb-1">Username</label>
            <input type="text" id="f-user" name="user" class="form-control form-control-sm bg-light" value="<?php echo htmlspecialchars($filterUser); ?>" readonly tabindex="-1" aria-readonly="true">
          </div>
<?php endif; ?>
          <div class="col-sm-4 col-md-3">
            <label for="f-from" class="form-label small mb-1">From</label>
            <input type="date" id="f-from" name="from" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filterFrom); ?>">
          </div>
          <div class="col-sm-4 col-md-3">
            <label for="f-to" class="form-label small mb-1">To</label>
            <input type="date" id="f-to" name="to" class="form-control form-control-sm" value="<?php echo htmlspecialchars($filterTo); ?>">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
<?php if ($filterFrom !== '' || $filterTo !== ''): ?>
            <a href="audit_log.php<?php echo $filterUser !== '' ? '?user=' . urlencode($filterUser) : ''; ?>" class="btn btn-sm btn-outline-secondary">Clear dates</a>
<?php endif; ?>
          </div>
        </form>
        <div class="table-responsive">
          <table class="table table-striped datatable nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th>Time</th>
                <th>Username</th>
                <th>Action</th>
                <th>Details</th>
                <th>Route</th>
                <th>IP Address</th>
              </tr>
            </thead>
            <tbody>
<?php if ($auditResult && $auditResult->num_rows > 0): ?>
<?php while ($row = $auditResult->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['action']); ?></td>
                <td><?php echo htmlspecialchars($row['details'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['route'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address'] ?? ''); ?></td>
              </tr>
<?php endwhile; ?>
<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
</body>
</html>
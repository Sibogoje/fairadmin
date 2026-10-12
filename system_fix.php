<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/access_control.php';
require_once 'scripts/audit.php';

if (!isset($_SESSION['zid'])) {
    header('Location: ' . APP_URL . 'index.php');
    exit();
}

// System Fix is restricted to administrators via access_control.php, but we
// double-check here so the page is safe even if the access rules change.
$role = access_control_normalize_role($_SESSION['role'] ?? '');
if (!access_control_role_in($role, ['admin'])) {
    access_control_forbidden();
}

$gg = $gg ?? ($_SESSION['user'] ?? 'User');

$checks = [];
$errors = [];

/**
 * Run a diagnostic query and capture the result in a consistent shape.
 *
 * @return array{status:string, value:mixed, message:string}
 */
function system_fix_check(mysqli $conn, string $sql): array
{
    try {
        $result = $conn->query($sql);
        if ($result === false) {
            return ['status' => 'error', 'value' => null, 'message' => 'Query failed.'];
        }

        $row = $result->fetch_row();
        return ['status' => 'ok', 'value' => $row[0] ?? null, 'message' => ''];
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix check error: ' . $e->getMessage());
        return ['status' => 'error', 'value' => null, 'message' => $e->getMessage()];
    }
}

// --- Basic environment -------------------------------------------------------
$dbName = null;
if ($envResult = $conn->query('SELECT DATABASE()')) {
    $envRow = $envResult->fetch_row();
    $dbName = $envRow[0] ?? null;
}

// --- Table inventory ---------------------------------------------------------
$tables = [];
try {
    $tableResult = $conn->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
    while ($tableResult && $row = $tableResult->fetch_row()) {
        $tables[] = $row[0];
    }
    sort($tables);
} catch (mysqli_sql_exception $e) {
    error_log('System Fix table list error: ' . $e->getMessage());
    $errors[] = 'Unable to list database tables: ' . $e->getMessage();
}

// --- Key integrity checks ----------------------------------------------------
$checks['members_total'] = system_fix_check($conn, 'SELECT COUNT(*) FROM tblmembers');
$checks['members_terminated'] = system_fix_check($conn, 'SELECT COUNT(*) FROM tblmembers WHERE Terminated = 1');
$checks['members_without_balance'] = system_fix_check(
    $conn,
    'SELECT COUNT(*) FROM tblmembers m
     LEFT JOIN balances b ON b.memberID = m.MemberID
     WHERE b.memberID IS NULL'
);
$checks['balances_total'] = system_fix_check($conn, 'SELECT COUNT(*) FROM balances');
$checks['orphan_balances'] = system_fix_check(
    $conn,
    'SELECT COUNT(*) FROM balances b
     LEFT JOIN tblmembers m ON m.MemberID = b.memberID
     WHERE m.MemberID IS NULL'
);
$checks['accounts_total'] = system_fix_check($conn, 'SELECT COUNT(*) FROM tblmemberaccounts');
$checks['orphan_accounts'] = system_fix_check(
    $conn,
    'SELECT COUNT(*) FROM tblmemberaccounts a
     LEFT JOIN tblmembers m ON m.MemberID = a.MemberID
     WHERE m.MemberID IS NULL'
);
$checks['negative_balances'] = system_fix_check(
    $conn,
    'SELECT COUNT(*) FROM balances WHERE NewBalance < 0'
);
$checks['duplicate_member_numbers'] = system_fix_check(
    $conn,
    'SELECT COUNT(*) FROM (
        SELECT MemberNo
        FROM tblmembers
        GROUP BY MemberNo
        HAVING COUNT(*) > 1
     ) dupes'
);

// Human-readable labels / hints for each check.
$checkMeta = [
    'members_total' => ['label' => 'Total members', 'help' => 'Rows in tblmembers.'],
    'members_terminated' => ['label' => 'Terminated members', 'help' => 'Members flagged Terminated = 1.'],
    'members_without_balance' => ['label' => 'Members without a balance row', 'help' => 'Should normally be 0 — members missing from the balances table.'],
    'balances_total' => ['label' => 'Balance rows', 'help' => 'Rows in balances.'],
    'orphan_balances' => ['label' => 'Orphan balance rows', 'help' => 'Balance rows whose member no longer exists. Should be 0.'],
    'accounts_total' => ['label' => 'Transaction rows', 'help' => 'Rows in tblmemberaccounts.'],
    'orphan_accounts' => ['label' => 'Orphan transaction rows', 'help' => 'Transactions whose member no longer exists. Should be 0.'],
    'negative_balances' => ['label' => 'Negative balances', 'help' => 'Balance rows below zero — review before correcting.'],
    'duplicate_member_numbers' => ['label' => 'Duplicate member numbers', 'help' => 'MemberNo values used by more than one member. Should be 0.'],
];

audit_trail_log($conn, 'system_fix_view', 'Opened System Fix page');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>System Fix</title>
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
    <h1>System Fix</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= app_url('dash.php') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">System Fix</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="alert alert-warning d-flex align-items-start" role="alert">
      <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
      <div>
        <strong>Administrator area.</strong> This page is for diagnosing and repairing database issues.
        Any corrective actions will be added here as we work through them — nothing is modified automatically yet.
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Environment</h5>
            <table class="table table-sm mb-0">
              <tbody>
                <tr>
                  <th style="width: 240px;">Connected database</th>
                  <td><?= htmlspecialchars($dbName ?? '(unknown)') ?></td>
                </tr>
                <tr>
                  <th>Server</th>
                  <td><?= htmlspecialchars($conn->host_info ?? '') ?></td>
                </tr>
                <tr>
                  <th>Signed in as</th>
                  <td><?= htmlspecialchars((string) $gg) ?> (<?= htmlspecialchars($role) ?>)</td>
                </tr>
                <tr>
                  <th>Checked at</th>
                  <td><?= htmlspecialchars(date('Y-m-d H:i:s')) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Integrity Checks</h5>
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>Check</th>
                    <th>Result</th>
                    <th>Notes</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($checks as $key => $check): ?>
<?php
    $meta = $checkMeta[$key] ?? ['label' => $key, 'help' => ''];
    $isError = $check['status'] === 'error';
    $value = $check['value'];
    $flagZero = in_array($key, ['members_without_balance', 'orphan_balances', 'orphan_accounts', 'negative_balances', 'duplicate_member_numbers'], true);
    $isWarning = !$isError && $flagZero && (int) $value > 0;
?>
                  <tr>
                    <td>
                      <strong><?= htmlspecialchars($meta['label']) ?></strong>
                    </td>
                    <td>
<?php if ($isError): ?>
                      <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Error</span>
<?php elseif ($isWarning): ?>
                      <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i><?= htmlspecialchars((string) $value) ?></span>
<?php else: ?>
                      <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i><?= htmlspecialchars((string) $value) ?></span>
<?php endif; ?>
                    </td>
                    <td class="text-muted small">
                      <?= htmlspecialchars($isError ? $check['message'] : $meta['help']) ?>
                    </td>
                  </tr>
<?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Tables (<?= count($tables) ?>)</h5>
<?php if (empty($tables)): ?>
            <p class="text-muted mb-0">No tables found or the list could not be read.</p>
<?php else: ?>
            <div class="d-flex flex-wrap gap-1">
<?php foreach ($tables as $table): ?>
              <span class="badge bg-secondary"><?= htmlspecialchars($table) ?></span>
<?php endforeach; ?>
            </div>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>

<?php if (!empty($errors)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-danger" role="alert">
          <strong>Diagnostics reported problems:</strong>
          <ul class="mb-0">
<?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
<?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
<?php endif; ?>
  </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
</body>

</html>

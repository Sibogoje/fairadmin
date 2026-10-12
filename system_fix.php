<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/access_control.php';
require_once 'scripts/audit.php';
require_once __DIR__ . '/scripts/system_fix_transactions.php';

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
$sandboxMessages = [];
$sandboxErrors = [];
$sandboxTableReady = false;

// --- Transactions sandbox ----------------------------------------------------
// Everything below writes ONLY to `transactionsfix`.

try {
    $sandboxTableReady = system_fix_tx_ensure_table($conn);
} catch (mysqli_sql_exception $e) {
    error_log('System Fix sandbox table error: ' . $e->getMessage());
    $sandboxErrors[] = 'Unable to prepare the transactionsfix table: ' . $e->getMessage();
}

if (!isset($_SESSION['system_fix_run_id'])) {
    $_SESSION['system_fix_run_id'] = '';
}
if (!isset($_SESSION['system_fix_period'])) {
    $_SESSION['system_fix_period'] = date('Y-m-t');
}

$action = (string) ($_POST['action'] ?? '');
if ($action !== '' && $sandboxTableReady) {
    $period = system_fix_tx_normalise_date((string) ($_POST['period'] ?? '')) ?: $_SESSION['system_fix_period'];
    $_SESSION['system_fix_period'] = $period;

    try {
        switch ($action) {
            case 'new_run':
                // Start a fresh, isolated run so results never mix.
                $_SESSION['system_fix_run_id'] = 'SF-' . date('Ymd-His');
                $sandboxMessages[] = 'Started a new sandbox run: ' . $_SESSION['system_fix_run_id'];
                break;

            case 'clear_run':
                $runId = (string) ($_POST['run_id'] ?? $_SESSION['system_fix_run_id']);
                if ($runId !== '') {
                    $deleted = system_fix_tx_clear_run($conn, $runId);
                    $sandboxMessages[] = "Cleared {$deleted} sandbox row(s) from run {$runId}.";
                    if ($runId === $_SESSION['system_fix_run_id']) {
                        $_SESSION['system_fix_run_id'] = '';
                    }
                    audit_trail_log($conn, 'system_fix_clear_run', 'Cleared sandbox run ' . $runId);
                }
                break;

            case 'stage_adhocs':
                $runId = system_fix_tx_current_run();
                $source = '';

                if (!empty($_FILES['adhoc_csv']['tmp_name']) && is_uploaded_file($_FILES['adhoc_csv']['tmp_name'])) {
                    $source = $_FILES['adhoc_csv']['tmp_name'];
                    $sourceLabel = $_FILES['adhoc_csv']['name'] ?? 'uploaded file';
                } elseif (!empty($_POST['existing_csv'])) {
                    $candidate = __DIR__ . '/' . basename((string) $_POST['existing_csv']);
                    if (is_file($candidate)) {
                        $source = $candidate;
                        $sourceLabel = basename($candidate);
                    } else {
                        $sandboxErrors[] = 'The selected CSV file could not be found.';
                    }
                } else {
                    $sandboxErrors[] = 'Choose a CSV file to upload, or pick one of the existing files.';
                }

                if ($source !== '') {
                    $parsed = system_fix_tx_parse_csv($source);
                    foreach ($parsed['errors'] as $parseError) {
                        $sandboxErrors[] = $parseError;
                    }

                    $result = system_fix_tx_stage_adhocs($conn, $runId, $parsed['rows'], $period);
                    foreach ($result['skipped'] as $skipped) {
                        $sandboxErrors[] = $skipped;
                    }

                    $sandboxMessages[] = sprintf(
                        'Adhocs staged from %s: %d payment(s), total E %s, fees E %s.',
                        htmlspecialchars($sourceLabel),
                        $result['posted'],
                        number_format($result['total_amount'], 2),
                        number_format($result['total_fee'], 2)
                    );
                    audit_trail_log($conn, 'system_fix_stage_adhocs', "Staged {$result['posted']} adhocs for {$period}");
                }
                break;

            case 'stage_regular':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_regular($conn, $runId, $period);
                $sandboxMessages[] = sprintf(
                    'Regular payments staged: %d member(s), total E %s, fees E %s.',
                    $result['posted'],
                    number_format($result['total_amount'], 2),
                    number_format($result['total_fee'], 2)
                );
                audit_trail_log($conn, 'system_fix_stage_regular', "Staged {$result['posted']} regular payments for {$period}");
                break;

            case 'stage_admin_fees':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_admin_fees($conn, $runId, $period);
                $sandboxMessages[] = sprintf(
                    'Admin fees staged: %d member(s), total E %s.',
                    $result['posted'],
                    number_format($result['total_amount'], 2)
                );
                audit_trail_log($conn, 'system_fix_stage_admin_fees', "Staged {$result['posted']} admin fees for {$period}");
                break;

            case 'stage_fixed_fees':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_fixed_fees($conn, $runId, $period);
                $sandboxMessages[] = sprintf(
                    'Fixed monthly fees staged: %d member(s), total E %s.',
                    $result['posted'],
                    number_format($result['total_amount'], 2)
                );
                audit_trail_log($conn, 'system_fix_stage_fixed_fees', "Staged {$result['posted']} fixed fees for {$period}");
                break;

            case 'stage_interest':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_interest($conn, $runId, $period);
                $sandboxMessages[] = sprintf(
                    'Interest staged: %d member(s), allocated E %s from a pool of E %s (%d source row(s) in tblmemberaccounts).',
                    $result['posted'],
                    number_format($result['total_amount'], 2),
                    number_format($result['pool'], 2),
                    $result['source_rows']
                );
                audit_trail_log($conn, 'system_fix_stage_interest', "Staged interest for {$period}, pool {$result['pool']}");
                break;
        }
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix sandbox action error (' . $action . '): ' . $e->getMessage());
        $sandboxErrors[] = 'Sandbox action failed: ' . $e->getMessage();
    }
}

$sandboxRunId = (string) $_SESSION['system_fix_run_id'];
$sandboxPeriod = (string) $_SESSION['system_fix_period'];
$sandboxSummary = $sandboxTableReady ? system_fix_tx_summary($conn) : ['runs' => 0, 'rows' => 0, 'last_run' => null];
$sandboxRunRows = [];
$sandboxStageTotals = [];

if ($sandboxTableReady && $sandboxRunId !== '') {
    try {
        $stmt = $conn->prepare(
            "SELECT `AccountsID`, `TransactionDate`, `TransactionTypeID`, `MemberNo`, `Details`,
                    `Credit`, `StartingBalance`, `Amount`, `NewBalance`, `Stage`
             FROM `transactionsfix`
             WHERE `RunID` = ?
             ORDER BY `AccountsID` ASC
             LIMIT 300"
        );
        $stmt->bind_param('s', $sandboxRunId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $sandboxRunRows[] = $row;
        }
        $stmt->close();

        $stmt = $conn->prepare(
            "SELECT `Stage`, `TransactionTypeID`, COUNT(*) AS rows_count, COALESCE(SUM(`Amount`), 0) AS total
             FROM `transactionsfix`
             WHERE `RunID` = ?
             GROUP BY `Stage`, `TransactionTypeID`
             ORDER BY MIN(`AccountsID`) ASC"
        );
        $stmt->bind_param('s', $sandboxRunId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $sandboxStageTotals[] = $row;
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix sandbox preview error: ' . $e->getMessage());
        $sandboxErrors[] = 'Unable to read sandbox rows: ' . $e->getMessage();
    }
}

$existingCsvFiles = [];
foreach (glob(__DIR__ . '/*.csv') ?: [] as $csvPath) {
    $existingCsvFiles[] = basename($csvPath);
}
sort($existingCsvFiles);

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

    <div class="alert alert-info d-flex align-items-start" role="alert">
      <i class="bi bi-shield-check me-2 fs-5"></i>
      <div>
        <strong>Sandbox:</strong> the transaction reconstruction below writes <em>only</em> to the
        <code>transactionsfix</code> table. Production tables (<code>tblmemberaccounts</code>,
        <code>balances</code>, <code>member_fees</code>) are read-only here.
      </div>
    </div>

    <?php foreach ($sandboxMessages as $message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $message ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <?php foreach ($sandboxErrors as $message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <?php if (!$sandboxTableReady): ?>
    <div class="alert alert-danger" role="alert">
      The <code>transactionsfix</code> sandbox table could not be prepared, so the rebuild tools are disabled.
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Month-End Sandbox</h5>

            <form method="post" enctype="multipart/form-data" class="row g-3 align-items-end mb-3">
              <div class="col-md-3">
                <label class="form-label" for="period">Period end date</label>
                <input type="date" class="form-control" id="period" name="period" value="<?= htmlspecialchars($sandboxPeriod) ?>" required>
                <div class="form-text">Drives the day-count used by admin fees.</div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="adhoc_csv">Adhocs CSV (upload)</label>
                <input type="file" class="form-control" id="adhoc_csv" name="adhoc_csv" accept=".csv,text/csv">
              </div>

              <div class="col-md-3">
                <label class="form-label" for="existing_csv">…or use a file on the server</label>
                <select class="form-select" id="existing_csv" name="existing_csv">
                  <option value="">— none —</option>
                  <?php foreach ($existingCsvFiles as $csvFile): ?>
                  <option value="<?= htmlspecialchars($csvFile) ?>"><?= htmlspecialchars($csvFile) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-2 d-grid">
                <button type="submit" name="action" value="stage_adhocs" class="btn btn-warning" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-upload me-1"></i>Stage Adhocs
                </button>
              </div>
            </form>

            <hr>

            <div class="row g-2 mb-3">
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="stage_regular" class="btn btn-outline-primary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-arrow-repeat me-1"></i>Run Regular Payments
                </button>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="stage_admin_fees" class="btn btn-outline-primary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-percent me-1"></i>Run Admin Fees
                </button>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="stage_fixed_fees" class="btn btn-outline-primary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-calendar-check me-1"></i>Run Fixed Monthly Fees
                </button>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="stage_interest" class="btn btn-outline-primary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-cash-coin me-1"></i>Run Interest
                </button>
              </div>
            </div>

            <form method="post" id="stageForm" class="d-flex flex-wrap gap-2 align-items-center">
              <input type="hidden" name="period" value="<?= htmlspecialchars($sandboxPeriod) ?>">
              <span class="text-muted small me-2">
                Current run: <code><?= htmlspecialchars($sandboxRunId !== '' ? $sandboxRunId : '(none yet)') ?></code>
                · Period: <code><?= htmlspecialchars($sandboxPeriod) ?></code>
              </span>
              <button type="submit" name="action" value="new_run" class="btn btn-sm btn-success" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                <i class="bi bi-plus-circle me-1"></i>New Run
              </button>
              <button type="submit" name="action" value="clear_run" class="btn btn-sm btn-outline-danger" <?= ($sandboxTableReady && $sandboxRunId !== '') ? '' : 'disabled' ?>>
                <i class="bi bi-trash me-1"></i>Clear Current Run
              </button>
            </form>

            <div class="table-responsive mt-3">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead>
                  <tr>
                    <th>Run</th>
                    <th>Rows</th>
                    <th>Period</th>
                    <th>Started</th>
                  </tr>
                </thead>
                <tbody>
<?php if (!empty($sandboxSummary['last_run'])): ?>
<?php $lastRun = $sandboxSummary['last_run']; ?>
                  <tr>
                    <td><code><?= htmlspecialchars((string) $lastRun['RunID']) ?></code></td>
                    <td><?= (int) $lastRun['rows'] ?></td>
                    <td>
                      <?= htmlspecialchars((string) ($lastRun['period_start'] ?? '')) ?>
                      &rarr;
                      <?= htmlspecialchars((string) ($lastRun['period_end'] ?? '')) ?>
                    </td>
                    <td><?= htmlspecialchars((string) $lastRun['started_at']) ?></td>
                  </tr>
<?php else: ?>
                  <tr><td colspan="4" class="text-muted">No sandbox runs yet.</td></tr>
<?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php if ($sandboxRunId !== '' && !empty($sandboxStageTotals)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Stages in run <code><?= htmlspecialchars($sandboxRunId) ?></code></h5>
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>Stage</th>
                    <th>TransactionTypeID</th>
                    <th>Rows</th>
                    <th>Total Amount</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxStageTotals as $stage): ?>
                  <tr>
                    <td><?= htmlspecialchars((string) $stage['Stage']) ?></td>
                    <td><?= (int) $stage['TransactionTypeID'] ?></td>
                    <td><?= (int) $stage['rows_count'] ?></td>
                    <td>E <?= htmlspecialchars(number_format((float) $stage['total'], 2)) ?></td>
                  </tr>
<?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php endif; ?>

<?php if ($sandboxRunId !== '' && !empty($sandboxRunRows)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Sandbox Rows <small class="text-muted">(first 300)</small></h5>
            <div class="table-responsive">
              <table class="table table-striped datatable nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Member</th>
                    <th>Details</th>
                    <th>Cr</th>
                    <th>Starting</th>
                    <th>Amount</th>
                    <th>New Balance</th>
                    <th>Stage</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxRunRows as $row): ?>
                  <tr>
                    <td><?= (int) $row['AccountsID'] ?></td>
                    <td><?= htmlspecialchars((string) $row['TransactionDate']) ?></td>
                    <td><?= (int) $row['TransactionTypeID'] ?></td>
                    <td><?= htmlspecialchars((string) $row['MemberNo']) ?></td>
                    <td><?= htmlspecialchars((string) $row['Details']) ?></td>
                    <td><?= (int) $row['Credit'] ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['StartingBalance'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['Amount'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['NewBalance'], 2)) ?></td>
                    <td><?= htmlspecialchars((string) $row['Stage']) ?></td>
                  </tr>
<?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php endif; ?>

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

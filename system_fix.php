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
$sandboxPreview = null;
$sandboxPreviewLabel = '';

// --- Transactions sandbox ----------------------------------------------------
// Everything below writes ONLY to `transactionsfix`.

try {
    $sandboxTableReady = system_fix_tx_ensure_table($conn);
} catch (mysqli_sql_exception $e) {
    error_log('System Fix sandbox table error: ' . $e->getMessage());
    $sandboxErrors[] = 'Unable to prepare the transactionsfix table: ' . $e->getMessage();
}

if (!isset($_SESSION['system_fix_period'])) {
    $_SESSION['system_fix_period'] = date('Y-m-t');
}

$action = (string) ($_POST['action'] ?? '');

/**
 * Folders searched for spreadsheets. Values are relative to the app root so a
 * selected file can be resolved back to a real path.
 */
$sandboxSpreadsheetFolders = ['', 'Transactions/filestoupload'];

/**
 * Find a spreadsheet by name across the known folders.
 */
$findSpreadsheet = static function (string $name) use ($sandboxSpreadsheetFolders): array {
    $name = basename($name); // blocks path traversal
    foreach ($sandboxSpreadsheetFolders as $folder) {
        $path = $folder === '' ? __DIR__ . '/' . $name : __DIR__ . '/' . $folder . '/' . $name;
        if (is_file($path)) {
            return [$path, $name, ''];
        }
    }

    return ['', '', 'The selected file could not be found on the server.'];
};

/**
 * Auto-detect a spreadsheet by a keyword in its filename (e.g. "regular" or
 * "adhoc"). Only used as a fallback when nothing was explicitly chosen, and only
 * when exactly one file matches — never guesses between candidates.
 *
 * @return array{0:string, 1:string} [path, label] or ['', '']
 */
$findSpreadsheetByKeyword = static function (string $keyword) use ($sandboxSpreadsheetFolders, $findSpreadsheet): array {
    $matches = [];
    foreach ($sandboxSpreadsheetFolders as $folder) {
        $pattern = $folder === '' ? __DIR__ . '/*' : __DIR__ . '/' . $folder . '/*';
        foreach (glob($pattern) ?: [] as $path) {
            if (is_file($path) && preg_match('/\.(csv|xlsx)$/i', $path) && stripos(basename($path), $keyword) !== false) {
                $matches[] = basename($path);
            }
        }
    }

    $matches = array_values(array_unique($matches));
    if (count($matches) !== 1) {
        return ['', ''];
    }

    [$path, $label] = $findSpreadsheet($matches[0]);
    return [$path, $label];
};

/**
 * Pick the adhocs source: an uploaded file wins, else a file already present on
 * the server (matched across the known folders). Returns [path, label, error].
 */
$resolveAdhocSource = static function () use ($findSpreadsheet, $findSpreadsheetByKeyword): array {
    if (!empty($_FILES['adhoc_csv']['tmp_name']) && is_uploaded_file($_FILES['adhoc_csv']['tmp_name'])) {
        return [$_FILES['adhoc_csv']['tmp_name'], (string) ($_FILES['adhoc_csv']['name'] ?? 'uploaded file'), ''];
    }

    if (!empty($_POST['existing_csv'])) {
        return $findSpreadsheet((string) $_POST['existing_csv']);
    }

    // Fallback: the only adhocs-looking file on disk (e.g. "…ADHOC - done.xlsx").
    [$path, $label] = $findSpreadsheetByKeyword('adhoc');
    if ($path !== '') {
        return [$path, $label, ''];
    }

    return ['', '', 'Choose a CSV file to upload, or pick one of the existing files.'];
};

/**
 * Pick the regulars source the same way.
 */
$resolveRegularSource = static function () use ($findSpreadsheet, $findSpreadsheetByKeyword): array {
    if (!empty($_FILES['regular_xlsx']['tmp_name']) && is_uploaded_file($_FILES['regular_xlsx']['tmp_name'])) {
        return [$_FILES['regular_xlsx']['tmp_name'], (string) ($_FILES['regular_xlsx']['name'] ?? 'uploaded regulars'), ''];
    }

    if (!empty($_POST['existing_regular'])) {
        return $findSpreadsheet((string) $_POST['existing_regular']);
    }

    // Fallback: the only regulars-looking file on disk (e.g. "…REGULAR - done.xlsx").
    [$path, $label] = $findSpreadsheetByKeyword('regular');
    if ($path !== '') {
        return [$path, $label, ''];
    }

    return ['', '', 'Choose a regulars file to upload, or pick one of the existing files.'];
};

if ($action !== '' && $sandboxTableReady) {
    $period = system_fix_tx_normalise_date((string) ($_POST['period'] ?? '')) ?: $_SESSION['system_fix_period'];
    $_SESSION['system_fix_period'] = $period;

    try {
        switch ($action) {
            case 'clear_run':
                $runId = (string) ($_POST['run_id'] ?? system_fix_tx_current_run());
                $deleted = system_fix_tx_clear_run($conn, $runId);
                $sandboxMessages[] = "Cleared {$deleted} sandbox row(s) for period {$runId}.";
                audit_trail_log($conn, 'system_fix_clear_run', 'Cleared sandbox period ' . $runId);
                break;

            case 'preview_adhocs':
                // Show the MemberNo -> MemberID mapping without writing anything.
                [$source, $sourceLabel, $sourceError] = $resolveAdhocSource();
                if ($sourceError !== '') {
                    $sandboxErrors[] = $sourceError;
                    break;
                }

                $parsed = system_fix_tx_parse_csv($source);
                foreach ($parsed['errors'] as $parseError) {
                    $sandboxErrors[] = $parseError;
                }

                $sandboxPreview = system_fix_tx_preview_adhocs($conn, $parsed['rows']);
                $sandboxPreviewLabel = $sourceLabel;
                $sandboxMessages[] = sprintf(
                    'Mapping check for %s: %d row(s) matched to a memberID, %d unmatched, total E %s.',
                    htmlspecialchars($sourceLabel),
                    $sandboxPreview['matched'],
                    $sandboxPreview['unmatched'],
                    number_format($sandboxPreview['total_amount'], 2)
                );
                break;

            case 'stage_adhocs':
                $runId = system_fix_tx_current_run();
                [$source, $sourceLabel, $sourceError] = $resolveAdhocSource();
                if ($sourceError !== '') {
                    $sandboxErrors[] = $sourceError;
                    break;
                }

                $parsed = system_fix_tx_parse_csv($source);
                foreach ($parsed['errors'] as $parseError) {
                    $sandboxErrors[] = $parseError;
                }

                $result = system_fix_tx_stage_adhocs($conn, $runId, $parsed['rows'], $period);
                foreach ($result['skipped'] as $skipped) {
                    $sandboxErrors[] = $skipped;
                }

                // Members who came through the CSV now exist in the fund for this
                // period — add them to the running roster.
                $rosterAdded = system_fix_tx_roster_add($conn, system_fix_tx_period_key($period), system_fix_tx_scope_ids($conn, $runId), 'csv');

                $sandboxMessages[] = sprintf(
                    'Adhocs staged from %s: %d payment(s), total E %s, fees E %s. Roster updated (+%d member(s)).',
                    htmlspecialchars($sourceLabel),
                    $result['posted'],
                    number_format($result['total_amount'], 2),
                    number_format($result['total_fee'], 2),
                    $rosterAdded
                );
                audit_trail_log($conn, 'system_fix_stage_adhocs', "Staged {$result['posted']} adhocs for {$period}");
                break;

            case 'update_roster':
                $added = system_fix_tx_roster_add($conn, system_fix_tx_period_key($period), system_fix_tx_scope_ids($conn, system_fix_tx_current_run()), 'manual');
                $sandboxMessages[] = sprintf(
                    'Roster updated for %s: +%d member(s) added.',
                    system_fix_tx_period_key($period),
                    $added
                );
                audit_trail_log($conn, 'system_fix_update_roster', "Roster +{$added} for " . system_fix_tx_period_key($period));
                break;

            case 'capture_regular':
                $capture = system_fix_tx_capture_regular($conn, $period);
                $sandboxMessages[] = sprintf(
                    'Captured regular payments for %s: %d member(s), total E %s (from tblmemberaccounts).',
                    system_fix_tx_period_key($period),
                    $capture['rows'],
                    number_format($capture['total'], 2)
                );
                audit_trail_log($conn, 'system_fix_capture_regular', "Captured {$capture['rows']} regular payments for " . system_fix_tx_period_key($period));
                break;

            case 'seed_history':
                // Copy production history before 2014-05 into the sandbox baseline.
                $seedBefore = system_fix_tx_normalise_date((string) ($_POST['seed_before'] ?? '')) ?: '2014-05-01';
                $seed = system_fix_tx_seed_history($conn, $seedBefore);
                $sandboxMessages[] = sprintf(
                    'History seeded: %d row(s) copied from tblmemberaccounts before %s (%d previous seed row(s) replaced). %d member(s) added to the roster for %s.',
                    $seed['inserted'],
                    $seedBefore,
                    $seed['deleted'],
                    $seed['members'],
                    $seed['roster_period']
                );
                audit_trail_log($conn, 'system_fix_seed_history', "Seeded {$seed['inserted']} rows before {$seedBefore}");
                break;

            case 'run_all_months':
                // Walk every month between the spreadsheet dates, running the
                // full month end (adhocs -> regulars -> admin -> fixed -> interest)
                // so balances carry forward automatically.
                [$adhocSource, $adhocLabel, $adhocError] = $resolveAdhocSource();
                if ($adhocSource === '') {
                    $adhocGot = (string) ($_POST['existing_csv'] ?? '');
                    $adhocUpload = $_FILES['adhoc_csv']['name'] ?? '';
                    $sandboxErrors[] = sprintf(
                        'Adhocs spreadsheet not found (dropdown="%s", upload="%s"). Pick “JANUARY 2014 - JUNE 2016 ADHOC - done.xlsx” and try again.',
                        $adhocGot,
                        $adhocUpload
                    );
                    break;
                }
                $sandboxMessages[] = 'Adhocs loaded from: ' . htmlspecialchars($adhocLabel);

                $regularSource = '';
                $regularLabel = '';
                [$regularSource, $regularLabel, $regularError] = $resolveRegularSource();
                if ($regularSource === '') {
                    $regularGot = (string) ($_POST['existing_regular'] ?? '');
                    $regularUpload = $_FILES['regular_xlsx']['name'] ?? '';
                    $sandboxErrors[] = sprintf(
                        'Regulars spreadsheet not found (dropdown="%s", upload="%s"). Pick “JANUARY 2014 - JUNE 2016 REGULAR - done.xlsx” and click Run All Months again.',
                        $regularGot,
                        $regularUpload
                    );
                    break;
                }
                $sandboxMessages[] = 'Regulars loaded from: ' . htmlspecialchars($regularLabel);

                $adhocParsed = system_fix_tx_parse_csv($adhocSource);
                $regularParsed = system_fix_tx_parse_csv($regularSource);
                foreach (array_merge($adhocParsed['errors'], $regularParsed['errors']) as $parseError) {
                    $sandboxErrors[] = $parseError;
                }

                if (empty($adhocParsed['rows']) && empty($regularParsed['rows'])) {
                    $sandboxErrors[] = 'Neither spreadsheet produced any rows.';
                    break;
                }

                // Determine the month range from the rows themselves.
                $allDates = [];
                foreach ($adhocParsed['rows'] as $r) { if (!empty($r['date'])) { $allDates[] = $r['date']; } }
                foreach ($regularParsed['rows'] as $r) { if (!empty($r['date'])) { $allDates[] = $r['date']; } }
                if (empty($allDates)) {
                    $sandboxErrors[] = 'No usable dates found in the spreadsheets.';
                    break;
                }
                sort($allDates);

                $fromMonth = substr($allDates[0], 0, 7) . '-01';
                $toMonth = substr(end($allDates), 0, 7) . '-01';

                // Optional override so a seeded month is not processed twice.
                $fromOverride = trim((string) ($_POST['run_from_month'] ?? ''));
                if (preg_match('#^\d{4}-\d{2}$#', $fromOverride) && $fromOverride > substr($fromMonth, 0, 7)) {
                    $fromMonth = $fromOverride . '-01';
                }

                $months = system_fix_tx_months_between($fromMonth, $toMonth);
                if (empty($months)) {
                    $sandboxErrors[] = 'No months to process — the start month is after the last month in the files.';
                    break;
                }

                $processed = 0;
                $totalTerminations = 0;
                $totalTermFee = 0.0;
                $totalAdhoc = 0.0;
                $totalRegular = 0.0;
                $totalAdmin = 0.0;
                $totalFixed = 0.0;
                $totalInterest = 0.0;

                foreach ($months as $monthStart) {
                    $monthEnd = date('Y-m-t', strtotime($monthStart));
                    $m = system_fix_tx_run_month($conn, $monthEnd, $adhocParsed['rows'], $regularParsed['rows'], true);
                    $processed++;
                    $totalTerminations += $m['adhoc']['terminations'] ?? 0;
                    $totalTermFee += $m['adhoc']['termination_fee'] ?? 0;
                    $totalAdhoc += $m['adhoc']['total_amount'] ?? 0;
                    $totalRegular += $m['regular']['total_amount'] ?? 0;
                    $totalAdmin += $m['admin']['total_amount'] ?? 0;
                    $totalFixed += $m['fixed']['total_amount'] ?? 0;
                    $totalInterest += $m['interest']['total_amount'] ?? 0;
                }

                $sandboxMessages[] = sprintf(
                    'Ran %d month(s) from %s to %s. Adhocs E %s, regulars E %s, admin E %s, fixed E %s, interest E %s. %d termination(s), termination fees E %s.',
                    $processed,
                    date('F Y', strtotime($fromMonth)),
                    date('F Y', strtotime($toMonth)),
                    number_format($totalAdhoc, 2),
                    number_format($totalRegular, 2),
                    number_format($totalAdmin, 2),
                    number_format($totalFixed, 2),
                    number_format($totalInterest, 2),
                    $totalTerminations,
                    number_format($totalTermFee, 2)
                );
                audit_trail_log($conn, 'system_fix_run_all_months', "Ran {$processed} months {$fromMonth}..{$toMonth}");
                break;

            case 'build_interest_pool':
                $pool = system_fix_tx_populate_interest_pool($conn, null);
                $sandboxMessages[] = sprintf(
                    'Interest pool built: %d month(s) stored in interestfix, total E %s.',
                    $pool['months'],
                    number_format($pool['total'], 2)
                );
                audit_trail_log($conn, 'system_fix_build_interest_pool', "Built interest pool: {$pool['months']} months");
                break;

            case 'stage_openings':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_openings($conn, $runId, $period);
                foreach ($result['skipped'] as $skipped) {
                    $sandboxErrors[] = $skipped;
                }
                $sandboxMessages[] = sprintf(
                    'Opening balances staged: %d member(s), total E %s, transfer-in fees E %s (%d charged, %d waived below 20,000).',
                    $result['posted'],
                    number_format($result['total_opening'], 2),
                    number_format($result['total_fee'], 2),
                    $result['fee_count'],
                    $result['no_fee_count']
                );
                audit_trail_log($conn, 'system_fix_stage_openings', "Staged {$result['posted']} opening balances for {$period}");
                break;

            case 'stage_regular':
                $runId = system_fix_tx_current_run();
                $result = system_fix_tx_stage_regular($conn, $runId, $period);
                foreach (($result['skipped'] ?? []) as $skipped) {
                    $sandboxErrors[] = $skipped;
                }
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
                    'Fixed monthly fees staged: %d member(s) at E 10.00 each, total E %s (%d Coca-Cola member(s) exempt).',
                    $result['posted'],
                    number_format($result['total_amount'], 2),
                    $result['exempt']
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

$sandboxPeriod = (string) $_SESSION['system_fix_period'];
$sandboxRunId = system_fix_tx_period_key($sandboxPeriod);
$sandboxSummary = $sandboxTableReady ? system_fix_tx_summary($conn) : ['runs' => 0, 'rows' => 0, 'last_run' => null];
$sandboxScopeCount = 0;
$sandboxRosterCount = 0;
$sandboxCapturedCount = 0;
$sandboxInterestMonths = 0;
$sandboxInterestPools = [];
$sandboxPeriodKey = system_fix_tx_period_key($sandboxPeriod);
$sandboxRunRows = [];
$sandboxStageTotals = [];
$sandboxTerminationCount = 0;
$sandboxTerminationDiscrepancy = 0.0;
$sandboxTerminationFees = 0.0;
$sandboxTerminationRows = [];

if ($sandboxTableReady) {
    try {
        $sandboxScopeCount = count(system_fix_tx_scope_ids($conn, $sandboxPeriodKey));
        $sandboxRosterCount = count(system_fix_tx_roster_ids($conn, $sandboxPeriodKey));
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix scope count error: ' . $e->getMessage());
    }
}

if ($sandboxTableReady) {
    try {
        $captureTable = system_fix_tx_regular_capture_table();
        $stmt = $conn->prepare("SELECT COUNT(*) AS rows_count FROM `{$captureTable}` WHERE `Period` = ?");
        $stmt->bind_param('s', $sandboxPeriodKey);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $sandboxCapturedCount = (int) ($row['rows_count'] ?? 0);
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix capture count error: ' . $e->getMessage());
    }

    try {
        $interestTable = system_fix_tx_interest_pool_table();
        if ($result = $conn->query("SELECT COUNT(*) AS months_count FROM `{$interestTable}`")) {
            $sandboxInterestMonths = (int) ($result->fetch_assoc()['months_count'] ?? 0);
        }

        // Most recent 12 months for display.
        if ($result = $conn->query("SELECT `Period`, `Amount`, `SourceRows` FROM `{$interestTable}` ORDER BY `Period` DESC LIMIT 12")) {
            while ($row = $result->fetch_assoc()) {
                $sandboxInterestPools[] = $row;
            }
        }
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix interest pool read error: ' . $e->getMessage());
    }

    try {
        $termTable = system_fix_tx_terminations_table();
        if ($result = $conn->query("SELECT COUNT(*) AS c, COALESCE(SUM(`Discrepancy`),0) AS d, COALESCE(SUM(`TerminationFee`),0) AS f FROM `{$termTable}`")) {
            $row = $result->fetch_assoc();
            $sandboxTerminationCount = (int) ($row['c'] ?? 0);
            $sandboxTerminationDiscrepancy = (float) ($row['d'] ?? 0);
            $sandboxTerminationFees = (float) ($row['f'] ?? 0);
        }
        if ($result = $conn->query("SELECT `Period`, `TransactionDate`, `MemberNo`, `AmountRequested`, `AmountAvailable`, `AmountGiven`, `Discrepancy`, `TerminationFee`, `Reason` FROM `{$termTable}` ORDER BY `id` DESC LIMIT 50")) {
            while ($row = $result->fetch_assoc()) {
                $sandboxTerminationRows[] = $row;
            }
        }
    } catch (mysqli_sql_exception $e) {
        error_log('System Fix terminations read error: ' . $e->getMessage());
    }
}

if ($sandboxTableReady) {
    // Rows for the period being rebuilt (the sandbox is period-scoped).
    $bounds = system_fix_tx_period_bounds($sandboxPeriod);
    try {
        $stmt = $conn->prepare(
            "SELECT `accountsID`, `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`,
                    `Credit`, `StartingBalance`, `Amount`, `NewBalance`, `Comments`
             FROM `transactionsfix`
             WHERE `TransactionDate` BETWEEN ? AND ?
             ORDER BY `accountsID` ASC
             LIMIT 300"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $sandboxRunRows[] = $row;
        }
        $stmt->close();

        $stmt = $conn->prepare(
            "SELECT `TransactionTypeID`, COUNT(*) AS rows_count, COALESCE(SUM(`Amount`), 0) AS total
             FROM `transactionsfix`
             WHERE `TransactionDate` BETWEEN ? AND ?
             GROUP BY `TransactionTypeID`
             ORDER BY MIN(`accountsID`) ASC"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
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
foreach ($sandboxSpreadsheetFolders as $folder) {
    $pattern = $folder === '' ? __DIR__ . '/*' : __DIR__ . '/' . $folder . '/*';
    foreach (glob($pattern) ?: [] as $csvPath) {
        if (is_file($csvPath) && preg_match('/\.(csv|xlsx)$/i', $csvPath)) {
            $existingCsvFiles[] = basename($csvPath);
        }
    }
}
$existingCsvFiles = array_values(array_unique($existingCsvFiles));
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

            <form method="post" enctype="multipart/form-data" id="adhocForm" class="row g-3 align-items-end mb-3">
              <div class="col-md-3">
                <label class="form-label" for="period">Period end date</label>
                <input type="date" class="form-control" id="period" name="period" value="<?= htmlspecialchars($sandboxPeriod) ?>" required>
                <div class="form-text">Drives the day-count used by admin fees.</div>
              </div>

              <div class="col-md-4">
                <label class="form-label" for="adhoc_csv">Adhocs file (CSV or Excel)</label>
                <input type="file" class="form-control" id="adhoc_csv" name="adhoc_csv" accept=".csv,.xlsx,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
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

              <div class="col-md-2 d-grid gap-2">
                <button type="submit" name="action" value="preview_adhocs" class="btn btn-outline-secondary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-search me-1"></i>Preview Mapping
                </button>
                <button type="submit" name="action" value="stage_adhocs" class="btn btn-warning" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-upload me-1"></i>Stage Adhocs
                </button>
              </div>
            </form>

            <hr>

            <form method="post" enctype="multipart/form-data" id="runAllForm" class="row g-3 align-items-end mb-3">
              <div class="col-md-4">
                <label class="form-label" for="adhoc_csv_run">Adhocs spreadsheet (upload)</label>
                <input type="file" class="form-control" id="adhoc_csv_run" name="adhoc_csv" accept=".csv,.xlsx">
              </div>
              <div class="col-md-3">
                <label class="form-label" for="existing_csv2">…or adhocs on the server</label>
                <select class="form-select" id="existing_csv2" name="existing_csv">
                  <option value="">— none —</option>
                  <?php foreach ($existingCsvFiles as $csvFile): ?>
                  <option value="<?= htmlspecialchars($csvFile) ?>" <?= stripos($csvFile, 'adhoc') !== false ? 'selected' : '' ?>><?= htmlspecialchars($csvFile) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="regular_xlsx">Regulars spreadsheet (upload)</label>
                <input type="file" class="form-control" id="regular_xlsx" name="regular_xlsx" accept=".csv,.xlsx">
              </div>
              <div class="col-md-3">
                <label class="form-label" for="existing_regular">…or regulars on the server</label>
                <select class="form-select" id="existing_regular" name="existing_regular">
                  <option value="">— none —</option>
                  <?php foreach ($existingCsvFiles as $csvFile): ?>
                  <option value="<?= htmlspecialchars($csvFile) ?>" <?= stripos($csvFile, 'regular') !== false ? 'selected' : '' ?>><?= htmlspecialchars($csvFile) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="run_from_month">Start from month</label>
                <input type="month" class="form-control" id="run_from_month" name="run_from_month" value="">
                <div class="form-text">Leave blank to start at the first month in the files.</div>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" name="action" value="run_all_months" class="btn btn-success" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-play-circle me-1"></i>Run All Months
                </button>
              </div>
              <div class="col-12">
                <div class="form-text">
                  Walks every month between the two spreadsheets, running adhocs → regulars → admin → fixed → interest
                  and carrying balances forward. Terminations are recorded, not applied to members.
                </div>
              </div>
            </form>

            <p class="text-muted small mb-0">
              <i class="bi bi-info-circle me-1"></i>
              The CSV identifies members by <strong>Member No</strong>. Every transaction is keyed on
              <code>memberID</code>, so each Member No is looked up in <code>tblmembers</code> first.
              Use <em>Preview Mapping</em> to confirm the match before staging.
            </p>

            <div class="alert <?= $sandboxScopeCount > 0 ? 'alert-secondary' : 'alert-warning' ?> d-flex align-items-start mt-3 mb-0" role="alert">
              <i class="bi bi-people me-2 fs-5"></i>
              <div>
                <strong>Scope:</strong>
                <?php if ($sandboxScopeCount > 0): ?>
                  this run covers <strong><?= (int) $sandboxScopeCount ?></strong> member(s) imported from the adhocs CSV.
                <?php else: ?>
                  no members in scope yet. <strong>Stage an adhocs CSV first</strong> — the other stages will do
                  nothing until the member list is defined by the import.
                <?php endif; ?>
                <br>
                <strong>Roster (<?= htmlspecialchars($sandboxPeriodKey) ?>):</strong>
                <strong><?= (int) $sandboxRosterCount ?></strong> member(s) exist in the fund for this period.
                Month-end stages (regular, admin, fixed, interest) run for these members.
                <?php if ($sandboxCapturedCount > 0): ?>
                <br><strong>Regular payments captured:</strong> <strong><?= (int) $sandboxCapturedCount ?></strong> member(s)
                for <?= htmlspecialchars($sandboxPeriodKey) ?>.
                <?php else: ?>
                <br><span class="text-danger">Regular payments not captured yet — click <em>Capture Regular Payments</em> before running them.</span>
                <?php endif; ?>
              </div>
            </div>

            <hr>

            <hr>

            <div class="row g-2 mb-3">
              <div class="col-md-3">
                <label class="form-label" for="seed_before">Seed history before</label>
                <input type="date" class="form-control" id="seed_before" name="seed_before" form="stageForm" value="2014-05-01">
              </div>
              <div class="col-md-3 d-grid align-self-end">
                <button type="submit" form="stageForm" name="action" value="seed_history" class="btn btn-outline-dark" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-box-arrow-in-down me-1"></i>Seed History
                </button>
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="update_roster" class="btn btn-outline-secondary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-people me-1"></i>Update Roster
                </button>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="capture_regular" class="btn btn-outline-secondary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-download me-1"></i>Capture Regular Payments
                </button>
              </div>
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="build_interest_pool" class="btn btn-outline-secondary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-cash-stack me-1"></i>Build Interest Pool
                </button>
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-md-3 d-grid">
                <button type="submit" form="stageForm" name="action" value="stage_openings" class="btn btn-outline-dark" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-person-plus me-1"></i>Process Openings (New Members)
                </button>
              </div>
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
                Period: <code><?= htmlspecialchars($sandboxRunId) ?></code>
              </span>
              <button type="submit" name="action" value="clear_run" class="btn btn-sm btn-outline-danger" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                <i class="bi bi-trash me-1"></i>Clear This Period
              </button>
            </form>

            <div class="table-responsive mt-3">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead>
                  <tr>
                    <th>Period</th>
                    <th>Rows</th>
                    <th>Date Range in Sandbox</th>
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
                  </tr>
<?php else: ?>
                  <tr><td colspan="3" class="text-muted">No sandbox rows yet.</td></tr>
<?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php if ($sandboxTableReady && !empty($sandboxInterestPools)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Interest Pool <small class="text-muted">(interestfix — <?= (int) $sandboxInterestMonths ?> month(s) stored)</small>
            </h5>
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead>
                  <tr>
                    <th>Month</th>
                    <th>Pool Amount</th>
                    <th>Source Rows in tblmemberaccounts</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxInterestPools as $poolRow): ?>
                  <tr>
                    <td><code><?= htmlspecialchars((string) $poolRow['Period']) ?></code></td>
                    <td>E <?= htmlspecialchars(number_format((float) $poolRow['Amount'], 2)) ?></td>
                    <td><?= (int) $poolRow['SourceRows'] ?></td>
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

<?php if ($sandboxTableReady && !empty($sandboxTerminationRows)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Terminations
              <small class="text-muted">
                — <?= (int) $sandboxTerminationCount ?> recorded ·
                discrepancy E <?= htmlspecialchars(number_format($sandboxTerminationDiscrepancy, 2)) ?> ·
                fees E <?= htmlspecialchars(number_format($sandboxTerminationFees, 2)) ?>
              </small>
            </h5>
            <p class="text-muted small">
              Decisions are recorded here only — <code>tblmembers</code> is not modified by this fix.
            </p>
            <div class="table-responsive">
              <table class="table table-sm table-striped datatable nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>Period</th>
                    <th>Date</th>
                    <th>Member No</th>
                    <th>Requested</th>
                    <th>Available</th>
                    <th>Given</th>
                    <th>Discrepancy</th>
                    <th>Term Fee</th>
                    <th>Reason</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxTerminationRows as $tRow): ?>
                  <tr>
                    <td><?= htmlspecialchars((string) $tRow['Period']) ?></td>
                    <td><?= htmlspecialchars((string) $tRow['TransactionDate']) ?></td>
                    <td><?= htmlspecialchars((string) $tRow['MemberNo']) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $tRow['AmountRequested'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $tRow['AmountAvailable'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $tRow['AmountGiven'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $tRow['Discrepancy'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $tRow['TerminationFee'], 2)) ?></td>
                    <td><?= htmlspecialchars((string) $tRow['Reason']) ?></td>
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

<?php if ($sandboxPreview !== null): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Member No &rarr; Member ID mapping
              <small class="text-muted">— <?= htmlspecialchars($sandboxPreviewLabel) ?></small>
            </h5>
            <p class="mb-2">
              <span class="badge bg-success"><?= (int) $sandboxPreview['matched'] ?> matched</span>
              <?php if ($sandboxPreview['unmatched'] > 0): ?>
              <span class="badge bg-danger"><?= (int) $sandboxPreview['unmatched'] ?> unmatched</span>
              <?php endif; ?>
              <span class="badge bg-secondary">Total E <?= htmlspecialchars(number_format($sandboxPreview['total_amount'], 2)) ?></span>
            </p>
            <div class="table-responsive">
              <table class="table table-sm table-striped datatable nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>Line</th>
                    <th>Member No (CSV)</th>
                    <th>Member ID (tblmembers)</th>
                    <th>Name</th>
                    <th>Terminated</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxPreview['lines'] as $line): ?>
                  <tr>
                    <td><?= (int) $line['line'] ?></td>
                    <td><?= htmlspecialchars((string) $line['member_no']) ?></td>
                    <td><?= $line['member_id'] === null ? '—' : (int) $line['member_id'] ?></td>
                    <td><?= htmlspecialchars((string) $line['name']) ?></td>
                    <td><?= $line['terminated'] === null ? '—' : ((int) $line['terminated'] === 1 ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars(number_format((float) $line['amount'], 2)) ?></td>
                    <td>
<?php if ($line['resolved']): ?>
                      <span class="badge bg-success">Resolved</span>
<?php else: ?>
                      <span class="badge bg-danger">Not found</span>
<?php endif; ?>
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
<?php endif; ?>

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
                    <td><?= htmlspecialchars(system_fix_tx_transaction_type_name($conn, (int) $stage['TransactionTypeID'])) ?></td>
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

<?php if (!empty($sandboxRunRows)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Sandbox Rows <small class="text-muted">(first 300 for <?= htmlspecialchars($sandboxRunId) ?>)</small></h5>
            <div class="table-responsive">
              <table class="table table-striped datatable nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Member ID</th>
                    <th>Details</th>
                    <th>Cr</th>
                    <th>Starting</th>
                    <th>Amount</th>
                    <th>New Balance</th>
                    <th>Comments</th>
                  </tr>
                </thead>
                <tbody>
<?php foreach ($sandboxRunRows as $row): ?>
                  <tr>
                    <td><?= (int) $row['accountsID'] ?></td>
                    <td><?= htmlspecialchars((string) $row['TransactionDate']) ?></td>
                    <td><?= (int) $row['TransactionTypeID'] ?></td>
                    <td><?= htmlspecialchars((string) $row['memberID']) ?></td>
                    <td><?= htmlspecialchars((string) $row['Details']) ?></td>
                    <td><?= htmlspecialchars((string) $row['Credit']) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['StartingBalance'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['Amount'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format((float) $row['NewBalance'], 2)) ?></td>
                    <td><?= htmlspecialchars((string) $row['Comments']) ?></td>
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

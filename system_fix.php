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
 * Pick the adhocs source: an uploaded file wins, else the server dropdown, else
 * the only "adhoc" file on disk.
 *
 * The dropdown choice is cross-checked against the expected keyword: if the
 * chosen file plainly looks like the OTHER spreadsheet (e.g. a regulars file was
 * picked in the adhocs box), the correctly-named file is used instead so an
 * honest mis-selection cannot silently produce an empty stage.
 *
 * @return array{0:string, 1:string, 2:string} [path, label, error]
 */
$resolveAdhocSource = static function () use ($findSpreadsheet, $findSpreadsheetByKeyword): array {
    if (!empty($_FILES['adhoc_csv']['tmp_name']) && is_uploaded_file($_FILES['adhoc_csv']['tmp_name'])) {
        return [$_FILES['adhoc_csv']['tmp_name'], (string) ($_FILES['adhoc_csv']['name'] ?? 'uploaded file'), ''];
    }

    if (!empty($_POST['existing_csv'])) {
        $chosen = (string) $_POST['existing_csv'];
        // Wrong spreadsheet in this box? Prefer the adhocs-looking file.
        if (stripos($chosen, 'regular') !== false && stripos($chosen, 'adhoc') === false) {
            [$path, $label] = $findSpreadsheetByKeyword('adhoc');
            if ($path !== '') {
                return [$path, $label, ''];
            }
        }

        return $findSpreadsheet($chosen);
    }

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
        $chosen = (string) $_POST['existing_regular'];
        if (stripos($chosen, 'adhoc') !== false && stripos($chosen, 'regular') === false) {
            [$path, $label] = $findSpreadsheetByKeyword('regular');
            if ($path !== '') {
                return [$path, $label, ''];
            }
        }

        return $findSpreadsheet($chosen);
    }

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
            case 'view_statement':
                // Read-only: nothing to do here, the statement is rendered below
                // from the selected member's `transactionsfix` rows.
                break;

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
                $totalSynced = 0;
                $totalOpenings = 0;

                foreach ($months as $monthStart) {
                    $monthEnd = date('Y-m-t', strtotime($monthStart));
                    $m = system_fix_tx_run_month_auto($conn, $monthEnd, $adhocParsed['rows'], $regularParsed['rows']);
                    $processed++;
                    $totalTerminations += $m['adhoc']['terminations'] ?? 0;
                    $totalTermFee += $m['adhoc']['termination_fee'] ?? 0;
                    $totalAdhoc += $m['adhoc']['total_amount'] ?? 0;
                    $totalRegular += $m['regular']['total_amount'] ?? 0;
                    $totalAdmin += $m['admin']['total_amount'] ?? 0;
                    $totalFixed += $m['fixed']['total_amount'] ?? 0;
                    $totalInterest += $m['interest']['total_amount'] ?? 0;
                    $totalSynced += $m['sync']['members'] ?? 0;
                    $totalOpenings += $m['sync']['openings'] ?? 0;
                }

                $sandboxMessages[] = sprintf(
                    'Ran %d month(s) from %s to %s. Adhocs E %s, regulars E %s, interest E %s, fixed E %s, admin E %s. %d termination(s), fees E %s. Members synced from production: %d; new opening balances created: %d.',
                    $processed,
                    date('F Y', strtotime($fromMonth)),
                    date('F Y', strtotime($toMonth)),
                    number_format($totalAdhoc, 2),
                    number_format($totalRegular, 2),
                    number_format($totalInterest, 2),
                    number_format($totalFixed, 2),
                    number_format($totalAdmin, 2),
                    $totalTerminations,
                    number_format($totalTermFee, 2),
                    $totalSynced,
                    $totalOpenings
                );
                audit_trail_log($conn, 'system_fix_run_all_months', "Ran {$processed} months {$fromMonth}..{$toMonth}, synced {$totalSynced} members");
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

            case 'build_schedule':
                $schedule = system_fix_tx_build_schedule($conn);
                $sandboxMessages[] = sprintf(
                    'Member schedule built: %d member(s) with a join date. Members without adhocs will now be included in the month runs.',
                    $schedule['members']
                );
                audit_trail_log($conn, 'system_fix_build_schedule', "Built schedule for {$schedule['members']} members");
                break;

            case 'sync_members':
                $sync = system_fix_tx_backfill_from_production($conn, $period);
                $scheduleCount = count(system_fix_tx_schedule_member_ids($conn, $period));
                $sandboxMessages[] = sprintf(
                    'Member sync for %s: %d schedule member(s) exist by that date; %d member(s) had no sandbox history and were pulled from production (%d row(s)).',
                    system_fix_tx_period_key($period),
                    $scheduleCount,
                    $sync['members'],
                    $sync['rows']
                );
                audit_trail_log($conn, 'system_fix_sync_members', "Synced {$sync['members']} members for " . system_fix_tx_period_key($period));
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
$sandboxScheduleCount = 0;
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
        $sandboxScheduleCount = count(system_fix_tx_schedule_member_ids($conn, $sandboxPeriod));
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

// --- Member statement ---------------------------------------------------------
// Emulates membership/profileaccount.php, but every figure is read from the
// sandbox `transactionsfix` table instead of `tblmemberaccounts`.

$statementMemberId = (int) ($_POST['statement_member'] ?? 0);
$statementMember = null;
$statementRows = [];
$statementError = '';

if ($statementMemberId > 0) {
    // Member details, same fields the profile statement shows.
    $stmt = $conn->prepare(
        "SELECT `MemberNo`, `MemberSurname`, `MemberFirstname`, `DateOfBirth`, `Gender`,
                `ApprovedBenefit`, `DateAccountOpened`, `Terminated`, `FundName`
         FROM `memberz2` WHERE `MemberID` = ? LIMIT 1"
    );
    $stmt->bind_param('i', $statementMemberId);
    $stmt->execute();
    $statementMember = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    if ($statementMember === null) {
        $statementError = 'No member details found for that member.';
    }

    // Latest sandbox balance (instead of `balances`).
    $stmt = $conn->prepare(
        "SELECT `NewBalance` FROM `transactionsfix`
         WHERE `memberID` = ?
         ORDER BY `TransactionDate` DESC, `accountsID` DESC LIMIT 1"
    );
    $stmt->bind_param('i', $statementMemberId);
    $stmt->execute();
    $statementBalance = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // The statement itself — newest first, exactly like the profile.
    $stmt = $conn->prepare(
        "SELECT `accountsID`, `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`,
                `Credit`, `StartingBalance`, `Amount`, `NewBalance`, `Comments`
         FROM `transactionsfix`
         WHERE `memberID` = ?
         ORDER BY `TransactionDate` DESC, `accountsID` DESC"
    );
    $stmt->bind_param('i', $statementMemberId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $statementRows[] = $row;
    }
    $stmt->close();
}

$statementMembers = [];
if ($sandboxTableReady) {
    if ($result = $conn->query("SELECT `MemberID`, `MemberNo`, `MemberSurname`, `MemberFirstname` FROM `memberz2` ORDER BY `MemberNo` ASC")) {
        while ($row = $result->fetch_assoc()) {
            $statementMembers[] = $row;
        }
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
            <h5 class="card-title">Run All Months</h5>

            <form method="post" enctype="multipart/form-data" id="runAllForm" class="row g-3 align-items-end">
              <div class="col-md-4">
                <label class="form-label" for="adhoc_csv_run">Adhocs spreadsheet (upload)</label>
                <input type="file" class="form-control" id="adhoc_csv_run" name="adhoc_csv" accept=".csv,.xlsx">
              </div>
              <div class="col-md-4">
                <label class="form-label" for="existing_csv2">…or adhocs on the server</label>
                <select class="form-select" id="existing_csv2" name="existing_csv">
                  <option value="">— none —</option>
                  <?php foreach ($existingCsvFiles as $csvFile): ?>
                  <option value="<?= htmlspecialchars($csvFile) ?>" <?= stripos($csvFile, 'adhoc') !== false ? 'selected' : '' ?>><?= htmlspecialchars($csvFile) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="run_from_month">Start from month <span class="text-muted">(optional)</span></label>
                <input type="month" class="form-control" id="run_from_month" name="run_from_month" value="">
              </div>

              <div class="col-md-4">
                <label class="form-label" for="regular_xlsx">Regulars spreadsheet (upload)</label>
                <input type="file" class="form-control" id="regular_xlsx" name="regular_xlsx" accept=".csv,.xlsx">
              </div>
              <div class="col-md-4">
                <label class="form-label" for="existing_regular">…or regulars on the server</label>
                <select class="form-select" id="existing_regular" name="existing_regular">
                  <option value="">— none —</option>
                  <?php foreach ($existingCsvFiles as $csvFile): ?>
                  <option value="<?= htmlspecialchars($csvFile) ?>" <?= stripos($csvFile, 'regular') !== false ? 'selected' : '' ?>><?= htmlspecialchars($csvFile) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4 d-grid">
                <button type="submit" name="action" value="run_all_months" class="btn btn-success" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-play-circle me-1"></i>Run All Months
                </button>
              </div>

              <div class="col-12">
                <div class="form-text">
                  Walks every month between the two spreadsheets, running each member's month end in order:
                  adhocs &amp; terminations → regular payments → interest → fixed monthly fee → admin fees,
                  carrying balances forward. Members without adhocs are included automatically, and any debit
                  the funds cannot cover terminates the member instead of going negative.
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php if ($sandboxTableReady && !empty($sandboxTerminationRows)): ?>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Latest Terminations
              <small class="text-muted">
                — <?= (int) $sandboxTerminationCount ?> recorded ·
                discrepancy E <?= htmlspecialchars(number_format($sandboxTerminationDiscrepancy, 2)) ?> ·
                fees E <?= htmlspecialchars(number_format($sandboxTerminationFees, 2)) ?>
              </small>
            </h5>
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

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Member Statement <small class="text-muted">(from <code>transactionsfix</code>)</small></h5>

            <form method="post" class="row g-3 align-items-end" id="statementForm">
              <div class="col-md-8">
                <label class="form-label" for="statement_member">Choose Member</label>
                <select class="form-select" id="statement_member" name="statement_member">
                  <option value="">— none —</option>
                  <?php foreach ($statementMembers as $m): ?>
                  <option value="<?= (int) $m['MemberID'] ?>" <?= $statementMemberId === (int) $m['MemberID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['MemberNo'] . ' - ' . $m['MemberSurname'] . ' ' . $m['MemberFirstname']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4 d-grid">
                <button type="submit" name="action" value="view_statement" class="btn btn-primary" <?= $sandboxTableReady ? '' : 'disabled' ?>>
                  <i class="bi bi-file-earmark-text me-1"></i>Preview Statement
                </button>
              </div>
            </form>

<?php if ($statementError !== ''): ?>
            <div class="alert alert-danger mt-3 mb-0" role="alert"><?= htmlspecialchars($statementError) ?></div>
<?php endif; ?>

<?php if ($statementMember !== null): ?>
            <table class="table datatable mt-3" style="width:100%">
              <thead>
                <tr>
                  <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                </tr>
                <tr style="text-align: center; background: black; color: white;">
                  <th scope="col" colspan="6">MEMBER DETAILS</th>
                </tr>
                <tr>
                  <th scope="col" style="vertical-align: top;">Full Name</th>
                  <td scope="col"><?= htmlspecialchars($statementMember['MemberFirstname'] . ' ' . $statementMember['MemberSurname']) ?></td>
                  <th scope="col" style="vertical-align: top;">MemberNo</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['MemberNo']) ?></td>
                  <th scope="col" style="vertical-align: top;">FundName</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['FundName']) ?></td>
                </tr>
                <tr>
                  <th scope="col" style="vertical-align: top;">Date of Birth</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['DateOfBirth']) ?></td>
                  <th scope="col" style="vertical-align: top;">Account Opened</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['DateAccountOpened']) ?></td>
                  <th scope="col" style="vertical-align: top;">Gender</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['Gender']) ?></td>
                </tr>
                <tr>
                  <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['ApprovedBenefit']) ?></td>
                  <th scope="col" style="vertical-align: top;">Terminated</th>
                  <td scope="col"><?= htmlspecialchars((string) $statementMember['Terminated']) ?></td>
                  <th scope="col" style="vertical-align: top;">Balance</th>
                  <td scope="col" style="font-weight: bold;">
                    <?= $statementBalance ? 'E ' . htmlspecialchars(number_format((float) $statementBalance['NewBalance'], 2)) : 'No data' ?>
                  </td>
                </tr>
                <tr style="text-align: center; background: black; color: white;">
                  <th scope="col" colspan="6">Transaction Statement</th>
                </tr>
              </thead>
            </table>

<?php if (!empty($statementRows)): ?>
            <table class="table datatable mt-3" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">TransactionDate</th>
                  <th scope="col">Details</th>
                  <th scope="col">Type</th>
                  <th scope="col">Comments</th>
                  <th scope="col">Prev balance</th>
                  <th scope="col">Amount</th>
                  <th scope="col">NewBalance</th>
                </tr>
              </thead>
              <tbody>
<?php foreach ($statementRows as $tRow): ?>
<?php $tType = ((string) $tRow['Credit'] === '1') ? 'Credit' : 'Debit'; ?>
                <tr>
                  <th scope="row"><?= htmlspecialchars((string) $tRow['TransactionDate']) ?></th>
                  <td><?= htmlspecialchars((string) $tRow['Details']) ?></td>
                  <td><?= htmlspecialchars($tType) ?></td>
                  <td><?= htmlspecialchars((string) $tRow['Comments']) ?></td>
                  <td><?= htmlspecialchars(number_format((float) $tRow['StartingBalance'], 2)) ?></td>
                  <td><?= htmlspecialchars(number_format((float) $tRow['Amount'], 2)) ?></td>
                  <td><?= htmlspecialchars(number_format((float) $tRow['NewBalance'], 2)) ?></td>
                </tr>
<?php endforeach; ?>
              </tbody>
            </table>
<?php else: ?>
            <p class="text-muted mt-3 mb-0">No transactions found in <code>transactionsfix</code> for this member.</p>
<?php endif; ?>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script>
// Initialise the statement tables (search / sort / paging), the same way the
// beneficiary profile preview does.
document.addEventListener('DOMContentLoaded', function () {
  if (typeof simpleDatatables === 'undefined') { return; }
  document.querySelectorAll('table.datatable').forEach(function (table) {
    if (table.datatableInitialised) { return; }
    try {
      new simpleDatatables.DataTable(table);
      table.datatableInitialised = true;
    } catch (e) {
      /* leave the plain table if initialisation fails */
    }
  });
});
</script>
</body>

</html>

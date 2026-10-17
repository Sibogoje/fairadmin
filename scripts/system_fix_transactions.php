<?php
/**
 * System Fix — Transactions sandbox engine.
 *
 * This library powers the "System Fix" administration page. It rebuilds the
 * month-end transaction run (adhoc payments, regular payments, transaction
 * fees, admin fees and interest allocation) inside a PARALLEL sandbox table
 * called `transactionsfix`.
 *
 * IMPORTANT: Every function in this file writes ONLY to `transactionsfix`.
 * Nothing here ever touches `tblmemberaccounts`, `balances`, `member_fees`,
 * `tblmembers` or any other production table. Reads are allowed so that the
 * sandbox can mirror production figures.
 *
 * Business rules mirror the live code:
 *   - Opening balance        : TransactionTypeID 1, Credit=1, amount = ApprovedBenefit (approve.php)
 *   - Transfer In Fee        : TransactionTypeID 2, 1% of the approved amount; NO charge when
 *                              the approved amount is below 20,000 (approve.php)
 *   - Regular payment        : TransactionTypeID 3, fixed amount     (scheduleprocess.php)
 *   - Adhoc payment          : TransactionTypeID 4, amount from CSV.
 *   - Adhoc transaction fee  : TransactionTypeID 5, amount * 2%      (adhocprocessall.php)
 *   - Regular transaction fee: TransactionTypeID 5, amount * TransactionPercent% (scheduleprocess.php)
 *   - Fixed monthly fee      : TransactionTypeID 6, member_fees.FixedMonthlyFee
 *   - Admin fees             : TransactionTypeID 7, (1.5% * balance / 365) * days (mprocess.php)
 *   - Interest allocation    : TransactionTypeID 8, proportional to balance share (interest.php)
 *
 * STARTING BALANCE RULE
 *   When a member has no rows in the current run, their starting balance is the
 *   LAST NewBalance recorded for them in `tblmemberaccounts` during the PREVIOUS
 *   month (e.g. running August 2014 reads July 2014). Members who are opening an
 *   account in the period do not need this lookup — their balance begins at zero
 *   and is established by the Opening Balance (type 1) entry.
 */

if (!function_exists('system_fix_tx_table')) {
    function system_fix_tx_table(): string
    {
        return 'transactionsfix';
    }
}

if (!function_exists('system_fix_tx_current_run')) {
    /**
     * Return the run id for the current session, creating one on first use.
     * A "run" keeps each reconstruction isolated so results never mix.
     */
    function system_fix_tx_current_run(): string
    {
        if (empty($_SESSION['system_fix_run_id'])) {
            $_SESSION['system_fix_run_id'] = 'SF-' . date('Ymd-His');
        }

        return (string) $_SESSION['system_fix_run_id'];
    }
}

if (!function_exists('system_fix_tx_days_in_month')) {
    /**
     * Reproduce the day-count used by Transactions/mprocess.php.
     * Note: the legacy code treats February as 30 days, so we mirror it exactly.
     */
    function system_fix_tx_days_in_month(int $month): int
    {
        return in_array($month, [1, 3, 5, 7, 8, 10, 12], true) ? 31 : 30;
    }
}

if (!function_exists('system_fix_tx_period_bounds')) {
    /**
     * Month bounds for a period date, plus the bounds of the PREVIOUS month.
     *
     * The previous-month end is the closing balance date used as the starting
     * balance for members with no activity in the current run.
     *
     * @return array{
     *     start:string, end:string,
     *     prev_start:string, prev_end:string,
     *     label:string, prev_label:string
     * }
     */
    function system_fix_tx_period_bounds(string $periodDate): array
    {
        $ts = strtotime($periodDate);
        $start = date('Y-m-01', $ts);
        $end = date('Y-m-t', $ts);

        $prevTs = strtotime($start . ' -1 day');

        return [
            'start' => $start,
            'end' => $end,
            'prev_start' => date('Y-m-01', $prevTs),
            'prev_end' => date('Y-m-t', $prevTs),
            'label' => date('F Y', $ts),
            'prev_label' => date('F Y', $prevTs),
        ];
    }
}

if (!function_exists('system_fix_tx_ensure_table')) {
    /**
     * Create the sandbox table if it does not exist yet.
     * Structure intentionally mirrors `tblmemberaccounts` so results can be
     * compared side by side and, if ever required, promoted later.
     */
    function system_fix_tx_ensure_table(mysqli $conn): bool
    {
        $table = system_fix_tx_table();

        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (
            `AccountsID` INT NOT NULL AUTO_INCREMENT,
            `RunID` VARCHAR(40) NOT NULL,
            `TransactionDate` DATE NULL,
            `TransactionTypeID` INT NULL,
            `memberID` INT NULL,
            `MemberNo` VARCHAR(20) NULL,
            `Details` VARCHAR(255) NULL,
            `Credit` TINYINT(1) NOT NULL DEFAULT 0,
            `StartingBalance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `NewBalance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Comments` VARCHAR(500) NULL,
            `Stage` VARCHAR(30) NULL,
            `CreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`AccountsID`),
            KEY `idx_txf_run` (`RunID`),
            KEY `idx_txf_member` (`memberID`),
            KEY `idx_txf_type` (`TransactionTypeID`),
            KEY `idx_txf_date` (`TransactionDate`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        return (bool) $conn->query($sql);
    }
}

if (!function_exists('system_fix_tx_summary')) {
    /**
     * High level state of the sandbox table.
     *
     * @return array{runs:int, rows:int, last_run:?array}
     */
    function system_fix_tx_summary(mysqli $conn): array
    {
        $table = system_fix_tx_table();
        $summary = ['runs' => 0, 'rows' => 0, 'last_run' => null];

        try {
            if ($result = $conn->query("SELECT COUNT(DISTINCT `RunID`) AS runs, COUNT(*) AS rows FROM `{$table}`")) {
                $row = $result->fetch_assoc();
                $summary['runs'] = (int) ($row['runs'] ?? 0);
                $summary['rows'] = (int) ($row['rows'] ?? 0);
            }

            if ($result = $conn->query(
                "SELECT `RunID`, MIN(`CreatedAt`) AS started_at, COUNT(*) AS rows,
                        MIN(`TransactionDate`) AS period_start, MAX(`TransactionDate`) AS period_end
                 FROM `{$table}`
                 GROUP BY `RunID`
                 ORDER BY started_at DESC
                 LIMIT 1"
            )) {
                $summary['last_run'] = $result->fetch_assoc() ?: null;
            }
        } catch (mysqli_sql_exception $e) {
            error_log('System Fix sandbox summary error: ' . $e->getMessage());
        }

        return $summary;
    }
}

if (!function_exists('system_fix_tx_clear_run')) {
    function system_fix_tx_clear_run(mysqli $conn, string $runId): int
    {
        $table = system_fix_tx_table();
        $stmt = $conn->prepare("DELETE FROM `{$table}` WHERE `RunID` = ?");
        $stmt->bind_param('s', $runId);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();

        return $deleted;
    }
}

if (!function_exists('system_fix_tx_resolve_member')) {
    /**
     * Resolve a CSV MemberNo into the internal tblmembers.MemberID.
     *
     * The adhocs CSV carries MemberNo (e.g. "Member No"), but every transaction
     * table keys on memberID. This is the single place that bridges the two, so
     * the system can run transactions against the correct memberID.
     *
     * Mirrors the CAST(... AS UNSIGNED) join used by generate_adhoc_sql.php so
     * that zero-padded MemberNo values (e.g. "001758") still match.
     */
    function system_fix_tx_resolve_member(mysqli $conn, string $memberNo): ?array
    {
        $stmt = $conn->prepare(
            "SELECT `MemberID`, `MemberNo`, `MemberSurname`, `MemberFirstname`, `Terminated`
             FROM `tblmembers`
             WHERE CAST(`MemberNo` AS UNSIGNED) = CAST(? AS UNSIGNED)
             LIMIT 1"
        );
        $stmt->bind_param('s', $memberNo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $row;
    }
}

if (!function_exists('system_fix_tx_parse_csv')) {
    /**
     * Parse an adhocs CSV into normalised rows.
     * Expected headers: MemberNo, Transaction Date, Comments, Amount
     *
     * @return array{rows:array<int,array>, errors:array<int,string>}
     */
    function system_fix_tx_parse_csv(string $filePath): array
    {
        $rows = [];
        $errors = [];

        if (($handle = fopen($filePath, 'r')) === false) {
            return ['rows' => [], 'errors' => ['Unable to open the uploaded CSV file.']];
        }

        $header = fgetcsv($handle, 0, ',');
        if ($header === false) {
            fclose($handle);
            return ['rows' => [], 'errors' => ['The CSV file is empty.']];
        }

        // Normalise header names so "Transaction Date", "transaction_date" etc. all map.
        $map = [];
        foreach ($header as $index => $name) {
            $key = strtolower(preg_replace('/[^a-z0-9]+/i', '', (string) $name));
            $map[$key] = $index;
        }

        $memberIdx = $map['memberno'] ?? 0;
        $dateIdx = $map['transactiondate'] ?? $map['date'] ?? 1;
        $commentIdx = $map['comments'] ?? $map['comment'] ?? 2;
        $amountIdx = $map['amount'] ?? 3;

        $lineNo = 1;
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $lineNo++;

            $memberNo = trim((string) ($data[$memberIdx] ?? ''));
            $rawDate = trim((string) ($data[$dateIdx] ?? ''));
            $comments = trim((string) ($data[$commentIdx] ?? ''));
            $rawAmount = str_replace([' ', ','], '', trim((string) ($data[$amountIdx] ?? '')));

            if ($memberNo === '' && $rawDate === '' && $rawAmount === '') {
                continue; // blank line
            }

            if ($memberNo === '' || !is_numeric($rawAmount)) {
                $errors[] = "Line {$lineNo}: skipped (MemberNo/Amount could not be read).";
                continue;
            }

            $date = system_fix_tx_normalise_date($rawDate);
            if ($date === null) {
                $errors[] = "Line {$lineNo}: unrecognised date '{$rawDate}' — using the period date instead.";
            }

            $rows[] = [
                'line' => $lineNo,
                'member_no' => $memberNo,
                'date' => $date,
                'comments' => $comments,
                'amount' => round((float) $rawAmount, 2),
            ];
        }

        fclose($handle);

        if (empty($rows) && empty($errors)) {
            $errors[] = 'No data rows were found in the CSV file.';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }
}

if (!function_exists('system_fix_tx_normalise_date')) {
    /**
     * Accept dd/mm/yyyy (the adhocs format), yyyy-mm-dd, or anything strtotime understands.
     * Returns Y-m-d, or null when the value cannot be interpreted.
     */
    function system_fix_tx_normalise_date(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
        }

        if (preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2})$#', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
        }

        $ts = strtotime($value);
        return $ts === false ? null : date('Y-m-d', $ts);
    }
}

if (!function_exists('system_fix_tx_preview_adhocs')) {
    /**
     * Resolve every CSV MemberNo to its tblmembers.MemberID WITHOUT writing
     * anything, so an administrator can confirm the mapping before staging.
     *
     * The CSV carries MemberNo; the transaction tables key on memberID, so this
     * is the step that connects the two.
     *
     * @param array<int,array> $rows Output of system_fix_tx_parse_csv().
     * @return array{
     *     lines:array<int,array>,
     *     matched:int,
     *     unmatched:int,
     *     total_amount:float
     * }
     */
    function system_fix_tx_preview_adhocs(mysqli $conn, array $rows): array
    {
        $lines = [];
        $matched = 0;
        $unmatched = 0;
        $total = 0.0;

        // Cache lookups so a member appearing many times is only resolved once.
        $cache = [];

        foreach ($rows as $row) {
            $memberNo = (string) $row['member_no'];

            if (!array_key_exists($memberNo, $cache)) {
                $cache[$memberNo] = system_fix_tx_resolve_member($conn, $memberNo);
            }
            $member = $cache[$memberNo];

            $amount = (float) $row['amount'];
            $total += $amount;

            if ($member === null) {
                $unmatched++;
                $lines[] = [
                    'line' => $row['line'],
                    'member_no' => $memberNo,
                    'member_id' => null,
                    'name' => '',
                    'terminated' => null,
                    'amount' => $amount,
                    'resolved' => false,
                ];
                continue;
            }

            $matched++;
            $lines[] = [
                'line' => $row['line'],
                'member_no' => (string) $member['MemberNo'],
                'member_id' => (int) $member['MemberID'],
                'name' => trim(((string) $member['MemberSurname']) . ' ' . ((string) $member['MemberFirstname'])),
                'terminated' => (int) $member['Terminated'],
                'amount' => $amount,
                'resolved' => true,
            ];
        }

        return [
            'lines' => $lines,
            'matched' => $matched,
            'unmatched' => $unmatched,
            'total_amount' => round($total, 2),
        ];
    }
}

if (!function_exists('system_fix_tx_stage_adhocs')) {
    /**
     * STAGE 1 — Read the adhocs CSV, resolve each MemberNo and write both the
     * adhoc payment (type 4) and its 2% transaction fee (type 5) to the sandbox.
     *
     * @param array<int,array> $rows Output of system_fix_tx_parse_csv().
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float}
     */
    function system_fix_tx_stage_adhocs(mysqli $conn, string $runId, array $rows, string $periodDate): array
    {
        $posted = 0;
        $skipped = [];
        $totalAmount = 0.0;
        $totalFee = 0.0;

        // Seed running balances from the run, then resolve each member's opening
        // balance (run balance, else previous-month closing balance) as we go.
        $balances = system_fix_tx_running_balances($conn, $runId);

        foreach ($rows as $row) {
            $member = system_fix_tx_resolve_member($conn, (string) $row['member_no']);
            if ($member === null) {
                $skipped[] = "Line {$row['line']}: member {$row['member_no']} was not found in tblmembers.";
                continue;
            }

            $memberId = (int) $member['MemberID'];
            $memberNo = (string) $member['MemberNo'];
            $date = $row['date'] ?: $periodDate;
            $amount = (float) $row['amount'];
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            $afterPayment = round($starting - $amount, 2);

            // --- Adhoc payment (type 4) ---------------------------------------
            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $date,
                'type' => 4,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Ad Hoc Payment',
                'credit' => 0,
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => $afterPayment,
                'comments' => (string) $row['comments'],
                'stage' => 'adhoc',
            ]);

            // --- Adhoc transaction fee (type 5) = 2% --------------------------
            $fee = round($amount * 0.02, 2);
            $afterFee = round($afterPayment - $fee, 2);
            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $date,
                'type' => 5,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Adhoc Transaction Fee',
                'credit' => 0,
                'starting' => $afterPayment,
                'amount' => $fee,
                'new_balance' => $afterFee,
                'comments' => (string) $row['comments'],
                'stage' => 'adhoc_fee',
            ]);

            // Carry the closing balance forward so the next adhoc for this
            // member (and the later stages) chains correctly.
            $balances[$memberId] = $afterFee;

            $posted++;
            $totalAmount += $amount;
            $totalFee += $fee;
        }

        return [
            'posted' => $posted,
            'skipped' => $skipped,
            'total_amount' => round($totalAmount, 2),
            'total_fee' => round($totalFee, 2),
        ];
    }
}

if (!function_exists('system_fix_tx_insert')) {
    /**
     * Low level insert helper for the sandbox table (single prepared statement).
     */
    function system_fix_tx_insert(mysqli $conn, array $row): void
    {
        $table = system_fix_tx_table();
        $stmt = $conn->prepare(
            "INSERT INTO `{$table}`
                (`RunID`, `TransactionDate`, `TransactionTypeID`, `memberID`, `MemberNo`, `Details`,
                 `Credit`, `StartingBalance`, `Amount`, `NewBalance`, `Comments`, `Stage`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $runId = (string) ($row['run_id'] ?? '');
        $date = (string) ($row['date'] ?? '');
        $type = (int) ($row['type'] ?? 0);
        $memberId = (int) ($row['member_id'] ?? 0);
        $memberNo = (string) ($row['member_no'] ?? '');
        $details = (string) ($row['details'] ?? '');
        $credit = (int) ($row['credit'] ?? 0);
        $starting = (float) ($row['starting'] ?? 0);
        $amount = (float) ($row['amount'] ?? 0);
        $newBalance = (float) ($row['new_balance'] ?? 0);
        $comments = (string) ($row['comments'] ?? '');
        $stage = (string) ($row['stage'] ?? '');

        $stmt->bind_param(
            'ssiissidddss',
            $runId,
            $date,
            $type,
            $memberId,
            $memberNo,
            $details,
            $credit,
            $starting,
            $amount,
            $newBalance,
            $comments,
            $stage
        );
        $stmt->execute();
        $stmt->close();
    }
}

if (!function_exists('system_fix_tx_member_balance')) {
    /**
     * Latest production balance for a member (read-only).
     */
    function system_fix_tx_member_balance(mysqli $conn, int $memberId): float
    {
        $stmt = $conn->prepare("SELECT `NewBalance` FROM `balances` WHERE `memberID` = ? LIMIT 1");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? (float) $row['NewBalance'] : 0.0;
    }
}

if (!function_exists('system_fix_tx_prior_month_balance')) {
    /**
     * LAST NewBalance in `tblmemberaccounts` for the month BEFORE the period.
     *
     * This is the starting balance used when a member has no rows in the
     * current run (e.g. running August 2014 reads the closing July 2014 balance).
     * Returns null when the member had no transactions in the previous month,
     * which lets the caller distinguish "opening account" from "prior balance".
     */
    function system_fix_tx_prior_month_balance(mysqli $conn, int $memberId, string $periodDate): ?float
    {
        $bounds = system_fix_tx_period_bounds($periodDate);

        $stmt = $conn->prepare(
            "SELECT `NewBalance`
             FROM `tblmemberaccounts`
             WHERE `memberID` = ?
               AND `TransactionDate` BETWEEN ? AND ?
             ORDER BY `TransactionDate` DESC, `AccountsID` DESC
             LIMIT 1"
        );
        $stmt->bind_param('iss', $memberId, $bounds['prev_start'], $bounds['prev_end']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row === null ? null : (float) $row['NewBalance'];
    }
}

if (!function_exists('system_fix_tx_base_balance')) {
    /**
     * Starting balance for a member at the beginning of the period.
     *
     * Resolution order:
     *   1. The member's closing balance from the run (if they already have rows).
     *   2. Their LAST NewBalance in `tblmemberaccounts` for the PREVIOUS month.
     *   3. Production `balances` as a last resort.
     *
     * Members opening an account in the period have no previous-month rows and
     * are expected to be handled by `system_fix_tx_stage_openings` (balance
     * starts at zero), so the production fallback is only a safety net.
     */
    function system_fix_tx_base_balance(mysqli $conn, string $runId, int $memberId, string $periodDate, ?array $runBalances = null): float
    {
        if ($runBalances === null) {
            $runBalances = system_fix_tx_running_balances($conn, $runId);
        }

        if (array_key_exists($memberId, $runBalances)) {
            return (float) $runBalances[$memberId];
        }

        $prior = system_fix_tx_prior_month_balance($conn, $memberId, $periodDate);
        if ($prior !== null) {
            return $prior;
        }

        return system_fix_tx_member_balance($conn, $memberId);
    }
}

if (!function_exists('system_fix_tx_running_balances')) {
    /**
     * Build the running balance for every member within a run, so each stage
     * chains off the previous stage's closing balance.
     *
     * @return array<int,float> [memberID => closing balance]
     */
    function system_fix_tx_running_balances(mysqli $conn, string $runId): array
    {
        $table = system_fix_tx_table();
        $balances = [];

        $stmt = $conn->prepare(
            "SELECT `memberID`, `NewBalance`
             FROM `{$table}`
             WHERE `RunID` = ?
             ORDER BY `AccountsID` ASC"
        );
        $stmt->bind_param('s', $runId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $balances[(int) $row['memberID']] = (float) $row['NewBalance'];
        }
        $stmt->close();

        return $balances;
    }
}

if (!function_exists('system_fix_tx_stage_regular')) {
    /**
     * STAGE 2 — Regular payments (type 3) plus their transaction fee (type 5).
     * Mirrors Transactions/scheduleprocess.php: rate comes from the member's
     * `TransactionPercent`, falling back to the fund fee table.
     *
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float}
     */
    function system_fix_tx_stage_regular(mysqli $conn, string $runId, string $periodDate, ?int $unused = null): array
    {
        $posted = 0;
        $totalAmount = 0.0;
        $totalFee = 0.0;

        $scopeIds = system_fix_tx_scope_ids($conn, $runId);
        if (empty($scopeIds)) {
            return ['posted' => 0, 'skipped' => ['No members in scope yet — import the adhocs CSV first.'], 'total_amount' => 0.0, 'total_fee' => 0.0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($scopeIds);
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `FixedPaymentAmount`, `TransactionPercent`, `balance`
                FROM `member_fees`
                WHERE `FixedPaymentAmount` > 0 AND `Terminated` = 0" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $amount = (float) $row['FixedPaymentAmount'];
            $percent = (float) $row['TransactionPercent'];

            // Prefer the run balance, else the member's previous-month closing balance.
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            $afterPayment = round($starting - $amount, 2);

            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $periodDate,
                'type' => 3,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Regular Payments',
                'credit' => 0,
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => $afterPayment,
                'comments' => '',
                'stage' => 'regular',
            ]);

            $fee = round($amount * ($percent / 100), 2);
            $afterFee = round($afterPayment - $fee, 2);
            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $periodDate,
                'type' => 5,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Scheduled Transaction Fee',
                'credit' => 0,
                'starting' => $afterPayment,
                'amount' => $fee,
                'new_balance' => $afterFee,
                'comments' => '',
                'stage' => 'regular_fee',
            ]);

            $balances[$memberId] = $afterFee;

            $posted++;
            $totalAmount += $amount;
            $totalFee += $fee;
        }
        $stmt->close();

        return [
            'posted' => $posted,
            'skipped' => [],
            'total_amount' => round($totalAmount, 2),
            'total_fee' => round($totalFee, 2),
        ];
    }
}

if (!function_exists('system_fix_tx_stage_admin_fees')) {
    /**
     * STAGE 3 — Admin fees (type 7). Mirrors Transactions/mprocess.php:
     *   fee = (1.5% * balance / 365) * days, where days = 31 or 30 for the month.
     *
     * @return array{posted:int, total_amount:float}
     */
    function system_fix_tx_stage_admin_fees(mysqli $conn, string $runId, string $periodDate): array
    {
        $posted = 0;
        $total = 0.0;
        $month = (int) date('n', strtotime($periodDate));
        $days = system_fix_tx_days_in_month($month);

        $scopeIds = system_fix_tx_scope_ids($conn, $runId);
        if (empty($scopeIds)) {
            return ['posted' => 0, 'total_amount' => 0.0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($scopeIds);
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `balance` FROM `member_fees` WHERE `Terminated` = 0" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            $fee = round(((1.5 / 100) * $starting / 365) * $days, 2);

            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $periodDate,
                'type' => 7,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Admin Fees',
                'credit' => 0,
                'starting' => $starting,
                'amount' => $fee,
                'new_balance' => round($starting - $fee, 2),
                'comments' => '',
                'stage' => 'admin_fee',
            ]);

            $posted++;
            $total += $fee;
        }
        $stmt->close();

        return ['posted' => $posted, 'total_amount' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_stage_fixed_fees')) {
    /**
     * STAGE 4 — Fixed monthly fees (type 6) from member_fees.FixedMonthlyFee.
     *
     * @return array{posted:int, total_amount:float}
     */
    function system_fix_tx_stage_fixed_fees(mysqli $conn, string $runId, string $periodDate): array
    {
        $posted = 0;
        $total = 0.0;

        $scopeIds = system_fix_tx_scope_ids($conn, $runId);
        if (empty($scopeIds)) {
            return ['posted' => 0, 'total_amount' => 0.0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($scopeIds);
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `FixedMonthlyFee`, `balance` FROM `member_fees` WHERE `Terminated` = 0" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $amount = (float) $row['FixedMonthlyFee'];
            if ($amount <= 0) {
                continue;
            }

            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $periodDate,
                'type' => 6,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Fixed Monthly Fee',
                'credit' => 0,
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => round($starting - $amount, 2),
                'comments' => '',
                'stage' => 'fixed_fee',
            ]);

            $posted++;
            $total += $amount;
        }
        $stmt->close();

        return ['posted' => $posted, 'total_amount' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_scope_ids')) {
    /**
     * Member IDs that appear in the run — i.e. the members who came through the
     * imported adhocs CSV. Every "run"-style stage is limited to this list so a
     * month-end rebuild only ever touches the members being repaired.
     *
     * @return array<int,int>
     */
    function system_fix_tx_scope_ids(mysqli $conn, string $runId): array
    {
        $table = system_fix_tx_table();
        $ids = [];

        $stmt = $conn->prepare(
            "SELECT DISTINCT `memberID` FROM `{$table}` WHERE `RunID` = ? AND `memberID` > 0"
        );
        $stmt->bind_param('s', $runId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['memberID'];
        }
        $stmt->close();

        return $ids;
    }
}

if (!function_exists('system_fix_tx_in_scope')) {
    /**
     * Append an IN (...) clause for the scoped member IDs.
     *
     * Returns [sqlFragment, types, params]. When the scope is empty the fragment
     * is " AND 1 = 0", which deliberately matches nothing so no stage can run
     * before the adhocs CSV has defined the members to repair.
     *
     * @param array<int,int> $scopeIds
     * @return array{0:string, 1:string, 2:array<int,int>}
     */
    function system_fix_tx_in_scope(array $scopeIds): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $scopeIds), static fn ($id) => $id > 0)));

        if (empty($ids)) {
            return [' AND 1 = 0', '', []];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        return [' AND `MemberID` IN (' . $placeholders . ')', str_repeat('i', count($ids)), $ids];
    }
}

if (!function_exists('system_fix_tx_stage_openings')) {
    /**
     * STAGE — Opening balances for new members (types 1 and 2).
     *
     * Mirrors membership/approve.php:
     *   - Type 1 "Opening Balance": Credit = 1, amount = members.ApprovedBenefit,
     *     starting at zero, closing balance = the approved benefit.
     *   - Type 2 "Transfer In Fee": 1% of the approved amount. NO fee is charged
     *     when the approved amount is below 20,000 (the legacy code posts a zero
     *     fee in that case).
     *
     * This is a RETROSPECTIVE repair run: the members being rebuilt are already
     * active, so openings are selected by `Terminated = 0`. They must NOT be
     * terminated by this process.
     *
     * Scope: ONLY members who appear in the imported adhocs CSV for this run are
     * considered. Members who came through the CSV and are missing an opening
     * balance get their type 1 / type 2 entries rebuilt here.
     *
     * Opening members ARE included in interest for the period — they already
     * have transactions (the opening balance and, where applicable, the
     * transfer-in fee), and their opening rows participate in the interest
     * denominator just like everyone else. Run this stage BEFORE interest.
     *
     * @return array{
     *     posted:int, fee_count:int, no_fee_count:int,
     *     total_opening:float, total_fee:float, skipped:array<int,string>
     * }
     */
    function system_fix_tx_stage_openings(mysqli $conn, string $runId, string $periodDate, ?int $terminatedFilter = 0): array
    {
        $posted = 0;
        $feeCount = 0;
        $noFeeCount = 0;
        $totalOpening = 0.0;
        $totalFee = 0.0;
        $skipped = [];

        $scopeIds = system_fix_tx_scope_ids($conn, $runId);
        if (empty($scopeIds)) {
            return [
                'posted' => 0,
                'fee_count' => 0,
                'no_fee_count' => 0,
                'total_opening' => 0.0,
                'total_fee' => 0.0,
                'skipped' => ['No members in scope yet — import the adhocs CSV first.'],
            ];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($scopeIds);

        $sql = "SELECT `MemberID`, `MemberNo`, `ApprovedBenefit`, `Terminated`, `DateAccountOpened`
                FROM `tblmembers`
                WHERE `ApprovedBenefit` > 0" . $scopeSql;
        if ($terminatedFilter !== null) {
            // Retrospective rebuild targets members that are already active.
            $sql .= " AND `Terminated` = " . (int) $terminatedFilter;
        }

        $stmt = $conn->prepare($sql);
        if ($scopeTypes !== '') {
            $stmt->bind_param($scopeTypes, ...$scopeParams);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = (string) $row['MemberNo'];
            $approved = (float) $row['ApprovedBenefit'];

            // Skip members already represented in this run so they are never doubled.
            if (system_fix_tx_member_has_rows($conn, $runId, $memberId)) {
                $skipped[] = "Member {$memberNo} already has rows in this run — opening entry skipped.";
                continue;
            }

            $openingDate = system_fix_tx_normalise_date((string) $row['DateAccountOpened']) ?: $periodDate;

            // --- Opening Balance (type 1), Credit = 1 -------------------------
            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $openingDate,
                'type' => 1,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Opening Balance',
                'credit' => 1,
                'starting' => 0.00,
                'amount' => round($approved, 2),
                'new_balance' => round($approved, 2),
                'comments' => '',
                'stage' => 'opening',
            ]);

            // --- Transfer In Fee (type 2): 1%, waived below 20,000 ------------
            $fee = $approved < 20000 ? 0.00 : round($approved * 0.01, 2);
            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $openingDate,
                'type' => 2,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Transfer In Fee',
                'credit' => 0,
                'starting' => round($approved, 2),
                'amount' => $fee,
                'new_balance' => round($approved - $fee, 2),
                'comments' => $fee == 0.00 ? 'No charge (below 20,000)' : '',
                'stage' => 'transfer_in_fee',
            ]);

            if ($fee > 0) {
                $feeCount++;
            } else {
                $noFeeCount++;
            }

            $posted++;
            $totalOpening += $approved;
            $totalFee += $fee;
        }
        $stmt->close();

        return [
            'posted' => $posted,
            'fee_count' => $feeCount,
            'no_fee_count' => $noFeeCount,
            'total_opening' => round($totalOpening, 2),
            'total_fee' => round($totalFee, 2),
            'skipped' => $skipped,
        ];
    }
}

if (!function_exists('system_fix_tx_member_has_rows')) {
    function system_fix_tx_member_has_rows(mysqli $conn, string $runId, int $memberId): bool
    {
        $table = system_fix_tx_table();
        $stmt = $conn->prepare("SELECT 1 FROM `{$table}` WHERE `RunID` = ? AND `memberID` = ? LIMIT 1");
        $stmt->bind_param('si', $runId, $memberId);
        $stmt->execute();
        $found = $stmt->get_result()->fetch_row() !== null;
        $stmt->close();

        return $found;
    }
}

if (!function_exists('system_fix_tx_stage_interest')) {
    /**
     * STAGE — Interest allocation (type 8).
     *
     * The pool amount is pulled from the CURRENT `tblmemberaccounts` for the
     * period: the total interest paid in that month, i.e. SUM(Amount) where
     * TransactionTypeID = 8. Each member then receives a share proportional to
     * their balance, exactly as Transactions/interest.php does.
     *
     * @return array{posted:int, pool:float, source_rows:int, total_amount:float}
     */
    function system_fix_tx_stage_interest(mysqli $conn, string $runId, string $periodDate): array
    {
        $bounds = system_fix_tx_period_bounds($periodDate);

        // Pool = total interest already allocated for the month in production.
        $pool = 0.0;
        $sourceRows = 0;
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS rows_count, COALESCE(SUM(`Amount`), 0) AS pool
             FROM `tblmemberaccounts`
             WHERE `TransactionTypeID` = 8
               AND `TransactionDate` BETWEEN ? AND ?"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $pool = (float) $row['pool'];
            $sourceRows = (int) $row['rows_count'];
        }
        $stmt->close();

        $posted = 0;
        $total = 0.0;

        if ($pool <= 0) {
            return ['posted' => 0, 'pool' => 0.0, 'source_rows' => $sourceRows, 'total_amount' => 0.0];
        }

        // Denominator and beneficiaries are limited to members in scope.
        $balances = system_fix_tx_balance_denominator($conn, $runId, $periodDate);
        $totalFunds = array_sum($balances);

        if ($totalFunds <= 0) {
            return ['posted' => 0, 'pool' => $pool, 'source_rows' => $sourceRows, 'total_amount' => 0.0];
        }

        foreach ($balances as $memberId => $starting) {
            $interest = round(($starting / $totalFunds) * $pool, 2);
            $memberNo = system_fix_tx_member_no($conn, $memberId);

            system_fix_tx_insert($conn, [
                'run_id' => $runId,
                'date' => $periodDate,
                'type' => 8,
                'member_id' => $memberId,
                'member_no' => $memberNo,
                'details' => 'Monthly Interest ' . date('F', strtotime($periodDate)) . ' Allocated',
                'credit' => 1,
                'starting' => $starting,
                'amount' => $interest,
                'new_balance' => round($starting + $interest, 2),
                'comments' => '',
                'stage' => 'interest',
            ]);

            $posted++;
            $total += $interest;
        }

        return [
            'posted' => $posted,
            'pool' => round($pool, 2),
            'source_rows' => $sourceRows,
            'total_amount' => round($total, 2),
        ];
    }
}

if (!function_exists('system_fix_tx_member_no')) {
    function system_fix_tx_member_no(mysqli $conn, int $memberId): string
    {
        $stmt = $conn->prepare("SELECT `MemberNo` FROM `tblmembers` WHERE `MemberID` = ? LIMIT 1");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? (string) $row['MemberNo'] : '';
    }
}

if (!function_exists('system_fix_tx_balance_denominator')) {
    /**
     * Balance-per-member to use as the interest denominator.
     *
     * Scoped to the members who came through the adhocs CSV for this run.
     * Walks the run from newest row backwards so each member only contributes
     * their most recent closing balance. Scoped members not yet touched by the
     * run fall back to their PREVIOUS-MONTH closing balance in tblmemberaccounts.
     *
     * @return array<int,float> [memberID => balance]
     */
    function system_fix_tx_balance_denominator(mysqli $conn, string $runId, string $periodDate): array
    {
        $table = system_fix_tx_table();
        $balances = [];

        $stmt = $conn->prepare(
            "SELECT `memberID`, `NewBalance`
             FROM `{$table}`
             WHERE `RunID` = ?
             ORDER BY `AccountsID` DESC"
        );
        $stmt->bind_param('s', $runId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['memberID'];
            if (!array_key_exists($memberId, $balances)) {
                $balances[$memberId] = (float) $row['NewBalance'];
            }
        }
        $stmt->close();

        // Scoped members with no rows in this run: use their previous-month close.
        $scopeIds = system_fix_tx_scope_ids($conn, $runId);
        foreach ($scopeIds as $memberId) {
            if (!array_key_exists($memberId, $balances)) {
                $balances[$memberId] = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            }
        }

        return $balances;
    }
}

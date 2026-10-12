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
 *   - Adhoc payment          : TransactionTypeID 4, amount from CSV.
 *   - Adhoc transaction fee  : TransactionTypeID 5, amount * 2%      (adhocprocessall.php)
 *   - Regular payment        : TransactionTypeID 3, fixed amount     (scheduleprocess.php)
 *   - Regular transaction fee: TransactionTypeID 5, amount * TransactionPercent% (scheduleprocess.php)
 *   - Admin fees             : TransactionTypeID 7, (1.5% * balance / 365) * days (mprocess.php)
 *   - Fixed monthly fee      : TransactionTypeID 6, member_fees.FixedMonthlyFee
 *   - Interest allocation    : TransactionTypeID 8, proportional to balance share (interest.php)
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
     * Mirrors the CAST(... AS UNSIGNED) join used by generate_adhoc_sql.php so
     * that zero-padded MemberNo values still match.
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
            $starting = system_fix_tx_member_balance($conn, $memberId);
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
                'new_balance' => round($afterPayment - $fee, 2),
                'comments' => (string) $row['comments'],
                'stage' => 'adhoc_fee',
            ]);

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
    function system_fix_tx_stage_regular(mysqli $conn, string $runId, string $periodDate, ?string $memberIdFilter = null): array
    {
        $posted = 0;
        $totalAmount = 0.0;
        $totalFee = 0.0;

        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `FixedPaymentAmount`, `TransactionPercent`, `balance`
                FROM `member_fees`
                WHERE `FixedPaymentAmount` > 0 AND `Terminated` = 0";
        if ($memberIdFilter !== null && $memberIdFilter !== '') {
            $sql .= " AND `MemberID` = " . (int) $memberIdFilter;
        }

        $result = $conn->query($sql);
        while ($result && $row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $amount = (float) $row['FixedPaymentAmount'];
            $percent = (float) $row['TransactionPercent'];

            // Prefer the sandbox running balance; fall back to production.
            $starting = $balances[$memberId] ?? (float) $row['balance'];
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
                'new_balance' => round($afterPayment - $fee, 2),
                'comments' => '',
                'stage' => 'regular_fee',
            ]);

            $posted++;
            $totalAmount += $amount;
            $totalFee += $fee;
        }

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
    function system_fix_tx_stage_admin_fees(mysqli $conn, string $runId, string $periodDate, ?string $memberIdFilter = null): array
    {
        $posted = 0;
        $total = 0.0;
        $month = (int) date('n', strtotime($periodDate));
        $days = system_fix_tx_days_in_month($month);

        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `balance` FROM `member_fees` WHERE `Terminated` = 0";
        if ($memberIdFilter !== null && $memberIdFilter !== '') {
            $sql .= " AND `MemberID` = " . (int) $memberIdFilter;
        }

        $result = $conn->query($sql);
        while ($result && $row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = $balances[$memberId] ?? (float) $row['balance'];

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

        return ['posted' => $posted, 'total_amount' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_stage_fixed_fees')) {
    /**
     * STAGE 4 — Fixed monthly fees (type 6) from member_fees.FixedMonthlyFee.
     *
     * @return array{posted:int, total_amount:float}
     */
    function system_fix_tx_stage_fixed_fees(mysqli $conn, string $runId, string $periodDate, ?string $memberIdFilter = null): array
    {
        $posted = 0;
        $total = 0.0;
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `FixedMonthlyFee`, `balance` FROM `member_fees` WHERE `Terminated` = 0";
        if ($memberIdFilter !== null && $memberIdFilter !== '') {
            $sql .= " AND `MemberID` = " . (int) $memberIdFilter;
        }

        $result = $conn->query($sql);
        while ($result && $row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $amount = (float) $row['FixedMonthlyFee'];
            if ($amount <= 0) {
                continue;
            }

            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = $balances[$memberId] ?? (float) $row['balance'];

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

        return ['posted' => $posted, 'total_amount' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_stage_interest')) {
    /**
     * STAGE 5 — Interest allocation (type 8).
     *
     * The pool amount is pulled from the CURRENT `tblmemberaccounts` for the
     * period: the total interest paid in that month, i.e. SUM(Amount) where
     * TransactionTypeID = 8. Each member then receives a share proportional to
     * their balance, exactly as Transactions/interest.php does.
     *
     * @return array{posted:int, pool:float, source_rows:int, total_amount:float}
     */
    function system_fix_tx_stage_interest(mysqli $conn, string $runId, string $periodDate, ?string $memberIdFilter = null): array
    {
        $monthStart = date('Y-m-01', strtotime($periodDate));
        $monthEnd = date('Y-m-t', strtotime($periodDate));

        // Pool = total interest already allocated for the month in production.
        $pool = 0.0;
        $sourceRows = 0;
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS rows_count, COALESCE(SUM(`Amount`), 0) AS pool
             FROM `tblmemberaccounts`
             WHERE `TransactionTypeID` = 8
               AND `TransactionDate` BETWEEN ? AND ?"
        );
        $stmt->bind_param('ss', $monthStart, $monthEnd);
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

        // Total sandbox balances after the previous stages — the denominator.
        $balances = system_fix_tx_balance_denominator($conn, $runId, $memberIdFilter);
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
     * Walks the sandbox run from newest row backwards so each member only
     * contributes their most recent closing balance. Members present in
     * `member_fees` but not yet touched by the run fall back to their
     * production balance so nobody is silently left out.
     *
     * @return array<int,float> [memberID => balance]
     */
    function system_fix_tx_balance_denominator(mysqli $conn, string $runId, ?string $memberIdFilter = null): array
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

        // Fill in members that had no activity in this run from production.
        $sql = "SELECT `MemberID`, `balance` FROM `member_fees` WHERE `Terminated` = 0";
        if ($memberIdFilter !== null && $memberIdFilter !== '') {
            $sql .= " AND `MemberID` = " . (int) $memberIdFilter;
        }

        $result = $conn->query($sql);
        while ($result && $row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            if (!array_key_exists($memberId, $balances)) {
                $balances[$memberId] = (float) $row['balance'];
            }
        }

        return $balances;
    }
}

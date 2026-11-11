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
 *   - Regular payment        : TransactionTypeID 3, amount READ from tblmemberaccounts for the
 *                              month (captured into transactionsfix_regular), then debited
 *   - Regular transaction fee: TransactionTypeID 5, amount * TransactionPercent%
 *   - Adhoc payment          : TransactionTypeID 4, amount from CSV.
 *   - Adhoc transaction fee  : TransactionTypeID 5, amount * 2%      (adhocprocessall.php)
 *   - Additional capital     : TransactionTypeID 9, amount from the capital sheet, posted on
 *                              the spreadsheet date; fee type 5 = 1%, waived below 20,000
 *                              (capitalprocess.php charges 1%, the waiver mirrors type 1)
 *   - Fixed monthly fee      : TransactionTypeID 6, flat 10.00 per member; Coca-Cola fund
 *                              (RetirementFundID 27) members are EXEMPT
 *   - Admin fees             : TransactionTypeID 7, (1.5% * balance / 365) * days (mprocess.php)
 *   - Interest allocation    : TransactionTypeID 8, proportional to balance share (interest.php)
 *
 * RUNNING MEMBER ROSTER
 *   `transactionsfix_members` records, per period, which members existed in the fund at
 *   that time (updated whenever a CSV is staged). The month-end stages run for the roster
 *   members, so the fix mirrors everyone who was in the fund then — not just CSV importers.
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
     * The current run identifier.
     *
     * The sandbox now mirrors `tblmemberaccounts` (no RunID column), so a "run"
     * is identified by its PERIOD: the YYYY-MM of the month being rebuilt. This
     * keeps months isolated — staging August never mixes with July — because all
     * queries filter on TransactionDate within that month.
     */
    function system_fix_tx_current_run(): string
    {
        $period = $_SESSION['system_fix_period'] ?? date('Y-m-t');
        return date('Y-m', strtotime($period));
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

if (!function_exists('system_fix_tx_running_members_table')) {
    function system_fix_tx_running_members_table(): string
    {
        return 'transactionsfix_members';
    }
}

if (!function_exists('system_fix_tx_regular_capture_table')) {
    function system_fix_tx_regular_capture_table(): string
    {
        return 'transactionsfix_regular';
    }
}

if (!function_exists('system_fix_tx_interest_pool_table')) {
    function system_fix_tx_interest_pool_table(): string
    {
        return 'interestfix';
    }
}

if (!function_exists('system_fix_tx_terminations_table')) {
    function system_fix_tx_terminations_table(): string
    {
        return 'transactionsfix_terminations';
    }
}

if (!function_exists('system_fix_tx_schedule_table')) {
    function system_fix_tx_schedule_table(): string
    {
        return 'transactionsfix_schedule';
    }
}

if (!function_exists('system_fix_tx_impairment_table')) {
    /**
     * Standalone impairment working table.
     *
     * One row per member on the uploaded impairment sheet, holding the balance
     * BEFORE the impairment, the impairment itself and the resulting balance
     * AFTER it. Unlike `transactionsfix`, this table is allowed to go negative:
     * it is the true record of what the impairment does to the member, even when
     * the fund balance is not enough to absorb it.
     */
    function system_fix_tx_impairment_table(): string
    {
        return 'transactionsfix_impairment';
    }
}

if (!function_exists('system_fix_tx_termination_fee_percent')) {
    /**
     * Termination fee percentage (1%). All 736 members carry
     * `member_fees.TerminationFeePercent = 1`, so this is the standing rate.
     * The link member -> fund is available if a per-fund rate is ever needed.
     */
    function system_fix_tx_termination_fee_percent(): float
    {
        return 1.0;
    }
}

if (!function_exists('system_fix_tx_cocacola_fund_id')) {
    /**
     * RetirementFundID for the Coca-Cola fund. Members in this fund are exempt
     * from the fixed monthly fee. The link is member -> deceased -> retirement
     * fund, surfaced by `member_fees.F_ID` (member_fees is a VIEW).
     */
    function system_fix_tx_cocacola_fund_id(): int
    {
        return 27; // Eswatini Coca Cola Provident Fund
    }
}

if (!function_exists('system_fix_tx_excluded_member_nos')) {
    /**
     * Member numbers that are BARRED from the fix rebuild.
     *
     * These accounts must never appear in the sandbox — no history, no adhoc, no
     * capital, no regular payment, no interest, no fee and no termination — so
     * every stage skips them. Change this list to add or remove a member; the
     * helpers below propagate it to all stages.
     *
     * @return array<int,string>
     */
    function system_fix_tx_excluded_member_nos(): array
    {
        return ['269', '1740'];
    }
}

if (!function_exists('system_fix_tx_normalise_member_no')) {
    /**
     * Normalise a MemberNo for comparison: trim it and drop leading zeros, so
     * "269", "0269", " 269 " and 269 all compare equal.
     */
    function system_fix_tx_normalise_member_no($memberNo): string
    {
        $memberNo = trim((string) $memberNo);
        if ($memberNo === '') {
            return '';
        }

        $stripped = ltrim($memberNo, '0');

        // "0" or "0000" is not a member number.
        return $stripped === '' ? '' : $stripped;
    }
}

if (!function_exists('system_fix_tx_is_excluded_member_no')) {
    /**
     * Is this MemberNo on the exclusion list?
     */
    function system_fix_tx_is_excluded_member_no($memberNo): bool
    {
        $normalised = system_fix_tx_normalise_member_no($memberNo);
        if ($normalised === '') {
            return false;
        }

        foreach (system_fix_tx_excluded_member_nos() as $excluded) {
            if (system_fix_tx_normalise_member_no($excluded) === $normalised) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('system_fix_tx_excluded_member_ids')) {
    /**
     * The excluded MemberNos resolved to tblmembers.MemberID, cached per
     * connection. Empty when the members do not exist.
     *
     * @return array<int,int>
     */
    function system_fix_tx_excluded_member_ids(mysqli $conn): array
    {
        static $cache = [];

        $cacheKey = spl_object_id($conn);
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $ids = [];
        $numericNos = [];
        foreach (system_fix_tx_excluded_member_nos() as $memberNo) {
            $value = (int) system_fix_tx_normalise_member_no($memberNo);
            if ($value > 0) {
                $numericNos[] = $value;
            }
        }

        if (!empty($numericNos)) {
            // Values are cast to int above, so inlining them is safe.
            $list = implode(',', array_unique($numericNos));
            $result = $conn->query(
                "SELECT `MemberID` FROM `tblmembers` WHERE CAST(`MemberNo` AS UNSIGNED) IN ({$list})"
            );
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $memberId = (int) $row['MemberID'];
                    if ($memberId > 0) {
                        $ids[] = $memberId;
                    }
                }
            }
        }

        return $cache[$cacheKey] = array_values(array_unique($ids));
    }
}

if (!function_exists('system_fix_tx_is_excluded_member_id')) {
    /**
     * Is this tblmembers.MemberID on the exclusion list?
     */
    function system_fix_tx_is_excluded_member_id(mysqli $conn, int $memberId): bool
    {
        if ($memberId <= 0) {
            return false;
        }

        return in_array($memberId, system_fix_tx_excluded_member_ids($conn), true);
    }
}

if (!function_exists('system_fix_tx_filter_excluded')) {
    /**
     * Drop excluded members from a list of MemberIDs.
     *
     * @param array<int,int> $ids
     * @return array<int,int>
     */
    function system_fix_tx_filter_excluded(mysqli $conn, array $ids): array
    {
        $excluded = system_fix_tx_excluded_member_ids($conn);
        if (empty($excluded)) {
            return array_values($ids);
        }

        $kept = [];
        foreach ($ids as $memberId) {
            $memberId = (int) $memberId;
            if ($memberId > 0 && !in_array($memberId, $excluded, true)) {
                $kept[] = $memberId;
            }
        }

        return $kept;
    }
}

if (!function_exists('system_fix_tx_excluded_sql')) {
    /**
     * "AND `<column>` NOT IN (...)" fragment for bulk INSERT ... SELECT and
     * aggregate queries. Returns an empty string when nothing is excluded.
     *
     * `$column` accepts a bare column name or a qualified one ("m.MemberID");
     * qualify it whenever the statement joins another table that also exposes
     * the column, otherwise MySQL reports it as ambiguous.
     *
     * `$column` is always a column name from this file, never user input.
     */
    function system_fix_tx_excluded_sql(mysqli $conn, string $column = 'memberID'): string
    {
        $excluded = system_fix_tx_excluded_member_ids($conn);
        if (empty($excluded)) {
            return '';
        }

        $qualified = implode('.', array_map(
            static fn(string $part): string => '`' . $part . '`',
            explode('.', $column)
        ));

        return ' AND ' . $qualified . ' NOT IN (' . implode(',', array_map('intval', $excluded)) . ')';
    }
}

if (!function_exists('system_fix_tx_ensure_table')) {
    /**
     * Create the sandbox tables if they do not exist yet.
     *
     *   transactionsfix          — the rebuilt transactions (mirrors tblmemberaccounts)
     *   transactionsfix_members  — running roster of members in the fund at a period
     *   transactionsfix_regular  — captured regular-payment amounts per member/month
     *   interestfix              — summed interest pool (type 8) per month/year
     *   transactionsfix_schedule — member ledger schedule (join date + capital movements)
     *   transactionsfix_impairment — uploaded impairments with before/after balances
     *
     * All are sandbox objects; production tables are read-only.
     */
    function system_fix_tx_ensure_table(mysqli $conn): bool
    {
        $table = system_fix_tx_table();
        $membersTable = system_fix_tx_running_members_table();
        $regularTable = system_fix_tx_regular_capture_table();
        $interestTable = system_fix_tx_interest_pool_table();
        $termTable = system_fix_tx_terminations_table();
        $scheduleTable = system_fix_tx_schedule_table();
        $impairmentTable = system_fix_tx_impairment_table();

        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (
            `accountsID` INT(10) NOT NULL AUTO_INCREMENT,
            `TransactionDate` DATE DEFAULT NULL,
            `TransactionTypeID` INT(10) DEFAULT NULL,
            `memberID` INT(10) DEFAULT NULL,
            `Details` VARCHAR(255) DEFAULT NULL,
            `Credit` CHAR(1) NOT NULL DEFAULT '0',
            `StartingBalance` DOUBLE(10,2) DEFAULT NULL,
            `Amount` DOUBLE(10,2) DEFAULT NULL,
            `NewBalance` DOUBLE(10,2) DEFAULT NULL,
            `Comments` VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (`accountsID`),
            KEY `memberID` (`memberID`),
            KEY `TransactionTypeID` (`TransactionTypeID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $membersSql = "CREATE TABLE IF NOT EXISTS `{$membersTable}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `Period` CHAR(7) NOT NULL,
            `memberID` INT NOT NULL,
            `MemberNo` VARCHAR(20) NULL,
            `FundID` INT NULL,
            `Source` VARCHAR(30) NULL,
            `AddedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_period_member` (`Period`, `memberID`),
            KEY `idx_members_period` (`Period`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $regularSql = "CREATE TABLE IF NOT EXISTS `{$regularTable}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `Period` CHAR(7) NOT NULL,
            `memberID` INT NOT NULL,
            `Amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Transactions` INT NOT NULL DEFAULT 0,
            `Source` VARCHAR(30) NULL,
            `CapturedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_period_member` (`Period`, `memberID`),
            KEY `idx_regular_period` (`Period`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $interestSql = "CREATE TABLE IF NOT EXISTS `{$interestTable}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `Period` CHAR(7) NOT NULL,
            `Year` SMALLINT NOT NULL,
            `Month` TINYINT NOT NULL,
            `Amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `SourceRows` INT NOT NULL DEFAULT 0,
            `CreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_period` (`Period`),
            KEY `idx_interest_year_month` (`Year`, `Month`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $termSql = "CREATE TABLE IF NOT EXISTS `{$termTable}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `Period` CHAR(7) NOT NULL,
            `TransactionDate` DATE NULL,
            `MemberNo` VARCHAR(20) NOT NULL,
            `memberID` INT NOT NULL,
            `AmountRequested` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `AmountAvailable` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `AmountGiven` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Discrepancy` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `TerminationFee` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Reason` VARCHAR(50) NULL,
            `Source` VARCHAR(30) NULL,
            `CreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_term_period` (`Period`),
            KEY `idx_term_member` (`memberID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $scheduleSql = "CREATE TABLE IF NOT EXISTS `{$scheduleTable}` (
            `memberID` INT NOT NULL,
            `MemberNo` VARCHAR(20) NULL,
            `DateJoined` DATE NULL,
            `Source` VARCHAR(20) NULL,
            `BuiltAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`memberID`),
            KEY `idx_schedule_joined` (`DateJoined`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $impairmentSql = "CREATE TABLE IF NOT EXISTS `{$impairmentTable}` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `RunID` CHAR(7) NOT NULL,
            `TransactionDate` DATE NULL,
            `MemberNo` VARCHAR(20) NOT NULL,
            `memberID` INT NULL,
            `BalanceBefore` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Impairment` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `BalanceAfter` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `AppliedAmount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
            `Posted` TINYINT(1) NOT NULL DEFAULT 0,
            `Source` VARCHAR(80) NULL,
            `CreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_impairment_run` (`RunID`),
            KEY `idx_impairment_member` (`memberID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        return (bool) $conn->query($sql)
            && (bool) $conn->query($membersSql)
            && (bool) $conn->query($regularSql)
            && (bool) $conn->query($interestSql)
            && (bool) $conn->query($termSql)
            && (bool) $conn->query($scheduleSql)
            && (bool) $conn->query($impairmentSql);
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
            // `rows` is a reserved word in MariaDB, so it must be quoted.
            if ($result = $conn->query(
                "SELECT COUNT(DISTINCT DATE_FORMAT(`TransactionDate`, '%Y-%m')) AS runs, COUNT(*) AS `rows`
                 FROM `{$table}`"
            )) {
                $row = $result->fetch_assoc();
                $summary['runs'] = (int) ($row['runs'] ?? 0);
                $summary['rows'] = (int) ($row['rows'] ?? 0);
            }

            if ($result = $conn->query(
                "SELECT DATE_FORMAT(`TransactionDate`, '%Y-%m') AS RunID, COUNT(*) AS `rows`,
                        MIN(`TransactionDate`) AS period_start, MAX(`TransactionDate`) AS period_end
                 FROM `{$table}`
                 GROUP BY RunID
                 ORDER BY period_start DESC
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

if (!function_exists('system_fix_tx_seed_history')) {
    /**
     * Copy historical rows from `tblmemberaccounts` into the sandbox, so the
     * rebuild starts from a known-good baseline.
     *
     * Defaults to everything BEFORE 2014-05-01 — this establishes opening
     * balances for the months that will be rebuilt from the spreadsheets.
     * Reads production; writes ONLY to `transactionsfix` (period-scoped by
     * TransactionDate, so it sits outside the months being rebuilt).
     */
    function system_fix_tx_seed_history(mysqli $conn, string $beforeDate = '2014-05-01'): array
    {
        $table = system_fix_tx_table();

        // Idempotent: remove any previous seed for the same window first.
        $del = $conn->prepare("DELETE FROM `{$table}` WHERE `TransactionDate` < ?");
        $del->bind_param('s', $beforeDate);
        $del->execute();
        $deleted = $del->affected_rows;
        $del->close();

        $stmt = $conn->prepare(
            "INSERT INTO `{$table}`
                (`TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                 `StartingBalance`, `Amount`, `NewBalance`, `Comments`)
             SELECT `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                    `StartingBalance`, `Amount`, `NewBalance`, `Comments`
             FROM `tblmemberaccounts`
             WHERE `TransactionDate` < ?" . system_fix_tx_excluded_sql($conn)
        );
        $stmt->bind_param('s', $beforeDate);
        $stmt->execute();
        $inserted = $stmt->affected_rows;
        $stmt->close();

        // Members present in the seeded history exist in the fund at that time.
        $rosterPeriod = date('Y-m', strtotime($beforeDate . ' -1 day'));
        $idStmt = $conn->prepare(
            "SELECT DISTINCT `memberID` FROM `{$table}` WHERE `TransactionDate` < ? AND `memberID` > 0"
        );
        $idStmt->bind_param('s', $beforeDate);
        $idStmt->execute();
        $idResult = $idStmt->get_result();
        $memberIds = [];
        while ($row = $idResult->fetch_assoc()) {
            $memberIds[] = (int) $row['memberID'];
        }
        $idStmt->close();
        $memberIds = system_fix_tx_filter_excluded($conn, $memberIds);
        $rosterAdded = system_fix_tx_roster_add($conn, $rosterPeriod, $memberIds, 'seed');

        return [
            'deleted' => $deleted,
            'inserted' => $inserted,
            'members' => count($memberIds),
            'roster_period' => $rosterPeriod,
            'roster_added' => $rosterAdded,
        ];
    }
}

if (!function_exists('system_fix_tx_clear_run')) {
    /**
     * Clear all sandbox rows for a period (YYYY-MM). The period is derived from
     * TransactionDate because the sandbox mirrors tblmemberaccounts exactly.
     */
    function system_fix_tx_clear_run(mysqli $conn, string $runId): int
    {
        $table = system_fix_tx_table();
        $period = substr($runId, 0, 7);
        $bounds = system_fix_tx_period_bounds($period . '-01');

        $stmt = $conn->prepare(
            "DELETE FROM `{$table}` WHERE `TransactionDate` BETWEEN ? AND ?"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();

        // The impairment working rows for this month describe balances that no
        // longer exist once the month is cleared, so drop them as well.
        $impairmentTable = system_fix_tx_impairment_table();
        $stmt = $conn->prepare("DELETE FROM `{$impairmentTable}` WHERE `RunID` = ?");
        $stmt->bind_param('s', $period);
        $stmt->execute();
        $stmt->close();

        return $deleted;
    }
}

if (!function_exists('system_fix_tx_clear_all')) {
    /**
     * Empty EVERY sandbox table, so a rebuild can start from a clean slate.
     * Production tables are never touched.
     *
     * @return array<string,int> rows deleted, keyed by short table name
     */
    function system_fix_tx_clear_all(mysqli $conn): array
    {
        $tables = [
            'transactions' => system_fix_tx_table(),
            'roster' => system_fix_tx_running_members_table(),
            'regular' => system_fix_tx_regular_capture_table(),
            'interest' => system_fix_tx_interest_pool_table(),
            'terminations' => system_fix_tx_terminations_table(),
            'schedule' => system_fix_tx_schedule_table(),
            'impairment' => system_fix_tx_impairment_table(),
        ];

        $cleared = [];
        foreach ($tables as $key => $table) {
            $conn->query("DELETE FROM `{$table}`");
            $cleared[$key] = (int) $conn->affected_rows;
        }

        return $cleared;
    }
}

if (!function_exists('system_fix_tx_purge_excluded')) {
    /**
     * Remove any sandbox trace of the excluded members left by an earlier run.
     *
     * Every stage already skips them, so no new row can appear; this cleans up
     * what a previous build wrote. The interest pool is a per-month aggregate
     * rather than per member, so it must be rebuilt (Build interest pool) for
     * their share to drop out of the allocation.
     *
     * @return array<string,int> rows deleted, keyed by short table name
     */
    function system_fix_tx_purge_excluded(mysqli $conn): array
    {
        $excluded = system_fix_tx_excluded_member_ids($conn);
        if (empty($excluded)) {
            return [];
        }

        $list = implode(',', array_map('intval', $excluded));
        $targets = [
            'transactions' => system_fix_tx_table(),
            'roster' => system_fix_tx_running_members_table(),
            'regular' => system_fix_tx_regular_capture_table(),
            'terminations' => system_fix_tx_terminations_table(),
            'schedule' => system_fix_tx_schedule_table(),
            'impairment' => system_fix_tx_impairment_table(),
        ];

        $deleted = [];
        foreach ($targets as $key => $table) {
            try {
                $conn->query("DELETE FROM `{$table}` WHERE `memberID` IN ({$list})");
                if ($conn->affected_rows > 0) {
                    $deleted[$key] = (int) $conn->affected_rows;
                }
            } catch (mysqli_sql_exception $e) {
                error_log("System Fix: unable to purge excluded members from {$table}: " . $e->getMessage());
            }
        }

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
     *
     * Members on the exclusion list (system_fix_tx_excluded_member_nos) are still
     * resolved, but carry `Excluded => true` so callers can skip them with a
     * clear message instead of posting a transaction.
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

        if ($row !== null) {
            $row['Excluded'] = system_fix_tx_is_excluded_member_no($row['MemberNo'])
                || system_fix_tx_is_excluded_member_no($memberNo);
        }

        return $row;
    }
}

if (!function_exists('system_fix_tx_parse_csv')) {
    /**
     * Parse an adhocs spreadsheet (CSV or .xlsx) into normalised rows.
     * Expected headers: Member No, Transaction Date, Comments, Amount
     * An optional Transaction Type / Type ID column is also honoured.
     *
     * The default type is Ad Hoc Payment (4); the additional-capital reader
     * passes 9 so rows without a type column are treated as capital.
     *
     * @return array{rows:array<int,array>, errors:array<int,string>}
     */
    function system_fix_tx_parse_csv(string $filePath, int $defaultType = 4): array
    {
        // Detect the format from the NAME or the CONTENT. Uploaded files arrive
        // with an extensionless temp name (e.g. phpA1B2.tmp), so the content sniff
        // is essential — otherwise an .xlsx would be parsed as CSV and every row
        // would fail.
        if (preg_match('/\.xlsx$/i', $filePath) || system_fix_tx_looks_like_xlsx($filePath)) {
            return system_fix_tx_parse_xlsx($filePath, $defaultType);
        }

        $rows = [];
        $errors = [];

        if (($handle = fopen($filePath, 'r')) === false) {
            return ['rows' => [], 'errors' => ['Unable to open the uploaded file.']];
        }

        $header = fgetcsv($handle, 0, ',');
        $dataRows = [];
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $dataRows[] = $data;
        }
        fclose($handle);

        return system_fix_tx_rows_to_adhocs($header ?: [], $dataRows, $defaultType);
    }
}

if (!function_exists('system_fix_tx_parse_capital')) {
    /**
     * Parse the ADDITIONAL CAPITAL spreadsheet.
     *
     * The sheet has the same layout as the adhocs file
     * (`Member No | Transaction Date | Details | Amount`) but every row is
     * Additional Capital (TransactionTypeID 9) unless the file carries its own
     * type column.
     *
     * @return array{rows:array<int,array>, errors:array<int,string>}
     */
    function system_fix_tx_parse_capital(string $filePath): array
    {
        return system_fix_tx_parse_csv($filePath, 9);
    }
}

if (!function_exists('system_fix_tx_looks_like_xlsx')) {
    /**
     * Sniff whether a file is really an .xlsx (a ZIP archive). The first bytes of
     * any ZIP are "PK", which is enough to tell it apart from CSV text.
     */
    function system_fix_tx_looks_like_xlsx(string $filePath): bool
    {
        if (!is_file($filePath)) {
            return false;
        }

        $handle = @fopen($filePath, 'rb');
        if ($handle === false) {
            return false;
        }
        $magic = (string) fread($handle, 2);
        fclose($handle);

        return $magic === 'PK';
    }
}

if (!function_exists('system_fix_tx_parse_xlsx')) {
    /**
     * Parse an .xlsx adhocs file into the same shape as the CSV parser.
     *
     * @return array{rows:array<int,array>, errors:array<int,string>}
     */
    function system_fix_tx_parse_xlsx(string $filePath, int $defaultType = 4): array
    {
        $read = system_fix_tx_xlsx_rows($filePath);
        if (!empty($read['errors'])) {
            return ['rows' => [], 'errors' => $read['errors']];
        }

        $all = $read['rows'];
        if (empty($all)) {
            return ['rows' => [], 'errors' => ['The spreadsheet is empty.']];
        }

        $header = array_shift($all);
        return system_fix_tx_rows_to_adhocs($header, $all, $defaultType);
    }
}

if (!function_exists('system_fix_tx_rows_to_adhocs')) {
    /**
     * Turn a header row + data rows into normalised adhoc rows. Shared by the
     * CSV and XLSX readers so both behave identically.
     *
     * @param array<int,string> $header
     * @param array<int,array>  $dataRows
     * @param int               $defaultType Type used when the row has no type column.
     * @return array{rows:array<int,array>, errors:array<int,string>}
     */
    function system_fix_tx_rows_to_adhocs(array $header, array $dataRows, int $defaultType = 4): array
    {
        $rows = [];
        $errors = [];

        // Normalise header names so "Member No", "member_no" etc. all map.
        $map = [];
        foreach ($header as $index => $name) {
            $key = strtolower(preg_replace('/[^a-z0-9]+/i', '', (string) $name));
            $map[$key] = $index;
        }

        $memberIdx = $map['memberno'] ?? 0;
        $dateIdx = $map['transactiondate'] ?? $map['date'] ?? 1;
        $commentIdx = $map['comments'] ?? $map['comment'] ?? $map['details'] ?? 2;
        $amountIdx = $map['amount'] ?? 3;
        $typeIdx = $map['transactiontypeid'] ?? $map['transactiontype'] ?? $map['type'] ?? $map['typeid'] ?? null;

        $lineNo = 1;
        foreach ($dataRows as $data) {
            $lineNo++;

            $memberNo = trim((string) ($data[$memberIdx] ?? ''));
            $rawDate = trim((string) ($data[$dateIdx] ?? ''));
            $comments = trim((string) ($data[$commentIdx] ?? ''));
            // Strip currency symbols, thousands separators and spaces.
            $rawAmount = preg_replace('/[^0-9.\-]/', '', (string) ($data[$amountIdx] ?? ''));

            $type = $defaultType;
            if ($typeIdx !== null && isset($data[$typeIdx]) && trim((string) $data[$typeIdx]) !== '') {
                $type = (int) $data[$typeIdx];
            }

            if ($memberNo === '' && $rawDate === '' && $rawAmount === '') {
                continue; // blank line
            }

            if ($memberNo === '' || !is_numeric($rawAmount)) {
                $errors[] = "Line {$lineNo}: skipped (Member No/Amount could not be read).";
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
                'type' => $type,
            ];
        }

        if (empty($rows) && empty($errors)) {
            $errors[] = 'No data rows were found in the file.';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }
}

if (!function_exists('system_fix_tx_xlsx_rows')) {
    /**
     * Read the first worksheet of an .xlsx file into a plain array of rows.
     *
     * Uses ZipArchive + SimpleXML (both bundled with XAMPP PHP) so no external
     * library is required. Dates stored as Excel serial numbers are returned as
     * numbers; system_fix_tx_normalise_date() converts them later.
     *
     * @return array{rows:array<int,array<int,string>>, errors:array<int,string>}
     */
    function system_fix_tx_xlsx_rows(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            return ['rows' => [], 'errors' => ['The PHP zip extension is required to read .xlsx files.']];
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return ['rows' => [], 'errors' => ['Unable to open the .xlsx file.']];
        }

        // Shared strings table (values referenced by t="s" cells).
        $shared = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $sx = @simplexml_load_string($sharedXml);
            if ($sx !== false) {
                foreach ($sx->si as $si) {
                    // Concatenate all <t> runs (formatted text).
                    $text = '';
                    foreach ($si->t as $t) {
                        $text .= (string) $t;
                    }
                    if ($text === '' && isset($si->r)) {
                        foreach ($si->r as $run) {
                            $text .= (string) $run->t;
                        }
                    }
                    $shared[] = $text;
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            $zip->close();
            return ['rows' => [], 'errors' => ['The .xlsx file has no first worksheet.']];
        }

        $sheet = @simplexml_load_string($sheetXml);
        $zip->close();
        if ($sheet === false) {
            return ['rows' => [], 'errors' => ['Unable to parse the worksheet XML.']];
        }

        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $cells = [];
            foreach ($row->c as $c) {
                $ref = (string) $c['r'];
                // Column letter -> zero-based index (A=0, B=1, ...).
                preg_match('#^([A-Z]+)#', $ref, $m);
                $colLetters = $m[1] ?? 'A';
                $colIndex = 0;
                for ($i = 0; $i < strlen($colLetters); $i++) {
                    $colIndex = $colIndex * 26 + (ord($colLetters[$i]) - 64);
                }
                $colIndex--;

                $type = (string) $c['t'];
                if ($type === 'inlineStr') {
                    // Some writers (openpyxl, Google Sheets, newer Excel) store
                    // text inline instead of in xl/sharedStrings.xml.
                    $value = '';
                    foreach ($c->is->t as $inlineText) {
                        $value .= (string) $inlineText;
                    }
                    if ($value === '' && isset($c->is->r)) {
                        foreach ($c->is->r as $run) {
                            $value .= (string) $run->t;
                        }
                    }
                } else {
                    $value = (string) $c->v;
                    if ($type === 's') {
                        $value = $shared[(int) $value] ?? '';
                    }
                }

                $cells[$colIndex] = $value;
            }

            if (!empty($cells)) {
                // Fill gaps so indexes line up, then order by column.
                $max = max(array_keys($cells));
                $ordered = [];
                for ($i = 0; $i <= $max; $i++) {
                    $ordered[$i] = $cells[$i] ?? '';
                }
                $rows[] = $ordered;
            }
        }

        return ['rows' => $rows, 'errors' => []];
    }
}

if (!function_exists('system_fix_tx_normalise_date')) {
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

        // Month picker value (YYYY-MM) — the period is the END of that month.
        if (preg_match('#^(\d{4})-(\d{1,2})$#', $value, $m)) {
            $monthStart = sprintf('%04d-%02d-01', (int) $m[1], (int) $m[2]);
            return date('Y-m-t', strtotime($monthStart));
        }

        // Excel serial date (days since 1899-12-30), typically 20000–60000.
        if (preg_match('#^\d{4,6}(\.\d+)?$#', $value)) {
            $serial = (float) $value;
            if ($serial >= 1000 && $serial <= 80000) {
                $unix = ($serial - 25569) * 86400;
                return gmdate('Y-m-d', (int) $unix);
            }
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
                    'excluded' => false,
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
                'excluded' => !empty($member['Excluded']),
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

if (!function_exists('system_fix_tx_record_termination')) {
    /**
     * Record a termination in the sandbox termination table.
     *
     * Records EVERY termination, including exact matches (discrepancy 0).
     * The member is NOT modified in `tblmembers` during the fix — the decision
     * is simply recorded here for review/application.
     *
     * @return array{Discrepancy:float, AmountGiven:float, TerminationFee:float}
     */
    function system_fix_tx_record_termination(
        mysqli $conn,
        string $period,
        string $date,
        int $memberId,
        string $memberNo,
        float $requested,
        float $available,
        float $given,
        float $terminationFee,
        string $reason,
        string $source = 'adhoc'
    ): array {
        $table = system_fix_tx_terminations_table();
        $discrepancy = round(max(0.0, $requested - $available), 2);

        $stmt = $conn->prepare(
            "INSERT INTO `{$table}`
                (`Period`, `TransactionDate`, `MemberNo`, `memberID`, `AmountRequested`,
                 `AmountAvailable`, `AmountGiven`, `Discrepancy`, `TerminationFee`, `Reason`, `Source`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'sssidddddss',
            $period,
            $date,
            $memberNo,
            $memberId,
            $requested,
            $available,
            $given,
            $discrepancy,
            $terminationFee,
            $reason,
            $source
        );
        $stmt->execute();
        $stmt->close();

        return [
            'Discrepancy' => $discrepancy,
            'AmountGiven' => $given,
            'TerminationFee' => $terminationFee,
        ];
    }
}

if (!function_exists('system_fix_tx_terminated_ids')) {
    /**
     * Members terminated in the sandbox ON OR BEFORE the given date.
     *
     * Termination is a ONE-TIME event: once a member is terminated they take no
     * further part in any run — no adhocs, no regulars, no capital, no fees and
     * no interest. Passing the date being processed is what makes month-by-month
     * rebuilds correct: when rebuilding an earlier month, a termination that
     * happened later must NOT hide the member from that earlier month.
     *
     * Pass null for "terminated at any point" (used by reporting helpers).
     *
     * @return array<int,int>
     */
    function system_fix_tx_terminated_ids(mysqli $conn, ?string $onOrBefore = null): array
    {
        $table = system_fix_tx_table();
        $ids = [];

        if ($onOrBefore === null) {
            $result = $conn->query(
                "SELECT DISTINCT `memberID` FROM `{$table}` WHERE `TransactionTypeID` = 11 AND `memberID` > 0"
            );
        } else {
            $stmt = $conn->prepare(
                "SELECT DISTINCT `memberID` FROM `{$table}`
                 WHERE `TransactionTypeID` = 11 AND `memberID` > 0
                   AND `TransactionDate` <= ?"
            );
            $stmt->bind_param('s', $onOrBefore);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        while ($result && $row = $result->fetch_assoc()) {
            $ids[] = (int) $row['memberID'];
        }

        if (isset($stmt)) {
            $stmt->close();
        }

        return $ids;
    }
}

if (!function_exists('system_fix_tx_is_terminated')) {
    /**
     * Has this member been terminated at, or before, the given date?
     *
     * A null date means "terminated at any point".
     */
    function system_fix_tx_is_terminated(mysqli $conn, int $memberId, ?string $onOrBefore = null): bool
    {
        $table = system_fix_tx_table();

        if ($onOrBefore === null) {
            $stmt = $conn->prepare(
                "SELECT 1 FROM `{$table}` WHERE `TransactionTypeID` = 11 AND `memberID` = ? LIMIT 1"
            );
            $stmt->bind_param('i', $memberId);
        } else {
            $stmt = $conn->prepare(
                "SELECT 1 FROM `{$table}`
                 WHERE `TransactionTypeID` = 11 AND `memberID` = ? AND `TransactionDate` <= ?
                 LIMIT 1"
            );
            $stmt->bind_param('is', $memberId, $onOrBefore);
        }

        $stmt->execute();
        $found = $stmt->get_result()->fetch_row() !== null;
        $stmt->close();

        return $found;
    }
}

if (!function_exists('system_fix_tx_filter_active')) {
    /**
     * Remove members terminated on or before the supplied date, so only members
     * still active for that period are processed by the month-end stages.
     *
     * @param array<int,int> $ids
     * @return array<int,int>
     */
    function system_fix_tx_filter_active(mysqli $conn, array $ids, ?string $onOrBefore = null): array
    {
        $terminated = system_fix_tx_terminated_ids($conn, $onOrBefore);
        if (empty($terminated)) {
            return $ids;
        }

        return array_values(array_diff($ids, $terminated));
    }
}

if (!function_exists('system_fix_tx_scheduled_termination_ids')) {
    /**
     * Members whose termination lands inside the given period, per the
     * `terminations` view.
     *
     * Members in this list must be EXCLUDED from the month-end stages for that
     * month (interest, admin and fixed fees): they are paid out on their
     * termination date, so an end-of-month charge or interest credit afterwards
     * would leave the account non-zero — or negative once the fee is deducted.
     *
     * @return array<int,int>
     */
    function system_fix_tx_scheduled_termination_ids(mysqli $conn, string $periodDate): array
    {
        $bounds = system_fix_tx_period_bounds($periodDate);
        $ids = [];

        try {
            $stmt = $conn->prepare(
                "SELECT DISTINCT `MemberID` FROM `terminations`
                 WHERE `TerminationDate` BETWEEN ? AND ?" . system_fix_tx_excluded_sql($conn)
            );
            $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $ids[] = (int) $row['MemberID'];
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // The view may not have been created yet; the run still works.
            error_log('System Fix: terminations view unavailable: ' . $e->getMessage());
        }

        return $ids;
    }
}

if (!function_exists('system_fix_tx_termination_rows')) {
    /**
     * Termination rows from the `terminations` view that fall inside a period,
     * oldest first, ready to post.
     *
     * @return array<int,array{MemberID:int, MemberNo:string, TerminationDate:string, Amount:float}>
     */
    function system_fix_tx_termination_rows(mysqli $conn, string $periodDate): array
    {
        $bounds = system_fix_tx_period_bounds($periodDate);
        $rows = [];

        try {
            $stmt = $conn->prepare(
                "SELECT `MemberID`, `MemberNo`, `TerminationDate`, `Amount`
                 FROM `terminations`
                 WHERE `TerminationDate` BETWEEN ? AND ?" . system_fix_tx_excluded_sql($conn) . "
                 ORDER BY `TerminationDate` ASC, `MemberID` ASC"
            );
            $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $rows[] = [
                    'MemberID' => (int) $row['MemberID'],
                    'MemberNo' => (string) $row['MemberNo'],
                    'TerminationDate' => (string) $row['TerminationDate'],
                    'Amount' => (float) $row['Amount'],
                ];
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            error_log('System Fix: terminations view unavailable: ' . $e->getMessage());
        }

        return $rows;
    }
}

if (!function_exists('system_fix_tx_filter_dormant')) {
    /**
     * Drop members whose sandbox balance is zero or below.
     *
     * A member with nothing left in the fund can no longer transact: charging an
     * admin or fixed fee would drive the balance negative, and interest on a zero
     * balance is pointless. They are excluded from every run from that point on.
     *
     * @param array<int,int>  $ids
     * @param array<int,float> $balances Closing balance per member.
     * @return array<int,int>
     */
    function system_fix_tx_filter_dormant(array $ids, array $balances): array
    {
        $active = [];
        foreach ($ids as $memberId) {
            $memberId = (int) $memberId;
            if ($memberId <= 0) {
                continue;
            }
            if (($balances[$memberId] ?? 0.0) > 0.0) {
                $active[] = $memberId;
            }
        }

        return $active;
    }
}

if (!function_exists('system_fix_tx_stage_adhocs')) {
    /**
     * STAGE 1 — Read the adhocs CSV, resolve each MemberNo and write both the
     * adhoc payment (type 4) and its 2% transaction fee (type 5) to the sandbox.
     *
     * Additional capital rows (type 9, from the capital sheet) are accepted here
     * as well so the two sources are posted in DATE ORDER within the month: the
     * sandbox chains NewBalance by insertion order, so posting every capital row
     * before every adhoc row would give a member a balance that never actually
     * existed on the adhoc's date. On the same date capital is posted first
     * (money in before money out).
     *
     * @param array<int,array> $rows        Output of system_fix_tx_parse_csv().
     * @param array<int,array> $capitalRows Output of system_fix_tx_parse_capital().
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float,
     *               terminations:int, termination_fee:float, capital_amount:float,
     *               capital_fee:float, capital_posted:int, capital_duplicates:int}
     */
    function system_fix_tx_stage_adhocs(mysqli $conn, string $runId, array $rows, string $periodDate, array $capitalRows = []): array
    {
        $posted = 0;
        $skipped = [];
        $totalAmount = 0.0;
        $totalFee = 0.0;
        $terminations = 0;
        $terminationFeeTotal = 0.0;
        $capitalPosted = 0;
        $capitalAmount = 0.0;
        $capitalFeeTotal = 0.0;
        $capitalDuplicates = 0;
        $capitalMemo = [];
        $bounds = system_fix_tx_period_bounds($periodDate);
        $alreadyTerminated = [];

        // Seed running balances from the run, then resolve each member's opening
        // balance (run balance, else previous-month closing balance) as we go.
        $balances = system_fix_tx_running_balances($conn, $runId);

        // Merge both sources into one date-ordered list for this month.
        $flows = [];
        foreach ($rows as $row) {
            $date = $row['date'] ?? null;
            if ($date === null || $date < $bounds['start'] || $date > $bounds['end']) {
                continue;
            }
            $flows[] = ['kind' => 'adhoc', 'date' => $date, 'line' => (int) ($row['line'] ?? 0), 'row' => $row];
        }
        foreach ($capitalRows as $row) {
            $date = $row['date'] ?? null;
            if ($date === null || $date < $bounds['start'] || $date > $bounds['end']) {
                continue;
            }
            $flows[] = ['kind' => 'capital', 'date' => $date, 'line' => (int) ($row['line'] ?? 0), 'row' => $row];
        }

        // Terminations come from the `terminations` VIEW and are posted on the
        // date the member actually left the fund, not at month end.
        foreach (system_fix_tx_termination_rows($conn, $periodDate) as $row) {
            $flows[] = [
                'kind' => 'termination',
                'date' => $row['TerminationDate'],
                'line' => $row['MemberID'],
                'row' => $row,
            ];
        }

        // Date first. On the same date the order is:
        //   capital (money in) -> adhoc (money out) -> termination (payout)
        // so a member who deposits, withdraws and leaves on one day is handled
        // in the only order that cannot produce a negative balance.
        usort($flows, static function (array $a, array $b): int {
            $byDate = strcmp($a['date'], $b['date']);
            if ($byDate !== 0) {
                return $byDate;
            }
            $rank = ['capital' => 0, 'adhoc' => 1, 'termination' => 2];
            $aRank = $rank[$a['kind']] ?? 3;
            $bRank = $rank[$b['kind']] ?? 3;
            if ($aRank !== $bRank) {
                return $aRank <=> $bRank;
            }
            return $a['line'] <=> $b['line'];
        });

        foreach ($flows as $flow) {
            $row = $flow['row'];
            $date = $flow['date'];

            // --- Additional capital (type 9) ---------------------------------
            if ($flow['kind'] === 'capital') {
                $result = system_fix_tx_post_capital_row($conn, $runId, $periodDate, $row, $balances, $alreadyTerminated, $capitalMemo);
                if ($result['skipped'] !== null) {
                    $skipped[] = $result['skipped'];
                    continue;
                }
                if ($result['duplicate']) {
                    $capitalDuplicates++;
                    continue;
                }
                if ($result['posted']) {
                    $capitalPosted++;
                    $posted++;
                    $capitalAmount += $result['amount'];
                    $capitalFeeTotal += $result['fee'];
                }
                continue;
            }

            // --- Termination from the `terminations` view --------------------
            //
            // The member is paid out on the date they actually left the fund.
            // The payout is whatever balance is available AT THAT MOMENT ("the
            // amount available at the time of running will be the money
            // deducted"), so the account can never end up negative. The view's
            // Amount is passed as AmountRequested, which keeps the discrepancy
            // reportable in transactionsfix_terminations.
            if ($flow['kind'] === 'termination') {
                $term = $flow['row'];
                $termMemberId = (int) $term['MemberID'];

                // Already paid out earlier in this run? Nothing more to do.
                if ($alreadyTerminated[$termMemberId] ?? system_fix_tx_is_terminated($conn, $termMemberId, $date)) {
                    $alreadyTerminated[$termMemberId] = true;
                    continue;
                }

                $alreadyTerminated[$termMemberId] = true;

                $availableNow = system_fix_tx_base_balance($conn, $runId, $termMemberId, $periodDate, $balances);

                // Nothing left to pay out — the account is already empty, so
                // there is no transaction to post and no fee to charge.
                if ($availableNow <= 0.0) {
                    $balances[$termMemberId] = 0.0;
                    continue;
                }

                $termResult = system_fix_tx_terminate_member(
                    $conn,
                    $runId,
                    $periodDate,
                    $date,
                    $termMemberId,
                    (string) $term['MemberNo'],
                    (float) $term['Amount'],
                    $availableNow,
                    'view',
                    'Terminated per terminations view'
                );

                $balances[$termMemberId] = 0.0;
                $terminations++;
                $terminationFeeTotal += $termResult['TerminationFee'];
                $posted++;
                continue;
            }

            $member = system_fix_tx_resolve_member($conn, (string) $row['member_no']);
            if ($member === null) {
                $skipped[] = "Line {$row['line']}: member {$row['member_no']} was not found in tblmembers.";
                continue;
            }

            $memberId = (int) $member['MemberID'];
            $memberNo = (string) $member['MemberNo'];

            // Members barred from the fix are skipped outright.
            if (!empty($member['Excluded']) || system_fix_tx_is_excluded_member_id($conn, $memberId)) {
                $skipped[] = "Line {$row['line']}: member {$memberNo} is excluded from the fix and was skipped.";
                continue;
            }

            $requested = (float) $row['amount'];
            // Type comes from the spreadsheet when present, else Ad Hoc Payment (4).
            $type = (int) ($row['type'] ?? 4);

            // --- Already terminated? Record only, never post a transaction. ----
            // Termination happens ONCE. Any later adhoc for a terminated member
            // is logged for review and nothing is written to the ledger. The
            // date is passed so an adhoc that happened BEFORE the (later)
            // termination is still posted.
            if ($alreadyTerminated[$memberId] ?? system_fix_tx_is_terminated($conn, $memberId, $date)) {
                $alreadyTerminated[$memberId] = true;
                system_fix_tx_record_termination(
                    $conn,
                    system_fix_tx_period_key($periodDate),
                    $date,
                    $memberId,
                    $memberNo,
                    $requested,
                    0.00,
                    0.00,
                    0.00,
                    'after_termination',
                    'adhoc'
                );
                continue;
            }

            $available = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            // A member with nothing left cannot transact at all.
            if ($available <= 0.0) {
                continue;
            }

            // A request that meets or exceeds the balance terminates the member.
            // The payout is the available balance, never the requested amount, so
            // the account cannot go negative.
            if ($requested >= $available) {
                $result = system_fix_tx_terminate_member(
                    $conn,
                    $runId,
                    $periodDate,
                    $date,
                    $memberId,
                    $memberNo,
                    $requested,
                    $available,
                    'adhoc',
                    (string) $row['comments']
                );

                $balances[$memberId] = 0.0;
                $terminations++;
                $terminationFeeTotal += $result['TerminationFee'];
                $posted++;
                continue;
            }

            // --- Normal adhoc payment (type 4) --------------------------------
            $afterPayment = round($available - $requested, 2);
            system_fix_tx_insert($conn, [
                'date' => $date,
                'type' => $type,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, $type),
                'credit' => '0',
                'starting' => $available,
                'amount' => $requested,
                'new_balance' => $afterPayment,
                'comments' => (string) $row['comments'],
            ]);

            // --- Transaction fee (type 5) = 2% --------------------------------
            $fee = round($requested * 0.02, 2);
            $afterFee = round($afterPayment - $fee, 2);
            $feeType = 5;
            system_fix_tx_insert($conn, [
                'date' => $date,
                'type' => $feeType,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, $feeType),
                'credit' => '0',
                'starting' => $afterPayment,
                'amount' => $fee,
                'new_balance' => $afterFee,
                'comments' => (string) $row['comments'],
            ]);

            // Carry the closing balance forward so the next adhoc for this
            // member (and the later stages) chains correctly.
            $balances[$memberId] = $afterFee;

            $posted++;
            $totalAmount += $requested;
            $totalFee += $fee;
        }

        return [
            'posted' => $posted,
            'skipped' => $skipped,
            'total_amount' => round($totalAmount, 2),
            'total_fee' => round($totalFee, 2),
            'terminations' => $terminations,
            'termination_fee' => round($terminationFeeTotal, 2),
            'capital_posted' => $capitalPosted,
            'capital_amount' => round($capitalAmount, 2),
            'capital_fee' => round($capitalFeeTotal, 2),
            'capital_duplicates' => $capitalDuplicates,
        ];
    }
}

if (!function_exists('system_fix_tx_post_capital_row')) {
    /**
     * Post one additional-capital row (type 9) plus its fee (type 5).
     *
     * Shared by the month run (where capital is interleaved with the adhocs in
     * date order) and by the standalone "capital only" action.
     *
     * Fee rule confirmed for the rebuild: 1% of the amount, WAIVED when the
     * amount is below 20,000 — the same waiver an opening balance (type 1) gets.
     *
     * Capital is money IN, so it can never terminate a member. A member already
     * terminated in the run is logged in the termination table (Reason
     * "after_termination") instead of receiving a new ledger row.
     *
     * @param array<int,float> $balances Running balances, updated in place.
     * @param array<int,bool>  $terminatedCache Cache of terminated members, updated in place.
     * @param array<string,array{baseline:int,seen:int}> $capitalMemo Per-row duplicate bookkeeping.
     * @return array{posted:bool, skipped:?string, duplicate:bool, amount:float, fee:float}
     */
    function system_fix_tx_post_capital_row(
        mysqli $conn,
        string $runId,
        string $periodDate,
        array $row,
        array &$balances,
        array &$terminatedCache,
        array &$capitalMemo
    ): array {
        $result = ['posted' => false, 'skipped' => null, 'duplicate' => false, 'amount' => 0.0, 'fee' => 0.0];

        $date = $row['date'] ?? null;
        if ($date === null) {
            return $result;
        }

        $member = system_fix_tx_resolve_member($conn, (string) $row['member_no']);
        if ($member === null) {
            $result['skipped'] = "Line {$row['line']}: member {$row['member_no']} was not found in tblmembers.";
            return $result;
        }

        $memberId = (int) $member['MemberID'];
        $memberNo = (string) $member['MemberNo'];
        $amount = (float) $row['amount'];
        if ($amount <= 0) {
            return $result;
        }

        // Members barred from the fix are never credited.
        if (!empty($member['Excluded'])) {
            $result['skipped'] = "Line {$row['line']}: member {$memberNo} is excluded from the fix and was skipped.";
            return $result;
        }

        // Idempotency: never credit the same row twice when the sheet is re-run.
        //
        // Matching on member/date/amount alone would swallow a member who
        // legitimately has two identical capital rows in one month, so the
        // sandbox count is compared with how many identical rows this run has
        // already handled: the first N post (N = what the sheet contains) and
        // anything beyond that is an already-staged row from an earlier run.
        $key = $memberId . '|' . $date . '|' . number_format($amount, 2, '.', '');
        if (!isset($capitalMemo[$key])) {
            $capitalMemo[$key] = [
                'baseline' => system_fix_tx_capital_row_count($conn, $memberId, $date, $amount),
                'seen' => 0,
            ];
        }
        $alreadyStaged = $capitalMemo[$key]['baseline'];
        $seenThisRun = $capitalMemo[$key]['seen'];
        $capitalMemo[$key]['seen'] = $seenThisRun + 1;

        if ($seenThisRun < $alreadyStaged) {
            $result['duplicate'] = true;
            return $result;
        }

        // --- Additional Capital (type 9), Credit = 1 -------------------------
        //
        // Capital is money the member deposits, so it is posted even if the
        // member has already been terminated: the funds really did arrive and
        // the ledger must show them. The deposit revives the balance, which in
        // turn lets the member take part in the remaining runs (capital is a
        // credit, never a debit, so it can never drive the account negative).
        $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
        $afterCapital = round($starting + $amount, 2);
        system_fix_tx_insert($conn, [
            'date' => $date,
            'type' => 9,
            'member_id' => $memberId,
            'details' => system_fix_tx_transaction_type_name($conn, 9),
            'credit' => '1',
            'starting' => $starting,
            'amount' => $amount,
            'new_balance' => $afterCapital,
            'comments' => (string) $row['comments'],
        ]);

        // --- Additional Capital Fee (type 5): a flat 1% ----------------------
        //
        // NO 20,000 waiver. Every capital row in production is charged 1%,
        // including the 27 injections below 20,000 (smallest E 595.00 was still
        // charged E 5.95), so the waiver that applies to an opening balance does
        // NOT apply here.
        $fee = round($amount * 0.01, 2);
        $afterFee = round($afterCapital - $fee, 2);
        system_fix_tx_insert($conn, [
            'date' => $date,
            'type' => 5,
            'member_id' => $memberId,
            'details' => 'Additional Capital Fee',
            'credit' => '0',
            'starting' => $afterCapital,
            'amount' => $fee,
            'new_balance' => $afterFee,
            'comments' => (string) $row['comments'],
        ]);

        $balances[$memberId] = $afterFee;

        return ['posted' => true, 'skipped' => null, 'duplicate' => false, 'amount' => $amount, 'fee' => $fee];
    }
}

if (!function_exists('system_fix_tx_stage_capital')) {
    /**
     * STAGE — Additional capital (type 9) on its own (the "Capital Only" action).
     *
     * Kept for manual/backfill use. The normal month run takes capital from the
     * `additional_capital` VIEW instead (see system_fix_tx_capital_rows), so no
     * spreadsheet is uploaded for it.
     *
     * Mirrors Transactions/capitalprocess.php / the production data:
     *   - Type 9 "Additional Capital": Credit = 1, posted on the SPREADSHEET date.
     *   - Type 5 "Additional Capital Fee": a flat 1%, with NO 20,000 waiver.
     *
     * @param array<int,array> $rows Output of system_fix_tx_parse_capital().
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float, duplicates:int}
     */
    function system_fix_tx_stage_capital(mysqli $conn, string $runId, array $rows, string $periodDate): array
    {
        $posted = 0;
        $skipped = [];
        $totalAmount = 0.0;
        $totalFee = 0.0;
        $duplicates = 0;
        $bounds = system_fix_tx_period_bounds($periodDate);
        $alreadyTerminated = [];
        $capitalMemo = [];

        $balances = system_fix_tx_running_balances($conn, $runId);

        foreach ($rows as $row) {
            $date = $row['date'] ?? null;

            // Only the month being rebuilt — the file spans all periods.
            if ($date === null || $date < $bounds['start'] || $date > $bounds['end']) {
                continue;
            }

            $result = system_fix_tx_post_capital_row($conn, $runId, $periodDate, $row, $balances, $alreadyTerminated, $capitalMemo);

            if ($result['skipped'] !== null) {
                $skipped[] = $result['skipped'];
                continue;
            }
            if ($result['duplicate']) {
                $duplicates++;
                continue;
            }
            if (!$result['posted']) {
                continue;
            }

            $posted++;
            $totalAmount += $result['amount'];
            $totalFee += $result['fee'];
        }

        return [
            'posted' => $posted,
            'skipped' => $skipped,
            'total_amount' => round($totalAmount, 2),
            'total_fee' => round($totalFee, 2),
            'duplicates' => $duplicates,
        ];
    }
}

if (!function_exists('system_fix_tx_capital_rows')) {
    /**
     * Additional capital rows for a period, read from the `additional_capital`
     * VIEW rather than from an uploaded spreadsheet.
     *
     * The view is the single source of truth: it already resolves MemberID and
     * MemberNo, so no mapping or upload step is needed. Rows are returned in the
     * same shape the capital stage expects.
     *
     * @return array<int,array{member_no:string, date:string, amount:float, line:int, comments:string}>
     */
    function system_fix_tx_capital_rows(mysqli $conn, string $periodDate): array
    {
        $bounds = system_fix_tx_period_bounds($periodDate);
        $rows = [];

        try {
            $stmt = $conn->prepare(
                "SELECT `MemberID`, `MemberNo`, `TransactionDate`, `Amount`
                 FROM `additional_capital`
                 WHERE `TransactionDate` BETWEEN ? AND ?" . system_fix_tx_excluded_sql($conn) . "
                 ORDER BY `TransactionDate` ASC, `MemberID` ASC"
            );
            $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
            $stmt->execute();
            $result = $stmt->get_result();
            $line = 0;
            while ($row = $result->fetch_assoc()) {
                $line++;
                $rows[] = [
                    'member_no' => (string) $row['MemberNo'],
                    'date' => (string) $row['TransactionDate'],
                    'amount' => (float) $row['Amount'],
                    'line' => $line,
                    'comments' => '',
                ];
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // The view may not exist yet; the run simply has no capital.
            error_log('System Fix: additional_capital view unavailable: ' . $e->getMessage());
        }

        return $rows;
    }
}

if (!function_exists('system_fix_tx_capital_row_count')) {
    /**
     * How many times this exact additional-capital row already exists in the
     * sandbox (member + date + amount).
     *
     * Used so re-running the same capital spreadsheet (which spans all periods)
     * never credits a member twice, while still allowing a member who really does
     * have repeated identical capital rows in one month.
     */
    function system_fix_tx_capital_row_count(mysqli $conn, int $memberId, string $date, float $amount): int
    {
        $table = system_fix_tx_table();
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS c FROM `{$table}`
             WHERE `memberID` = ? AND `TransactionTypeID` = 9
               AND `TransactionDate` = ? AND ROUND(`Amount`, 2) = ?"
        );
        $amount = round($amount, 2);
        $stmt->bind_param('isd', $memberId, $date, $amount);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return (int) ($row['c'] ?? 0);
    }
}

if (!function_exists('system_fix_tx_has_capital_row')) {
    /**
     * Is at least one copy of this exact additional-capital row already in the
     * sandbox? Kept for simple yes/no checks; the stage uses the count above so
     * repeated identical rows are not lost.
     */
    function system_fix_tx_has_capital_row(mysqli $conn, int $memberId, string $date, float $amount): bool
    {
        return system_fix_tx_capital_row_count($conn, $memberId, $date, $amount) > 0;
    }
}

if (!function_exists('system_fix_tx_terminate_member')) {
    /**
     * Terminate a member in the sandbox.
     *
     * Charged BEFORE the payout so the balance never goes negative:
     *   termination fee (type 12) = TerminationFeePercent (1%) of the available
     *   balance, funded from the balance; then the remainder is paid out
     *   (type 11 "Final Transaction") leaving a zero balance.
     *
     * The decision is RECORDED in `transactionsfix_terminations`; `tblmembers`
     * is never modified during the fix.
     *
     * @return array{TerminationFee:float, AmountGiven:float, Discrepancy:float}
     */
    function system_fix_tx_terminate_member(
        mysqli $conn,
        string $runId,
        string $periodDate,
        string $date,
        int $memberId,
        string $memberNo,
        float $requested,
        float $available,
        string $reason = 'adhoc',
        string $comments = ''
    ): array {
        $percent = system_fix_tx_termination_fee_percent();
        $terminationFee = round($available * ($percent / 100), 2);
        $payout = round($available - $terminationFee, 2);

        // --- Termination fee (type 12) ---------------------------------------
        system_fix_tx_insert($conn, [
            'date' => $date,
            'type' => 12,
            'member_id' => $memberId,
            'details' => 'Termination Fee',
            'credit' => '0',
            'starting' => $available,
            'amount' => $terminationFee,
            'new_balance' => $payout,
            'comments' => $comments,
        ]);

        // --- Final transaction / payout (type 11) ----------------------------
        system_fix_tx_insert($conn, [
            'date' => $date,
            'type' => 11,
            'member_id' => $memberId,
            'details' => 'Final Transaction',
            'credit' => '0',
            'starting' => $payout,
            'amount' => $payout,
            'new_balance' => 0.00,
            'comments' => $comments,
        ]);

        return system_fix_tx_record_termination(
            $conn,
            system_fix_tx_period_key($periodDate),
            $date,
            $memberId,
            $memberNo,
            $requested,
            $available,
            $payout,
            $terminationFee,
            $reason
        );
    }
}

if (!function_exists('system_fix_tx_transaction_type_name')) {
    /**
     * Transaction type label from tbltransactiontypes, used for `Details`.
     */
    function system_fix_tx_transaction_type_name(mysqli $conn, int $typeId): string
    {
        static $cache = [];

        if (array_key_exists($typeId, $cache)) {
            return $cache[$typeId];
        }

        $stmt = $conn->prepare("SELECT `TransactionType` FROM `tbltransactiontypes` WHERE `transactiontypeID` = ? LIMIT 1");
        $stmt->bind_param('i', $typeId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $cache[$typeId] = $row ? (string) $row['TransactionType'] : '';
    }
}

if (!function_exists('system_fix_tx_insert')) {
    /**
     * Low level insert helper for the sandbox table (single prepared statement).
     *
     * The sandbox mirrors `tblmemberaccounts` exactly: (TransactionDate,
     * TransactionTypeID, memberID, Details, Credit, StartingBalance, Amount,
     * NewBalance, Comments).
     *
     * Excluded members (system_fix_tx_excluded_member_nos) are never written,
     * whatever stage produced the row: this is the last line of defence so no
     * sandbox transaction can exist for them.
     */
    function system_fix_tx_insert(mysqli $conn, array $row): void
    {
        if (system_fix_tx_is_excluded_member_id($conn, (int) ($row['member_id'] ?? 0))) {
            return;
        }

        $table = system_fix_tx_table();
        $stmt = $conn->prepare(
            "INSERT INTO `{$table}`
                (`TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                 `StartingBalance`, `Amount`, `NewBalance`, `Comments`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $date = (string) ($row['date'] ?? '');
        $type = (int) ($row['type'] ?? 0);
        $memberId = (int) ($row['member_id'] ?? 0);
        $details = (string) ($row['details'] ?? '');
        $credit = (string) ($row['credit'] ?? '0');
        $starting = (float) ($row['starting'] ?? 0);
        $amount = (float) ($row['amount'] ?? 0);
        $newBalance = (float) ($row['new_balance'] ?? 0);
        $comments = (string) ($row['comments'] ?? '');

        $stmt->bind_param(
            'siissddds',
            $date,
            $type,
            $memberId,
            $details,
            $credit,
            $starting,
            $amount,
            $newBalance,
            $comments
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
     * Latest closing balance per member as at the END of the given period —
     * i.e. the running balance carried forward from every prior month in the
     * sandbox plus anything already staged for this month.
     *
     * Reading only the current month would break the chain: each new month would
     * fall back to production instead of continuing from the previous month's
     * sandbox balance.
     *
     * @return array<int,float> [memberID => closing balance]
     */
    function system_fix_tx_running_balances(mysqli $conn, string $runId): array
    {
        $table = system_fix_tx_table();
        $period = substr($runId, 0, 7);
        $bounds = system_fix_tx_period_bounds($period . '-01');
        $balances = [];

        $stmt = $conn->prepare(
            "SELECT t.`memberID`, t.`NewBalance`
             FROM `{$table}` t
             INNER JOIN (
                 SELECT `memberID`, MAX(`accountsID`) AS last_id
                 FROM `{$table}`
                 WHERE `TransactionDate` <= ?
                 GROUP BY `memberID`
             ) x ON x.last_id = t.`accountsID`"
        );
        $stmt->bind_param('s', $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $balances[(int) $row['memberID']] = (float) $row['NewBalance'];
        }
        $stmt->close();

        return $balances;
    }
}

if (!function_exists('system_fix_tx_debit_or_terminate')) {
    /**
     * Apply a debit, or terminate the member when they cannot afford it.
     *
     * Used by every debit stage (regular, fixed, admin) so no transaction can
     * ever push a balance negative. When the amount exceeds the available
     * balance the member is terminated: the 1% termination fee is charged from
     * what remains and the rest is paid out, exactly as the adhoc rule does.
     *
     * @return array{terminated:bool, new_balance:float}
     */
    function system_fix_tx_debit_or_terminate(
        mysqli $conn,
        string $runId,
        string $periodDate,
        string $date,
        int $memberId,
        string $memberNo,
        float $amount,
        float $available,
        string $reason
    ): array {
        if ($amount > $available) {
            system_fix_tx_terminate_member(
                $conn,
                $runId,
                $periodDate,
                $date,
                $memberId,
                $memberNo,
                $amount,
                $available,
                $reason,
                ''
            );

            return ['terminated' => true, 'new_balance' => 0.0];
        }

        return ['terminated' => false, 'new_balance' => round($available - $amount, 2)];
    }
}

if (!function_exists('system_fix_tx_stage_regular_from_rows')) {
    /**
     * Stage regular payments (type 3) from the regulars spreadsheet rows, plus
     * the transaction fee (type 5) at the member's TransactionPercent.
     *
     * Only rows falling inside the given period are posted, so the same parsed
     * spreadsheet can be walked month by month.
     *
     * @param array<int,array> $rows Output of system_fix_tx_parse_csv().
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float}
     */
    function system_fix_tx_stage_regular_from_rows(mysqli $conn, string $runId, array $rows, string $periodDate): array
    {
        $posted = 0;
        $skipped = [];
        $totalAmount = 0.0;
        $totalFee = 0.0;
        $terminations = 0;
        $bounds = system_fix_tx_period_bounds($periodDate);

        $balances = system_fix_tx_running_balances($conn, $runId);

        foreach ($rows as $row) {
            $date = $row['date'] ?? null;
            if ($date === null) {
                continue;
            }
            // Only the month being rebuilt.
            if ($date < $bounds['start'] || $date > $bounds['end']) {
                continue;
            }

            $member = system_fix_tx_resolve_member($conn, (string) $row['member_no']);
            if ($member === null) {
                $skipped[] = "Line {$row['line']}: member {$row['member_no']} was not found in tblmembers.";
                continue;
            }

            $memberId = (int) $member['MemberID'];
            $memberNo = (string) $member['MemberNo'];

            // Members barred from the fix are skipped outright.
            if (!empty($member['Excluded']) || system_fix_tx_is_excluded_member_id($conn, $memberId)) {
                $skipped[] = "Line {$row['line']}: member {$memberNo} is excluded from the fix and was skipped.";
                continue;
            }

            $amount = (float) $row['amount'];
            if ($amount <= 0) {
                continue;
            }

            // Already terminated on or before this date? Record only, never post.
            // The date is passed so a regular payment made BEFORE the (later)
            // termination is still processed.
            if (system_fix_tx_is_terminated($conn, $memberId, $date)) {
                system_fix_tx_record_termination(
                    $conn,
                    system_fix_tx_period_key($periodDate),
                    $date,
                    $memberId,
                    $memberNo,
                    $amount,
                    0.00,
                    0.00,
                    0.00,
                    'after_termination',
                    'regular'
                );
                $terminations++;
                continue;
            }

            $percent = system_fix_tx_member_transaction_percent($conn, $memberId);
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            // A member with nothing left in the fund no longer transacts.
            if ($starting <= 0.0) {
                continue;
            }

            // A debit that exceeds the balance terminates the member instead of
            // pushing the account negative.
            $debit = system_fix_tx_debit_or_terminate(
                $conn,
                $runId,
                $periodDate,
                $date,
                $memberId,
                $memberNo,
                $amount,
                $starting,
                'regular'
            );

            if ($debit['terminated']) {
                $balances[$memberId] = 0.0;
                $terminations++;
                continue;
            }

            $afterPayment = $debit['new_balance'];
            system_fix_tx_insert($conn, [
                'date' => $date,
                'type' => 3,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 3),
                'credit' => '0',
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => $afterPayment,
                'comments' => (string) ($row['comments'] ?? ''),
            ]);

            $fee = round($amount * ($percent / 100), 2);
            $afterFee = round($afterPayment - $fee, 2);
            system_fix_tx_insert($conn, [
                'date' => $date,
                'type' => 5,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 5),
                'credit' => '0',
                'starting' => $afterPayment,
                'amount' => $fee,
                'new_balance' => $afterFee,
                'comments' => (string) ($row['comments'] ?? ''),
            ]);

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
            'terminations' => $terminations,
        ];
    }
}

if (!function_exists('system_fix_tx_member_transaction_percent')) {
    /**
     * Transaction fee percentage for a member (member_fees.TransactionPercent).
     */
    function system_fix_tx_member_transaction_percent(mysqli $conn, int $memberId): float
    {
        $stmt = $conn->prepare("SELECT `TransactionPercent` FROM `member_fees` WHERE `MemberID` = ? LIMIT 1");
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $row && $row['TransactionPercent'] !== null ? (float) $row['TransactionPercent'] : 0.0;
    }
}

if (!function_exists('system_fix_tx_stage_regular')) {
    /**
     * STAGE 2 — Regular payments (type 3) plus their transaction fee (type 5),
     * for every member on the running roster that existed in the fund.
     *
     * The AMOUNT is not recalculated from member_fees: it is read from the
     * sandbox capture (`transactionsfix_regular`), which holds how much actually
     * ran as a regular payment for each member in that month in production
     * `tblmemberaccounts`. Capturing first guarantees identical amounts.
     *
     * @return array{posted:int, skipped:array<int,string>, total_amount:float, total_fee:float}
     */
    function system_fix_tx_stage_regular(mysqli $conn, string $runId, string $periodDate, ?int $unused = null): array
    {
        $posted = 0;
        $totalAmount = 0.0;
        $totalFee = 0.0;

        $period = system_fix_tx_period_key($periodDate);
        // Regular payments are debits, so members terminating this month are
        // paid out on their termination date and must not also be debited at
        // month end; members with a zero balance cannot pay.
        $ids = system_fix_tx_eligible_month_end_ids($conn, $runId, $periodDate);
        if (empty($ids)) {
            return ['posted' => 0, 'skipped' => [], 'total_amount' => 0.0, 'total_fee' => 0.0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($ids);
        $balances = system_fix_tx_running_balances($conn, $runId);

        // Pull the member's fee rate alongside the capture lookup.
        $sql = "SELECT `m`.`MemberID`, `m`.`TransactionPercent`
                FROM `member_fees` `m`
                WHERE 1 = 1" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $percent = (float) $row['TransactionPercent'];

            // Amount = what actually ran for this member that month.
            $amount = system_fix_tx_captured_amount($conn, $period, $memberId);
            if ($amount <= 0) {
                continue; // nothing ran for this member — no regular payment to mirror
            }

            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            $afterPayment = round($starting - $amount, 2);

            system_fix_tx_insert($conn, [
                'date' => $periodDate,
                'type' => 3,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 3),
                'credit' => '0',
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => $afterPayment,
                'comments' => '',
            ]);

            $fee = round($amount * ($percent / 100), 2);
            $afterFee = round($afterPayment - $fee, 2);
            system_fix_tx_insert($conn, [
                'date' => $periodDate,
                'type' => 5,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 5),
                'credit' => '0',
                'starting' => $afterPayment,
                'amount' => $fee,
                'new_balance' => $afterFee,
                'comments' => '',
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
     * STAGE — Admin fees (type 7) for every member on the roster.
     *
     *   fee = (1.5% * balance / 365) * days
     *
     * where `days` is 31 for Jan/Mar/May/Jul/Aug/Oct/Dec and 30 otherwise —
     * identical to Transactions/mprocess.php's day count.
     *
     * If the fee cannot be covered the member is terminated (1% fee + payout),
     * so the balance never goes negative.
     *
     * @return array{posted:int, total_amount:float, terminations:int}
     */
    function system_fix_tx_stage_admin_fees(mysqli $conn, string $runId, string $periodDate): array
    {
        $posted = 0;
        $total = 0.0;
        $terminations = 0;
        $month = (int) date('n', strtotime($periodDate));
        $days = system_fix_tx_days_in_month($month);

        // Members terminating THIS month are paid out on their termination date,
        // so they must not also receive an end-of-month admin fee. Members with
        // a zero or negative balance cannot be charged either.
        $ids = system_fix_tx_eligible_month_end_ids($conn, $runId, $periodDate);
        if (empty($ids)) {
            return ['posted' => 0, 'total_amount' => 0.0, 'terminations' => 0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($ids);
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `balance` FROM `member_fees` WHERE 1 = 1" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            $fee = round(((1.5 / 100) * $starting / 365) * $days, 2);

            $debit = system_fix_tx_debit_or_terminate(
                $conn,
                $runId,
                $periodDate,
                $periodDate,
                $memberId,
                $memberNo,
                $fee,
                $starting,
                'admin'
            );

            if ($debit['terminated']) {
                $balances[$memberId] = 0.0;
                $terminations++;
                continue;
            }

            system_fix_tx_insert($conn, [
                'date' => $periodDate,
                'type' => 7,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 7),
                'credit' => '0',
                'starting' => $starting,
                'amount' => $fee,
                'new_balance' => $debit['new_balance'],
                'comments' => '',
            ]);

            $balances[$memberId] = $debit['new_balance'];
            $posted++;
            $total += $fee;
        }
        $stmt->close();

        return ['posted' => $posted, 'total_amount' => round($total, 2), 'terminations' => $terminations];
    }
}

if (!function_exists('system_fix_tx_stage_fixed_fees')) {
    /**
     * STAGE — Fixed monthly fee (type 6): a flat 10.00 per member per month.
     *
     * Members belong to the fund via member -> deceased -> retirement fund,
     * surfaced by `member_fees.F_ID`. Members of the Coca-Cola fund
     * (RetirementFundID 27) are EXEMPT and receive no fixed monthly fee.
     *
     * If the fee cannot be covered the member is terminated, so the balance
     * never goes negative.
     *
     * @return array{posted:int, exempt:int, total_amount:float, terminations:int}
     */
    function system_fix_tx_stage_fixed_fees(mysqli $conn, string $runId, string $periodDate): array
    {
        $posted = 0;
        $exempt = 0;
        $total = 0.0;
        $terminations = 0;
        $cocaColaFundId = system_fix_tx_cocacola_fund_id();

        // Members terminating THIS month are paid out on their termination date,
        // so they must not also receive an end-of-month fixed fee. Members with a
        // zero or negative balance cannot be charged either.
        $ids = system_fix_tx_eligible_month_end_ids($conn, $runId, $periodDate);
        if (empty($ids)) {
            return ['posted' => 0, 'exempt' => 0, 'total_amount' => 0.0, 'terminations' => 0];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($ids);
        $balances = system_fix_tx_running_balances($conn, $runId);

        $sql = "SELECT `MemberID`, `F_ID` FROM `member_fees` WHERE 1 = 1" . $scopeSql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['MemberID'];
            $fundId = $row['F_ID'] === null ? 0 : (int) $row['F_ID'];

            // Coca-Cola fund members are exempt from the fixed monthly fee.
            if ($fundId === $cocaColaFundId) {
                $exempt++;
                continue;
            }

            $amount = 10.00; // flat monthly fee
            $memberNo = system_fix_tx_member_no($conn, $memberId);
            $starting = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);

            $debit = system_fix_tx_debit_or_terminate(
                $conn,
                $runId,
                $periodDate,
                $periodDate,
                $memberId,
                $memberNo,
                $amount,
                $starting,
                'fixed'
            );

            if ($debit['terminated']) {
                $balances[$memberId] = 0.0;
                $terminations++;
                continue;
            }

            system_fix_tx_insert($conn, [
                'date' => $periodDate,
                'type' => 6,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 6),
                'credit' => '0',
                'starting' => $starting,
                'amount' => $amount,
                'new_balance' => $debit['new_balance'],
                'comments' => '',
            ]);

            $balances[$memberId] = $debit['new_balance'];
            $posted++;
            $total += $amount;
        }
        $stmt->close();

        return ['posted' => $posted, 'exempt' => $exempt, 'total_amount' => round($total, 2), 'terminations' => $terminations];
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
        $period = substr($runId, 0, 7);
        $bounds = system_fix_tx_period_bounds($period . '-01');
        $ids = [];

        $stmt = $conn->prepare(
            "SELECT DISTINCT `memberID` FROM `{$table}`
             WHERE `TransactionDate` BETWEEN ? AND ? AND `memberID` > 0"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['memberID'];
        }
        $stmt->close();

        // Members barred from the fix never take part in a run.
        return system_fix_tx_filter_excluded($conn, $ids);
    }
}

if (!function_exists('system_fix_tx_period_key')) {
    /**
     * Period key (YYYY-MM) used by the running-member roster and the captured
     * regular payments, e.g. "2014-08".
     */
    function system_fix_tx_period_key(string $periodDate): string
    {
        return date('Y-m', strtotime($periodDate));
    }
}

if (!function_exists('system_fix_tx_roster_add')) {
    /**
     * Record that members exist in the fund for a period (idempotent).
     * Rows are never removed — the roster is the running list of members.
     *
     * @param array<int,int> $memberIds
     */
    function system_fix_tx_roster_add(mysqli $conn, string $period, array $memberIds, string $source = 'csv'): int
    {
        $table = system_fix_tx_running_members_table();
        $added = 0;

        $memberSql = "SELECT `m`.`MemberID`, `m`.`MemberNo`, `f`.`F_ID`
                      FROM `tblmembers` `m`
                      LEFT JOIN `member_fees` `f` ON `f`.`MemberID` = `m`.`MemberID`
                      WHERE `m`.`MemberID` = ? LIMIT 1";
        $memberStmt = $conn->prepare($memberSql);

        $insert = $conn->prepare(
            "INSERT IGNORE INTO `{$table}` (`Period`, `memberID`, `MemberNo`, `FundID`, `Source`)
             VALUES (?, ?, ?, ?, ?)"
        );

        foreach (array_unique(array_map('intval', $memberIds)) as $memberId) {
            if ($memberId <= 0) {
                continue;
            }

            // Members barred from the fix are not on the roster.
            if (system_fix_tx_is_excluded_member_id($conn, $memberId)) {
                continue;
            }

            $memberStmt->bind_param('i', $memberId);
            $memberStmt->execute();
            $member = $memberStmt->get_result()->fetch_assoc();
            if ($member === null) {
                continue;
            }

            $memberNo = (string) $member['MemberNo'];
            $fundId = $member['F_ID'] === null ? 0 : (int) $member['F_ID'];
            // Period(s), memberID(i), MemberNo(s), FundID(i), Source(s)
            $insert->bind_param('sisis', $period, $memberId, $memberNo, $fundId, $source);
            $insert->execute();
            $added += $insert->affected_rows;
        }

        $memberStmt->close();
        $insert->close();

        return $added;
    }
}

if (!function_exists('system_fix_tx_roster_ids')) {
    /**
     * Members on the running roster for a period.
     *
     * @return array<int,int>
     */
    function system_fix_tx_roster_ids(mysqli $conn, string $period): array
    {
        $table = system_fix_tx_running_members_table();
        $ids = [];

        $stmt = $conn->prepare("SELECT `memberID` FROM `{$table}` WHERE `Period` = ?");
        $stmt->bind_param('s', $period);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['memberID'];
        }
        $stmt->close();

        return system_fix_tx_filter_excluded($conn, $ids);
    }
}

if (!function_exists('system_fix_tx_capture_regular')) {
    /**
     * Capture how much was run as a regular payment (type 3) for each member in
     * the period, straight from production `tblmemberaccounts`. This is the
     * amount the rebuild then debits, so the fix mirrors what actually ran.
     *
     * Upserts one row per member per period. Writes only to the sandbox capture
     * table.
     *
     * @return array{rows:int, total:float}
     */
    function system_fix_tx_capture_regular(mysqli $conn, string $periodDate, string $source = 'tblmemberaccounts'): array
    {
        $period = system_fix_tx_period_key($periodDate);
        $bounds = system_fix_tx_period_bounds($periodDate);
        $table = system_fix_tx_regular_capture_table();

        $rows = 0;
        $total = 0.0;

        $stmt = $conn->prepare(
            "SELECT `memberID`, COUNT(*) AS tx_count, COALESCE(SUM(`Amount`), 0) AS amount
             FROM `tblmemberaccounts`
             WHERE `TransactionTypeID` = 3
               AND `TransactionDate` BETWEEN ? AND ?
             GROUP BY `memberID`"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();

        $upsert = $conn->prepare(
            "INSERT INTO `{$table}` (`Period`, `memberID`, `Amount`, `Transactions`, `Source`)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                 `Amount` = VALUES(`Amount`),
                 `Transactions` = VALUES(`Transactions`),
                 `Source` = VALUES(`Source`),
                 `CapturedAt` = CURRENT_TIMESTAMP"
        );

        while ($row = $result->fetch_assoc()) {
            $memberId = (int) $row['memberID'];
            $amount = (float) $row['amount'];
            $txCount = (int) $row['tx_count'];

            // Members barred from the fix have no regular payment captured.
            if (system_fix_tx_is_excluded_member_id($conn, $memberId)) {
                continue;
            }

            $upsert->bind_param('sidis', $period, $memberId, $amount, $txCount, $source);
            $upsert->execute();

            $rows++;
            $total += $amount;
        }

        $stmt->close();
        $upsert->close();

        return ['rows' => $rows, 'total' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_captured_amount')) {
    /**
     * Captured regular-payment amount for a member in a period (0 when none).
     */
    function system_fix_tx_captured_amount(mysqli $conn, string $period, int $memberId): float
    {
        $table = system_fix_tx_regular_capture_table();
        $stmt = $conn->prepare("SELECT `Amount` FROM `{$table}` WHERE `Period` = ? AND `memberID` = ? LIMIT 1");
        $stmt->bind_param('si', $period, $memberId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $row ? (float) $row['Amount'] : 0.0;
    }
}

if (!function_exists('system_fix_tx_populate_interest_pool')) {
    /**
     * Build the `interestfix` table: one row per month/year holding the TOTAL
     * interest allocated (type 8) across all members in `tblmemberaccounts`.
     *
     * The pool is read from production ONCE per month and stored, so the rebuild
     * never has to re-scan `tblmemberaccounts` for each run.
     *
     * Pass a start month to limit how far back to build, otherwise everything is
     * rebuilt. Upserts by period, so it is safe to run repeatedly.
     *
     * @param string|null $fromMonth First month to include (Y-m). Null = all history.
     * @return array{months:int, total:float}
     */
    function system_fix_tx_populate_interest_pool(mysqli $conn, ?string $fromMonth = null): array
    {
        $table = system_fix_tx_interest_pool_table();

        $sql = "SELECT DATE_FORMAT(`TransactionDate`, '%Y-%m') AS period,
                       YEAR(`TransactionDate`) AS yr,
                       MONTH(`TransactionDate`) AS mo,
                       COUNT(*) AS source_rows,
                       COALESCE(SUM(`Amount`), 0) AS pool
                FROM `tblmemberaccounts`
                WHERE `TransactionTypeID` = 8
                  AND `TransactionDate` IS NOT NULL" . system_fix_tx_excluded_sql($conn);
        if ($fromMonth !== null && $fromMonth !== '') {
            $sql .= " AND `TransactionDate` >= ?";
        }
        $sql .= " GROUP BY period, yr, mo ORDER BY period ASC";

        $stmt = $conn->prepare($sql);
        if ($fromMonth !== null && $fromMonth !== '') {
            $fromDate = $fromMonth . '-01';
            $stmt->bind_param('s', $fromDate);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $upsert = $conn->prepare(
            "INSERT INTO `{$table}` (`Period`, `Year`, `Month`, `Amount`, `SourceRows`)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                 `Year` = VALUES(`Year`),
                 `Month` = VALUES(`Month`),
                 `Amount` = VALUES(`Amount`),
                 `SourceRows` = VALUES(`SourceRows`)"
        );

        $months = 0;
        $total = 0.0;

        while ($row = $result->fetch_assoc()) {
            $period = (string) $row['period'];
            $year = (int) $row['yr'];
            $month = (int) $row['mo'];
            $pool = (float) $row['pool'];
            $sourceRows = (int) $row['source_rows'];

            $upsert->bind_param('siidi', $period, $year, $month, $pool, $sourceRows);
            $upsert->execute();

            $months++;
            $total += $pool;
        }

        $stmt->close();
        $upsert->close();

        return ['months' => $months, 'total' => round($total, 2)];
    }
}

if (!function_exists('system_fix_tx_interest_pool')) {
    /**
     * Stored interest pool for a period (0 when the month is not yet built).
     */
    function system_fix_tx_interest_pool(mysqli $conn, string $periodDate): float
    {
        $table = system_fix_tx_interest_pool_table();
        $period = system_fix_tx_period_key($periodDate);

        $stmt = $conn->prepare("SELECT `Amount` FROM `{$table}` WHERE `Period` = ? LIMIT 1");
        $stmt->bind_param('s', $period);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $row ? (float) $row['Amount'] : 0.0;
    }
}

if (!function_exists('system_fix_tx_eligible_month_end_ids')) {
    /**
     * Members the month-end stages (interest, admin fees, fixed fees) may touch.
     *
     * A member is eligible only when ALL of these hold:
     *   1. They existed in the fund that month (schedule / roster / run scope).
     *   2. They were not terminated on or before the end of the month.
     *   3. They are not scheduled to be paid out DURING the month — an
     *      end-of-month fee or interest credit after the payout would leave the
     *      account non-zero, or negative once the fee lands.
     *   4. Their balance is greater than zero — an empty account cannot be
     *      charged, and interest on zero is meaningless.
     *
     * @return array<int,int>
     */
    function system_fix_tx_eligible_month_end_ids(mysqli $conn, string $runId, string $periodDate): array
    {
        $bounds = system_fix_tx_period_bounds($periodDate);

        $ids = system_fix_tx_month_end_ids($conn, $runId, $periodDate);
        if (empty($ids)) {
            return [];
        }

        // (2) already-paid-out members, as at the end of this month.
        $ids = system_fix_tx_filter_active($conn, $ids, $bounds['end']);

        // (3) members whose termination lands inside this month.
        $scheduled = system_fix_tx_scheduled_termination_ids($conn, $periodDate);
        if (!empty($scheduled)) {
            $ids = array_values(array_diff($ids, $scheduled));
        }

        // (4) members with nothing left in the fund.
        $balances = system_fix_tx_running_balances($conn, $runId);
        foreach ($ids as $memberId) {
            if (!array_key_exists($memberId, $balances)) {
                $balances[$memberId] = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            }
        }

        return system_fix_tx_filter_dormant($ids, $balances);
    }
}

if (!function_exists('system_fix_tx_month_end_ids')) {
    /**
     * Member IDs the month-end stages (regular, admin, fixed, interest) run for.
     *
     * The running roster for the period is authoritative when populated: it lists
     * every member that existed in the fund at that time. Until the roster is
     * built (e.g. right after a CSV upload), fall back to the run scope so
     * behaviour stays consistent.
     *
     * @return array<int,int>
     */
    function system_fix_tx_month_end_ids(mysqli $conn, string $runId, string $periodDate): array
    {
        // The member schedule is authoritative when built: it lists every member
        // who existed in the fund at that time — including those with no adhocs or
        // regular payments.
        $scheduled = system_fix_tx_schedule_member_ids($conn, $periodDate);
        if (!empty($scheduled)) {
            return $scheduled;
        }

        // Fall back to the running roster, then to whatever is already in scope.
        $roster = system_fix_tx_roster_ids($conn, system_fix_tx_period_key($periodDate));
        if (!empty($roster)) {
            return $roster;
        }

        return system_fix_tx_scope_ids($conn, $runId);
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

if (!function_exists('system_fix_tx_months_between')) {
    /**
     * List of YYYY-MM-01 periods between two dates (inclusive), used to walk the
     * rebuild month by month.
     *
     * @return array<int,string>
     */
    function system_fix_tx_months_between(string $from, string $to): array
    {
        $months = [];
        $cursor = strtotime(date('Y-m-01', strtotime($from)));
        $end = strtotime(date('Y-m-01', strtotime($to)));

        while ($cursor <= $end) {
            $months[] = date('Y-m-01', $cursor);
            $cursor = strtotime('+1 month', $cursor);
        }

        return $months;
    }
}

if (!function_exists('system_fix_tx_run_window')) {
    /**
     * The month range a rebuild should cover.
     *
     * The window comes from the ADHOCS and REGULARS rows only. The additional
     * capital spreadsheet covers all members for ALL periods, so including its
     * dates would silently stretch the run far past the period being repaired
     * (e.g. adhocs for 2014-05..2016-06 would drag the run to 2026-09).
     *
     * Capital rows dated outside the window are therefore ignored by the run —
     * they are not yet part of the period being rebuilt.
     *
     * @param array<int,array> $adhocRows
     * @param array<int,array> $regularRows
     * @param string           $fromOverride Optional YYYY-MM floor (start month).
     * @return array{from:string, to:string, months:array<int,string>, first_date:?string, last_date:?string}
     */
    function system_fix_tx_run_window(array $adhocRows, array $regularRows, string $fromOverride = ''): array
    {
        $dates = [];
        foreach ($adhocRows as $r) {
            if (!empty($r['date'])) {
                $dates[] = (string) $r['date'];
            }
        }
        foreach ($regularRows as $r) {
            if (!empty($r['date'])) {
                $dates[] = (string) $r['date'];
            }
        }

        if (empty($dates)) {
            return ['from' => '', 'to' => '', 'months' => [], 'first_date' => null, 'last_date' => null];
        }

        sort($dates);
        $first = $dates[0];
        $last = end($dates);

        $fromMonth = substr($first, 0, 7) . '-01';
        $toMonth = substr($last, 0, 7) . '-01';

        // Optional floor so an already-seeded month is not processed twice.
        if (preg_match('#^\d{4}-\d{2}$#', trim($fromOverride)) && trim($fromOverride) > substr($fromMonth, 0, 7)) {
            $fromMonth = trim($fromOverride) . '-01';
        }

        return [
            'from' => $fromMonth,
            'to' => $toMonth,
            'months' => system_fix_tx_months_between($fromMonth, $toMonth),
            'first_date' => $first,
            'last_date' => $last,
        ];
    }
}

if (!function_exists('system_fix_tx_run_month')) {
    /**
     * Run one month end to end for the period supplied, in the order that keeps
     * the money flowing correctly:
     *
     *   1. Capital + adhocs + terminations (all in DATE order)
     *   2. Regular payments
     *   3. Interest            (credited on the post-regular balance)
     *   4. Fixed monthly fee   (except Coca-Cola fund members)
     *   5. Admin fees          (charged last)
     *
     * CAPITAL AND TERMINATIONS COME FROM THE VIEWS
     *   Additional capital is read from the `additional_capital` view and
     *   terminations from the `terminations` view, so only the adhocs and
     *   regulars spreadsheets need to be uploaded. Seeding history once is
     *   enough; after that the run is entirely view- and file-driven.
     *
     * NOTHING EVER GOES NEGATIVE
     *   Every debit is capped at the balance available at that moment. A member
     *   terminating this month is paid out on their termination date and is then
     *   excluded from the month-end stages, so no fee or interest can land on an
     *   account that has already been emptied.
     *
     * @param array<int,array> $adhocRows
     * @param array<int,array> $regularRows
     * @param array<int,array> $capitalRows Optional override; null = read the view.
     * @return array<string,mixed>
     */
    function system_fix_tx_run_month(
        mysqli $conn,
        string $periodDate,
        array $adhocRows,
        array $regularRows,
        ?array $capitalRows = null,
        bool $updateRoster = true
    ): array {
        $runId = system_fix_tx_period_key($periodDate);

        $result = ['period' => $runId];

        // Capital for this month comes from the additional_capital view unless the
        // caller supplied rows explicitly (used by the "Capital Only" action).
        if ($capitalRows === null) {
            $capitalRows = system_fix_tx_capital_rows($conn, $periodDate);
        }

        // 0. Make sure EVERY member who existed in the fund at this time is
        //    present in the sandbox, even those with no adhocs or regulars. Their
        //    history is pulled from production once, before the month is rebuilt.
        $result['sync'] = system_fix_tx_backfill_from_production($conn, $periodDate);

        // 1. Capital, adhocs and terminations, posted in DATE ORDER so a member's
        //    running balance always reflects what existed on the date of each
        //    transaction.
        $result['adhoc'] = system_fix_tx_stage_adhocs($conn, $runId, $adhocRows, $periodDate, $capitalRows);
        // Capital figures are reported separately for clarity.
        $result['capital'] = [
            'posted' => $result['adhoc']['capital_posted'] ?? 0,
            'total_amount' => $result['adhoc']['capital_amount'] ?? 0.0,
            'total_fee' => $result['adhoc']['capital_fee'] ?? 0.0,
            'duplicates' => $result['adhoc']['capital_duplicates'] ?? 0,
        ];

        // 2. Regular payments.
        $result['regular'] = system_fix_tx_stage_regular_from_rows($conn, $runId, $regularRows, $periodDate);

        // 3. Interest — credited before any fees are deducted.
        //
        //    The pool lives in `interestfix` and is derived state, so it can be
        //    empty (a full clear wipes it, and a run may extend past the months
        //    already built). Rebuild this month's pool from production when it is
        //    missing, otherwise the month posts no interest at all.
        if (system_fix_tx_interest_pool($conn, $periodDate) <= 0.0) {
            system_fix_tx_populate_interest_pool($conn, $runId);
        }
        $result['interest'] = system_fix_tx_stage_interest($conn, $runId, $periodDate);

        // 4. Fixed monthly fee (Coca-Cola fund members are exempt).
        $result['fixed'] = system_fix_tx_stage_fixed_fees($conn, $runId, $periodDate);

        // 5. Admin fees — charged on the closing balance.
        $result['admin'] = system_fix_tx_stage_admin_fees($conn, $runId, $periodDate);

        if ($updateRoster) {
            $result['roster'] = system_fix_tx_roster_add(
                $conn,
                $runId,
                system_fix_tx_scope_ids($conn, $runId),
                'monthrun'
            );
        }

        return $result;
    }
}

if (!function_exists('system_fix_tx_run_month_auto')) {
    /**
     * Run a month with the schedule/backfill handled for you, so every member who
     * existed in the fund that month is included even without adhocs.
     */
    function system_fix_tx_run_month_auto(
        mysqli $conn,
        string $periodDate,
        array $adhocRows,
        array $regularRows,
        ?array $capitalRows = null
    ): array {
        // Build the schedule on first use so join dates are known.
        $schedule = $conn->query("SELECT COUNT(*) AS c FROM `" . system_fix_tx_schedule_table() . "`")->fetch_assoc();
        if ((int) ($schedule['c'] ?? 0) === 0) {
            system_fix_tx_build_schedule($conn);
        }

        return system_fix_tx_run_month($conn, $periodDate, $adhocRows, $regularRows, $capitalRows, true);
    }
}

if (!function_exists('system_fix_tx_build_schedule')) {
    /**
     * Build the member schedule: one row per member recording WHEN they joined
     * the fund, so every member can be included in the month runs — even those
     * with no adhocs or regular payments.
     *
     * "Date joined" is the earliest date we can prove they existed:
     *   1. `tblmembers.DateAccountOpened`
     *   2. the earliest `tblmemberaccounts.TransactionDate` (opening balance etc.)
     *
     * Members are keyed by `memberID`, which is sequential (1, 2, 3, ...).
     *
     * Idempotent: rebuilds the table each time it is run.
     *
     * @return array{members:int}
     */
    function system_fix_tx_build_schedule(mysqli $conn): array
    {
        $schedule = system_fix_tx_schedule_table();

        $conn->query("DELETE FROM `{$schedule}`");

        $conn->query(
            "INSERT INTO `{$schedule}` (`memberID`, `MemberNo`, `DateJoined`, `Source`)
             SELECT
                 m.`MemberID`,
                 m.`MemberNo`,
                 COALESCE(m.`DateAccountOpened`, a.`first_tx`),
                 CASE
                     WHEN m.`DateAccountOpened` IS NOT NULL THEN 'account'
                     WHEN a.`first_tx` IS NOT NULL THEN 'ledger'
                     ELSE 'unknown'
                 END
             FROM `tblmembers` m
             LEFT JOIN (
                 SELECT `memberID`, MIN(`TransactionDate`) AS first_tx
                 FROM `tblmemberaccounts`
                 WHERE `memberID` > 0
                 GROUP BY `memberID`
             ) a ON a.`memberID` = m.`MemberID`
             WHERE 1 = 1" . system_fix_tx_excluded_sql($conn, 'm.MemberID')
        );

        $count = $conn->query("SELECT COUNT(*) AS c FROM `{$schedule}`")->fetch_assoc();

        return ['members' => (int) ($count['c'] ?? 0)];
    }
}

if (!function_exists('system_fix_tx_schedule_member_ids')) {
    /**
     * Members whose join date is on or before the given period end — i.e. the
     * members who existed in the fund at that time.
     *
     * @return array<int,int>
     */
    function system_fix_tx_schedule_member_ids(mysqli $conn, string $periodDate): array
    {
        $schedule = system_fix_tx_schedule_table();
        $bounds = system_fix_tx_period_bounds($periodDate);
        $ids = [];

        $stmt = $conn->prepare(
            "SELECT `memberID` FROM `{$schedule}`
             WHERE `DateJoined` IS NOT NULL AND `DateJoined` <= ?
             ORDER BY `memberID` ASC"
        );
        $stmt->bind_param('s', $bounds['end']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['memberID'];
        }
        $stmt->close();

        return system_fix_tx_filter_excluded($conn, $ids);
    }
}

if (!function_exists('system_fix_tx_backfill_from_production')) {
    /**
     * Give every scheduled member their COMPLETE history up to the run date.
     *
     * Members with no sandbox history at all are not covered by the spreadsheets,
     * so production is their source of truth: their whole ledger up to the end of
     * the period is copied in. That includes new joiners, whose opening balance and
     * transfer-in fee (and any reversal of it) live in production — and whose
     * `tblmembers.ApprovedBenefit` can be 0 or out of date, so it must not be
     * synthesised from that column.
     *
     * Members already carrying sandbox history are left untouched, so a month that
     * the fix has already rebuilt is never overwritten or duplicated.
     *
     * @param array<int,int>|null $memberIds Defaults to the whole schedule.
     * @return array{members:int, rows:int, openings:int}
     */
    function system_fix_tx_backfill_from_production(mysqli $conn, string $periodDate, ?array $memberIds = null): array
    {
        $table = system_fix_tx_table();
        $bounds = system_fix_tx_period_bounds($periodDate);

        if ($memberIds === null) {
            $memberIds = system_fix_tx_schedule_member_ids($conn, $periodDate);
        }
        if (empty($memberIds)) {
            return ['members' => 0, 'rows' => 0, 'openings' => 0];
        }

        // Members barred from the fix are never backfilled.
        $memberIds = system_fix_tx_filter_excluded($conn, $memberIds);
        if (empty($memberIds)) {
            return ['members' => 0, 'rows' => 0, 'openings' => 0];
        }

        // Members who already have sandbox history own their own months.
        $existing = system_fix_tx_running_balances($conn, $periodDate);

        $missing = [];
        foreach ($memberIds as $id) {
            $id = (int) $id;
            if ($id > 0 && !array_key_exists($id, $existing)) {
                $missing[] = $id;
            }
        }

        $rows = 0;
        $openings = 0;

        if (!empty($missing)) {
            [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($missing);
            $types = 's' . $scopeTypes;
            $end = $bounds['end'];

            // 1. Everything before the period: history (and the opening balance).
            $stmt = $conn->prepare(
                "INSERT INTO `{$table}`
                    (`TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                     `StartingBalance`, `Amount`, `NewBalance`, `Comments`)
                 SELECT `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                        `StartingBalance`, `Amount`, `NewBalance`, `Comments`
                 FROM `tblmemberaccounts`
                 WHERE `TransactionDate` < ?" . $scopeSql
            );
            $stmt->bind_param($types, ...array_merge([$bounds['start']], $scopeParams));
            $stmt->execute();
            $rows += $stmt->affected_rows;
            $stmt->close();

            // 2. Structural events inside the period for members production has
            //    never recorded before — brand-new joiners whose opening balance,
            //    transfer-in fee and any reversal exist only in production.
            //
            //    Only structural types are copied (opening 1, transfer-in 2,
            //    other/reversal 10, termination 11/12). The operational entries
            //    (regular 3, adhoc 4, fees 5, fixed 6, admin 7, interest 8) are
            //    generated by the month-end stages, so copying them here would
            //    duplicate them. Additional capital (9) is likewise NOT copied —
            //    it is posted from the capital spreadsheet by
            //    system_fix_tx_stage_capital(), so copying it would double-credit
            //    the member (and its fee would be charged twice).
            $newJoiners = system_fix_tx_members_without_history_before($conn, $missing, $bounds['start']);
            if (!empty($newJoiners)) {
                [$nScopeSql, $nScopeTypes, $nScopeParams] = system_fix_tx_in_scope($newJoiners);
                $stmt = $conn->prepare(
                    "INSERT INTO `{$table}`
                        (`TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                         `StartingBalance`, `Amount`, `NewBalance`, `Comments`)
                     SELECT `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`, `Credit`,
                            `StartingBalance`, `Amount`, `NewBalance`, `Comments`
                     FROM `tblmemberaccounts`
                     WHERE `TransactionDate` BETWEEN ? AND ?
                       AND `TransactionTypeID` IN (1, 2, 10, 11, 12)" . $nScopeSql
                );
                $stmt->bind_param('ss' . $nScopeTypes, ...array_merge([$bounds['start'], $end], $nScopeParams));
                $stmt->execute();
                $rows += $stmt->affected_rows;
                $stmt->close();
            }
        }

        // Safety net: anyone still without an opening balance in the sandbox gets a
        // synthetic one (used only when production has no record at all).
        $openings = system_fix_tx_ensure_openings($conn, $periodDate, $missing);

        return [
            'members' => count($missing),
            'rows' => $rows,
            'openings' => $openings,
        ];
    }
}

if (!function_exists('system_fix_tx_members_without_history_before')) {
    /**
     * Of the given member IDs, those with NO production transaction before the
     * supplied date (i.e. members who joined at or after that date).
     *
     * @param array<int,int> $memberIds
     * @return array<int,int>
     */
    function system_fix_tx_members_without_history_before(mysqli $conn, array $memberIds, string $beforeDate): array
    {
        if (empty($memberIds)) {
            return [];
        }

        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($memberIds);

        $stmt = $conn->prepare(
            "SELECT `memberID` FROM `tblmemberaccounts`
             WHERE `TransactionDate` < ?" . $scopeSql . "
             GROUP BY `memberID`"
        );
        $stmt->bind_param('s' . $scopeTypes, ...array_merge([$beforeDate], $scopeParams));
        $stmt->execute();
        $result = $stmt->get_result();

        $hasHistory = [];
        while ($row = $result->fetch_assoc()) {
            $hasHistory[(int) $row['memberID']] = true;
        }
        $stmt->close();

        $newJoiners = [];
        foreach ($memberIds as $id) {
            $id = (int) $id;
            if (!isset($hasHistory[$id])) {
                $newJoiners[] = $id;
            }
        }

        return $newJoiners;
    }
}

if (!function_exists('system_fix_tx_ensure_openings')) {
    /**
     * Create a type-1 opening balance for any of the supplied members who still
     * have no opening entry in the sandbox. Uses `tblmembers.ApprovedBenefit` when
     * it is positive; otherwise it derives the opening from their production
     * ledger so the balance is never wrongly left at zero.
     *
     * @param array<int,int> $memberIds
     * @return int Number of openings created.
     */
    function system_fix_tx_ensure_openings(mysqli $conn, string $periodDate, array $memberIds): int
    {
        if (empty($memberIds)) {
            return 0;
        }

        // Members barred from the fix never receive an opening balance.
        $memberIds = system_fix_tx_filter_excluded($conn, $memberIds);
        if (empty($memberIds)) {
            return 0;
        }

        $table = system_fix_tx_table();
        [$scopeSql, $scopeTypes, $scopeParams] = system_fix_tx_in_scope($memberIds);
        $created = 0;

        $stmt = $conn->
            prepare(
                "SELECT m.`MemberID`, m.`ApprovedBenefit`, m.`DateAccountOpened`,
                        (SELECT COUNT(*) FROM `{$table}` t
                          WHERE t.`memberID` = m.`MemberID` AND t.`TransactionTypeID` = 1) AS has_opening
                 FROM `tblmembers` m
                 WHERE 1 = 1" . $scopeSql
            );
        $stmt->bind_param($scopeTypes, ...$scopeParams);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            if ((int) $row['has_opening'] > 0) {
                continue;
            }
            $rows[] = $row;
        }
        $stmt->close();

        foreach ($rows as $row) {
            $memberId = (int) $row['MemberID'];
            $approved = (float) $row['ApprovedBenefit'];

            // Fall back to production when ApprovedBenefit is zero/unset.
            if ($approved <= 0) {
                $approved = system_fix_tx_production_opening($conn, $memberId);
            }
            if ($approved <= 0) {
                continue; // nothing to open with
            }

            $openingDate = system_fix_tx_normalise_date((string) $row['DateAccountOpened']) ?: $periodDate;

            system_fix_tx_insert($conn, [
                'date' => $openingDate,
                'type' => 1,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 1),
                'credit' => '1',
                'starting' => 0.00,
                'amount' => round($approved, 2),
                'new_balance' => round($approved, 2),
                'comments' => '',
            ]);

            $fee = $approved < 20000 ? 0.00 : round($approved * 0.01, 2);
            system_fix_tx_insert($conn, [
                'date' => $openingDate,
                'type' => 2,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 2),
                'credit' => '0',
                'starting' => round($approved, 2),
                'amount' => $fee,
                'new_balance' => round($approved - $fee, 2),
                'comments' => $fee == 0.00 ? 'No charge (below 20,000)' : '',
            ]);

            $created++;
        }

        return $created;
    }
}

if (!function_exists('system_fix_tx_production_opening')) {
    /**
     * The opening amount production recorded for a member (their type-1 entry).
     * Used when `tblmembers.ApprovedBenefit` is zero or stale.
     */
    function system_fix_tx_production_opening(mysqli $conn, int $memberId): float
    {
        $stmt = $conn->prepare(
            "SELECT `NewBalance` FROM `tblmemberaccounts`
             WHERE `memberID` = ? AND `TransactionTypeID` = 1
             ORDER BY `accountsID` ASC LIMIT 1"
        );
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        return $row ? (float) $row['NewBalance'] : 0.0;
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
                'date' => $openingDate,
                'type' => 1,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 1),
                'credit' => '1',
                'starting' => 0.00,
                'amount' => round($approved, 2),
                'new_balance' => round($approved, 2),
                'comments' => '',
            ]);

            // --- Transfer In Fee (type 2): 1%, waived below 20,000 ------------
            $fee = $approved < 20000 ? 0.00 : round($approved * 0.01, 2);
            system_fix_tx_insert($conn, [
                'date' => $openingDate,
                'type' => 2,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 2),
                'credit' => '0',
                'starting' => round($approved, 2),
                'amount' => $fee,
                'new_balance' => round($approved - $fee, 2),
                'comments' => $fee == 0.00 ? 'No charge (below 20,000)' : '',
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
        $period = substr($runId, 0, 7);
        $bounds = system_fix_tx_period_bounds($period . '-01');

        $stmt = $conn->prepare(
            "SELECT 1 FROM `{$table}`
             WHERE `TransactionDate` BETWEEN ? AND ? AND `memberID` = ? LIMIT 1"
        );
        $stmt->bind_param('ssi', $bounds['start'], $bounds['end'], $memberId);
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
        // Pool comes from the pre-built `interestfix` table (one row per month).
        $pool = system_fix_tx_interest_pool($conn, $periodDate);
        $sourceRows = 0;

        $posted = 0;
        $total = 0.0;

        if ($pool <= 0) {
            return ['posted' => 0, 'pool' => 0.0, 'source_rows' => $sourceRows, 'total_amount' => 0.0];
        }

        // Denominator and beneficiaries are limited to members who are still in
        // the fund, still have a balance, and are NOT being paid out this month.
        $balances = system_fix_tx_balance_denominator($conn, $runId, $periodDate);
        $terminated = system_fix_tx_terminated_ids($conn, system_fix_tx_period_bounds($periodDate)['end']);
        foreach ($terminated as $memberId) {
            unset($balances[$memberId]);
        }
        foreach (system_fix_tx_scheduled_termination_ids($conn, $periodDate) as $memberId) {
            unset($balances[$memberId]);
        }
        // Nothing left in the account means nothing to allocate interest on.
        foreach (array_keys($balances) as $memberId) {
            if ((float) $balances[$memberId] <= 0.0) {
                unset($balances[$memberId]);
            }
        }
        $totalFunds = array_sum($balances);

        if ($totalFunds <= 0) {
            return ['posted' => 0, 'pool' => $pool, 'source_rows' => $sourceRows, 'total_amount' => 0.0];
        }

        foreach ($balances as $memberId => $starting) {
            $interest = round(($starting / $totalFunds) * $pool, 2);

            system_fix_tx_insert($conn, [
                'date' => $periodDate,
                'type' => 8,
                'member_id' => $memberId,
                'details' => system_fix_tx_transaction_type_name($conn, 8),
                'credit' => '1',
                'starting' => $starting,
                'amount' => $interest,
                'new_balance' => round($starting + $interest, 2),
                'comments' => '',
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
     * Scoped to the members who existed in the fund for the period (the running
     * roster, falling back to the run scope). Walks the run from newest row
     * backwards so each member only contributes their most recent closing
     * balance; roster members not yet touched by the run fall back to their
     * PREVIOUS-MONTH closing balance in tblmemberaccounts.
     *
     * @return array<int,float> [memberID => balance]
     */
    function system_fix_tx_balance_denominator(mysqli $conn, string $runId, string $periodDate): array
    {
        // Latest sandbox balance per member as at the end of this period — this
        // already carries forward every earlier month, so the interest split is
        // based on the true running balances.
        $balances = system_fix_tx_running_balances($conn, $runId);

        // Members who can still earn interest: not terminated, not being paid out
        // this month, and holding a positive balance.
        $active = system_fix_tx_eligible_month_end_ids($conn, $runId, $periodDate);
        $eligible = [];
        foreach ($active as $memberId) {
            if (!array_key_exists($memberId, $balances)) {
                $balances[$memberId] = system_fix_tx_base_balance($conn, $runId, $memberId, $periodDate, $balances);
            }
            if (($balances[$memberId] ?? 0.0) > 0.0) {
                $eligible[$memberId] = true;
            }
        }

        // Keep only members actually eligible to receive interest this month.
        foreach (array_keys($balances) as $memberId) {
            if (!isset($eligible[$memberId])) {
                unset($balances[$memberId]);
            }
        }

        return $balances;
    }
}

if (!function_exists('system_fix_tx_recon')) {
    /**
     * Reconciliation between production (`tblmemberaccounts`) and the rebuild
     * (`transactionsfix`), both read AS AT the same cutoff date.
     *
     * Returned columns match reconciliation.sql exactly, so the page, the Excel
     * export and the standalone script always agree:
     *
     *   MemberNo, OldOpeningBalance, FixOpeningBalance, OpeningDifference,
     *   OldClosingBalance, FixClosingBalance, ClosingDifference,
     *   OldInterestCount, FixInterestCount, InterestCountDifference,
     *   OldInterestTotal, FixInterestTotal, InterestDifference
     *
     * The cutoff is essential: production may run to today while the sandbox
     * stops at the last rebuilt month, so without it every member looks wrong.
     *
     * @param string|null $cutoffEnd 'YYYY-MM-DD', or null/'' to compare all dates.
     * @return array<int,array<string,mixed>> Rows, with a "GRAND TOTAL" row last.
     */
    function system_fix_tx_recon(mysqli $conn, ?string $cutoffEnd): array
    {
        $cutoff = ($cutoffEnd === null || trim($cutoffEnd) === '') ? '9999-12-31' : (string) $cutoffEnd;

        // Bind the cutoff once; the same value drives both sides.
        $conn->query("SET @cutoff_end = '" . $conn->real_escape_string($cutoff) . "'");

        $sql = "
        SELECT
            `r`.`MemberNo`,
            `r`.`OldOpeningBalance`,
            `r`.`FixOpeningBalance`,
            `r`.`OpeningDifference`,
            `r`.`OldClosingBalance`,
            `r`.`FixClosingBalance`,
            `r`.`ClosingDifference`,
            `r`.`OldInterestCount`,
            `r`.`FixInterestCount`,
            `r`.`InterestCountDifference`,
            `r`.`OldInterestTotal`,
            `r`.`FixInterestTotal`,
            `r`.`InterestDifference`
        FROM (
            SELECT
                COALESCE(CAST(`t`.`MemberNo` AS CHAR), 'GRAND TOTAL')        AS `MemberNo`,
                ROUND(SUM(`t`.`old_opening`), 2)                             AS `OldOpeningBalance`,
                ROUND(SUM(`t`.`fix_opening`), 2)                             AS `FixOpeningBalance`,
                ROUND(SUM(`t`.`fix_opening`) - SUM(`t`.`old_opening`), 2)    AS `OpeningDifference`,
                ROUND(SUM(`t`.`old_closing`), 2)                             AS `OldClosingBalance`,
                ROUND(SUM(`t`.`fix_closing`), 2)                             AS `FixClosingBalance`,
                ROUND(SUM(`t`.`fix_closing`) - SUM(`t`.`old_closing`), 2)    AS `ClosingDifference`,
                SUM(`t`.`old_int_count`)                                     AS `OldInterestCount`,
                SUM(`t`.`fix_int_count`)                                     AS `FixInterestCount`,
                SUM(`t`.`fix_int_count`) - SUM(`t`.`old_int_count`)          AS `InterestCountDifference`,
                ROUND(SUM(`t`.`old_int_total`), 2)                           AS `OldInterestTotal`,
                ROUND(SUM(`t`.`fix_int_total`), 2)                           AS `FixInterestTotal`,
                ROUND(SUM(`t`.`fix_int_total`) - SUM(`t`.`old_int_total`), 2) AS `InterestDifference`
            FROM (
                SELECT
                    `m`.`MemberID`,
                    `m`.`MemberNo`,
                    IFNULL((SELECT `a`.`NewBalance`
                            FROM `tblmemberaccounts` `a`
                            WHERE `a`.`memberID` = `m`.`MemberID`
                              AND `a`.`TransactionTypeID` = 1
                              AND `a`.`TransactionDate` <= @cutoff_end
                            ORDER BY `a`.`TransactionDate` ASC, `a`.`accountsID` ASC LIMIT 1), 0) AS `old_opening`,
                    IFNULL((SELECT `f`.`NewBalance`
                            FROM `transactionsfix` `f`
                            WHERE `f`.`memberID` = `m`.`MemberID`
                              AND `f`.`TransactionTypeID` = 1
                              AND `f`.`TransactionDate` <= @cutoff_end
                            ORDER BY `f`.`TransactionDate` ASC, `f`.`accountsID` ASC LIMIT 1), 0) AS `fix_opening`,
                    IFNULL((SELECT `a`.`NewBalance`
                            FROM `tblmemberaccounts` `a`
                            WHERE `a`.`memberID` = `m`.`MemberID`
                              AND `a`.`TransactionDate` <= @cutoff_end
                            ORDER BY `a`.`TransactionDate` DESC, `a`.`accountsID` DESC LIMIT 1), 0) AS `old_closing`,
                    IFNULL((SELECT `f`.`NewBalance`
                            FROM `transactionsfix` `f`
                            WHERE `f`.`memberID` = `m`.`MemberID`
                              AND `f`.`TransactionDate` <= @cutoff_end
                            ORDER BY `f`.`TransactionDate` DESC, `f`.`accountsID` DESC LIMIT 1), 0) AS `fix_closing`,
                    (SELECT COUNT(*)
                       FROM `tblmemberaccounts` `a`
                      WHERE `a`.`memberID` = `m`.`MemberID`
                        AND `a`.`TransactionTypeID` = 8
                        AND `a`.`TransactionDate` <= @cutoff_end)             AS `old_int_count`,
                    (SELECT COUNT(*)
                       FROM `transactionsfix` `f`
                      WHERE `f`.`memberID` = `m`.`MemberID`
                        AND `f`.`TransactionTypeID` = 8
                        AND `f`.`TransactionDate` <= @cutoff_end)             AS `fix_int_count`,
                    IFNULL((SELECT SUM(`a`.`Amount`)
                              FROM `tblmemberaccounts` `a`
                             WHERE `a`.`memberID` = `m`.`MemberID`
                               AND `a`.`TransactionTypeID` = 8
                               AND `a`.`TransactionDate` <= @cutoff_end), 0)   AS `old_int_total`,
                    IFNULL((SELECT SUM(`f`.`Amount`)
                              FROM `transactionsfix` `f`
                             WHERE `f`.`memberID` = `m`.`MemberID`
                               AND `f`.`TransactionTypeID` = 8
                               AND `f`.`TransactionDate` <= @cutoff_end), 0)   AS `fix_int_total`
                FROM `tblmembers` `m`
                WHERE 1 = 1" . system_fix_tx_excluded_sql($conn, 'm.MemberID') . "
            ) `t`
            GROUP BY `t`.`MemberNo` WITH ROLLUP
        ) `r`
        ORDER BY
            `r`.`MemberNo` = 'GRAND TOTAL',
            ABS(`r`.`ClosingDifference`) DESC,
            `r`.`MemberNo` ASC";

        $rows = [];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
}
if (!function_exists('system_fix_tx_consolidated_statement')) {
    /**
     * Consolidated (all-members) statement for a date range, read from the
     * rebuild in `transactionsfix`.
     *
     * Opening balance is the balance carried into `$fromDate` (the closing
     * balance of the member's last row before it); closing balance is the
     * balance after the member's last row on or before `$toDate`. Members with
     * no balance and no activity in the range are left out, so a settled or
     * dormant account does not clutter the sheet with zeros.
     *
     * When `$toDate` is omitted the range covers the whole month of `$fromDate`.
     *
     * @return array{rows:array<int,array<string,mixed>>,total_opening:float,total_closing:float,period:string,label:string,count:int,from:string,to:string}
     */
    function system_fix_tx_consolidated_statement(mysqli $conn, string $fromDate, ?string $toDate = null): array
    {
        $table = system_fix_tx_table();

        if ($toDate === null || trim($toDate) === '') {
            // One argument means "the whole month of that date".
            $from = date('Y-m-01', strtotime($fromDate));
            $to = date('Y-m-t', strtotime($fromDate));
        } else {
            $from = date('Y-m-d', strtotime($fromDate));
            $to = date('Y-m-d', strtotime($toDate));
        }
        if ($to < $from) {
            [$from, $to] = [$to, $from];
        }

        // A range that exactly covers one calendar month is labelled by month.
        $isWholeMonth = $from === date('Y-m-01', strtotime($from))
            && $to === date('Y-m-t', strtotime($to))
            && date('Y-m', strtotime($from)) === date('Y-m', strtotime($to));

        $periodLabel = $isWholeMonth ? date('Y-m', strtotime($from)) : $from . ' to ' . $to;

        $start = $conn->real_escape_string($from);
        $end = $conn->real_escape_string($to);

        $sql = "
        SELECT `t`.*
        FROM (
            SELECT
                `m`.`MemberNo`                                    AS `MemberNo`,
                `m`.`MemberFirstname`                             AS `Name`,
                `m`.`MemberSurname`                               AS `Surname`,
                IFNULL((SELECT `f`.`NewBalance`
                          FROM `{$table}` `f`
                         WHERE `f`.`memberID` = `m`.`MemberID`
                           AND `f`.`TransactionDate` < '{$start}'
                         ORDER BY `f`.`TransactionDate` DESC, `f`.`accountsID` DESC LIMIT 1), 0) AS `OpeningBalance`,
                IFNULL((SELECT `f`.`NewBalance`
                          FROM `{$table}` `f`
                         WHERE `f`.`memberID` = `m`.`MemberID`
                           AND `f`.`TransactionDate` <= '{$end}'
                         ORDER BY `f`.`TransactionDate` DESC, `f`.`accountsID` DESC LIMIT 1), 0) AS `ClosingBalance`,
                (SELECT COUNT(*)
                   FROM `{$table}` `f`
                  WHERE `f`.`memberID` = `m`.`MemberID`
                    AND `f`.`TransactionDate` >= '{$start}'
                    AND `f`.`TransactionDate` <= '{$end}')        AS `PeriodRows`
            FROM `tblmembers` `m`
            WHERE 1 = 1" . system_fix_tx_excluded_sql($conn, 'm.MemberID') . "
        ) `t`
        WHERE `t`.`OpeningBalance` <> 0
           OR `t`.`ClosingBalance` <> 0
           OR `t`.`PeriodRows` > 0
        ORDER BY CAST(`t`.`MemberNo` AS UNSIGNED) ASC, `t`.`MemberNo` ASC";

        $rows = [];
        $totalOpening = 0.0;
        $totalClosing = 0.0;

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $opening = (float) $row['OpeningBalance'];
            $closing = (float) $row['ClosingBalance'];

            $rows[] = [
                'MemberNo' => (string) $row['MemberNo'],
                'Name' => (string) $row['Name'],
                'Surname' => (string) $row['Surname'],
                'Period' => $periodLabel,
                'OpeningBalance' => $opening,
                'ClosingBalance' => $closing,
            ];

            $totalOpening += $opening;
            $totalClosing += $closing;
        }

        return [
            'rows' => $rows,
            'count' => count($rows),
            'total_opening' => round($totalOpening, 2),
            'total_closing' => round($totalClosing, 2),
            'period' => $periodLabel,
            'label' => $isWholeMonth
                ? date('F Y', strtotime($from))
                : date('j F Y', strtotime($from)) . ' to ' . date('j F Y', strtotime($to)),
            'from' => $from,
            'to' => $to,
        ];
    }
}

/* =========================================================================
 * IMPAIRMENT
 * =========================================================================
 * The impairment sheet lists MemberNo + Impairment for members whose fund
 * balance must be written down. Two things happen for every row:
 *
 *   1. `transactionsfix_impairment` records balance before, the impairment and
 *      balance after — for EVERY member on the sheet, including those whose
 *      balance is already zero or would go negative.
 *   2. `transactionsfix` receives a debit (type 13, Details 'Impairment') only
 *      when the member actually has a balance above zero. The debit is capped
 *      at that balance, because the ledger must never go negative; the working
 *      table keeps the true (possibly negative) figure.
 * ========================================================================= */

if (!function_exists('system_fix_tx_balance_at')) {
    /**
     * Sandbox balance for a member as at a date (inclusive) — the NewBalance of
     * their last `transactionsfix` row on or before that date.
     *
     * Falls back the same way system_fix_tx_base_balance() does when the sandbox
     * holds nothing for the member yet: previous month in tblmemberaccounts,
     * then the production `balances` figure.
     */
    function system_fix_tx_balance_at(mysqli $conn, int $memberId, string $date): float
    {
        $table = system_fix_tx_table();
        $stmt = $conn->prepare(
            "SELECT `NewBalance` FROM `{$table}`
             WHERE `memberID` = ? AND `TransactionDate` <= ?
             ORDER BY `TransactionDate` DESC, `accountsID` DESC
             LIMIT 1"
        );
        $stmt->bind_param('is', $memberId, $date);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();

        if ($row !== null) {
            return round((float) $row['NewBalance'], 2);
        }

        $prior = system_fix_tx_prior_month_balance($conn, $memberId, $date);
        if ($prior !== null) {
            return round($prior, 2);
        }

        return round(system_fix_tx_member_balance($conn, $memberId), 2);
    }
}

if (!function_exists('system_fix_tx_spreadsheet_rows')) {
    /**
     * Read a spreadsheet (CSV or .xlsx) into a header row plus data rows.
     *
     * @return array{header:array<int,string>, rows:array<int,array<int,string>>, errors:array<int,string>}
     */
    function system_fix_tx_spreadsheet_rows(string $filePath): array
    {
        if (preg_match('/\.xlsx$/i', $filePath) || system_fix_tx_looks_like_xlsx($filePath)) {
            $read = system_fix_tx_xlsx_rows($filePath);
            if (!empty($read['errors'])) {
                return ['header' => [], 'rows' => [], 'errors' => $read['errors']];
            }

            $all = $read['rows'];
            if (empty($all)) {
                return ['header' => [], 'rows' => [], 'errors' => ['The spreadsheet is empty.']];
            }

            $header = array_shift($all);
            return ['header' => $header, 'rows' => $all, 'errors' => []];
        }

        if (($handle = fopen($filePath, 'r')) === false) {
            return ['header' => [], 'rows' => [], 'errors' => ['Unable to open the uploaded file.']];
        }

        $header = fgetcsv($handle, 0, ',') ?: [];
        $rows = [];
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $rows[] = $data;
        }
        fclose($handle);

        if (empty($header)) {
            return ['header' => [], 'rows' => [], 'errors' => ['The spreadsheet is empty.']];
        }

        return ['header' => $header, 'rows' => $rows, 'errors' => []];
    }
}

if (!function_exists('system_fix_tx_parse_impairment')) {
    /**
     * Parse the IMPAIRMENT spreadsheet.
     *
     * Expected headers: `MemberNo` (Member No / Member) and `Impairment`.
     * Column order does not matter; when the header matches nothing, the first
     * column is read as the member number and the second as the impairment.
     *
     * An impairment always reduces a balance, so the figures may arrive either
     * positive or negative (`-3.99` and `3.99` both mean "take 3.99"). The sign
     * is ignored and the magnitude is used.
     *
     * @return array{rows:array<int,array{line:int,member_no:string,impairment:float}>, errors:array<int,string>}
     */
    function system_fix_tx_parse_impairment(string $filePath): array
    {
        $read = system_fix_tx_spreadsheet_rows($filePath);
        if (!empty($read['errors'])) {
            return ['rows' => [], 'errors' => $read['errors']];
        }

        $map = [];
        foreach ($read['header'] as $index => $name) {
            $map[strtolower(preg_replace('/[^a-z0-9]+/i', '', (string) $name))] = $index;
        }

        $memberIdx = $map['memberno'] ?? $map['member'] ?? $map['memberid'] ?? 0;
        $impairmentIdx = $map['impairment'] ?? $map['impairments'] ?? $map['impairmentamount']
            ?? $map['amount'] ?? ($memberIdx === 0 ? 1 : 0);

        $rows = [];
        $errors = [];
        $lineNo = 1;

        foreach ($read['rows'] as $data) {
            $lineNo++;

            $memberNo = trim((string) ($data[$memberIdx] ?? ''));
            // Strip currency symbols, thousands separators and spaces.
            $rawImpairment = preg_replace('/[^0-9.\-]/', '', (string) ($data[$impairmentIdx] ?? ''));

            if ($memberNo === '' && $rawImpairment === '') {
                continue; // blank line
            }

            if ($memberNo === '' || !is_numeric($rawImpairment)) {
                $errors[] = "Line {$lineNo}: skipped (MemberNo/Impairment could not be read).";
                continue;
            }

            $rows[] = [
                'line' => $lineNo,
                'member_no' => $memberNo,
                'impairment' => round(abs((float) $rawImpairment), 2),
            ];
        }

        if (empty($rows) && empty($errors)) {
            $errors[] = 'No impairment rows were found in the file.';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }
}

if (!function_exists('system_fix_tx_clear_impairment')) {
    /**
     * Remove a run's impairments from BOTH tables, so re-uploading the same
     * sheet replaces the previous result instead of debiting twice.
     *
     * @return array{rows:int, transactions:int}
     */
    function system_fix_tx_clear_impairment(mysqli $conn, string $runId): array
    {
        $impairmentTable = system_fix_tx_impairment_table();
        $table = system_fix_tx_table();
        $period = substr($runId, 0, 7);
        $bounds = system_fix_tx_period_bounds($period . '-01');

        $stmt = $conn->prepare("DELETE FROM `{$impairmentTable}` WHERE `RunID` = ?");
        $stmt->bind_param('s', $period);
        $stmt->execute();
        $rows = (int) $stmt->affected_rows;
        $stmt->close();

        $stmt = $conn->prepare(
            "DELETE FROM `{$table}`
             WHERE `TransactionTypeID` = 13 AND `Details` = 'Impairment'
               AND `TransactionDate` BETWEEN ? AND ?"
        );
        $stmt->bind_param('ss', $bounds['start'], $bounds['end']);
        $stmt->execute();
        $transactions = (int) $stmt->affected_rows;
        $stmt->close();

        return ['rows' => $rows, 'transactions' => $transactions];
    }
}

if (!function_exists('system_fix_tx_apply_impairment')) {
    /**
     * Apply an impairment sheet as at a date.
     *
     * For EVERY row on the sheet:
     *   balance_before = member's sandbox balance as at $date
     *   applied        = min(impairment, balance_before), never below zero
     *   balance_after  = balance_before - applied   (0.00 when the impairment
     *                    exceeds the balance, e.g. 90 - 100 = 0.00 with 90 applied)
     *
     * `transactionsfix_impairment` always receives the row, even when the member
     * has nothing to take (balance_before <= 0), so every member on the sheet is
     * visible. `Impairment` keeps the full amount asked for; `AppliedAmount`
     * records what could actually be taken.
     *
     * `transactionsfix` receives a debit ONLY when balance_before is above zero,
     * capped at that balance so the ledger can never go negative:
     *   TransactionTypeID 13, Details 'Impairment',
     *   Comments 'Impairment of <amount applied> Applied'.
     *
     * @param array<int,array> $rows Output of system_fix_tx_parse_impairment().
     * @return array{run_id:string, rows:int, posted:int, negatives:int,
     *               total_impairment:float, total_applied:float,
     *               skipped:array<int,string>, cleared:array{rows:int,transactions:int}}
     */
    function system_fix_tx_apply_impairment(
        mysqli $conn,
        array $rows,
        string $date,
        string $source = ''
    ): array {
        $runId = system_fix_tx_period_key($date);
        $impairmentTable = system_fix_tx_impairment_table();

        $result = [
            'run_id' => $runId,
            'rows' => 0,
            'posted' => 0,
            'negatives' => 0,
            'total_impairment' => 0.0,
            'total_applied' => 0.0,
            'skipped' => [],
            'cleared' => system_fix_tx_clear_impairment($conn, $runId),
        ];

        $insert = $conn->prepare(
            "INSERT INTO `{$impairmentTable}`
                (`RunID`, `TransactionDate`, `MemberNo`, `memberID`, `BalanceBefore`,
                 `Impairment`, `BalanceAfter`, `AppliedAmount`, `Posted`, `Source`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $seen = [];

        try {
            foreach ($rows as $row) {
                $memberNo = (string) ($row['member_no'] ?? '');
                $impairment = round((float) ($row['impairment'] ?? 0), 2);
                $line = (int) ($row['line'] ?? 0);

                $member = system_fix_tx_resolve_member($conn, $memberNo);
                if ($member === null) {
                    $result['skipped'][] = "Line {$line}: member {$memberNo} was not found in tblmembers.";
                    continue;
                }

                $memberId = (int) $member['MemberID'];
                $resolvedNo = (string) $member['MemberNo'];

                if (!empty($member['Excluded'])) {
                    $result['skipped'][] = "Line {$line}: member {$resolvedNo} is excluded from the fix and was skipped.";
                    continue;
                }

                if (isset($seen[$memberId])) {
                    $result['skipped'][] = "Line {$line}: member {$resolvedNo} already appeared on line {$seen[$memberId]}; skipped.";
                    continue;
                }
                $seen[$memberId] = $line;

                if ($impairment <= 0) {
                    $result['skipped'][] = "Line {$line}: member {$resolvedNo} has an impairment of "
                        . number_format($impairment, 2) . '; nothing to apply.';
                    continue;
                }

                $before = system_fix_tx_balance_at($conn, $memberId, $date);

                // The member can never be left owing money: when the impairment
                // is larger than the balance it is capped there, so a member
                // with 90 impaired by 100 ends at 0.00 holding an impairment of
                // 90.00 (the shortfall stays visible in `Impairment`).
                $applied = $before > 0 ? round(min($impairment, $before), 2) : 0.0;
                $after = round($before - $applied, 2);
                $posted = $applied > 0 ? 1 : 0;

                if ($applied < $impairment || $before <= 0) {
                    $result['negatives']++;
                }

                if ($posted === 1) {
                    system_fix_tx_insert($conn, [
                        'date' => $date,
                        'type' => 13,
                        'member_id' => $memberId,
                        'details' => 'Impairment',
                        'credit' => '0',
                        'starting' => $before,
                        'amount' => $applied,
                        'new_balance' => round($before - $applied, 2),
                        'comments' => 'Impairment of ' . number_format($applied, 2) . ' Applied',
                    ]);
                    $result['posted']++;
                }

                $insert->bind_param(
                    'sssiddddis',
                    $runId,
                    $date,
                    $resolvedNo,
                    $memberId,
                    $before,
                    $impairment,
                    $after,
                    $applied,
                    $posted,
                    $source
                );
                $insert->execute();

                $result['rows']++;
                $result['total_impairment'] = round($result['total_impairment'] + $impairment, 2);
                $result['total_applied'] = round($result['total_applied'] + $applied, 2);
            }
        } finally {
            $insert->close();
        }

        return $result;
    }
}

if (!function_exists('system_fix_tx_impairment_rows')) {
    /**
     * Impairment rows for display, newest run first then by member number.
     *
     * @return array<int,array<string,mixed>>
     */
    function system_fix_tx_impairment_rows(mysqli $conn, ?string $runId = null, int $limit = 1000): array
    {
        $table = system_fix_tx_impairment_table();
        $sql = "SELECT `id`, `RunID`, `TransactionDate`, `MemberNo`, `memberID`, `BalanceBefore`,
                       `Impairment`, `BalanceAfter`, `AppliedAmount`, `Posted`, `Source`, `CreatedAt`
                FROM `{$table}`";
        if ($runId !== null && $runId !== '') {
            $sql .= " WHERE `RunID` = ?";
        }
        $sql .= " ORDER BY `RunID` DESC, CAST(`MemberNo` AS UNSIGNED) ASC LIMIT " . max(1, $limit);

        $stmt = $conn->prepare($sql);
        if ($runId !== null && $runId !== '') {
            $stmt->bind_param('s', $runId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();

        return $rows;
    }
}

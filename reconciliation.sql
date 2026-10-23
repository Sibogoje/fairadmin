-- =============================================================================
--  SYSTEM FIX — RECONCILIATION
--
--  Old table : tblmemberaccounts   (production)
--  New table : transactionsfix     (the rebuild)
--
--  Returns one row per member, plus a GRAND TOTAL row at the bottom:
--    MemberNo | OldOpeningBalance | FixOpeningBalance | OpeningDifference
--             | OldClosingBalance | FixClosingBalance | ClosingDifference
--
--  CUTOFF END DATE
--    Both sides are read AS AT the same date, so production (which may run to
--    today) is never compared against a sandbox that only reaches the last
--    rebuilt month. That mismatch used to make every member look different.
--
--    Set @cutoff_end below to the last day of the month you rebuilt, e.g. the
--    June 2016 run uses '2016-06-30'. Use NULL to compare ALL dates.
--
--  Run BOTH statements (the SET and the SELECT) in phpMyAdmin / HeidiSQL / the
--  mysql CLI. Nothing is modified — this is read-only.
-- =============================================================================

SET @cutoff_end = '2016-06-30';                              -- <<< CHANGE THIS
SET @cutoff_end = IFNULL(DATE(@cutoff_end), '9999-12-31');   -- NULL = every date

SELECT
    `r`.`MemberNo`,
    `r`.`OldOpeningBalance`,
    `r`.`FixOpeningBalance`,
    `r`.`OpeningDifference`,
    `r`.`OldClosingBalance`,
    `r`.`FixClosingBalance`,
    `r`.`ClosingDifference`
FROM (
    SELECT
        COALESCE(CAST(`t`.`MemberNo` AS CHAR), 'GRAND TOTAL')        AS `MemberNo`,
        ROUND(SUM(`t`.`old_opening`), 2)                             AS `OldOpeningBalance`,
        ROUND(SUM(`t`.`fix_opening`), 2)                             AS `FixOpeningBalance`,
        ROUND(SUM(`t`.`fix_opening`) - SUM(`t`.`old_opening`), 2)    AS `OpeningDifference`,
        ROUND(SUM(`t`.`old_closing`), 2)                             AS `OldClosingBalance`,
        ROUND(SUM(`t`.`fix_closing`), 2)                             AS `FixClosingBalance`,
        ROUND(SUM(`t`.`fix_closing`) - SUM(`t`.`old_closing`), 2)    AS `ClosingDifference`
    FROM (
        SELECT
            `m`.`MemberID`,
            `m`.`MemberNo`,
            -- Opening = first opening-balance entry ON OR BEFORE the cutoff.
            -- Members who only joined after it score 0 on BOTH sides.
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
            -- Closing = last entry ON OR BEFORE the cutoff, on both sides.
            IFNULL((SELECT `a`.`NewBalance`
                    FROM `tblmemberaccounts` `a`
                    WHERE `a`.`memberID` = `m`.`MemberID`
                      AND `a`.`TransactionDate` <= @cutoff_end
                    ORDER BY `a`.`TransactionDate` DESC, `a`.`accountsID` DESC LIMIT 1), 0) AS `old_closing`,
            IFNULL((SELECT `f`.`NewBalance`
                    FROM `transactionsfix` `f`
                    WHERE `f`.`memberID` = `m`.`MemberID`
                      AND `f`.`TransactionDate` <= @cutoff_end
                    ORDER BY `f`.`TransactionDate` DESC, `f`.`accountsID` DESC LIMIT 1), 0) AS `fix_closing`
        FROM `tblmembers` `m`
    ) `t`
    GROUP BY `t`.`MemberNo` WITH ROLLUP
) `r`
ORDER BY
    `r`.`MemberNo` = 'GRAND TOTAL',
    ABS(`r`.`ClosingDifference`) DESC,
    `r`.`MemberNo` ASC;

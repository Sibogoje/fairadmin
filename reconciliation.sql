-- =============================================================================
--  SYSTEM FIX — RECONCILIATION  (single statement, SELECT-only)
--
--  Old table : tblmemberaccounts   (production)
--  New table : transactionsfix     (the rebuild)
--
--  Returns one row per member, plus a GRAND TOTAL row at the bottom:
--    MemberNo | OldOpeningBalance | FixOpeningBalance | OpeningDifference
--             | OldClosingBalance | FixClosingBalance | ClosingDifference
--
--  Run it as-is in phpMyAdmin / HeidiSQL / the mysql CLI. Nothing is modified.
-- =============================================================================

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
            IFNULL((SELECT `a`.`NewBalance`
                    FROM `tblmemberaccounts` `a`
                    WHERE `a`.`memberID` = `m`.`MemberID` AND `a`.`TransactionTypeID` = 1
                    ORDER BY `a`.`accountsID` ASC LIMIT 1), 0)        AS `old_opening`,
            IFNULL((SELECT `f`.`NewBalance`
                    FROM `transactionsfix` `f`
                    WHERE `f`.`memberID` = `m`.`MemberID` AND `f`.`TransactionTypeID` = 1
                    ORDER BY `f`.`accountsID` ASC LIMIT 1), 0)        AS `fix_opening`,
            IFNULL((SELECT `a`.`NewBalance`
                    FROM `tblmemberaccounts` `a`
                    WHERE `a`.`memberID` = `m`.`MemberID`
                    ORDER BY `a`.`accountsID` DESC LIMIT 1), 0)       AS `old_closing`,
            IFNULL((SELECT `f`.`NewBalance`
                    FROM `transactionsfix` `f`
                    WHERE `f`.`memberID` = `m`.`MemberID`
                    ORDER BY `f`.`accountsID` DESC LIMIT 1), 0)       AS `fix_closing`
        FROM `tblmembers` `m`
    ) `t`
    GROUP BY `t`.`MemberNo` WITH ROLLUP
) `r`
ORDER BY
    `r`.`MemberNo` = 'GRAND TOTAL',
    ABS(`r`.`ClosingDifference`) DESC,
    `r`.`MemberNo` ASC;

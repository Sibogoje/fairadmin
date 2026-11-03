-- =============================================================================
--  TERMINATIONS VIEW
--
--  Purpose : one row per termination, taken from the LEDGER rather than from
--            `tblmembers.TerminationDate`.
--
--  Source  : tblmemberaccounts WHERE TransactionTypeID = 11
--            11 = 'Final Transaction' (the payout that zeroes the balance)
--
--  Columns : MemberID | MemberNo | TerminationDate | Amount
--
--  WHY THE LEDGER AND NOT tblmembers
--    tblmembers.TerminationDate is NOT reliable in this database. Measured on
--    2026-11-03:
--        members with a TerminationDate .............. 243
--        members with a type-11 transaction ........... 160
--        date set but NO type-11 payout ............... 189
--        type-11 payout but date NOT set .............. 106
--    So the two sources disagree for ~90% of members. A type-11 row is proof
--    that money actually left the fund, which is what a termination report
--    needs, so the ledger is the source of truth here.
--
--  AMOUNT
--    `Amount` is the final payout (the full remaining balance). It does NOT
--    include the termination fee, which is booked separately as
--    TransactionTypeID = 12 ('Termination Fee').
--
--  REPEATS
--    Termination is meant to happen once per member, but one member (118) has
--    two type-11 rows because a later withdrawal was also booked as a final
--    transaction. The view returns BOTH rows, which is what happened in the
--    ledger; filter or GROUP BY if you need one row per member.
--
--  Run this file with phpMyAdmin / HeidiSQL / the mysql CLI. It only creates a
--  view — no data is modified.
-- =============================================================================

CREATE OR REPLACE
    ALGORITHM = MERGE
    DEFINER = CURRENT_USER
    SQL SECURITY INVOKER
VIEW `terminations` AS
SELECT
    `a`.`memberID`                      AS `MemberID`,
    `m`.`MemberNo`                      AS `MemberNo`,
    `a`.`TransactionDate`               AS `TerminationDate`,
    ROUND(`a`.`Amount`, 2)              AS `Amount`
FROM `tblmemberaccounts` `a`
INNER JOIN `tblmembers` `m` ON `m`.`MemberID` = `a`.`memberID`
WHERE `a`.`TransactionTypeID` = 11
  AND `a`.`MemberID` > 0;


-- -----------------------------------------------------------------------------
--  Verification (optional — comment out if your client runs the whole file)
-- -----------------------------------------------------------------------------
-- SELECT * FROM `terminations` ORDER BY `TerminationDate` DESC LIMIT 20;
--
-- SELECT COUNT(*) AS terminations,
--        COUNT(DISTINCT `MemberID`) AS members,
--        MIN(`TerminationDate`) AS first_date,
--        MAX(`TerminationDate`) AS last_date,
--        ROUND(SUM(`Amount`), 2) AS total_paid
-- FROM `terminations`;
--
-- Expected around: 161 terminations, 160 members, E 767,006.58 in payouts.

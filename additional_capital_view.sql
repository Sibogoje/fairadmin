-- =============================================================================
--  ADDITIONAL CAPITAL VIEW
--
--  Purpose : one row per additional-capital injection.
--
--  Source  : tblmemberaccounts WHERE TransactionTypeID = 9
--            9 = 'Additional Capital'
--
--  Columns : MemberID | MemberNo | TransactionDate | Amount
--
--  AMOUNT
--    `Amount` is the capital credited to the member. Type 9 is always money IN
--    (every row has Credit = '1').
--
--    The 1% capital fee is booked SEPARATELY as TransactionTypeID 5 with
--    Details = 'Additional Capital Fee', so it is NOT included here. Use the
--    capital_fee view (or join on the same member/date) for fee totals.
--
--  REPEATS
--    A member can inject capital more than once, so the view intentionally
--    returns every row rather than one row per member. 40 members currently
--    hold the 44 rows (4 members have two injections each).
--
--  Run this file with phpMyAdmin / HeidiSQL / the mysql CLI. It only creates a
--  view — no data is modified.
-- =============================================================================

CREATE OR REPLACE
    ALGORITHM = MERGE
    DEFINER = CURRENT_USER
    SQL SECURITY INVOKER
VIEW `additional_capital` AS
SELECT
    `a`.`memberID`                      AS `MemberID`,
    `m`.`MemberNo`                      AS `MemberNo`,
    `a`.`TransactionDate`               AS `TransactionDate`,
    ROUND(`a`.`Amount`, 2)              AS `Amount`
FROM `tblmemberaccounts` `a`
INNER JOIN `tblmembers` `m` ON `m`.`MemberID` = `a`.`memberID`
WHERE `a`.`TransactionTypeID` = 9
  AND `a`.`MemberID` > 0;


-- =============================================================================
--  ADDITIONAL CAPITAL FEE VIEW  (companion)
--
--  The fee charged on the injections above, for reporting alongside them.
--
--  IMPORTANT: the fee has been booked under TWO different Details strings over
--  the years, and BOTH are needed to cover every capital row:
--     'Additional Capital Transaction Fee'  -> 23 rows, E 9,377.51 (historical)
--     'Additional Capital Fee'              -> 21 rows, E 3,768.20 (current)
--  Matching only the current one would silently drop half the fees.
--
--  Both are TransactionTypeID 5, which is also used for adhoc and regular
--  transaction fees, so the Details filter is what keeps them apart.
-- =============================================================================

CREATE OR REPLACE
    ALGORITHM = MERGE
    DEFINER = CURRENT_USER
    SQL SECURITY INVOKER
VIEW `additional_capital_fee` AS
SELECT
    `a`.`memberID`                      AS `MemberID`,
    `m`.`MemberNo`                      AS `MemberNo`,
    `a`.`TransactionDate`               AS `TransactionDate`,
    ROUND(`a`.`Amount`, 2)              AS `Amount`,
    `a`.`Details`                       AS `Details`
FROM `tblmemberaccounts` `a`
INNER JOIN `tblmembers` `m` ON `m`.`MemberID` = `a`.`memberID`
WHERE `a`.`TransactionTypeID` = 5
  AND `a`.`Details` IN ('Additional Capital Fee', 'Additional Capital Transaction Fee')
  AND `a`.`MemberID` > 0;


-- -----------------------------------------------------------------------------
--  Verification (optional — comment out if your client runs the whole file)
-- -----------------------------------------------------------------------------
-- SELECT * FROM `additional_capital` ORDER BY `TransactionDate` DESC LIMIT 20;
--
-- SELECT COUNT(*) AS injections,
--        COUNT(DISTINCT `MemberID`) AS members,
--        MIN(`TransactionDate`) AS first_date,
--        MAX(`TransactionDate`) AS last_date,
--        ROUND(SUM(`Amount`), 2) AS total_capital
-- FROM `additional_capital`;
--
-- -- Capital next to its fee, per member per day.
-- SELECT c.`MemberID`, c.`MemberNo`, c.`TransactionDate`,
--        c.`Amount` AS `Capital`, IFNULL(SUM(f.`Amount`), 0) AS `Fee`
-- FROM `additional_capital` c
-- LEFT JOIN `additional_capital_fee` f
--        ON f.`MemberID` = c.`MemberID` AND f.`TransactionDate` = c.`TransactionDate`
-- GROUP BY c.`MemberID`, c.`MemberNo`, c.`TransactionDate`, c.`Amount`
-- ORDER BY c.`TransactionDate` DESC;
--
-- Expected around: 44 injections, 40 members, E 1,314,569.86 total capital,
-- and 44 fee rows totalling E 13,145.71 (1% on every injection).

-- =====================================================
-- STAGE ADHOC PAYMENTS: 2026-11-01 .. 2026-11-17
-- Source : tblmemberaccountsbackup (TransactionTypeID = 4)
-- Target : tbltempadhocpayments
-- Only the adhoc payment row is staged; the 2% fee (type 5)
-- is generated later by adhocprocess.php when processing.
-- =====================================================

START TRANSACTION;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT
  b.`memberID`,
  CONCAT(m.`MemberNo`, '--', m.`MemberSurname`, ' ', m.`MemberFirstname`) AS Name,
  b.`TransactionDate`,
  b.`Details`,
  b.`Amount`,
  b.`Comments`
FROM `tblmemberaccountsbackup` b
INNER JOIN `tblmembers` m ON m.`MemberID` = b.`memberID`
WHERE b.`TransactionTypeID` = 4
  AND b.`TransactionDate` >= '2026-11-01'
  AND b.`TransactionDate` <= '2026-11-17'
  AND b.`Amount` > 0
  AND NOT EXISTS (
    SELECT 1 FROM `tbltempadhocpayments` t
    WHERE t.`MemberID` = b.`memberID`
      AND t.`PaymentDate` = b.`TransactionDate`
      AND t.`AdHocPayment` = b.`Amount`
      AND COALESCE(t.`Comments`, '') = COALESCE(b.`Comments`, '')
  )
ORDER BY b.`TransactionDate`, b.`accountsID`;

COMMIT;

-- Verification
SELECT COUNT(*) AS staged_rows,
       ROUND(SUM(`AdHocPayment`), 2) AS staged_total,
       MIN(`PaymentDate`) AS first_date,
       MAX(`PaymentDate`) AS last_date
FROM `tbltempadhocpayments`
WHERE `PaymentDate` >= '2026-11-01' AND `PaymentDate` <= '2026-11-17';

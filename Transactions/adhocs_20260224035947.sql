-- =====================================================
-- INSERT ADHOC TRANSACTIONS - JAN 2026
-- Generated: 2026-02-24 03:59:47
-- Total Records: 430
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
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,430.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723438' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( Registration Fee,F1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1561' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '1615' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1, Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1615' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178482' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 11 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '783' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,020.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( Registration Fee,F1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 353.24 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Water Bill'
FROM tblmembers tm 
WHERE tm.MemberNo = '1644' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,776.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1561' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Book Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '1561' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,390.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1561' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 11,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 6 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178545' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,503.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Exam Fee ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178546' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178546' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,590.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1133' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,541.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1133' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1694' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 14,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 7 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723516' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 550.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723516' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1688' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,640.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees (Form 2)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178557' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent & Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178475' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178474' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 41.60 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '178466' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('13/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,260.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('13/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,850.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1615' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 10,185.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1787' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport, Groceries & Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees (Grade 7)'
FROM tblmembers tm 
WHERE tm.MemberNo = '799' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1598' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Data (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1598' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('14/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries & Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1598' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723440' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,320.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Bedding'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723440' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Travelling Case'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723440' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723440' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox'
FROM tblmembers tm 
WHERE tm.MemberNo = '178506' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178506' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 430.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Sneakers'
FROM tblmembers tm 
WHERE tm.MemberNo = '178506' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,130.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178506' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178396' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 560.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( Stationery)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178396' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 8,925.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees( Grade 7 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178396' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox'
FROM tblmembers tm 
WHERE tm.MemberNo = '178527' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178527' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,288.90 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178527' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178527' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,020.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes ,Bag & Socks'
FROM tblmembers tm 
WHERE tm.MemberNo = '1613' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag, Shoes & Socks'
FROM tblmembers tm 
WHERE tm.MemberNo = '1791' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 7 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1791' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,539.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1766' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 150.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund - Acceptance Fee  (Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1765' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1765' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,280.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1765' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1765' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,440.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hall Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 590.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Typek'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Accommodation ( Lubhoko High)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Bootcamp '
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport & Pocket Money ( Term1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178465' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '994' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,260.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,910.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 8,272.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( School Fees Grade 7)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1741' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 350.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tunic'
FROM tblmembers tm 
WHERE tm.MemberNo = '178528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox & Transport ( Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Books'
FROM tblmembers tm 
WHERE tm.MemberNo = '178528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 10,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723412' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag & Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 440.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,990.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178465' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723441' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723468' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,780.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '973' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport & Pocket Money ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '973' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178401' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery '
FROM tblmembers tm 
WHERE tm.MemberNo = '178499' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Socks, T- Shirts, Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178499' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178496' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Socks, T- Shirts, Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178496' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178494' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Socks, T- Shirts, Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178494' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,794.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee (UNESWA)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723529' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '741' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '741' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,590.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 9)'
FROM tblmembers tm 
WHERE tm.MemberNo = '741' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '989' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,730.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1613' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178484' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,090.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178374' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,390.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178376' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,280.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178376' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,270.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178375' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,280.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178375' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Accommodation (Zambia)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178533' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 420.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Socks & Sneakers'
FROM tblmembers tm 
WHERE tm.MemberNo = '178531' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178531' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees( Grade 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178531' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 12,553.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,430.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Sneakers, Socks & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 11,325.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox ( Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1787' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('15/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,569.99 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Socks, Sneakers & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1787' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1612' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,372.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Medical Aid'
FROM tblmembers tm 
WHERE tm.MemberNo = '178398' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,420.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178455' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 970.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178415' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 990.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178388' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 980.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178387' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,640.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178387' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 8,890.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178507' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 15,270.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178554' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Trousers, Shirts & Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723520' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,209.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723520' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723499' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178555' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 540.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178555' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 850.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178555' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 7 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1571' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 7 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178415' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,210.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723400' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 16,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 12 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '593' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '593' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723504' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,050.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 3 Months )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723433' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1783' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,130.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1783' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1782' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,120.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1782' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 8,870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,050.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,059.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,610.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport (Year)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1566' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,503.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Exam Fee ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1566' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,060.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1566' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Upgrading Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723489' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money (3 Months)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723489' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,560.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 3 Months )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723489' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,020.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 636.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,039.70 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Trousers'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 150.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Iron'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,190.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Trunk Box'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723511' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723511' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 170.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723511' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 968.90 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag & Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723511' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 529.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723511' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723510' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723510' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 170.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723510' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 968.90 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag & Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723510' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 261.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723510' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 353.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 968.90 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag & Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 170.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723482' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723481' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723480' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 11,798.10 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723526' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '178515' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 11,975.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178515' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 990.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178402' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,160.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178402' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,010.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,430.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,230.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag, Shirts & Socks'
FROM tblmembers tm 
WHERE tm.MemberNo = '178541' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1569' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,020.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1569' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178446' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723432' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 880.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag & Trousers'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723435' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 16,515.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 11 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1795' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,273.40 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 3 Months)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1730' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 632.27 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Water Bill'
FROM tblmembers tm 
WHERE tm.MemberNo = '1644' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 470.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723538' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,330.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723538' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,620.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1744' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('17/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,530.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723438' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('17/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees'
FROM tblmembers tm 
WHERE tm.MemberNo = '1791' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('17/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag, Shoes & Socks'
FROM tblmembers tm 
WHERE tm.MemberNo = '1791' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 52.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '178456' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees (Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1769' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,620.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178459' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,067.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178459' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,620.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178458' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178458' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 11 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723411' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( School Fees, Grade 10 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178416' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1623' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1622' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Building Fund'
FROM tblmembers tm 
WHERE tm.MemberNo = '1622' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees  ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1622' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,230.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1678' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1678' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('20/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,010.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('21/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Interview  Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '178397' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('21/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,790.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178397' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Travelling Case'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,688.64 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723473' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,120.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723473' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178416' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178381' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178380' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 420.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery '
FROM tblmembers tm 
WHERE tm.MemberNo = '178380' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 650.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Belt'
FROM tblmembers tm 
WHERE tm.MemberNo = '178380' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees (Form 2)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1560' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 10,019.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 9 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723522' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Schoo Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723449' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,190.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178406' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178467' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178467' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,370.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,406.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178507' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,360.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1688' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '1743' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees (Form 2)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1743' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178390' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1589' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,169.70 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1589' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2025', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 540.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723434' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 960.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1613' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,390.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1613' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 170.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178498' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,220.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178498' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 170.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178497' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,270.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178497' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,924.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '377' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178356' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 680.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178357' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 690.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178357' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178357' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 840.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178355' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,920.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178355' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,150.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178355' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 260.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723438' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723425' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723424' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,662.95 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Top Up, Grade 9 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723424' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 410.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 310.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,310.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 360.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1278' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,070.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag,Trousers,Socks & Shirts )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1278' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1278' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1770' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1770' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan & Feb.)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1769' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 290.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1769' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,110.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1769' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 520.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178484' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 14,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 2, Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178397' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('22/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,550.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178397' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 75,029.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee ( EDUVOS )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178503' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 26,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723469' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( School Fees )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1757' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 100.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees'
FROM tblmembers tm 
WHERE tm.MemberNo = '178524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,449.30 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723411' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,350.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan. -  March )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723411' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 230.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723418' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 230.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723418' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723418' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 10,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1706' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 350.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lost Book ( Bible)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,700.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1660' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1660' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1660' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport & Lunchbox'
FROM tblmembers tm 
WHERE tm.MemberNo = '1775' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 790.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178464' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178464' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,503.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Extra Luggage'
FROM tblmembers tm 
WHERE tm.MemberNo = '178463' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 460.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tunic'
FROM tblmembers tm 
WHERE tm.MemberNo = '178397' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox ( Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723419' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,700.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723419' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178541' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,960.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723468' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,430.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1770' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178423' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178422' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178422' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723479' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 870.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723477' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 640.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Trousers '
FROM tblmembers tm 
WHERE tm.MemberNo = '178476' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 850.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Booklet Printing'
FROM tblmembers tm 
WHERE tm.MemberNo = '178475' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178474' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,335.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,060.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,730.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723504' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 940.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1680' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money ( Jan.- March )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178431' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money ( Jan.- March )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan. -  March )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,340.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1687' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,415.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 8 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1687' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,455.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723472' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 750.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1746' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,109.50 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1746' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,050.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1149' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,350.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178547' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178523' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 740.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178459' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178458' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 510.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178458' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 610.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1611' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,510.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1611' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,590.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178370' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 690.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723536' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('23/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 0 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723536' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees'
FROM tblmembers tm 
WHERE tm.MemberNo = '628' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 700.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178388' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox'
FROM tblmembers tm 
WHERE tm.MemberNo = '178388' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox'
FROM tblmembers tm 
WHERE tm.MemberNo = '178387' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '178387' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,250.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Trunk Box'
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 270.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 530.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Appliances ( Kettle & Iron )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 610.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Essentialls(Bedding,Towel,Busket , Basin)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 8,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '361' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,970.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178413' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,030.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178421' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178421' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '178488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,540.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723396' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,040.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723395' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '340' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('26/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 800.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '330' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('27/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 52.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('27/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 34.08 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723532' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('27/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 52.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '178503' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723490' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 200.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,640.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Fee '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,960.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178430' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,900.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178553' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 350.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Registration Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '178553' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,040.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178553' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 400.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178554' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 850.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1633' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,080.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '612' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '612' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,630.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '612' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 740.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money ( Jan- March)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,150.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Jan - March)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178453' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 240.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723441' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723441' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 480.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178508' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 480.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178507' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178357' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178356' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178355' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 720.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178354' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178354' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1777' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1776' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 790.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes, Bag & Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 5,030.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1774' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 920.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '377' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178549' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,000.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 950.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178455' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees'
FROM tblmembers tm 
WHERE tm.MemberNo = '178373' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 3,450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723505' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,230.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178541' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,060.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723448' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723448' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,591.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 450.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tunic'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 9,591.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723514' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 150.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178509' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 130.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178509' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms '
FROM tblmembers tm 
WHERE tm.MemberNo = '178509' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,050.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178509' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 4,840.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Registration Fee ( University of Western Cape )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1513' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1718' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('29/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,781.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1718' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('30/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'New Regular Payment'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723491' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('30/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'New Regular Payment'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723492' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('30/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'New Regular Payment'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723493' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('30/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 300.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'New Regular Payment'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723490' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 320.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '1680' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 7,109.70 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,586.63 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,850.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuck Shop ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '572' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Building Fund'
FROM tblmembers tm 
WHERE tm.MemberNo = '1680' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 6,500.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178553' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 1,600.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723474' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('31/01/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE(' 2,180.00 ', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723474' 
LIMIT 1;

COMMIT;

-- Verification
SELECT COUNT(*) AS adhoc_records_inserted 
FROM `tbltempadhocpayments` 
WHERE PaymentDate >= '2026-01-01';

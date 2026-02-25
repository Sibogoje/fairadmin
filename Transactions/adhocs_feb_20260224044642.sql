-- =====================================================
-- INSERT ADHOC TRANSACTIONS - FEB 2026
-- Generated: 2026-02-24 04:46:42
-- Total Records: 183
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2024', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723496' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2024', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723494' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('680', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('680', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1750', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('5000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1100', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Zondle'
FROM tblmembers tm 
WHERE tm.MemberNo = '1758' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1758' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('410', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Items For Practical Test '
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('450', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('390', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('340', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('390', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1693' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('340', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1693' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1693' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178414' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('8780', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade10 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178414' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1830', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178414' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('800', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723483' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('350', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( Registration Fee )'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('280', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money for Educational Trip'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('02/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2730', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178530' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1080', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Sports Track & School Shoes )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723521' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Tunic & T- Shirt'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 10)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1030' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('830', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shoes & Bag'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('480', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723403' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('480', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723403' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('4030', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Exam Fee ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723403' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('8450', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723403' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('4051', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2850', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2240', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1745' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('900', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1156.86', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('110', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Valentine & Fun Day'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Educational Trip'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Graduation Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3900', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Pre- School )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723528' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2310', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178486' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1150', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Jersey ( Sports Track )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1465', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('29309', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee (North West University )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('20779', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 11 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723470' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('7800', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Cellphone Purchase'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('690', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178504' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('5300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( School Fees )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178504' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('4200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees F2, 2025 )'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('840', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('840', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('840', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('840', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('840', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('140', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('130', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('395.03', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Practical Fees ( Computer Lab & Health Science )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('79.01', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Library Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('158.01', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Internet Connectivity'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('79.01', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Student Card'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('197.51', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Registration Fee'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('7900.51', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee (Zambia)'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('726.85', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Exam Fee'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('5925.38', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee (Zambia)'
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
  STR_TO_DATE('04/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('800', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('4155', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1593' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('250', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1734' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3800', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( School Fees Deposit)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1734' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1247', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees Balance ( F4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1734' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('9366.89', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund ( Accommodation & Admin, Acceptance Fee & Student Visa )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Cellphone Purchase'
FROM tblmembers tm 
WHERE tm.MemberNo = '178550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Calculator'
FROM tblmembers tm 
WHERE tm.MemberNo = '178550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1230', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
FROM tblmembers tm 
WHERE tm.MemberNo = '178550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('800', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
FROM tblmembers tm 
WHERE tm.MemberNo = '178550' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Cellphone Purchase'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('350', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
FROM tblmembers tm 
WHERE tm.MemberNo = '178450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '178450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1260', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '178450' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('890', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Shirts, Trouser & Shoes'
FROM tblmembers tm 
WHERE tm.MemberNo = '1661' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Term 1 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1661' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1287', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lost Book Replacement'
FROM tblmembers tm 
WHERE tm.MemberNo = '1661' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('5050', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1661' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Educational Trip'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Educational Trip'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('750', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('950', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School T- Shirt, Tunic & Skirt )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('750', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 3 )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Groceries'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('700', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries ( 3 Months)'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('610', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723519' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('150', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Trouser'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723517' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Building Fund'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723517' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2270', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 5 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723517' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('330', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees Balance F ,2025'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Educational Trip'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('648', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent ( UNESWA )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Driving Lessons ( Heavy Duty )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723486' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('7900', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee (BSA)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723486' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('17462', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 6 )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Lunchbox ( 2 Months )'
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
  STR_TO_DATE('05/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( 2 Months )'
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
  STR_TO_DATE('06/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1793' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('06/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport'
FROM tblmembers tm 
WHERE tm.MemberNo = '1792' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('440', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('250', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Wifi'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('230', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('5400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Rent (February)'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Sustenance Top Up'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Fuel & Toll Gates (Eswatini to JHB)'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Car Rental (2 Days)'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1899.89', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Bedding'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('920', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('190', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Bag'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('350', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723420' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1081', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees, Balance  (Form 5)'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1070', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723436' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1520', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('350', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('09/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('160', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Belt'
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
  STR_TO_DATE('10/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1870', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
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
  STR_TO_DATE('10/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('640', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Ingredients for Practical\'s'
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
  STR_TO_DATE('10/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1100', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Judo Classes (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1793' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('10/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1100', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Judo Classes (Term 1)'
FROM tblmembers tm 
WHERE tm.MemberNo = '1792' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Refund (Consultation Fee)'
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
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3745', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Eye Glasses'
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
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2103', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Exam Fee ( Form 3 )'
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
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('95.37', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
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
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('95.37', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '178534' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('11/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('23.4', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Forex Charges'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('720', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2609.4', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Pocket Money'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723515' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('270', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723515' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('260', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Raining Boots & Shirts'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723515' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('150', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Toiletries'
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('630', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'T- Shirt & Sneakers'
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('180', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3430', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Hostel Essentials'
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('800', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('10910', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4,Restart )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('12/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('60240', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tuition Fee ( EMERIS)'
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('7900', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees '
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('480', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School T- Shirt & Jersey'
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Sustenance Top Up'
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('189.79', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('650', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tracksuit'
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
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('6660', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 2 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178364' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('13/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3330', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1789' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('400', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport '
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2090', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '178400' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('4524', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Form 4 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723495' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('16/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('2250', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Cellphone Purchase'
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
  STR_TO_DATE('16/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Clothes'
FROM tblmembers tm 
WHERE tm.MemberNo = '178360' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('280', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Sneakers'
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
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('3500', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Driving Lessons ( Light Duty )'
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
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('450', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Tunic'
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
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('17650', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'School Fees ( Grade 11 )'
FROM tblmembers tm 
WHERE tm.MemberNo = '178544' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('6600', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Math Bootcamp'
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
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('468.8', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Stationery'
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
  STR_TO_DATE('18/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1590', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Uniforms'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723524' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('6300', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Laptop Purchase'
FROM tblmembers tm 
WHERE tm.MemberNo = '1723488' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1015', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Transport ( Feb & March )'
FROM tblmembers tm 
WHERE tm.MemberNo = '1626' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('900', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Educational Trip'
FROM tblmembers tm 
WHERE tm.MemberNo = '1626' 
LIMIT 1;

INSERT INTO `tbltempadhocpayments` (
  `MemberID`,
  `Name`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)
SELECT 
  tm.memberID,
  CONCAT(tm.MemberNo, '--', tm.MemberSurname, ' ', tm.MemberFirstname) AS Name,
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('1000', ' ', ''), ',', '') AS DECIMAL(12,2)),
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
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('190', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Balloons for Décor'
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
  STR_TO_DATE('19/02/2026', '%d/%m/%Y'),
  'Ad Hoc Payment',
  CAST(REPLACE(REPLACE('200', ' ', ''), ',', '') AS DECIMAL(12,2)),
  'Mathematics Workshop'
FROM tblmembers tm 
WHERE tm.MemberNo = '63' 
LIMIT 1;

COMMIT;

-- Verification
SELECT COUNT(*) AS adhoc_records_inserted 
FROM `tbltempadhocpayments` 
WHERE PaymentDate >= '2026-02-01';

<?php
/**
 * System Fix — Member statement print preview (A4).
 *
 * Emulates membership/profileaccountprint.php, but every figure comes from the
 * sandbox `transactionsfix` table instead of `tblmemberaccounts`/`balances`.
 * Nothing here writes to the database.
 */
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';
require_once 'scripts/access_control.php';

if (!isset($_SESSION['zid'])) {
    header('Location: ' . APP_URL . 'index.php');
    exit();
}

$role = access_control_normalize_role($_SESSION['role'] ?? '');
if (!access_control_is_allowed($role, '/system_fix_print.php')) {
    access_control_forbidden();
}

$ii = (int) ($_REQUEST['MemberID'] ?? 0);

$member = null;
$balance = null;
$rows = [];

if ($ii > 0) {
    $stmt = $conn->prepare(
        "SELECT `MemberNo`, `MemberSurname`, `MemberFirstname`, `MemberIDnumber`, `DateOfBirth`,
                `DateAccountOpened`, `MemberPostalAddress`, `ApprovedBenefit`, `Terminated`, `FundName`
         FROM `memberz2` WHERE `MemberID` = ? LIMIT 1"
    );
    $stmt->bind_param('i', $ii);
    $stmt->execute();
    $member = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    $stmt = $conn->prepare(
        "SELECT `NewBalance` FROM `transactionsfix`
         WHERE `memberID` = ?
         ORDER BY `TransactionDate` DESC, `accountsID` DESC LIMIT 1"
    );
    $stmt->bind_param('i', $ii);
    $stmt->execute();
    $balance = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();

    $stmt = $conn->prepare(
        "SELECT `accountsID`, `TransactionDate`, `TransactionTypeID`, `memberID`, `Details`,
                `Credit`, `StartingBalance`, `Amount`, `NewBalance`, `Comments`
         FROM `transactionsfix`
         WHERE `memberID` = ?
         ORDER BY `TransactionDate` DESC, `accountsID` DESC"
    );
    $stmt->bind_param('i', $ii);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Statement <?= htmlspecialchars($member['MemberNo'] ?? '') ?></title>

  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">

  <style>
    footer {
      font-size: 14px;
      color: black;
      text-align: center;
    }

    @page {
      size: A4;
      margin: 11mm 17mm 17mm 17mm;
    }

    .table {
      font-family: 'Arial';
    }

    #bottom {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hed {
      padding: 10px;
    }

    /* Screen preview */
    .sheet {
      max-width: 210mm;
      margin: 0 auto;
      padding: 10mm 17mm;
      background: #fff;
    }

    .screen-toolbar {
      position: sticky;
      top: 0;
      z-index: 10;
      background: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 10px 17mm;
    }

    @media print {
      .screen-toolbar {
        display: none !important;
      }

      .sheet {
        padding: 0;
        max-width: none;
      }

      footer {
        position: fixed;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .content-block, p {
        page-break-inside: avoid;
      }

      html, body {
        width: 210mm;
        height: 297mm;
      }

      html {
        font-size: 16px;
      }

      .mains {
        border: 1px solid black;
        border-collapse: collapse;
      }

      .inside {
        font-weight: normal;
      }

      tr {
        border: 1px solid black;
      }

      table, tr, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: left;
        padding: 5px;
      }
    }

    @media print {
      @page {
        margin-top: 0;
        margin-bottom: 0;
      }

      body {
        padding-top: 72px;
        padding-bottom: 79px;
      }
    }

    @media print {
      html {
        zoom: 100%;
      }
    }
  </style>
</head>

<body class="bg-light">

  <div class="screen-toolbar d-flex justify-content-between align-items-center">
    <div>
      <strong>Statement Preview</strong>
      <span class="text-muted small ms-2">A4 · from <code>transactionsfix</code></span>
    </div>
    <div>
      <button type="button" class="btn btn-primary btn-sm" onclick="window.print();">
        <i class="bi bi-printer"></i> Print
      </button>
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.close();">
        Close
      </button>
    </div>
  </div>

  <div class="sheet">
<?php if ($member === null): ?>
    <div class="alert alert-danger mt-4">No member details found for that member.</div>
<?php else: ?>
    <table class="table mains" id="free">
      <thead>
        <tr>
          <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
        </tr>
        <tr style="text-align: center; background: black; color: white;">
          <th scope="col" colspan="6">MEMBER DETAILS</th>
        </tr>
        <tr>
          <th scope="col" colspan="2" style="vertical-align: top;">Full Name: <span class="inside"><?= htmlspecialchars($member['MemberFirstname'] . ' ' . $member['MemberSurname']) ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">MemberNo: <span class="inside"><?= htmlspecialchars((string) $member['MemberNo']) ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">National ID: <span class="inside"><?= htmlspecialchars((string) $member['MemberIDnumber']) ?></span></th>
        </tr>
        <tr>
          <th scope="col" colspan="2" style="vertical-align: top;">Date of Birth: <span class="inside"><?= htmlspecialchars((string) $member['DateOfBirth']) ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">Account Opened: <span class="inside"><?= htmlspecialchars((string) $member['DateAccountOpened']) ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">Postal Address: <span class="inside"><?= htmlspecialchars((string) $member['MemberPostalAddress']) ?></span></th>
        </tr>
        <tr>
          <th scope="col" colspan="2" style="vertical-align: top;">Approved Benefit: <span class="inside"><?= htmlspecialchars((string) $member['ApprovedBenefit']) ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">Active: <span class="inside"><?= ((string) $member['Terminated'] === '0') ? 'Yes' : 'No' ?></span></th>
          <th scope="col" colspan="2" style="vertical-align: top;">Balance: <span class="inside"><?= $balance ? 'E ' . htmlspecialchars(number_format((float) $balance['NewBalance'], 2)) : 'No Balance Retrieved' ?></span></th>
        </tr>
        <tr style="text-align: center; background: black; color: white;">
          <th scope="col" style="text-align: center;" colspan="6">Transaction Statement</th>
        </tr>
      </thead>
    </table>

<?php if (!empty($rows)): ?>
    <table class="table" id="free">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">TransactionDate</th>
          <th scope="col">Details</th>
          <th scope="col">Type</th>
          <th scope="col">Comments</th>
          <th scope="col">Prev balance</th>
          <th scope="col">Amount</th>
          <th scope="col">NewBalance</th>
        </tr>
      </thead>
      <tbody>
<?php $add = 1; ?>
<?php foreach ($rows as $row): ?>
<?php $tyes = ((string) $row['Credit'] === '1') ? 'Credit' : 'Debit'; ?>
        <tr>
          <th scope="row"><?= $add ?></th>
          <th scope="row"><?= htmlspecialchars((string) $row['TransactionDate']) ?></th>
          <td><?= htmlspecialchars((string) $row['Details']) ?></td>
          <td><?= htmlspecialchars($tyes) ?></td>
          <td><?= htmlspecialchars((string) $row['Comments']) ?></td>
          <td><?= htmlspecialchars(number_format((float) $row['StartingBalance'], 2)) ?></td>
          <td><?= $tyes === 'Credit'
              ? htmlspecialchars(number_format((float) $row['Amount'], 2))
              : '-' . htmlspecialchars(number_format((float) $row['Amount'], 2)) ?></td>
          <td><?= htmlspecialchars(number_format((float) $row['NewBalance'], 2)) ?></td>
        </tr>
<?php $add++; ?>
<?php endforeach; ?>
      </tbody>
    </table>
<?php else: ?>
    <p class="text-muted">No transactions found in <code>transactionsfix</code> for this member.</p>
<?php endif; ?>
<?php endif; ?>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>

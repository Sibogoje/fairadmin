<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';

$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';
$fundId = $_POST['fund_id'] ?? 'all';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

if ($from === '' || $to === '') {
    echo '<div class="alert alert-warning mt-3">Please choose both dates.</div>';
    exit;
}

if ($from > $to) {
    echo '<div class="alert alert-warning mt-3">The start date cannot be after the end date.</div>';
    exit;
}

$sql = "
    SELECT
        f.RetirementFundID,
        f.FundName,
        COUNT(DISTINCT m.MemberID) AS TotalMembers,
        SUM(
            COALESCE(
                (
                    SELECT ma_before.NewBalance
                    FROM tblmemberaccounts AS ma_before
                    WHERE ma_before.memberID = m.MemberID
                      AND DATE(ma_before.TransactionDate) < ?
                    ORDER BY DATE(ma_before.TransactionDate) DESC, ma_before.accountsID DESC
                    LIMIT 1
                ),
                (
                    SELECT ma_first.StartingBalance
                    FROM tblmemberaccounts AS ma_first
                    WHERE ma_first.memberID = m.MemberID
                      AND DATE(ma_first.TransactionDate) BETWEEN ? AND ?
                    ORDER BY DATE(ma_first.TransactionDate) ASC, ma_first.accountsID ASC
                    LIMIT 1
                ),
                0
            )
        ) AS OpeningBalance,
        SUM(
            COALESCE(
                (
                    SELECT ma_close.NewBalance
                    FROM tblmemberaccounts AS ma_close
                    WHERE ma_close.memberID = m.MemberID
                      AND DATE(ma_close.TransactionDate) <= ?
                    ORDER BY DATE(ma_close.TransactionDate) DESC, ma_close.accountsID DESC
                    LIMIT 1
                ),
                0
            )
        ) AS ClosingBalance
    FROM tblretirementfunds AS f
    LEFT JOIN tbldeceased AS d ON d.RetirementFundID = f.RetirementFundID
    LEFT JOIN tblmembers AS m ON m.DeceasedID = d.DeceasedID
    WHERE m.MemberID IS NOT NULL
      AND EXISTS (
            SELECT 1
            FROM tblmemberaccounts AS ma_exists
            WHERE ma_exists.memberID = m.MemberID
              AND DATE(ma_exists.TransactionDate) <= ?
      )
";

$types = 'sssss';
$params = [$from, $from, $to, $to, $to];

if ($fundId !== 'all') {
    $sql .= ' AND f.RetirementFundID = ?';
    $types .= 's';
    $params[] = $fundId;
}

$sql .= '
    GROUP BY f.RetirementFundID, f.FundName
    ORDER BY f.FundName ASC
';

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo '<div class="alert alert-danger mt-3">Failed to prepare fund summary query.</div>';
    exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-info mt-3">No fund summary found for the selected period.</div>';
    exit;
}

$totalOpening = 0;
$totalClosing = 0;
$totalMembers = 0;
?>
<div class="table-responsive mt-3">
  <table class="table table-striped table-bordered align-middle">
    <thead>
      <tr style="text-align:center; background:black; color:white;">
        <th>Fund</th>
        <th>Opening Balance</th>
        <th>Closing Balance</th>
        <th>Total Members</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()) {
    $openingBalance = (float)($row['OpeningBalance'] ?? 0);
    $closingBalance = (float)($row['ClosingBalance'] ?? 0);
    $memberCount = (int)($row['TotalMembers'] ?? 0);
    $totalOpening += $openingBalance;
    $totalClosing += $closingBalance;
    $totalMembers += $memberCount;
?>
      <tr>
        <td><?php echo htmlspecialchars($row['FundName']); ?></td>
        <td><?php echo 'E ' . number_format($openingBalance, 2); ?></td>
        <td><?php echo 'E ' . number_format($closingBalance, 2); ?></td>
        <td><?php echo number_format($memberCount); ?></td>
      </tr>
      <?php } ?>
      <tr style="font-weight: bold; background: #f6f9ff;">
        <td>Total</td>
        <td><?php echo 'E ' . number_format($totalOpening, 2); ?></td>
        <td><?php echo 'E ' . number_format($totalClosing, 2); ?></td>
        <td><?php echo number_format($totalMembers); ?></td>
      </tr>
    </tbody>
  </table>
</div>
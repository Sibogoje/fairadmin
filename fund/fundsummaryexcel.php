<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';
require_once __DIR__ . '/fundsummary_data.php';

$request = fund_summary_request_from_post();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

if ($request['from'] === '' || $request['to'] === '' || $request['fund_id'] === '' || $request['from'] > $request['to']) {
    echo 'Invalid export request.';
    exit;
}

$resultData = fund_summary_fetch($conn, $request['from'], $request['to'], $request['fund_id']);
if ($resultData['statement'] === false) {
    echo 'Failed to prepare export.';
    exit;
}

$result = $resultData['result'];
if ($result->num_rows === 0) {
    echo 'No fund summary found for the selected period.';
    exit;
}

$filename = 'funds-summary-' . date('Y-m-d') . '.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$totalOpening = 0;
$totalClosing = 0;
$totalMembers = 0;
?>
<table border="1">
  <thead>
    <tr>
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
      <td><?php echo number_format($openingBalance, 2, '.', ''); ?></td>
      <td><?php echo number_format($closingBalance, 2, '.', ''); ?></td>
      <td><?php echo $memberCount; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>Total</td>
      <td><?php echo number_format($totalOpening, 2, '.', ''); ?></td>
      <td><?php echo number_format($totalClosing, 2, '.', ''); ?></td>
      <td><?php echo $totalMembers; ?></td>
    </tr>
  </tbody>
</table>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once '../scripts/connection.php';
require_once __DIR__ . '/fundsummary_data.php';

$request = fund_summary_request_from_post();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

if ($request['from'] === '' || $request['to'] === '') {
    echo '<div class="alert alert-warning mt-3">Please choose both dates.</div>';
    exit;
}

if ($request['from'] > $request['to']) {
    echo '<div class="alert alert-warning mt-3">The start date cannot be after the end date.</div>';
    exit;
}

$resultData = fund_summary_fetch($conn, $request['from'], $request['to'], $request['fund_id']);

if ($resultData['statement'] === false) {
    echo '<div class="alert alert-danger mt-3">Failed to prepare fund summary query.</div>';
    exit;
}

$result = $resultData['result'];

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
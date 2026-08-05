<?php
require_once __DIR__ . '/scripts/bootstrap.php';
session_start();
require_once 'scripts/connection.php';

if (!isset($_SESSION['zid'])) {
    header('Location: ' . APP_URL . 'index.php');
    exit();
}

$gg = $_SESSION['user'];
$result = $conn->query("SELECT `username`, `action`, `details`, `route`, `ip_address`, `created_at` FROM `audit_trail` ORDER BY `created_at` DESC, `id` DESC LIMIT 200");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Audit Trail</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Audit Trail</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dash.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Audit Trail</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Recent Activity</h5>
        <div class="table-responsive">
          <table class="table table-striped datatable nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th>Time</th>
                <th>Username</th>
                <th>Action</th>
                <th>Details</th>
                <th>Route</th>
                <th>IP Address</th>
              </tr>
            </thead>
            <tbody>
<?php if ($result && $result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['action']); ?></td>
                <td><?php echo htmlspecialchars($row['details'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['route'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address'] ?? ''); ?></td>
              </tr>
<?php endwhile; ?>
<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
</body>
</html>
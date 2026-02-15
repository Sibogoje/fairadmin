<!DOCTYPE html>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once __DIR__ . '/../scripts/access_control.php';
session_start();

if (!isset($_SESSION['zid'])) {
    header('Location: ' . app_url('index.php'));
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    access_control_forbidden();
}

$gg = $_SESSION['user'] ?? 'admin';
require_once __DIR__ . '/../scripts/connection.php';

$message = '';
$messageType = 'success';

if (empty($_SESSION['access_control_csrf'])) {
    $_SESSION['access_control_csrf'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['access_control_csrf'], $token)) {
        $message = 'Invalid request token. Please refresh and try again.';
        $messageType = 'danger';
    } else {
        $submitted = $_POST['permissions'] ?? [];
        list($ok, $msg) = access_control_save_overrides(is_array($submitted) ? $submitted : [], $gg);
        $message = $msg;
        $messageType = $ok ? 'success' : 'danger';
    }
}

$definitions = access_control_permission_definitions();
$effectiveRoles = access_control_effective_role_map();
$knownRoles = access_control_known_roles();
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Access Control Manager</title>

  <link href="<?= app_url('logo.png') ?>" rel="icon">
  <link href="<?= app_url('logo.png') ?>" rel="apple-touch-icon">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Access Control Manager</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= app_url('dash.php') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Access Control</li>
      </ol>
    </nav>
  </div>

  <?php if ($message !== ''): ?>
    <div class="alert alert-<?= $messageType ?>" role="alert">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Role Permissions by Page Group</h5>
        <p class="small text-muted">Choose which account types can access each module group. Changes apply immediately.</p>

        <form method="post" action="">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['access_control_csrf']) ?>">

          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>Permission Group</th>
                  <?php foreach ($knownRoles as $r): ?>
                    <th class="text-center"><?= htmlspecialchars($r) ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($definitions as $key => $definition): ?>
                  <?php $selectedRoles = $effectiveRoles[$key] ?? $definition['default_roles']; ?>
                  <tr>
                    <td>
                      <strong><?= htmlspecialchars($definition['label']) ?></strong>
                    </td>
                    <?php foreach ($knownRoles as $r): ?>
                      <td class="text-center">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          name="permissions[<?= htmlspecialchars($key) ?>][]"
                          value="<?= htmlspecialchars($r) ?>"
                          <?= in_array($r, $selectedRoles, true) ? 'checked' : '' ?>
                        >
                      </td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Access Rules</button>
            <a href="<?= app_url('dash.php') ?>" class="btn btn-secondary">Back</a>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>

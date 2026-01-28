  <!DOCTYPE html>
  <?php
  session_start();
  require_once 'scripts/connection.php';
  ?>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fairlife — Login</title>

    <link href="logo.png" rel="icon">

    <!-- Fonts & Vendor CSS -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,600,700&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Project CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">

  </head>

  <body class="bg-light">

    <main class="d-flex vh-100 align-items-center justify-content-center">
      <div class="card shadow-sm w-100" style="max-width:420px">
        <div class="card-body p-4">
          <div class="text-center mb-3">
            <img src="logo.png" alt="FairLife" class="login-logo mb-2">
            <h4 class="mb-0">FairLife Benefit Services</h4>
            <small class="text-muted">Sign in to continue</small>
          </div>

          <div id="alert-placeholder"></div>

          <form id="loginform" method="post" action="parse.php" novalidate>
            <div class="mb-3 form-floating">
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="username" required>
              <label for="username">Username</label>
            </div>

            <div class="mb-3 form-floating">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="current-password" required>
              <label for="password">Password</label>
            </div>

            <div class="d-grid">
              <button type="submit" id="loginBtn" class="btn btn-warning">Login</button>
            </div>
          </form>

        </div>
      </div>
    </main>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Main + Page JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/login.js"></script>

  </body>

  </html>
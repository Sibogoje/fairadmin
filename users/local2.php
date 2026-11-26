<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
require_once __DIR__ . '/../scripts/access_control.php';
require_once __DIR__ . '/../scripts/audit.php';
session_start();

if (!isset($_SESSION['zid'])) {
  header('Location: ' . app_url('index.php'));
  exit();
}

$routePath = access_control_route_path();
if (!access_control_is_allowed($_SESSION['role'] ?? '', $routePath)) {
  access_control_forbidden();
}

if(isset($_SESSION['zid'])) {
    $gg = $_SESSION['user'];
    $role = $_SESSION['role'];
    require_once '../scripts/connection.php';

    // Roles an admin can assign, with the labels shown in the forms.
    $roleLabels = [
        'admin'      => 'System Admin',
        'clerk'      => 'Office Admin',
        'Operations' => 'Operations Officer',
        'Accounts'   => 'Accountant',
        'Reports'    => 'Reports Viewer',
    ];
    $roleLabels = array_intersect_key($roleLabels, array_flip(access_control_known_roles()));

    $currentUserId = (string)($_SESSION['xid'] ?? '');

    // Post/Redirect/Get: store a message, redirect back to this page, show it once.
    $finish = function ($type, $message) {
        $_SESSION['users_flash'] = ['type' => $type, 'message' => $message];
        header('Location: local2.php');
        exit();
    };

    $usernameTaken = function ($username, $exceptId = null) use ($conn) {
        $sql = "SELECT `id` FROM `realuzer` WHERE `username` = ?" . ($exceptId !== null ? " AND `id` <> ?" : "") . " LIMIT 1";
        $stmt = $conn->prepare($sql);
        if ($exceptId !== null) {
            $stmt->bind_param('ss', $username, $exceptId);
        } else {
            $stmt->bind_param('s', $username);
        }
        $stmt->execute();
        $taken = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $taken;
    };

    $validUsername = function ($username) {
        return (bool)preg_match('/^[A-Za-z0-9._-]{3,20}$/', $username);
    };

    // Add new user
    if (isset($_POST['savenew'])) {
        $username = trim((string)($_POST['addusername'] ?? ''));
        $password = (string)($_POST['addpassword'] ?? '');
        $confirm  = (string)($_POST['addpassword2'] ?? '');
        $newRole  = access_control_normalize_role($_POST['addrole'] ?? '');

        if ($username === '' || $password === '' || $newRole === '') {
            $finish('danger', 'All fields are required.');
        }
        if (!$validUsername($username)) {
            $finish('danger', 'Username must be 3-20 characters: letters, numbers, dot, dash or underscore.');
        }
        if (!isset($roleLabels[$newRole])) {
            $finish('danger', 'Please choose a valid role.');
        }
        if (strlen($password) < 8) {
            $finish('danger', 'Password must be at least 8 characters.');
        }
        if ($password !== $confirm) {
            $finish('danger', 'The two passwords do not match.');
        }
        if ($usernameTaken($username)) {
            $finish('danger', 'A user named "' . $username . '" already exists.');
        }

        // md5 matches what parse.php checks at login. The password counts as
        // expired, so the new user must choose their own at first login.
        $hash = md5($password);
        $expiredMarker = PASSWORD_EXPIRED_MARKER;
        $stmt = session_guard_prepare($conn, "INSERT INTO `realuzer` (`username`, `password`, `role`, `password_changed_at`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $hash, $newRole, $expiredMarker);
        if ($stmt->execute()) {
            audit_trail_log($conn, 'user_create', 'Created user ' . $username . ' with role ' . $newRole);
            $finish('success', 'User "' . $username . '" created as ' . $roleLabels[$newRole] . '. They will be asked to choose their own password when they first log in.');
        }
        error_log('User create failed: ' . $conn->error);
        $finish('danger', 'Could not create the user. Please try again.');
    }

    // Edit existing user
    if (isset($_POST['edit'])) {
        $id       = trim((string)($_POST['id'] ?? ''));
        $username = trim((string)($_POST['username'] ?? ''));
        $newRole  = access_control_normalize_role($_POST['role'] ?? '');

        if ($id === '' || $username === '' || $newRole === '') {
            $finish('danger', 'All fields are required.');
        }
        if (!$validUsername($username)) {
            $finish('danger', 'Username must be 3-20 characters: letters, numbers, dot, dash or underscore.');
        }
        if (!isset($roleLabels[$newRole])) {
            $finish('danger', 'Please choose a valid role.');
        }
        if ($id === $currentUserId && $newRole !== 'admin') {
            $finish('danger', 'You cannot remove your own admin role. Ask another admin to change it.');
        }
        if ($usernameTaken($username, $id)) {
            $finish('danger', 'A user named "' . $username . '" already exists.');
        }

        $sql = "UPDATE `realuzer` SET `username` = ?, `role` = ?";
        $params = [$username, $newRole];
        $passwordChanged = !empty($_POST['change_password']) && (string)($_POST['password'] ?? '') !== '';
        if ($passwordChanged) {
            if (strlen((string)$_POST['password']) < 8) {
                $finish('danger', 'Password must be at least 8 characters.');
            }
            // Reset by an admin: the user must choose their own at next login.
            $sql .= ", `password` = ?, `password_changed_at` = ?";
            $params[] = md5((string)$_POST['password']);
            $params[] = PASSWORD_EXPIRED_MARKER;
        }
        $sql .= " WHERE `id` = ?";
        $params[] = $id;

        $stmt = session_guard_prepare($conn, $sql);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        if ($stmt->execute()) {
            audit_trail_log($conn, 'user_update', 'Updated user ' . $username . ' (ID ' . $id . ') with role ' . $newRole . ($passwordChanged ? ', password changed' : ''));
            $finish('success', 'User "' . $username . '" updated.' . ($passwordChanged ? ' They will be asked to choose a new password at next login.' : ''));
        }
        error_log('User update failed: ' . $conn->error);
        $finish('danger', 'Could not update the user. Please try again.');
    }

    // Delete user
    if (isset($_POST['delete'])) {
        $id = trim((string)($_POST['id'] ?? ''));
        if ($id === '') {
            $finish('danger', 'No user selected.');
        }
        if ($id === $currentUserId) {
            $finish('danger', 'You cannot delete your own account.');
        }
        $stmt = $conn->prepare("SELECT `username` FROM `realuzer` WHERE `id` = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $target = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$target) {
            $finish('danger', 'That user no longer exists.');
        }
        $stmt = $conn->prepare("DELETE FROM `realuzer` WHERE `id` = ?");
        $stmt->bind_param('s', $id);
        if ($stmt->execute()) {
            audit_trail_log($conn, 'user_delete', 'Deleted user ' . $target['username'] . ' (ID ' . $id . ')');
            $finish('success', 'User "' . $target['username'] . '" deleted.');
        }
        error_log('User delete failed: ' . $conn->error);
        $finish('danger', 'Could not delete the user. Please try again.');
    }

    $flash = $_SESSION['users_flash'] ?? null;
    unset($_SESSION['users_flash']);
} else {
  header('Location: ' . app_url('index.php'));
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Local Users</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>


      

  <!-- Favicons -->
  <link href="<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo APP_URL; ?>/logo.png" rel="icon">
  <link href="<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo APP_URL; ?>logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

      

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
<style>
html {
    -webkit-transition: background-color 1s;
    transition: background-color 1s;
}
html, body {
    /* For the loading indicator to be vertically centered ensure */
    /* the html and body elements take up the full viewport */
    min-height: 100%;
}
.loading {
    /* Replace #333 with the background-color of your choice */
    /* Replace loading.gif with the loading image of your choice */
    background: rgba(0,0,0,0.8) url('progress.gif') no-repeat 50% 10%;
	margin: auto;
	position: fixed;

    /* Ensures that the transition only runs in one direction */
    -webkit-transition: background-color 0;
    transition: background-color 0.7;
}
body {
    -webkit-transition: opacity 1s ease-in;
    transition: opacity 1s ease-in;
}
html.loading body {
    /* Make the contents of the body opaque during loading */
    opacity: 0.5;

    /* Ensures that the transition only runs in one direction */
    -webkit-transition: opacity 0.5;
    transition: opacity 0.5;
}
</style>

  <script src="https://cdn.ckeditor.com/4.18.0/standard-all/ckeditor.js"></script>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Local Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form class="row g-3" method="post" action="" autocomplete="off">
			    <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="id" name="id" id="use" autocomplete="new-text" required readonly>
                </div>
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Username" name="username" id="username" autocomplete="new-text" required>
                </div>
				<div class="col-md-12">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="changePasswordCheckbox" name="change_password">
                    <label class="form-check-label" for="changePasswordCheckbox">
                      Change Password
                    </label>
                  </div>
                </div>
			<div class="col-md-12" id="passwordField" style="display:none;">
                  <input type="password" class="form-control" placeholder="Password" autocomplete="new-password" id="password" name="password" >
                </div>
            
                <div class="col-md-12">
                  <label for="role" class="form-label small mb-1">Role</label>
                  <select class="form-select" name="role" id="role">
<?php foreach ($roleLabels as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
<?php endforeach; ?>
                  </select>
                </div>


                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="edit">Save</button>
                  <button type="submit" class="btn btn-outline-danger" name="delete" id="delete" formnovalidate onclick="return confirm('Delete this user? They will no longer be able to log in. This cannot be undone.');">Delete</button>
				  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </form><!-- End No Labels Form -->
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form class="row g-3" method="post" action="local2.php" autocomplete="off" id="addUserForm">
                <div class="col-md-12">
                  <label for="addusername" class="form-label small mb-1">Username</label>
                  <input type="text" class="form-control" name="addusername" id="addusername" autocomplete="off" required minlength="3" maxlength="20" pattern="[A-Za-z0-9._\-]{3,20}" title="3-20 characters: letters, numbers, dot, dash or underscore">
                </div>
                <div class="col-md-6">
                  <label for="addpassword" class="form-label small mb-1">Password</label>
                  <input type="password" class="form-control" name="addpassword" id="addpassword" autocomplete="new-password" required minlength="8">
                </div>
                <div class="col-md-6">
                  <label for="addpassword2" class="form-label small mb-1">Confirm password</label>
                  <input type="password" class="form-control" name="addpassword2" id="addpassword2" autocomplete="new-password" required minlength="8">
                </div>
                <div class="col-md-12">
                  <label for="addrole" class="form-label small mb-1">Role</label>
                  <select class="form-select" name="addrole" id="addrole" required>
                    <option value="" selected disabled>Choose a role</option>
<?php foreach ($roleLabels as $value => $label): ?>
                    <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
<?php endforeach; ?>
                  </select>
                  <div class="form-text">What each role can open is set in Access Control Manager.</div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="savenew"><i class="bi bi-person-plus"></i> Create user</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>

 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
<?php if (!empty($flash)): ?>
              <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show mt-3" role="alert">
                <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
<?php endif; ?>
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">System Users</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-person-plus"></i> Add User</button>
              </div>

  <div class="table-responsive"> 
              <!-- Table with stripped rows -->
              <table class="table datatable" id="jj">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    <th scope="col">Last Login</th>
				
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
// Last login comes from the audit trail (stored in UTC), because realuzer.last_login
// held logout times before parse.php started recording logins.
$stmt = $conn->prepare("SELECT u.*, (SELECT MAX(a.`created_at`) FROM `audit_trail` a WHERE a.`username` = u.`username` AND a.`action` = 'login') AS `audit_last_login` FROM `realuzer` u");
$sast = new DateTimeZone('Africa/Johannesburg');
// Mark anyone idle past the timeout offline first, so the Online column is current.
session_guard_sweep($conn);
$onlineCutoff = session_guard_now()->modify('-' . SESSION_IDLE_TIMEOUT . ' seconds');

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

$uid = $row['id'];
?>
                  <tr>
                    <th scope="row"><?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['id']; ?></th>
                    <td><a href="../audit_log.php?user=<?php echo urlencode($row['username']); ?>" title="View activity logs for this user"><?php
echo htmlspecialchars($row['username']); ?></a></td>
                    <td><?php echo htmlspecialchars($roleLabels[access_control_normalize_role($row['role'])] ?? $row['role']); ?></td>
                    <td><?php
// Online = flagged as logged in AND made a request within the idle timeout.
$seenRecently = !empty($row['last_seen'])
    && (new DateTime($row['last_seen'], $sast)) >= $onlineCutoff;
if ((int)$row['session'] === 1 && $seenRecently): ?>
                      <span class="badge bg-success">Online</span>
<?php else: ?>
                      <span class="badge bg-secondary">Offline</span>
<?php endif; ?></td>
                    <td><?php
if (!empty($row['audit_last_login'])) {
    $lastLogin = new DateTime($row['audit_last_login'], new DateTimeZone('UTC'));
    echo htmlspecialchars($lastLogin->setTimezone($sast)->format('Y-m-d H:i'));
} else {
    echo '<span class="text-muted">&mdash;</span>';
} ?></td>

					<td>
			<button type="button" data-link="edit.php?id=<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['id']; ?>" class="btn btn-outline-primary edit"  title="Edit" 
			data-id="<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
echo $row['id']; ?>"
			data-username="<?php echo htmlspecialchars($row['username']); ?>"
			data-role="<?php echo htmlspecialchars(access_control_normalize_role($row['role'])); ?>"
			data-bs-toggle="modal" data-bs-target="#editUserModal"
			><i class="bi bi-eye-fill"></i></button>
			<a href="../audit_log.php?user=<?php echo urlencode($row['username']); ?>" class="btn btn-outline-secondary" title="View logs"><i class="bi bi-journal-text"></i></a>
		
  
					</td>
                  </tr>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
}
} else {
 // echo "No Employees Found";
} ?>                 
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
             </div>
          </div>

        </div>
      </div>
    </section>
    
    
    
    

<!-- end of new beneficiary form -->
 
  </main><!-- End #main -->

  <!-- ======= Footer ======= -
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ --
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  


  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
<script>
// Populate edit modal with user's information
$('.edit').click(function() {
    var id = $(this).data("id");
    var username = $(this).data("username");
    var role = $(this).data("role");

    $('#username').val(username);
    $('#use').val(id);

    // Set the selected option based on the role value
    $('#role').val(role).change();

    // Reset password checkbox and field
    $('#changePasswordCheckbox').prop('checked', false);
    $('#passwordField').hide();
    $('#password').val('');
});

// Add User: make sure both passwords match before submitting
$('#addUserForm').on('submit', function(e) {
    if ($('#addpassword').val() !== $('#addpassword2').val()) {
        e.preventDefault();
        alert('The two passwords do not match.');
        $('#addpassword2').focus();
    }
});

// Toggle password field visibility based on checkbox
$('#changePasswordCheckbox').change(function() {
    if ($(this).is(':checked')) {
        $('#passwordField').show();
        $('#password').focus();
    } else {
        $('#passwordField').hide();
        $('#password').val('');
    }
});
</script>  
  
  



</body>

</html>

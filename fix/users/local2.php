<!DOCTYPE html>
<?php
session_start();

// Check if user is logged in
if(isset($_SESSION['zid']) && $_SESSION['role'] == 'admin') {
    $gg = $_SESSION['user'];
    $role = $_SESSION['role'];
    require_once '../scripts/connection.php';

    // Add new user
    if(isset($_POST['savenew'])) {
        // Check if all required fields are filled
        if(empty($_POST['addusername']) || empty($_POST['addpassword']) || empty($_POST['addrole'])) {
            echo "<script>alert('All fields are required');</script>";
        } else {
            // Sanitize input to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['addusername']);
            $password = md5($_POST['addpassword']); // Note: md5 is not a secure way to hash passwords, consider using bcrypt or argon2
            $role = mysqli_real_escape_string($conn, $_POST['addrole']);

            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO `realuzer` (`username`, `password`, `role`) VALUES (?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("sss", $username, $password, $role);

            // Execute the statement
            if($stmt->execute()) {
                // Display an alert if insertion was successful
                echo "<script>alert('Data inserted successfully');</script>";
                echo "<script>window.location.href = 'local.php';</script>";
                exit();
            } else {
                // Display an alert if insertion failed
                echo "<script>alert('Failed to insert data: " . $conn->error . "');</script>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
    }

    // Edit existing user
   if(isset($_POST['edit'])) {
        // Check if all required fields are filled
        if(empty($_POST['username']) || empty($_POST['role'])) {
            echo "<script>alert('All fields are required');</script>";
        } else {
            // Sanitize input to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $role = mysqli_real_escape_string($conn, $_POST['role']);
            $id = mysqli_real_escape_string($conn, $_POST['id']);

            // Prepare the SQL statement
            $sql = "UPDATE `realuzer` SET `username` = ?, `role` = ?";
            $params = array($username, $role);

            // Check if a new password is provided
            if(!empty($_POST['password'])) {
                $password = md5($_POST['password']); // Note: md5 is not a secure way to hash passwords, consider using bcrypt or argon2
                $sql .= ", `password` = ?";
                $params[] = $password;
            }

            $sql .= " WHERE `id` = ?";
            $params[] = $id;

            // Prepare the SQL statement
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $types = str_repeat("s", count($params)); // Determine parameter types dynamically
            $stmt->bind_param($types, ...$params);

            // Execute the statement
            if($stmt->execute()) {
                // Display an alert if update was successful
                echo "<script>alert('Data updated successfully');</script>";
                echo "<script>window.location.href = 'local.php';</script>";
                exit();
            } else {
                // Display an alert if update failed
                echo "<script>alert('Failed to update data: " . $conn->error . "');</script>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
    }
} else {
    // Redirect to login page if user is not logged in
    header('Location: https://fair.liquag.com/logout.php');
}
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Local Users</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>


      

  <!-- Favicons -->
  <link href="https://fair.liquag.com//logo.png" rel="icon">
  <link href="https://fair.liquag.com/logo.png" rel="apple-touch-icon">

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
  <?php include '../header.php'; ?>

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
<!-- New beneficiary form-->
		  
<!-- New User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="" autocomplete="off">
                   
			    <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="id" name="addid" id="adduse" autocomplete="new-text" required readonly>
                </div>
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Username" name="addusername" id="addusername" autocomplete="new-text" required>
                </div>
				<div class="col-md-12">
                  <input type="password" class="form-control" placeholder="Password" autocomplete="new-password" id="addpassword" name="addpassword" required>
                </div>
			
            
                
                <div class="col-md-12">
                  <select id="inputState" class="form-select" name="addrole" id="addrole" >
                   
                    <option value="admin">System Admin</option>
					<option value="clerk">Office Admin</option>
					<option value="Operations">Operations Officer</option>
						<option value="Accounts">Accountant</option>
                  </select>
                </div>
             
				
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="savenew">Save</button>
                  <button type="reset" class="btn btn-secondary">Clear</button>
                  <button type="submit" class="btn btn-danger" name="delete" id="delete" >Delete</button>
				  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </form><!-- End No Labels Form -->

            </div>
        </div>
    </div>
</div>

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
                  <input type="password" class="form-control" placeholder="Password" autocomplete="new-password" id="password" name="password" >
                </div>
			
            
                
                <div class="col-md-12">
                  <select id="inputState" class="form-select" name="role" id="role" >
                   
                    <option value="admin">System Admin</option>
					<option value="clerk">Office Admin</option>
					<option value="Operations">Operations Officer</option>
						<option value="Accounts">Accountant</option>
                  </select>
                </div>
             
				
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="edit">Save</button>
                  <button type="reset" class="btn btn-secondary">Clear</button>
                  <button type="submit" class="btn btn-danger" name="delete" id="delete" >Delete</button>
				  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </form><!-- End No Labels Form -->
            </div>
        </div>
    </div>
</div>

<button style="margin: 10px; " type="button" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-outline-secondary col-md-3 dedit" title="Add New"><i class="bi bi-person-plus"></i>Add New Local User</button>

		  




 <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
 
	
  <div class="table-responsive"> 
              <!-- Table with stripped rows -->
              <table class="table datatable" id="jj">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Role</th>
                    <th scope="col">Online</th>
                    <th scope="col">Last Logout</th>
				
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("SELECT * FROM `realuzer`");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

$uid = $row['id'];
?>
                  <tr>
                    <th scope="row"><?php echo $row['id']; ?></th>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['session']; ?></td>
                    <td><?php echo $row['last_login']; ?></td>

					<td>
			<button type="button" data-link="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary edit"  title="Edit" 
			data-id="<?php echo $row['id']; ?>"
			data-username="<?php echo $row['username']; ?>"
			data-role="<?php echo $row['role']; ?>"
			data-password="<?php echo $row['password']; ?>"
			data-bs-toggle="modal" data-bs-target="#editUserModal"
			><i class="bi bi-eye-fill"></i></button>
		
  
					</td>
                  </tr>
<?php   }
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
    var password = $(this).data("password");

    $('#username').val(username);
   // $('#password').val(password);
    $('#use').val(id);

    // Set the selected option based on the role value
    $('#role').val(role);

    // Trigger the 'change' event to update the dropdown visually
    $('#role').trigger('change');
});
</script>  
  
  



</body>

</html>

<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once 'scripts/connection.php';
 error_log('File deletion script executed successfully.');
 
 
 // Query to check the role of the user
$selectRole = $conn->prepare("SELECT role FROM realuzer WHERE username = ?");
$selectRole->bind_param("s", $gg);
$selectRole->execute();
$selectRole->bind_result($userRole);
$selectRole->fetch();
$selectRole->close();

// Check if the user has the 'admin' role
$isAdmin = ($userRole === 'admin');
 
 
////////insert new 
if (isset($_POST['submit'])) { // if save button on the form is clicked
    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];
   
    $reason = "Adhoc Document";
    $owner = $_POST['member'];
    $dates = $_POST['datefile'];

   // $destination = 'uploads/'.$filename;

    $file_path = 'adhocfile/uploads/'.$owner;
  
// Checking whether file exists or not
if (!file_exists($file_path)) {
  
    // Create a new file or direcotry
    mkdir($file_path, 0777, true);
}

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

 // the physical file on a temporary uploads directory on the server
$file = $_FILES['myfile']['tmp_name'];
$size = $_FILES['myfile']['size'];
$new_filename = $dates . '_' . $owner . '.' . $extension;
$file_path = 'adhocfile/uploads/' . $owner;
$url = "https://fair.liquag.com/" . $file_path . $new_filename;

if (!in_array($extension, ['zip', 'pdf', 'docx', 'png', 'jpg', 'jpeg', 'doc'])) {
    echo "You file extension must be .zip, .pdf or .docx";
} elseif ($size > 5000000) { // file shouldn't be larger than 5Megabyte
    echo "File too large!";
} else {
    // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $file_path . '/' . $new_filename)) {
        // Rename the actual file
        rename($file_path . '/' . $filename, $file_path . '/' . $new_filename);

        $sql = "INSERT INTO adhocfiles (`member`, `name`, `dateupload`, `reason`, `url`) VALUES ('$owner','$new_filename', '$dates', '$reason', '$url')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Document uploaded successfully!"); window.location.href = "files.php";</script>';
        } else {
            echo '<script>alert("Operation not successful!"); window.location.href = "files.php";</script>';
        }
    } else {
        echo '<script>alert("Failed to upload file."); window.location.href = "files.php";</script>';
    }
}

}

?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Upload File</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://liquag.com/dev/fairlife/logo.png" rel="icon">
  <link href="https://liquag.com/dev/fairlife/logo.png" rel="apple-touch-icon">

  <script src=" https://code.jquery.com/jquery-3.5.1.js"></script>




  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>

 <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
        <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>

        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
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

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
 
</head>

<body>

  <!-- ======= Header ======= -->
 <?php include 'header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Files</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Members</a></li>
          <li class="breadcrumb-item active">Files</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
            
        <section class="section dashboard">
      <div class="row">

      <div class="col-lg-12">

<div class="card shadow-lg">
  <div class="card-body">
    <!-- Add the Delete File button with Bootstrap classes for positioning -->
   <?php if ($isAdmin): ?>
    <button type="button" class="btn btn-danger position-absolute top-0 end-0 mt-3 mr-3 me-3" id="deleteFileBtn" name="deleteFileBtn">Delete File</button>
<?php endif; ?>

    <h5 class="card-title">View All Files</h5>

    <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
    <hr style="margin-bottom: 0px">
    <div class="col-md-6">
    <label for="delivery">Member List</label>
    <div class="form-floating mb-3">
        <select name="member" class="form-select" id="member" aria-label="Member" required>
            <option selected>Select Member</option>
            <?php
            $select = $conn->prepare("SELECT * FROM `tblmembers`");
            $select->execute();
            $result = $select->get_result();
            while ($row = $result->fetch_assoc()) {
                ?>
                <option value="<?php echo $row['MemberNo']; ?>"><?php echo $row['MemberNo']." - ".$row['MemberSurname']; ?> <?php echo $row['MemberFirstname']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="col-md-6">
    <label for="delivery">File List</label>
    <div class="form-floating mb-3">
        <select name="filelist" class="form-select" id="filelist" aria-label="File" required>
            <!-- Options will be dynamically populated using JavaScript -->
        </select>
    </div>
</div>
</form><!-- End floating Labels Form -->
<!-- Add this code after your existing HTML form -->
<!-- Add this code after your existing HTML form -->
 <hr style="margin-bottom: 4px">
<div class="col-md-12" style="margin-top: 4px">
    <label for="pdfViewer" style="font-weight: bold;">Selected Document Here</label>
    <div class="form-floating mb-3">
        <iframe id="pdfViewer" width="100%" height="600px" frameborder="0"></iframe>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#pdfViewer').hide(); // Hide the iframe initially

        $('#filelist').change(function () {
            var selectedFileUrl = $(this).val();

            if (selectedFileUrl) {
                // Show the iframe and update its src attribute with the selected file URL
                $('#pdfViewer').show().attr('src', selectedFileUrl);
            } else {
                // Hide the iframe when no file is selected
                $('#pdfViewer').hide();
            }
        });
    });
</script>
  </div>
</div>

</div>


       
      </div>
    </section>

        

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

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/vendor/php-email-form/validate.js"></script>
<!-- Vendor JS Files -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>


<script>
$(document).on("click",".edit",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
<script>
        $(document).ready(function(){
    $('#member').select2();  
    $("#member").select2({ width: '100%' });

        });
        </script>
        
        
        <script>
        $(document).ready(function(){
    $('#filelist').select2();  
    $("#filelist").select2({ width: '100%' });

        });
        </script>
        
 <script>
  $(document).on('click', '.delete', function() {
    var id = $(this).data('id');
    var filepath = $(this).data('filepath');
    
    
    if (confirm('Are you sure you want to delete this item?')) {
      // User clicked "Yes" button
      // Call delete function here
          $.ajax({
      url: 'deletefile.php',
      type: 'POST',
      data: {id: id, filepath: filepath},
      success: function(response) {
        alert(response);
        location.reload();
      },
      error: function(xhr, status, error) {
        alert("Error deleting item: " + error);
      }
    });
      //alert('Item deleted successfully.');
    } else {
      // User clicked "No" button
      alert('Item deletion cancelled.');
    }
    
    

  });
  </script>


<script>
 $(document).ready(function () {
    // Add click event for the Delete File button
    $('#deleteFileBtn').click(function () {
        var selectedFileId = $('#filelist').val();
        if (selectedFileId) {
            // Confirm the deletion with the user
            if (confirm('Are you sure you want to delete this file?')) {
                // Perform the deletion logic using AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'delete_file.php', // Replace with your server-side script handling the deletion
                    data: { fileId: selectedFileId },
                    dataType: 'json', // Specify that you expect JSON as the response
                    success: function (response) {
                        if (response.status === 'success') {
                            // Optionally, you can clear the selected file and hide the iframe
                            $('#filelist').val('');
                            $('#pdfViewer').hide();
                            alert('File deleted successfully.');
                           // Remove the deleted file from the filelist combo box
                            $('#filelist option[value="' + selectedFileId + '"]').remove();

                        } else {
                            alert('Failed to delete file. ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Error occurred during the deletion process. Status: ' + status + '\nError: ' + error);
                    }
                });
            }
        } else {
            // Show an alert if no file is selected
            alert('Please select a file to delete.');
        }
    });
});

</script>        


<script>
    $(document).ready(function () {
        $('#member').change(function () {
            var memberId = $(this).val();
             $('#pdfViewer').hide();
            var filelistDropdown = $('#filelist');

            // Clear existing options
            filelistDropdown.empty().append('<option selected></option>');

            if (memberId !== "Select Member") {
                // Fetch file list based on the selected member using AJAX
                $.ajax({
                    url: 'fetch_file_list.php',
                    method: 'GET',
                    data: { memberId: memberId },
                    dataType: 'json',
                    success: function (fileList) {
                        // Populate the File List dropdown
                        $.each(fileList, function (index, file) {
                            filelistDropdown.append('<option value="' + file.url + '">' + file.name + '</option>');
                        });
                    },
                    error: function () {
                        console.error('Failed to fetch file list.');
                    }
                });
            }
        });
    });
</script>

       
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/logout.php');
}

?>
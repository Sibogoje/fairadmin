<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once 'scripts/connection.php';

////////insert new 
if (isset($_POST['submit'])) { // if save button on the form is clicked
    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];
   
    $reason = $_POST['reason'];
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
$randomNumber = rand(100, 999);


 // the physical file on a temporary uploads directory on the server
$file = $_FILES['myfile']['tmp_name'];
$size = $_FILES['myfile']['size'];
$new_filename = $dates.' [' .$owner. '][' .$reason.' ]'.' ['.$randomNumber.'].'.$extension;
$file_path = 'adhocfile/uploads/' . $owner;
$url = "https://fair.liquag.com/" . $file_path ."/". $new_filename;

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
  
  <script src='jquery-3.2.1.min.js' type='text/javascript'></script>



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
    <h5 class="card-title">Adhoc Document Upload</h5>
   

    <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
    <hr style="margin-bottom: 1px">


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
                    <label for="delivery">Reason</label>
                  <div class="form mb-3">
               <input type="text" class="form-control" name='reason'  id="reason">
                    
                  </div>
                </div>

                <div class="col-md-6">
                    <label for="delivery">Date of Request</label>
                  <div class="form mb-3">
               <input type="date" class="form-control" name='datefile'  id="datefile">
                    
                  </div>
                </div>

              
                <div class="col-md-6">
                <label for="delivery">Select File</label>
              
                  <div class="col-sm-12">
                    <input type="file" class="form-control" name='myfile'  id="formFile">
                  </div>
                </div>

                <div class="text-center col-xs-12 col-lg-12" style="margin-top: 8px; margin-bottom: 8px;">
                  <button type="submit" name="submit" class="btn btn-primary col-12 col-lg-12" style="background-color: orange;">Upload Document</button>
                 
                </div>

  
              </form><!-- End floating Labels Form -->


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
//$(document).ready(function(){
/*	
	$('.edit').click(function(){
   window.location.href = $(this).data('link');
});
});
*/

$(document).ready(function() {
    $('#memberstbl').DataTable( {
        lengthMenu: [
            [5, 10, 50, -1],
            [5, 10, 20, 'All'],
        ],
        dom: 'Blfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        responsive: true,
       
        
    } );
    
  
} );
</script>
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
    $('#reason').select2();  
    $("#reason").select2({ width: '100%' });

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
        
        
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/logout.php');
}

?>
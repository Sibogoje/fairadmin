<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';
//$ids=$_POST['id'];
//$ids = $_REQUEST['id'];

////////insert new 

if (isset($_POST['submit'])){
	
	
//$RetirementFundID = $_POST['RetirementFundID']; 
$FundName = $_POST['FundName'];
$RegistrationNo = $_POST['RegistrationNo'];
$FundPhysicalAddress = $_POST['FundPhysicalAddress'];
$FundContact = $_POST['FundContact'];
$FundPostalAddress = $_POST['FundPostalAddress'];
$FundPostOfficeID = $_POST['FundPostOfficeID'];
$FundEmail = $_POST['FundEmail'];
$FundCellNo = $_POST['FundCellNo'];
$FundTelNo = $_POST['FundTelNo'];
$FundFaxNo = $_POST['FundFaxNo'];

$stmt = $conn->prepare("INSERT INTO `tblretirementfunds` (
  `FundName`,
  `RegistrationNo`,
  `FundPhysicalAddress`,
  `FundContact`,
  `FundPostalAddress`,
  `FundPostOfficeID`,
  `FundEmail`,
  `FundCellNo`,
  `FundTelNo`,
  `FundFaxNo`
)

VALUES
  (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
	?,
	?,
	?
    

  );");
$stmt->bind_param("ssssssssss", 
$FundName,
$RegistrationNo,
$FundPhysicalAddress,
$FundContact,
$FundPostalAddress,
$FundPostOfficeID,
$FundEmail,
$FundCellNo,
$FundTelNo,
$FundFaxNo

);
$stmt->execute();

echo "New records created successfully";

$stmt->close();
$conn->close();
}else{

}


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>New Member</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://fairlifeadmin.com/" rel="icon">
  <link href="https://fairlifeadmin.com/" rel="apple-touch-icon">

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
  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
 <?php include '../header.php'; ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>New Fund Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Funds</a></li>
          <li class="breadcrumb-item active">New Fund Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">New Fund Profile</h5>

              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>		    

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundName" value="" name="FundName" required>
                    <label for="floatingName">Fund Name:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="RegistrationNo" value="" name="RegistrationNo" >
                    <label for="floatingName">Registration No:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundContact" value="" name="FundContact" required>
                    <label for="floatingName">Fund Contact:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundCellNo" value="" name="FundCellNo" required>
                    <label for="floatingName">Fund Cell No:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  	  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="FundPostOfficeID" name="FundPostOfficeID" >
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblpostoffices` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['postofficeID']; ?>"><?php echo $row12['PostOffice']; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Post Office:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
		  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundPostalAddress" value="" name="FundPostalAddress">
                    <label for="floatingName">Fund Postal Address:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundEmail" value="" name="FundEmail" required>
                    <label for="floatingName">Fund Email:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Fund Tel No" value="" name="FundTelNo" >
                    <label for="floatingName">FundTelNo:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundFaxNo" value="" name="FundFaxNo" >
                    <label for="floatingName">Fund fax:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  				  				  
				  <div class="col-md-9">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="FundPhysicalAddress" value="" name="FundPhysicalAddress" required>
                    <label for="floatingName">FundPhysicalAddress:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
      
                
                <div class="text-center">
                  <button type="submit" class="btn btn-warning" name="submit" style="width: 49%;">Add New Fund</button>
                  <button type="reset" class="btn btn-secondary" style="width: 49%;">Reset</button>
                  
                </div>

              </form><!-- End floating Labels Form -->

            </div>
          </div>

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
<script>
$(document).on("click",".dnew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
</body>

</html>
<?php
}else{
    header('Location: https://fairlifeadmin.com/index.php');
}

?>
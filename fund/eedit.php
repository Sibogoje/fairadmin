<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';
$idz=$_GET['id'];


////////insert new 

if (isset($_POST['submit'])){
	
	
//$RetirementFundID = $_POST['RetirementFundID']; 
$FundID = $_POST['FundID'];
$EmployerName = $_POST['EmployerName'];
$EmployerAddress = $_POST['EmployerAddress'];
$EmployerPostalAddress = $_POST['EmployerPostalAddress'];
$EmployerPostOfficeID = $_POST['EmployerPostOfficeID'];
$EmployerContactPerson = $_POST['EmployerContactPerson'];
$EmployerEmail = $_POST['EmployerEmail'];
$EmployerTel = $_POST['EmployerTel'];
$EmployerCell = $_POST['EmployerCell'];
$EmployerID = $_POST['EmployerID'];


$stmt = $conn->prepare("REPLACE INTO `tblemployers` (
  `employerID`,
 `FundID`,
  `EmployerName`,
  `EmployerAddress`,
  `EmployerPostalAddress`,
  `EmployerPostOfficeID`,
  `EmployerContactPerson`,
  `EmployerEmail`,
  `EmployerTel`,
  `EmployerCell`
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
$EmployerID,
$FundID,
$EmployerName,
$EmployerAddress,
$EmployerPostalAddress,
$EmployerPostOfficeID,
$EmployerContactPerson,
$EmployerEmail,
$EmployerTel,
$EmployerCell

);
$stmt->execute();

echo "New records created successfully";
header("location: employers.php");

$stmt->close();
$conn->close();
}else{

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Employer Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://fair.liquag.com/" rel="icon">
  <link href="https://fair.liquag.com/" rel="apple-touch-icon">

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
      <h1>Update Employer Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Employers</a></li>
          <li class="breadcrumb-item active">Update Employer Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Update Fund Profile</h5>

              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>	

	<?php 
$stmt = $conn->prepare("SELECT * FROM `tblemployers` where `employerID`=?");
$stmt->bind_param("s", $idz);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {
	
	//$imgs = $row['profilepic'];
?>
			  
			  	  
  
  
  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Employer Name" value="<?php echo $row['employerID']; ?>" name="EmployerID" >
                    <label for="floatingName">Employer ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Employer Name" value="<?php echo $row['EmployerName']; ?>" name="EmployerName" >
                    <label for="floatingName">Employer Name:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
  
  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="FundID" name="FundID" >
					<option value="<?php echo $row['FundID']; ?>" selected><?php echo $row12['FundID']; ?></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblretirementfunds` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['RetirementFundID']; ?>"><?php echo $row12['FundName']; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Fund:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="EmployerAddress" value="<?php echo $row['EmployerAddress']; ?>" name="EmployerAddress" >
                    <label for="floatingName">Employer Address:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

				  
				  
				  	  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="EmployerPostOfficeID" name="EmployerPostOfficeID" >
					<option value="<?php echo $row['EmployerPostOfficeID']; ?>" selected><?php echo $row['EmployerPostOfficeID']; ?></option>
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
                    <input type="text" class="form-control" id="ff" placeholder="EmployerContactPerson" value="<?php echo $row['EmployerContactPerson']; ?>" name="EmployerContactPerson">
                    <label for="floatingName">Employer ContactPerson:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="EmployerPostalAddress" value="<?php echo $row['EmployerPostalAddress']; ?>" name="EmployerPostalAddress" >
                    <label for="floatingName">Employer PostalAddress:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Employer Tel" value="<?php echo $row['EmployerTel']; ?>" name="EmployerTel" >
                    <label for="floatingName">Employer Tel:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="EmployerCell" value="<?php echo $row['EmployerCell']; ?>" name="EmployerCell" >
                    <label for="floatingName">Employer Cell:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="EmployerEmail" value="<?php echo $row['EmployerEmail']; ?>" name="EmployerEmail" >
                    <label for="floatingName">EmployerEmail:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				        
                
                <div class="text-center">
                  <button type="submit" class="btn btn-warning" name="submit" style="width: 100%;"><b>Update Employer Information</b></button>
                 
                  
                </div>
				<?php
}} else {
?> 
  <div class="text-center">
                  <button type="button" class="btn btn-warning dnew" data-link="gnew.php" data-id="rr"  style="width: 100%;">New Fund</button>
               </div>
  <?php
} ?> 
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
    header('Location: index.php');
}

?>
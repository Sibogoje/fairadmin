<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';
//$ids=$_POST['id'];
//$ids = $_REQUEST['id'];
$ids = $_GET['id'];

////////insert new 
if (isset($_POST['updatekin'])){

  $memno = $_POST['memno']; 
  $kinno = $_POST['kinno'];

  $update = $conn->prepare("UPDATE tblmembers SET `NextOfKinID` = ? WHERE MemberID=? ");
  $update->bind_param("ss", $kinno,  $memno);
  $update->execute();

}

if (isset($_POST['submit'])){
	

	
$KinSurname = $_POST['KinSurname']; 
$KinFirstNames = $_POST['KinFirstNames'];
$KinPostalAddress = $_POST['KinPostalAddress'];
$KinPostOfficeID = $_POST['KinPostOfficeID'];
$KinTelWork = $_POST['KinTelWork'];
$KinTelHome = $_POST['KinTelHome'];
$KinCell = $_POST['KinCell'];
$KinEmail = $_POST['KinEmail'];

$stmt = $conn->prepare("UPDATE ``tblnextofkin` SET
 `KinSurname`,
  `KinFirstNames`,
  `KinPostalAddress`,
  `KinPostOfficeID`,
  `KinTelWork`,
  `KinTelHome`,
  `KinCell`,
  `KinEmail`
    WHERE 
  `NextOfKinID` =?"
);


$stmt->bind_param("sssssssss", 
$KinSurname,
$KinFirstNames,
$KinPostalAddress,
$KinPostOfficeID,
$KinTelWork,
$KinTelHome,
$KinCell,
$KinEmail,
$NextOfKinID
);
if (!$stmt->execute() ) 
$stmt->close();
}else{
	//
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
      <h1>Next of Kin Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Members</a></li>
          <li class="breadcrumb-item active">Next of Kin Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">View, Edit and Delete Member</h5>

              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
	<?php 
$stmt = $conn->prepare("SELECT * FROM `tblnextofkin` where `NextOfKinID`=?");
$stmt->bind_param("s", $ids);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {
	
	//$imgs = $row['profilepic'];
?>		    
					<div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Deceased ID" value="<?php echo $row['NextOfKinID']; ?>" name="NextOfKinID" readonly>
                    <label for="floatingName">Next Of KinID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin Surname" value="<?php echo $row['KinSurname']; ?>" name="KinSurname" required>
                    <label for="floatingName">Kin Surname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin FirstNames" value="<?php echo $row['KinFirstNames']; ?>" name="KinFirstNames" required>
                    <label for="floatingName">Kin FirstNames:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin Postal Address" value="<?php echo $row['KinPostalAddress']; ?>" name="KinPostalAddress" >
                    <label for="floatingName">KinPostalAddress:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  
				  
				  	  	  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="KinPostOfficeID" name="KinPostOfficeID" >
					<option value="<?php echo $row['KinPostOfficeID'];?>" selected><?php echo $row['KinPostOfficeID']; ?></option>
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
                    <input type="text" class="form-control" id="ff" placeholder="Guardian Postal Address" value="<?php echo $row['KinTelWork']; ?>" name="KinTelWork" >
                    <label for="floatingName">Kin TelWork:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
			
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin TelHome" value="<?php echo $row['KinTelHome']; ?>" name="KinTelHome">
                    <label for="floatingName">Kin Tel Home:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin Cell" value="<?php echo $row['KinCell']; ?>" name="KinCell" >
                    <label for="floatingName">Kin Cell:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Kin Email" value="<?php echo $row['KinEmail']; ?>" name="KinEmail" >
                    <label for="floatingName">Kin Email:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				 
      
                
                <div class="text-center">
                  <button type="submit"  class="btn btn-warning" style="width: 100%;" name="submit">Update Next of Kin Info</button>
                  
                </div>
								<?php   }
} else {
?> 
  <div class="text-center">
                  <button type="button" class="btn btn-warning knew" data-link="knew.php" data-id="rr"  style="width: 100%;"><b>Add New Next of Kin</b></button>
               </div>
  </form><!-- End floating Labels Form -->
<br>
  <label><b>--OR--</b></label>
<br>


  <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>

<div class="col-md-6">
          <div class="form-floating">
					 <select type="text" class="form-control"  name="memno" placeholder="KinPostOfficeID"  >
					
						<?php 
						$stmt122 = $conn->prepare("SELECT * FROM `tblmembers` ");
						$stmt122->execute();
						$result122 = $stmt122->get_result();
						if ($result122->num_rows > 0) {
						  // output data of each row
						while($row122 = $result122->fetch_assoc()) {

						?>
					<option value="<?php echo $row122['MemberID']; ?>"><?php echo $row122['MemberNo']." ".$row122['MemberSurname']."  ".$row122['MemberFirstname']; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Member:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

    <div class="col-md-6">
          <div class="form-floating">
					 <select type="text" class="form-control" name="kinno" placeholder="KinPostOfficeID"  >
					
						<?php 
						$stmt121 = $conn->prepare("SELECT * FROM `tblnextofkin` ");
						$stmt121->execute();
						$result121 = $stmt121->get_result();
						if ($result121->num_rows > 0) {
						  // output data of each row
						while($row121 = $result121->fetch_assoc()) {

						?>
					<option value="<?php echo $row121['NextOfKinID']; ?>"><?php echo $row121['NextOfKinID']." ".$row121['KinSurname']."  ".$row121['KinFirstNames']; ?></option>
						<?php   }
						} else {
						 // echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Matching Next Of Kin:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <button type="submit" class="btn btn-warning" name="updatekin"  style="width: 100%;">Update Next Of Kin</button>


</form>
  






  <?php
} ?> 
            

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
$(document).on("click",".knew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
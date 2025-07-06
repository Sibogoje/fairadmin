<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';

////////insert new 
if (isset($_POST['submit'])){
	
	
$DeceasedSurname = $_POST['DeceasedSurname']; 
$DeceasedFirstnames = $_POST['DeceasedFirstnames'];
$DeceasedIDnumber = $_POST['DeceasedIDnumber'];
$DateOfDeath = $_POST['DateOfDeath'];
$TotalFunds = $_POST['TotalFunds'];
$RetirementFundID = $_POST['RetirementFundID'];
$EmployerID = $_POST['EmployerID'];


$stmt = $conn->prepare("INSERT INTO `tbldeceased` (
  `DeceasedSurname`,
  `DeceasedFirstnames`,
  `DeceasedIDnumber`,
  `DateOfDeath`,
  `TotalFunds`,
  `RetirementFundID`,
  `EmployerID`
)

VALUES
  (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?
    

  );");
$stmt->bind_param("sssssss", 
$DeceasedSurname,
$DeceasedFirstnames,
$DeceasedIDnumber,
$DateOfDeath,
$TotalFunds,
$RetirementFundID,
$EmployerID

);
//$stmt->execute();

//echo "New records created successfully";
if ($stmt->execute()) { 
   echo '<script>
alert("New deceased added succesfully");
window.location.href="dnew.php";
</script>';
} else {
   echo '<script>
alert("There was an error, Please try again");
window.location.href="dnew.php";
</script>';
}
$stmt->close();
//$conn->close();
}else{
//	
}




?>
<html>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Add Deceased</title>
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
      <h1>New Deceased</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Home</a></li>
          <li class="breadcrumb-item active">New Deceased</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Please fill the form to add new Deceased</h5>

              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
			  
			  
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Deceased Surname" name="DeceasedSurname" required>
                    <label for="floatingName">Deceased Surname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Deceased Firstnames" name="DeceasedFirstnames" required>
                    <label for="floatingName">Deceased Firstnames:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Deceased IDnumber" name="DeceasedIDnumber" required>
                    <label for="floatingName">Deceased IDnumber:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="ff" placeholder="Date Of Death" name="DateOfDeath" required>
                    <label for="floatingName">Date Of Death:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Total Funds" name="TotalFunds" required>
                    <label for="floatingName">Total Funds:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

				  	  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="Retirement FundID" name="RetirementFundID" required>
					<option value=""></option>
						<?php 
						$stmt1 = $conn->prepare("SELECT * FROM `tblretirementfunds` ");
						$stmt1->execute();
						$result1 = $stmt1->get_result();
						if ($result1->num_rows > 0) {
						  // output data of each row
						while($row1 = $result1->fetch_assoc()) {

						?>
					<option value="<?php echo $row1['RetirementFundID']; ?>"><?php echo $row1['FundName']; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Retirement Fund:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>


				   	  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control select js-example-basic-single" id="ff" placeholder="Employer ID" name="EmployerID" required>
					<option value=""></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblemployers` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['employerID']; ?>"><?php echo $row12['EmployerName']; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    <label for="floatingName">Select Employer:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				
               
      
                
                <div class="text-center">
                  <button type="submit" class="btn btn-warning" name="submit" style="width: 49%;">Add New Deceased</button>
                  <button type="reset" class="btn btn-secondary" style="width: 49%;">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Vendor JS Files -->

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>
<script>
 $(document).ready(function(){
    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();
	
	 });  
	</script>
</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
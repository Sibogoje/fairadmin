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


if (isset($_POST['submit'])){

	
	
	
$MemberNo = $_POST['MemberNo']; 
$MemberSurname = $_POST['MemberSurname'];
$MemberFirstname = $_POST['MemberFirstname'];
$MemberIDnumber = $_POST['MemberIDnumber'];
$DeceasedID = $_POST['DeceasedID'];
$RelationshipDeceased = $_POST['RelationshipDeceased'];
$GuardianID = $_POST['GuardianID'];
$RelationshipGuardian = $_POST['RelationshipGuardian'];
$NextOfKinID = $_POST['NextOfKinID'];
$RelationshipNextOfKin = $_POST['RelationshipNextOfKin'];
$MemberPostalAddress = $_POST['MemberPostalAddress'];
$MemberPostOfficeID = $_POST['MemberPostOfficeID'];
$Gender = $_POST['Gender'];
$DateOfBirth = $_POST['DateOfBirth'];
$ApprovedBenefit = $_POST['ApprovedBenefit'];
$DateAccountOpened = $_POST['DateAccountOpened'];
$RegularPaymentFrequencyID = $_POST['RegularPaymentFrequencyID'];
$RegularPaymentTypeID = $_POST['RegularPaymentTypeID'];
$FixedPaymentAmount = $_POST['FixedPaymentAmount'];
$MaxPaymentAmount = $_POST['MaxPaymentAmount'];
$Comments = $_POST['Comments'];
$BankID = $_POST['BankID'];
$BankAccountNo = $_POST['BankAccountNo'];
$AccountTypeID = $_POST['AccountTypeID'];
$AccountHolderName = $_POST['AccountHolderName'];
$terminated = $_POST['terminated'];


$stmt = $conn->prepare("UPDATE `tblmembers` SET
  `MemberSurname`=?,
  `MemberFirstname`=?,
  `MemberIDnumber`=?,
  `DeceasedID`=?,
  `RelationshipDeceased`=?,
  `GuardianID`=?,
  `RelationshipGuardian`=?,
  `NextOfKinID`=?,
  `RelationshipNextOfKin`=?,
  `MemberPostalAddress`=?,
  `MemberPostOfficeID`=?,
  `Gender`=?,
  `DateOfBirth`=?,
  `ApprovedBenefit`=?,
  `DateAccountOpened`=?,
  `RegularPaymentFrequencyID`=?,
  `RegularPaymentTypeID`=?,
  `FixedPaymentAmount`=?,
  `MaxPaymentAmount`=?,
  `Comments`=?,
  `BankID`=?,
  `BankAccountNo`=?,
  `AccountTypeID`=?,
  `AccountHolderName`=?,
   `Terminated`=?

  WHERE 
  `MemberNo` =?"

);


$stmt->bind_param("ssssssssssssssssssssssssss", 

$MemberSurname,
$MemberFirstname,
$MemberIDnumber,
$DeceasedID,
$RelationshipDeceased,
$GuardianID,
$RelationshipGuardian,
$NextOfKinID,
$RelationshipNextOfKin,
$MemberPostalAddress,
$MemberPostOfficeID,
$Gender,
$DateOfBirth,
$ApprovedBenefit,
$DateAccountOpened,
$RegularPaymentFrequencyID,
$RegularPaymentTypeID,
$FixedPaymentAmount,
$MaxPaymentAmount,
$Comments,
$BankID,
$BankAccountNo,
$AccountTypeID,
$AccountHolderName,
$terminated,
//$target_file,
$MemberNo

);
// set parameters and execute
//$stmt->execute();
if (!$stmt->execute() ){}else{header("location: index.php");} 
 //echo "Execute Error: ($stmt->errno)  $stmt->error";
//echo "update made";

$stmt->close();
//$conn->close();
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
      <h1>Beneficiary Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Home</a></li>
          <li class="breadcrumb-item active">Beneficiary Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">View, Edit and Delete Member</h5>
<!--`MemberID`,
  `MemberNo`,
  `MemberSurname`,
  `MemberFirstname`,
  `MemberIDnumber`,
  `DeceasedID`,
  `RelationshipDeceased`,
  `GuardianID`,
  `RelationshipGuardian`,
  `NextOfKinID`,
  `RelationshipNextOfKin`,
  `MemberPostalAddress`,
  `MemberPostOfficeID`,
  `Gender`,
  `DateOfBirth`,
  `ApprovedBenefit`,
  `DateAccountOpened`,
  `RegularPaymentFrequencyID`,
  `RegularPaymentTypeID`,
  `FixedPaymentAmount`,
  `MaxPaymentAmount`,
  `Comments`,
  `BankID`,
  `BankAccountNo`,
  `AccountTypeID`,
  `AccountHolderName`,
  `TerminationDate`,
  `Terminated`
              <!-- Floating Labels Form -->
              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
	<?php 
$stmt = $conn->prepare("SELECT * FROM `tblmembers` where `MemberNo`=?");
$stmt->bind_param("s", $ids);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {
	
	$imgs = $row['profilepic'];
?>		  

			  <div class="col-md-3">
                  <div class="form-floating">
				  <img src="<?php echo $imgs; ?>" alt="<?php echo $imgs; ?>" width="160" height="120"> 
                  </div>
				  </div>
				  <div class="col-3">
                  <div class="form-floating">
                    <input type="file" name="fileToUpload" id="fileToUpload" style="height: 100px;" class="form-control">
                    
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member No" name="MemberNo" value="<?php echo $row['MemberNo']; ?>" readonly>
                    <label for="floatingName">Member No:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Surname" name="MemberSurname" value="<?php echo $row['MemberSurname']; ?>" required>
                    <label for="floatingName">Member Surname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Firstname" name="MemberFirstname" value="<?php echo $row['MemberFirstname']; ?>" required>
                    <label for="floatingName">Member Firstname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member ID Number" name="MemberIDnumber" value="<?php echo $row['MemberIDnumber']; ?>" required>
                    <label for="floatingName">Member ID Number:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Deceased ID" name="DeceasedID" value="<?php echo $row['DeceasedID']; ?>" required>
                    <label for="floatingName">Deceased ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship With Deceased" name="RelationshipDeceased" value="<?php echo $row['RelationshipDeceased']; ?>" required>
                    <label for="floatingName">Relationship-Deceased:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="GuardianID" name="GuardianID" value="<?php echo $row['GuardianID']; ?>" >
                    <label for="floatingName">Guardian ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship Guardian" name="RelationshipGuardian" value="<?php echo $row['RelationshipGuardian']; ?>" >
                    <label for="floatingName">Relationship Guardian:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Next Of Kin ID" name="NextOfKinID" value="<?php echo $row['NextOfKinID']; ?>" >
                    <label for="floatingName">Next Of Kin ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship Next Of Kin" name="RelationshipNextOfKin"  value="<?php echo $row['RelationshipNextOfKin']; ?>" >
                    <label for="floatingName">Relationship Next Of Kin:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Postal Address" name="MemberPostalAddress" value="<?php echo $row['MemberPostalAddress']; ?>" >
                    <label for="floatingName">Member Postal Address:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Post Office ID" name="MemberPostOfficeID" value="<?php echo $row['MemberPostOfficeID']; ?>" >
                    <label for="floatingName">Member Post Office ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <select type="text" class="form-control" id="ff" placeholder="Gender" name="Gender" required>
					<option value="<?php echo $row['Gender']; ?>" selected><?php echo $row['Gender']; ?><option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					</select>
                    <label for="floatingName">Select Gender:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Date Of Birth" name="DateOfBirth" value="<?php echo $row['DateOfBirth']; ?>" required>
                    <label for="floatingName">Date Of Birth:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Approved Benefit" name="ApprovedBenefit" value="<?php echo $row['ApprovedBenefit']; ?>" required>
                    <label for="floatingName">Approved Benefit:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Date Account Opened" name="DateAccountOpened" value="<?php echo $row['DateAccountOpened']; ?>" required>
                    <label for="floatingName">Date Account Opened:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="Regular Payment Frequency" name="RegularPaymentFrequencyID" >
					<option value="<?php echo $row['RegularPaymentFrequencyID']; ?>" selected><?php echo $row['RegularPaymentFrequencyID']; ?><option>
					<option value="1">Monthly</option>
					<option value="2">Quarterly</option>
					<option value="3">Bi-annual</option>
					<option value="4">Annual</option>
					</select>
                    <label for="floatingName">Regular Payment:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    
					 <select type="text" class="form-control" id="ff" placeholder="Gender" name="RegularPaymentTypeID"  >
					<option value="<?php echo $row['RegularPaymentTypeID']; ?>" selected><?php echo $row['RegularPaymentTypeID']; ?><option>
					<option value="1">Regular discretionary payment</option>
					<option value="2">Regular fixed payment</option>
					<option value="3">Regular maximum payment</option>
					<option value="4">No regular payment</option>
					</select>
                    <label for="floatingName">Regular Payment Type:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Fixed Payment Amount" name="FixedPaymentAmount" value="<?php echo $row['FixedPaymentAmount']; ?>" >
                    <label for="floatingName">Fixed Payment Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Max Payment Amount" name="MaxPaymentAmount" value="<?php echo $row['MaxPaymentAmount']; ?>" >
                    <label for="floatingName">Max Payment Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Bank ID" name="BankID" value="<?php echo $row['BankID']; ?>" >
                    <label for="floatingName">Bank ID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Bank AccountNo" name="BankAccountNo" value="<?php echo $row['BankAccountNo']; ?>" >
                    <label for="floatingName">Bank AccountNo:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Account TypeID" name="AccountTypeID" value="<?php echo $row['AccountTypeID']; ?>" >
                    <label for="floatingName">Account TypeID:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Account Holder Name" name="AccountHolderName" value="<?php echo $row['AccountHolderName']; ?>" >
                    <label for="floatingName">Account Holder Name:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="ff" placeholder="Account Holder Name" name="terminated" value="<?php echo $row['Terminated']; ?>" required>
                    <label for="floatingName">Terminated:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-3">
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="Address" id="floatingTextarea" name="Comments" style="height: 100px;" value="<?php echo $row['Comments']; ?>"></textarea>
                    <label for="floatingTextarea">Comments</label>
                  </div>
                </div>
				 
               
      
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="submit">Update</button>
                  <button type="reset" class="btn btn-danger">Delete</button>
                </div>
				
				<?php   }
} else {
 // echo "0 results";
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

</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
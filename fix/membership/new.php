<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';

////////insert new 
if (isset($_POST['submit'])){
	
	
	
  $Terminated = 3;
	
	
$MemberNo = $_POST['MemberNo']; 
$MemberSurname = $_POST['MemberSurname'];
$MemberFirstname = $_POST['MemberFirstname'];
$MemberIDnumber = $_POST['MemberIDnumber'];
$DeceasedID = $_POST['MemberID'];
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
$BankID = $_POST['bankID'];
$BankAccountNo = $_POST['BankAccountNo'];
$AccountTypeID = $_POST['AccountTypeID'];
$AccountHolderName = $_POST['AccountHolderName'];
$FixedPaymentEndDate = $_POST['FixedPaymentEndDate'];



$stmt1 = $conn->prepare("SELECT RetirementFundID, EmployerID  FROM `tbldeceased` WHERE `DeceasedID` ='$DeceasedID' ");
						$stmt1->execute();
						$result1 = $stmt1->get_result();
						if ($result1->num_rows > 0) {
						while($row1 = $result1->fetch_assoc()) {
							

$employer = $row1['EmployerID'];
$fund = $row1['RetirementFundID'];

$stmt = $conn->prepare("INSERT INTO `tblmembers` (
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
  `Terminated`, 
  `FixedPaymentEndDate`

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
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
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
$stmt->bind_param("sssssssssssssssssssssssssss", 
$MemberNo,
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
$Terminated,
$FixedPaymentEndDate


);
// set parameters and execute
$stmt->execute();


echo "<script> alert('New Beneficiary Added');
window.location.href='index.php';
</script>";




$stmt->close();
$conn->close();

}
    
}else{
    echo "<script> alert('No Deceased Found');

</script>";
}


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
<script src='../jquery-3.2.1.min.js' type='text/javascript'></script>
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
   <script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
<link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
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
      <h1>New Member</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Home</a></li>
          <li class="breadcrumb-item active">New Member</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Please fill the form to add new Member</h5>

              <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
 <?php
			  $stmt = $conn->prepare("SELECT MAX(MemberNo) FROM `tblmembers` ");
//$stmt->bind_param("s", $rr);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$max = $row['MAX(MemberNo)'];	
$id = $max + 1;
?>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member No" name="MemberNo" value="<?php echo $id;?>" required readonly>
                    <label for="floatingName">Member No:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Surname" name="MemberSurname" required>
                    <label for="floatingName">Member Surname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Firstname" name="MemberFirstname" required>
                    <label for="floatingName">Member Firstname:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="ff" placeholder="Member ID Number" name="MemberIDnumber" required>
                    <label for="floatingName">Member ID Number:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				<div class="col-md-3">
				<label for="floatingName">Deceased ID:</label><br>
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"    placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `DeceasedID`, `DeceasedSurname`, `DeceasedFirstnames` FROM `tbldeceased` order by DeceasedSurname");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
						$fundname = $row12['DeceasedSurname'];
						$retirementfund = $row12['DeceasedFirstnames'];  ?>
					<option value="<?php echo $row12['DeceasedID']; ?>"><?php echo $row12['DeceasedID']."- ".$row12['DeceasedSurname']."  ".$row12['DeceasedFirstnames'] ; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                     
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship With Deceased" name="RelationshipDeceased" >
                    <label for="floatingName">Relationship-Deceased:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
          <div class="col-md-3">
				<label for="floatingName">Guardian:</label><br>
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="GuardianID"    placeholder="GuardianID" name="GuardianID"  >
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `GuardianID`, `GuardianSurname`, `GuardianFirstNames` FROM `tblguardians` order by GuardianSurname");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) { ?>
					<option value="<?php echo $row12['GuardianID']; ?>"><?php echo $row12['GuardianID']."- ".$row12['GuardianSurname']."  ".$row12['GuardianFirstNames'] ; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                     
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship Guardian" name="RelationshipGuardian" >
                    <label for="floatingName">Relationship Guardian:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
          <div class="col-md-3">
				<label for="floatingName">Next Of Kin:</label><br>
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="NextOfKinID"    placeholder="NextOfKinID" name="NextOfKinID"  >
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `NextOfKinID`, `KinSurname`, `KinFirstNames` FROM `tblnextofkin` order by KinSurname");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) { ?>
					<option value="<?php echo $row12['NextOfKinID']; ?>"><?php echo $row12['NextOfKinID']."- ".$row12['KinSurname']."  ".$row12['KinFirstNames'] ; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                     
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Relationship Next Of Kin" name="RelationshipNextOfKin" >
                    <label for="floatingName">Relationship Next Of Kin:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Member Postal Address" name="MemberPostalAddress" >
                    <label for="floatingName">Member Postal Address:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
          <div class="col-md-3">
				<label for="floatingName">Post Office:</label><br>
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="postofficeID"    placeholder="postofficeID" name="postofficeID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `postofficeID`, `PostOffice`, `PostCode` FROM `tblpostoffices` order by PostOffice");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) { ?>
					<option value="<?php echo $row12['postofficeID']; ?>"><?php echo $row12['postofficeID']."- ".$row12['PostOffice']."  ".$row12['PostCode'] ; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                     
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <select type="text" class="form-control" id="ff" placeholder="Gender" name="Gender" required>
					<option value=""><option>
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
                    <input type="date" class="form-control" id="ff" placeholder="Date Of Birth" name="DateOfBirth" required>
                    <label for="floatingName">Date Of Birth:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                   <input type="number" class="form-control" id="ff" placeholder="Approved Benefit" step="0.01" name="ApprovedBenefit" required>
                    <label for="floatingName">Approved Benefit:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="ff" placeholder="Date Account Opened" name="DateAccountOpened" >
                    <label for="floatingName">Date Account Opened:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
					 <select type="text" class="form-control" id="ff" placeholder="Regular Payment Frequency" name="RegularPaymentFrequencyID" >
					<option value=""><option>
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
                    
					 <select type="text" class="form-control" id="ff" placeholder="Gender" name="RegularPaymentTypeID" >
					<option value=""><option>
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
                    <input type="text" class="form-control" id="ff" placeholder="Fixed Payment Amount" name="FixedPaymentAmount" >
                    <label for="floatingName">Fixed Payment Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				    <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="ff" placeholder="Fixed Payment EndDate" name="FixedPaymentEndDate" >
                    <label for="floatingName">Fixed EndDate:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Max Payment Amount" name="MaxPaymentAmount" >
                    <label for="floatingName">Max Payment Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
				<label for="floatingName">Bank ID:</label><br>
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="banks"    placeholder="bankID" name="bankID"  >
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `BankID`, `BankName`, `Branch` FROM `tblbanks` order by BankID");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {?>
					<option value="<?php echo $row12['BankID']; ?>"><?php echo $row12['BankID']."- ".$row12['BankName']."  ".$row12['Branch'] ; ?></option>
						<?php   }
						} else {
						  //echo "0 results";
						} ?> 
					</select>
                     
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="ff" placeholder="Bank AccountNo" name="BankAccountNo" >
                    <label for="floatingName">Bank AccountNo:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
          <div class="col-md-3">
                  <div class="form-floating">
                    
					 <select type="text" class="form-control" id="ff" placeholder="Account Type" name="AccountTypeID" >
					<option value=""><option>
					<option value="1">Cheque</option>
					<option value="2">Savings</option>
					<option value="3">Current</option>
					
					</select>
                    <label for="floatingName">Account Type:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				   <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Account Holder Name" name="AccountHolderName" >
                    <label for="floatingName">Account Holder Name:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  

				  
				  <div class="col-xs-12 col-md-8">
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="Address" id="floatingTextarea" name="Comments" style="height: 60px;"></textarea>
                    <label for="floatingTextarea">Comments</label>
                  </div>
                </div>

               
      
                
                <div class="text-center">
                  <button type="submit" class="btn btn-warning" name="submit" style="width: 49%;">Add New Beneficiary</button>
                  <button type="reset" class="btn btn-secondary" style="width: 49%;">Reset</button>
                </div>
                
 <?php               
                }

}else{
echo "<script> alert('There was an Error, Member not added');
window.location.href='index.php';
</script>";	
}
?>
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
<script>
 $(document).ready(function(){
    $('#single').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});

$('#banks').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});

$('#GuardianID').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});
$('#NextOfKinID').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});
$('#postofficeID').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});

});
</script>
</body>

</html>
<?php
}else{
    header('Location: https://fairlifeadmin.com/index.php');
}

?>
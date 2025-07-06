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



?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Fund Reports</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 
<script src='jquery-3.2.1.min.js' type='text/javascript'></script>
        <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>

        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

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
      <h1>Query Design</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Design</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Design Query</h5>
			  
			  <form class="row g-3 needs-validation" id="user_form" method="post" action="" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				
					 <select type="text" class="form-control" id="design"   placeholder="MemberID" name="MemberID"  required>
					<option value="1" selected>Fund</option>
					<option value="2" selected>Beneficiary</option>
					<option value="3" selected>Employer</option>
					<option value="4" selected>Complex Design</option>
						 
					</select>
                    
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>	

                   <h5 class="card-title">Fund Options</h5>
				    <select type="text" class="form-control" id="design"   placeholder="MemberID" name="MemberID"  required>
					<option value="1" selected>Balances</option>
					<option value="2" selected>Transactions Report</option>
					<option value="3" selected>Members</option>
					</select>
                     <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>				  

  </form><!-- End floating Labels Form -->
 </div>
 </div>
        <br/>
		
<div class="card col-lg-12" style="">
<div class="card-body">
<h5 class="card-title">Design Query</h5>
 <form class="row g-3 needs-validation" id="user_form" method="post" action="" enctype="multipart/form-data" novalidate>
      
 <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="tmembers" placeholder="AdHocPayment" value="0" name="AdHocPayment" required>
                    <label for="floatingName">Total Members:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>				  
				  
</form>				  
</div>
 </div>
				
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  <div class="col-md-12" id="benoption">
					<div class="form-floating"  >
					
					<h5 class="card-title">Beneficiary Options</h5>
				    <select type="text" class="form-control" id="design"   placeholder="MemberID" name="MemberID"  required>
					<option value="1" selected>Balance</option>
					<option value="2" selected>Transactions Report</option>
					<option value="3" selected>Print Member File</option>
					</select>
                     <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-12" id="emploption">
					<div class="form-floating"  >
					
					<h5 class="card-title">Employer Options</h5>
				    <select type="text" class="form-control" id="design"   placeholder="MemberID" name="MemberID"  required>
					<option value="1" selected>Balances</option>
					<option value="2" selected>Transactions Report</option>
					<option value="3" selected>Members</option>
					</select>
                     <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="tapproved" placeholder="PaymentDate" value="0.00" name="PaymentDate" required>
                    <label for="floatingName">Total Approved:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="trunning" placeholder="AdHocPayment" value="0.00" name="AdHocPayment" required>
                    <label for="floatingName">Cuurent Balance:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="tmembers" placeholder="AdHocPayment" value="0" name="AdHocPayment" required>
                    <label for="floatingName">Total Members:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
              </form><!-- End floating Labels Form -->



            </div>
          </div>

<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Fund Beneficiary Movement</h5>
			  
			  <form class="row g-3 needs-validation" id="user_form" method="post" action="" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-6">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="fundsid"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `RetirementFundID`, `FundName` FROM `tblretirementfunds1` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
						$fundname = $row12['FundName'];
						$retirementfund = $row12['RetirementFundID'];
?>
					<option value="<?php echo $row12['RetirementFundID']; ?>"><?php echo $row12['FundName'] ; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

  	             <div class="col-md-6">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="memberid"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `RetirementFundID`, `FundName` FROM `tblretirementfunds1` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
						$fundname = $row12['FundName'];
						$retirementfund = $row12['RetirementFundID'];
?>
					<option value="<?php echo $row12['RetirementFundID']; ?>"><?php echo $row12['FundName'] ; ?></option>
						<?php   }
						} else {
						  echo "0 results";
						} ?> 
					</select>
                    
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>				  


        <br/>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="tapproved" placeholder="PaymentDate" value="0.00" name="PaymentDate" required>
                    <label for="floatingName">Total Approved:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="trunning" placeholder="AdHocPayment" value="0.00" name="AdHocPayment" required>
                    <label for="floatingName">Cuurent Balance:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="tmembers" placeholder="AdHocPayment" value="0" name="AdHocPayment" required>
                    <label for="floatingName">Total Members:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
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

<script>
$(document).on("click",".dnew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
<script>
        $(document).ready(function(){

$('#design').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});	
    $('#memberid').select2();  
$('#fundsid').select2();	
});
</script>
		
<script>
$('#single').change(function() {
    var jk2 = $('#single option:selected').val();

  var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "fundreport1.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var running = (dataResult.running);
						//running = running.toFixed(2);
						$("#trunning").val("");
						running = parseFloat(running).toFixed(2);
						$("#trunning").val(running);
						var approved = (dataResult.approved);
						//approved = approved.toFixed(2);
						approved = parseFloat(approved).toFixed(2);
					  $("#tapproved").val("");
					   $("#tapproved").val(approved);
					   
					   var memberz = (dataResult.members);
					   
					   $("#tmembers").val(memberz);


	                        			
                     //   location.reload();						
					}
					else if(dataResult.statusCode==201){
                      var error = (dataResult.error);
					   alert(error);
					}
			}
		});
});</script>		
		
		
<script>
    $(function(){
        $(".monthly").click(function(){
            var postid = $("#dates").val();
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'mprocess.php',
                data:{'id':postid},
                success:function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var succ = (dataResult.datas);
						alert(succ);

                        location.reload();						
					}
					else if(dataResult.statusCode==201){
						var error = (dataResult.datas);
					   alert(error);
					}else if(dataResult.statusCode==203){
						var mid = (dataResult.datas);
					   alert("Please Update Recent Transaction for = "+mid);
					}
            
                }
            });

        });
    });

</script>
		
		
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
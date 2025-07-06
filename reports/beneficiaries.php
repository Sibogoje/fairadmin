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

  <title>Beneficiary Reports</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='jquery-3.2.1.min.js' type='text/javascript'></script>

        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

  <!-- Favicons -->
  <link href="https://fair.liquag.com/" rel="icon">
  <link href="https://fair.liquag.com/" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>
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

</style>

 
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Beneficiary Reports</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Reports</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Choose Period First</h5>
		
              

			  <form class="row g-3 needs-validation" id="user_form" method="post" action=""  enctype="multipart/form-data" novalidate>
			  
			   <div class="col-md-6">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date1" placeholder="AdHocPayment" value="0" name="date1" required>
                    <label for="floatingName"><b>From:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				   <div class="col-md-6">
                  <div class="form-floating">
                    <input type="date"  class="form-control" id="date2" placeholder="AdHocPayment" value="0" name="date2" required>
                    <label for="floatingName"><b>To:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

  	      <div class="col-md-12">
				
          <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"    placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `MemberID`, `MemberNo`, `MemberFirstname`, `MemberSurname` FROM `tblmembers` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
						?>
					<option value="<?php echo $row12['MemberID']; ?>"><?php echo "<b>".$row12['MemberNo']."</b>  ".$row12['MemberFirstname']."  ".$row12['MemberSurname']; ?></option>
						<?php   }
						} else {
						 // echo "0 results";
						} ?> 
					</select>
                    
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>	

				  


        <br/>
               <div class="col-md-6">
               <div class="text-center">
                  <button type="submit" formaction="beneficiarycsv.php"  class="btn btn-warning add" id="" data-link="" data-id="rr"  style="width: 100%;"><b>Download Beneficiary Statement CSV</b></button>
               </div>
               </div>
               <div class="col-md-6">
               <div class="text-center">
                  <button type="submit" formaction="benreportprint.php" formtarget="_blank"  class="btn btn-warning add" id="" data-link="" data-id="rr"  style="width: 100%;"><b>Download Beneficiary Report PDF</b></button>
               </div>
               </div>
			   
				  
				  
              </form><!-- End floating Labels Form -->
              
              
   <div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Fund Statement</h5>
			  
			  <form class="row g-3 needs-validation" id="fff" method="post" action="" target="" enctype="multipart/form-data" novalidate>


          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Opening" placeholder="AdHocPayment" value="0" name="Opening" readonly>
                    <label for="floatingName"><b>Opening Amount:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="TransferIn" placeholder="AdHocPayment" value="0" name="TransferIn" readonly>
                    <label for="floatingName"><b>TransferIn Fees:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Regular" placeholder="AdHocPayment" value="0" name="Regular" readonly>
                    <label for="floatingName"><b>Total Regular :</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Adhoc" placeholder="AdHocPayment" value="0" name="Adhoc" readonly>
                    <label for="floatingName"><b>Total Adhoc:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Transfee" placeholder="AdHocPayment" value="0" name="Transfee" readonly>
                    <label for="floatingName"><b>Total Transaction Fees:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Monthly" placeholder="AdHocPayment" value="0" name="Monthly" readonly>
                    <label for="floatingName"><b>Total Monthly Fees:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Admin" placeholder="AdHocPayment" value="0" name="Admin" readonly>
                    <label for="floatingName"><b>Total Admin Fees:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Interest" placeholder="AdHocPayment" value="0" name="Interest" readonly>
                    <label for="floatingName"><b>Total Interest Fees:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Additional" placeholder="AdHocPayment" value="0" name="Additional" readonly>
                    <label for="floatingName"><b>Total Add. Capital:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

          <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" step="0.01" class="form-control" id="Other" placeholder="AdHocPayment" value="0" name="Other" readonly>
                    <label for="floatingName"><b>Total Other:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
			  
			  
  	             	
			
			     <div class="text-center">
                  <button type="submit" name="ggg" class="btn btn-success direct" id=""  style="width: 100%;"><b>Beneficiary Statement</b></button>
               </div>
				  
				  
              </form><!-- End floating Labels Form -->

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body" id="jj">
              <h5 class="card-title">Transaction History Table</h5>
              <!-- Table with stripped rows -->
              

             
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

            </div>
          </div>
		              
              
              
              
<br><br>
              <!-- Quill Editor Default -->




              <!-- End Quill Editor Default -->

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
  
	<!-- Select2 CSS --> 

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>



<Script>
 $(document).ready(function(){
	 $("#single").change(function(){
		 
		var from = $('#date1').val(); 
		var to = $('#date2').val();
        $(this).find("option:selected").each(function(){
            var annex = $(this).attr("value");
			   if(annex != "") {
				  // alert(sss);
      $.ajax({
        url:"benhistory.php",
        data:{c_id:annex, from:from, to:to},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#jj").html(resp);
        }
      });
    } else {
      $("#jj").html("No History");
    }
 
        });
    }).change();
	});
</script>
		

<script>
$(document).ready(function(){


$('#single').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});



});
</script>
		
<script>
	 $("#single").change(function(){
		 $(this).find("option:selected").each(function(){
         var annex = $(this).attr("value");
      if(annex != "") {
  var data = $("#user_form").serialize();
  // $("html").addClass("loading");
		$.ajax({
			data: data,
			type: "post",
			url: "benereport.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){

            var ttfundmembers = (dataResult.ttfundmembers);
						$("#Clients").val("Null");
						$("#Clients").val(ttfundmembers);

            var ClientsExit = (dataResult.ClientsExit);
						$("#ClientsExit").val("Null");
						$("#ClientsExit").val(ClientsExit);


            var ttopeningrow = (dataResult.ttopeningrow);
						$("#Opening").val("Null");
						$("#Opening").val(ttopeningrow);

            var ttinrow = (dataResult.ttinrow);
						$("#TransferIn").val("Null");
						$("#TransferIn").val(ttinrow);

            var ttregularrow = (dataResult.ttregularrow);
						$("#Regular").val("Null");
						$("#Regular").val(ttregularrow);

            var ttadhocgrow = (dataResult.ttadhocgrow);
						$("#Adhoc").val("Null");
						$("#Adhoc").val(ttadhocgrow);

            var ttfeegrow = (dataResult.ttfeegrow);
						$("#Transfee").val("Null");
						$("#Transfee").val(ttfeegrow);

            var ttmonthlyrow = (dataResult.ttmonthlyrow);
						$("#Monthly").val("Null");
						$("#Monthly").val(ttmonthlyrow);

            var ttadminrow = (dataResult.ttadminrow);
						$("#Admin").val("Null");
						$("#Admin").val(ttadminrow);

            var ttintrow = (dataResult.ttintrow);
						$("#Interest").val("Null");
						$("#Interest").val(ttintrow);

            var ttaddrow = (dataResult.ttaddrow);
						$("#Additional").val("Null");
						$("#Additional").val(ttaddrow);

            var ttotherrow = (dataResult.ttotherrow);
						$("#Other").val("Null");
						$("#Other").val(ttotherrow);

           
					
			}
    }
		
});
} else {
      $("#jj").html("No Beneficiary Selected");
    }

  });  
}).change();

</script>		
	
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
<!DOCTYPE html>
<?php
session_start();
require_once 'scripts/connection.php';


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Fund Reports</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='jquery-3.2.1.min.js' type='text/javascript'></script>



  <!-- Favicons -->
  <link href="https://fairlifeadmin.com/fix/" rel="icon">
  <link href="https://fairlifeadmin.com/fix/" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">


      

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
<style>

  .bigger {

      margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  
      
  }

@media screen and (max-width: 600px) {
  .bigger {

      margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80%;
  
 
  }
}
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 25%;
  margin-top: 10px;
  margin-bottom: 15px;
}
.center2 {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
  text-align: center;
}
</style>

</head>

<body>

  <!-- ======= Header ======= -->


  <main id="main" class="main">


<!-- New beneficiary form-->
<div class="card col-lg-3 bigger" style="">
        <div class="pagetitle">

      <img src="logo.png" alt="Paris" class="center">
      
      <h3 class="center2"><b></b>FairLife Login</b></h3>

    </div><!-- End Page Title -->
            <div class="card-body">
        
			  
			  <form class="row g-3" id="loginform" method="post"  enctype="multipart/form-data" >


        <br/>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="username" placeholder="PaymentDate" autocomplete="off"  name="username" required>
                    <label for="floatingName"><b>Username:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-12">
                  <div class="form-floating">
                    <input type="password" class="form-control" id="password" placeholder="AdHocPayment"  name="password" required >
                    <label for="floatingName"><b>Password:</b></label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>

   



	
				     <div class="text-center" >
                  <button type="button" id="login"  class="btn btn-warning add" id="addreport" data-link="" data-id="rr"  style="width: 100%;"><b>Login</b></button>
               </div>
			   
				  
				  
              </form><!-- End floating Labels Form -->
<br><br>


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



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
	<!-- Select2 CSS --> 

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
 $(document).ready(function(){      
	 $("#login").click(function(){
		 
		var user = $('#username').val(); 
		var pass = $('#password').val();

  var data = $("#loginform").serialize();
 //  $("html").addClass("loading");
		$.ajax({
			data: data,
			type: "post",
			url: "parse.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
					    var success = (dataResult.success);
					   // alert(success);
					   window.location.href = "dash.php";
					    
					}else if(dataResult.statusCode==201){
					    var exception = (dataResult.exception);
					    alert(exception);
					    
					}else{
					    var error = (dataResult.error);
					    alert(error);
					    
					}
			}
		});

    });
    
 }); 
  </script>



</body>

</html>
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

  <title>Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src='../jquery-3.2.1.min.js' type='text/javascript'></script>

        <link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

  <!-- Favicons -->
  <link href="https://fairlifeadmin.com/logo.png" rel="icon">
  <link href="https://fairlifeadmin.com/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
  <script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
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
th {

  vertical-align: top;
}
html {
    -webkit-transition: background-color 1s;
    transition: background-color 1s;
}
html, body {
    /* For the loading indicator to be vertically centered ensure */
    /* the html and body elements take up the full viewport */
    min-height: 100%;
}
.loading {
    /* Replace #333 with the background-color of your choice */
    /* Replace loading.gif with the loading image of your choice */
    background: rgba(0,0,0,0.8) url('progress.gif') no-repeat 50% 10%;
	margin: auto;
	position: fixed;

    /* Ensures that the transition only runs in one direction */
    -webkit-transition: background-color 0;
    transition: background-color 0.7;
}
body {
    -webkit-transition: opacity 1s ease-in;
    transition: opacity 1s ease-in;
}
html.loading body {
    /* Make the contents of the body opaque during loading */
    opacity: 0.5;

    /* Ensures that the transition only runs in one direction */
    -webkit-transition: opacity 0.5;
    transition: opacity 0.5;
}
</style>

  <script src="https://cdn.ckeditor.com/4.18.0/standard-all/ckeditor.js"></script>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Beneficiary Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Choose Beneficiary</h5>
			  
			  <form class="row g-3 needs-validation" id="user_form" method="post" action="profileprint.php" target="_blank"  enctype="multipart/form-data" novalidate>
			 

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"    placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT * FROM `tblmembers` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
						$MemberNo = $row12['MemberNo'];
						//$retirementfund = $row12['RetirementFundID'];
					    	
							

						?>
					<option value="<?php echo $row12['MemberNo']; ?>"><?php echo $row12['MemberNo']." - ".$row12['MemberSurname']."  ".$row12['MemberFirstname'] ; ?></option>
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
  <div class="text-center">
                  <button type="submit" class="btn btn-warning" name="printprofile" data-id="rr"  style="width: 100%;">Print Profile</button>
               </div>
				  
				  
              </form><!-- End floating Labels Form -->
<br><br>
              <!-- Quill Editor Default -->

		  
		  
		  	
	
             
			  
			  <form class="row g-3 needs-validation" id="fff" method="post" action="" target="" enctype="multipart/form-data" novalidate>
			  
  	             	
			
			     <div class="text-center">
                  <button type="buttton" name="ggg" class="btn btn-success direct" id=""  style="width: 100%;"><b>Beneficiary Profile Preview</b></button>
               </div>
				  
				  
              </form><!-- End floating Labels Form -->


            <div class="card-body" id="jj">
             
              <!-- Table with stripped rows -->
              

             
              <!-- End Table with stripped rows -->

            </div>


          
</div>
          </div>		  
		  



<!-- end of new beneficiary form -->
 
  </main><!-- End #main -->


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
  <script>
$('#xxx').click(function() {
    var jk2 = $('#single option:selected').val();
    var from = $('#date1').val(); 
		var to = $('#date2').val();
if (from!=""){
  //var dataz = $("#user_form").serialize();
		$.ajax({
			data    : $("#user_form").serialize(),
			type: "POST",
			url: "beneficiarycsv.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
					//	var rsuccess = (dataResult.rsuccess);
	                       alert(rsuccess);  			
                    // location.reload();						
					}
					else if(dataResult.statusCode==201){
                     // var rerror = (dataResult.rerror);
					//   alert(rerror);
					}
			}
});}else{
alert("Please select fund to save report");
}
});</script>


<Script>

	 $("#single").change(function(){
		 

        $(this).find("option:selected").each(function(){
            var annex = $(this).attr("value");
			   if(annex != "") {
				  // alert(sss);
      $.ajax({
        url:"profilehistory.php",
        data:{c_id:annex},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#jj").html(resp);
        }
      });
    } else {
      $("#jj").html("No Beneficiary Selected");
    }
 
        });
    }).change();

</script>
		


<script>
        $(document).ready(function(){
$("html").removeClass("loading");	

$('#single').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});


});
</script>

<script>
$('#addreport').click(function() {
    var jk2 = $('#single option:selected').val();
if (jk2!=""){
  var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "addreport.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var rsuccess = (dataResult.rsuccess);
	                       alert(rsuccess);  			
                     location.reload();						
					}
					else if(dataResult.statusCode==201){
                      var rerror = (dataResult.rerror);
					   alert(rerror);
					}
			}
});}else{
alert("Please select fund to save report");
}
});</script>	

	

		
</body>

</html>
<?php
}else{
    header('Location: https://fairlifeadmin.com/index.php');
}

?>
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

  <title>Other Transactions</title>
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
      <h1>Other Transactions</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Transactions</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Choose Transaction Type</h5>
			  
			  <form class="row g-3 needs-validation" id="user_form" method="post" action="" enctype="multipart/form-data" novalidate>
			  



  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="transtype"    placeholder="MemberID" name="transtype"  required>
					 <option value="" selected ></option>
					<option value="1" >Rerversal Credit</option>
					<option value="2" >Rerversal Debit</option>
					<option value="3" >Other</option>
					</select>
                    <label for="floatingName">Choose Transaction Type:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>	
<br/>



  	             <div class="col-md-12" id="transid">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="sss"    placeholder="fundid" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT DISTINCT `MemberID`, `MemberNo`, `MemberFirstName`, `MemberSurname` FROM `tblmembers` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
							?>
					<option value="<?php echo $row12['MemberID']; ?>" ><?php echo $row12['MemberFirstName']." ".$row12['MemberSurname']." - ".$row12['MemberNo'] ; ?></option>
						<?php   }
						} else {
						//  echo "0 results";
						} ?> 
					</select>
                    
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>


   	             <div class="col-md-12" id="reco">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="records"    placeholder="records" name="records"  required>

					</select>

				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  

				   <div class="col-md-12" id="amount1">
                  <div class="form-floating">
                    <input type="number"  class="form-control" id="amount" placeholder="PaymentDate" value="0.00" name="tapproved" required>
                    <label for="floatingName">Reversal Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-12" id="comments1">
                  <div class="form-floating">
                    <input type="text"  class="form-control" id="comments" placeholder="PaymentDate"  name="tapproved" required>
                    <label for="floatingName">Comments:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  				     <div class="text-center">
                  <button type="button" class="btn btn-warning add" id="addreport" data-link="" data-id="rr"  style="width: 100%;"><b>Reverse Transaction [Include Charges if Any]</b></button>
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
    $(function(){
        $(".add").click(function(){
            var amount = $("#amount").val();
			var account = $("#records").val();
			var comments = $("#comments").val();
			var sss = $("#sss").val();
			var transtype = $("#transtype").val();
			var types = 0;
			if (transtype == 1){
				types = 1;
			}else if (transtype == 1){
				types = 0;
			}

              $.ajax({
                type:'POST',
                url:'reverse.php',
                data:{'amount':amount, 'member': sss, 'comments': comments, 'type': types},
                success:function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var succ = (dataResult.rsuccess);
						alert(succ);

                        location.reload();						
					}
					else if(dataResult.statusCode==201){
						var error = (dataResult.rerror);
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
		
<script>

</script>
<script>
        $(document).ready(function(){
$('#sss').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});
$('#records').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});
$('#transid').hide();
$('#myDiv').hide();


 $("#transtype").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue=="1"){
				$('#transid').show();
            } else if (optionValue=="2"){
              // $('#transid').show();
			  $('#transid').show();
            }else if (optionValue=="3"){
				$('#transid').hide();
				$('#reco').hide();

				$('#amount1').hide();
			}else{
				$('#transid').hide();
				$('#reco').hide();

				$('#amount1').hide();
				
			}
        });
    }).change();
$('#reco').hide();	
	 $("#transid").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue1 = $(this).attr("value");
            if(optionValue1 !=""){
				$('#reco').show();

				$('#amount1').show();
            }else{
				$('#reco').hide();

				$('#amount1').hide();
				
			}
        });
    }).change()
	
	

});
</script>
<Script>
$(document).ready(function(){
	 $("#sss").change(function(){
        $(this).find("option:selected").each(function(){
            var sss = $(this).attr("value");
			   if(sss != "") {
				  // alert(sss);
      $.ajax({
        url:"gettransactions.php",
        data:{c_id:sss},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#records").html(resp);
        }
      });
    } else {
      $("#records").html("<option value=''>No Transaction Loaded Yet</option>");
    }
 
        });
    }).change();
	});
</script>

</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
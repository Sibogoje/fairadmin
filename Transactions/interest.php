<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';

////////insert new 



?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Interests</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

 <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>
 
  <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
        
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
      <h1>Interest Sources</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Interests</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Choose Interest Source</h5>
			  
			  <form class="row g-3 needs-validation" method="post" action="" id="interestform" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single" style="width: 100%;"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblinterestsources` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['InterestSourceID']; ?>"><?php echo $row12['InterestSource'] ; ?></option>
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

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="dates" placeholder="PaymentDate" value="" name="dates" required>
                    <label for="floatingName">Received Date:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="amount" placeholder="AdHocPayment" value="0.00" name="amount" required>
                    <label for="floatingName">Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  

                <div class="text-center">
                  <button type="button" id="intrs"  class="btn btn-warning interest" style="width: 100%;" name="submit">Apply Interest<b>[Apply Interest before any Deductions]</b></button>
                  
                </div>
          <div class="card col-lg-12" >
            <div class="card-body">
              <div class="logs" id="logs">


              </div>

            </div>
          </div>
              </form><!-- End floating Labels Form -->



            </div>
          </div>

          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Interest Received (last 12 Months)</h5>
              <!-- Table with stripped rows -->
              <div class="table responsive">
              <table class="table table-striped datatable nowrap" id="jj" style="width: 100%;" >
                <thead>
                  <tr>
                    <th scope="col">Start Date</th>
                    <th scope="col">Allocation Date</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Allocated</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
				
$prevmon = date("m");
$current = $prevmon - 1;
$year = date("Y");

				
				
$stmt = $conn->prepare("SELECT * FROM `tblinterestreceived` ORDER BY InterestDate DESC LIMIT 12 ");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {
  $ver = "";
if ($row['Allocated'] == 1){
  $ver = "Yes";
}else{
  $ver = "No";
}
//$uid = $row['MEMBERNO'];
?>

                  <tr>
				  <td  scope="row"><?php echo $row['InterestStartDate']; ?></td>
				  <td  scope="row"><?php echo $row['InterestDate']; ?></td>
				  <td><?php echo $row['InterestAmount']; ?></td>
				  <td><?php echo $ver; ?></td>
					

				
                  </tr>
<?php   }
} else {
  echo "<b style='color: red;'>No Interest Paid For ".$current."/".$year."</b>";
} ?>                 
                </tbody>
              </table>
	</div>
				
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
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <!-- Vendor JS Files -->
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

  <script>
$(document).ready(function() {
    $('#jj').DataTable( {
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        dom: 'Blfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        responsive: true,
       
        
    } );
    
  
} );
</script>


  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

<script>
$(document).on("click",".dnew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
<script>
$("#single").select2({

});
        </script>
		
<script>	
   $(".interest").click(function(){
    $("#intrs").attr("disabled", true);
   
  var data = $("#interestform").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "calinterest.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var success1 = (dataResult.dones);
            alert(success1);
           // $("#intrs").attr("disabled", false);
                location.reload();	
                //console.log(success1);	
               // $(".logs").html(success1);    				
					}
					else if(dataResult.statusCode==201){
                      var error = (dataResult.error);
					   alert(error);
					}
			}
		});
});</script>			
		

		
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
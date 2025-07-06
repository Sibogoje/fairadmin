<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Scheduled Payment</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
 
    <script src=" https://code.jquery.com/jquery-3.5.1.js"></script>
	<!-- Select2 CSS --> 

        <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>

        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

  <!-- Favicons -->
  <link href="https://fairlifeadmin.com/fix/" rel="icon">
  <link href="https://fairlifeadmin.com/fix/" rel="apple-touch-icon">

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



   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>

 <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Scheduled Payment</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Scheduled Payments</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->


          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Scheduled Payments</h5>
              <!-- Table with stripped rows -->
              <div class="table responsive">
              <table class="table table-striped datatable" style="width: 100%;" id="jj">
                <thead>
                  <tr>
                    <th scope="col">ID</th>

                    <th scope="col">Full Name</th>
                    <th scope="col">FixedPaymentAmount</th>
					<th scope="col">MaxPaymentAmount</th>
					<th scope="col">Balance</th>
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("
SELECT MemberID, MemberNO, MemberSurname, MemberFirstname, FixedPaymentAmount, MaxPaymentAmount, balance FROM member_fees where FixedPaymentAmount != '0' OR FixedPaymentAmount != NULL

");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

$uid = $row['MemberNo'];


    
?>

                  <tr>
				  <th scope="row"><?php echo $row['MemberNo']; ?></th>
                    <th scope="row"><?php echo $row['MemberSurname']." ".$row['MemberFirstname']; ?></th>
					<td><?php echo $row['FixedPaymentAmount']; ?></td>
					<td><?php echo $row['MaxPaymentAmount'];  ?></td>
					<td><?php echo   $balance = $row['balance'];  ?></td>
				
					

			<td class="no-wrap">
			<button type="button" data-link="schefees.php?id=<?php echo $row['MemberID']; ?>" class="btn btn-outline-warning fees"  title="Process" data-id="<?php echo $row['MemberID']; ?>"><i class="bi bi-receipt"></i></button>
			</td>
                  </tr>
<?php  

}
 

} else {
  echo "0 results";
} ?>                 
                </tbody>
              </table>
              </div>
              <!-- End Table with stripped rows -->
                <div class="text-center">
                  <input type="date" id="regdate"  class="regdate" style="width: 100%;" name="process"></button>
                  
                </div>
                
                
                <br><br>
  <div class="text-center">
                  <button type="submit" id="regslar"  class="btn btn-warning regular" style="width: 100%;" name="process">Process All</button>
                  
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

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

<script>
$(document).on("click",".dnew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>

		
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
		
		
<script>
    $(function(){
        $(".regular").click(function(){
            var postid = "Process";
            var ddate = $("#regdate").val();
            $("#regslar").attr("disabled", true);
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'scheduleprocess.php',
                data:{'id':postid, 'ddate': ddate},
                success:function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var succ = (dataResult.datas);
						alert(succ);

                        location.reload();	
                        $("#regslar").attr("disabled", false);					
					}
					else if(dataResult.statusCode==201){
						var error = (dataResult.datas);
					   alert(error);
             $("#regslar").attr("disabled", true);	
					}else if(dataResult.statusCode==203){
						var mid = (dataResult.datas);
					   alert("Please Update Recent Transaction for = "+mid);
             $("#regslar").attr("disabled", true);	
            	
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
    header('Location: https://fairlifeadmin.com/fix/index.php');
}

?>
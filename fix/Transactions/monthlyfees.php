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

if (isset($_POST['submit'])){
	
	
$MemberID = $_POST['MemberID']; 
$PaymentDate = $_POST['PaymentDate'];
$Details = $_POST['Details'];
$AdHocPayment = $_POST['AdHocPayment'];
$Comments = $_POST['Comments'];




$stmt = $conn->prepare("insert into `u747325399_fairlife`.`tbltempadhocpayments1` (

  `MemberID`,
  `PaymentDate`,
  `Details`,
  `AdHocPayment`,
  `Comments`
)

VALUES
  (

    ?,
    ?,
    ?,
    ?,
	?
    

  );");
$stmt->bind_param("sssss", 
$MemberID, 
$PaymentDate,
$Details,
$AdHocPayment,
$Comments
);
$stmt->execute();

//echo "New records created successfully";
header("location: adhoc.php");
$stmt->close();
$conn->close();
}else{
	//
}


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Monthly Fees</title>
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
      <h1>Apply Monthly fees</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Apply Monthly fees</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
      <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Monthly fees</h5>
 <form class="row g-12 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
			    <div class="col-md-12">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="dates" placeholder="PaymentDate" value="" name="PaymentDate" required>
                    <label for="floatingName">Date To Apply Fees:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				    <div class="text-center">
                  <button type="button" id="mon"  class="btn btn-warning monthly" style="width: 100%;" name="process">Apply Monthly Fees <b>[Make sure Interest has been allocated]</b></button>
                  
                </div>
				  </form>
</div></div>

          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Balances</h5>
              <!-- Table with stripped rows -->
              <div class="table">
              <table class="table table-striped datatable nowrap" id="jj" style=" width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">ID</th>

                    <th scope="col">Full Name</th>
                    <th scope="col">AdminPercent</th>
					<th scope="col">FixedMonthlyFee</th>
					<th scope="col">Balance</th>
				
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("
SELECT MemberID, MemberNO, MemberSurname, MemberFirstname, AdminPercent, FixedMonthlyFee, balance FROM member_fees 

");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

$uid = $row['MemberNo'];


    
?>

                  <tr>
				  <td><?php echo $row['MemberNo']; ?></td>
                    <th scope="row"><?php echo $row['MemberSurname']." ".$row['MemberFirstname']; ?></th>
					<td><?php echo $row['AdminPercent']; ?></td>
					<td><?php echo $row['FixedMonthlyFee'];  ?></td>
					<td><?php echo   $balance = $row['balance'];  ?></td>
				
					


                  </tr>
<?php  

}
 

} else {
  echo "0 results";
} ?>                 
                </tbody>
              </table>
			  </div>
				  </br>
				  </br>
              <!-- End Table with stripped rows -->

				
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


<script>
$(document).on("click",".dnew",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
<script>
        $(document).ready(function(){
    $('#single').select2();        

        });
        </script>
		
		
		
		
<script>
    $(function(){
        $(".monthly").click(function(){
            var postid = $("#dates").val();
            $("#mon").attr("disabled", true);
            if (postid !=""){
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
                        $("#mon").attr("disabled", false);					
					}
					else if(dataResult.statusCode==201){
						var error = (dataResult.error);
					   alert(error);
					}else if(dataResult.statusCode==203){
						var mid = (dataResult.error2);
					   alert("Please Update Recent Transaction for = "+mid);
					}
            
                }
            });
}else{
    alert("Please choose date first");
    
}
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
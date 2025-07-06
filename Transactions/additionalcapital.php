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
$AdHocPayment = $_POST['Amount'];
$Comments = $_POST['Comments'];




$stmt = $conn->prepare("insert into `tbltempcapital` (

  `MemberID`,
  `PaymentDate`,
  `Details`,
  `Amount`,
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
header("location: additionalcapital.php");
$stmt->close();
$conn->close();
}else{

}


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Additional Capital</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 
<script src='jquery-3.2.1.min.js' type='text/javascript'></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>

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


 <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

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
      <h1>Additional Capital</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Ad Hoc Payment</a></li>
          <li class="breadcrumb-item active">Additional Capital Payments</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->

<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">Additional Capital Payments</h5>
			  
			  <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblmembers` WHERE `Terminated`='0'");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['MemberID']; ?>"><?php echo $row12['MemberNo']." -".$row12['MemberSurname']. ", ".$row12['MemberFirstname'] ; ?></option>
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


        <br/>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="ff" placeholder="PaymentDate" value="" name="PaymentDate" required>
                    <label for="floatingName">Payment Date:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="ff" placeholder="AdHocPayment" value="0.00" name="Amount" required>
                    <label for="floatingName">Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Details" value="Additional Capital" name="Details" required>
                    <label for="floatingName">Details:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  
				
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Comments" value="" name="Comments" >
                    <label for="floatingName">Comments:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  

                <div class="text-center">
                  <button type="submit"  class="btn btn-warning" style="width: 100%;" name="submit">Save Capital Intoruduction Data</button>
                  
                </div>

              </form><!-- End floating Labels Form -->



            </div>
          </div>
          <?php if ($role == 'admin' ){ ?> 

          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Additional Capital-Unprocessed</h5>
              <!-- Table with stripped rows -->
              <table class="table datatable" id="jj" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th scope="col">ID</th>

                    <th scope="col">MemberID</th>
                     <th scope="col">Date</th>
                    <th scope="col">Amount</th>
					<th scope="col">Comment</th>


					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("SELECT * FROM `tbltempcapital`" );

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

//$uid = $row['MemberNo'];
?>

                  <tr>
                    <th scope="row"><?php echo $row['CapitalID']; ?></th>

                    <td><?php echo $row['MemberID']; ?></td>
                    <td><?php echo $row['PaymentDate']; ?></td>
					
					<td><?php echo $row['Amount']; ?></td>
					<td><?php echo $row['Comments']; ?></td>



					<td class="no-wrap">
			
			 <button type="button" data-link="fees.php?id=<?php echo $row['CapitalID']; ?>" class="btn btn-outline-warning fees" name="<?php echo $row['CapitalID']; ?>" title="Process" data-id="<?php echo $row['CapitalID']; ?>"><i class="bi bi-receipt"></i></button>
			 <button type="button" data-link="fees.php?id=<?php echo $row['CapitalID']; ?>" class="btn btn-outline-danger del" name="<?php echo $row['CapitalID']; ?>" title="Delete" data-id="<?php echo $row['CapitalID']; ?>"><i class="bi bi-trash"></i></button>
			
					</td>
                  </tr>
<?php   }
} else {
 // echo "0 results";
} ?>                 
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
  <div class="text-center">
       
                  
                </div>
            </div>
          </div>

<?php } ?>
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
        $(document).ready(function(){
    $('#single').select2();        

        });
        </script>
	
<script>
    $(function(){
        $(".fees").click(function(){
            var postid = $(this).attr("data-id");
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'capitalprocess.php',
                data:{'id':postid},
                success:function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var res = (dataResult.datas);
						alert(res);

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

<script>
    $(function(){
        $(".del").click(function(){
            var postid = $(this).attr("data-id");
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'capitaldelete.php',
                data:{'id':postid},
                success:function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						var res = (dataResult.rsuccess);
						alert(res);

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
		
		
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
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

  $stmtb = $conn->prepare("SELECT MemberNo,  MemberSurname, MemberFirstname FROM `tblmembers` WHERE `MemberID`=?");
  $stmtb->bind_param("s", $MemberID);
  $stmtb->execute();
  $resultb = $stmtb->get_result();
  if ($resultb->num_rows > 0) {
  while($rowb = $resultb->fetch_assoc()) {
    
    $Name = $rowb['MemberNo']."--".$rowb['MemberSurname']." ".$rowb['MemberFirstname'];
     
$stmt = $conn->prepare("insert into `tbltempadhocpayments` (

  `MemberID`,
  `Name`,
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
    ?,
	?
    

  );");
$stmt->bind_param("ssssss", 
$MemberID, 
$Name, 
$PaymentDate,
$Details,
$AdHocPayment,
$Comments
);
$stmt->execute();
    
  }} 



//echo "New records created successfully";
header("location: adhoc.php");
$stmt->close();
$conn->close();
}else{

}


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Adhoc Payments</title>
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
      <h1>Ad Hoc Payment</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Ad Hoc Payment</a></li>
          <li class="breadcrumb-item active">Adhoc Payments</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->


          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Requests From Clients</h5>
              <!-- Table with stripped rows -->
              <div class="table-responsive">

<table class="table datatable" id="jj" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th hidden scope="col">MemberID</th>
            <th scope="col">ID-Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Comment</th>
            <th scope="col">Letter</th>
            <th scope="col">Support Docs</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $stmt = $conn->prepare("SELECT * FROM `clientr`" );
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td hidden scope="row"><?php echo $row['memberid']; ?> </td>
                    <td scope="row"><?php echo $row['name']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td>
                        <a href="<?php echo $row['file']; ?>" class="btn btn-warning" download target="_blank">Download</a>
                    </td>
                    <td>
                        <?php
                        // Loop through and display buttons for available supporting documents
                        for ($i = 1; $i <= 3; $i++) {
                            $supportDocKey = 'support' . $i;
                            if (!empty($row[$supportDocKey])) {
                                ?>
                                <a href="<?php echo "https://liquag.com/dev/fairlife/mobile/".$row[$supportDocKey]; ?>" class="btn btn-warning" download target="_blank"><?php echo $i; ?></a>
                                <?php
                            }
                        }
                        ?>
                    </td>
                    <td class="no-wrap">
                        <button type="button" data-link="fees.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning fees" name="<?php echo $row['id']; ?>" title="Process" data-id="<?php echo $row['id']; ?>"><i class="bi bi-check-all"></i></button>
                        <button type="button" data-link="fees.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger del" name="<?php echo $row['id']; ?>" title="Delete" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php
            }
        } else {
            // Handle the case where there are no rows
        }
        ?>
    </tbody>
</table>


              </div>
              <!-- End Table with stripped rows -->
  <div class="text-center">
                  <button type="submit"  class="btn btn-warning" style="width: 100%;" name="process">Process All</button>
                  
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
                url:'adhocprocess1.php',
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
					}else if(dataResult.statusCode==210){
						var r_error = (dataResult.retrieveerror);
            alert(r_error);
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
                url:'adhocdelete1.php',
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
						var mid = (dataResult.rerror);
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
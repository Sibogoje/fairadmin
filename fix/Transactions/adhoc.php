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

 
 // Query to check the role of the user
$selectRole = $conn->prepare("SELECT role FROM realuzer WHERE username = ?");
$selectRole->bind_param("s", $gg);
$selectRole->execute();
$selectRole->bind_result($userRole);
$selectRole->fetch();
$selectRole->close();

// Check if the user has the 'admin' role
$isAdmin = ($userRole === 'admin');


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

<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">New Adhoc-Payments</h5>
			  
			  <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT MemberNo, MemberID, MemberSurname, MemberFirstname FROM `tblmembers` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {

						?>
					<option value="<?php echo $row12['MemberID']; ?>"><?php echo $row12['MemberNo']." -".$row12['MemberSurname']. ", ".$row12['MemberFirstname'] ; ?></option>
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
                    <input type="number" step="0.01" class="form-control" id="ff" placeholder="AdHocPayment" value="0.00" name="AdHocPayment" required>
                    <label for="floatingName">Amount:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Details" value="Ad Hoc Payment" name="Details" required>
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
                  <button type="submit"  class="btn btn-warning" style="width: 100%;" name="submit">Save Adhoc Data</button>
                  
                </div>

              </form><!-- End floating Labels Form -->



            </div>
          </div>


          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">AdHoc Payments-Unprocessed</h5>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
              <table class="table datatable" id="jj" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    
                    <th hidden scope="col">MemberID</th>
                    
                    <th scope="col">Date</th>
                    <th scope="col">Amount</th>
				    <th scope="col">Comment</th>


					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("SELECT * FROM `tbltempadhocpayments`" );

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

//$uid = $row['MemberNo'];
?>

                  <tr>
                    

                    <td  scope="row"><?php echo $row['MemberID']; ?> </td>
                    
					<td scope="row"><?php echo $row['PaymentDate']; ?></td>
					<td><?php echo $row['AdHocPayment']; ?></td>
					<td><?php echo $row['Comments']; ?></td>



					<td class="no-wrap">
			
			   <?php if ($isAdmin): ?>
			 <button type="button" data-link="fees.php?id=<?php echo $row['adhocPaymentID']; ?>" class="btn btn-outline-warning fees" name="<?php echo $row['adhocPaymentID']; ?>" title="Process" data-id="<?php echo $row['adhocPaymentID']; ?>"><i class="bi bi-check-all"></i></button>
			 
			  <button type="button" data-link="fees.php?id=<?php echo $row['adhocPaymentID']; ?>" class="btn btn-outline-danger del" name="<?php echo $row['adhocPaymentID']; ?>" title="Delete" data-id="<?php echo $row['adhocPaymentID']; ?>"><i class="bi bi-trash"></i></button>
<?php endif; ?>
			

			
					</td>
                  </tr>
<?php   }
} else {

} ?>                 
                </tbody>
              </table>
              </div>
              <!-- End Table with stripped rows -->
              <form action="adhocprocess2.php" method="post">
              
<div class="text-center">
    <button type="submit" class="btn btn-warning proall" name="processall" style="width: 100%;" name="process">Process All</button>
</div>

</form>
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
                url:'adhocprocess.php',
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
					}else if(dataResult.statusCode==220){
						var r_error = (dataResult.retrieveerror);
            alert("Balance less than requested amount, please terminate member or request lesser amount");
					}
            
                }
            });

        });
    });

</script>


<script>
$(function(){
    $(".proall").click(function(){
        var postid = $(this).attr("data-id");
        $.ajax({
            type: 'POST',
            url: 'adhocprocess2.php',
            data: {'id': postid},
            success: function(dataResult){
                try {
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        alert(dataResult.datas);
                        location.reload();
                    }
                    else if(dataResult.statusCode == 201){
                        alert(dataResult.datas);
                    }
                    else if(dataResult.statusCode == 203){
                        alert("Please Update Recent Transaction for = " + dataResult.datas);
                    }
                    else if(dataResult.statusCode == 210){
                        alert(dataResult.retrieveerror);
                    }
                    else if(dataResult.statusCode == 220){
                        alert(dataResult.datas);
                    }
                } catch (e) {
                    console.error("Parsing error:", e, dataResult);
                    alert("An error occurred while processing your request. Please try again later.1");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error, xhr.responseText);
                alert("An error occurred while processing your request. Please try again later.2");
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
                url:'adhocdelete.php',
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
    header('Location: https://fairlifeadmin.com/fix/index.php');
}

?>
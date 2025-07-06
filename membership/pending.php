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

  <title>Pending Members</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 
<script src='jquery-3.2.1.min.js' type='text/javascript'></script>
        <script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>

        <link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

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
      <h1>Pending Members</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Pending</a></li>
          <li class="breadcrumb-item active">Pending Members</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->

          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">List of Pending Members</h5>
              <!-- Table with stripped rows -->
              
              <div class="table-responsive">
              <table class="table table-striped datatable" id="jj">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">MemberNo</th>
                    <th scope="col">FUllName</th>
                    <th scope="col">Gender</th>
                    <th scope="col">DOB</th>
                    <th scope="col">Acc Opened</th>
                     <th scope="col">Appr. Benefit</th>
                     
                    
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
			<?php 
$stmt = $conn->prepare("SELECT * FROM `tblmembers` where `Terminated` = '3' ");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if ($row['Terminated'] == 1){
            $term = "Terminated";
        } else {
            $term = "Active";
        }
        $uid = $row['MemberNo'];
?>
<tr>
    <th scope="row"><?php echo $row['MemberID']; ?></th>
    <td><?php echo $row['MemberNo']; ?></td>
    <td><?php echo $row['MemberSurname']." ".$row['MemberFirstname']; ?></td>
    <td><?php echo $row['Gender']; ?></td>
    <td><?php echo $row['DateOfBirth']; ?></td>
    <td><?php echo $row['DateAccountOpened']; ?></td>
    <td><?php echo 'E  ' . number_format($row['ApprovedBenefit'], 2); ?></td>
    <td>
        <button type="button" data-link="edit.php?id=<?php echo $row['MemberID']; ?>" class="btn btn-outline-success approve"  title="Approve" data-id="<?php echo $row['MemberID']; ?>"><i class="bi bi-check-all"></i></button>
        <!--<button type="button" data-link="dedit.php?id=<?php echo $row['MemberID']; ?>" class="btn btn-outline-primary eye"  title="View" data-id="<?php echo $row['MemberID']; ?>"><i class="bi bi-eye"></i></button>-->
        <button type="button" data-id="<?php echo $row['MemberID']; ?>" class="btn btn-outline-secondary delete"  title="Delete"><i class="bi bi-trash"></i></button>
    </td>
</tr>
<?php   
    }
} else {
?>
<tr>
    <td colspan="8">No records found</td>
</tr>
<?php 
} 
?>



                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>

            </div>
          </div>


<!-- end of new beneficiary form -->

  
  </main><!-- End #main -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var deleteButtons = document.querySelectorAll(".delete");
        deleteButtons.forEach(function(button) {
            button.addEventListener("click", function() {
                var memberId = this.getAttribute("data-id");
                if (confirm("Are you sure you want to delete this record?")) {
                    // AJAX call to delete the record
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Remove the row from the DOM
                            var row = button.closest("tr");
                            row.parentNode.removeChild(row);
                            // Optional: You can add a success message or perform any other actions here
                        }
                    };
                    xhr.open("GET", "delete_record.php?id=" + memberId, true);
                    xhr.send();
                }
            });
        });
    });
</script>
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
        $(".approve").click(function(){
            var postid = $(this).attr("data-id");
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'approve.php',
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
        $(".eye").click(function(){
            var postid = $(this).attr("data-id");
			var ff = "jj";
              $.ajax({
                type:'POST',
                url:'view.php',
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
    header('Location: https://fair.liquag.com/index.php');
}

?>
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

  <title>All Members</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
<script src=" https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- Favicons -->
  <link href="https://fair.liquag.com/logo.png" rel="icon">
  <link href="https://fair.liquag.com/logo.png" rel="apple-touch-icon">

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

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

 <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>
 <script type="text/javascript" src=" https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>

 <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
  <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
 <style>
tr td {
    white-space: nowrap;
}
 </style>
</head>

<body>

  <!-- ======= Header ======= -->
<?php include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>All Members</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">All Members</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">All Beneficiaries</h5>
              <!-- Table with stripped rows -->


  <div class="table responsive">
              <table class="table table-striped datatable nowrap" id="jj" style="width: 100%;" >
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">MemberNo</th>
                    <th scope="col">FUllName</th>
                    <th scope="col">Gender</th>
                    <th scope="col">DOB</th>
                    <th scope="col">Acc Opened</th>
                     <th scope="col">Deceased</th>
                     <th scope="col">Appr. Benefit</th>
                      <th scope="col">Balance</th>
                      <th scope="col">Terminated</th>
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("SELECT * FROM `mbr_list` ");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {
if ($row['Terminated'] == 1){
	$term = "Terminated";
}else {
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
                     <td><?php echo $row['Dname']; ?></td>
                      <td><?php echo $row['ApprovedBenefit']; ?></td>
                      <td><?php echo $row['NewBalance']; ?></td>
                      <td><?php echo $row['Terminated']; ?></td>
					<td>
			<button type="button" data-link="edit.php?id=<?php echo $row['MemberNo']; ?>" class="btn btn-outline-primary edit"  title="Edit" data-id="<?php echo $row['MemberNo']; ?>"><i class="bi bi-eye-fill"></i></button>
			<button type="button" data-link="dedit.php?id=<?php echo $row['DeceasedID']; ?>" class="btn btn-outline-secondary dedit"  title="Deceased" data-id="<?php echo $row['DeceasedID']; ?>"><i class="bi bi-person-dash"></i></button>
			<button type="button" data-link="kedit.php?id=<?php echo $row['NextOfKinID']; ?>" class="btn btn-outline-warning kedit"  title="N.of.Kin" data-id="<?php echo $row['NextOfKinID']; ?>"><i class="bi bi-person-fill"></i></button>
			<button type="button" data-link="gedit.php?id=<?php echo $row['GuardianID']; ?>" class="btn btn-outline-info gedit"  title="Guardian" data-id="<?php echo $row['GuardianID']; ?>"><i class="bi bi-person-check-fill"></i></button>
			<button type="button" data-link="file.php?id=<?php echo $row['MemberNo']; ?>" class="btn btn-outline-success gedit"  title="Files" data-id="<?php echo $row['MemberNo']; ?>"><i class="bi bi-archive-fill"></i></button>
              
					</td>
                  </tr>
<?php   }
} else {
  echo "0 results";
} ?>                 
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
            </div>
          </div>

        </div>
      </div>
    </section>

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

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../assets/vendor/php-email-form/validate.js"></script>
 


<script src="../assets/js/main.js"></script>



    <script src=""></script>
    <script src=""></script>
    
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

  <!-- Template Main JS File -->

  
<script>
//$(document).ready(function(){
/*	
	$('.edit').click(function(){
   window.location.href = $(this).data('link');
});
});
*/
$(document).on("click",".edit",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});

$(document).on("click",".dedit",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});

$(document).on("click",".gedit",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});

$(document).on("click",".kedit",function(e){
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
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
       
        
    } );
    
  
} );
</script>



</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/');
}

?>


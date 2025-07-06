
<?php 
session_start();
if(isset($_SESSION['zid']))
{
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';


include 'db_connect.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Files Management</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>
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


  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>

 
 <script type="text/javascript" src=" https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>

 <link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
  <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

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
            #form{

                padding: 20px;
            }
            .container{
                margin-top: 2%;
            }
            .topnav{
                padding: 10px;
                background-color: #303030;
                color: white;
            }
            #nav{
            background-color: gray;
            height: 50px;
            color: white;
        }
        </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include '../header.php'; ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Files Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Files</a></li>
          <li class="breadcrumb-item active">Management</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->


               <form class="row g-3 needs-validation" id="upload_form" method="post"  enctype="multipart/form-data" novalidate>
               
               <div class="col-md-12 col-lg-6">
               <div class="form-floating">
                <div class="form-group">
                    <input type="file" class="inp" name="uploadingfile" id="uploadingfile">
                </div>
                </div></div>

                <div class="col-md-12 col-lg-6">
				<div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"    placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT * FROM `tblmembers` where `Terminated` = '0' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {
					
					    	
							

						?>
					<option value="<?php echo $row12['MemberNo']; ?>"><?php echo $row12['MemberNo']." ".$row12['MemberSurname']." ".$row12['MemberFirstname'] ; ?></option>
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


                <div class="form-group">
                    <input class="btn btn-warning add" class="inp" type="button" value="Upload File" name="btnSubmit"
                           onclick="uploadFileHandler()" style="width: 100%;" >
                </div>
                <div class="form-group">
                    <div class="progress" id="progressDiv">
                        <progress id="progressBar" value="0" max="100" style="width:100%; height: 1.2rem;"></progress>
                    </div>
                </div>
                <div class="form-group">
                    <h3 id="status"></h3>
                    <p id="uploaded_progress"></p>
                </div>
            </form>


            <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">All Client Files</h5>
              <!-- Table with stripped rows -->


              
               <div class="table responsive">
              <table class="table table-striped datatable nowrap" id="jj" style="width: 100%;" >
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Owner</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Link</th>
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
$stmt = $conn->prepare("SELECT * FROM `files` ");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  // output data of each row
while($row = $result->fetch_assoc()) {

?>
                  <tr>
                    <th scope="row"><?php echo $row['id']; ?></th>
                    <td><?php echo $row['userid']; ?></td>
                    <td><?php echo $row['name']?></td>
                    <td>
                    <button type="button" data-link="<?php echo $row['link']; ?>" class="btn btn-outline-primary goto"  title="Check" target="_blank" data-id="<?php echo $row['id']; ?>"><i class="bi bi-eye-fill"></i>Go To FIle</button>
                
                </td>


					<td>

			
            <button type="button" data-link="dedit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary dedit"  title="Deceased" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>

              
					</td>
                  </tr>
<?php   }
} else {
 // echo "0 results";
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
        </main>
        

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/vendor/php-email-form/validate.js"></script>

<script src="../assets/js/main.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>


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


 <script>
        $(document).ready(function(){
            $('#single').select2({
    width: '100%',
    allowClear: false,
    height: '100%',
});
});
</script>
       
<script>
    function _(abc) {
    return document.getElementById(abc);
}
var userids = document.getElementById('single');
myElem = $('#single').val();
function uploadFileHandler() {
    var file = _("uploadingfile").files[0];
    var formdata = new FormData();
    formdata.append("uploadingfile", file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "up.php?owner=uuuu");
    ajax.send(formdata);
}

function progressHandler(event) {
    var loaded = new Number((event.loaded / 510048576));//Make loaded a "number" and divide bytes to get Megabytes
    var total = new Number((event.total / 510048576));//Make total file size a "number" and divide bytes to get Megabytes
    _("uploaded_progress").innerHTML = "Uploaded " + loaded.toPrecision(5) + " Megabytes of " + total.toPrecision(5);//String output
    var percent = (event.loaded / event.total) * 100;//Get percentage of upload progress
    _("progressBar").value = Math.round(percent);//Round value to solid
    _("status").innerHTML = Math.round(percent) + "% uploaded";//String output
}

function completeHandler(event) {
    _("status").innerHTML = event.target.responseText;//Build and show response text
    _("progressBar").value = 0;//Set progress bar to 0
    document.getElementById('progressDiv').style.display = 'none';//Hide progress bar
}

function errorHandler(event) {
    _("status").innerHTML = "Upload Failed";//Switch status to upload failed
}

function abortHandler(event) {
    _("status").innerHTML = "Upload Aborted";//Switch status to aborted
}
</script>

<script>
$(document).on("click",".goto",function(e){
 // your code goes here
  window.location.href = $(this).data('link');
});
</script>
    </body>
</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
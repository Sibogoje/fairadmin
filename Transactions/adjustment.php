<!DOCTYPE html>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
if(isset($_SESSION['zid'])) {
  $gg = $_SESSION['user'];
  require_once '../scripts/connection.php';
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Bulk Adjustment</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/datatables.min.js"></script>
  <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
  <link href='../select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
  <script src='../select2/dist/js/select2.min.js' type='text/javascript'></script>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
</head>

<body>

  <!-- ======= Header ======= -->
  <?php
require_once __DIR__ . '/../scripts/bootstrap.php';
include '../header.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Bulk Adjustment</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Bulk Adjustment</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <div class="card col-lg-12" style="">
      <div class="card-body">
        <h5 class="card-title">Enter Adjustment Amount</h5>
        <form class="row g-3 needs-validation" method="post" action="" id="adjustmentform" enctype="multipart/form-data" novalidate>
          <div class="col-md-12">
            <div class="form-floating">
              <input type="number" step="0.01" class="form-control" id="adjustmentAmount" name="adjustmentAmount" required placeholder="Adjustment Amount">
              <label for="adjustmentAmount">Adjustment Amount</label>
              <div class="valid-feedback">Looks good!</div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-floating">
              <input type="date" class="form-control" id="adjustmentDate" name="adjustmentDate" required placeholder="Adjustment Date">
              <label for="adjustmentDate">Adjustment Date</label>
              <div class="valid-feedback">Looks good!</div>
            </div>
          </div>
          <div class="text-center">
            <button type="button" id="adjbtn" class="btn btn-danger adjustment" style="width: 100%;" name="submit">Process Adjustment</button>
          </div>
        </form>
        <div class="card col-lg-12">
          <div class="card-body">
            <div class="logs" id="logs"></div>
          </div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->

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
console.log('Adjustment script loaded');
$(document).ready(function() {
  console.log('jQuery ready');
  $(".adjustment").on('click', function(){
    console.log('Process Adjustment button clicked');
    $("#adjbtn").attr("disabled", true);
  var data = $("#adjustmentform").serialize();
    $.ajax({
      data: data,
      type: "post",
      url: "caladjustment.php",
      success: function(dataResult){
        var parsed = null;
        if (typeof dataResult === 'string') {
          try {
            parsed = JSON.parse(dataResult);
          } catch (e) {
            $("#adjbtn").attr("disabled", false);
            $("#logs").html('<div class="alert alert-danger">Server returned invalid response.</div>');
            return;
          }
        } else {
          parsed = dataResult;
        }
        $("#adjbtn").attr("disabled", false);
        if(parsed.statusCode==200){
          var success1 = (parsed.dones);
          $("#logs").html('<div class="alert alert-success">'+success1+'</div>');
          // Clear form fields
          $("#adjustmentform")[0].reset();
        }
        else {
          var error = (parsed.error);
          $("#logs").html('<div class="alert alert-danger">'+error+'</div>');
        }
      },
      error: function(xhr, status, error) {
        $("#adjbtn").attr("disabled", false);
        $("#logs").html('<div class="alert alert-danger">AJAX error: '+error+'</div>');
      }
    });
  });
});
</script>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
} else {
  header('location: ../login.php');
}
?>
</body>
</html>
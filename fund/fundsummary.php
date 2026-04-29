<!DOCTYPE html>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
session_start();
if (isset($_SESSION['zid'])) {
$gg = $_SESSION['user'];
require_once '../scripts/connection.php';
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Funds Summary</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="../jquery-3.2.1.min.js" type="text/javascript"></script>
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
  <script src="../select2/dist/js/select2.min.js" type="text/javascript"></script>

  <link href="<?= APP_URL ?>logo.png" rel="icon">
  <link href="<?= APP_URL ?>logo.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
html {
    -webkit-transition: background-color 1s;
    transition: background-color 1s;
}
html, body {
    min-height: 100%;
}
.loading {
    background: rgba(0,0,0,0.8) url('progress.gif') no-repeat 50% 10%;
    margin: auto;
    position: fixed;
    -webkit-transition: background-color 0;
    transition: background-color 0.7s;
}
body {
    -webkit-transition: opacity 1s ease-in;
    transition: opacity 1s ease-in;
}
html.loading body {
    opacity: 0.5;
    -webkit-transition: opacity 0.5s;
    transition: opacity 0.5s;
}
  </style>
</head>

<body>
  <?php include '../header.php'; ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Funds Summary</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dash.php">Dashboard</a></li>
          <li class="breadcrumb-item">Funds</li>
          <li class="breadcrumb-item active">Summary</li>
        </ol>
      </nav>
    </div>

    <div class="card col-lg-12">
      <div class="card-body">
        <h5 class="card-title">Choose Period And Fund</h5>

        <form class="row g-3 needs-validation" id="fund_summary_form" method="post" action="" novalidate>
          <div class="col-md-4">
            <div class="form-floating">
              <input type="date" class="form-control" id="from" name="from" required>
              <label for="from">From</label>
              <div class="valid-feedback">Looks good!</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="date" class="form-control" id="to" name="to" required>
              <label for="to">To</label>
              <div class="valid-feedback">Looks good!</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <select class="form-control" id="fund_id" name="fund_id" required>
                <option value=""></option>
                <option value="all">All Funds</option>
                <?php
$stmtFunds = $conn->prepare("SELECT RetirementFundID, FundName FROM tblretirementfunds ORDER BY FundName ASC");
$stmtFunds->execute();
$resultFunds = $stmtFunds->get_result();
while ($rowFund = $resultFunds->fetch_assoc()) {
?>
                <option value="<?php echo htmlspecialchars($rowFund['RetirementFundID']); ?>"><?php echo htmlspecialchars($rowFund['RetirementFundID'] . ' - ' . $rowFund['FundName']); ?></option>
                <?php } ?>
              </select>
              <label for="fund_id">Fund</label>
              <div class="valid-feedback">Looks good!</div>
            </div>
          </div>

          <div class="col-md-6">
            <button type="submit" class="btn btn-warning" style="width: 100%;"><b>Show Funds Summary</b></button>
          </div>

          <div class="col-md-6">
            <button type="button" class="btn btn-success" id="export_excel" style="width: 100%;"><b>Export To Excel</b></button>
          </div>
        </form>

        <section class="section mt-4">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body" id="fund-summary-results"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>
  <script>
$(document).ready(function () {
  $('html').removeClass('loading');

  function validateFundSummaryForm() {
    var from = $('#from').val();
    var to = $('#to').val();
    var fundId = $('#fund_id').val();

    if (from === '' || to === '') {
      $('#fund-summary-results').html('<div class="alert alert-warning mt-3">Please select both dates.</div>');
      return false;
    }

    if (fundId === '') {
      $('#fund-summary-results').html('<div class="alert alert-warning mt-3">Please select a fund.</div>');
      return false;
    }

    return true;
  }

  $('#fund_id').select2({
    width: '100%',
    allowClear: false,
    height: '100%'
  });

  $('#fund_summary_form').on('submit', function (event) {
    event.preventDefault();

    if (!validateFundSummaryForm()) {
      return;
    }

    $.ajax({
      url: 'fundsummaryhistory.php',
      data: $(this).serialize(),
      type: 'POST',
      success: function (response) {
        $('#fund-summary-results').html($.trim(response));
      }
    });
  });

  $('#export_excel').on('click', function () {
    if (!validateFundSummaryForm()) {
      return;
    }

    var exportForm = $('<form>', {
      method: 'POST',
      action: 'fundsummaryexcel.php',
      target: '_blank'
    });

    $.each($('#fund_summary_form').serializeArray(), function (_, field) {
      exportForm.append($('<input>', {
        type: 'hidden',
        name: field.name,
        value: field.value
      }));
    });

    $('body').append(exportForm);
    exportForm.trigger('submit');
    exportForm.remove();
  });
});
  </script>
</body>

</html>
<?php
} else {
    header('Location: ' . APP_URL . '');
}
?>
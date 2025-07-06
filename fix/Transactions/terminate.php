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
  $Comments = $_POST['Comments'];
  $Credit = '';
  $stmt = $conn->prepare("SELECT balance, TerminationFeePercent FROM `member_fees`WHERE MemberID = '$MemberID' ");
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    // output data of each row
  while($row = $result->fetch_assoc()) {
$balance  = $row['balance'];
$percent  = $row['TerminationFeePercent'];

$Amount = ($percent/100) * $balance;
$newbalance = $balance - $Amount;
$TransactionTypeID = '12';
$insertnew = $conn->prepare("insert into `tblmemberaccounts` (

    `TransactionDate`,
    `TransactionTypeID`,
    `memberID`,
    `Details`,
    `Credit`,
    `StartingBalance`,
    `Amount`,
    `NewBalance`,
    `Comments`
  
  )
  
  VALUES
    (
  
      ?,
      ?,
      ?,
      ?,
      ?,
      ?,
      ?,
      ?,
      ?
    );");
  $insertnew->bind_param("sssssssss", 
  $PaymentDate, 
  $TransactionTypeID,
  $MemberID,
  $Details,
  $Credit,
  $balance,
  $Amount,
  $newbalance,
  $Comments
  
  );

  $insertnew->execute();
  $TransactionTypeID = '11';
  $finalbalance = 0.00;
  $Detailsfinal = 'Final Transaction';
  
  $insertnew->bind_param("sssssssss", 
  $PaymentDate, 
  $TransactionTypeID,
  $MemberID,
  $Detailsfinal,
  $Credit,
  $newbalance,
 $newbalance,
  $finalbalance,
  $Comments
  
  );


 if($insertnew->execute()){
    $update = $conn->prepare("UPDATE tblmembers SET `Terminated` = '1', `TerminationDate` = '$PaymentDate' WHERE MemberID=? ");
$update->bind_param("s", $MemberID);
$update->execute();
echo "<script> alert('The Member Was Terminted Succesfully');
window.location.href='terminate.php';
</script>";
 } else{
    echo "<script> alert('The was an Error Terminating the Member');
    window.location.href='terminate.php';
    </script>";
 }


    }
}else{
    echo "<script> alert('Member Not Found');
    window.location.href='terminate.php';
    </script>"; 
}




  
  



//echo "New records created successfully";

$stmt->close();
$conn->close();
}else{

}


?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Member Termination</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Select2 CSS --> 

        <script src='select2/dist/js/select2.min.js' type='text/javascript'></script>

        <link href='select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

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
      <h1>Member Termination</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Termination</a></li>
          <li class="breadcrumb-item active">Member Termination</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- New beneficiary form-->

<div class="card col-lg-12" style="">
            <div class="card-body">
              <h5 class="card-title">New Termination</h5>
			  
			  <form class="row g-3 needs-validation" id="user_form" method="post" action="" enctype="multipart/form-data" novalidate>

  	             <div class="col-md-12">
				
                  <div class="form-floating">
				  
					 <select type="text" class="form-control" id="single"   placeholder="MemberID" name="MemberID"  required>
					<option value="" selected></option>
						<?php 
						$stmt12 = $conn->prepare("SELECT MemberNo, MemberID, MemberSurname, MemberFirstname, balance, TerminationFeePercent FROM `member_fees` ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						while($row12 = $result12->fetch_assoc()) {?>

                      

					<option value="<?php echo $row12['MemberID']; ?>"><?php echo $row12['MemberNo']." -".$row12['MemberSurname']. ", ".$row12['MemberFirstname']." Balance: ".$row12['balance'] ; ?></option>
                   
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
                    <label for="floatingName">Termination Date Date:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  
                  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="newss" placeholder="Amount After" name="newss" required >
                    <label for="floatingName">Amount After:</label>
				  <div class="valid-feedback">
                    Looks good!
                  </div>
                  </div>
				  </div>
				  

				 
				  
				  <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="ff" placeholder="Details" value="Termination Fee" name="Details" required>
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
                  <button type="submit"  class="btn btn-warning" style="width: 100%;" name="submit"><b>Terminate Selected Member</b></button>
                  
                </div>

              </form><!-- End floating Labels Form -->



            </div>
          </div>


          <div class="card col-lg-12" >
            <div class="card-body">
              <h5 class="card-title">Recently Terminated</h5>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
              <table class="table datatable" id="jj" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    
                    <th scope="col">MemberNo</th>
                    <th scope="col">MemberID</th>
                    <th scope="col">Prev balance</th>
				    <th scope="col">Balance After Term.</th>
                    <th scope="col">Date</th>

                  </tr>
                </thead>
                <tbody>
<?php 
$stmt = $conn->prepare("SELECT a.*, m.MemberNo, m.MemberFirstname, m.MemberSurname 
                        FROM `tblmemberaccounts` AS a
                        INNER JOIN `tblmembers` AS m ON a.memberID = m.memberID
                        WHERE a.TransactionTypeID = '11'
                        ORDER BY a.memberID ASC
                        LIMIT 100");

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $memberNo = $row['MemberNo'];
        $mno = $row['memberID'];
        $amount = $row['Amount'];
        $newBalance = $row['NewBalance'];
        $transactionDate = $row['TransactionDate'];
?>

    <tr>
        <th scope="row"><?php echo $memberNo; ?></th>
        <th scope="row"><?php echo $mno; ?></th>
        <td scope="row"><?php echo $amount; ?></td>
        <td><?php echo $newBalance; ?></td>
        <td><?php echo $transactionDate; ?></td>
    </tr>

<?php
    }
} else {
    // No results
}
?>
                
                </tbody>
              </table>
              </div>
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
  function formatNumber(input) {
  // Convert the input value to a number, allowing for different decimal separators
  const value = parseFloat(input.value.replace(',', '.'));
  // Check if the value is a valid number
  if (!isNaN(value)) {
    // Format the number to two decimal places
    input.value = value.toFixed(2);
  }
}
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
   	 $("#single").change(function(){
        $(this).find("option:selected").each(function(){
        //alert("sELCTED");
            var data = $("#user_form").serialize();
           // $('#newss').val(ff);

            $.ajax({
			data: data,
			type: "post",
			url: "transact.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){

                        var ttfundmembers = (dataResult.ttfundmemberszz);
						$("#newss").val("Null");
						$("#newss").val(ttfundmembers.toFixed(2));
                      //  alert(ttfundmembers);
                     // formatNumber(ttfundmembers);

                    }else{

                        alert("Data Could not be retrieved");

                    }
}
});
}); 
    }).change();
</script>



		
</body>

</html>
<?php
}else{
    header('Location: https://fair.liquag.com/index.php');
}

?>
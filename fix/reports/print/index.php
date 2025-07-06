<?php
require_once '../../scripts/connection.php';
if (isset($_POST['viewport'])) {
	$fundid = $_POST['fundidzz'];
	$newDate = $_POST['dateszz'];
	//$originalDate = "2010-03-21";
$d1 = date("d-m-Y", strtotime($newDate));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>fundreport</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Responsive-UI-Card-02.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <section style="margin-top: 28px;">
        <h2 class="text-center" style="margin-bottom: 0px;">Fund Title</h2>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">1. Total Assets Managed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">This outlines the Assets that are administered by the Fairlife Beneficiary Fund during this reporting period:</th>
                                        </tr>
                                        <tr>
                                            <th>From</th>
											<th>To</th>
                                            <th>Amount</th>
                                        </tr>
										<?php
										$stmt = $conn->prepare("SELECT * FROM `tblfundreport` WHERE `fundid`='$fundid' AND `when`='$d1'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
                                            <td><?php echo $row['balance'];?></td>
                                        </tr>
										<?php   }
										} else {
										  ?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;color: rgba(255,252,252,0.87);">
                            <div class="table-responsive" style="color: rgba(255,255,255,0.87);">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">2. Beneficiary Movement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="5">This table outlines the movements of the beneficiaries during the period under review:</th>
                                        </tr>
                                        <tr>
										    <th>From</th>
                                            <th>To</th>
                                            <th>Total</th>
                                            <th>New Members</th>
                                            <th>Terminated</th>
                                            <th>Active</th>
                                        </tr>
                                     <?php
											$stmt = $conn->prepare("SELECT * FROM `tblfundreport` WHERE `fundid`='$fundid' AND `when`='$d1'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
										$tt = $row['tmembers'] + $row['active'];
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
                                            <td><?php echo $tt; ?></td>
											<td></td>
											<td><?php echo $row['active'];?></td>
											<td><?php echo $row['tmembers'];?></td>
                                        </tr>
										<?php   }
										} else {
										  ?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">3. Regular Payments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">This illustrates the regular payments that were made during the period under review. These are paid monthly payment made to the beneficiary.</th>
                                        </tr>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
											 <th>Amount</th>
                                        </tr>
										<?php
										$stmt = $conn->prepare("SELECT * FROM `tblfundreport1` WHERE `fundid`='$fundid' AND `when`='$d1' AND `type`='Regular'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
								
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
											<td><?php echo $row['sum'];?></td>
                                        </tr>
										<?php   }
										} else {
										 ?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">4. Adhoc Payments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">This illustrates the ad hoc payments that were made during the period under review.</th>
                                        </tr>
<tr>
                                            <th>From</th>
                                            <th>To</th>
											 <th>Amount</th>
                                        </tr>
										<?php
										$stmt = $conn->prepare("SELECT * FROM `tblfundreport1` WHERE `fundid`='$fundid' AND `when`='$d1' AND `type`='Adhoc'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
								
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
											<td><?php echo $row['sum'];?></td>
                                        </tr>
										<?php   }
										} else {
										 ?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">5. Investment Returns Allocation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">This illustrates the returns that were paid to the Fund’s beneficiaries from the investment returns that were made during the period under review. This is prorated based on each individual Beneficiary’s balance.</th>
                                        </tr>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
											 <th>Amount</th>
                                        </tr>
										<?php
										$stmt = $conn->prepare("SELECT * FROM `tblfundreport1` WHERE `fundid`='$fundid' AND `when`='$d1' AND `type`='Interest'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
								
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
											<td><?php echo $row['sum'];?></td>
                                        </tr>
										<?php   }
										} else {
										  ?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">6. Fees Charged</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2" style="--bs-body-bg: #b32020;">This illustrates the fees charged on the Beneficiaries as outlined in the fee structure and paid out of their accounts during the period under review.</th>
                                        </tr>
                                      <tr>
                                            <th>From</th>
                                            <th>To</th>
											 <th>Amount</th>
                                        </tr>
										<?php
										$stmt = $conn->prepare("SELECT * FROM `tblfundreport1` WHERE `fundid`='$fundid' AND `when`='$d1' AND `type`='Tfees'");

										$stmt->execute();
										$result = $stmt->get_result();
										if ($result->num_rows > 0) {
										  // output data of each row
										while($row = $result->fetch_assoc()) {

										//$uid = $row['MemberNo'];
								
?>
                                        <tr>
                                            <td><?php echo $row['from'];?></td>
											<td><?php echo $row['to'];?></td>
											<td><?php echo $row['sum'];?></td>
                                        </tr>
										<?php   }
										} else {
											?>
											<tr>No data available for fundID<?php echo $fundid; ?></tr>
											<?php
										 
										} ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4" style="margin: 47px;margin-bottom: 26px;margin-left: 70px;margin-right: 70px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0" style="border-color: rgb(0,0,0);color: var(--bs-body-color);font-size: 16px;"><i class="far fa-comment-alt"></i>&nbsp;Comments</h6>
            </div>
            <div class="card-body"></div>
        </div>
        <div class="container">
            <div class="row" style="margin: 6px;">
                <div class="col-md-4" style="width: 100%;margin-top: 22px;">
                    <div class="blog-card blog-card-blog" style="padding: -14px;padding-top: 20px;">
                        <div class="blog-card-image"><a href="#"></a>
                            <div class="ripple-cont"></div>
                        </div>
                        <div class="blog-table" style="padding-top: 12px;background: #ffffff;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">9. Annexure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Capital</th>
                                            <th>Interest</th>
                                            <th>Payments</th>
                                            <th>Balance</th>
                                        </tr>
                                        <tr>
                                            <td>Cell 3</td>
                                            <td>Cell 4</td>
                                            <td>Cell 4</td>
                                            <td>Cell 4</td>
                                            <td>Cell 4</td>
                                            <td>Cell 4</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/card-header-with-tooltip-and-icon.js"></script>
	
	<script>
	window.onload = function () {
   // window.print();
}</script>
	
</body>

</html>

<?php 
}
?>
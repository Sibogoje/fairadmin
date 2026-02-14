<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];


if(count($_POST)>0){
    
        
$stmt = $conn->prepare("SELECT tblmembers.*, mbr_deceased.FundNames 
                        FROM tblmembers 
                        LEFT JOIN mbr_deceased ON tblmembers.MemberID = mbr_deceased.MemberID 
                        WHERE tblmembers.MemberID = '$ii'");
						$stmt->execute();
						$result = $stmt->get_result();
						if ($result->num_rows > 0) {
						    while($row = $result->fetch_assoc()) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  <tr>
                    <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
                   
                    </tr>
                   	<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">MEMBER DETAILS</th>
                   
                    </tr>
                    <tr>
                    <th scope="col" style="vertical-align: top;">Full Name</th>
                    <td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['MemberFirstname']." ".$row['MemberSurname']; ?></td>
                    <th scope="col" style="vertical-align: top;">MemberNo</th>
					<td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['MemberNo']; ?></td>
					<th scope="col" style="vertical-align: top;">National ID</th>
					<td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['MemberIDnumber']; ?></td>
					</tr>
					
					
					<tr>
                    <th scope="col" style="vertical-align: top;">Date of Birth</th>
                    <td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['DateOfBirth']; ?></td>
                    <th scope="col" style="vertical-align: top;">Account Opened</th>
					<td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['DateAccountOpened']; ?></td>
					 <th scope="col" style="vertical-align: top;">Fund Name</th>
					<td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['FundNames']; ?></td>
					</tr>
					
						<tr>
                    <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                    <td scope="col"  style="font-weight: bold;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo "E ". number_format($row['ApprovedBenefit'], 2); ?></td>
                    <th scope="col" style="vertical-align: top;">Terminated</th>
					<td scope="col"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo $row['Terminated']; ?></td>
					 <th scope="col" style="vertical-align: top;">Balance</th>
					 <?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
$stmt12 = $conn->prepare("SELECT `NewBalance` from `balances` where  `memberID` = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
?>
			<td scope="col" style="font-weight: bold;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo "E ". number_format($row12['NewBalance'], 2); ?></td>
<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}}else{
								?>
								<td scope="col" style="font-weight: bold;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo "No data";?></td>
								<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}
?>		
				</tr>
				<tr style="text-align: center; background: black; color: white;">
                    <th scope="col" colspan="6">Account Summary   [<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo date('d-M-Y')?>]</th>
                   
                    </tr>	
					</thead>
					</table>
					</div>
					
<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}}    
    
    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '1' AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Initial Transfer In</th>
                    <td scope="col" style="text-align: right;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo "E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}
	?>
</table>
</div>
<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
} else {
echo "0 results";	}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID = '2' AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Transfer In Fee</th>
                    <td scope="col" style="text-align: right;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo "E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}
	?>
</table>
</div>
<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
} else {
echo "0 results";	}    
 


    
$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where  TransactionTypeID IN ('9' ) AND memberID = '$ii' ");
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						    while($row12 = $result12->fetch_assoc()) {
						  // output data of each row
						 // $sum = $sum + $row12['TT3'] ;
						 ?>
						 <div class="table-responsive">
		<table class="table datatable"  id="free">
			<thead>
                  
                   
                    <tr>
                    <th scope="col" style="vertical-align: top;">Additional Capital</th>
                    <td scope="col" style="text-align: right;"><?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
echo " E ". number_format($row12['TT3'], 2); ?></td>
                    </tr>
</thead>
 <?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
}
	?>
</table>
</div>
<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
} else {
echo "0 results";	}    
 
 

?>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php
if (!defined('APP_URL')) {
    $appConfigCandidates = array(
        __DIR__ . '/scripts/app_config.php',
        dirname(__DIR__) . '/scripts/app_config.php',
        dirname(__DIR__, 2) . '/scripts/app_config.php',
        dirname(__DIR__, 3) . '/scripts/app_config.php'
    );

    foreach ($appConfigCandidates as $appConfigPath) {
        if (file_exists($appConfigPath)) {
            require_once $appConfigPath;
            break;
        }
    }
}
} else {
  header('location: ./');
}







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
$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];


foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );

//echo $mntharray1;
$name = array($mntharray2);

if (in_array("all", $mntharray)){
 // echo $mntharray2;
  //console("HHHH");
   $choose = "`initialfees` WHERE DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";  
}else{
  
 $choose = "`initialfees` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";   
}





if(count($_POST)>0){
$stmt12 = $conn->prepare("SELECT MemberNo, Name, Surname, ApprovedBenefit, Transactiodate, Amount FROM ".$choose);
						$stmt12->execute();
						$result12 = $stmt12->get_result();
						if ($result12->num_rows > 0) {
						  // output data of each row
						 ?>
						 <div class="table-responsive">
		<table class="table datatable responsive"  id="free">
			<thead>
                  <tr>
                    <th scope="col">Member No</th>
                    <th scope="col">Name </th>
                    <th scope="col">Surname</th>
                    <th scope="col">ApprovedBenefit</th>
                    <th scope="col">Date</th>
					<th scope="col">Amount</th>

                  </tr>
                </thead>
                <tbody>
				
          
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
while($row12 = $result12->fetch_assoc()) {
						
?>							
<tr>
                    <th scope="row"><?php
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
echo $row12['MemberNo']; ?></th>
                    <th scope="row"><?php
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
echo $row12['Name'];?></th>
                    <td><?php
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
echo $row12['Surname']; ?></td>
                    <td><?php
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
echo number_format($row12['ApprovedBenefit'], 2); ?></td>
                    <td><?php
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
echo $row12['Transactiodate']; ?></td>
                    <td><?php
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
echo  number_format($row12['Amount'], 2);  ?></td>

					<td>
			  
					</td>
                  </tr>
				   
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
						</tbody>
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
						  echo "No Data Found";



						} 
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
?>




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

$single = $_POST['single'];
$d1 = $_POST['date1'];
$d2 = $_POST['date2'];


foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );

//echo $mntharray1;
$name = array($mntharray2);


$sing = 'all';

if ($sing == 'all') {
    
    $stmt = $conn->prepare("
    SELECT 
        m.`MemberNo`,
        m.`membersurname`,
        m.`memberfirstname`,
        m.`DateOfBirth`,
        m.`gender`,
        m.`DateAccountOpened`,
        a.newbalance
    FROM 
        `u747325399_fair2`.`tblmembers` m
    JOIN (
        SELECT 
            t.*,
            ROW_NUMBER() OVER (PARTITION BY t.`memberID` ORDER BY t.`accountsID` DESC) AS row_num
        FROM 
            `u747325399_fair2`.`tblmemberaccounts` t
        WHERE 
            t.`TransactionDate` BETWEEN ? AND ? -- Adjust date range here
    ) a ON m.`memberID` = a.`memberID` AND a.row_num = 1
");
    
    
    $stmt->bind_param("ss", $d1, $d2);
} else if ($single != 'all') {
    
    $stmt = $conn->prepare("
    SELECT 
        m.`MemberNo`,
        m.`membersurname`,
        m.`memberfirstname`,
        m.`DateOfBirth`,
        m.`gender`,
        m.`DateAccountOpened`,
        a.newbalance
    FROM 
        `u747325399_fair2`.`tblmembers` m
    JOIN (
        SELECT 
            t.*,
            ROW_NUMBER() OVER (PARTITION BY t.`memberID` ORDER BY t.`accountsID` DESC) AS row_num
        FROM 
            `u747325399_fair2`.`tblmemberaccounts` t
        WHERE 
            t.`TransactionDate` BETWEEN ? AND ? 
        AND t.memberid = ?
    ) a ON m.`memberID` = a.`memberID` AND a.row_num = 1
");
    
    $stmt->bind_param("sss", $d1, $d2, $single);
}

//echo "Query: " . $stmt->sqlstate . "\n";  // Debugging line


$stmt->execute();


if ($stmt->error) {
    die("Error: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output data of each row
    ?>
    <div class="table-responsive">
        <table class="table datatable responsive" id="free">
            <thead>
                <tr>
                    <th scope="col">Member No</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Date of Birth</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Date Account Opened</th>
                    <th scope="col">New Balance</th>
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
while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td scope="row"><?php
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
echo $row['membersurname']; ?></td>
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
echo $row['memberfirstname']; ?></td>
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
echo $row['DateOfBirth']; ?></td>
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
echo $row['gender']; ?></td>
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
echo $row['DateAccountOpened']; ?></td>
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
echo number_format($row['newbalance'], 2); ?></td>
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

$stmt->close();
$conn->close();
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
?>

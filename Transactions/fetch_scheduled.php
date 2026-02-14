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
$sql = "SELECT  `tblmembers1`.`MemberID`, `tblmembers1`.`MemberSurname`, `tblmembers1`.`MemberFirstname`, `tblmembers1`.`ApprovedBenefit`, `tblMemberAccounts1`.`NewBalance`, `tblMemberAccounts1`.`latest` FROM `tblmembers1` JOIN `tblMemberAccounts1` ON `tblmembers1`.`MemberID`= `tblMemberAccounts1`.`memberID` WHERE `tblmembers1`.`Terminated` IN ('0')   ";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
}

$dataset = array(
    "echo" => 1,
    "totalrecords" => count($array),
    "totaldisplayrecords" => count($array),
    "data" => $array
);

echo json_encode($dataset);
?>
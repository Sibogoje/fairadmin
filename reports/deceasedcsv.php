<?php
require_once '../scripts/connection.php';

$d1  = $_POST['date1'] ?? '';
$d2  = $_POST['date2'] ?? '';
$sel = $_POST['single']  ?? [];

if (!$d1 || !$d2 || empty($sel)) {
    exit('No data to export');
}

// build WHERE clause matching deceasedlist.php
if (in_array('all', $sel)) {
    $where  = "m.DateAccountOpened BETWEEN ? AND ?";
    $params = "ss";
    $vals    = [$d1, $d2];
} else {
    $ids     = implode(',', array_map('intval', $sel));
    $where   = "m.DateAccountOpened BETWEEN ? AND ? AND d.DeceasedID IN ($ids)";
    $params  = "ss";
    $vals     = [$d1, $d2];
}

$sql = "
 SELECT 
   CONCAT(d.DeceasedSurname,' ',d.DeceasedFirstnames) AS DeceasedName,
   CONCAT(m.MemberFirstname,' ',m.MemberSurname)    AS BeneficiaryName,
   m.RelationshipDeceased                           AS Relationship,
   d.DateOfDeath                                    AS DeceasedDOB
 FROM tbldeceased d
 JOIN tblmembers   m ON m.DeceasedID = d.DeceasedID
 WHERE $where
 ORDER BY d.DeceasedSurname, d.DeceasedFirstnames
";

$stmt = $conn->prepare($sql);
if (strpos($sql, '?') !== false) {
    $stmt->bind_param($params, ...$vals);
}
$stmt->execute();
$res = $stmt->get_result();

$filename = "Deceased_Report_". date('Y-m-d') .".csv";
$fh = fopen('php://memory','w');

// write header
fputcsv($fh, ['Name of Deceased','Beneficiary Name','Relationship','Date of Death']);

while ($row = $res->fetch_assoc()) {
    fputcsv($fh, [
        $row['DeceasedName'],
        $row['BeneficiaryName'],
        $row['Relationship'],
        $row['DeceasedDOB']
    ]);
}

fseek($fh, 0);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'. $filename .'";');
fpassthru($fh);
exit();
?>

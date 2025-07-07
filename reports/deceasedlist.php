<?php
require_once '../scripts/connection.php';
$d1  = $_POST['date1'] ?? '';
$d2  = $_POST['date2'] ?? '';
$sel = $_POST['single'] ?? [];

if (!$d1 || !$d2 || empty($sel)) {
  echo '<p style="color:red;">Please select dates and at least one deceased.</p>';
  exit;
}

// build filter: use members.DateAccountOpened
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
    m.RelationshipDeceased,
    d.DateOfDeath                                AS DeceasedDOB
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

if ($res->num_rows === 0) {
  echo '<p>No records found.</p>';
  exit;
}

echo '<div class="table-responsive">
        <table class="table datatable" id="free">
          <thead>
            <tr>
              <th>Name of Deceased</th>
              <th>Beneficiary Name</th>
              <th>Relationship to Beneficiary</th>
              <th>Deceased Date of Death</th>
            </tr>
          </thead>
          <tbody>';
while ($row = $res->fetch_assoc()) {
  echo '<tr>',
       '<td>', htmlspecialchars($row['DeceasedName']),       '</td>',
       '<td>', htmlspecialchars($row['BeneficiaryName']),    '</td>',
       '<td>', htmlspecialchars($row['RelationshipDeceased']),'</td>',
       '<td>', htmlspecialchars($row['DeceasedDOB']),        '</td>',
       '</tr>';
}
echo '    </tbody>
        </table>
      </div>
      <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>';

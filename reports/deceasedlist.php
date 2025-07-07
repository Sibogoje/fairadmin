<?php
require_once '../scripts/connection.php';
$d1 = $_POST['date1'] ?? '';
$d2 = $_POST['date2'] ?? '';
$sel = $_POST['single'] ?? [];

if (!$d1 || !$d2 || empty($sel)) {
  echo '<p style="color:red;">Please select dates and at least one deceased.</p>';
  exit;
}

// build filter
if (in_array('all', $sel)) {
  $where = "DateOfDeath BETWEEN ? AND ?";
  $params = "ss";
  $vals = [$d1, $d2];
} else {
  $ids = implode(',', array_map('intval', $sel));
  $where = "d.DeceasedID IN ($ids) AND DateOfDeath BETWEEN ? AND ?";
  $params = "ss";
  $vals = [$d1, $d2];
}

$sql = "
 SELECT 
   d.DeceasedID,
   CONCAT(d.DeceasedSurname, ' ', d.DeceasedFirstnames) AS DeceasedName,
   d.DateOfDeath,
   m.RelationshipDeceased
 FROM tbldeceased d
 LEFT JOIN tblmembers m ON m.DeceasedID = d.DeceasedID
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

echo '<div class="table-responsive"><table class="table datatable" id="free">
        <thead>
          <tr>
            <th>Name of Deceased</th>
            <th>Relationship to Beneficiary</th>
            <th>Date of Birth</th>
          </tr>
        </thead><tbody>';
while ($row = $res->fetch_assoc()) {
  echo '<tr>',
       '<td>', htmlspecialchars($row['DeceasedName']), '</td>',
       '<td>', htmlspecialchars($row['RelationshipDeceased'] ?? '-'), '</td>',
       '<td>', htmlspecialchars($row['DateOfDeath']), '</td>',
       '</tr>';
}
echo '</tbody></table></div>',
     '<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>';

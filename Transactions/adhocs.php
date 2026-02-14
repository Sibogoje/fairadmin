<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
$servername = "srv1212.hstgr.io";
$username = "u747325399_fair2";
$password = "2Jznf67RS=";
$dbname   = 'u747325399_fair2';

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Path to your CSV
$csvFile = 'adhocs.csv';

if (($handle = fopen($csvFile, "r")) !== false) {
    // Skip header row
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $memberNo = trim($data[0]);
        $transactionDate = trim($data[1]);
        $comments = trim($data[2]);
        $amount = str_replace(',', '', trim($data[3])); // remove commas

        // Get MemberID and Name from tblmembers
        $stmt = $conn->prepare("SELECT MemberID, CONCAT(MemberFirstname, ' ', MemberSurname) AS Name FROM tblmembers WHERE MemberNo = ?");
        $stmt->bind_param("i", $memberNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $memberID = $row['MemberID'];
            $name = $row['Name'];

            // Insert into tbltempadhocpayments
            $insert = $conn->prepare("INSERT INTO tbltempadhocpayments (MemberID, Name, PaymentDate, Details, AdHocPayment, Comments) VALUES (?, ?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)");
            $insert->bind_param("isssds", $memberID, $name, $transactionDate, $comments, $amount, $comments);
            $insert->execute();
        }
    }
    fclose($handle);
}

$conn->close();

echo "CSV data imported successfully.";
?>
<?php
require_once __DIR__ . '/../scripts/bootstrap.php';
// End of the PHP script
?>
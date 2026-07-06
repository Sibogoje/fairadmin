<?php
require_once __DIR__ . '/helpers.php';

require_post();
$member = require_auth($pdo);

$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
$details = trim((string) ($_POST['details'] ?? ''));

if (!$amount || $amount <= 0 || $details === '') {
    api_response(422, ['success' => false, 'message' => 'Amount and request details are required.']);
}

if (empty($_FILES['combined_pdf']['name']) || !is_uploaded_file($_FILES['combined_pdf']['tmp_name'])) {
    api_response(422, ['success' => false, 'message' => 'A combined PDF document is required.']);
}

$extension = strtolower(pathinfo($_FILES['combined_pdf']['name'], PATHINFO_EXTENSION));
if ($extension !== 'pdf') {
    api_response(422, ['success' => false, 'message' => 'The combined document must be a PDF.']);
}

$memberNo = $member['MemberNo'];
$memberId = $member['MemberID'];
$fnumber = $memberNo . date('YmdHis') . random_int(100, 999);
$uploadDirectory = __DIR__ . '/../uploads/' . $memberNo . '/';
$relativeDirectory = 'uploads/' . $memberNo . '/';

if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0777, true)) {
    api_response(500, ['success' => false, 'message' => 'Unable to prepare upload directory.']);
}

$fileName = $fnumber . '_adhoc_request.pdf';
$absolutePath = $uploadDirectory . $fileName;
$relativePath = $relativeDirectory . $fileName;

if (!move_uploaded_file($_FILES['combined_pdf']['tmp_name'], $absolutePath)) {
    api_response(500, ['success' => false, 'message' => 'Unable to save uploaded PDF.']);
}

$publicUrl = app_url('mobile/' . $relativePath);
$requestDate = date('Y-m-d');
$fullName = member_display_name($member);

try {
    $stmt = $pdo->prepare("INSERT INTO clientr (memberid, name, amount, reason, file, reqdate, fnumber)
        VALUES (:memberid, :name, :amount, :reason, :file, :reqdate, :fnumber)");
    $stmt->bindValue(':memberid', $memberId, PDO::PARAM_INT);
    $stmt->bindValue(':name', $fullName, PDO::PARAM_STR);
    $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
    $stmt->bindValue(':reason', $details, PDO::PARAM_STR);
    $stmt->bindValue(':file', $publicUrl, PDO::PARAM_STR);
    $stmt->bindValue(':reqdate', $requestDate, PDO::PARAM_STR);
    $stmt->bindValue(':fnumber', $fnumber, PDO::PARAM_STR);
    $stmt->execute();

    try {
        $files = $pdo->prepare("INSERT INTO tblfiles (member, name, dateupload, reason, url)
            VALUES (:member, :name, :dateupload, :reason, :url)");
        $files->bindValue(':member', $memberId, PDO::PARAM_STR);
        $files->bindValue(':name', $fullName, PDO::PARAM_STR);
        $files->bindValue(':dateupload', $requestDate, PDO::PARAM_STR);
        $files->bindValue(':reason', 'Adhoc request - ' . $details, PDO::PARAM_STR);
        $files->bindValue(':url', $relativePath, PDO::PARAM_STR);
        $files->execute();
    } catch (Throwable $fileError) {
        error_log('mobile adhoc tblfiles insert failed: ' . $fileError->getMessage());
    }
} catch (Throwable $error) {
    if (file_exists($absolutePath)) {
        unlink($absolutePath);
    }
    error_log('mobile adhoc insert failed: ' . $error->getMessage());
    api_response(500, ['success' => false, 'message' => 'Unable to submit adhoc request.']);
}

api_response(201, [
    'success' => true,
    'message' => 'Adhoc request submitted.',
    'request' => [
        'reference' => $fnumber,
        'file' => $publicUrl,
        'request_date' => $requestDate,
    ],
]);
?>
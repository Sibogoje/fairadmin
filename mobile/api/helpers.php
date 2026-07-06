<?php
require_once __DIR__ . '/../../scripts/bootstrap.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function api_response($statusCode, $payload)
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

function require_post()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        api_response(405, ['success' => false, 'message' => 'POST is required.']);
    }
}

function request_json()
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        api_response(400, ['success' => false, 'message' => 'Invalid JSON request.']);
    }

    return $data;
}

function clean_id_value($value)
{
    return strtoupper(preg_replace('/\s+/', '', trim((string) $value)));
}

function clean_answer($value)
{
    return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
}

function clean_date_value($value)
{
    $date = date_create((string) $value);
    return $date ? $date->format('Y-m-d') : trim((string) $value);
}

function ensure_mobile_api_tables(PDO $pdo)
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS mobile_api_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        memberid VARCHAR(64) NOT NULL,
        token_hash CHAR(64) NOT NULL,
        device_name VARCHAR(255) NULL,
        expires_at DATETIME NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        last_used_at DATETIME NULL,
        UNIQUE KEY uq_mobile_api_token_hash (token_hash),
        KEY idx_mobile_api_memberid (memberid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS mobile_security_questions (
        memberid VARCHAR(64) NOT NULL PRIMARY KEY,
        question1 VARCHAR(255) NOT NULL,
        answer1_hash VARCHAR(255) NOT NULL,
        question2 VARCHAR(255) NOT NULL,
        answer2_hash VARCHAR(255) NOT NULL,
        question3 VARCHAR(255) NOT NULL,
        answer3_hash VARCHAR(255) NOT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function fetch_member(PDO $pdo, $memberNo)
{
    $stmt = $pdo->prepare("SELECT
            m.MemberID,
            m.MemberNo,
            m.MemberFirstname,
            m.MemberSurname,
            m.MemberIDnumber,
            m.DateAccountOpened,
            m.Terminated,
            d.DeceasedIDnumber
        FROM tblmembers m
        LEFT JOIN tbldeceased d ON d.DeceasedID = m.DeceasedID
        WHERE m.MemberNo = :memberno
        LIMIT 1");
    $stmt->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function member_is_active($member)
{
    return $member && (string) $member['Terminated'] === '0';
}

function verify_identity_payload($member, $payload)
{
    return clean_id_value($member['DeceasedIDnumber'] ?? '') === clean_id_value($payload['deceased_id_number'] ?? '')
        && clean_id_value($member['MemberIDnumber'] ?? '') === clean_id_value($payload['member_id_number'] ?? '')
        && clean_date_value($member['DateAccountOpened'] ?? '') === clean_date_value($payload['date_account_opened'] ?? '');
}

function member_display_name($member)
{
    return $member['MemberNo'] . '-' . trim($member['MemberFirstname'] . ' ' . $member['MemberSurname']);
}

function issue_token(PDO $pdo, $memberNo, $deviceName = null)
{
    ensure_mobile_api_tables($pdo);

    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

    $stmt = $pdo->prepare("INSERT INTO mobile_api_tokens (memberid, token_hash, device_name, expires_at)
        VALUES (:memberid, :token_hash, :device_name, :expires_at)");
    $stmt->bindValue(':memberid', $memberNo, PDO::PARAM_STR);
    $stmt->bindValue(':token_hash', $tokenHash, PDO::PARAM_STR);
    $stmt->bindValue(':device_name', $deviceName, PDO::PARAM_STR);
    $stmt->bindValue(':expires_at', $expiresAt, PDO::PARAM_STR);
    $stmt->execute();

    return ['token' => $token, 'expires_at' => $expiresAt];
}

function bearer_token()
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (stripos($header, 'Bearer ') !== 0) {
        return null;
    }

    return trim(substr($header, 7));
}

function require_auth(PDO $pdo)
{
    ensure_mobile_api_tables($pdo);

    $token = bearer_token();
    if (!$token) {
        api_response(401, ['success' => false, 'message' => 'Missing authorization token.']);
    }

    $stmt = $pdo->prepare("SELECT memberid FROM mobile_api_tokens
        WHERE token_hash = :token_hash AND expires_at > NOW()
        LIMIT 1");
    $stmt->bindValue(':token_hash', hash('sha256', $token), PDO::PARAM_STR);
    $stmt->execute();
    $tokenRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenRow) {
        api_response(401, ['success' => false, 'message' => 'Invalid or expired token.']);
    }

    $update = $pdo->prepare("UPDATE mobile_api_tokens SET last_used_at = NOW() WHERE token_hash = :token_hash");
    $update->bindValue(':token_hash', hash('sha256', $token), PDO::PARAM_STR);
    $update->execute();

    $member = fetch_member($pdo, $tokenRow['memberid']);
    if (!member_is_active($member)) {
        api_response(403, ['success' => false, 'message' => 'Account is not active.']);
    }

    return $member;
}
?>
<?php
require_once __DIR__ . '/helpers.php';

require_post();
ensure_mobile_api_tables($pdo);

$payload = request_json();
$memberNo = trim((string) ($payload['memberno'] ?? ''));
$password = (string) ($payload['password'] ?? '');

if ($memberNo === '' || $password === '') {
    api_response(422, ['success' => false, 'message' => 'Member number and password are required.']);
}

$member = fetch_member($pdo, $memberNo);
if (!member_is_active($member)) {
    api_response(403, ['success' => false, 'message' => 'Member account is not active or could not be found.']);
}

$stmt = $pdo->prepare("SELECT password FROM MobUsers WHERE memberid = :memberno LIMIT 1");
$stmt->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    api_response(409, ['success' => false, 'code' => 'FIRST_LOGIN_REQUIRED', 'message' => 'Please complete first login before signing in.']);
}

if (!password_verify($password, $user['password'])) {
    api_response(401, ['success' => false, 'message' => 'Invalid login credentials.']);
}

$security = $pdo->prepare("SELECT memberid FROM mobile_security_questions WHERE memberid = :memberno LIMIT 1");
$security->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
$security->execute();

if (!$security->fetch(PDO::FETCH_ASSOC)) {
    api_response(409, ['success' => false, 'code' => 'SECURITY_SETUP_REQUIRED', 'message' => 'Security questions must be set before using the app.']);
}

$update = $pdo->prepare("UPDATE MobUsers SET online = 1, deviceinfo = :deviceinfo WHERE memberid = :memberno");
$update->bindValue(':deviceinfo', $payload['device_name'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? 'Flutter Android'), PDO::PARAM_STR);
$update->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
$update->execute();

$issuedToken = issue_token($pdo, $memberNo, $payload['device_name'] ?? null);

api_response(200, [
    'success' => true,
    'message' => 'Login successful.',
    'token' => $issuedToken['token'],
    'expires_at' => $issuedToken['expires_at'],
    'member' => [
        'memberno' => $member['MemberNo'],
        'name' => member_display_name($member),
    ],
]);
?>
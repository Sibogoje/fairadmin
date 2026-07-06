<?php
require_once __DIR__ . '/helpers.php';

require_post();
ensure_mobile_api_tables($pdo);

$payload = request_json();
$memberNo = trim((string) ($payload['memberno'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$questions = $payload['security_questions'] ?? [];

if ($memberNo === '' || strlen($password) < 8 || !is_array($questions) || count($questions) !== 3) {
    api_response(422, ['success' => false, 'message' => 'Member number, password of at least 8 characters, and 3 security questions are required.']);
}

$member = fetch_member($pdo, $memberNo);
if (!member_is_active($member)) {
    api_response(403, ['success' => false, 'message' => 'Member account is not active or could not be found.']);
}

if (!verify_identity_payload($member, $payload)) {
    api_response(403, ['success' => false, 'message' => 'The member details provided do not match our records.']);
}

foreach ($questions as $question) {
    if (trim((string) ($question['question'] ?? '')) === '' || strlen(clean_answer($question['answer'] ?? '')) < 2) {
        api_response(422, ['success' => false, 'message' => 'Each security question must include an answer.']);
    }
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$pdo->beginTransaction();

try {
    $existing = $pdo->prepare("SELECT id FROM MobUsers WHERE memberid = :memberno LIMIT 1");
    $existing->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
    $existing->execute();

    if ($existing->fetch(PDO::FETCH_ASSOC)) {
        $stmt = $pdo->prepare("UPDATE MobUsers SET password = :password, online = 0 WHERE memberid = :memberno");
    } else {
        $stmt = $pdo->prepare("INSERT INTO MobUsers (memberid, password, online) VALUES (:memberno, :password, 0)");
    }

    $stmt->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
    $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
    $stmt->execute();

    $security = $pdo->prepare("REPLACE INTO mobile_security_questions
        (memberid, question1, answer1_hash, question2, answer2_hash, question3, answer3_hash)
        VALUES (:memberid, :q1, :a1, :q2, :a2, :q3, :a3)");
    $security->bindValue(':memberid', $memberNo, PDO::PARAM_STR);
    $security->bindValue(':q1', trim((string) $questions[0]['question']), PDO::PARAM_STR);
    $security->bindValue(':a1', password_hash(clean_answer($questions[0]['answer']), PASSWORD_DEFAULT), PDO::PARAM_STR);
    $security->bindValue(':q2', trim((string) $questions[1]['question']), PDO::PARAM_STR);
    $security->bindValue(':a2', password_hash(clean_answer($questions[1]['answer']), PASSWORD_DEFAULT), PDO::PARAM_STR);
    $security->bindValue(':q3', trim((string) $questions[2]['question']), PDO::PARAM_STR);
    $security->bindValue(':a3', password_hash(clean_answer($questions[2]['answer']), PASSWORD_DEFAULT), PDO::PARAM_STR);
    $security->execute();

    $pdo->commit();
} catch (Throwable $error) {
    $pdo->rollBack();
    error_log('mobile first-login failed: ' . $error->getMessage());
    api_response(500, ['success' => false, 'message' => 'Unable to complete first login.']);
}

$issuedToken = issue_token($pdo, $memberNo, $payload['device_name'] ?? null);

api_response(201, [
    'success' => true,
    'message' => 'First login completed.',
    'token' => $issuedToken['token'],
    'expires_at' => $issuedToken['expires_at'],
    'member' => [
        'memberno' => $member['MemberNo'],
        'name' => member_display_name($member),
    ],
]);
?>
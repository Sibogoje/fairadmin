<?php
require_once __DIR__ . '/helpers.php';

ensure_mobile_api_tables($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $memberNo = trim((string) ($_GET['memberno'] ?? ''));
    if ($memberNo === '') {
        api_response(422, ['success' => false, 'message' => 'Member number is required.']);
    }

    $stmt = $pdo->prepare("SELECT question1, question2, question3 FROM mobile_security_questions WHERE memberid = :memberno LIMIT 1");
    $stmt->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        api_response(404, ['success' => false, 'message' => 'No security questions are registered for this member.']);
    }

    api_response(200, [
        'success' => true,
        'questions' => [$row['question1'], $row['question2'], $row['question3']],
    ]);
}

require_post();
$payload = request_json();
$memberNo = trim((string) ($payload['memberno'] ?? ''));
$newPassword = (string) ($payload['password'] ?? '');
$answers = $payload['answers'] ?? [];

if ($memberNo === '' || strlen($newPassword) < 8 || !is_array($answers) || count($answers) !== 3) {
    api_response(422, ['success' => false, 'message' => 'Member number, a new password, and all 3 answers are required.']);
}

$member = fetch_member($pdo, $memberNo);
if (!member_is_active($member)) {
    api_response(403, ['success' => false, 'message' => 'Member account is not active or could not be found.']);
}

$stmt = $pdo->prepare("SELECT question1, question2, question3, answer1_hash, answer2_hash, answer3_hash FROM mobile_security_questions WHERE memberid = :memberno LIMIT 1");
$stmt->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
$stmt->execute();
$security = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$security) {
    api_response(404, ['success' => false, 'message' => 'Security questions have not been set.']);
}

$expectedByQuestion = [
    clean_answer($security['question1'] ?? '') => $security['answer1_hash'],
    clean_answer($security['question2'] ?? '') => $security['answer2_hash'],
    clean_answer($security['question3'] ?? '') => $security['answer3_hash'],
];

$allAnswersMatch = true;

foreach ($answers as $index => $answerPayload) {
    if (is_array($answerPayload)) {
        $questionKey = clean_answer($answerPayload['question'] ?? '');
        $answerValue = clean_answer($answerPayload['answer'] ?? '');
        if ($questionKey === '' || !isset($expectedByQuestion[$questionKey]) || !password_verify($answerValue, $expectedByQuestion[$questionKey])) {
            $allAnswersMatch = false;
            break;
        }
    } else {
        $answerKey = 'answer' . ((int) $index + 1) . '_hash';
        if (!isset($security[$answerKey]) || !password_verify(clean_answer($answerPayload), $security[$answerKey])) {
            $allAnswersMatch = false;
            break;
        }
    }
}

if (!$allAnswersMatch) {
    api_response(403, ['success' => false, 'message' => 'Security answers do not match.']);
}

$update = $pdo->prepare("UPDATE MobUsers SET password = :password, online = 0 WHERE memberid = :memberno");
$update->bindValue(':password', password_hash($newPassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
$update->bindValue(':memberno', $memberNo, PDO::PARAM_STR);
$update->execute();

api_response(200, ['success' => true, 'message' => 'Password reset successful.']);
?>
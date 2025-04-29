<?php
include_once(__DIR__ . '/../../lib/common.php');

header('Content-Type: application/json; charset=utf-8');

$mb_id = trim($_POST['mb_id'] ?? '');

if (!$mb_id || !preg_match('/^[a-zA-Z0-9]{4,}$/', $mb_id)) {
    echo json_encode(['status' => 'invalid', 'message' => '형식이 올바르지 않습니다']);
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM g5_member WHERE mb_id = ?");
$stmt->bind_param("s", $mb_id);
$stmt->execute();
$stmt->bind_result($exists);
$stmt->fetch();
$stmt->close();

if ($exists > 0) {
    echo json_encode(['status' => 'exists', 'message' => '이미 사용 중인 아이디입니다']);
} else {
    echo json_encode(['status' => 'ok', 'message' => '사용 가능한 아이디입니다']);
}

<?php
include_once(__DIR__ . '/../../lib/common.php');

$token = $_GET['token'] ?? '';

if (!$token) {
    $_SESSION['verify_result'] = '잘못된 접근입니다.';
    header("Location: /html/auth/verify_result.php");
    exit;
}

$stmt = $conn->prepare("SELECT mb_id FROM g5_member WHERE mb_email_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['verify_result'] = '유효하지 않은 토큰입니다.';
    header("Location: /html/auth/verify_result.php");
    exit;
}

$stmt->bind_result($mb_id);
$stmt->fetch();
$stmt->close();

// 인증 완료 처리
$update = $conn->prepare("
    UPDATE g5_member 
    SET mb_email_certified = 1, mb_email_token = NULL, mb_email_token_created = NULL 
    WHERE mb_id = ?
");
$update->bind_param("s", $mb_id);
$update->execute();
$update->close();

$_SESSION['verify_result'] = '이메일 인증이 완료되었습니다. 로그인해주세요!';
header("Location: /html/auth/verify_result.php");
exit;

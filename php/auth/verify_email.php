<?php
include_once(__DIR__ . '/../../lib/common.php');

$token = $_GET['token'] ?? '';

if (!$token) {
    $_SESSION['verify_result'] = '잘못된 접근입니다.';
    redirect_to('/html/auth/verify_result.php');
    exit;
}

$stmt = $conn->prepare("SELECT mb_id, mb_email_token_created FROM g5_member WHERE mb_email_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['verify_result'] = '유효하지 않은 토큰입니다.';
    redirect_to('/html/auth/verify_result.php');
    exit;
}

$stmt->bind_result($mb_id, $token_created);
$stmt->fetch();
$stmt->close();

// 토큰 유효 시간 확인 (1시간 이내만 유효)
$created_time = strtotime($token_created);
$current_time = time();
if (($current_time - $created_time) > 3600) {
    $_SESSION['verify_result'] = '이메일 인증 유효 시간이 만료되었습니다. 다시 시도해주세요.';
    redirect_to('/html/auth/verify_result.php');
    exit;
}

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
redirect_to('/html/auth/verify_result.php');
exit;

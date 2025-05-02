<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message("잘못된 접근입니다.", '/html/auth/login_form.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message("CSRF 토큰이 유효하지 않습니다.", '/html/auth/login_form.php');
}

$token = $_SESSION['reset_pw_token'] ?? '';
$mb_id = $_SESSION['reset_mb_id'] ?? '';

if (!$token || !$mb_id) {
    redirect_with_message("세션이 만료되었거나 유효하지 않습니다.", '/html/auth/login_form.php');
}

$pw1 = $_POST['new_pw'] ?? '';
$pw2 = $_POST['new_pw_confirm'] ?? '';

if ($pw1 !== $pw2) {
    redirect_with_message("비밀번호가 일치하지 않습니다.", '/html/auth/reset_password.php?token=' . urlencode($token));
}

if (strlen($pw1) < 8) {
    redirect_with_message("비밀번호는 8자 이상이어야 합니다.", '/html/auth/reset_password.php?token=' . urlencode($token));
}

$pw_hash = password_hash($pw1, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    UPDATE g5_member 
    SET mb_password = ?, mb_pw_token = NULL, mb_pw_token_created = NULL 
    WHERE mb_id = ? AND mb_pw_token = ?
");
$stmt->bind_param("sss", $pw_hash, $mb_id, $token);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    redirect_with_message("비밀번호 변경에 실패했습니다. 다시 시도해주세요.", '/html/auth/login_form.php');
}

$stmt->close();
unset($_SESSION['reset_pw_token'], $_SESSION['reset_mb_id']);

redirect_with_message("비밀번호가 성공적으로 변경되었습니다. 로그인해주세요.", '/html/auth/login_form.php');
exit;

<?php
include_once(__DIR__ . '/../../lib/common.php');

function redirect_error($msg) {
    $_SESSION['error_message'] = $msg;
    header("Location: /error/error.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_error("잘못된 접근입니다.");
}

// CSRF 검증
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_error("CSRF 토큰이 유효하지 않습니다.");
}

// 입력값 가져오기 및 기본 필터링
$mb_id = trim($_POST['mb_id'] ?? '');
$mb_email = trim($_POST['mb_email'] ?? '');
$mb_password = $_POST['mb_password'] ?? '';
$mb_password_confirm = $_POST['mb_password_confirm'] ?? '';
$mb_streamer_url = trim($_POST['mb_streamer_url'] ?? '');

if (!$mb_id || !$mb_email || !$mb_password || !$mb_password_confirm) {
    redirect_error("모든 필수 입력값을 입력해주세요.");
}

// 비밀번호 확인
if ($mb_password !== $mb_password_confirm) {
    redirect_error("비밀번호가 일치하지 않습니다.");
}

// 중복 체크
$stmt = $conn->prepare("SELECT COUNT(*) FROM g5_member WHERE mb_id = ? OR mb_email = ?");
$stmt->bind_param("ss", $mb_id, $mb_email);
$stmt->execute();
$stmt->bind_result($exists);
$stmt->fetch();
$stmt->close();

if ($exists > 0) {
    redirect_error("이미 사용 중인 아이디 또는 이메일입니다.");
}

// 비밀번호 암호화
$hashed_pw = password_hash($mb_password, PASSWORD_DEFAULT);

// 이메일 인증 토큰 생성
$email_token = bin2hex(random_bytes(32));
$token_created = date("Y-m-d H:i:s");

// INSERT
$stmt = $conn->prepare("INSERT INTO g5_member (mb_id, mb_password, mb_email, mb_streamer_url, mb_email_token, mb_email_token_created) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $mb_id, $hashed_pw, $mb_email, $mb_streamer_url, $email_token, $token_created);

if (!$stmt->execute()) {
    log_error("회원가입 INSERT 실패: " . $stmt->error);
    redirect_error("회원가입 중 오류가 발생했습니다.");
}

$stmt->close();

// 성공 → 로그인 or 이메일 인증 페이지로 리디렉션
header("Location: /html/auth/login_form.php");
exit;

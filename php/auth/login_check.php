<?php
include_once(__DIR__ . '/../../lib/common.php');

function redirect_error($msg) {
    $_SESSION['error_message'] = $msg;
    header("Location: /error/error.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_error("잘못된 요청 방식입니다.");
}

// CSRF 토큰 검증
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_error("CSRF 토큰 오류입니다.");
}

$mb_id = trim($_POST['mb_id'] ?? '');
$mb_password = $_POST['mb_password'] ?? '';

if (!$mb_id || !$mb_password) {
    redirect_error("아이디와 비밀번호를 입력해주세요.");
}

// 사용자 정보 조회
$stmt = $conn->prepare("SELECT mb_no, mb_id, mb_password, mb_level, mb_is_leave, mb_is_intercepted FROM g5_member WHERE mb_id = ?");
$stmt->bind_param("s", $mb_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    redirect_error("존재하지 않는 아이디입니다.");
}

if ($user['mb_is_leave']) {
    redirect_error("탈퇴한 계정입니다.");
}

if ($user['mb_is_intercepted']) {
    redirect_error("차단된 계정입니다.");
}

// 비밀번호 확인
if (!password_verify($mb_password, $user['mb_password'])) {
    redirect_error("비밀번호가 일치하지 않습니다.");
}

// 로그인 성공 - 세션 설정
session_regenerate_id(true); // 세션 고정 공격 방지

$_SESSION['mb_no']    = $user['mb_no'];
$_SESSION['mb_id']    = $user['mb_id'];
$_SESSION['mb_level'] = $user['mb_level'];

// 로그인 성공 → 메인 페이지로
header("Location: /index.php");
exit;

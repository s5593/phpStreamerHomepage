<?php
include_once(__DIR__ . '/../../lib/common.php');

function log_login_attempt($mb_id, $success) {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt = $conn->prepare("
        INSERT INTO g5_login_log (mb_id, ip_address, user_agent, login_datetime, success)
        VALUES (?, ?, ?, NOW(), ?)
    ");
    $stmt->bind_param("ssss", $mb_id, $ip, $ua, $success);
    $stmt->execute();
    $stmt->close();
}

function is_login_blocked($mb_id) {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];

    // 1. 아이디 기준 (5회 이상 실패 시 차단)
    $stmt1 = $conn->prepare("
        SELECT COUNT(*) AS cnt
        FROM g5_login_log
        WHERE mb_id = ?
          AND success = 'N'
          AND login_datetime > (NOW() - INTERVAL 10 MINUTE)
    ");
    $stmt1->bind_param("s", $mb_id);
    $stmt1->execute();
    $row1 = $stmt1->get_result()->fetch_assoc();
    $stmt1->close();

    // 2. IP 기준 (10회 이상 실패 시 차단)
    $stmt2 = $conn->prepare("
        SELECT COUNT(*) AS cnt
        FROM g5_login_log
        WHERE ip_address = ?
          AND success = 'N'
          AND login_datetime > (NOW() - INTERVAL 10 MINUTE)
    ");
    $stmt2->bind_param("s", $ip);
    $stmt2->execute();
    $row2 = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    return $row1['cnt'] >= 5 || $row2['cnt'] >= 10;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_error("잘못된 요청 방식입니다.");
}

// ✅ CSRF 토큰 검증
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_error("CSRF 토큰 오류입니다.");
}

$mb_id = trim($_POST['mb_id'] ?? '');
$mb_password = $_POST['mb_password'] ?? '';

if (!$mb_id || !$mb_password) {
    redirect_error("아이디와 비밀번호를 입력해주세요.");
}

// ✅ 로그인 차단 여부 확인 (10분간 5회 실패 시 차단)
if (is_login_blocked($mb_id)) {
    redirect_error("로그인 시도 횟수가 너무 많습니다. 10분 후 다시 시도해주세요.");
}

// 사용자 정보 조회
$stmt = $conn->prepare("SELECT mb_no, mb_id, mb_password, mb_level, mb_is_leave, mb_is_intercepted, mb_email_certified FROM g5_member WHERE mb_id = ?");
$stmt->bind_param("s", $mb_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    log_login_attempt($mb_id, 'N');
    show_alert_and_back("존재하지 않는 아이디입니다.");
}

if ($user['mb_is_leave']) {
    log_login_attempt($mb_id, 'N');
    show_alert_and_back("탈퇴한 계정입니다.");
}

if ($user['mb_is_intercepted']) {
    log_login_attempt($mb_id, 'N');
    show_alert_and_back("차단된 계정입니다.");
}

// ✅ 이메일 인증 확인
if ((int)$user['mb_email_certified'] !== 1) {
    log_login_attempt($mb_id, 'N');
    show_alert_and_back("이메일 인증이 완료되지 않았습니다.");
}

// 비밀번호 확인
if (!password_verify($mb_password, $user['mb_password'])) {
    log_login_attempt($mb_id, 'N');
    show_alert_and_back("비밀번호가 일치하지 않습니다.");
}

// ✅ 로그인 성공 시 로그 기록
log_login_attempt($mb_id, 'Y');

// ✅ 세션 고정 방지 (기존 세션 제거 → 새 ID 발급)
session_regenerate_id(true);

// ✅ 세션 변수 설정
$_SESSION['mb_no']    = $user['mb_no'];
$_SESSION['mb_id']    = $user['mb_id'];
$_SESSION['mb_level'] = $user['mb_level'];

// ✅ 여기 추가: 세션 하이재킹 방지용 정보
$_SESSION['login_ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['login_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

// ✅ 로그인 완료 후 이동
header("Location: /index.php");
exit;
<?php
include_once('./_common.php');

if (!isset($_SESSION)) session_start();

// ✅ CSRF 토큰 검증
$token = $_POST['token'] ?? '';
if (!isset($_SESSION['login_token']) || $token !== $_SESSION['login_token']) {
    alert('유효하지 않은 요청입니다. (CSRF 차단)');
}

// ✅ 로그인 실패 제한 설정
$mb_id = trim($_POST['mb_id'] ?? '');
$mb_password = trim($_POST['mb_password'] ?? '');
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!$mb_id || !$mb_password) {
    alert('아이디와 비밀번호를 모두 입력해 주세요.');
}

// 최근 10분간 로그인 실패 횟수 조회
$fail_check_sql = "SELECT COUNT(*) AS cnt 
                   FROM g5_login_log 
                   WHERE mb_id = '{$mb_id}' 
                     AND success = 'N' 
                     AND login_datetime > DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
$fail_result = sql_fetch($fail_check_sql);
if ((int)$fail_result['cnt'] >= 5) {
    alert("로그인 시도 횟수가 너무 많습니다. 잠시 후 다시 시도해주세요.");
}

// 회원 조회
$mb = get_member($mb_id);

// 비밀번호 검증
if (!$mb || !password_verify($mb_password, $mb['mb_password'])) {
    // 실패 로그 기록
    $sql = "INSERT INTO g5_login_log (mb_id, ip_address, user_agent, login_datetime, success)
            VALUES ('{$mb_id}', '{$ip_address}', '{$_SERVER['HTTP_USER_AGENT']}', NOW(), 'N')";
    sql_query($sql);
    alert('아이디 또는 비밀번호가 틀렸습니다.');
}

// 차단 여부 확인
if (!empty($mb['mb_is_intercepted']) && $mb['mb_is_intercepted'] == 1 && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
    alert("접근이 제한된 계정입니다. 처리일: {$date}");
}

// 탈퇴 여부 확인
if (!empty($mb['mb_is_leave']) && $mb['mb_is_leave'] == 1 && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
    alert("탈퇴한 계정입니다. 탈퇴일: {$date}");
}

// 이메일 인증 여부 확인
if (is_use_email_certify() && (!isset($mb['mb_email_certify']) || $mb['mb_email_certify'] !== 'Y')) {
    alert("이메일 인증을 완료한 후 로그인할 수 있습니다.");
}

// ✅ 로그인 성공 처리

// 세션 고정 공격 방지
session_regenerate_id(true);

// 세션에 로그인 정보 저장
set_session('ss_mb_id', $mb['mb_id']);

// 세션 내 토큰 제거
unset($_SESSION['login_token']);

// 사용자 인증 키 생성
generate_mb_key($mb);

// 로그인 성공 로그 기록
$sql = "INSERT INTO g5_login_log (mb_id, ip_address, user_agent, login_datetime, success)
        VALUES ('{$mb['mb_id']}', '{$ip_address}', '{$_SERVER['HTTP_USER_AGENT']}', NOW(), 'Y')";
sql_query($sql);

// 로그인 후 이동
$url = $_POST['url'] ?? G5_URL;
goto_url($url);

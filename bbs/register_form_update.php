<?php
// 📄 register_form_update.php — 전체 보안 통합 회원가입 처리 코드

session_start();
include_once('./_common.php');

// ✅ 세션 보안 설정 (세션 고정 공격 방지)
if (!isset($_SESSION['session_initialized'])) {
    session_regenerate_id(true);
    $_SESSION['session_initialized'] = true;
}
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    die("비정상적인 접근입니다 (User-Agent 변경)");
}

// ✅ CSRF 토큰 유효성 검사 + 유효시간 5분 제한
if (
    !isset($_SESSION['csrf_token']) ||
    !isset($_SESSION['csrf_token_created']) ||
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    die("잘못된 접근입니다 (CSRF 검증 실패)");
}
if (time() - $_SESSION['csrf_token_created'] > 300) {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_created']);
    die("토큰 유효시간이 초과되었습니다. 다시 시도해주세요.");
}
unset($_SESSION['csrf_token'], $_SESSION['csrf_token_created']);

// ✅ Rate Limit (10초 제한)
if (isset($_SESSION['last_join_attempt']) && time() - $_SESSION['last_join_attempt'] < 10) {
    die("너무 빠르게 가입을 시도하고 있습니다.");
}
$_SESSION['last_join_attempt'] = time();

// ✅ 입력값 처리
$mb_id       = sql_real_escape_string(trim($_POST['mb_id']));
$mb_password = sql_real_escape_string(trim($_POST['mb_password']));
$mb_email    = sql_real_escape_string(trim($_POST['mb_email']));
$mb_homepage = sql_real_escape_string(trim($_POST['mb_homepage']));
$now         = date('Y-m-d H:i:s');

// ✅ 정규식 유효성 검사
if (!preg_match('/^[가-힣a-zA-Z0-9]{2,12}$/u', $mb_id)) {
    die("아이디는 한글, 영문, 숫자를 포함한 2~12자만 가능합니다. 특수문자는 사용할 수 없습니다.");
}
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,20}$/', $mb_password)) {
    die("비밀번호는 영문, 숫자, 특수문자 조합 8~20자여야 합니다.");
}
if (!filter_var($mb_email, FILTER_VALIDATE_EMAIL)) {
    die("올바른 이메일 형식이 아닙니다.");
}

// ✅ 일회용 이메일 도메인 필터링
defined('DISPOSABLE_PATH') || define('DISPOSABLE_PATH', __DIR__.'/data/disposable_domains.txt');
$disposable_domains = file(DISPOSABLE_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$email_parts = explode('@', $mb_email);
$email_domain = strtolower(end($email_parts));
if (in_array($email_domain, $disposable_domains)) {
    die("일회용 이메일은 사용하실 수 없습니다.");
}

// ✅ 기존 인증 안된 이메일 삭제
sql_query("DELETE FROM g5_member WHERE mb_email = '$mb_email' AND mb_email_certified = 'N'");

// ✅ 이메일 중복 가입 방지
$dup = sql_fetch("SELECT COUNT(*) as cnt FROM g5_member WHERE mb_email = '$mb_email' AND mb_email_certified = 'Y'");
if ((int)$dup['cnt'] > 0) {
    die("이미 가입된 이메일입니다.");
}

// ✅ 비밀번호 암호화
$mb_password_enc = get_encrypt_string($mb_password);

// ✅ 이메일 인증용 토큰 생성
$email_token = bin2hex(random_bytes(32));

// ✅ DB 저장
sql_query("INSERT INTO g5_member (
    mb_id, mb_password, mb_email, mb_homepage,
    mb_level, mb_datetime,
    mb_streamer_approved, mb_email_certified,
    mb_email_token, mb_email_token_created
) VALUES (
    '$mb_id', '$mb_password_enc', '$mb_email', '$mb_homepage',
    2, '$now',
    'N', 'N',
    '$email_token', '$now'
)");

// ✅ 이메일 발송
$verify_link = "https://yourdomain.com/verify.php?token={$email_token}";
$subject = "[스트리머 커뮤니티] 이메일 인증을 완료해주세요";
$content = "아래 링크를 클릭하여 이메일 인증을 완료해주세요. (유효시간: 1시간)\n\n{$verify_link}";

// 메일 발송 설정 예시
$headers = "From: noreply@yourdomain.com\r\n" .
           "Content-Type: text/plain; charset=UTF-8";

if (mail($mb_email, $subject, $content, $headers)) {
    echo "가입 신청이 완료되었습니다. 이메일을 확인해주세요.";
} else {
    echo "가입은 완료되었지만, 이메일 발송에 실패했습니다. 관리자에게 문의해주세요.";
}

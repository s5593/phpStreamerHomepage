<?php
//register_form_update.php — 전체 보안 통합 회원가입 처리 코드
include_once('./_common.php');

// 에러 로그 디렉토리 경로
define('ERROR_LOG_PATH', __DIR__ . '/../logs/error.log');
// 에러 핸들링 함수 정의
function custom_error_exit($message, $type = 'general') {
    $log_dir = __DIR__ . '/../logs';
    $log_file = $log_dir . '/error.log';
    $max_size = 1024 * 1024; // 1MB
    $keep_days = 14;

    // 로그 디렉토리 생성 (없으면)
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true);
    }

    // 1. 로그 로테이션 (1MB 이상이면 백업)
    if (file_exists($log_file) && filesize($log_file) > $max_size) {
        $backup_name = $log_file . '.' . date('Ymd_His');
        rename($log_file, $backup_name);
    }

    // 2. 로그 기록
    error_log("[" . date("Y-m-d H:i:s") . "] $message\n", 3, $log_file);

    // 3. 14일 이상된 백업 로그 삭제
    foreach (glob($log_file . '.*') as $backup) {
        if (filemtime($backup) < strtotime("-{$keep_days} days")) {
            unlink($backup);
        }
    }

    // 4. 사용자에게 에러 안내 페이지로 이동
    header("Location: /gnuboard/phpStreamerHomepage/error/{$type}.php?msg=" . urlencode($message));
    exit;
}


// ✅ 세션 보안 설정 (세션 고정 공격 방지)
if (!isset($_SESSION['session_initialized'])) {
    session_regenerate_id(true);
    $_SESSION['session_initialized'] = true;
}
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    custom_error_exit("비정상적인 접근입니다 (User-Agent 변경)", 'error_page');
}

// ✅ CSRF 토큰 유효성 검사 + 유효시간 5분 제한
if (
    !isset($_SESSION['csrf_token']) ||
    !isset($_SESSION['csrf_token_created']) ||
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    custom_error_exit("잘못된 접근입니다 (CSRF 검증 실패)", 'error_page');
}
if (time() - $_SESSION['csrf_token_created'] > 300) {
    unset($_SESSION['csrf_token']);
    unset($_SESSION['csrf_token_created']);
    custom_error_exit("토큰 유효시간이 초과되었습니다. 다시 시도해주세요.", 'error_page');
}
unset($_SESSION['csrf_token'], $_SESSION['csrf_token_created']);

// ✅ Rate Limit (10초 제한)
if (isset($_SESSION['last_join_attempt']) && time() - $_SESSION['last_join_attempt'] < 10) {
    custom_error_exit("너무 빠르게 가입을 시도하고 있습니다.", 'error_page');
}
$_SESSION['last_join_attempt'] = time();

// ✅ CAPTCHA 검증
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if (!chk_captcha()) {
    custom_error_exit("자동등록방지(CAPTCHA) 입력이 올바르지 않습니다.", 'error_page');
}

// ✅ 입력값 처리
$mb_id       = sql_real_escape_string(trim($_POST['mb_id']));
//$mb_password = sql_real_escape_string(trim($_POST['mb_password']));
// 하단에서 해시 암호화 하므므로 굳이 sql_real_escape_string문 불필요
$mb_password_raw = trim($_POST['mb_password']);
$mb_email    = sql_real_escape_string(trim($_POST['mb_email']));
$mb_streamer_page = sql_real_escape_string(trim($_POST['mb_streamer_page']));
$now         = date('Y-m-d H:i:s');

// ✅ 정규식 유효성 검사
if (!preg_match('/^[가-힣a-zA-Z0-9]{2,12}$/u', $mb_id)) {
    custom_error_exit("아이디는 한글, 영문, 숫자를 포함한 2~12자만 가능합니다. 특수문자는 사용할 수 없습니다.", 'error_page');
}
if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,20}$/', $mb_password_raw)) {
    custom_error_exit("비밀번호는 영문, 숫자, 특수문자 조합 8~20자여야 합니다", 'error_page');
}
if (!filter_var($mb_email, FILTER_VALIDATE_EMAIL)) {
    custom_error_exit("올바른 이메일 형식이 아닙니다", 'error_page');
}

// ✅ 일회용 이메일 도메인 필터링
defined('DISPOSABLE_PATH') || define('DISPOSABLE_PATH', __DIR__.'/data/disposable_domains.txt');

$disposable_domains = [];
if (file_exists(DISPOSABLE_PATH)) {
    $disposable_domains = file(DISPOSABLE_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$email_parts = explode('@', $mb_email);
$email_domain = strtolower(end($email_parts));

if (!empty($disposable_domains) && in_array($email_domain, $disposable_domains)) {
    custom_error_exit("일회용 이메일은 사용하실 수 없습니다", 'error_page');
}

// ✅ 기존 인증 안된 이메일 삭제
sql_query("DELETE FROM g5_member WHERE mb_email = '$mb_email' AND mb_email_certified = 'N'");

// ✅ 이메일 중복 가입 방지
$dup = sql_fetch("SELECT COUNT(*) as cnt FROM g5_member WHERE mb_email = '$mb_email' AND mb_email_certified = 'Y'");
if ((int)$dup['cnt'] > 0) {
    custom_error_exit("이미 가입된 이메일입니다", 'error_page');
}

// ✅ 비밀번호 암호화
//$mb_password_enc = get_encrypt_string($mb_password);
$mb_password_enc = password_hash($mb_password_raw, PASSWORD_DEFAULT);

// PHPMailer 라이브러리 불러오기 (경로 확인!)
require_once('../PHPMailer/src/PHPMailer.php');
require_once('../PHPMailer/src/SMTP.php');
require_once('../PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ 이메일 인증용 토큰 생성
$email_token = bin2hex(random_bytes(32));

// ✅ 이메일 인증 링크
$verify_link = "http://localhost/gnuboard/phpStreamerHomepage/bbs/verify.php?token={$email_token}";

// ✅ 메일 객체 생성
$mail = new PHPMailer(true);

// ✅ DB 저장
$insert_result = sql_query("INSERT INTO g5_member (
    mb_id, mb_password, mb_email, mb_streamer_page,
    mb_level, mb_datetime,
    mb_streamer_approved, mb_email_certified,
    mb_email_token, mb_email_token_created,
    mb_is_leave, mb_is_intercepted
) VALUES (
    '$mb_id', '$mb_password_enc', '$mb_email', '$mb_streamer_page',
    2, '$now',
    'N', 'N',
    '$email_token', '$now',
    0, 0
)");

if (!$insert_result) {
    custom_error_exit("회원가입에 실패했습니다. 관리자에게 문의해주세요", 'error_page');
}

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rlaqjatn5593@gmail.com';         // 너의 Gmail
    $mail->Password = 'fcyoxmielhussdpa';            // 앱 비밀번호!
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('rlaqjatn5593@gmail.com', '스트리머 커뮤니티');  // 보내는 사람
    $mail->addAddress($mb_email);                                   // 받는 사람
    $mail->Subject = '[스트리머 커뮤니티] 이메일 인증을 완료해주세요';
    $mail->Body = "아래 링크를 클릭하여 이메일 인증을 완료해주세요.\n\n{$verify_link}";

    $mail->send();
    echo "가입 신청이 완료되었습니다. 이메일을 확인해주세요.";
} catch (Exception $e) {
    echo "메일 발송에 실패했습니다. 사유: {$mail->ErrorInfo}";
}
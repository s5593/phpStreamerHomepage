<?php
// 파일: /php/auth/register_action.php

include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message("잘못된 접근입니다.","/html/auth/register_form.php");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message("CSRF 토큰이 유용하지 않습니다.","/html/auth/register_form.php");
}

// 입력값 필터링
$mb_id = trim($_POST['mb_id'] ?? '');
$mb_email = trim($_POST['mb_email'] ?? '');
$mb_password = $_POST['mb_password'] ?? '';
$mb_password_confirm = $_POST['mb_password_confirm'] ?? '';
$mb_streamer_url = trim($_POST['mb_streamer_url'] ?? '');

if (!$mb_id || !$mb_email || !$mb_password || !$mb_password_confirm) {
    redirect_with_message("모든 필수 입력값을 입력해주세요.","/html/auth/register_form.php");
}

if ($mb_password !== $mb_password_confirm) {
    redirect_with_message("비밀번호가 일치하지 않습니다.","/html/auth/register_form.php");
}

// 중복 체크
$stmt = $conn->prepare("SELECT COUNT(*) FROM g5_member WHERE mb_id = ? OR mb_email = ?");
$stmt->bind_param("ss", $mb_id, $mb_email);
$stmt->execute();
$stmt->bind_result($exists);
$stmt->fetch();
$stmt->close();

if ($exists > 0) {
    redirect_with_message("이미 사용 중인 아이디 또는 이메일입니다.","/html/auth/register_form.php");
}

// 일회용 이메일 도메인 체크
function is_disposable_email($email) {
    $parts = explode('@', $email);
    $domain = strtolower(trim($parts[1] ?? ''));
    $blacklist_file = __DIR__ . '/../../lib/disposable_domains.txt';
    if (!file_exists($blacklist_file)) return false;
    $blacklist = file($blacklist_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($domain, $blacklist);
}

if (is_disposable_email($mb_email)) {
    redirect_with_message("일회용 이메일 주소는 사용할 수 없습니다.","/html/auth/register_form.php");
}

// 비밀번호 암호화
$hashed_pw = password_hash($mb_password, PASSWORD_DEFAULT);

// 이메일 인증 토큰 생성
$email_token = bin2hex(random_bytes(32));
$token_created = date("Y-m-d H:i:s");

// DB INSERT
$stmt = $conn->prepare("INSERT INTO g5_member (mb_id, mb_password, mb_email, mb_streamer_url, mb_email_token, mb_email_token_created) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $mb_id, $hashed_pw, $mb_email, $mb_streamer_url, $email_token, $token_created);

if (!$stmt->execute()) {
    log_error("회원가입 INSERT 실패: " . $stmt->error);
    redirect_with_message("회원가입 중 오류가 발생했습니다.","/html/auth/register_form.php");
}
$stmt->close();
// 가입 완료 세션 설정 후 사용자 리다이렉트
$_SESSION['register_success'] = true;
header("Location: " . get_base_url() . "/html/auth/register_complete.php");
flush();
ignore_user_abort(true);
// 이메일 전송 비동기 처리
$email_log = __DIR__ . '/../../logs/debug_send_email.log';
$email_token_escaped = escapeshellarg($email_token);
$verify_link = get_base_url() . "/php/auth/verify_email.php?token=$email_token_escaped";
$mb_email_escaped = escapeshellarg($mb_email);
$email_token_safe = addslashes($email_token);
$mb_email_safe = addslashes($mb_email);
$email_title_html = "[스트리머 홈페이지] 이메일 인증을 완료해주세요";
$email_body_html = "
        <div style='font-family: Arial, sans-serif;'>
            <h3 style='color:#333;'>이메일 인증을 완료해주세요</h3>
            <p>안녕하세요, 스트리머 홈페이지입니다.<br>
            아래 버튼을 클릭하면 이메일 인증이 완료됩니다.</p>
            <p style='margin:20px 0;'>
                <a href='$verify_link' style='display:inline-block;padding:10px 20px;background:#222;color:#fff;text-decoration:none;border-radius:5px;'>
                    이메일 인증하기
                </a>
            </p>
        </div>
    ";
$email_AltBody_html = "이메일 인증 링크: $verify_link";
$email_title = '"' . $email_title_html . '"';
$body_escaped = '"' . base64_encode($email_body_html) . '"';
$alt_escaped  = '"' . base64_encode($email_AltBody_html) . '"';
file_put_contents($email_log, "실행 명령어: $shell_cmd\n", FILE_APPEND);

if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
    // Windows: .bat 파일을 비동기로 proc_open으로 실행
    file_put_contents($email_log, "윈도우 처리\n", FILE_APPEND);
    $bat_path = realpath(__DIR__ . '/send_email_windows.bat');
    $cmd = "cmd /c \"$bat_path $mb_email_escaped $email_title $body_escaped $alt_escaped\"";
} else {
    // Linux: .sh 파일 호출
    file_put_contents($email_log, "리눅스 처리\n", FILE_APPEND);
    $sh_path = realpath(__DIR__ . '/send_email_linux.sh');
    $cmd = "sh \"$sh_path\" $mb_email_escaped $email_title $body_escaped $alt_escaped";
}

// 비동기 처리용 proc_open
$descriptor_spec = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

file_put_contents($email_log, date('Y-m-d H:i:s') . "\n실행 명령어: $cmd\n\n", FILE_APPEND);
$process = proc_open($cmd, $descriptor_spec, $pipes);
if (is_resource($process)) {
    proc_close($process);
}

file_put_contents($email_log, date('Y-m-d H:i:s')."실행 시도 완료\n\n", FILE_APPEND);


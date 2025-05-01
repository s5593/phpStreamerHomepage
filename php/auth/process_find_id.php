<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    show_alert_and_back("잘못된 접근입니다.");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    show_alert_and_back("CSRF 토큰이 유효하지 않습니다.");
}

$email = trim($_POST['mb_email'] ?? '');

if ( $email === '') {
    $_SESSION['find_result'] = '아이디와 이메일을 모두 입력해주세요.';
    redirect_to('/html/auth/find_result_id.php');
    exit;
}

// 사용자 조회
$stmt = $conn->prepare("SELECT mb_id FROM g5_member WHERE mb_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $_SESSION['find_result'] = '입력하신 정보와 일치하는 계정을 찾을 수 없습니다.';
    redirect_to('/html/auth/find_result_id.php');
    exit;
}

$stmt->bind_result($mb_id);
$stmt->fetch();
$stmt->close();

// ✅ 이메일 전송 비동기 처리
$log_path = __DIR__ . '/../../logs/debug_send_email.log';
$title = '[별구름] 아이디 찾기 안내';
$body_html = "
<div style='font-family: Arial, sans-serif;'>
  <h3>아이디 찾기 안내</h3>
  <p>요청하신 아이디는 다음과 같습니다:</p>
  <div style='padding:10px;margin-top:10px;font-size:18px;color:#6a0dad;font-weight:bold;'>
    👉 {$mb_id}
  </div>
</div>
";
$body_text = "안녕하세요, {$mb_id}님.\n요청하신 아이디는 다음과 같습니다: {$mb_id}";

// ⛑ base64로 인코딩하여 인자 깨짐 방지
$email_escaped = '"' . $email . '"';
$title_escaped = '"' . $title . '"';
$body_escaped = '"' . base64_encode($body_html) . '"';
$alt_escaped  = '"' . base64_encode($body_text) . '"';

// 명령어 구성
if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
    $bat_path = realpath(__DIR__ . '/send_email_windows.bat');
    $cmd = "cmd /c \"$bat_path $email_escaped $title_escaped $body_escaped $alt_escaped\"";
} else {
    $sh_path = realpath(__DIR__ . '/send_email_linux.sh');
    $cmd = "sh \"$sh_path\" $email_escaped $title_escaped $body_escaped $alt_escaped";
}

// 로그 기록 및 비동기 실행
file_put_contents($log_path, date('Y-m-d H:i:s') . " [아이디찾기 메일 실행] $cmd\n", FILE_APPEND);
$proc = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
if (is_resource($proc)) proc_close($proc);

$_SESSION['find_result'] = '입력하신 이메일로 아이디를 발송했습니다.';
redirect_to('/html/auth/find_result_id.php');
exit;

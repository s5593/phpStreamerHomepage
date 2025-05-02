<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message("잘못된 접근입니다.", '/html/auth/find_pw.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message("CSRF 토큰이 유효하지 않습니다.", '/html/auth/find_pw.php');
}

$id = trim($_POST['mb_id'] ?? '');
$email = trim($_POST['mb_email'] ?? '');

if ($id === '' || $email === '') {
    redirect_with_message("아이디와 이메일을 모두 입력해주세요.", '/html/auth/find_pw.php');
}

// 사용자 조회
$stmt = $conn->prepare("SELECT mb_no FROM g5_member WHERE mb_id = ? AND mb_email = ?");
$stmt->bind_param("ss", $id, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    redirect_with_message("입력하신 정보와 일치하는 계정을 찾을 수 없습니다.", '/html/auth/find_pw.php');
}

$stmt->bind_result($mb_no);
$stmt->fetch();
$stmt->close();

// ✅ 토큰 생성 및 저장
$token = bin2hex(random_bytes(32));
$created = date('Y-m-d H:i:s');

$update = $conn->prepare("UPDATE g5_member SET mb_pw_token = ?, mb_pw_token_created = ? WHERE mb_no = ?");
$update->bind_param("ssi", $token, $created, $mb_no);
$update->execute();
$update->close();

// ✅ 이메일 발송
$verify_link = get_base_url() . "/html/auth/reset_password.php?token=$token";
$title = '[별구름] 비밀번호 재설정 링크 안내';
$body_html = "
<div style='font-family: Arial, sans-serif;'>
  <h3>비밀번호 재설정 안내</h3>
  <p>안녕하세요, {$id}님.</p>
  <p>아래 버튼을 클릭하면 비밀번호를 재설정할 수 있습니다:</p>
  <p style='margin:20px 0;'>
    <a href='$verify_link' style='display:inline-block;padding:10px 20px;background:#222;color:#fff;text-decoration:none;border-radius:5px;'>
      비밀번호 재설정하기
    </a>
  </p>
</div>
";
$alt_body = "비밀번호 재설정 링크: $verify_link";

// 인자 인코딩
$email_escaped = '"' . $email . '"';
$title_escaped = '"' . $title . '"';
$body_escaped  = '"' . base64_encode($body_html) . '"';
$alt_escaped   = '"' . base64_encode($alt_body) . '"';

$log_path = __DIR__ . '/../../logs/debug_send_email.log';
if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
    $bat_path = realpath(__DIR__ . '/send_email_windows.bat');
    $cmd = "cmd /c \"$bat_path $email_escaped $title_escaped $body_escaped $alt_escaped\"";
} else {
    $sh_path = realpath(__DIR__ . '/send_email_linux.sh');
    $cmd = "sh \"$sh_path\" $email_escaped $title_escaped $body_escaped $alt_escaped";
}

file_put_contents($log_path, date('Y-m-d H:i:s') . " [비번찾기 메일 실행] $cmd\n", FILE_APPEND);
$proc = proc_open($cmd, [0 => ['pipe','r'], 1 => ['pipe','w'], 2 => ['pipe','w']], $pipes);
if (is_resource($proc)) proc_close($proc);

redirect_with_message("입력하신 이메일로 비밀번호 재설정 링크를 전송했습니다.", '/html/auth/login_form.php');
exit;

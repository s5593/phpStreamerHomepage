<?php
include_once(__DIR__ . '/../../lib/common.php');
require_once __DIR__ . '/../../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../../lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 에러 페이지로 리디렉션
function redirect_error($msg) {
    $_SESSION['error_message'] = $msg;
    header("Location: /error/error.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_error("잘못된 접근입니다.");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_error("CSRF 토큰이 유효하지 않습니다.");
}

// 입력값 필터링
$mb_id = trim($_POST['mb_id'] ?? '');
$mb_email = trim($_POST['mb_email'] ?? '');
$mb_password = $_POST['mb_password'] ?? '';
$mb_password_confirm = $_POST['mb_password_confirm'] ?? '';
$mb_streamer_url = trim($_POST['mb_streamer_url'] ?? '');

if (!$mb_id || !$mb_email || !$mb_password || !$mb_password_confirm) {
    redirect_error("모든 필수 입력값을 입력해주세요.");
}

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

// DB INSERT
$stmt = $conn->prepare("
    INSERT INTO g5_member 
    (mb_id, mb_password, mb_email, mb_streamer_url, mb_email_token, mb_email_token_created) 
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssss", $mb_id, $hashed_pw, $mb_email, $mb_streamer_url, $email_token, $token_created);

if (!$stmt->execute()) {
    log_error("회원가입 INSERT 실패: " . $stmt->error);
    redirect_error("회원가입 중 오류가 발생했습니다.");
}
$stmt->close();

// ✅ 이메일 전송
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';       // 📝 SMTP 서버 주소
    $mail->SMTPAuth = true;
    $mail->Username = 'rlaqjatn5593@gmail.com';     // 📝 SMTP 사용자 이메일
    $mail->Password = 'bzvrrwgptkixlbin';      // 📝 SMTP 비밀번호
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('rlaqjatn5593@gmail.com', '스트리머 홈페이지');
    $mail->addAddress($mb_email);

    $mail->isHTML(true);
    $mail->Subject = '[스트리머 홈페이지] 이메일 인증을 완료해주세요';

    $verify_link = $protocol . $host . "/php/auth/verify_email.php?token=$email_token";
    $mail->Body = "
        <h3>이메일 인증을 완료해주세요</h3>
        <p>아래 링크를 클릭하면 인증이 완료됩니다.</p>
        <a href='$verify_link'>$verify_link</a>
    ";

    $mail->send();
} catch (Exception $e) {
    log_error("이메일 전송 실패: {$mail->ErrorInfo}");
    // 이메일 전송 실패는 실패로 간주하지 않음
}

// ✅ 가입 완료 세션 설정 후 GET 방식 이동
$_SESSION['register_success'] = true;
header("Location: /html/auth/register_complete.php");
exit;

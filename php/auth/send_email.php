<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/SMTP.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;

if (PHP_SAPI !== 'cli') {
    echo "CLI에서만 실행됩니다.\n";
    exit;
}
log_error("📥 디코딩된 본문: [" . substr($argv[3], 0, 100) . "]");
log_error("📥 디코딩된 본문: [" . substr($argv[4], 0, 100) . "]");
$email = $argv[1] ?? '';
$title = $argv[2] ?? '';
$body = base64_decode($argv[3] ?? '');
$altBody = base64_decode($argv[4] ?? '');
log_error("📥 디코딩된 본문: [" . substr($body, 0, 100) . "]");
log_error("📥 디코딩된 본문: [" . substr($altBody, 0, 100) . "]");
if (!$email) {
    log_error("[비동기] 이메일 누락");
    exit;
}

log_error("이메일 전송 시작 → $email");

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rlaqjatn5593@gmail.com';
    $mail->Password = 'bzvrrwgptkixlbin'; // 앱 비밀번호
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('rlaqjatn5593@gmail.com', '스트리머 홈페이지');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;
    $mail->AltBody = $altBody;

    $mail->send();
    log_error("[비동기] 이메일 전송 성공: $email");
} catch (Exception $e) {
    log_error("[비동기] 이메일 전송 실패: " . $mail->ErrorInfo);
}

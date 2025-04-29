<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/SMTP.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;

$token = $argv[1] ?? '';
$email = $argv[2] ?? '';
// 유효성 검사
if (!$token || !$email) {
    log_error("[비동기] 토큰 또는 이메일 누락");
    exit;
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rlaqjatn5593@gmail.com';
    $mail->Password = 'bzvrrwgptkixlbin'; // 앱 비밀번호 사용
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('rlaqjatn5593@gmail.com', '스트리머 홈페이지');
    $mail->addAddress($email);

    $verify_link = get_base_url() . "/php/auth/verify_email.php?token=$token";

    $mail->isHTML(true);
    $mail->Subject = '[스트리머 홈페이지] 이메일 인증을 완료해주세요';
    $mail->Body = "
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
    $mail->AltBody = "이메일 인증 링크: $verify_link";

    $mail->send();
    log_error("[비동기] 이메일 전송 성공");
} catch (Exception $e) {
    log_error("[비동기] 이메일 전송 실패: {$mail->ErrorInfo}");
}


<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/SMTP.php');
include_once(__DIR__ . '/../../lib/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// CLI에서만 실행 허용
if (PHP_SAPI !== 'cli') {
    echo "이 스크립트는 CLI 환경에서만 실행 가능합니다.\n";
    exit;
}

// 인자 처리
$email     = $argv[1] ?? '';
$title     = $argv[2] ?? '';
$body_file = $argv[3] ?? '';
$alt_file  = $argv[4] ?? '';

$body = base64_decode(file_get_contents($body_file));
$altBody = base64_decode(file_get_contents($alt_file));

log_error("이메일 전송 시작 → $email");
file_put_contents('/home1/sss5593/public_html/logs/email_body.html', $body); // ✅ 전체 HTML 저장
file_put_contents('/home1/sss5593/public_html/logs/email_altBody.html', $altBody); // ✅ 전체 HTML 저장
log_error("📥 디코딩된 본문 저장 완료: email_body.html");
$mail = new PHPMailer(true);

try {
    // SMTP 설정
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rlaqjatn5593@gmail.com';       // Gmail 주소
    $mail->Password = 'tuwzxhodmzuryzwk';             // Gmail 앱 비밀번호
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // ⚠️ SSL 인증서 검증 비활성화 (공유형 한정)
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ];

    // 메일 인코딩 설정
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // 메일 정보 설정
    $mail->setFrom('rlaqjatn5593@gmail.com', '스트리머 홈페이지');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body    = $body;
    $mail->AltBody = $altBody;

    // 메일 전송
    $mail->send();
    log_error("[비동기] 이메일 전송 성공: $email");

} catch (Exception $e) {
    log_error("[비동기] 이메일 전송 실패: " . $mail->ErrorInfo);
}

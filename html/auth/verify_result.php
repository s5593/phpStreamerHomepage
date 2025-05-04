<?php
session_start();

$message = $_SESSION['verify_result'] ?? null;
unset($_SESSION['verify_result']);

if (!$message) {
    // 어떤 메시지도 전달되지 않은 경우
    $message = '관리자에게 문의해주세요.';
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>이메일 인증 결과</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <div class="auth-verify">
    <h2 class="auth-verify__title">📬 이메일 인증 결과</h2>
    <p class="auth-verify__message"><?= htmlspecialchars($message) ?></p>

    <a href="<?= get_base_url() ?>/html/auth/login_form.php" class="auth-verify__btn">로그인하러 가기</a>
  </div>

</body>
</html>
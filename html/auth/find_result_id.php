<?php
include_once(__DIR__ . '/../../lib/common.php');

$result = $_SESSION['find_result'] ?? '결과 메시지가 없습니다.';
unset($_SESSION['find_result']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>아이디 찾기 결과</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="auth">
      <h2 class="auth__title">📬 이메일 전송 결과</h2>
      <p class="auth__info"><?= nl2br(htmlspecialchars($result)) ?></p>

      <div class="button-group" style="margin-top: 30px;">
        <a href="/html/auth/login_form.php" class="button button--primary">로그인</a>
        <a href="/html/auth/find_password.php" class="button button--secondary">비밀번호 찾기</a>
      </div>
    </div>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

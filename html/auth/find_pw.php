<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>비밀번호 찾기</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>

  <div class="auth">
    <h2 class="auth__title">🔑 비밀번호 찾기</h2>

    <form action="/php/auth/process_find_pw.php" method="post" class="auth__form">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

      <input type="text" id="mb_id" name="mb_id" class="input" required placeholder="아이디 입력">
      <input type="email" id="mb_email" name="mb_email" class="input" required placeholder="이메일 입력">

      <button type="submit" class="button button--primary">임시 비밀번호 링크 받기</button>
    </form>

    <div class="auth__info">
      <a href="/html/auth/login_form.php" class="auth__link">로그인</a>
      <span class="auth__separator">|</span>
      <a href="/html/auth/find_id.php" class="auth__link">아이디 찾기</a>
    </div>
  </div>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>

</html>

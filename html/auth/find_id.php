<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>아이디 찾기</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="auth-find">
    <h2 class="auth-find__title">🔍 아이디 찾기</h2>

    <form action="/php/auth/process_find_id.php" method="post" class="auth-find__form">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

      <label for="mb_email" class="auth-find__label">이메일</label>
      <input type="email" id="mb_email" name="mb_email" required class="auth-find__input">

      <button type="submit" class="auth-find__submit-btn">아이디 찾기</button>
    </form>

    <div class="auth-find__action-buttons">
      <a href="/html/auth/login_form.php" class="auth-find__btn">로그인</a>
      <a href="/html/auth/find_pw.php" class="auth-find__btn">비밀번호 찾기</a>
    </div>
  </div>
</body>
</html>

<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/auth/style.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>
  <div class="auth-login">
    <div class="auth-login__container">
      <h2 class="auth-login__title">로그인</h2>

      <form method="POST" action="/php/auth/login_check.php" class="auth-login__form">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <label for="mb_id" class="auth-login__label">아이디</label>
        <input type="text" name="mb_id" id="mb_id" required class="auth-login__input">

        <label for="mb_password" class="auth-login__label">비밀번호</label>
        <input type="password" name="mb_password" id="mb_password" required class="auth-login__input">

        <input type="submit" value="로그인" class="auth-login__submit-btn">
      </form>

      <div class="auth-login__links">
        <a href="<?= get_base_url() ?>/html/auth/find_id.php" class="auth-login__link">아이디 찾기</a>
        <span class="auth-login__separator">|</span>
        <a href="<?= get_base_url() ?>/html/auth/find_password.php" class="auth-login__link">비밀번호 찾기</a>
        <span class="auth-login__separator">|</span>
        <a href="<?= get_base_url() ?>/html/auth/agree.php" class="auth-login__link">회원가입</a>
      </div>
    </div>
  </div>

</body>
</html>

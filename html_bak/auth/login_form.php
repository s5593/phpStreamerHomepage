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
    <link rel="stylesheet" href="../../css/auth/login.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>
<div class="login-page-wrapper">
  <div class="login-container">
    <h2>로그인</h2>
    <form method="POST" action="/php/auth/login_check.php">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
      <label for="mb_id">아이디</label>
      <input type="text" name="mb_id" id="mb_id" required>
      <label for="mb_password">비밀번호</label>
      <input type="password" name="mb_password" id="mb_password" required>
      <input type="submit" value="로그인" class="btn-login">
    </form>

    <div class="login-links">
    <a href="<?= get_base_url() ?>/html/auth/find_id.php">아이디 찾기</a>
      <span>|</span>
      <a href="<?= get_base_url() ?>/html/auth/find_password.php">비밀번호 찾기</a>
      <span>|</span>
      <a href="<?= get_base_url() ?>/html/auth/agree.php">회원가입</a>
    </div>
  </div>
</div>


</body>
</html>

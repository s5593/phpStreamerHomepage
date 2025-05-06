<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
unset($_SESSION['agreed_to_terms']);
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
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="auth">
      <h2 class="auth__title">로그인</h2>

      <form method="POST" action="/php/auth/login_check.php" class="auth__form">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <input type="text" name="mb_id" id="mb_id" required placeholder="아이디" />
        <input type="password" name="mb_password" id="mb_password" required placeholder="비밀번호" />

        <button type="submit">로그인</button>
      </form>

      <div class="auth__info">
        <a href="<?= get_base_url() ?>/html/auth/find_id.php" class="auth__link">아이디 찾기</a>
        <span class="auth__separator">|</span>
        <a href="<?= get_base_url() ?>/html/auth/find_pw.php" class="auth__link">비밀번호 찾기</a>
        <span class="auth__separator">|</span>
        <a href="<?= get_base_url() ?>/html/auth/agree.php" class="auth__link">회원가입</a>
      </div>
    </div>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>


</html>

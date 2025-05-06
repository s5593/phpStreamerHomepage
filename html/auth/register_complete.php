<?php
include_once(__DIR__ . '/../../lib/common.php');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원가입 완료</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="auth-complete">
      <h2 class="auth-complete__title">🎉 회원가입이 완료되었습니다</h2>
      <p class="auth-complete__text">입력하신 이메일 주소로 인증 메일을 발송했습니다.</p>
      <p class="auth-complete__text"><strong>이메일 인증을 완료해야 로그인할 수 있습니다.</strong></p>
      <p class="auth-complete__text">메일이 오지 않았다면 스팸함을 확인해주세요.</p>

      <a href="<?= get_base_url() ?>/html/auth/login_form.php" class="button button--primary">로그인하러 가기</a>
    </div>
  </div>

  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>

</html>

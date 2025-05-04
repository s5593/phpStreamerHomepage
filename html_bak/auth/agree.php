<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();

// 이미 동의한 경우 바로 회원가입 페이지로 이동
if (isset($_SESSION['agreed_to_terms']) && $_SESSION['agreed_to_terms'] === true) {
  redirect_to('/html/auth/register_form.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>약관 동의</title>
  <link rel="stylesheet" href="../../css/auth/agree.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="agree-container">
  <h2>약관 및 개인정보 수집 동의</h2>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div class="error-box"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
    <?php unset($_SESSION['error_message']); ?>
  <?php endif; ?>

  <form action="../../php/auth/check_agreement.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

    <label class="check-line">
      <input type="checkbox" id="agree_all">
      전체 약관에 동의합니다
    </label>

    <hr>

    <h4>이용약관</h4>
    <textarea readonly>여기에 이용약관 내용을 작성하세요.</textarea>
    <label class="check-line">
      <input type="checkbox" name="agree_terms" class="agree-check" required>
      이용약관에 동의합니다
    </label>

    <h4>개인정보 수집 및 이용 동의</h4>
    <textarea readonly>여기에 개인정보 처리방침 내용을 작성하세요.</textarea>
    <label class="check-line">
      <input type="checkbox" name="agree_privacy" class="agree-check" required>
      개인정보 수집 및 이용에 동의합니다
    </label>

    <button type="submit">동의하고 회원가입 진행</button>
  </form>
</div>

<script defer src="../../js/auth/agree.js"></script>
</body>
</html>

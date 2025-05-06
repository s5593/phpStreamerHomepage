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
  <link rel="stylesheet" href="../../css/auth/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="auth">
      <h2 class="auth__title">약관 및 개인정보 수집 동의</h2>

      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="auth__error"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

      <form action="../../php/auth/check_agreement.php" method="POST" class="auth__form">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <!-- 전체 동의 -->
        <div class="form__group">
          <label class="form__checkbox">
            <input type="checkbox" id="agree_all">
            전체 약관에 동의합니다
          </label>
        </div>

        <hr class="form__divider">

        <!-- 이용약관 -->
        <div class="form__group">
          <h4 class="form__section-title">이용약관</h4>
          <textarea readonly class="auth__textarea">여기에 이용약관 내용을 작성하세요.</textarea>
          <label class="form__checkbox">
            <input type="checkbox" name="agree_terms" required>
            이용약관에 동의합니다
          </label>
        </div>

        <!-- 개인정보 수집 -->
        <div class="form__group">
          <h4 class="form__section-title">개인정보 수집 및 이용 동의</h4>
          <textarea readonly class="auth__textarea">여기에 개인정보 처리방침 내용을 작성하세요.</textarea>
          <label class="form__checkbox">
            <input type="checkbox" name="agree_privacy" required>
            개인정보 수집 및 이용에 동의합니다
          </label>
        </div>

        <!-- 제출 -->
        <div class="button-group">
          <button type="submit" class="button button--primary">동의하고 회원가입 진행</button>
        </div>
      </form>
    </div>
  </div>
  <script defer src="../../js/auth/agree.js"></script>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>

</html>

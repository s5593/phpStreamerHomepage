<?php
include_once(__DIR__ . '/../../lib/common.php');

$token = $_GET['token'] ?? '';

if (!$token) {
    show_alert_and_back("잘못된 접근입니다.");
}

// DB 연결
include_once(__DIR__ . '/../../lib/db.php');

// 토큰 조회
$stmt = $conn->prepare("SELECT mb_id, mb_pw_token_created FROM g5_member WHERE mb_pw_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    show_alert_and_back("유효하지 않은 토큰입니다.");
}

$stmt->bind_result($mb_id, $token_created);
$stmt->fetch();
$stmt->close();

// 토큰 유효시간 확인 (1시간)
if ((time() - strtotime($token_created)) > 3600) {
    show_alert_and_back("비밀번호 재설정 유효시간이 만료되었습니다. 다시 시도해주세요.");
}

// 토큰 통과 → 세션에 토큰 저장 (POST 처리 시 검증용)
$_SESSION['reset_pw_token'] = $token;
$_SESSION['reset_mb_id'] = $mb_id;

// ✅ HTML 출력
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>비밀번호 재설정</title>
  <link rel="stylesheet" href="/css/auth/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="auth">
      <h2 class="auth__title">🔒 비밀번호 재설정</h2>

      <form action="/php/auth/reset_password_action.php" method="post" class="auth__form">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

        <!-- 비밀번호 -->
        <div class="form__group">
          <label for="mb_password" class="form__label">비밀번호</label>
          <input type="password" name="new_pw" id="mb_password" class="input" required autocomplete="off">
          <ul id="pw_rules" class="form__pw-checklist">
            <li id="pw_length">8자 이상</li>
            <li id="pw_max">30자 이하</li>
            <li id="pw_letter">영문 포함</li>
            <li id="pw_number">숫자 포함</li>
            <li id="pw_special">특수문자 포함 (!@#$%^&*()_-)</li>
            <li id="pw_allowed">허용된 문자만 사용</li>
          </ul>
        </div>

        <!-- 비밀번호 확인 -->
        <div class="form__group">
          <label for="mb_password_confirm" class="form__label">비밀번호 확인</label>
          <input type="password" name="new_pw_confirm" id="mb_password_confirm" class="input" required autocomplete="off">
          <small id="pw_match_message" class="form__help"></small>
        </div>
        
        <div class="button-group">
          <button type="submit" class="button button--primary">비밀번호 변경</button>
        </div>
      </form>
    </div>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>

</html>

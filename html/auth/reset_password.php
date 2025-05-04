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
  <div class="auth-reset">
    <h2 class="auth-reset__title">🔒 비밀번호 재설정</h2>

    <form action="/php/auth/reset_password_action.php" method="post" class="auth-reset__form">
      <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

      <label for="new_pw" class="auth-reset__label">새 비밀번호</label>
      <input type="password" id="new_pw" name="new_pw" required class="auth-reset__input">

      <label for="new_pw_confirm" class="auth-reset__label">새 비밀번호 확인</label>
      <input type="password" id="new_pw_confirm" name="new_pw_confirm" required class="auth-reset__input">

      <button type="submit" class="auth-reset__submit-btn">비밀번호 변경</button>
    </form>
  </div>
</body>
</html>

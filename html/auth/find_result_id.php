<?php
include_once(__DIR__ . '/../../lib/common.php');

$result = $_SESSION['find_result'] ?? '결과 메시지가 없습니다.';
unset($_SESSION['find_result']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>아이디 찾기 결과</title>
  <link rel="stylesheet" href="/css/auth/find_id.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="form-card">
    <h2>📬 이메일 전송 결과</h2>
    <p><?= nl2br(htmlspecialchars($result)) ?></p>

    <div class="action-buttons">
      <a href="/html/auth/login_form.php" class="auth-btn">로그인</a>
      <a href="/html/auth/find_pw.php" class="auth-btn">비밀번호 찾기</a>
    </div>
  </div>
</body>
</html>

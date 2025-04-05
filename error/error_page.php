<?php
session_start();
$message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '알 수 없는 오류가 발생했습니다.';
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>오류 발생</title>
</head>
<body>
  <h2>❗ 오류가 발생했습니다</h2>
  <p><?php echo htmlspecialchars($message); ?></p>
  <a href="javascript:history.back();">← 이전 페이지로 돌아가기</a>
</body>
</html>

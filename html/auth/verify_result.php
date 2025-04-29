<?php
session_start();

$message = $_SESSION['verify_result'] ?? null;
unset($_SESSION['verify_result']);

if (!$message) {
    // 어떤 메시지도 전달되지 않은 경우
    $message = '잘못된 접근입니다.';
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>결과 메시지</title>
</head>
<body>
    <h2>결과 메시지</h2>
    <p><?= htmlspecialchars($message) ?></p>
</body>
</html>
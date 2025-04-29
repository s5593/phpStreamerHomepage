<?php
// 세션 및 공통처리
include_once(__DIR__ . '/../lib/common.php');

// 에러 메시지를 세션 또는 GET으로 전달 가능
$error_message = $_SESSION['error_message'] ?? ($_GET['msg'] ?? "문제가 발생했습니다.");
unset($_SESSION['error_message']); // 재사용 방지
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>에러 발생</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fefefe; text-align: center; padding: 60px; }
        h1 { font-size: 2em; color: #cc0000; }
        p { margin-top: 20px; font-size: 1.1em; }
        a { color: #333; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🚫 에러가 발생했습니다</h1>
    <p><?= htmlspecialchars($error_message) ?></p>
    <p><a href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/../index.php">홈으로 돌아가기</a></p>
</body>
</html>

<?php
include_once(__DIR__ . '/../../lib/common.php');

$msg = $_SESSION['verify_result'] ?? '잘못된 접근입니다.';
unset($_SESSION['verify_result']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>이메일 인증 결과</title>
    <style>
        body { font-family: Arial; padding: 40px; text-align: center; }
        .box { display: inline-block; padding: 30px; border: 1px solid #ccc; border-radius: 10px; }
        a {
            display: inline-block;
            margin-top: 20px;
            color: white;
            background-color: #222;
            padding: 10px 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2><?= htmlspecialchars($msg) ?></h2>
        <a href="/html/auth/login_form.php">로그인하러 가기</a>
    </div>
</body>
</html>

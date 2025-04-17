<?php
include_once(__DIR__ . '/../../lib/common.php');

if (!($_SESSION['register_success'] ?? false)) {
    redirect_error("잘못된 접근입니다.");
}
unset($_SESSION['register_success']); // 일회용 처리
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입 완료</title>
    <style>
        body { font-family: Arial; padding: 40px; text-align: center; }
        .box {
            display: inline-block;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
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
    <h2>🎉 회원가입이 완료되었습니다!</h2>
    <p>이메일 인증 후 로그인이 가능합니다.</p>
    <a href="/html/auth/login_form.php">로그인하러 가기</a>
</div>

</body>
</html>

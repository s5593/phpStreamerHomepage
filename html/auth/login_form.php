<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type=text], input[type=password] {
            width: 100%; padding: 8px;
        }
        input[type=submit] {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: #444;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>로그인</h2>

<form action="../../php/auth/login_check.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

    <label for="mb_id">아이디</label>
    <input type="text" name="mb_id" required>

    <label for="mb_password">비밀번호</label>
    <input type="password" name="mb_password" required>

    <input type="submit" value="로그인">
</form>
<a href="<?= get_base_url() ?>/html/auth/agree.php" class="btn-go-login">회원가입</a>
</body>
</html>

<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 8px;
        }
        input[type=submit] {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: #222;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>회원가입</h2>

<form action="/php/auth/register_action.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

    <label for="mb_id">아이디</label>
    <input type="text" name="mb_id" required>

    <label for="mb_email">이메일</label>
    <input type="email" name="mb_email" required>

    <label for="mb_password">비밀번호</label>
    <input type="password" name="mb_password" required>

    <label for="mb_password_confirm">비밀번호 확인</label>
    <input type="password" name="mb_password_confirm" required>

    <label for="mb_streamer_url">스트리머/채널 URL</label>
    <input type="text" name="mb_streamer_url">

    <input type="submit" value="회원가입">
</form>

</body>
</html>

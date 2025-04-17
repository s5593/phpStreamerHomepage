<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <link rel="stylesheet" href="../../css/auth/register_form.css">
</head>
<body>

<h2>회원가입</h2>

<form action="../../php/auth/register_action.php" method="POST" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="mb_email" id="mb_email"> <!-- 조합된 이메일을 담을 hidden -->

    <label for="mb_id">아이디</label>
    <input type="text" name="mb_id" id="mb_id" required>

    <label for="email_group">이메일</label>
    <div class="email-group">
        <input type="text" id="mb_email_id" placeholder="이메일 아이디" required>
        <span>@</span>
        <select id="mb_email_domain_select" required>
            <option value="">도메인 선택</option>
            <option value="naver.com">naver.com</option>
            <option value="gmail.com">gmail.com</option>
            <option value="daum.net">daum.net</option>
            <option value="direct">직접입력</option>
        </select>
        <input type="text" id="mb_email_domain_input" placeholder="직접입력" style="display:none;">
    </div>

    <label for="mb_password">비밀번호</label>
    <input type="password" name="mb_password" id="mb_password" required>

    <label for="mb_password_confirm">비밀번호 확인</label>
    <input type="password" name="mb_password_confirm" id="mb_password_confirm" required>

    <label for="mb_streamer_url">스트리머/채널 URL</label>
    <input type="text" name="mb_streamer_url" id="mb_streamer_url">

    <input type="submit" value="회원가입">
</form>
<script defer src="../../js/auth/register_form.js"></script>
</body>
</html>

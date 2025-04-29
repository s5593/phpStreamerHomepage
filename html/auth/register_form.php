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
    <!-- autofill 방지용 가짜 input (시작하자마자 무시됨) -->
    <input type="text" style="display:none">
    <input type="password" style="display:none">

    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="mb_email" id="mb_email"> <!-- 조합된 이메일을 담을 hidden -->

    <label for="mb_id">아이디</label>
    <div class="id-check-group">
        <input type="text" name="mb_id" id="mb_id" autocomplete="off">
        <button type="button" id="btn_check_id">중복 확인</button>
    </div>
    <small id="id_check_message" class="input-help"></small>
    <small id="id_help" class="input-help">4자 이상, 영어 또는 숫자만 입력하세요.</small>

    <label for="mb_password">비밀번호</label>
    <input type="password" name="mb_password" id="mb_password" autocomplete="off">
    <ul id="pw_rules" class="pw-checklist">
        <li id="pw_length">8자 이상</li>
        <li id="pw_letter">영어 포함</li>
        <li id="pw_number">숫자 포함</li>
        <li id="pw_special">특수문자 포함 (!@#$%^&*()_-)</li>
        <li id="pw_invalid" class="invalid" style="display:none; color:red;">허용되지 않은 문자가 포함되어 있습니다</li>
    </ul>


    <label for="mb_password_confirm">비밀번호 확인</label>
    <input type="password" name="mb_password_confirm" id="mb_password_confirm" autocomplete="off">
    <small id="pw_match_message" class="input-help"></small>

    <label for="email_group">이메일</label>
    <div class="email-group">
        <input type="text" id="mb_email_id" placeholder="이메일 아이디" autocomplete="off">
        <span>@</span>
        <select id="mb_email_domain_select" autocomplete="off">
            <option value="">도메인 선택</option>
            <option value="naver.com">naver.com</option>
            <option value="gmail.com">gmail.com</option>
            <option value="daum.net">daum.net</option>
            <option value="direct">직접입력</option>
        </select>
        <input type="text" id="mb_email_domain_input" placeholder="직접입력" style="display:none;" autocomplete="off">
    </div>

    <label for="mb_streamer_url">스트리머/채널 URL</label>
    <input type="text" name="mb_streamer_url" id="mb_streamer_url">

    <input type="submit" value="회원가입">
</form>
<script defer src="../../js/auth/register_form.js"></script>
</body>
</html>

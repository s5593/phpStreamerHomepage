<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();

if (!isset($_SESSION['agreed_to_terms']) || $_SESSION['agreed_to_terms'] !== true) {
    $_SESSION['error_message'] = '약관 동의 후 회원가입을 진행해주세요.';
    redirect_to('/html/auth/agree.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
    <link rel="stylesheet" href="../../css/auth/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <h2 class="auth-register__title">회원가입</h2>

    <form action="../../php/auth/register_action.php" method="POST" autocomplete="off" class="auth-register__form">
    <!-- autofill 방지용 가짜 input -->
    <input type="text" style="display:none">
    <input type="password" style="display:none">

    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="mb_email" id="mb_email">

    <!-- 아이디 -->
    <label for="mb_id" class="auth-register__label">아이디</label>
    <div class="auth-register__input-group">
        <input type="text" name="mb_id" id="mb_id" class="auth-register__input" autocomplete="off">
        <button type="button" id="btn_check_id" class="auth-register__button">중복 확인</button>
    </div>
    <small id="id_check_message" class="auth-register__message"></small>
    <small id="id_help" class="auth-register__message">4자 이상, 영어 또는 숫자만 입력하세요.</small>

    <!-- 비밀번호 -->
    <label for="mb_password" class="auth-register__label">비밀번호</label>
    <input type="password" name="mb_password" id="mb_password" class="auth-register__input" autocomplete="off">

    <ul id="pw_rules" class="auth-register__pw-checklist">
        <li id="pw_length">8자 이상</li>
        <li id="pw_max">30자 이하</li>
        <li id="pw_letter">영문 포함</li>
        <li id="pw_number">숫자 포함</li>
        <li id="pw_special">특수문자 포함 (!@#$%^&*()_-)</li>
        <li id="pw_allowed">허용된 문자만 사용</li>
    </ul>

    <!-- 비밀번호 확인 -->
    <label for="mb_password_confirm" class="auth-register__label">비밀번호 확인</label>
    <input type="password" name="mb_password_confirm" id="mb_password_confirm" class="auth-register__input" autocomplete="off">
    <small id="pw_match_message" class="auth-register__message"></small>

    <!-- 이메일 -->
    <label for="email_group" class="auth-register__label">이메일</label>
    <div class="auth-register__email-group">
        <input type="text" id="mb_email_id" placeholder="이메일 아이디" class="auth-register__input" autocomplete="off">
        <span class="auth-register__email-separator">@</span>
        <select id="mb_email_domain_select" class="auth-register__select" autocomplete="off">
        <option value="">도메인 선택</option>
        <option value="naver.com">naver.com</option>
        <option value="gmail.com">gmail.com</option>
        <option value="daum.net">daum.net</option>
        <option value="direct">직접입력</option>
        </select>
        <input type="text" id="mb_email_domain_input" placeholder="직접입력" style="display:none;" class="auth-register__input" autocomplete="off">
    </div>

    <!-- 스트리머 URL -->
    <label for="mb_streamer_url" class="auth-register__label">스트리머/채널 URL</label>
    <input type="text" name="mb_streamer_url" id="mb_streamer_url" class="auth-register__input">

    <!-- 제출 버튼 -->
    <input type="submit" value="회원가입" class="auth-register__submit">
    </form>

    <script src="/js/auth/register_form.js"></script>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

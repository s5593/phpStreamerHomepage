<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
session_start();


if ($is_member) {
    goto_url(G5_URL);
}

referer_check();

// 약관 동의 체크
if (!isset($_POST['agree']) || !$_POST['agree']) {
    alert('회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_BBS_URL.'/register.php');
}
if (!isset($_POST['agree2']) || !$_POST['agree2']) {
    alert('개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_BBS_URL.'/register.php');
}

// 세션 초기화
$token = bin2hex(random_bytes(32));
set_session("ss_token", $token);
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_created'] = time();
set_session("ss_cert_no",   "");
set_session("ss_cert_hash", "");
set_session("ss_cert_type", "");

// 타이틀 설정
$g5['title'] = '회원 가입';
include_once('./_head.php');

// 주소 API 사용 시 자바스크립트 추가
if ($config['cf_use_addr'])
    add_javascript(G5_POSTCODE_JS, 0); 

// 스킨 경로에서 UI 출력
include_once($member_skin_path.'/register_form.skin.php');

include_once('./_tail.php');
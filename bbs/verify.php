<?php
// 📄 verify.php — 이메일 인증 확인 및 가입 확정

session_start();
include_once('./_common.php');

// 1. 토큰 확인
if (!isset($_GET['token']) || !$_GET['token']) {
    die("잘못된 접근입니다. 토큰이 없습니다.");
}
$token = sql_real_escape_string($_GET['token']);

// 2. 토큰으로 사용자 조회
$member = sql_fetch("SELECT * FROM g5_member WHERE mb_email_token = '$token'");
if (!$member || !$member['mb_id']) {
    die("유효하지 않은 인증 토큰입니다.");
}

// 3. 토큰 유효시간 확인 (1시간 이내)
$created = strtotime($member['mb_email_token_created']);
if (time() - $created > 3600) {
    die("인증 유효시간이 만료되었습니다. 다시 가입해주세요.");
}

// 4. 이메일 인증 처리
sql_query("UPDATE g5_member SET 
    mb_email_certified = 'Y', 
    mb_email_token = '', 
    mb_email_token_created = NULL
    WHERE mb_id = '{$member['mb_id']}'");

// 5. 사용자에게 안내
echo "✅ 이메일 인증이 완료되었습니다. 이제 로그인하실 수 있습니다.";

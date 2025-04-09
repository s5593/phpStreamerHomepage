<?php
include_once('./_common.php');

// 세션 초기화
session_unset(); // 세션 변수 모두 제거
session_destroy(); // 세션 자체 제거

// 로그인 관련 쿠키 삭제 (혹시라도 있을 경우 대비)
set_cookie('ck_mb_id', '', 0);
set_cookie('ck_auto', '', 0);

// 로그아웃 후 이동할 페이지 지정
$url = $_GET['url'] ?? G5_URL;

// 외부 도메인 리디렉션 방지 (보안)
if (preg_match('/^https?:\/\//i', $url)) {
    $url = G5_URL;
}

// 로그아웃 이벤트 실행 (필요 시)
run_event('member_logout', $url);

// 리디렉션
goto_url($url);

<?php
// 세션 기반 회원 관련 헬퍼 함수

// 로그인 여부 확인
function is_logged_in() {
    return isset($_SESSION['mb_id']);
}

// 현재 로그인한 회원 ID 반환
function current_user_id() {
    return $_SESSION['mb_id'] ?? null;
}

function current_user_no() {
    return $_SESSION['mb_no'] ?? null;
}

// 현재 로그인한 회원 이메일 반환
function current_user_email() {
    return $_SESSION['mb_email'] ?? null;
}

// 현재 로그인한 회원 권한 레벨 반환
function current_user_level() {
    return $_SESSION['mb_level'] ?? 0;
}

// 관리자 여부 (레벨 3 이상)
function is_admin() {
    return current_user_level() >= 3;
}

// 최고 관리자 여부 (레벨 4)
function is_super_admin() {
    return current_user_level() >= 4;
}

// 이메일 인증 여부
function is_email_certified() {
    return ($_SESSION['mb_email_certified'] ?? 'N') === 'Y';
}

// 탈퇴 상태 여부
function is_leaved_user() {
    return ($_SESSION['mb_is_leave'] ?? 'N') === 'Y';
}

// 차단된 회원 여부
function is_blocked_user() {
    return ($_SESSION['mb_is_intercepted'] ?? 'N') === 'Y';
}

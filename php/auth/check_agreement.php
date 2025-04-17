<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_error("잘못된 접근입니다.");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_error("CSRF 토큰이 유효하지 않습니다.");
}

if (!isset($_POST['agree_terms']) || !isset($_POST['agree_privacy'])) {
    redirect_error("모든 약관에 동의해야 회원가입을 진행할 수 있습니다.");
}

// 세션에 동의 상태 저장
$_SESSION['agree_ok'] = true;

// 다음 단계 진입 허용용 토큰 (선택)
$_SESSION['register_stage'] = 'form_ready';

// GET 방식으로 register_form.php 진입
redirect_to('/html/auth/register_form.php');
exit;

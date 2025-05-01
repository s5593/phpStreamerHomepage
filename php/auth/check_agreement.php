<?php
include_once(__DIR__ . '/../../lib/common.php');

// 1. POST 방식만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    show_alert_and_back("잘못된 접근입니다.");
}

// 2. CSRF 검증
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    show_alert_and_back("CSRF 토큰이 유효하지 않습니다.");
}

// 3. 필수 체크박스 미동의 시 차단
if (!isset($_POST['agree_terms']) || !isset($_POST['agree_privacy'])) {
    show_alert_and_back("모든 약관에 동의해야 회원가입을 진행할 수 있습니다.");
}

// ✅ 약관 동의 여부 세션 저장
$_SESSION['agreed_to_terms'] = true;

// 선택적으로 추가: 회원가입 단계 관리용
unset($_SESSION['register_stage']); // 이전 단계 값 제거
$_SESSION['register_stage'] = 'form_ready';

// ✅ 다음 단계로 이동
redirect_to('/html/auth/register_form.php');
exit;

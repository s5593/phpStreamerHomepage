<?php
include_once(__DIR__ . '/../../lib/common.php');

if (!($_SESSION['register_success'] ?? false)) {
    redirect_error("잘못된 접근입니다.");
}
unset($_SESSION['register_success']); // 일회용 처리
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입 완료</title>
    <link rel="stylesheet" href="../../css/auth/register_complete.css">
</head>
<body>

<div class="complete-box">
    <h2>🎉 회원가입이 완료되었습니다!</h2>
    <p>이메일 인증 후 로그인이 가능합니다.</p>
    <a href="<?= get_base_url() ?>/html/auth/login_form.php" class="btn-go-login">로그인하러 가기</a>
</div>

</body>
</html>

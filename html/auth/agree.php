<?php
include_once(__DIR__ . '/../../lib/common.php');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>약관 동의</title>
    <link rel="stylesheet" href="../../css/auth/agree.css">
</head>
<body>

<h2>약관 및 개인정보 수집 동의</h2>

<form action="../../php/auth/check_agreement.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

    <!-- ✅ 전체동의 체크박스 -->
    <label>
        <input type="checkbox" id="agree_all">
        전체 약관에 동의합니다
    </label>
    <hr>

    <h4>📜 이용약관</h4>
    <textarea readonly>여기에 이용약관 내용을 작성하세요.</textarea>
    <label>
        <input type="checkbox" name="agree_terms" class="agree-check" required>
        이용약관에 동의합니다
    </label>

    <h4>🔐 개인정보 수집 및 이용 동의</h4>
    <textarea readonly>여기에 개인정보 처리방침 내용을 작성하세요.</textarea>
    <label>
        <input type="checkbox" name="agree_privacy" class="agree-check" required>
        개인정보 수집 및 이용에 동의합니다
    </label>

    <input type="submit" value="동의하고 회원가입 진행">
</form>
<script defer src="../../js/auth/agree.js"></script>
</body>
</html>

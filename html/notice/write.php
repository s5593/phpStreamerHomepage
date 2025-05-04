<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!is_admin()) show_alert_and_back('접근 권한이 없습니다.');
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>공지사항 작성</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Toast UI Editor CSS (CDN) -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">

    <!-- 프로젝트 전용 스타일 -->
    <link rel="stylesheet" href="../../css/notice/style.css">
    <link rel="stylesheet" href="../../css/editor.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <div class="notice">
        <h2 class="notice__title">공지사항 작성</h2>

        <form method="POST" action="/php/notice/create.php" data-editor-target="notice">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <!-- 제목 입력 -->
            <div class="notice__input-wrap">
                <input type="text" name="subject" placeholder="제목을 입력하세요" required>
            </div>

            <!-- 본문 에디터 -->
            <div id="editor"></div>
            <input type="hidden" id="content" name="content">

            <!-- 버튼 그룹 -->
            <div class="notice__btn-group">
                <button type="submit" class="notice__btn notice__btn--submit">작성 완료</button>
                <a href="list.php" class="notice__btn notice__btn--back">목록으로</a>
            </div>
        </form>
    </div>

    <?php include_once(__DIR__ . '/../../footer.php'); ?>
    <!-- Toast UI Editor JS (CDN) -->
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

    <!-- 프로젝트 에디터 초기화 스크립트 -->
    <script src="../../js/notice/editor.js"></script>
    <script src="../../js/notice/script.js"></script>
</body>
</html>

<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php'); // 공통 권한 함수

if (!is_admin()) show_alert_and_back('접근 권한이 없습니다.');

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>위키 문서 작성</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Toast UI Editor -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../../css/wiki/style.css">
  <link rel="stylesheet" href="../../css/editor.css">
</head>
<body>

<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="content-wrapper">

  <div class="page-title-write">
    <h2>위키 문서 작성</h2>
  </div>

  <form action="../../php/wiki/create.php" method="POST" id="wikiForm" data-editor-target="wiki" class="write-form">
  <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
  
  <div class="form-group">
    <input type="text" name="subject" placeholder="제목을 입력하세요" required class="input-title">
  </div>

  <div class="form-group">
    <div id="editor" style="height: 500px;"></div>
    <textarea name="content" id="content" style="display:none;"></textarea>
  </div>

  <div class="button-group">
    <input type="submit" value="작성 완료" class="btn-primary">
    <a href="list.php" class="btn-secondary">목록으로</a>
  </div>
</form>

</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="../../js/wiki/editor.js" defer></script>
</body>
</html>

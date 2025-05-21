<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>글쓰기</title>
  <link rel="stylesheet" href="/css/post/style.css">
  <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <h1 class="page-title">글쓰기</h1>

    <form action="/php/post/write_action.php" method="POST" onsubmit="return handleSubmitEditor();">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
      <div style="margin-bottom: 15px;">
        <input type="text" name="subject" class="search-input" style="width: 100%;" placeholder="제목을 입력하세요" required>
      </div>

      <div style="margin-bottom: 15px;">
        <input type="text" name="keywords" class="search-input" style="width: 100%;" placeholder="키워드 입력 (쉼표로 구분)">
      </div>

      <p style="color: #aaa; font-size: 14px;">※ 본문 전체 용량은 1MB 이하로 제한됩니다.</p>

      <div id="editor" style="margin-bottom: 15px;"></div>
      <input type="hidden" name="content" id="content-hidden">

      <div class="button-group">
        <button type="submit" class="button">등록</button>
        <a href="list.php" class="button button--secondary">취소</a>
      </div>
    </form>
  </div>

  <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
  <script src="/js/post/script.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>글 수정</title>
  <link rel="stylesheet" href="/css/post/style.css">
  <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <h1 class="page-title">글 수정</h1>

    <form action="/php/post/edit_action.php" method="POST" onsubmit="return handleSubmitEditor();">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">

      <div style="margin-bottom: 15px;">
        <input type="text" name="subject" class="search-input" style="width: 100%;" required
               value="<?= htmlspecialchars($post['subject']) ?>">
      </div>

      <div style="margin-bottom: 15px;">
        <input type="text" name="keywords" class="search-input" style="width: 100%;"
               value="<?= htmlspecialchars($post['keywords']) ?>">
      </div>

      <p style="color: #aaa; font-size: 14px;">※ 본문 전체 용량은 1MB 이하로 제한됩니다.</p>

      <div id="editor" style="margin-bottom: 15px;"></div>
      <input type="hidden" name="content" id="content-hidden">

      <div class="button-group">
        <button type="submit" class="button">수정 완료</button>
        <a href="view.php?id=<?= $post['id'] ?>" class="button button--secondary">취소</a>
      </div>
    </form>
  </div>

  <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
  <script>
    let editor;
    document.addEventListener('DOMContentLoaded', () => {
      editor = new toastui.Editor({
        el: document.querySelector('#editor'),
        height: '500px',
        initialEditType: 'wysiwyg',
        previewStyle: 'vertical',
        hideModeSwitch: true,
        toolbarItems: [['image']], // 이미지 버튼만 허용
        initialValue: <?= json_encode($post['content']) ?>
      });
    });

    function handleSubmitEditor() {
      const html = editor.getHTML();
      if (new Blob([html]).size > 1024 * 1024) {
        alert('본문 용량이 너무 큽니다. 1MB 이하로 작성해주세요.');
        return false;
      }
      document.getElementById('content-hidden').value = html;
      return true;
    }
  </script>
</body>
</html>

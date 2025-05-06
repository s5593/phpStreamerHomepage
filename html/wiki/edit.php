<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!is_admin()) show_alert_and_back('접근 권한이 없습니다.');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    show_alert_and_back('잘못된 접근입니다.');
    exit;
}

// 글 가져오기
$stmt = $conn->prepare("SELECT id, subject, content FROM board_wiki WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$post = $result->fetch_assoc();
if (!$post) {
    show_alert_and_back('존재하지 않는 문서입니다.');
    exit;
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>문서 수정 - <?= htmlspecialchars($post['subject']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/wiki/style.css">
  <link rel="stylesheet" href="../../css/editor.css">
</head>
<body>
<div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>

  <div class="wiki">
    <div class="wiki__header">
      <h2 class="wiki__title">문서 수정</h2>
    </div>

    <form action="/php/wiki/update.php" method="POST" id="wikiForm" onsubmit="return handleSubmitEditor();" class="wiki__form">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">

      <div class="wiki__form-group">
        <input type="text" name="subject" value="<?= htmlspecialchars($post['subject']) ?>" required class="input input--title">
      </div>

      <div class="wiki__form-group">
        <div id="editor" style="height: 500px;"></div>
        <textarea name="content" id="content" style="display:none;"></textarea>
      </div>

      <div class="button-group button-group--right">
        <button type="submit" class="button button--edit">수정 완료</button>
        <a href="view.php?id=<?= $post['id'] ?>" class="button button--secondary">취소</a>
      </div>
    </form>
  </div>
</div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="../../js/wiki/editor.js" defer></script>

<script>
  window.postContent = <?= json_encode($post['content']) ?>;
</script>

</body>
</html>

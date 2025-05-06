<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
  show_alert_and_back('잘못된 접근입니다.');
    exit;
}

// 글 가져오기
$stmt = $conn->prepare("
    SELECT id, subject, content, created_at 
    FROM board_wiki 
    WHERE id = ? AND is_use = 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$post = $result->fetch_assoc();

if (!$post) {
  show_alert_and_back('존재하지 않는 문서입니다.');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['subject']) ?> - 위키 문서</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/wiki/style.css">
</head>
<body>
<div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>

  <div class="wiki">
    <h2 class="wiki__title"><?= htmlspecialchars($post['subject']) ?></h2>

    <div class="wiki__card">
      <button id="tocToggleBtn" class="wiki__toc-toggle">▼ 펼치기</button>

      <div class="wiki-layout">
        <!-- 왼쪽: 콘텐츠 -->
        <div id="wiki-content" class="wiki__content">
          <?= $post['content'] ?>
        </div>

        <!-- 오른쪽: 목차 -->
        <div id="toc" class="wiki-toc">
          <!-- JS로 자동 생성됨 -->
        </div>
      </div>

      <div class="wiki__meta">작성일: <?= date('Y-m-d H:i:s', strtotime($post['created_at'])) ?></div>
    </div>

    <div class="button-group">
      <?php if (is_admin()): ?>
        <a href="edit.php?id=<?= $post['id'] ?>" class="button button--edit" style="display:inline;">수정</a>
        <form action="/php/wiki/delete.php" method="POST" class="wiki__form--inline" onsubmit="return confirm('삭제하시겠습니까?');" style="display:inline;">
          <input type="hidden" name="id" value="<?= $post['id'] ?>">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
          <button type="submit" class="button button--delete">삭제</button>
        </form>
      <?php endif; ?>
      <a href="list.php" class="button button--secondary">목록으로</a>
    </div>
  </div>
</div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
<script src="../../js/wiki/script.js" defer></script>
</body>
</html>

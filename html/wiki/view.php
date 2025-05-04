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

<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="wiki">
  <h2 class="wiki__title"><?= htmlspecialchars($post['subject']) ?></h2>

  <div class="wiki__card">
    <div class="wiki__content">
      <?= $post['content'] ?>
    </div>
    <div class="wiki__meta">작성일: <?= date('Y-m-d H:i:s', strtotime($post['created_at'])) ?></div>
  </div>

  <div class="wiki__button-group">
    <?php if (is_admin()): ?>
      <a href="edit.php?id=<?= $post['id'] ?>" class="wiki__btn wiki__btn--edit">수정</a>
      <form action="/php/wiki/delete.php" method="POST" class="wiki__form--inline" onsubmit="return confirm('삭제하시겠습니까?');">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        <button type="submit" class="wiki__btn wiki__btn--delete">삭제</button>
      </form>
    <?php endif; ?>
    <a href="list.php" class="wiki__btn wiki__btn--back">목록으로</a>
  </div>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

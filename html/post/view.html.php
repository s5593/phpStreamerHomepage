<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['subject']) ?></title>
  <link rel="stylesheet" href="/css/post/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <h1 class="page-title"><?= htmlspecialchars($post['subject']) ?></h1>
    <p style="color: #ccc;">작성자: <?= htmlspecialchars($post['mb_id']) ?> | 작성일: <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></p>

    <div class="video__keywords" style="margin: 10px 0 20px;">
      <?php foreach (explode(',', $post['keywords']) as $kw): ?>
        <?php $kw = trim($kw); if ($kw): ?>
          <button class="video__keyword-btn" disabled><?= htmlspecialchars($kw) ?></button>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="wiki__content" style="margin-bottom: 30px;">
      <?= $post['content'] ?>
    </div>

    <div class="button-group">
      <?php if ($can_edit): ?>
        <form method="post" action="edit.php" style="display:inline;">
          <input type="hidden" name="id" value="<?= $post['id'] ?>">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
          <button type="submit" class="button button--edit">수정</button>
        </form>
        <form method="post" action="/php/post/delete.php" style="display:inline;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
          <input type="hidden" name="id" value="<?= $post['id'] ?>">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
          <button type="submit" class="button button--delete">삭제</button>
        </form>
      <?php endif; ?>
      <a href="list.php" class="button button--secondary">목록으로</a>
    </div>
  </div>
</body>
</html>

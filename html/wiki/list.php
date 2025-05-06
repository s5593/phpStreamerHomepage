<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$keyword = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 8;
$offset = ($page - 1) * $limit;

// 검색어에 따라 쿼리 분기
if ($keyword) {
    $stmt = $conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS * 
        FROM board_wiki 
        WHERE is_use = 1 AND (subject LIKE ? OR content LIKE ?) 
        ORDER BY id DESC 
        LIMIT ? OFFSET ?");
    $search = "%{$keyword}%";
    $stmt->bind_param("ssii", $search, $search, $limit, $offset);
} else {
    $stmt = $conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS * 
        FROM board_wiki 
        WHERE is_use = 1 
        ORDER BY id DESC 
        LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 전체 건수 조회
$total_result = $conn->query("SELECT FOUND_ROWS() as total");
$total_count = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_count / $limit);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>위키 문서 목록</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/wiki/style.css">
</head>
<body>
<div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>

  <div class="wiki">
    <div class="wiki__header">
      <h2 class="wiki__title">위키 문서</h2>
    </div>

    <?php if (is_admin()): ?>
      <div class="wiki__write-button-wrapper">
        <a href="/html/wiki/write.php" class="wiki__write-button">[문서 작성]</a>
      </div>
    <?php endif; ?>

    <form method="GET" action="list.php" class="wiki__search-form" style="margin-top: 10px;">
      <input type="text" name="q" class="input" placeholder="제목 검색" value="<?= htmlspecialchars($keyword) ?>">
      <button type="submit" class="button button--primary">검색</button>
      <?php if ($keyword): ?>
        <a href="list.php" class="button button--secondary">검색 초기화</a>
      <?php endif; ?>
    </form>

    <?php if ($result->num_rows === 0): ?>
      <div class="wiki__empty-message">문서가 없습니다.</div>
    <?php else: ?>
      <div class="card-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <a href="view.php?id=<?= htmlspecialchars($row['id']) ?>" class="card-link">
            <div class="card">
              <div class="card__image">
                <img src="<?= htmlspecialchars($row['image'] ?? '/uploads/main/image.png') ?>" alt="공지 이미지">
              </div>
              <div class="card__content">
                <h3 class="card__title"><?= htmlspecialchars($row['subject']) ?></h3>
                <small class="card__meta"><?= date('Y-m-d', strtotime($row['created_at'])) ?></small>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>


    <?php if ($total_pages > 1): ?>
      <div class="wiki__pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php $params = "page=$i" . ($keyword ? "&q=" . urlencode($keyword) : ''); ?>
          <a href="list.php?<?= $params ?>" class="<?= $i == $page ? 'wiki__pagination--active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>

</body>
</html>

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

<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="wiki">
  <div class="wiki__header">
    <h2 class="wiki__title">위키 문서 목록</h2>
  </div>

  <!-- 글쓰기 버튼 -->
  <?php if (is_admin()): ?>
    <div class="wiki__write-button-wrapper">
      <a href="/html/wiki/write.php" class="wiki__write-button">[문서 작성]</a>
    </div>
  <?php endif; ?>

  <!-- 검색 폼 -->
  <form method="GET" action="list.php" class="wiki__search-form">
    <input type="text" name="q" placeholder="검색어를 입력하세요" value="<?= htmlspecialchars($keyword) ?>" class="wiki__search-input">
    <input type="submit" value="검색" class="wiki__search-button">
    <?php if ($keyword): ?>
      <a href="list.php" class="wiki__reset-link">검색 초기화</a>
    <?php endif; ?>
  </form>

  <!-- 문서 목록 -->
  <?php if ($result->num_rows === 0): ?>
    <div class="wiki__empty-message">문서가 없습니다.</div>
  <?php else: ?>
    <div class="wiki__card-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <a href="view.php?id=<?= htmlspecialchars($row['id']) ?>" class="wiki__card-link">
          <div class="wiki__card">
            <div class="wiki__card-image"></div>
            <div class="wiki__card-content">
              <h3 class="wiki__card-title"><?= htmlspecialchars($row['subject']) ?></h3>
              <small class="wiki__card-date"><?= date('Y-m-d', strtotime($row['created_at'])) ?></small>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

  <!-- 페이지네이션 -->
  <?php if ($total_pages > 1): ?>
    <div class="wiki__pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++):
        $params = "page=$i";
        if ($keyword) $params .= "&q=" . urlencode($keyword);
      ?>
        <a href="list.php?<?= $params ?>" class="<?= $i == $page ? 'wiki__pagination--active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

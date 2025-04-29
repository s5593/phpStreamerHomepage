<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$is_logged_in = current_user_id() !== null;
$search = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$page_size = 8;
$offset = ($page - 1) * $page_size;

$where = 'WHERE is_use = 1';
$params = [];
$types = '';

if ($search) {
    $where .= ' AND subject LIKE ?';
    $params[] = '%' . $search . '%';
    $types .= 's';
}

$sql_count = "SELECT COUNT(*) as cnt FROM board_video $where";
$stmt = $conn->prepare($sql_count);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

$sql = "SELECT V.id, V.subject, V.video_url, V.created_at, M.mb_id
        FROM board_video V
        JOIN g5_member M ON V.user_id = M.mb_no
        $where
        ORDER BY V.created_at DESC
        LIMIT ? OFFSET ?";
$params[] = $page_size;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>경상 목록</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/video/style.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>
  <div class="video-container">
    <div class="video-header">
      <h1>🎮 영상 게시판</h1>
      <?php if ($is_logged_in): ?>
        <a href="write.php" class="write-button">+ 글쓰기</a>
      <?php endif; ?>
    </div>

    <form method="get" action="list.php" class="search-form">
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="검색어 입력" class="search-input">
      <button type="submit" class="search-button">검색</button>
    </form>

    <div class="card-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          $video_id = $row['video_url'];
          $thumbnail_url = preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id)
            ? "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg"
            : "/uploads/main/image.png";
        ?>
        <a href="/html/video/view.php?id=<?= htmlspecialchars($row['id']) ?>" class="card-link">
          <div class="card">
            <div class="thumbnail" style="background-image: url('<?= $thumbnail_url ?>');"></div>
            <div class="card-body">
              <div class="card-title"><?= htmlspecialchars($row['subject']) ?></div>
              <div class="card-writer">작성자: <?= htmlspecialchars($row['mb_id']) ?></div>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>

    <div class="pagination">
      <?php
      $total_pages = ceil($total / $page_size);
      for ($i = 1; $i <= $total_pages; $i++):
        $link = "list.php?page=$i";
        if ($search) $link .= '&q=' . urlencode($search);
      ?>
        <a href="<?= $link ?>" class="page-button <?= $i === $page ? 'active' : '' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>
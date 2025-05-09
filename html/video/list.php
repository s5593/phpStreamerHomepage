<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$is_logged_in = current_user_id() !== null;

// GET 파라미터
$search = trim($_GET['q'] ?? '');
$search_type = $_GET['type'] ?? 'subject';
$page = max(1, intval($_GET['page'] ?? 1));
$page_size = 8;
$offset = ($page - 1) * $page_size;

// SQL 준비
$where = 'WHERE is_use = 1';
$params = [];
$types = '';

// 검색 조건 처리
if ($search) {
    if ($search_type === 'keywords') {
        $keywords = array_filter(array_map('trim', explode(',', $search)));
        foreach ($keywords as $kw) {
            $where .= ' AND keywords LIKE ?';
            $params[] = '%' . $kw . '%';
            $types .= 's';
        }
    } else {
        $where .= ' AND subject LIKE ?';
        $params[] = '%' . $search . '%';
        $types .= 's';
    }
}

// 총 개수 쿼리
$sql_count = "SELECT COUNT(*) as cnt FROM board_video $where";
$stmt = $conn->prepare($sql_count);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// 게시글 조회 쿼리
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
  <title>클립 목록</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/video/style.css">
  <link rel="stylesheet" href="/css/video/search.css">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <div class="video">
      <div class="video__header">
        <h1 class="video__title">🎮 영상 게시판</h1>
        <?php if ($is_logged_in): ?>
          <a href="write.php" class="video__write-button">+ 글쓰기</a>
        <?php endif; ?>
      </div>

      <?php include_once(__DIR__ . '/search.php'); ?>

      <div class="card-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $video_url = $row['video_url'];
            $thumbnail_url = '/uploads/main/image.png'; // 기본 이미지

            // YouTube ID 추출 (watch?v=, youtu.be, shorts 지원)
            if (preg_match('/(?:youtube\.com\/.*[?&]v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches)) {
                $youtube_id = $matches[1];
                $thumbnail_url = "https://img.youtube.com/vi/{$youtube_id}/hqdefault.jpg";
            }
          ?>
          <a href="/html/video/view.php?id=<?= htmlspecialchars($row['id']) ?>" class="card-link">
            <div class="card">
              <div class="card__image" style="background-image: url('<?= $thumbnail_url ?>');"></div>
              <div class="card__content">
                <h3 class="card__title"><?= htmlspecialchars($row['subject']) ?></h3>
                <p class="card__meta">작성자: <?= htmlspecialchars($row['mb_id']) ?></p>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>


      <div class="video__pagination">
        <?php
        $total_pages = ceil($total / $page_size);
        for ($i = 1; $i <= $total_pages; $i++):
          $link = "list.php?page=$i";
          if ($search) $link .= '&q=' . urlencode($search);
        ?>
          <a href="<?= $link ?>" class="video__page-button <?= $i === $page ? 'video__page-button--active' : '' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    </div>
  </div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>
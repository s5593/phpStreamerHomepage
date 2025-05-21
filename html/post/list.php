<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

// 검색 파라미터
$search_type = $_GET['type'] ?? 'subject';
$search_keyword = trim($_GET['q'] ?? '');

// 게시글 불러오기
$sql = "SELECT P.*, M.mb_id FROM board_user_post P JOIN g5_member M ON P.user_id = M.mb_no WHERE P.is_use = 1";
$params = [];

if ($search_keyword !== '') {
    if ($search_type === 'subject') {
        $sql .= " AND P.subject LIKE CONCAT('%', ?, '%')";
        $params[] = $search_keyword;
    } else if ($search_type === 'keywords') {
        $keywords = array_filter(array_map('trim', explode(',', $search_keyword)));
        if ($keywords) {
            $sql .= " AND (";
            foreach ($keywords as $i => $kw) {
                if ($i > 0) $sql .= " OR ";
                $sql .= "FIND_IN_SET(?, P.keywords)";
                $params[] = $kw;
            }
            $sql .= ")";
        }
    }
}

$sql .= " ORDER BY P.id DESC LIMIT 50";
$stmt = $conn->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>사용자 게시판</title>
  <link rel="stylesheet" href="/css/common.css">
  <link rel="stylesheet" href="/css/post/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <h1 class="page-title">사용자 게시판</h1>

    <form method="get" class="search-bar video__search-form">
      <select name="type" class="search-select">
        <option value="subject" <?= $search_type === 'subject' ? 'selected' : '' ?>>제목</option>
        <option value="keywords" <?= $search_type === 'keywords' ? 'selected' : '' ?>>키워드</option>
      </select>
      <input type="text" name="q" value="<?= htmlspecialchars($search_keyword) ?>" class="search-input" placeholder="검색어 입력">
      <button type="submit" class="button">검색</button>
    </form>
    <div class="button-group" style="margin: 20px 0;">
      <a href="write.php" class="button">글쓰기</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>번호</th>
          <th>제목</th>
          <th>작성자</th>
          <th>작성일</th>
          <th>키워드</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td>
              <a href="view.php?id=<?= $row['id'] ?>">
                <?= htmlspecialchars($row['subject']) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($row['mb_id']) ?></td>
            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
            <td>
              <?php foreach (explode(',', $row['keywords']) as $kw): ?>
                <?php $kw = trim($kw); if (!$kw) continue; ?>
                <button type="button" class="video__keyword-btn" disabled><?= htmlspecialchars($kw) ?></button>
              <?php endforeach; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

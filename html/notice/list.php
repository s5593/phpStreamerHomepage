<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$csrf_token = generate_csrf_token();
// GET 파라미터 처리
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

// 검색 조건 처리
$where = "WHERE is_use = 1";
$params = [];
$types = '';

if ($search) {
    $where .= " AND subject LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's';
}

// 전체 글 수 조회
$sql_count = "SELECT COUNT(*) FROM board_notice $where";
$stmt_count = $conn->prepare($sql_count);
if ($types) $stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$stmt_count->bind_result($total_count);
$stmt_count->fetch();
$stmt_count->close();

$total_pages = ceil($total_count / $limit);

// 게시글 조회
$sql = "SELECT * FROM board_notice $where ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $limit;
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
    <title>공지사항</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/notice/style.css">
</head>
<body>
    <div class="page-container">
        <?php include_once(__DIR__ . '/../../header.php'); ?>

        <div class="notice">
            <h2 class="notice__title">📢 공지사항</h2>

            <?php if (is_admin()): ?>
            <form method="POST" action="write.php">
                <button type="submit" class="button button--primary">[문서 작성]</button>
            </form>
            <?php endif; ?>

            <form method="GET" action="list.php" class="notice__search-form" style="margin-top: 10px;">
                <input type="text" name="q" class="input" placeholder="제목 검색" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="button button--primary">검색</button>
                <?php if ($search): ?>
                    <a href="list.php" class="button button--secondary">검색 초기화</a>
                <?php endif; ?>
            </form>

            <div class="card-grid">
            <?php if ($result->num_rows === 0): ?>
                <div class="card card--empty">공지사항이 없습니다.</div>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <a href="view.php?id=<?= $row['id'] ?>" class="card">
                    <div class="card__image">
                        <img src="<?= htmlspecialchars($row['image'] ?? '/uploads/main/image.png') ?>" alt="공지 이미지">
                    </div>
                    <div class="card__content">
                    <h4 class="card__title"><?= htmlspecialchars($row['subject']) ?></h4>
                    <span class="card__meta"><?= substr($row['created_at'], 0, 10) ?></span>
                    </div>
                </a>
                <?php endwhile; ?>
            <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="notice__pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="list.php?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="notice__pagination-link <?= $i === $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>

    <script src="../../js/notice/editor.js"></script>
    <script src="../../js/notice/script.js"></script>
</body>
</html>

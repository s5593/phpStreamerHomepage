<?php
include_once(__DIR__ . '/../../lib/common.php');

$result = $conn->query("SELECT * FROM board_video WHERE is_use = 1 ORDER BY id DESC");
?>

<h2>🎥 영상 게시판</h2>
<a href="/html/video/write.php">[영상 등록]</a>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <a href="/html/video/view.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['subject']) ?>
        </a>
        <small>작성자: <?= htmlspecialchars($row['author_id']) ?> | <?= $row['created_at'] ?></small>
    </li>
<?php endwhile; ?>
</ul>

<?php
include_once(__DIR__ . '/../../lib/common.php');

$result = $conn->query("SELECT * FROM board_notice WHERE is_use = 1 ORDER BY id DESC");
?>

<h2>📢 공지사항</h2>
<a href="/html/notice/write.php">[글쓰기]</a>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <a href="/html/notice/view.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['subject']) ?>
        </a>
        <small><?= $row['created_at'] ?></small>
    </li>
<?php endwhile; ?>
</ul>

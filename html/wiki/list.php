<?php
include_once(__DIR__ . '/../../lib/common.php');
$result = $conn->query("SELECT * FROM board_wiki WHERE is_use = 1 ORDER BY id DESC");
?>

<h2>📚 위키</h2>
<?php if ($_SESSION['mb_level'] >= 3): ?>
    <a href="/html/wiki/write.php">[새 문서 작성]</a>
<?php endif; ?>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <a href="/html/wiki/view.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['subject']) ?>
        </a>
        <small><?= $row['created_at'] ?></small>
    </li>
<?php endwhile; ?>
</ul>

<?php
include_once(__DIR__ . '/../../lib/common.php');
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM board_notice WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) exit('글이 존재하지 않거나 삭제되었습니다.');
?>

<h2><?= htmlspecialchars($post['subject']) ?></h2>
<div><?= $post['content'] ?></div>

<?php if ($_SESSION['mb_level'] >= 3): ?>
    <a href="/html/notice/edit.php?id=<?= $post['id'] ?>">[수정]</a>
    <a href="/php/notice/delete.php?id=<?= $post['id'] ?>" onclick="return confirm('삭제하시겠습니까?')">[삭제]</a>
<?php endif; ?>

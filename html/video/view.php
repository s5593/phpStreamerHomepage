<?php
include_once(__DIR__ . '/../../lib/common.php');
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM board_video WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) exit('영상이 존재하지 않거나 삭제되었습니다.');

$is_author = ($_SESSION['mb_id'] ?? '') === $post['author_id'];
$is_admin = ($_SESSION['mb_level'] ?? 0) >= 3;
?>

<h2><?= htmlspecialchars($post['subject']) ?></h2>
<p>영상 링크: <a href="<?= htmlspecialchars($post['video_url']) ?>" target="_blank"><?= htmlspecialchars($post['video_url']) ?></a></p>

<?php if ($is_author || $is_admin): ?>
    <a href="/html/video/edit.php?id=<?= $post['id'] ?>">[수정]</a>
    <a href="/php/video/delete.php?id=<?= $post['id'] ?>" onclick="return confirm('삭제할까요?')">[삭제]</a>
<?php endif; ?>

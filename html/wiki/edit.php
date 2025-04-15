<?php
include_once(__DIR__ . '/../../lib/common.php');
if ($_SESSION['mb_level'] < 3) exit('권한 없음');
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM board_wiki WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

$csrf_token = generate_csrf_token();
?>

<h2>문서 수정</h2>
<form action="/php/wiki/update.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    제목: <input type="text" name="subject" value="<?= htmlspecialchars($post['subject']) ?>"><br><br>
    내용:<br>
    <textarea name="content" rows="15" cols="80"><?= htmlspecialchars($post['content']) ?></textarea><br>
    <input type="submit" value="수정">
</form>

<?php
include_once(__DIR__ . '/../../lib/common.php');
$id = intval($_GET['id'] ?? 0);
if ($_SESSION['mb_level'] < 3) exit('관리자만 수정 가능');

$stmt = $conn->prepare("SELECT * FROM board_notice WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

$csrf_token = generate_csrf_token();
?>

<h2>글 수정</h2>
<form action="/php/notice/update.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    제목: <input type="text" name="subject" value="<?= htmlspecialchars($post['subject']) ?>"><br><br>
    내용:<br>
    <textarea name="content" rows="10" cols="80"><?= htmlspecialchars($post['content']) ?></textarea><br>
    <input type="submit" value="수정">
</form>

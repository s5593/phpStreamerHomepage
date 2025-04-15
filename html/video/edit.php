<?php
include_once(__DIR__ . '/../../lib/common.php');
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM board_video WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) exit('존재하지 않는 영상입니다.');

$is_author = ($_SESSION['mb_id'] ?? '') === $post['author_id'];
$is_admin = ($_SESSION['mb_level'] ?? 0) >= 3;
if (!$is_author && !$is_admin) exit('수정 권한 없음');

$csrf_token = generate_csrf_token();
?>

<h2>영상 수정</h2>
<form action="/php/video/update.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    제목: <input type="text" name="subject" value="<?= htmlspecialchars($post['subject']) ?>"><br><br>
    영상 URL: <input type="text" name="video_url" value="<?= htmlspecialchars($post['video_url']) ?>"><br><br>
    <input type="submit" value="수정">
</form>

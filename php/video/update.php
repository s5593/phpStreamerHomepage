<?php
include_once(__DIR__ . '/../../lib/common.php');

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) exit('CSRF 오류');

$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');
$author = $_SESSION['mb_id'];
$is_admin = ($_SESSION['mb_level'] ?? 0) >= 3;

// 작성자 확인
$stmt = $conn->prepare("SELECT author_id FROM board_video WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($author_id);
$stmt->fetch();
$stmt->close();

if ($author_id !== $author && !$is_admin) exit('수정 권한 없음');

$stmt = $conn->prepare("UPDATE board_video SET subject=?, video_url=? WHERE id=?");
$stmt->bind_param("ssi", $subject, $video_url, $id);
$stmt->execute();
$stmt->close();

header("Location: /html/video/view.php?id=$id");
exit;

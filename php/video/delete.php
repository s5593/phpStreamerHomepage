<?php
include_once(__DIR__ . '/../../lib/common.php');

$id = intval($_GET['id'] ?? 0);
$author = $_SESSION['mb_id'] ?? '';
$is_admin = ($_SESSION['mb_level'] ?? 0) >= 3;

// 권한 확인
$stmt = $conn->prepare("SELECT author_id FROM board_video WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($author_id);
$stmt->fetch();
$stmt->close();

if ($author_id !== $author && !$is_admin) exit('삭제 권한 없음');

// 삭제 처리
$stmt = $conn->prepare("UPDATE board_video SET is_use = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: /html/video/list.php");
exit;

<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SESSION['mb_level'] < 3) exit('권한 없음');
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) exit('CSRF 오류');

$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$content = $_POST['content'] ?? '';

$stmt = $conn->prepare("UPDATE board_notice SET subject=?, content=? WHERE id=?");
$stmt->bind_param("ssi", $subject, $content, $id);
$stmt->execute();
$stmt->close();

header("Location: /html/notice/view.php?id=$id");
exit;

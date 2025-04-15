<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SESSION['mb_level'] < 3) exit('권한이 없습니다.');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit('잘못된 접근입니다.');
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) exit('CSRF 오류');

$subject = trim($_POST['subject'] ?? '');
$content = $_POST['content'] ?? '';
$author = $_SESSION['mb_id'];

$stmt = $conn->prepare("INSERT INTO board_notice (subject, content, author_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $subject, $content, $author);
$stmt->execute();
$stmt->close();

header("Location: /html/notice/list.php");
exit;

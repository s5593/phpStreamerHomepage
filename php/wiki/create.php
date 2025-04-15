<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SESSION['mb_level'] < 3) exit('권한 없음');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit('잘못된 접근');
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) exit('CSRF 오류');

$subject = trim($_POST['subject'] ?? '');
$content = $_POST['content'] ?? '';
$author = $_SESSION['mb_id'];

$stmt = $conn->prepare("INSERT INTO board_wiki (subject, content, author_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $subject, $content, $author);
$stmt->execute();
$stmt->close();

header("Location: /html/wiki/list.php");
exit;

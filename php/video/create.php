<?php
include_once(__DIR__ . '/../../lib/common.php');

if (!isset($_SESSION['mb_id'])) exit('로그인이 필요합니다.');
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) exit('CSRF 오류');

$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');
$author = $_SESSION['mb_id'];

$stmt = $conn->prepare("INSERT INTO board_video (subject, video_url, author_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $subject, $video_url, $author);
$stmt->execute();
$stmt->close();

header("Location: /html/video/list.php");
exit;

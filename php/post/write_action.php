<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)', '/html/post/write.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('유효하지 않은 요청입니다. (CSRF 실패)', '/html/post/write.php');
}

$user_id = current_user_no();
if (!$user_id) {
    redirect_with_message('로그인이 필요합니다.', '/html/post/write.php');
}

$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');
$keywords = trim($_POST['keywords'] ?? '');

if (!$subject || !$content) {
    redirect_with_message('제목과 내용을 모두 입력해주세요.', '/html/post/write.php');
}

if (mb_strlen($subject) > 100 || mb_strlen($keywords) > 255) {
    redirect_with_message('제목은 100자 이하, 키워드는 255자 이하로 입력해주세요.', '/html/post/write.php');
}

$content = trim($_POST['content'] ?? '');
if (strlen($content) > 1024 * 1024) {
    redirect_with_message('본문 용량이 너무 큽니다. 1MB 이하로 작성해주세요.', "/html/post/write.php");
}

$stmt = $conn->prepare("
  INSERT INTO board_user_post (user_id, subject, content, keywords, created_at, updated_at, is_use)
  VALUES (?, ?, ?, ?, NOW(), NOW(), 1)
");
$stmt->bind_param("isss", $user_id, $subject, $content, $keywords);

if (!$stmt->execute()) {
    error_log('글 등록 실패: ' . $stmt->error);
    redirect_with_message('글 등록에 실패했습니다. 다시 시도해주세요.', '/html/post/write.php');
}

$stmt->close();
redirect_with_message('정상적으로 등록되었습니다.', '/html/post/list.php');

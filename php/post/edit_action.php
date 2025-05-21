<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)', '/html/post/list.php');
}

$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');
$keywords = trim($_POST['keywords'] ?? '');
$user_id = current_user_no();

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('유효하지 않은 요청입니다. (CSRF 실패)', "/html/post/edit.php?id={$id}");
}

if (!$id || !$subject || !$content || !$user_id) {
    redirect_with_message('필수 항목이 누락되었습니다.', "/html/post/edit.php?id={$id}");
}

if (mb_strlen($subject) > 100 || mb_strlen($keywords) > 255) {
    redirect_with_message('제목은 100자 이하, 키워드는 255자 이하로 입력해주세요.', "/html/post/edit.php?id={$id}");
}

$content = trim($_POST['content'] ?? '');
if (strlen($content) > 1024 * 1024) {
    redirect_with_message('본문 용량이 너무 큽니다. 1MB 이하로 작성해주세요.', "/html/post/edit.php?id={$id}");
}

// 작성자 확인
$stmt = $conn->prepare("SELECT user_id FROM board_user_post WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post || intval($post['user_id']) !== $user_id) {
    redirect_with_message('수정 권한이 없습니다.', "/html/post/list.php");
}

// 수정 처리
$stmt = $conn->prepare("
  UPDATE board_user_post
  SET subject = ?, content = ?, keywords = ?, updated_at = NOW()
  WHERE id = ? AND is_use = 1
");
$stmt->bind_param("sssi", $subject, $content, $keywords, $id);

if (!$stmt->execute()) {
    error_log("수정 실패: " . $stmt->error);
    redirect_with_message('글 수정에 실패했습니다.', "/html/post/edit.php?id={$id}");
}

$stmt->close();
redirect_with_message('글이 성공적으로 수정되었습니다.', "/html/post/view.php?id={$id}");

<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)', '/html/post/list.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('유효하지 않은 요청입니다. (CSRF 실패)', '/html/post/list.php');
}

$id = intval($_POST['id'] ?? 0);
$user_id = current_user_no();

if ($id <= 0 || !$user_id) {
    redirect_with_message('잘못된 요청입니다.', '/html/post/list.php');
}

// 작성자 본인 확인
$stmt = $conn->prepare("SELECT user_id FROM board_user_post WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if (!$result || $result->num_rows === 0) {
    redirect_with_message('존재하지 않는 게시글입니다.', '/html/post/list.php');
}

$post = $result->fetch_assoc();
if (intval($post['user_id']) !== $user_id) {
    redirect_with_message('삭제 권한이 없습니다.', '/html/post/list.php');
}

// 실제 삭제 처리 (Soft delete)
$stmt = $conn->prepare("UPDATE board_user_post SET is_use = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    error_log("글 삭제 실패: " . $stmt->error);
    redirect_with_message('삭제 처리에 실패했습니다.', '/html/post/list.php');
}
$stmt->close();

redirect_with_message('삭제되었습니다.', '/html/post/list.php');

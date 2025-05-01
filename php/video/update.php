<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)', "/html/video/edit.php?id={$id}");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('잘못된 요청입니다. (CSRF 실패)', "/html/video/edit.php?id={$id}");
}

$user_id = current_user_no();
if (!$user_id) {
    redirect_with_message('로그인이 필요합니다.', "/html/video/edit.php?id={$id}");
}

if ($id <= 0 || !$subject || !$video_url) {
    redirect_with_message('필수 입력값이 누락되었습니다.', "/html/video/edit.php?id={$id}");
}
if (mb_strlen($subject) > 100 || mb_strlen($video_url) > 255) {
    redirect_with_message('입력값이 너무 깁니다.', "/html/video/edit.php?id={$id}");
}

$video_id = extract_video_id($video_url);
if (!$video_id) {
    redirect_with_message('유효한 YouTube URL 또는 치지직 클립만 등록 가능합니다.', "/html/video/edit.php?id={$id}");
}

$stmt = $conn->prepare("SELECT user_id FROM board_video WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    redirect_with_message('존재하지 않는 게시글입니다.', "/html/video/edit.php?id={$id}");
}
if ($user_id !== intval($post['user_id'])) {
    redirect_with_message('수정 권한이 없습니다.', "/html/video/edit.php?id={$id}");
}

$keywords = trim($_POST['keywords'] ?? '');
if (mb_strlen($keywords) > 255) {
    redirect_with_message('키워드는 255자 이하로 입력해주세요.', "/html/video/edit.php?id={$id}");
}

$stmt = $conn->prepare("
    UPDATE board_video
    SET subject = ?, video_url = ?, keywords = ?, updated_at = NOW()
    WHERE id = ? AND is_use = 1
");
$stmt->bind_param("sssi", $subject, $video_id, $keywords, $id);

if (!$stmt->execute()) {
    error_log("영상 수정 실패: " . $stmt->error);
    redirect_with_message('영상 수정에 실패했습니다. 다시 시도해주세요.', "/html/video/edit.php?id={$id}");
}

$stmt->close();
redirect_with_message('영상이 성공적으로 수정되었습니다.', "/html/video/view.php?id={$id}");
exit;

function extract_video_id(string $url): ?string {
    // YouTube: watch?v= / youtu.be / shorts
    if (preg_match('/(?:youtube\.com\/.*[?&]v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    // 치지직 클립 전용: /clips/, /embed/clip/, /video/clip/
    if (preg_match('/chzzk\.naver\.com\/(?:clips|embed\/clip|video\/clip)\/([a-zA-Z0-9]+)/', $url, $m)) {
        return $m[1];
    }
    return null;
}

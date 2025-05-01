<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)','/html/video/write.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('잘못된 요청입니다. (CSRF 실패)','/html/video/write.php');
}

$user_id = current_user_no();
$writer = current_user_id();
if (!$user_id || !$writer) {
    redirect_with_message('로그인이 필요합니다.','/html/video/write.php');
}

$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');

if (!$subject || !$video_url) {
    redirect_with_message('제목과 URL을 모두 입력해주세요.','/html/video/write.php');
}
if (mb_strlen($subject) > 100 || mb_strlen($video_url) > 255) {
    redirect_with_message('입력값이 너무 깁니다.','/html/video/write.php');
}

$video_id = extract_video_id($video_url);
if (!$video_id) {
    redirect_with_message('유효한 YouTube URL 또는 치지직 클립만 등록 가능합니다.','/html/video/write.php');
}

$keywords = trim($_POST['keywords'] ?? '');
if (mb_strlen($keywords) > 255) {
    redirect_with_message('키워드는 255자 이하로 입력해주세요.', '/html/video/write.php');
}

$stmt = $conn->prepare("
    INSERT INTO board_video (subject, video_url, user_id, keywords, created_at, updated_at, is_use)
    VALUES (?, ?, ?, ?, NOW(), NOW(), 1)
");
$stmt->bind_param("ssis", $subject, $video_id, $user_id, $keywords);

if (!$stmt->execute()) {
    error_log("영상 등록 실패: " . $stmt->error);
    redirect_with_message('영상 등록에 실패했습니다. 다시 시도해주세요.','/html/video/write.php');
}

$stmt->close();
redirect_with_message('영상이 성공적으로 등록되었습니다.','/html/video/list.php');
exit;

// 🎯 공통 유틸: 영상 ID 추출 함수
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


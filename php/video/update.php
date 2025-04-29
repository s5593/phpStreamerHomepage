<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)', '/html/video/list.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('잘못된 요청입니다. (CSRF 실패)', '/html/video/list.php');
}

// 로그인 사용자
$user_id = current_user_no();
if (!$user_id) {
    redirect_with_message('로그인이 필요합니다.', '/html/video/list.php');
}

// 입력값 필터링
$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');

// 유효성 검사
if ($id <= 0 || !$subject || !$video_url) {
    redirect_with_message('필수 입력값이 누락되었습니다.', '/html/video/list.php');
}

if (mb_strlen($subject) > 100 || mb_strlen($video_url) > 255) {
    redirect_with_message('입력값이 너무 깁니다.', '/html/video/list.php');
}

// URL 유효성 검사
if (!preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be|chzzk\.naver\.com)/i', $video_url)) {
    redirect_with_message('유효한 YouTube 또는 치지직 URL만 등록 가능합니다.', '/html/video/list.php');
}

// 게시글 존재 및 작성자 확인
$stmt = $conn->prepare("
    SELECT user_id
    FROM board_video
    WHERE id = ? AND is_use = 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    redirect_with_message('존재하지 않는 게시글입니다.', '/html/video/list.php');
}

if ($user_id !== intval($post['user_id'])) {
    redirect_with_message('수정 권한이 없습니다.', '/html/video/list.php');
}

// DB 업데이트
$stmt = $conn->prepare("
    UPDATE board_video
    SET subject = ?, video_url = ?, updated_at = NOW()
    WHERE id = ? AND is_use = 1
");
$stmt->bind_param("ssi", $subject, $video_url, $id);

if (!$stmt->execute()) {
    error_log("영상 수정 실패: " . $stmt->error);
    redirect_with_message('영상 수정에 실패했습니다. 다시 시도해주세요.', '/html/video/list.php');
}

$stmt->close();

// 성공 메시지 후 목록 이동
redirect_with_message('영상이 성공적으로 수정되었습니다.', '/html/video/list.php');
exit;

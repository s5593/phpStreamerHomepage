<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다. (POST Only)','/html/video/write.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    redirect_with_message('잘못된 요청입니다. (CSRF 실패)','/html/video/write.php');
}

// 로그인 사용자
$user_id = current_user_no();  // 회원번호
$writer = current_user_id();      // 사용자 ID (문자열)

if (!$user_id || !$writer) {
    redirect_with_message('로그인이 필요합니다.','/html/video/write.php');
}

// 입력값 필터링
$subject = trim($_POST['subject'] ?? '');
$video_url = trim($_POST['video_url'] ?? '');

// 유효성 검사
if (!$subject || !$video_url) {
    redirect_with_message('제목과 URL을 모두 입력해주세요.','/html/video/write.php');
}

if (mb_strlen($subject) > 100 || mb_strlen($video_url) > 255) {
    redirect_with_message('입력값이 너무 깁니다.','/html/video/write.php');
}

// URL 유효성 검사 (YouTube / 치지직만 허용)
if (!preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be|chzzk\.naver\.com)/i', $video_url)) {
    redirect_with_message('유효한 YouTube 또는 치지직 URL만 등록 가능합니다.','/html/video/write.php');
}

// DB 삽입
$stmt = $conn->prepare("
    INSERT INTO board_video (subject, video_url, user_id, created_at, updated_at, is_use)
    VALUES (?, ?, ?, NOW(), NOW(), 1)
");
$stmt->bind_param("ssi", $subject, $video_url, $user_id);

if (!$stmt->execute()) {
    error_log("영상 등록 실패: " . $stmt->error);
    redirect_with_message('영상 등록에 실패했습니다. 다시 시도해주세요.','/html/video/write.php');
}

$stmt->close();

// 성공 시 메시지 출력하고 목록으로 이동
redirect_with_message('영상이 성공적으로 등록되었습니다.','/html/video/list.php');
exit;

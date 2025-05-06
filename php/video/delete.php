<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('잘못된 접근입니다. (POST Only)');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    exit('잘못된 요청입니다. (CSRF 실패)');
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    exit('잘못된 요청입니다. (ID 오류)');
}

// 로그인 정보
$user_id = current_user_id();
$mb_level = current_user_level();

// 기존 게시물 확인
$stmt = $conn->prepare("SELECT user_id FROM board_video WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    exit('삭제할 게시물이 존재하지 않습니다.');
}

// 권한 체크
if ($post['user_id'] !== $user_id && $mb_level < 3) {
    exit('삭제 권한이 없습니다.');
}

// 소프트 삭제 처리
$stmt = $conn->prepare("UPDATE board_video SET is_use = 0, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    error_log("영상 삭제 실패: " . $stmt->error);
    exit('삭제 중 오류가 발생했습니다.');
}

$stmt->close();

// 목록으로 이동
redirect_to("/html/video/list.php");
exit;
<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'POST only']);
    exit;
}

$user_id = current_user_no();
$video_id = intval($_POST['video_id'] ?? 0);

if (!$user_id || $video_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요하거나 잘못된 요청입니다.']);
    exit;
}

// 좋아요 토글 처리
$liked = false;
$stmt = $conn->prepare("SELECT 1 FROM video_likes WHERE video_id = ? AND user_id = ?");
$stmt->bind_param("ii", $video_id, $user_id);
$stmt->execute();
$result = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($result) {
    // 이미 좋아요 → 삭제
    $stmt = $conn->prepare("DELETE FROM video_likes WHERE video_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $video_id, $user_id);
    $stmt->execute();
    $stmt->close();
} else {
    // 좋아요 등록
    $stmt = $conn->prepare("INSERT INTO video_likes (video_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $video_id, $user_id);
    $stmt->execute();
    $stmt->close();
    $liked = true;
}

// 최신 좋아요 수 반환
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM video_likes WHERE video_id = ?");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

echo json_encode(['status' => 'ok', 'liked' => $liked, 'count' => $count]);

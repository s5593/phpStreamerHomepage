<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php'); // 공통 권한 함수

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 접근입니다.','/html/wiki/write.php');
}

if (!is_admin()) {
    redirect_with_message('접근 권한이 없습니다.','/html/wiki/write.php');
}

// CSRF 토큰 검증
$csrf_token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf_token)) {
    redirect_with_message('올바르지 않은 요청입니다.','/html/wiki/write.php');
}

// 입력값 가져오기
$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($subject === '' || $content === '') {
    redirect_with_message('제목과 본문을 모두 입력해주세요.','/html/wiki/write.php');
}

// DB 저장
$stmt = $conn->prepare("
    INSERT INTO board_wiki (subject, content, is_use, created_at)
    VALUES (?, ?, 1, NOW())
");
$stmt->bind_param("ss", $subject, $content);
$exec = $stmt->execute();
$insert_id = $conn->insert_id;
$stmt->close();

if ($exec) {
    redirect_to("/html/wiki/view.php?id=" . $insert_id);
} else {
    redirect_with_message('저장에 실패했습니다.','/html/wiki/write.php');
}
?>

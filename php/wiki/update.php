<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

// 권한 체크
if (!is_admin()) {
    redirect_with_message('접근 권한이 없습니다.',"/html/wiki/edit.php?id={$id}");
}

// POST 방식 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('잘못된 요청입니다.',"/html/wiki/edit.php?id={$id}");
}

// CSRF 토큰 검증
$csrf_token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf_token)) {
    redirect_with_message('올바르지 않은 요청입니다.',"/html/wiki/edit.php?id={$id}");
}

// 입력값 받기
$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$content = trim($_POST['content'] ?? '');

// 입력값 검증
if ($id <= 0) {
    redirect_with_message('잘못된 접근입니다.',"/html/wiki/edit.php?id={$id}");
}

if ($subject === '' || $content === '') {
    redirect_with_message('제목과 내용을 모두 입력해주세요.',"/html/wiki/edit.php?id={$id}");
}

// 수정 대상 글 확인
$stmt = $conn->prepare("SELECT id FROM board_wiki WHERE id = ? AND is_use = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$post = $result->fetch_assoc();

if (!$post) {
    redirect_with_message('존재하지 않는 문서이거나 수정할 수 없습니다.',"/html/wiki/edit.php?id={$id}");
}

// DB 수정 처리
$stmt = $conn->prepare("
    UPDATE board_wiki
    SET subject = ?, content = ?, updated_at = NOW()
    WHERE id = ? AND is_use = 1
");
$stmt->bind_param("ssi", $subject, $content, $id);
$success = $stmt->execute();
$stmt->close();

// 결과 처리
if ($success) {
    redirect_to("/html/wiki/view.php?id={$id}");
} else {
    redirect_with_message('문서 수정에 실패했습니다.',"/html/wiki/edit.php?id={$id}");
}
?>

<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_with_message('잘못된 접근입니다.',"/html/notice/edit.php?id="+$id);
if ($_SESSION['mb_level'] < 3) redirect_with_message('권한이 없습니다.',"/html/notice/edit.php?id="+$id);
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) redirect_with_message('CSRF 오류',"/html/notice/edit.php?id="+$id);

$id = intval($_POST['id'] ?? 0);
$subject = trim($_POST['subject'] ?? '');
$content = $_POST['content'] ?? '';

// 필수값 체크
if (!$id || !$subject || !$content) {
    redirect_with_message('입력값이 부족합니다.',"/html/notice/edit.php?id="+$id);
}

// DB 업데이트
$stmt = $conn->prepare("UPDATE board_notice SET subject = ?, content = ? WHERE id = ?");
$stmt->bind_param("ssi", $subject, $content, $id);
$stmt->execute();
$stmt->close();

// 보기 페이지로 돌아감
echo "<form id='viewForm' method='POST' action='/html/notice/view.php'>
    <input type='hidden' name='id' value='{$id}'>
    <input type='hidden' name='csrf_token' value='" . generate_csrf_token() . "'>
</form>
<script>document.getElementById('viewForm').submit();</script>";

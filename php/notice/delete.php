<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_with_message('잘못된 접근입니다.',"/html/notice/view.php?id="+$id);
if ($_SESSION['mb_level'] < 3) redirect_with_message('권한이 없습니다.',"/html/notice/view.php?id="+$id);
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) redirect_with_message('CSRF 오류',"/html/notice/view.php?id="+$id);

$id = intval($_POST['id'] ?? 0);
if (!$id) redirect_with_message('유효하지 않은 요청입니다.',"/html/notice/view.php?id="+$id);

// 글 삭제 처리 (is_use = 0)
$stmt = $conn->prepare("UPDATE board_notice SET is_use = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// 목록으로 이동
echo "<form id='goList' method='POST' action='/html/notice/list.php'>
    <input type='hidden' name='csrf_token' value='" . generate_csrf_token() . "'>
</form>
<script>document.getElementById('goList').submit();</script>";

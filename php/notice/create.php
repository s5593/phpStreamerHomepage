<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect_with_message('잘못된 접근입니다.',"/html/notice/write.php");
if ($_SESSION['mb_level'] < 3) redirect_with_message('권한이 없습니다.',"/html/notice/write.php");
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) redirect_with_message('CSRF 오류',"/html/notice/write.php");

$subject = trim($_POST['subject'] ?? '');
$content = $_POST['content'] ?? '';
$author_id = $_SESSION['mb_id'] ?? '';

// 필수값 확인
if (!$subject || !$content || !$author_id) {
    redirect_with_message('입력값이 부족하거나 로그인 정보가 유효하지 않습니다.',"/html/notice/write.php");
}

// 저장 처리
$stmt = $conn->prepare("INSERT INTO board_notice (subject, content, author_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $subject, $content, $author_id);
$stmt->execute();
$stmt->close();

// 목록으로 POST 리디렉션
echo "<form id='goList' method='POST' action='/html/notice/list.php'>
    <input type='hidden' name='csrf_token' value='" . generate_csrf_token() . "'>
</form>
<script>document.getElementById('goList').submit();</script>";

<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) show_alert_and_back('잘못된 접근입니다.');

$stmt = $conn->prepare("
  SELECT * FROM board_user_post
  WHERE id = ? AND is_use = 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) show_alert_and_back('존재하지 않는 게시글입니다.');

if (current_user_no() !== intval($post['user_id'])) {
    show_alert_and_back('수정 권한이 없습니다.');
}

$csrf_token = generate_csrf_token();
include_once(__DIR__ . '/edit_form.html.php');

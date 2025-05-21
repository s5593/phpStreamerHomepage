<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) show_alert_and_back('잘못된 접근입니다.');

$stmt = $conn->prepare("
  SELECT P.*, M.mb_id
  FROM board_user_post P
  JOIN g5_member M ON P.user_id = M.mb_no
  WHERE P.id = ? AND P.is_use = 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) show_alert_and_back('존재하지 않는 게시글입니다.');

$can_edit = current_user_no() === intval($post['user_id']);

include_once(__DIR__ . '/view.html.php');

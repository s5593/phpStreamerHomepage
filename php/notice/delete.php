<?php
include_once(__DIR__ . '/../../lib/common.php');

if ($_SESSION['mb_level'] < 3) exit('권한 없음');
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("UPDATE board_notice SET is_use = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: /html/notice/list.php");
exit;

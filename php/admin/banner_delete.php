<?php
include_once(__DIR__ . '/../../lib/common.php');

// GET으로 배너 ID 받기
$bn_id = (int)($_GET['id'] ?? 0);

if ($bn_id <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// is_active = 0으로 비활성화 처리
$stmt = $conn->prepare("UPDATE board_banner SET bn_is_active = 0 WHERE bn_id = ?");
$stmt->bind_param("i", $bn_id);

if ($stmt->execute()) {
    echo "<script>alert('배너가 삭제(비활성화)되었습니다.'); history.back();</script>";
} else {
    error_log('배너 삭제 실패: ' . $stmt->error);
    echo "<script>alert('배너 삭제에 실패했습니다.'); history.back();</script>";
}

$stmt->close();
?>

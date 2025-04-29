<?php
include_once(__DIR__ . '/../../db/dbconn.php');

// 관리자 인증
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['mb_level']) || $_SESSION['mb_level'] < 3) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='/';</script>";
    exit;
}

$mb_id = trim($_POST['mb_id'] ?? '');
$mb_level = (int)($_POST['mb_level'] ?? 1);

if ($mb_id === '') {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit;
}

$stmt = $conn->prepare("UPDATE g5_member SET mb_level = ? WHERE mb_id = ?");
$stmt->bind_param("is", $mb_level, $mb_id);

if ($stmt->execute()) {
    echo "<script>alert('회원 레벨이 수정되었습니다.'); location.href='../../html/admin/member_list.php';</script>";
} else {
    error_log('회원 레벨 수정 실패: ' . $stmt->error);
    echo "<script>alert('회원 레벨 수정에 실패했습니다.'); history.back();</script>";
}

$stmt->close();
?>

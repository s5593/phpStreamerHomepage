<?php
include_once(__DIR__ . '/../../db/dbconn.php');

// POST 데이터 안전하게 받기
$bn_title = trim($_POST['bn_title'] ?? '');
$bn_link = trim($_POST['bn_link'] ?? '');
$bn_sort = (int)($_POST['bn_sort'] ?? 0);
$bn_is_active = (int)($_POST['bn_is_active'] ?? 1);

// 필수 체크
if (empty($bn_title)) {
    echo "<script>alert('제목을 입력해주세요.'); history.back();</script>";
    exit;
}

// 이미지 업로드 처리
if (!isset($_FILES['bn_image']) || $_FILES['bn_image']['error'] !== UPLOAD_ERR_OK) {
    echo "<script>alert('이미지 업로드에 실패했습니다.'); history.back();</script>";
    exit;
}

// 이미지 저장
$upload_dir = __DIR__ . '/../../uploads/banner/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // 폴더 없으면 생성
}

$ext = pathinfo($_FILES['bn_image']['name'], PATHINFO_EXTENSION);
$new_filename = uniqid('banner_', true) . '.' . strtolower($ext);
$target_path = $upload_dir . $new_filename;

if (!move_uploaded_file($_FILES['bn_image']['tmp_name'], $target_path)) {
    echo "<script>alert('파일 저장에 실패했습니다.'); history.back();</script>";
    exit;
}

// DB에 저장 (주의: bn_image에는 웹에서 접근 가능한 경로를 넣어야 함)
$web_image_path = '/uploads/banner/' . $new_filename;

$stmt = $conn->prepare("INSERT INTO board_banner (bn_title, bn_image, bn_link, bn_sort, bn_is_active, bn_datetime) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssii", $bn_title, $web_image_path, $bn_link, $bn_sort, $bn_is_active);

if ($stmt->execute()) {
    echo "<script>alert('배너가 등록되었습니다.'); location.href='../../html/admin/banner_list.php';</script>";
} else {
    error_log('배너 등록 실패: ' . $stmt->error);
    echo "<script>alert('배너 등록에 실패했습니다.'); history.back();</script>";
}

$stmt->close();
?>

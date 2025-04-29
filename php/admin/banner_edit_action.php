<?php
include_once(__DIR__ . '/../../db/dbconn.php');

// POST 데이터 받기
$bn_id = (int)($_POST['bn_id'] ?? 0);
$bn_title = trim($_POST['bn_title'] ?? '');
$bn_link = trim($_POST['bn_link'] ?? '');
$bn_sort = (int)($_POST['bn_sort'] ?? 0);
$bn_is_active = (int)($_POST['bn_is_active'] ?? 1);

// 필수 체크
if ($bn_id <= 0 || empty($bn_title)) {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit;
}

// 기존 데이터 가져오기
$stmt = $conn->prepare("SELECT bn_image FROM board_banner WHERE bn_id = ?");
$stmt->bind_param("i", $bn_id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

if (!$banner) {
    echo "<script>alert('존재하지 않는 배너입니다.'); history.back();</script>";
    exit;
}

$current_image = $banner['bn_image']; // 기존 이미지 경로

$stmt->close();

// 새 이미지 업로드 처리
$new_image_path = $current_image; // 기본은 기존 이미지 유지

if (isset($_FILES['bn_image']) && $_FILES['bn_image']['error'] === UPLOAD_ERR_OK) {
    // 새 이미지 업로드 요청이 있으면

    $upload_dir = __DIR__ . '/../../uploads/banner/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $ext = pathinfo($_FILES['bn_image']['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('banner_', true) . '.' . strtolower($ext);
    $target_path = $upload_dir . $new_filename;

    if (move_uploaded_file($_FILES['bn_image']['tmp_name'], $target_path)) {
        $new_image_path = '/uploads/banner/' . $new_filename;
        // (선택) 기존 이미지 파일 삭제는 여기는 아직 안 함
    } else {
        echo "<script>alert('새 이미지 업로드에 실패했습니다.'); history.back();</script>";
        exit;
    }
}

// DB 업데이트
$stmt = $conn->prepare("UPDATE board_banner SET bn_title = ?, bn_image = ?, bn_link = ?, bn_sort = ?, bn_is_active = ?, bn_datetime = NOW() WHERE bn_id = ?");
$stmt->bind_param("sssiii", $bn_title, $new_image_path, $bn_link, $bn_sort, $bn_is_active, $bn_id);

if ($stmt->execute()) {
    echo "<script>alert('배너가 수정되었습니다.'); location.href='../../html/admin/banner_list.php';</script>";
} else {
    error_log('배너 수정 실패: ' . $stmt->error);
    echo "<script>alert('배너 수정에 실패했습니다.'); history.back();</script>";
}

$stmt->close();
?>

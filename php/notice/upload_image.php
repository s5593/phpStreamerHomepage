<?php
include_once(__DIR__ . '/../../lib/common.php');

$upload_dir = __DIR__ . '/../../uploads/notice/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

// MIME 허용 리스트
$allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '이미지가 전송되지 않았습니다.']);
    exit;
}

$image = $_FILES['image'];
$tmp = $image['tmp_name'];
$mime = mime_content_type($tmp);

// MIME 검증
if (!in_array($mime, $allowed_mime)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '허용되지 않은 이미지 형식입니다.']);
    exit;
}

// 확장자 결정
$ext = match($mime) {
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
};

// 파일 저장
$filename = uniqid('img_') . '.' . $ext;
$target = $upload_dir . $filename;
$web_path = '/uploads/notice/' . $filename;

if (move_uploaded_file($tmp, $target)) {
    echo json_encode(['success' => true, 'url' => $web_path]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '업로드에 실패했습니다.']);
}

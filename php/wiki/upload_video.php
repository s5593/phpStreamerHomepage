<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

$file = $_FILES['file'];
$maxSize = 50 * 1024 * 1024; // 50MB
$uploadDir = __DIR__ . '/../../uploads/wiki/';
$uploadUrl = '/uploads/wiki/';

// 확장자 및 MIME 체크
$allowedExt = ['mp4'];
$allowedMime = ['video/mp4'];

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$mime = mime_content_type($file['tmp_name']);

if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
    echo json_encode(['success' => false, 'message' => 'MP4 파일만 업로드 가능합니다.']);
    exit;
}

// 파일 사이즈 검사
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => '파일 용량 초과 (최대 50MB).']);
    exit;
}

// 파일명 무작위화
$newName = uniqid('video_', true) . '.' . $ext;
$targetPath = $uploadDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(['success' => false, 'message' => '파일 저장에 실패했습니다.']);
    exit;
}

// 반응형 <video> 태그 HTML 생성
$videoHtml = <<<HTML
<video src="{$uploadUrl}{$newName}" controls style="max-width: 100%;">
  브라우저가 video 태그를 지원하지 않습니다.
</video>
HTML;

echo json_encode(['success' => true, 'html' => $videoHtml]);

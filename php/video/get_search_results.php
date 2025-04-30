<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');
include_once(__DIR__ . '/../../lib/common_rate_limit.php');
include_once(__DIR__ . '/search_log.php');

// POST + JSON only
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// 요청 데이터 파싱
$data = json_decode(file_get_contents('php://input'), true);
$keyword = trim($data['keyword'] ?? '');
$sort = $data['sort'] ?? 'recent';

if (!$keyword || mb_strlen($keyword) > 100) {
    http_response_code(400);
    exit('검색어 오류');
}

// 검색어 필터링 (SQL 키워드 제한)
$deny_keywords = ['select', 'union', 'drop', 'insert', '--', ';'];
foreach ($deny_keywords as $deny) {
    if (stripos($keyword, $deny) !== false) {
        http_response_code(400);
        exit('금칙어 포함');
    }
}

// 검색 로그 저장
save_search_log($keyword);

// Rate Limit (IP or 사용자 기준 제한 - 예시)
$ip = $_SERVER['REMOTE_ADDR'];
rate_limit_check("video_search:$ip", 5, 10); // 10초에 5회 이하

// 정렬 기준
$order_by = match($sort) {
    'oldest' => 'created_at ASC',
    default => 'created_at DESC',
};

// 키워드 기반 검색
$stmt = $conn->prepare("
    SELECT id, subject
    FROM board_video
    WHERE is_use = 1 AND subject LIKE CONCAT('%', ?, '%')
    ORDER BY $order_by
    LIMIT 20
");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
while ($row = $result->fetch_assoc()) {
    $response[] = [
        'id' => $row['id'],
        'subject' => htmlspecialchars($row['subject']),
        'csrf_token' => generate_csrf_token(),
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

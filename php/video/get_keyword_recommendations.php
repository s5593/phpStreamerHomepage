<?php
include_once(__DIR__ . '/../../lib/common.php');

// 관리자 API가 아님 → 누구나 접근 가능
header('Content-Type: application/json');

$keywords = [];

// 인기 키워드 10개 추출
$stmt = $conn->prepare("
    SELECT keyword FROM video_search_stats
    ORDER BY count DESC
    LIMIT 10
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $kw = trim($row['keyword']);

    // 간단한 금칙어 필터링
    if (preg_match('/(욕|시발|fuck|sex)/i', $kw)) continue;

    $keywords[] = htmlspecialchars($kw);
}

$stmt->close();

echo json_encode(['keywords' => $keywords]);

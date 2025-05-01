<?php
include_once(__DIR__ . '/../../lib/common.php');

header('Content-Type: application/json');

$keywords = [];

// 게시글에서 keywords 수집
$result = $conn->query("SELECT keywords FROM board_video WHERE is_use = 1");

while ($row = $result->fetch_assoc()) {
    $raw = explode(',', $row['keywords']);
    foreach ($raw as $kw) {
        $word = trim($kw);
        if ($word && !preg_match('/(욕|fuck|sex|시발)/i', $word)) {
            $keywords[] = $word;
        }
    }
}

$result->close();

// 중복 제거 + 상위 10개만
$keywords = array_unique($keywords);
$keywords = array_slice($keywords, 0, 10);

echo json_encode(['keywords' => array_values($keywords)]);

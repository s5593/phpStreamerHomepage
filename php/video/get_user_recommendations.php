<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

header('Content-Type: application/json');

$user_id = current_user_id();
if (!$user_id) {
    echo json_encode(['keywords' => []]);
    exit;
}

$stmt = $conn->prepare("
    SELECT keyword
    FROM video_user_keyword
    WHERE user_id = ?
    ORDER BY count DESC
    LIMIT 10
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$keywords = [];
while ($row = $result->fetch_assoc()) {
    $kw = trim($row['keyword']);
    if (preg_match('/(욕|시발|fuck|sex)/i', $kw)) continue;
    $keywords[] = htmlspecialchars($kw);
}

$stmt->close();

echo json_encode(['keywords' => $keywords]);

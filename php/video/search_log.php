<?php
// 외부에서 직접 접근하지 않도록 차단
if (!defined('__SEARCH_LOG__')) define('__SEARCH_LOG__', true);

function save_search_log(string $keyword): void {
    global $conn;

    $keyword = trim($keyword);
    if (!$keyword || mb_strlen($keyword) > 100) return;

    $user_id = current_user_id();
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    // 1. 검색 로그 기록
    $stmt = $conn->prepare("
        INSERT INTO video_search_log (keyword, user_id, ip_address, user_agent, referer_url, searched_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("sisss", $keyword, $user_id, $ip, $agent, $referer);
    $stmt->execute();
    $stmt->close();

    // 2. 검색어 통계 증가
    $stmt = $conn->prepare("
        INSERT INTO video_search_stats (keyword, count)
        VALUES (?, 1)
        ON DUPLICATE KEY UPDATE count = count + 1
    ");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $stmt->close();

    // 3. 개인화 키워드 저장 (로그인 사용자만)
    if ($user_id) {
        $stmt = $conn->prepare("
            INSERT INTO video_user_keyword (user_id, keyword, count)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE count = count + 1
        ");
        $stmt->bind_param("is", $user_id, $keyword);
        $stmt->execute();
        $stmt->close();
    }
}

<?php
function rate_limit_check(string $key, int $maxCount, int $seconds): void {
    if (!isset($_SESSION)) session_start();

    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }

    $now = time();
    $data = $_SESSION['rate_limit'][$key] ?? ['count' => 0, 'start' => $now];

    if ($now - $data['start'] < $seconds) {
        if ($data['count'] >= $maxCount) {
            http_response_code(429); // Too Many Requests
            exit('요청이 너무 많습니다. 잠시 후 다시 시도해주세요.');
        }
        $data['count']++;
    } else {
        $data = ['count' => 1, 'start' => $now];
    }

    $_SESSION['rate_limit'][$key] = $data;
}

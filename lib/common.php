<?php
if (session_status() === PHP_SESSION_NONE) {
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $is_https, // 로컬에서는 false, HTTPS에서는 true
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// ✅ 세션 하이재킹 방지 검사 (로그인한 경우에만)
if (isset($_SESSION['mb_id'])) {
    if (
        ($_SESSION['login_ip'] ?? '') !== $_SERVER['REMOTE_ADDR'] ||
        ($_SESSION['login_ua'] ?? '') !== ($_SERVER['HTTP_USER_AGENT'] ?? '')
    ) {
        session_unset();
        session_destroy();
        redirect_error("세션이 위조되었거나 만료되었습니다.");
    }
}

include_once(__DIR__ . '/../db/dbconn.php');

function log_error($msg) {
    $msg = "[" . date("Y-m-d H:i:s") . "] " . mb_convert_encoding($msg, 'UTF-8', 'auto') . "\n";
    file_put_contents(__DIR__ . '/../logs/error.log', $msg, FILE_APPEND);
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function get_base_url() {
    if (php_sapi_name() === 'cli' && strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        // CLI 환경에서는 고정값 또는 .env 기반 경로 반환
        return 'http://localhost'; // 또는 https://yourdomain.com
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'];
}

function redirect_to($relative_path) {
    header("Location: " . get_base_url() . $relative_path);
    exit;
}

function redirect_with_message($message, $relative_path = '/') {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['_flash_message'] = $message;
    header("Location: " . get_base_url() . $relative_path);
    exit;
}

function get_flash_message() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (isset($_SESSION['_flash_message'])) {
        $msg = $_SESSION['_flash_message'];
        unset($_SESSION['_flash_message']);
        return $msg;
    }
    return null;
}

function redirect_error($msg) {
    $_SESSION['error_message'] = $msg;

    // 프로젝트 루트에 맞게 절대 경로 생성
    $base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https://" : "http://")
          . $_SERVER['HTTP_HOST'];

          redirect_to("/error/error.php");
    exit;
}

function show_alert_and_back($message) {
    echo "<script>alert(" . json_encode($message) . "); history.back();</script>";
    exit;
}
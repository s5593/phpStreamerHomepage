<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__ . '/../db/dbconn.php');

function log_error($msg) {
    $log_path = __DIR__ . '/../logs/error.log';
    $msg = "[" . date("Y-m-d H:i:s") . "] " . $msg . "\n";
    file_put_contents($log_path, $msg, FILE_APPEND);
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

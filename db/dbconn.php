<?php
include_once(__DIR__ . '/db_config.php');
load_env(__DIR__ . '/../.env');

$conn = new mysqli(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_NAME'),
    getenv('DB_PORT') ?: 3306
);

if ($conn->connect_error) {
    error_log("DB 연결 실패: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

<?php
// 안전하게 DB 연결 불러오기
$path = realpath(__DIR__ . '/../../db/dbconn.php');
if ($path && file_exists($path)) {
    include_once($path);
} else {
    error_log("DB 연결 파일을 찾을 수 없습니다: $path");
    exit('서버 오류가 발생했습니다. 관리자에게 문의해주세요.');
}


$sql = "SELECT * FROM board_notice WHERE is_use = 1 ORDER BY created_at DESC LIMIT 6";
$result = $conn->query($sql);

$notices = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}
?>

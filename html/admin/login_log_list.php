<?php
include_once(__DIR__ . '/../../db/dbconn.php');
include_once(__DIR__ . '/../../lib/common.php');

// 관리자 인증
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['mb_level']) || $_SESSION['mb_level'] < 3) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='/';</script>";
    exit;
}

// 검색어 받기
$search_keyword = trim($_GET['search'] ?? '');

// 기본 쿼리
$sql = "SELECT * FROM g5_login_log";
$params = [];
$where = [];

if ($search_keyword !== '') {
    $where[] = "(mb_id LIKE CONCAT('%', ?, '%') OR ip_address LIKE CONCAT('%', ?, '%'))";
    $params[] = $search_keyword;
    $params[] = $search_keyword;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY login_datetime DESC LIMIT 100"; // 최근 100개만 보기

$stmt = $conn->prepare($sql);

// 파라미터 바인딩
if (!empty($params)) {
    $types = str_repeat('s', count($params)); // 모두 문자열
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$logs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인 기록</title>
    <link rel="stylesheet" href="/css/admin/style.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin-log__title">로그인 기록</h2>

    <div class="admin-log__tabs">
    <a href="banner_list.php" class="admin-log__tab <?= basename($_SERVER['PHP_SELF']) === 'banner_list.php' ? 'active' : '' ?>">배너 관리</a>
    <a href="login_log_list.php" class="admin-log__tab <?= basename($_SERVER['PHP_SELF']) === 'login_log_list.php' ? 'active' : '' ?>">로그인 기록</a>
    <a href="member_list.php" class="admin-log__tab <?= basename($_SERVER['PHP_SELF']) === 'member_list.php' ? 'active' : '' ?>">회원 관리</a>
    </div>

    <form method="get" action="login_log_list.php" class="admin-log__search-form">
    <input type="text" name="search" placeholder="ID 또는 IP 검색" value="<?= htmlspecialchars($search_keyword) ?>" class="admin-log__search-input">
    <button type="submit" class="admin-log__btn">검색</button>
    <a href="login_log_list.php" class="admin-log__btn admin-log__btn--reset">초기화</a>
    </form>

    <table class="admin-log__table">
    <thead>
        <tr>
        <th>ID</th>
        <th>아이디</th>
        <th>IP 주소</th>
        <th>브라우저 정보</th>
        <th>로그인 시간</th>
        <th>성공 여부</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($logs) > 0): ?>
        <?php foreach ($logs as $log): ?>
            <tr>
            <td><?= htmlspecialchars($log['id']) ?></td>
            <td><?= htmlspecialchars($log['mb_id']) ?></td>
            <td><?= htmlspecialchars($log['ip_address']) ?></td>
            <td><?= htmlspecialchars($log['user_agent']) ?></td>
            <td><?= htmlspecialchars($log['login_datetime']) ?></td>
            <td><?= $log['success'] === 'Y' ? '성공' : '실패' ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr><td colspan="6" class="admin-log__empty">로그 기록이 없습니다.</td></tr>
        <?php endif; ?>
    </tbody>
    </table>

    <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

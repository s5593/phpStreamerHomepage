<?php
include_once(__DIR__ . '/../../db/dbconn.php');
include_once(__DIR__ . '/../../lib/common.php');

// 관리자 인증
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['mb_level']) || $_SESSION['mb_level'] < 3) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='/';</script>";
    exit;
}

// 회원 목록 가져오기
$sql = "SELECT mb_no, mb_id, mb_level FROM g5_member ORDER BY mb_level DESC, mb_no ASC";
$result = $conn->query($sql);

$members = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 관리</title>
    <link rel="stylesheet" href="/css/admin/admin_member.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>

<h2>회원 관리</h2>
<div class="admin-tabs">
    <a href="banner_list.php" class="<?= basename($_SERVER['PHP_SELF']) === 'banner_list.php' ? 'active' : '' ?>">배너 관리</a>
    <a href="login_log_list.php" class="<?= basename($_SERVER['PHP_SELF']) === 'login_log_list.php' ? 'active' : '' ?>">로그인 기록</a>
    <a href="member_list.php" class="<?= basename($_SERVER['PHP_SELF']) === 'member_list.php' ? 'active' : '' ?>">회원 관리</a>
</div>

<table class="member-table">
    <thead>
        <tr>
            <th>회원번호호</th>
            <th>아이디</th>
            <th>레벨</th>
            <th>관리</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($members) > 0): ?>
            <?php foreach ($members as $member): ?>
                <tr>
                    <td><?= htmlspecialchars($member['mb_no']) ?></td>
                    <td><?= htmlspecialchars($member['mb_id']) ?></td>
                    <td><?= htmlspecialchars($member['mb_level']) ?></td>
                    <td>
                        <a href="member_edit.php?mb_id=<?= urlencode($member['mb_id']) ?>" class="btn btn-edit">레벨 수정</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">회원이 없습니다.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

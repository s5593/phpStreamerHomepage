<?php
include_once(__DIR__ . '/../../db/dbconn.php');
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!is_admin()) exit('접근권한 없음');

// 배너 가져오기
$sql = "SELECT * FROM board_banner ORDER BY bn_sort ASC, bn_datetime DESC";
$result = $conn->query($sql);

$banners = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>배너 관리</title>
    <link rel="stylesheet" href="/css/admin/style.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin-banner__title">배너 관리</h2>

    <div class="admin-banner__tabs">
    <a href="banner_list.php" class="admin-banner__tab <?= basename($_SERVER['PHP_SELF']) === 'banner_list.php' ? 'active' : '' ?>">배너 관리</a>
    <a href="login_log_list.php" class="admin-banner__tab <?= basename($_SERVER['PHP_SELF']) === 'login_log_list.php' ? 'active' : '' ?>">로그인 기록</a>
    <a href="member_list.php" class="admin-banner__tab <?= basename($_SERVER['PHP_SELF']) === 'member_list.php' ? 'active' : '' ?>">회원 관리</a>
    </div>

    <a href="banner_write.php" class="admin-banner__btn admin-banner__btn--new">+ 새 배너 추가</a>

    <table class="admin-banner__table">
    <thead>
        <tr>
        <th>번호</th>
        <th>썸네일</th>
        <th>제목</th>
        <th>링크</th>
        <th>정렬</th>
        <th>활성화</th>
        <th>등록일</th>
        <th>관리</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($banners) > 0): ?>
        <?php foreach ($banners as $banner): ?>
            <tr>
            <td><?= htmlspecialchars($banner['bn_id']) ?></td>
            <td><img src="<?= htmlspecialchars($banner['bn_image']) ?>" alt="썸네일" class="admin-banner__thumbnail"></td>
            <td><?= htmlspecialchars($banner['bn_title']) ?></td>
            <td><a href="<?= htmlspecialchars($banner['bn_link']) ?>" target="_blank">링크</a></td>
            <td><?= htmlspecialchars($banner['bn_sort']) ?></td>
            <td><?= $banner['bn_is_active'] ? 'O' : 'X' ?></td>
            <td><?= htmlspecialchars($banner['bn_datetime']) ?></td>
            <td>
                <a href="banner_edit.php?id=<?= $banner['bn_id'] ?>" class="admin-banner__btn admin-banner__btn--edit">수정</a>
                <a href="banner_delete.php?id=<?= $banner['bn_id'] ?>" class="admin-banner__btn admin-banner__btn--delete" data-id="<?= $banner['bn_id'] ?>">삭제</a>
            </td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr><td colspan="8" class="admin-banner__empty">등록된 배너가 없습니다.</td></tr>
        <?php endif; ?>
    </tbody>
    </table>

    <script src="/js/admin/admin_banner.js"></script>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

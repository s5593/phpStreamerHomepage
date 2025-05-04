<?php
// 관리자 인증 체크 (생략 가능)
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');
if (!is_admin()) exit('접근권한 없음');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>배너 등록</title>
    <link rel="stylesheet" href="/css/admin/admin_banner.css">
</head>
<body>
    <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin-banner__title">배너 등록</h2>

    <form action="../../php/admin/banner_write_action.php" method="post" enctype="multipart/form-data" class="admin-banner__form">

    <div class="admin-banner__group">
        <label for="bn_title" class="admin-banner__label">제목</label>
        <input type="text" name="bn_title" id="bn_title" required class="admin-banner__input">
    </div>

    <div class="admin-banner__group">
        <label for="bn_image" class="admin-banner__label">이미지 업로드</label>
        <input type="file" name="bn_image" id="bn_image" accept="image/*" required class="admin-banner__input">
        <small class="admin-banner__note">이미지는 16:9 비율로 올려주세요.</small>
    </div>

    <div class="admin-banner__group">
        <label for="bn_link" class="admin-banner__label">링크 URL</label>
        <input type="url" name="bn_link" id="bn_link" placeholder="https://example.com" class="admin-banner__input">
    </div>

    <div class="admin-banner__group">
        <label for="bn_sort" class="admin-banner__label">정렬 순서</label>
        <input type="number" name="bn_sort" id="bn_sort" value="0" min="0" class="admin-banner__input">
    </div>

    <div class="admin-banner__group">
        <label for="bn_is_active" class="admin-banner__label">활성화 여부</label>
        <select name="bn_is_active" id="bn_is_active" class="admin-banner__select">
        <option value="1" selected>활성화</option>
        <option value="0">비활성화</option>
        </select>
    </div>

    <div class="admin-banner__button-group">
        <button type="submit" class="admin-banner__btn admin-banner__btn--submit">등록하기</button>
        <a href="banner_list.php" class="admin-banner__btn">목록으로</a>
    </div>
    </form>
    <script src="/js/admin/admin_banner.js"></script>
    <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

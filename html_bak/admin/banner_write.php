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
<h2>배너 등록</h2>

<form action="../../php/admin/banner_write_action.php" method="post" enctype="multipart/form-data" class="banner-form">
    <div>
        <label for="bn_title">제목</label><br>
        <input type="text" name="bn_title" id="bn_title" required>
    </div>
    <div>
        <label for="bn_image">이미지 업로드</label><br>
        <input type="file" name="bn_image" id="bn_image" accept="image/*" required><br>
        <small>이미지는 16:9 비율로 올려주세요.</small><br>
    </div>
    <div>
        <label for="bn_link">링크 URL</label><br>
        <input type="url" name="bn_link" id="bn_link" placeholder="https://example.com">
    </div>
    <div>
        <label for="bn_sort">정렬 순서</label><br>
        <input type="number" name="bn_sort" id="bn_sort" value="0" min="0">
    </div>
    <div>
        <label for="bn_is_active">활성화 여부</label><br>
        <select name="bn_is_active" id="bn_is_active">
            <option value="1" selected>활성화</option>
            <option value="0">비활성화</option>
        </select>
    </div>
    <div style="margin-top:15px;">
        <button type="submit" class="btn btn-new">등록하기</button>
        <a href="banner_list.php" class="btn">목록으로</a>
    </div>
</form>

<script src="/js/admin/admin_banner.js"></script>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

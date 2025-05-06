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
  <div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin-banner__title">배너 등록</h2>

    <form action="../../php/admin/banner_write_action.php" method="post" enctype="multipart/form-data" class="admin-banner__form">

      <div class="form__group">
        <label for="bn_title" class="form__label">제목</label>
        <input type="text" name="bn_title" id="bn_title" required class="input">
      </div>

      <div class="form__group">
        <label for="bn_image" class="form__label">이미지 업로드</label>
        <input type="file" name="bn_image" id="bn_image" accept="image/*" required class="input">
        <small class="form__note">이미지는 16:9 비율로 업로드 해주세요.</small>
      </div>

      <div class="form__group">
        <label for="bn_link" class="form__label">링크 URL</label>
        <input type="url" name="bn_link" id="bn_link" placeholder="https://example.com" class="input">
      </div>

      <div class="form__group">
        <label for="bn_sort" class="form__label">정렬 순서</label>
        <input type="number" name="bn_sort" id="bn_sort" value="0" min="0" class="input">
      </div>

      <div class="form__group">
        <label for="bn_is_active" class="form__label">활성화 여부</label>
        <select name="bn_is_active" id="bn_is_active" class="input">
          <option value="1" selected>활성화</option>
          <option value="0">비활성화</option>
        </select>
      </div>

      <div class="form__button-group">
        <button type="submit" class="button button--primary">등록하기</button>
        <a href="banner_list.php" class="button button--secondary">목록으로</a>
      </div>
    </form>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
  <script src="/js/admin/admin_banner.js"></script>
</body>

</html>

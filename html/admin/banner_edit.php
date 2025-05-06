<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$bn_id = (int)($_GET['id'] ?? 0);

// ID가 없으면 오류
if ($bn_id <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 기존 데이터 가져오기
$stmt = $conn->prepare("SELECT * FROM board_banner WHERE bn_id = ?");
$stmt->bind_param("i", $bn_id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

if (!$banner) {
    echo "<script>alert('존재하지 않는 배너입니다.'); history.back();</script>";
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>배너 수정</title>
    <link rel="stylesheet" href="/css/admin/admin_banner.css">
</head>
<body>
  <div class="page-container">
  <?php include_once(__DIR__ . '/../../header.php'); ?>
    <h2 class="admin-banner__title">배너 수정</h2>

    <form action="../../php/admin/banner_edit_action.php" method="post" enctype="multipart/form-data" class="admin-banner__form">
      <input type="hidden" name="bn_id" value="<?= htmlspecialchars($banner['bn_id']) ?>">

      <div class="form__group">
        <label for="bn_title" class="form__label">제목</label>
        <input type="text" name="bn_title" id="bn_title" value="<?= htmlspecialchars($banner['bn_title']) ?>" required class="input">
      </div>

      <div class="form__group">
        <label class="form__label">현재 이미지</label>
        <img src="<?= htmlspecialchars($banner['bn_image']) ?>" alt="현재 이미지" class="admin-banner__thumbnail">
        <small class="form__note">※ 새 이미지를 등록하면 기존 이미지는 덮어쓰기 됩니다.</small>
        <small class="form__note">이미지는 16:9 비율로 업로드 해주세요.</small>
        <input type="file" name="bn_image" id="bn_image" accept="image/*" class="input">
      </div>

      <div class="form__group">
        <label for="bn_link" class="form__label">링크 URL</label>
        <input type="url" name="bn_link" id="bn_link" value="<?= htmlspecialchars($banner['bn_link']) ?>" class="input">
      </div>

      <div class="form__group">
        <label for="bn_sort" class="form__label">정렬 순서</label>
        <input type="number" name="bn_sort" id="bn_sort" value="<?= htmlspecialchars($banner['bn_sort']) ?>" class="input">
      </div>

      <div class="form__group">
        <label for="bn_is_active" class="form__label">활성화 여부</label>
        <select name="bn_is_active" id="bn_is_active" class="input">
          <option value="1" <?= $banner['bn_is_active'] ? 'selected' : '' ?>>활성화</option>
          <option value="0" <?= !$banner['bn_is_active'] ? 'selected' : '' ?>>비활성화</option>
        </select>
      </div>

      <div class="form__button-group">
        <button type="submit" class="button button--primary">수정하기</button>
        <a href="banner_list.php" class="button button--secondary">목록으로</a>
      </div>
    </form>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
  <script src="/js/admin/admin_banner.js"></script>
</body>

</html>

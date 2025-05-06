<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

if (!is_logged_in()) {
  show_alert_and_back('로그인이 필요합니다.');
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>영상 등록</title>
  <link rel="stylesheet" href="/css/video/style.css">
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="page-container">
      <div class="video">
        <h1 class="video__title">영상 등록</h1>

        <form method="post" action="/php/video/create.php" class="form">
          <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

          <!-- 제목 -->
          <div class="form__group">
            <label for="subject" class="form__label">제목</label>
            <input type="text" id="subject" name="subject" class="input" required maxlength="100" placeholder="제목을 입력하세요">
          </div>

          <!-- 영상 URL -->
          <div class="form__group">
            <label for="video_url" class="form__label">영상 URL</label>
            <input type="text" id="video_url" name="video_url" class="input" required maxlength="255" placeholder="유튜브 URL 또는 치지직 URL">
          </div>

          <!-- 키워드 -->
          <div class="form__group">
            <label for="keywords" class="form__label">추천 키워드 <span class="form__hint">(쉼표로 구분)</span></label>
            <input type="text" id="keywords" name="keywords" class="input" placeholder="예: 액션,롤플레잉,트위치" value="<?= htmlspecialchars($keywords ?? '') ?>">
          </div>

          <!-- 버튼 -->
          <div class="button-group">
            <button type="submit" class="button button--primary">저장하기</button>
            <button type="button" class="button button--secondary" onclick="location.href='list.php'">목록으로</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

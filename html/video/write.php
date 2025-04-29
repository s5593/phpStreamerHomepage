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
<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="video-edit-container">
  <h1>영상 등록</h1>

  <form method="post" action="/php/video/create.php" class="video-form">
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

    <div class="form-group">
      <label for="subject">제목</label>
      <input type="text" id="subject" name="subject" required maxlength="100" placeholder="제목을 입력하세요">
    </div>

    <div class="form-group">
      <label for="video_url">영상 URL</label>
      <input type="text" id="video_url" name="video_url" required maxlength="255" placeholder="유튜브 URL 또는 치지직 URL">
    </div>

    <div class="video-buttons">
      <button type="submit" class="video-button edit">등록하기</button>
      <a href="/html/video/list.php" class="video-button list">목록으로</a>
    </div>
  </form>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

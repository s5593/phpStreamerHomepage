<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

// POST 진입 체크
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  show_alert_and_back('잘못된 접근입니다.');
}

// CSRF 검증
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
  show_alert_and_back('잘못된 접근입니다. (CSRF 검증 실패)');
}

// ID 확인
$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
  show_alert_and_back('잘못된 접근입니다. (ID 오류)');
}

// 게시글 조회
$stmt = $conn->prepare("
    SELECT V.*, M.mb_id
    FROM board_video V
    JOIN g5_member M ON V.user_id = M.mb_no
    WHERE V.id = ? AND V.is_use = 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
  show_alert_and_back('존재하지 않는 게시글입니다.');
}

// 수정 권한 체크
if (current_user_no() !== intval($post['user_id'])) {
  show_alert_and_back('수정 권한이 없습니다.');
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>영상 수정 - <?= htmlspecialchars($post['subject']) ?></title>
  <link rel="stylesheet" href="/css/video/style.css">
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="video-edit-container">
  <h1>영상 수정</h1>

  <form method="post" action="/php/video/update.php" class="video-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

    <div class="form-group">
      <label for="subject">제목</label>
      <input type="text" id="subject" name="subject" required maxlength="100" value="<?= htmlspecialchars($post['subject']) ?>">
    </div>

    <div class="form-group">
      <label for="video_url">영상 URL</label>
      <input type="text" id="video_url" name="video_url" required maxlength="255" value="<?= htmlspecialchars($post['video_url']) ?>">
    </div>

    <div class="video-buttons">
      <button type="submit" class="video-button edit">수정 완료</button>
      <a href="/html/video/list.php" class="video-button list">목록으로</a>
    </div>
  </form>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

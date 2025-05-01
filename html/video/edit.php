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

function restore_full_url($video_id) {
  if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id)) {
      // YouTube 기본 링크로 복원
      return "https://www.youtube.com/watch?v={$video_id}";
  }
  if (preg_match('/^[a-zA-Z0-9]+$/', $video_id)) {
      // 치지직 클립이라면
      return "https://chzzk.naver.com/clips/{$video_id}";
  }
  // 이미 URL이면 그대로 사용
  return $video_id;
}

$video_url = restore_full_url($post['video_url'] ?? '');

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
      <input type="text" id="video_url" name="video_url"
            value="<?= htmlspecialchars($video_url) ?>" required maxlength="255">
    </div>

    <div class="form-group">
      <label for="keywords">추천 키워드 (쉼표로 구분)</label>
      <input type="text" id="keywords" name="keywords" class="form-control" placeholder="예: 액션,롤플레잉,트위치" value="<?= htmlspecialchars($post['keywords']) ?>">
    </div>

    <div class="form-button-group">
      <button type="submit" class="save-button">저장하기</button>
      <button type="button" class="cancel-button" onclick="location.href='view.php?id=<?= $post['id'] ?>'">취소</button>
    </div>
  </form>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>
</body>
</html>

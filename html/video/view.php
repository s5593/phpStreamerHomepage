<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) show_alert_and_back('잘못된 접근입니다. (ID 없음)');

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

if (!$post) show_alert_and_back('존재하지 않는 영상입니다.');

$video_id = $post['video_url'];
$is_youtube = preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id); // 11자리 → YouTube
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['subject']) ?></title>
  <link rel="stylesheet" href="/css/video/style.css">
  <?php if (!$is_youtube): ?>
    <script src="https://player.chzzk.naver.com/resources/player-script.js"></script>
  <?php else: ?>
    <script src="https://www.youtube.com/iframe_api"></script>
  <?php endif; ?>
</head>
<body>
<?php include_once(__DIR__ . '/../../header.php'); ?>

<div class="video-view-container">
  <h1 class="video-title"><?= htmlspecialchars($post['subject']) ?></h1>
  <p class="video-writer">작성자: <?= htmlspecialchars($post['mb_id']) ?></p>
  <p class="video-date">등록일: <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></p>

  <div class="video-frame-wrapper">
    <?php if ($is_youtube): ?>
      <div id="youtube-player"></div>
    <?php else: ?>
      <div id="chzzk-player"></div>
    <?php endif; ?>
  </div>

  <div class="video-buttons">
    <?php if (current_user_no() === intval($post['user_id'])): ?>
      <form method="post" action="edit.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        <button type="submit" class="video-button edit">수정</button>
      </form>
    <?php endif; ?>
    
    <a href="list.php" class="video-button list">목록으로</a>
  </div>
</div>

<?php include_once(__DIR__ . '/../../footer.php'); ?>

<?php if ($is_youtube): ?>
<script>
  // 유튜브 API 로드 후 실행될 콜백 등록
  let ytPlayer;
  function onYouTubeIframeAPIReady() {
    ytPlayer = new YT.Player('youtube-player', {
      videoId: "<?= $video_id ?>",
      width: '100%',
      height: '100%',
      playerVars: {
        autoplay: 0,
        rel: 0,
        modestbranding: 1
      }
    });
  }
</script>
<?php else: ?>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    if (typeof ChzzkPlayer === 'undefined') {
      console.error("ChzzkPlayer is not loaded.");
      return;
    }

    new ChzzkPlayer({
      target: document.getElementById('chzzk-player'),
      channelId: "<?= htmlspecialchars($video_id) ?>",
      width: '100%',
      height: '100%'
    });
  });
</script>
<?php endif; ?>
</body>
</html>

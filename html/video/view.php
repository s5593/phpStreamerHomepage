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
$is_youtube = preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id); // YouTube는 11자리

$video_id = $post['id'];
$user_id = current_user_no();

$like_checked = false;
$like_count = 0;

if (current_user_no()) {
    $stmt = $conn->prepare("SELECT 1 FROM video_likes WHERE video_id = ? AND user_id = ?");
    $user_id = current_user_no();
    $stmt->bind_param("ii", $post['id'], $user_id);
    $stmt->execute();
    $like_checked = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    // 좋아요 수 조회
    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM video_likes WHERE video_id = ?");
    $stmt->bind_param("i", $post['id']);
    $stmt->execute();
    $like_count = $stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['subject']) ?></title>
  <link rel="stylesheet" href="/css/video/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php if ($is_youtube): ?>
    <script src="https://www.youtube.com/iframe_api"></script>
  <?php endif; ?>
</head>
<body>
  <div class="page-container">
    <?php include_once(__DIR__ . '/../../header.php'); ?>

    <div class="video">
      <h1 class="video__title"><?= htmlspecialchars($post['subject']) ?></h1>
      <p class="video__writer">작성자: <?= htmlspecialchars($post['mb_id']) ?></p>
      <p class="video__date">등록일: <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?></p>

      <?php if (!empty($post['keywords'])): ?>
        <div class="video__keywords">
          <?php
            $keywords = array_filter(array_map('trim', explode(',', $post['keywords'])));
            foreach ($keywords as $kw):
          ?>
            <button type="button" class="video__keyword-btn" disabled><?= htmlspecialchars($kw) ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="video__frame-wrapper">
        <?php if ($is_youtube): ?>
          <div id="youtube-player"></div>
        <?php else: ?>
          <iframe
            src="https://chzzk.naver.com/embed/clip/<?= htmlspecialchars($video_id) ?>"
            width="90%" height="450"
            frameborder="0"
            allow="autoplay; clipboard-write; web-share"
            allowfullscreen
            title="CHZZK Player">
          </iframe>
        <?php endif; ?>
      </div>

      <div class="video__like-wrapper">
        <form class="video__like-form">
          <button type="button" class="video__like-btn" data-video-id="<?= $post['id'] ?>">
            <i class="fa<?= $like_checked ? 's' : 'r' ?> fa-heart video__like-icon"></i>
            <span id="like-count"><?= $like_count ?></span>
          </button>
        </form>
      </div>

      <div class="button-group">
        <?php if (current_user_no() === intval($post['user_id'])): ?>
          <form method="post" action="edit.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <button type="submit" class="button button--edit">수정하기</button>
          </form>
          <form action="/php/video/delete.php" method="POST" class="video__form--inline" onsubmit="return confirm('삭제하시겠습니까?');" style="display:inline;">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <button type="submit" class="button button--delete">삭제</button>
          </form>
        <?php endif; ?>

        <a href="list.php" class="button button--secondary">목록으로</a>
      </div>
    </div>
</div>
<?php include_once(__DIR__ . '/../../footer.php'); ?>

<?php if ($is_youtube): ?>
<script>
  let ytPlayer;
  function onYouTubeIframeAPIReady() {
    ytPlayer = new YT.Player('youtube-player', {
      videoId: "<?= $video_id ?>",
      width: '100%',
      height: '450',
      playerVars: {
        autoplay: 0,
        rel: 0,
        modestbranding: 1
      }
    });
  }

</script>
<?php endif; ?>
</body>
<script src="/js/video/script.js"></script>
</html>

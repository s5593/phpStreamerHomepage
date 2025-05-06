<?php
include_once(__DIR__ . '/lib/common.php');
include_once(__DIR__ . '/lib/common_auth.php');
include_once('./php/data/banner_data.php');
include_once('./php/data/notice_data.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>PS 메인페이지</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Swiper CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- 내부 css 넣는곳 -->
    <link rel="stylesheet" href="/css/main/style.css">
    <link rel="stylesheet" href="/css/main/swiper.css">
    <!-- 내부 css 넣는곳 -->
</head>
<body>
<div class="page-container">
<?php include_once("header.php"); ?>

  <main class="main">
    <!-- Swiper 슬라이드 배너 -->
    <div class="main-banner">
      <div class="main-banner__left">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <?php foreach ($banners as $bn) { ?>
              <div class="swiper-slide">
                <a href="<?= htmlspecialchars($bn['bn_link']) ?>" target="_blank">
                  <img src="<?= htmlspecialchars($bn['bn_image']) ?>" alt="<?= htmlspecialchars($bn['bn_title']) ?>">
                </a>
              </div>
            <?php } ?>
          </div>

          <!-- 페이지네이션/네비게이션 -->
          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>

      <!-- <div class="main-banner__right">
        <h2 class="main-banner__title" id="title_text"></h2>
        <a href="#" class="main-banner__button" id="banner-link-button" target="_blank">링크 열기</a>
      </div> -->
    </div>

    <!-- 공지사항 카드뷰 -->
    <section class="main-notice">
      <h3 class="main-notice__title">📢 공지사항</h3>
      <div class="card-grid main-notice__card-grid">
        <?php if (!empty($notices)): ?>
          <?php foreach ($notices as $nt): ?>
            <a href="/html/notice/view.php?id=<?= htmlspecialchars($nt['id']) ?>" class="card">
              <div class="card__image">
                <img src="/uploads/main/image.png" alt="공지 이미지">
              </div>
              <div class="card__content">
                <h4 class="card__title"><?= htmlspecialchars($nt['subject']) ?></h4>
                <p class="card__meta"><?= mb_strimwidth(strip_tags($nt['content']), 0, 30, '...') ?></p>
                <span class="card__meta"><?= substr($nt['created_at'], 0, 10); ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="main-notice__empty">공지사항이 없습니다.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

</div>
<?php include_once("footer.php"); ?>
<!-- 내부 js 넣는곳 -->
<script>
    const banners = <?= json_encode($banners, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="/js/main/swiper.js"></script>
<script src="/js/main/index.js"></script>
<!-- 내부 js 넣는곳 -->
</body>
</html>

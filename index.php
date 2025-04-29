<?php
// index.php
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
    <link rel="stylesheet" href="/css/main/index.css">
    <link rel="stylesheet" href="/css/main/swiper.css">
    <!-- 내부 css 넣는곳 -->
</head>
<body>

<?php include_once("header.php"); ?>

<main class="main-content">
    <!-- Swiper 슬라이드 배너 -->
    <div class="banner-wrapper">
        <div class="banner-left">
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

        <div class="banner-right">
            <h2 class="coming-soon" id="title_text"></h2>
            <a href="#" class="button" id="banner-link-button" target="_blank">
                링크 열기
            </a>
        </div>
    </div>


    <!-- 공지사항 카드뷰 -->
    <section class="notice-section">
        <h3>📢 공지사항</h3>
        <div class="card-grid">
            <?php if (!empty($notices)): ?>
                <?php foreach ($notices as $nt): ?>
                    <a href="/html/notice/view.php?id=<?= htmlspecialchars($nt['id']) ?>" class="main-notice-link">
                        <div class="card-image">
                            <img src="/uploads/main/image.png" alt="공지 이미지">
                        </div>
                        <div class="card-content">
                            <h4><?= htmlspecialchars($nt['subject']) ?></h4>
                            <p><?= mb_strimwidth(strip_tags($nt['content']), 0, 30, '...') ?></p>
                            <span class="date"><?= substr($nt['created_at'], 0, 10); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="main-notice-empty">공지사항이 없습니다.</p>
            <?php endif; ?>
        </div>
    </section>


</main>

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

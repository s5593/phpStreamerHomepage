<?php
// index.php
include_once('./php/data/banner_data.php');
include_once('./php/data/notice_data.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>별구름 커뮤니티</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Swiper CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- 내부 css 넣는곳 -->
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/css/main/index.css">
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/css/main/swiper.css">
    <!-- 내부 css 넣는곳 -->
</head>
<body>

<?php include_once("header.php"); ?>

<main class="main-content">
    <!-- Swiper 슬라이드 배너 -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php
            /*
            <?php foreach ($banners as $bn) { ?>
                <div class="swiper-slide">
                    <a href="<?= $bn['bn_link'] ?>">
                    <img src="<?= $bn['bn_image'] ?>" alt="<?= $bn['bn_title'] ?>">
                    </a>
                </div>
            <?php } ?>
            */
            ?>
            <div class="swiper-slide"><img src="/uploads/banner1.jpg" alt="배너1"></div>
            <div class="swiper-slide"><img src="/uploads/banner2.jpg" alt="배너2"></div>
            <div class="swiper-slide"><img src="/uploads/banner3.jpg" alt="배너3"></div>
        </div>

        <!-- 페이지네이션/버튼 -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <!-- 공지사항 카드뷰 -->
    <section class="notice-section">
        <h3>📢 공지사항</h3>
        <div class="card-grid">
            <?php
            /*
            <?php foreach ($notices as $nt) { ?>
                <div class="notice-card">
                    <div class="card-image">
                        <img src="<?= $nt['nt_image'] ?>" alt="공지 이미지">
                    </div>
                    <div class="card-content">
                        <h4><?= $nt['nt_title'] ?></h4>
                        <p><?= mb_strimwidth(strip_tags($nt['nt_content']), 0, 60, '...') ?></p>
                        <span class="date"><?= substr($nt['nt_datetime'], 0, 10); ?></span>
                    </div>
                </div>
            <?php } ?>
            */
            ?>
            <div class="notice-card">
                <div class="card-image">
                    <img src="/uploads/thumb1.jpg" alt="공지 이미지">
                </div>
                <div class="card-content">
                    <h4>[공지] 사이트 오픈</h4>
                    <p>별구름 커뮤니티 오픈 소식!</p>
                    <span class="date">2025.04.15</span>
                </div>
            </div>
            <div class="notice-card">
                <div class="card-image">
                    <img src="/uploads/thumb1.jpg" alt="공지 이미지">
                </div>
                <div class="card-content">
                    <h4>[공지] 사이트 오픈</h4>
                    <p>별구름 커뮤니티 오픈 소식!</p>
                    <span class="date">2025.04.15</span>
                </div>
            </div>
            <div class="notice-card">
                <div class="card-image">
                    <img src="/uploads/thumb1.jpg" alt="공지 이미지">
                </div>
                <div class="card-content">
                    <h4>[공지] 사이트 오픈</h4>
                    <p>별구름 커뮤니티 오픈 소식!</p>
                    <span class="date">2025.04.15</span>
                </div>
            </div>
            <div class="notice-card">
                <div class="card-image">
                    <img src="/uploads/thumb1.jpg" alt="공지 이미지">
                </div>
                <div class="card-content">
                    <h4>[공지] 사이트 오픈</h4>
                    <p>별구름 커뮤니티 오픈 소식!</p>
                    <span class="date">2025.04.15</span>
                </div>
            </div>
            <!-- 카드 반복 -->
        </div>
    </section>


</main>

<?php include_once("footer.php"); ?>

<!-- 내부 js 넣는곳 -->
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/js/main/swiper.js"></script>
<script src="<?php echo dirname($_SERVER['PHP_SELF']); ?>/js/main/index.js"></script>
<!-- 내부 js 넣는곳 -->
</body>
</html>

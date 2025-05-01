<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$logged_in = isset($_SESSION['mb_id']);
$nickname = $_SESSION['mb_id'] ?? '';
$is_admin = isset($_SESSION['mb_level']) && $_SESSION['mb_level'] >= 3;

$message = get_flash_message();
if (!empty($message)):  // null, 빈 문자열, false 모두 방지됨
?>
<script>alert("<?= htmlspecialchars($message) ?>");</script>
<?php endif; ?>
<link rel="stylesheet" href="/css/main/header.css">
<script src="/js/main/header.js" defer></script>
<header class="site-header">
    <div class="container">
        <h1 class="logo">
            <a href="/">
                <img src="/uploads/main/logo.png" alt="별구름 로고">
            </a>
        </h1>
        <nav class="main-nav">
            <ul>
                <?php if ($logged_in): ?>
                    <li><span><?= htmlspecialchars($nickname) ?>님 안녕하세요</span></li>
                    <li><a href="/php/auth/logout.php">로그아웃</a></li>
                <?php else: ?>
                    <li><a href="/html/auth/login_form.php">로그인</a></li>
                    <li><a href="/html/auth/agree.php">회원가입</a></li>
                <?php endif; ?>
                <li><a href="/html/notice/list.php">공지사항</a></li>
                <li><a href="/html/wiki/list.php">위키</a></li>
                <li><a href="/html/video/list.php">영상</a></li>
                <?php if ($is_admin): ?>
                    <li><a href="/html/admin/banner_list.php">관리자</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- 햄버거 버튼 추가 -->
        <div class="hamburger" id="hamburger">
            ☰
        </div>

        <!-- 모바일 전용 메뉴 -->
        <div class="mobile-nav" id="mobileNav">
            <ul>
                <?php if ($logged_in): ?>
                    <li><span><?= htmlspecialchars($nickname) ?>님 안녕하세요</span></li>
                    <li><a href="/php/auth/logout.php">로그아웃</a></li>
                <?php else: ?>
                    <li><a href="/html/auth/login_form.php">로그인</a></li>
                    <li><a href="/html/auth/agree.php">회원가입</a></li>
                <?php endif; ?>
                <li><a href="/html/notice/list.php">공지사항</a></li>
                <li><a href="/html/wiki/list.php">위키</a></li>
                <li><a href="/html/video/list.php">영상</a></li>
                <?php if ($is_admin): ?>
                    <li><a href="/html/admin/banner_list.php">관리자</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
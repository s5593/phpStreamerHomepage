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
<header class="main-header">
  <div class="main-header__container">
    <h1 class="main-header__logo">
      <a href="/">
        <img src="/uploads/main/logo.png" alt="프리즘 로고">
      </a>
    </h1>

    <nav class="main-header__nav">
      <ul class="main-header__nav-list">
        <?php if ($logged_in): ?>
          <li class="main-header__nav-item"><span><?= htmlspecialchars($nickname) ?>님 안녕하세요</span></li>
          <li class="main-header__nav-item"><a href="/php/auth/logout.php">로그아웃</a></li>
        <?php else: ?>
          <li class="main-header__nav-item"><a href="/html/auth/login_form.php">로그인</a></li>
          <li class="main-header__nav-item"><a href="/html/auth/agree.php">회원가입</a></li>
        <?php endif; ?>
        <li class="main-header__nav-item"><a href="/html/notice/list.php">공지사항</a></li>
        <li class="main-header__nav-item"><a href="/html/wiki/list.php">위키</a></li>
        <li class="main-header__nav-item"><a href="/html/video/list.php">영상</a></li>
        <?php if ($is_admin): ?>
          <li class="main-header__nav-item"><a href="/html/admin/banner_list.php">관리자</a></li>
        <?php endif; ?>
      </ul>
    </nav>

    <!-- 햄버거 버튼 -->
    <div class="main-header__hamburger" id="hamburger">
      ☰
    </div>

    <!-- 모바일 전용 메뉴 -->
    <div class="main-header__mobile-nav" id="mobileNav">
      <ul class="main-header__mobile-list">
        <?php if ($logged_in): ?>
          <li class="main-header__mobile-item"><span><?= htmlspecialchars($nickname) ?>님 안녕하세요</span></li>
          <li class="main-header__mobile-item"><a href="/php/auth/logout.php">로그아웃</a></li>
        <?php else: ?>
          <li class="main-header__mobile-item"><a href="/html/auth/login_form.php">로그인</a></li>
          <li class="main-header__mobile-item"><a href="/html/auth/agree.php">회원가입</a></li>
        <?php endif; ?>
        <li class="main-header__mobile-item"><a href="/html/notice/list.php">공지사항</a></li>
        <li class="main-header__mobile-item"><a href="/html/wiki/list.php">위키</a></li>
        <li class="main-header__mobile-item"><a href="/html/video/list.php">영상</a></li>
        <?php if ($is_admin): ?>
          <li class="main-header__mobile-item"><a href="/html/admin/banner_list.php">관리자</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</header>
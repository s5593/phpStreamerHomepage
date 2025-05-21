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
<link rel="stylesheet" href="/css/main/style.css">
<link rel="stylesheet" href="/css/common.css">
<script src="/js/main/header.js" defer></script>
<header class="main-header">
  <div class="main-header__container">
    <!-- 왼쪽: 로고 -->
    <h1 class="main-header__logo">
      <a href="/">
        <img src="/uploads/main/logo.png" alt="프리즘 로고" height="60">
      </a>
    </h1>

    <!-- 오른쪽: 메뉴 + 햄버거 -->
    <div class="main-header__right">
      <nav class="main-header__nav">
        <ul class="main-header__nav-list">
          <?php if ($logged_in): ?>
            <li class="main-header__nav-item"><span><?= htmlspecialchars($nickname) ?>님</span></li>
            <li class="main-header__nav-item"><a href="/php/auth/logout.php">로그아웃</a></li>
          <?php else: ?>
            <li class="main-header__nav-item"><a href="/html/auth/login_form.php">로그인</a></li>
            <li class="main-header__nav-item"><a href="/html/auth/agree.php">회원가입</a></li>
          <?php endif; ?>
          <li class="main-header__nav-item"><a href="/html/notice/list.php">공지사항</a></li>
          <li class="main-header__nav-item"><a href="/html/wiki/list.php">위키</a></li>
          <li class="main-header__nav-item"><a href="/html/video/list.php">영상</a></li>
          <li class="main-header__nav-item"><a href="/html/post/list.php">이미지</a></li>
          <?php if ($is_admin): ?>
            <li class="main-header__nav-item"><a href="/html/admin/banner_list.php">관리자</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <div class="main-header__hamburger" id="hamburger">☰</div>
    </div>
  </div>

  <!-- 모바일 메뉴 -->
  <div class="main-header__mobile-nav" id="mobileNav">
    <ul class="main-header__mobile-list">
      <?php if ($logged_in): ?>
        <li><span><?= htmlspecialchars($nickname) ?>님</span></li>
        <li><a href="/php/auth/logout.php">로그아웃</a></li>
      <?php else: ?>
        <li><a href="/html/auth/login_form.php">로그인</a></li>
        <li><a href="/html/auth/agree.php">회원가입</a></li>
      <?php endif; ?>
      <li><a href="/html/notice/list.php">공지사항</a></li>
      <li><a href="/html/wiki/list.php">위키</a></li>
      <li><a href="/html/video/list.php">영상</a></li>
      <li><a href="/html/post/list.php">이미지</a></li>
      <?php if ($is_admin): ?>
        <li><a href="/html/admin/banner_list.php">관리자</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>

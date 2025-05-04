<?php
if (!isset($is_partial)) {
    include_once(__DIR__ . '/../../lib/common.php');
    include_once(__DIR__ . '/../../lib/common_auth.php');
    $user_id = current_user_id();
}

$search = trim($_GET['q'] ?? '');
$search_type = $_GET['type'] ?? 'subject';
?>

<!-- 검색 폼 -->
<form method="get" action="list.php" class="video__search-form">
  <!-- select + input -->
  <div class="video__search-row">
    <select name="type" class="video__search-type">
      <option value="subject" <?= $search_type === 'subject' ? 'selected' : '' ?>>제목</option>
      <option value="keywords" <?= $search_type === 'keywords' ? 'selected' : '' ?>>키워드</option>
    </select>

    <input type="text" name="q"
           value="<?= htmlspecialchars($search) ?>"
           placeholder="검색어 입력"
           class="video__search-input">
  </div>

  <!-- 버튼 그룹 -->
  <div class="video__search-button-row">
    <button type="submit" class="video__search-btn video__search-btn--submit">검색</button>
    <button type="button" class="video__search-btn video__search-btn--reset" onclick="location.href='list.php'">초기화</button>
  </div>

  <!-- 안내 텍스트 -->
  <div class="video__search-guide">
    <p><strong>제목</strong>: 검색어 입력</p>
    <p><strong>키워드</strong>: 검색어 입력 (,로 구분)</p>
  </div>
</form>

<!-- 추천 키워드 영역 -->
<div class="video__keyword-section" id="popular-keywords">
  <h3 class="video__keyword-title">🔥 인기 키워드</h3>
  <div class="video__keyword-list" id="keyword-buttons">불러오는 중...</div>
</div>

<div class="video__keyword-section" id="personal-keywords">
  <h3 class="video__keyword-title">🎯 개인화 추천 키워드</h3>
  <div class="video__keyword-list" id="personal-list">불러오는 중...</div>
</div>

<script>
  const USER_ID = <?= isset($user_id) ? json_encode($user_id) : 'null' ?>;
</script>
<script src="/js/video/recommend_ui.js"></script>


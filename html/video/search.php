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
<form method="get" action="list.php" class="search-form" style="margin-bottom: 10px;">
  <!-- select + input -->
  <div class="search-form__row">
    <select name="type" class="input--select">
      <option value="subject" <?= $search_type === 'subject' ? 'selected' : '' ?>>제목</option>
      <option value="keywords" <?= $search_type === 'keywords' ? 'selected' : '' ?>>키워드</option>
    </select>

    <input type="text" name="q"
           value="<?= htmlspecialchars($search) ?>"
           placeholder="검색어 입력"
           class="input input--text">
  </div>

  <!-- 안내 텍스트 -->
  <div class="search-form__guide">
    <p><strong>제목</strong>: 검색어 입력</p>
    <p><strong>키워드</strong>: 검색어 입력 (쉼표 `,`로 구분)</p>
  </div>

  <!-- 버튼 그룹 -->
  <div class="button-group button-group--right">
    <button type="submit" class="button button--primary">검색</button>
    <button type="button" class="button button--secondary" onclick="location.href='list.php'">검색 초기화</button>
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


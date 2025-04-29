<?php
include_once(__DIR__ . '/../../lib/common.php');
include_once(__DIR__ . '/../../lib/common_auth.php');

$user_id = current_user_id(); // 로그인한 경우만 개인화 추천
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>영상 검색</title>
  <link rel="stylesheet" href="/css/video/style.css">
  <link rel="stylesheet" href="/css/video/search.css">
</head>
<body>
  <h1>🔍 영상 검색</h1>

  <div id="search-box">
    <input type="text" id="search-input" placeholder="검색어를 입력하세요" maxlength="100">
    <select id="sort-select">
      <option value="recent">최신순</option>
      <option value="oldest">오래된순</option>
    </select>
    <button id="search-btn">검색</button>
  </div>

  <div id="recommend-keywords">
    <h3>🔥 추천 키워드</h3>
    <div id="keyword-list">불러오는 중...</div>
  </div>

  <div id="search-results">
    <h3>검색 결과</h3>
    <ul id="result-list"></ul>
  </div>

  <script>
    const USER_ID = <?= $user_id ? json_encode($user_id) : 'null' ?>;
  </script>
  <script src="/js/video/recommend_ui.js"></script>
  <script src="/js/video/search.js"></script>
</body>
</html>

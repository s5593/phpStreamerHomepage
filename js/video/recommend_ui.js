document.addEventListener('DOMContentLoaded', () => {
    const keywordContainer = document.getElementById('keyword-list');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
  
    fetch('/php/video/get_keyword_recommendations.php')
      .then(res => res.json())
      .then(data => {
        const keywords = data.keywords || [];
  
        if (keywords.length === 0) {
          keywordContainer.innerHTML = '<p>추천 키워드가 없습니다.</p>';
          return;
        }
  
        keywordContainer.innerHTML = '';
        keywords.forEach(word => {
          const btn = document.createElement('button');
          btn.textContent = word;
          btn.className = 'keyword-item';
          btn.style.marginRight = '8px';
          btn.style.cursor = 'pointer';
  
          btn.addEventListener('click', () => {
            searchInput.value = word;
            searchBtn.click();
          });
  
          keywordContainer.appendChild(btn);
        });
      })
      .catch(err => {
        console.error('추천 키워드 불러오기 실패:', err);
        keywordContainer.innerHTML = '<p>불러오기 실패</p>';
    });
});
  
if (USER_ID) {
    fetch('/php/video/get_user_recommendations.php')
    .then(res => res.json())
    .then(data => {
    // 원하는 위치에 append 또는 대체 출력 가능
    console.log("개인화 추천 키워드", data.keywords);
    });
}
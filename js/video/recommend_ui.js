document.addEventListener('DOMContentLoaded', () => {
  const keywordBox = document.getElementById('keyword-buttons');
  const personalBox = document.getElementById('personal-list');
  const keywordContainer = document.getElementById('popular-keywords');
  const personalContainer = document.getElementById('personal-keywords');
  const searchInput = document.getElementById('search-input');

  // 🔥 인기 키워드
  fetch('/php/video/get_keyword_recommendations.php')
    .then(res => res.json())
    .then(data => {
      const keywords = data.keywords || [];
      if (keywords.length === 0) {
        if (keywordContainer) keywordContainer.style.display = 'none';
        return;
      }

      keywordBox.innerHTML = '';
      keywords.slice(0, 5).forEach(word => {
        const btn = document.createElement('button');
        btn.textContent = word;
        btn.className = 'video__keyword-btn';
        btn.style.marginRight = '8px';
        btn.style.cursor = 'pointer';

        btn.addEventListener('click', () => {
          goToSearch(word, 'keywords');
        });

        keywordBox.appendChild(btn);
      });
    })
    .catch(err => {
      console.error('추천 키워드 실패:', err);
      if (keywordContainer) keywordContainer.style.display = 'none';
    });

  // 🎯 개인화 추천 키워드
  if (typeof USER_ID !== 'undefined' && USER_ID && personalBox && personalContainer) {
    fetch('/php/video/get_user_recommendations.php')
      .then(res => res.json())
      .then(data => {
        const keywords = data.keywords || [];
        if (keywords.length === 0) {
          personalContainer.style.display = 'none';
          return;
        }

        personalBox.innerHTML = '';
        keywords.slice(0, 5).forEach(word => {
          const btn = document.createElement('button');
          btn.textContent = word;
          btn.className = 'video__keyword-btn';
          btn.style.marginRight = '8px';
          btn.style.cursor = 'pointer';

          btn.addEventListener('click', () => {
            goToSearch(word, 'keywords');
          });

          personalBox.appendChild(btn);
        });
      })
      .catch(err => {
        console.error('개인화 추천 실패:', err);
        personalContainer.style.display = 'none';
      });
  } else if (personalContainer) {
    personalContainer.style.display = 'none';
  }

  function goToSearch(keyword, type = 'subject') {
    const encoded = encodeURIComponent(keyword);
    const target = `/html/video/list.php?q=${encoded}&type=${type}`;
    location.href = target;
  }
});

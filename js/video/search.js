document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const sortSelect = document.getElementById('sort-select');
    const resultList = document.getElementById('result-list');
    const typeSelect = document.getElementById('search-type');
  
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }
  
    function performSearch() {
      const keyword = searchInput.value.trim();
      const sort = sortSelect.value;
  
      if (!keyword) {
        alert('검색어를 입력하세요.');
        return;
      }
  
      fetch('/php/video/get_search_results.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ keyword, sort })
      })
        .then(res => res.json())
        .then(data => {
          resultList.innerHTML = '';
          if (data.length === 0) {
            resultList.innerHTML = '<li>검색 결과가 없습니다.</li>';
            return;
          }
  
          data.forEach(post => {
            const li = document.createElement('li');
  
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '/html/video/view.php';
  
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = post.id;
  
            const inputToken = document.createElement('input');
            inputToken.type = 'hidden';
            inputToken.name = 'csrf_token';
            inputToken.value = post.csrf_token;
  
            const btn = document.createElement('button');
            btn.type = 'submit';
            btn.style.background = 'none';
            btn.style.border = 'none';
            btn.style.color = 'blue';
            btn.style.cursor = 'pointer';
            btn.innerHTML = escapeHtml(post.title);
  
            form.appendChild(inputId);
            form.appendChild(inputToken);
            form.appendChild(btn);
  
            li.appendChild(form);
            resultList.appendChild(li);
          });
        })
        .catch(err => {
          console.error('검색 오류:', err);
          alert('검색 중 오류가 발생했습니다.');
        });
    }
  
    // 이벤트 바인딩
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        e.preventDefault();
        performSearch();
      }
    });
  });
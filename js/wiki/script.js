document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('tocToggleBtn');
    const toc = document.getElementById('toc');
    const content = document.getElementById('wiki-content');
  
    // 토글 버튼 기능
    if (toggleBtn && toc) {
      toggleBtn.addEventListener('click', () => {
        const isVisible = toc.classList.toggle('show');
        toggleBtn.textContent = isVisible ? '▲ 접기' : '▼ 펼치기';
      });
    }
  
    // 목차 생성: h1 ~ h3
    if (toc && content) {
      const headings = content.querySelectorAll('h1, h2, h3');
      if (headings.length > 0) {
        const ul = document.createElement('ul');
        headings.forEach((heading, idx) => {
          const id = 'heading-' + idx;
          heading.id = id;
  
          const li = document.createElement('li');
          li.classList.add('toc-item', heading.tagName.toLowerCase()); // h1, h2, h3
  
          const a = document.createElement('a');
          a.href = '#' + id;
          a.textContent = heading.textContent;
  
          li.appendChild(a);
          ul.appendChild(li);
        });
        toc.appendChild(ul);
      }
    }
});
  
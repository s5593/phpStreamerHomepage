document.addEventListener('DOMContentLoaded', () => {
    // 모든 삭제 form에 대해 확인창 적용
    const deleteForms = document.querySelectorAll('form[action="/php/notice/delete.php"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('정말 삭제하시겠습니까?')) {
                e.preventDefault();
            }
        });
    });

    // 제목 버튼 클릭 시 스타일 효과 (예시)
    const titleButtons = document.querySelectorAll('.notice-title-btn');
    titleButtons.forEach(btn => {
        btn.addEventListener('focus', () => {
            btn.style.outline = '2px solid #0066cc';
        });
        btn.addEventListener('blur', () => {
            btn.style.outline = 'none';
        });
    });
});

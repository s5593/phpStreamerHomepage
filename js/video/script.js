document.addEventListener('DOMContentLoaded', () => {
  const likeBtn = document.querySelector('.video__like-btn');
  if (!likeBtn) return;

  likeBtn.addEventListener('click', () => {
    const videoId = likeBtn.dataset.videoId;

    const formData = new FormData();
    formData.append('video_id', videoId);

    fetch('/php/video/toggle_like.php', {
      method: 'POST',
      body: formData,
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') {
        const icon = likeBtn.querySelector('i');
        const countSpan = document.getElementById('like-count');

        // 아이콘 토글
        icon.classList.remove('fas', 'far');
        icon.classList.add(data.liked ? 'fas' : 'far');

        // 수 갱신
        countSpan.textContent = data.count;
      } else {
        alert(data.message || '좋아요 처리 중 오류가 발생했습니다.');
      }
    })
    .catch(err => {
      console.error('좋아요 오류:', err);
      alert('서버와 통신에 실패했습니다.');
    });
  });
});

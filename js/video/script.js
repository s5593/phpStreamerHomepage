document.addEventListener('DOMContentLoaded', () => {
    const likeBtn = document.querySelector('.video-like-btn');
    if (!likeBtn) return;
  
    likeBtn.addEventListener('click', () => {
      const videoId = likeBtn.dataset.videoId;
  
      fetch('/php/video/toggle_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'video_id=' + encodeURIComponent(videoId)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
            const icon = likeBtn.querySelector('.like-icon');
            icon.classList.remove(data.liked ? 'far' : 'fas');
            icon.classList.add(data.liked ? 'fas' : 'far');            
            document.getElementById('like-count').textContent = data.count;
        } else {
          alert(data.message || '처리 중 오류가 발생했습니다.');
        }
      });
    });
  });
  
document.addEventListener('DOMContentLoaded', function() {
  const swiper = new Swiper(".mySwiper", {
      loop: true,
      pagination: {
          el: ".swiper-pagination",
          clickable: true,
      },
      navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
      },
      on: {
          init: function () {
              updateButtonLink(this.realIndex); // this = swiper 인스턴스
          },
          slideChange: function () {
              updateButtonLink(this.realIndex);
          }
      }
  });

  function updateButtonLink(activeIndex) {
    const button = document.getElementById('banner-link-button');
    const title = document.getElementById('title_text');

    if (banners && banners[activeIndex]) {
      if (button) {
          button.href = banners[activeIndex]['bn_link'];
      }
      if (title) {
        title.textContent = banners[activeIndex]['bn_title'] || '공지사항';
      }
    }
  }
});

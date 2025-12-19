(function () {
  function initAwProductsSliders() {
    var blocks = document.querySelectorAll("[data-aw-products]");
    if (!blocks.length) return;
    if (typeof Swiper === "undefined") return;

    blocks.forEach(function (block) {
      var threshold = parseInt(
        block.getAttribute("data-aw-threshold") || "3",
        10
      );

      var list = block.querySelector("ul.products");
      if (!list) return;

      var items = list.querySelectorAll(".product");
      var total = items.length;
      if (!total) return;

      var nav = block.querySelector(".aw-products-block__nav");
      var pag = block.querySelector(".swiper-pagination");
      var next = block.querySelector(".aw-products-block__btn--next");
      var prev = block.querySelector(".aw-products-block__btn--prev");

      var swiperInstance = null;

      function showControls() {
        if (nav) nav.style.display = "";
        if (pag) pag.style.display = "";
      }

      function hideControls() {
        if (nav) nav.style.display = "none";
        if (pag) pag.style.display = "none";
      }

      function enableSwiper() {
        if (swiperInstance) return;

        // klasy Swiper
        block.classList.add("swiper", "aw-products-block--is-slider");
        list.classList.add("swiper-wrapper");
        items.forEach(function (el) {
          el.classList.add("swiper-slide");
        });

        showControls();

        swiperInstance = new Swiper(block, {
          slidesPerView: 1.1,
          spaceBetween: 16,
          watchOverflow: true,
          grabCursor: true,
          speed: 450,
          loop: false,

          breakpoints: {
            640: { slidesPerView: 2.1, spaceBetween: 16 },
            1024: { slidesPerView: 3, spaceBetween: 24 },
          },

          pagination: pag
            ? { el: pag, clickable: true, dynamicBullets: true }
            : undefined,

          navigation: next && prev ? { nextEl: next, prevEl: prev } : undefined,
        });
      }

      function disableSwiper() {
        if (swiperInstance) {
          swiperInstance.destroy(true, true);
          swiperInstance = null;
        }

        // sprzątanie klas
        block.classList.remove("swiper", "aw-products-block--is-slider");
        list.classList.remove("swiper-wrapper");
        items.forEach(function (el) {
          el.classList.remove("swiper-slide");
        });

        // czyść inline po swiper
        list.style.transform = "";
        list.style.transitionDuration = "";

        hideControls();
      }

      function updateSliderMode() {
        var shouldBeSlider = total > threshold; // klucz: >3
        if (shouldBeSlider) enableSwiper();
        else disableSwiper();
      }

      updateSliderMode();
    });
  }

  document.addEventListener("DOMContentLoaded", initAwProductsSliders);
})();

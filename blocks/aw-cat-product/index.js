(function () {
  function initAwCatProductSlider() {
    var container = document.querySelector(
      ".aw-cat-product.aw-cat-product--slider",
    );
    if (!container) return;
    if (typeof Swiper === "undefined") return;

    var list = container.querySelector(".aw-cat-product__list");
    if (!list) return;

    var items = list.querySelectorAll(".aw-cat-product__item");
    var totalSlides = items.length;
    if (!totalSlides) return;

    var swiperInstance = null;

    function getMinSlidesForSlider(width) {
      if (width <= 767) return 2; // mobile -> slider jeśli > 2
      if (width <= 1023) return 3; // tablet -> slider jeśli > 3
      return 4; // desktop -> slider jeśli > 4
    }

    function enableSwiper() {
      if (swiperInstance) return;

      swiperInstance = new Swiper(container, {
        slidesPerView: 1.3,
        spaceBetween: 16,
        watchOverflow: true,
        grabCursor: true,
        // breakpoints – ile kart na szerokość
        breakpoints: {
          768: {
            slidesPerView: 3,
            spaceBetween: 24,
          },
          1024: {
            slidesPerView: 1,
            spaceBetween: 24,
          },
        },
        pagination: {
          el: container.querySelector(".aw-cat-product__pagination"),
          clickable: true,
          dynamicBullets: true,
        },
        navigation: {
          nextEl: container.querySelector(".aw-cat-product__next"),
          prevEl: container.querySelector(".aw-cat-product__prev"),
        },
      });

      container.classList.add("aw-cat-product--is-slider");
    }

    function disableSwiper() {
      if (!swiperInstance) return;

      swiperInstance.destroy(true, true);
      swiperInstance = null;
      container.classList.remove("aw-cat-product--is-slider");
    }

    function updateSliderMode() {
      var width = window.innerWidth || document.documentElement.clientWidth;
      var minSlides = getMinSlidesForSlider(width);
      var shouldBeSlider = totalSlides > minSlides;
      console.log(shouldBeSlider);

      if (shouldBeSlider) {
        enableSwiper();
      } else {
        disableSwiper();
      }
    }

    // prościutki debounce
    var resizeTimeout = null;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(updateSliderMode, 150);
    });

    // initial
    updateSliderMode();
  }

  document.addEventListener("DOMContentLoaded", initAwCatProductSlider);
})();

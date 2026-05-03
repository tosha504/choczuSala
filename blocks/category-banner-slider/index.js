(() => {
  const initCategoryBannerSlider = (slider) => {
    if (!slider || slider.dataset.awSliderInitialized === "1") {
      return;
    }

    if (typeof Swiper === "undefined") {
      return;
    }

    const block = slider.closest(".aw-category-slider");
    const slides = slider.querySelectorAll(".swiper-slide");

    if (!block || slides.length <= 1) {
      slider.dataset.awSliderInitialized = "1";
      return;
    }

    const prefersReducedMotion = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    const autoplayEnabled =
      slider.dataset.autoplay === "1" && !prefersReducedMotion;

    const delay = Number.parseInt(slider.dataset.delay || "4500", 10);

    const nextButton = block.querySelector(".aw-category-slider__arrow--next");
    const prevButton = block.querySelector(".aw-category-slider__arrow--prev");
    const pagination = block.querySelector(".aw-category-slider__pagination");

    slider.dataset.awSliderInitialized = "1";

    new Swiper(slider, {
      slidesPerView: 1,
      loop: true,
      speed: 650,
      watchOverflow: true,
      grabCursor: true,
      autoplay: autoplayEnabled
        ? {
            delay: Number.isNaN(delay) ? 4500 : delay,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }
        : false,
      pagination: pagination
        ? {
            el: pagination,
            clickable: true,
          }
        : false,
      navigation:
        nextButton && prevButton
          ? {
              nextEl: nextButton,
              prevEl: prevButton,
            }
          : false,
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
      a11y: {
        enabled: true,
      },
    });
  };

  const initAllCategoryBannerSliders = () => {
    document
      .querySelectorAll(".js-aw-category-slider")
      .forEach(initCategoryBannerSlider);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllCategoryBannerSliders);
  } else {
    initAllCategoryBannerSliders();
  }

  if (window.acf) {
    window.acf.addAction(
      "render_block_preview/type=aw-theme/category-banner-slider",
      initAllCategoryBannerSliders,
    );
  }
})();

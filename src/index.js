(function () {
  console.log("ready");
  const burger = jQuery(".burger"),
    burgerSpan = jQuery(".burger span"),
    nav = jQuery("#site-navigation"),
    body = jQuery("body");
  document.addEventListener("change", (e) => {
    const select = e.target.closest(".aw-lang__select");
    if (!select) return;

    window.location.href = select.value;
  });
  burger.on("click", function () {
    burgerSpan.toggleClass("active");
    nav.toggleClass("active");
    body.toggleClass("fixed-page");
  });
  jQuery(document).on("click", ".cart-qty.plus, .cart-qty.minus", function (e) {
    e.preventDefault();

    const input = jQuery(this).parent().find(".input-text.qty.text");
    const input_val = parseInt(input.val());
    if (jQuery(this).hasClass("plus")) {
      input.val(input_val + 1);
      input.attr("value", input_val + 1);
    } else {
      const new_val = input_val - 1;
      if (new_val > 0) {
        input.val(input_val - 1);
        input.attr("value", input_val - 1);
      }
    }

    input.trigger("change");
  });

  let timeout;
  jQuery(".woocommerce").on("change", "input.qty", function () {
    if (timeout !== undefined) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(function () {
      jQuery("[name='update_cart']").trigger("click"); // trigger cart update
    }, 100); // 1 second delay, half a second (500) seems comfortable too
  });
})(jQuery);
document.addEventListener("DOMContentLoaded", () => {
  const roots = document.querySelectorAll("[data-aw-gr]");
  if (!roots.length) return;

  const starSvg = `
    <svg viewBox="0 0 24 24" aria-hidden="true">
      <path d="M12 17.27l5.18 3.04-1.39-5.9L20.5 10l-6.03-.52L12 4 9.53 9.48 3.5 10l4.71 4.41-1.39 5.9L12 17.27z"></path>
    </svg>
  `;

  function renderStars(container, rating, theme = "dark") {
    if (!container) return;
    const r = Math.max(0, Math.min(5, Number(rating || 0)));
    container.innerHTML = "";
    for (let i = 1; i <= 5; i++) {
      const span = document.createElement("span");
      span.className =
        "aw-gr-star " +
        (i <= Math.round(r) ? "aw-gr-star--on" : "aw-gr-star--off");
      // w badge off gwiazdy są półprzezroczyste na białym, w modalach też OK
      span.innerHTML = starSvg;
      container.appendChild(span);
    }
  }

  roots.forEach((root) => {
    // badge stars (rating firmy)
    const rating = root.getAttribute("data-rating") || "0";
    renderStars(root.querySelector("[data-aw-gr-stars]"), rating);

    const openBtn = root.querySelector("[data-aw-gr-open]");
    const tpl = root.querySelector("[data-aw-gr-template]");
    if (!openBtn || !tpl) return;

    let modalEl = null;
    let lastActive = null;

    const open = () => {
      if (modalEl) return;
      lastActive = document.activeElement;

      modalEl = tpl.content.firstElementChild.cloneNode(true);
      document.body.appendChild(modalEl);

      // modal stars
      renderStars(modalEl.querySelector(".aw-gr-modal__stars"), rating);

      // card stars per review
      modalEl
        .querySelectorAll(".aw-gr-card__stars[data-rating]")
        .forEach((el) => {
          renderStars(el, el.getAttribute("data-rating"));
        });

      // zamykanie
      modalEl.querySelectorAll("[data-aw-gr-close]").forEach((btn) => {
        btn.addEventListener("click", close);
      });

      document.addEventListener("keydown", onKeydown);
      document.body.style.overflow = "hidden";

      openBtn.setAttribute("aria-expanded", "true");

      // focus
      const closeBtn = modalEl.querySelector(".aw-gr-modal__close");
      closeBtn && closeBtn.focus();
    };

    const close = () => {
      if (!modalEl) return;
      modalEl.remove();
      modalEl = null;

      document.removeEventListener("keydown", onKeydown);
      document.body.style.overflow = "";
      openBtn.setAttribute("aria-expanded", "false");
      lastActive && lastActive.focus();
    };

    const onKeydown = (e) => {
      if (e.key === "Escape") close();
    };

    openBtn.addEventListener("click", open);
  });
});

const doc = document;
const win = window;
(function ($) {
  "use strict";

  /**
   * Helpers
   */
  const setHeaderHeightVar = () => {
    const masthead = doc.querySelector("#masthead");
    if (!masthead) return;

    doc.documentElement.style.setProperty(
      "--aw-header-h",
      `${masthead.offsetHeight}px`,
    );
  };

  /**
   * Language switcher
   */
  const initLanguageSwitcher = () => {
    doc.addEventListener("change", (e) => {
      const select = e.target.closest(".aw-lang__select");
      if (!select || !select.value) return;

      win.location.href = select.value;
    });
  };

  /**
   * Burger / mobile nav
   */
  const initBurger = () => {
    const $burger = $(".burger");
    const $burgerSpan = $(".burger span");
    const $nav = $("#site-navigation");
    const $body = $("body");

    if (!$burger.length) return;

    $burger.on("click", function () {
      $burgerSpan.toggleClass("active");
      $nav.toggleClass("active");
      $body.toggleClass("fixed-page");
    });
  };

  /**
   * Cart qty controls
   */
  const initCartQtyControls = () => {
    $(document).on("click", ".cart-qty.plus, .cart-qty.minus", function (e) {
      e.preventDefault();

      const $input = $(this).parent().find(".input-text.qty.text, input.qty");
      if (!$input.length) return;

      const current = parseInt($input.val(), 10) || 0;
      let next = current;

      if ($(this).hasClass("plus")) {
        next = current + 1;
      } else {
        next = Math.max(0, current - 1);
      }

      $input.val(next).trigger("change");
    });

    let timeout;

    $(".woocommerce").on("change", "input.qty", function () {
      if (timeout) {
        clearTimeout(timeout);
      }

      timeout = setTimeout(() => {
        $("[name='update_cart']").trigger("click");
      }, 100);
    });
  };

  /**
   * Google reviews modal
   */
  const initGoogleReviews = () => {
    const roots = doc.querySelectorAll("[data-aw-gr]");
    if (!roots.length) return;

    const starSvg = `
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 17.27l5.18 3.04-1.39-5.9L20.5 10l-6.03-.52L12 4 9.53 9.48 3.5 10l4.71 4.41-1.39 5.9L12 17.27z"></path>
      </svg>
    `;

    const renderStars = (container, rating) => {
      if (!container) return;

      const normalized = Math.max(0, Math.min(5, Number(rating || 0)));
      container.innerHTML = "";

      for (let i = 1; i <= 5; i++) {
        const span = doc.createElement("span");
        span.className = `aw-gr-star ${
          i <= Math.round(normalized) ? "aw-gr-star--on" : "aw-gr-star--off"
        }`;
        span.innerHTML = starSvg;
        container.appendChild(span);
      }
    };

    roots.forEach((root) => {
      const rating = root.getAttribute("data-rating") || "0";
      renderStars(root.querySelector("[data-aw-gr-stars]"), rating);

      const openBtn = root.querySelector("[data-aw-gr-open]");
      const tpl = root.querySelector("[data-aw-gr-template]");
      if (!openBtn || !tpl) return;

      let modalEl = null;
      let lastActive = null;

      const onKeydown = (e) => {
        if (e.key === "Escape") {
          close();
        }
      };

      const close = () => {
        if (!modalEl) return;

        modalEl.remove();
        modalEl = null;
        doc.removeEventListener("keydown", onKeydown);
        doc.body.style.overflow = "";
        openBtn.setAttribute("aria-expanded", "false");

        if (lastActive) {
          lastActive.focus();
        }
      };

      const open = () => {
        if (modalEl) return;

        lastActive = doc.activeElement;
        modalEl = tpl.content.firstElementChild.cloneNode(true);
        doc.body.appendChild(modalEl);

        renderStars(modalEl.querySelector(".aw-gr-modal__stars"), rating);

        modalEl
          .querySelectorAll(".aw-gr-card__stars[data-rating]")
          .forEach((el) => {
            renderStars(el, el.getAttribute("data-rating"));
          });

        modalEl.querySelectorAll("[data-aw-gr-close]").forEach((btn) => {
          btn.addEventListener("click", close);
        });

        doc.addEventListener("keydown", onKeydown);
        doc.body.style.overflow = "hidden";
        openBtn.setAttribute("aria-expanded", "true");

        const closeBtn = modalEl.querySelector(".aw-gr-modal__close");
        if (closeBtn) {
          closeBtn.focus();
        }
      };

      openBtn.addEventListener("click", open);
    });
  };

  /**
   * Search modal
   */
  const initSearchModal = () => {
    const modal = doc.querySelector("#site-search-modal");
    if (!modal) return;

    const openButtons = doc.querySelectorAll(".js-search-open");
    const closeButtons = modal.querySelectorAll(".js-search-close");
    const form = modal.querySelector(".js-search-form");
    const input = modal.querySelector(".search-modal__input");
    const results = modal.querySelector("#aw-search-live-results");
    const refreshButton = modal.querySelector(".js-search-refresh");

    if (!form || !input) return;

    let lastFocusedElement = null;

    const getFocusableElements = () =>
      modal.querySelectorAll(
        'a[href], button:not([disabled]), textarea, input:not([disabled]), select, [tabindex]:not([tabindex="-1"])',
      );

    const triggerLiveSearch = () => {
      input.dispatchEvent(
        new KeyboardEvent("keyup", {
          bubbles: true,
          key: input.value?.slice(-1) || "a",
        }),
      );
      win.dispatchEvent(new Event("resize"));
    };

    const openModal = () => {
      lastFocusedElement = doc.activeElement;

      modal.hidden = false;
      modal.setAttribute("aria-hidden", "false");
      doc.body.classList.add("search-modal-open");

      openButtons.forEach((button) => {
        button.setAttribute("aria-expanded", "true");
      });

      win.requestAnimationFrame(() => {
        input.focus();

        if (input.value.trim().length >= 2) {
          triggerLiveSearch();
        }
      });
    };

    const closeModal = () => {
      modal.hidden = true;
      modal.setAttribute("aria-hidden", "true");
      doc.body.classList.remove("search-modal-open");

      openButtons.forEach((button) => {
        button.setAttribute("aria-expanded", "false");
      });

      if (lastFocusedElement) {
        lastFocusedElement.focus();
      }
    };

    openButtons.forEach((button) => {
      button.addEventListener("click", openModal);
    });

    closeButtons.forEach((button) => {
      button.addEventListener("click", closeModal);
    });

    modal.addEventListener("click", (event) => {
      if (event.target.classList.contains("js-search-close")) {
        closeModal();
      }
    });

    doc.addEventListener("keydown", (event) => {
      if (modal.hidden) return;

      if (event.key === "Escape") {
        closeModal();
        return;
      }

      if (event.key === "Tab") {
        const focusable = Array.from(getFocusableElements());
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && doc.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && doc.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    });

    /**
     * Guard dla action formularza:
     * jeśli action jest puste lub #, bierzemy data-search-action.
     */
    form.addEventListener("submit", () => {
      const action = form.getAttribute("action");
      const fallbackAction = form.dataset.searchAction;

      if ((!action || action === "#") && fallbackAction) {
        form.setAttribute("action", fallbackAction);
      }
    });

    /**
     * Klik w przycisk "Szukaj" -> zwykły submit formularza.
     */
    if (refreshButton) {
      refreshButton.addEventListener("click", () => {
        form.requestSubmit();
      });
    }

    /**
     * Klik w wynik zamyka modal.
     */
    if (results) {
      results.addEventListener("click", (event) => {
        const link = event.target.closest("a");
        if (link) {
          closeModal();
        }
      });
    }
  };

  /**
   * Init
   */
  const init = () => {
    setHeaderHeightVar();
    initLanguageSwitcher();
    initBurger();
    initCartQtyControls();
    initGoogleReviews();
    initSearchModal();

    win.addEventListener("resize", setHeaderHeightVar);
  };

  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }

  var $nav = $("#site-navigation .header__nav");
  var mobileBreakpoint = 1200;

  if (!$nav.length) {
    return;
  }

  $nav.find(".menu-item-has-children").each(function (index) {
    var $item = $(this);
    var $link = $item.children("a").first();
    var $submenu = $item.children(".sub-menu").first();

    if (!$link.length || !$submenu.length) {
      return;
    }

    // nie duplikuj przy ponownym init
    if ($item.children(".submenu-item-row").length) {
      return;
    }

    var submenuId = $submenu.attr("id");
    if (!submenuId) {
      submenuId =
        "submenu-" + index + "-" + Math.random().toString(36).slice(2, 8);
      $submenu.attr("id", submenuId);
    }

    var $row = $('<div class="submenu-item-row"></div>');
    $link.before($row);
    $row.append($link);

    var $button = $(
      '<button type="button" class="submenu-toggle" aria-expanded="false" aria-label="Rozwiń submenu"></button>',
    );

    $button.attr("aria-controls", submenuId);
    $row.append($button);

    $button.on("click", function (e) {
      var isMobile = window.innerWidth < mobileBreakpoint;

      if (!isMobile) {
        return;
      }

      e.preventDefault();
      e.stopPropagation();

      var isOpen = $item.hasClass("is-open");

      $item.toggleClass("is-open", !isOpen);
      $button.attr("aria-expanded", !isOpen ? "true" : "false");
    });
  });

  function resetDesktopSubmenus() {
    if (window.innerWidth >= mobileBreakpoint) {
      $nav.find(".menu-item-has-children.is-open").removeClass("is-open");
      $nav.find(".submenu-toggle").attr("aria-expanded", "false");
    }
  }

  $(window).on("resize", function () {
    resetDesktopSubmenus();
  });

  resetDesktopSubmenus();
})(jQuery);

const initProductCatalogMenu = () => {
  const menus = doc.querySelectorAll(".js-aw-product-menu");

  if (!menus.length) {
    return;
  }

  const getMenuHeader = (menu) => {
    return (
      menu.closest("header") ||
      doc.querySelector("#masthead") ||
      doc.querySelector(".header")
    );
  };

  const disableHeaderSticky = (menu) => {
    const header = getMenuHeader(menu);

    if (header) {
      header.classList.add("is-product-menu-open");
    }

    doc.body.classList.add("aw-product-menu-is-open");
  };

  const enableHeaderSticky = (menu) => {
    const header = getMenuHeader(menu);

    if (header) {
      header.classList.remove("is-product-menu-open");
    }

    if (!doc.querySelector(".js-aw-product-menu.is-open")) {
      doc.body.classList.remove("aw-product-menu-is-open");
    }
  };

  const closeMenu = (menu) => {
    const toggle = menu.querySelector(".js-aw-product-menu-toggle");
    const panel = menu.querySelector(".js-aw-product-menu-panel");

    if (!toggle || !panel) {
      return;
    }

    toggle.setAttribute("aria-expanded", "false");
    panel.hidden = true;
    menu.classList.remove("is-open");

    menu.querySelectorAll(".aw-product-menu__item.is-open").forEach((item) => {
      item.classList.remove("is-open");

      const button = item.querySelector(".js-aw-product-submenu-toggle");

      if (button) {
        button.setAttribute("aria-expanded", "false");
      }
    });

    enableHeaderSticky(menu);
  };

  const openMenu = (menu) => {
    const toggle = menu.querySelector(".js-aw-product-menu-toggle");
    const panel = menu.querySelector(".js-aw-product-menu-panel");

    if (!toggle || !panel) {
      return;
    }

    toggle.setAttribute("aria-expanded", "true");
    panel.hidden = false;
    menu.classList.add("is-open");

    disableHeaderSticky(menu);
  };

  menus.forEach((menu) => {
    const toggle = menu.querySelector(".js-aw-product-menu-toggle");

    if (!toggle) {
      return;
    }

    toggle.addEventListener("click", () => {
      const isOpen = menu.classList.contains("is-open");

      menus.forEach(closeMenu);

      if (!isOpen) {
        openMenu(menu);
      }
    });

    menu.querySelectorAll(".js-aw-product-submenu-toggle").forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();

        const item = button.closest(".aw-product-menu__item");

        if (!item) {
          return;
        }

        const isOpen = item.classList.contains("is-open");

        item.parentElement
          ?.querySelectorAll(".aw-product-menu__item.is-open")
          .forEach((openItem) => {
            if (openItem !== item) {
              openItem.classList.remove("is-open");

              const openButton = openItem.querySelector(
                ".js-aw-product-submenu-toggle",
              );

              if (openButton) {
                openButton.setAttribute("aria-expanded", "false");
              }
            }
          });

        item.classList.toggle("is-open", !isOpen);
        button.setAttribute("aria-expanded", String(!isOpen));
      });
    });
  });

  doc.addEventListener("click", (event) => {
    if (event.target.closest(".js-aw-product-menu")) {
      return;
    }

    menus.forEach(closeMenu);
  });

  doc.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
      return;
    }

    menus.forEach(closeMenu);
  });
};

const initMobileNavTabs = () => {
  const tabs = doc.querySelectorAll(".js-aw-mobile-nav-tab");
  const panels = doc.querySelectorAll(".js-aw-mobile-nav-panel");

  if (!tabs.length || !panels.length) {
    return;
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = tab.dataset.awMobileTab;

      tabs.forEach((item) => {
        const isActive = item === tab;

        item.classList.toggle("is-active", isActive);
        item.setAttribute("aria-selected", isActive ? "true" : "false");
      });

      panels.forEach((panel) => {
        panel.classList.toggle(
          "is-active",
          panel.dataset.awMobilePanel === target,
        );
      });
    });
  });
};

const initMobileCategoryAccordion = () => {
  const root = doc.querySelector(".js-aw-mobile-categories");

  if (!root) {
    return;
  }

  root.addEventListener("click", (event) => {
    const button = event.target.closest(".js-aw-mobile-category-toggle");

    if (!button) {
      return;
    }

    event.preventDefault();

    const item = button.closest(".aw-mobile-categories__item");
    const childrenId = button.getAttribute("aria-controls");
    const children = childrenId ? doc.getElementById(childrenId) : null;

    if (!item || !children) {
      return;
    }

    const isOpen = item.classList.contains("is-open");

    item.classList.toggle("is-open", !isOpen);
    button.setAttribute("aria-expanded", !isOpen ? "true" : "false");
    children.hidden = isOpen;
  });
};
initProductCatalogMenu();
initMobileNavTabs();
initMobileCategoryAccordion();
const init = () => {
  setHeaderHeightVar();
  initLanguageSwitcher();
  initBurger();
  initCartQtyControls();
  initGoogleReviews();
  initSearchModal();
  initProductCatalogMenu();
  initMobileNavTabs();
  initMobileCategoryAccordion();

  win.addEventListener("resize", setHeaderHeightVar);
};

/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ (() => {

(function ($) {
  "use strict";

  var doc = document;
  var win = window;

  /**
   * Helpers
   */
  var setHeaderHeightVar = function setHeaderHeightVar() {
    var masthead = doc.querySelector("#masthead");
    if (!masthead) return;
    doc.documentElement.style.setProperty("--aw-header-h", "".concat(masthead.offsetHeight, "px"));
  };

  /**
   * Language switcher
   */
  var initLanguageSwitcher = function initLanguageSwitcher() {
    doc.addEventListener("change", function (e) {
      var select = e.target.closest(".aw-lang__select");
      if (!select || !select.value) return;
      win.location.href = select.value;
    });
  };

  /**
   * Burger / mobile nav
   */
  var initBurger = function initBurger() {
    var $burger = $(".burger");
    var $burgerSpan = $(".burger span");
    var $nav = $("#site-navigation");
    var $body = $("body");
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
  var initCartQtyControls = function initCartQtyControls() {
    $(document).on("click", ".cart-qty.plus, .cart-qty.minus", function (e) {
      e.preventDefault();
      var $input = $(this).parent().find(".input-text.qty.text, input.qty");
      if (!$input.length) return;
      var current = parseInt($input.val(), 10) || 0;
      var next = current;
      if ($(this).hasClass("plus")) {
        next = current + 1;
      } else {
        next = Math.max(0, current - 1);
      }
      $input.val(next).trigger("change");
    });
    var timeout;
    $(".woocommerce").on("change", "input.qty", function () {
      if (timeout) {
        clearTimeout(timeout);
      }
      timeout = setTimeout(function () {
        $("[name='update_cart']").trigger("click");
      }, 100);
    });
  };

  /**
   * Google reviews modal
   */
  var initGoogleReviews = function initGoogleReviews() {
    var roots = doc.querySelectorAll("[data-aw-gr]");
    if (!roots.length) return;
    var starSvg = "\n      <svg viewBox=\"0 0 24 24\" aria-hidden=\"true\">\n        <path d=\"M12 17.27l5.18 3.04-1.39-5.9L20.5 10l-6.03-.52L12 4 9.53 9.48 3.5 10l4.71 4.41-1.39 5.9L12 17.27z\"></path>\n      </svg>\n    ";
    var renderStars = function renderStars(container, rating) {
      if (!container) return;
      var normalized = Math.max(0, Math.min(5, Number(rating || 0)));
      container.innerHTML = "";
      for (var i = 1; i <= 5; i++) {
        var span = doc.createElement("span");
        span.className = "aw-gr-star ".concat(i <= Math.round(normalized) ? "aw-gr-star--on" : "aw-gr-star--off");
        span.innerHTML = starSvg;
        container.appendChild(span);
      }
    };
    roots.forEach(function (root) {
      var rating = root.getAttribute("data-rating") || "0";
      renderStars(root.querySelector("[data-aw-gr-stars]"), rating);
      var openBtn = root.querySelector("[data-aw-gr-open]");
      var tpl = root.querySelector("[data-aw-gr-template]");
      if (!openBtn || !tpl) return;
      var modalEl = null;
      var lastActive = null;
      var onKeydown = function onKeydown(e) {
        if (e.key === "Escape") {
          close();
        }
      };
      var close = function close() {
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
      var open = function open() {
        if (modalEl) return;
        lastActive = doc.activeElement;
        modalEl = tpl.content.firstElementChild.cloneNode(true);
        doc.body.appendChild(modalEl);
        renderStars(modalEl.querySelector(".aw-gr-modal__stars"), rating);
        modalEl.querySelectorAll(".aw-gr-card__stars[data-rating]").forEach(function (el) {
          renderStars(el, el.getAttribute("data-rating"));
        });
        modalEl.querySelectorAll("[data-aw-gr-close]").forEach(function (btn) {
          btn.addEventListener("click", close);
        });
        doc.addEventListener("keydown", onKeydown);
        doc.body.style.overflow = "hidden";
        openBtn.setAttribute("aria-expanded", "true");
        var closeBtn = modalEl.querySelector(".aw-gr-modal__close");
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
  var initSearchModal = function initSearchModal() {
    var modal = doc.querySelector("#site-search-modal");
    if (!modal) return;
    var openButtons = doc.querySelectorAll(".js-search-open");
    var closeButtons = modal.querySelectorAll(".js-search-close");
    var form = modal.querySelector(".js-search-form");
    var input = modal.querySelector(".search-modal__input");
    var results = modal.querySelector("#aw-search-live-results");
    var refreshButton = modal.querySelector(".js-search-refresh");
    if (!form || !input) return;
    var lastFocusedElement = null;
    var getFocusableElements = function getFocusableElements() {
      return modal.querySelectorAll('a[href], button:not([disabled]), textarea, input:not([disabled]), select, [tabindex]:not([tabindex="-1"])');
    };
    var triggerLiveSearch = function triggerLiveSearch() {
      var _input$value;
      input.dispatchEvent(new KeyboardEvent("keyup", {
        bubbles: true,
        key: ((_input$value = input.value) === null || _input$value === void 0 ? void 0 : _input$value.slice(-1)) || "a"
      }));
      win.dispatchEvent(new Event("resize"));
    };
    var openModal = function openModal() {
      lastFocusedElement = doc.activeElement;
      modal.hidden = false;
      modal.setAttribute("aria-hidden", "false");
      doc.body.classList.add("search-modal-open");
      openButtons.forEach(function (button) {
        button.setAttribute("aria-expanded", "true");
      });
      win.requestAnimationFrame(function () {
        input.focus();
        if (input.value.trim().length >= 2) {
          triggerLiveSearch();
        }
      });
    };
    var closeModal = function closeModal() {
      modal.hidden = true;
      modal.setAttribute("aria-hidden", "true");
      doc.body.classList.remove("search-modal-open");
      openButtons.forEach(function (button) {
        button.setAttribute("aria-expanded", "false");
      });
      if (lastFocusedElement) {
        lastFocusedElement.focus();
      }
    };
    openButtons.forEach(function (button) {
      button.addEventListener("click", openModal);
    });
    closeButtons.forEach(function (button) {
      button.addEventListener("click", closeModal);
    });
    modal.addEventListener("click", function (event) {
      if (event.target.classList.contains("js-search-close")) {
        closeModal();
      }
    });
    doc.addEventListener("keydown", function (event) {
      if (modal.hidden) return;
      if (event.key === "Escape") {
        closeModal();
        return;
      }
      if (event.key === "Tab") {
        var focusable = Array.from(getFocusableElements());
        if (!focusable.length) return;
        var first = focusable[0];
        var last = focusable[focusable.length - 1];
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
    form.addEventListener("submit", function () {
      var action = form.getAttribute("action");
      var fallbackAction = form.dataset.searchAction;
      if ((!action || action === "#") && fallbackAction) {
        form.setAttribute("action", fallbackAction);
      }
    });

    /**
     * Klik w przycisk "Szukaj" -> zwykły submit formularza.
     */
    if (refreshButton) {
      refreshButton.addEventListener("click", function () {
        form.requestSubmit();
      });
    }

    /**
     * Klik w wynik zamyka modal.
     */
    if (results) {
      results.addEventListener("click", function (event) {
        var link = event.target.closest("a");
        if (link) {
          closeModal();
        }
      });
    }
  };

  /**
   * Init
   */
  var init = function init() {
    setHeaderHeightVar();
    initLanguageSwitcher();
    initBurger();
    initCartQtyControls();
    initGoogleReviews();
    initSearchModal();
    win.addEventListener("resize", setHeaderHeightVar);
  };
  if (doc.readyState === "loading") {
    doc.addEventListener("DOMContentLoaded", init, {
      once: true
    });
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
      submenuId = "submenu-" + index + "-" + Math.random().toString(36).slice(2, 8);
      $submenu.attr("id", submenuId);
    }
    var $row = $('<div class="submenu-item-row"></div>');
    $link.before($row);
    $row.append($link);
    var $button = $('<button type="button" class="submenu-toggle" aria-expanded="false" aria-label="Rozwiń submenu"></button>');
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

/***/ }),

/***/ "./gutenberg-styles/statystyki.scss":
/*!******************************************!*\
  !*** ./gutenberg-styles/statystyki.scss ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./sass/index.scss":
/*!*************************!*\
  !*** ./sass/index.scss ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./gutenberg-styles/aw-cat-product.scss":
/*!**********************************************!*\
  !*** ./gutenberg-styles/aw-cat-product.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./gutenberg-styles/aw-locations.scss":
/*!********************************************!*\
  !*** ./gutenberg-styles/aw-locations.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./gutenberg-styles/face-aw.scss":
/*!***************************************!*\
  !*** ./gutenberg-styles/face-aw.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./gutenberg-styles/hero.scss":
/*!************************************!*\
  !*** ./gutenberg-styles/hero.scss ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./gutenberg-styles/product-aw.scss":
/*!******************************************!*\
  !*** ./gutenberg-styles/product-aw.scss ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/dist/app": 0,
/******/ 			"css-blocks/product-aw": 0,
/******/ 			"css-blocks/hero": 0,
/******/ 			"css-blocks/face-aw": 0,
/******/ 			"css-blocks/aw-locations": 0,
/******/ 			"css-blocks/aw-cat-product": 0,
/******/ 			"src/index": 0,
/******/ 			"css-blocks/statystyki": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunktosha504_my_start_theme"] = self["webpackChunktosha504_my_start_theme"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./src/index.js")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/aw-cat-product.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/aw-locations.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/face-aw.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/hero.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/product-aw.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./gutenberg-styles/statystyki.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-locations","css-blocks/aw-cat-product","src/index","css-blocks/statystyki"], () => (__webpack_require__("./sass/index.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
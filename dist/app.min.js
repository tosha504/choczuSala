/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ (() => {

(function () {
  console.log("ready");
  var burger = jQuery(".burger"),
    burgerSpan = jQuery(".burger span"),
    nav = jQuery("#site-navigation"),
    body = jQuery("body");
  document.addEventListener("change", function (e) {
    var select = e.target.closest(".aw-lang__select");
    if (!select) return;
    window.location.href = select.value;
  });
  burger.on("click", function () {
    burgerSpan.toggleClass("active");
    nav.toggleClass("active");
    body.toggleClass("fixed-page");
  });
  setTimeout(function () {
    if (getCookie("popupCookie") != "submited") {
      jQuery(".cookies").css("display", "block").hide().fadeIn(2000);
    }
    jQuery("a.submit").click(function () {
      jQuery(".cookies").fadeOut();
      //sets the coookie to five minutes if the popup is submited (whole numbers = days)
      setCookie("popupCookie", "submited", 7);
    });
  }, 5000);
  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
})(jQuery);
document.addEventListener("DOMContentLoaded", function () {
  var roots = document.querySelectorAll("[data-aw-gr]");
  if (!roots.length) return;
  var starSvg = "\n    <svg viewBox=\"0 0 24 24\" aria-hidden=\"true\">\n      <path d=\"M12 17.27l5.18 3.04-1.39-5.9L20.5 10l-6.03-.52L12 4 9.53 9.48 3.5 10l4.71 4.41-1.39 5.9L12 17.27z\"></path>\n    </svg>\n  ";
  function renderStars(container, rating) {
    var theme = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "dark";
    if (!container) return;
    var r = Math.max(0, Math.min(5, Number(rating || 0)));
    container.innerHTML = "";
    for (var i = 1; i <= 5; i++) {
      var span = document.createElement("span");
      span.className = "aw-gr-star " + (i <= Math.round(r) ? "aw-gr-star--on" : "aw-gr-star--off");
      // w badge off gwiazdy są półprzezroczyste na białym, w modalach też OK
      span.innerHTML = starSvg;
      container.appendChild(span);
    }
  }
  roots.forEach(function (root) {
    // badge stars (rating firmy)
    var rating = root.getAttribute("data-rating") || "0";
    renderStars(root.querySelector("[data-aw-gr-stars]"), rating);
    var openBtn = root.querySelector("[data-aw-gr-open]");
    var tpl = root.querySelector("[data-aw-gr-template]");
    if (!openBtn || !tpl) return;
    var modalEl = null;
    var lastActive = null;
    var open = function open() {
      if (modalEl) return;
      lastActive = document.activeElement;
      modalEl = tpl.content.firstElementChild.cloneNode(true);
      document.body.appendChild(modalEl);

      // modal stars
      renderStars(modalEl.querySelector(".aw-gr-modal__stars"), rating);

      // card stars per review
      modalEl.querySelectorAll(".aw-gr-card__stars[data-rating]").forEach(function (el) {
        renderStars(el, el.getAttribute("data-rating"));
      });

      // zamykanie
      modalEl.querySelectorAll("[data-aw-gr-close]").forEach(function (btn) {
        btn.addEventListener("click", close);
      });
      document.addEventListener("keydown", onKeydown);
      document.body.style.overflow = "hidden";
      openBtn.setAttribute("aria-expanded", "true");

      // focus
      var closeBtn = modalEl.querySelector(".aw-gr-modal__close");
      closeBtn && closeBtn.focus();
    };
    var close = function close() {
      if (!modalEl) return;
      modalEl.remove();
      modalEl = null;
      document.removeEventListener("keydown", onKeydown);
      document.body.style.overflow = "";
      openBtn.setAttribute("aria-expanded", "false");
      lastActive && lastActive.focus();
    };
    var onKeydown = function onKeydown(e) {
      if (e.key === "Escape") close();
    };
    openBtn.addEventListener("click", open);
  });
});

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


/***/ }),

/***/ "./gutenberg-styles/statystyki.scss":
/*!******************************************!*\
  !*** ./gutenberg-styles/statystyki.scss ***!
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
/******/ 			"css-blocks/statystyki": 0,
/******/ 			"css-blocks/product-aw": 0,
/******/ 			"css-blocks/hero": 0,
/******/ 			"css-blocks/face-aw": 0,
/******/ 			"css-blocks/aw-cat-product": 0,
/******/ 			"src/index": 0
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
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./src/index.js")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./gutenberg-styles/aw-cat-product.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./gutenberg-styles/face-aw.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./gutenberg-styles/hero.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./gutenberg-styles/product-aw.scss")))
/******/ 	__webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./gutenberg-styles/statystyki.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css-blocks/statystyki","css-blocks/product-aw","css-blocks/hero","css-blocks/face-aw","css-blocks/aw-cat-product","src/index"], () => (__webpack_require__("./sass/index.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
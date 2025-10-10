/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ (() => {

(function () {
  console.log('ready');
  var burger = jQuery(".burger"),
    burgerSpan = jQuery(".burger span"),
    nav = jQuery('.header__nav'),
    body = jQuery('body');
  burger.on("click", function () {
    burgerSpan.toggleClass("active");
    nav.toggleClass("active");
    body.toggleClass("fixed-page");
  });
  setTimeout(function () {
    if (getCookie('popupCookie') != 'submited') {
      jQuery('.cookies').css("display", "block").hide().fadeIn(2000);
    }
    jQuery('a.submit').click(function () {
      jQuery('.cookies').fadeOut();
      //sets the coookie to five minutes if the popup is submited (whole numbers = days)
      setCookie('popupCookie', 'submited', 7);
    });
  }, 5000);
  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
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

/***/ }),

/***/ "./sass/index.scss":
/*!*************************!*\
  !*** ./sass/index.scss ***!
  \*************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\nSassError: Can't find stylesheet to import.\n  ╷\n3 │ @use \"../pages/404-page\" as page404;\r\n  │ ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^\n  ╵\n  sass\\components\\_base.scss 3:1  @use\n  sass\\index.scss 2:1             root stylesheet\n    at processResult (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\webpack\\lib\\NormalModule.js:764:19)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\webpack\\lib\\NormalModule.js:866:5\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\loader-runner\\lib\\LoaderRunner.js:400:11\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\loader-runner\\lib\\LoaderRunner.js:252:18\n    at context.callback (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\loader-runner\\lib\\LoaderRunner.js:124:13)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass-loader\\dist\\index.js:54:7\n    at Function.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:125861:16)\n    at render_closure1.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:107660:12)\n    at _RootZone.runBinary$3$3 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:39509:18)\n    at _FutureListener.handleError$1 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38027:21)\n    at _Future__propagateToListeners_handleError.call$0 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38342:49)\n    at Object._Future__propagateToListeners (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5208:77)\n    at _Future._completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38193:9)\n    at _AsyncAwaitCompleter.completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37791:12)\n    at Object._asyncRethrow (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:4986:17)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:28424:20\n    at _wrapJsFunctionForAsync_closure.$protected (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5011:15)\n    at _wrapJsFunctionForAsync_closure.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37810:12)\n    at _awaitOnObject_closure0.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37804:25)\n    at _RootZone.runBinary$3$3 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:39509:18)\n    at _FutureListener.handleError$1 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38027:21)\n    at _Future__propagateToListeners_handleError.call$0 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38342:49)\n    at Object._Future__propagateToListeners (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5208:77)\n    at _Future._completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38193:9)\n    at _AsyncAwaitCompleter.completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37791:12)\n    at Object._asyncRethrow (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:4986:17)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:20502:20\n    at _wrapJsFunctionForAsync_closure.$protected (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5011:15)\n    at _wrapJsFunctionForAsync_closure.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37810:12)\n    at _awaitOnObject_closure0.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37804:25)\n    at _RootZone.runBinary$3$3 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:39509:18)\n    at _FutureListener.handleError$1 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38027:21)\n    at _Future__propagateToListeners_handleError.call$0 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38342:49)\n    at Object._Future__propagateToListeners (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5208:77)\n    at _Future._completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38193:9)\n    at _AsyncAwaitCompleter.completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37791:12)\n    at Object._asyncRethrow (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:4986:17)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:20547:20\n    at _wrapJsFunctionForAsync_closure.$protected (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5011:15)\n    at _wrapJsFunctionForAsync_closure.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37810:12)\n    at _awaitOnObject_closure0.call$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37804:25)\n    at _RootZone.runBinary$3$3 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:39509:18)\n    at _FutureListener.handleError$1 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38027:21)\n    at _Future__propagateToListeners_handleError.call$0 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38342:49)\n    at Object._Future__propagateToListeners (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5208:77)\n    at _Future._completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:38193:9)\n    at _AsyncAwaitCompleter.completeError$2 (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:37791:12)\n    at Object._asyncRethrow (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:4986:17)\n    at C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:85735:24\n    at _wrapJsFunctionForAsync_closure.$protected (C:\\Users\\anton\\Local Sites\\shop\\app\\public\\wp-content\\themes\\tosha504-my-start-theme\\node_modules\\sass\\sass.dart.js:5011:15)");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	__webpack_modules__["./src/index.js"]();
/******/ 	// This entry module doesn't tell about it's top-level declarations so it can't be inlined
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./sass/index.scss"]();
/******/ 	
/******/ })()
;
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/javascript/custom-cookie-message-popup.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/javascript/custom-cookie-message-popup.js":
/*!*******************************************************!*\
  !*** ./src/javascript/custom-cookie-message-popup.js ***!
  \*******************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _sass_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sass/style.scss */ \"./src/sass/style.scss\");\n/* harmony import */ var _sass_style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_sass_style_scss__WEBPACK_IMPORTED_MODULE_0__);\n\n\njQuery( function ( $ ) {\n\tlet cookie = document.cookie.match( '(^|;) ?custom_cookie_message=([^;]*)(;|$)' );\n\n\tlet customCookieMessage = {\n\t\tstates: null, init: function () {\n\n\t\t\tif ( null === cookie ) {\n\t\t\t\tthis.showCookieNotice();\n\t\t\t}\n\n\t\t\t$( 'body' )\n\t\t\t\t.on( 'click', '#custom-cookie-message-preference', this.changeSettings )\n\t\t\t\t.on( 'click', '.custom-cookie-message-modal__close', this.killModal )\n\t\t\t\t.on( 'click', '#custom-cookie-message-modal', this.actionModal )\n\t\t\t\t.on( 'click', '.custom-cookie-message-modal__item', this.actionTab )\n\t\t\t\t.on( 'click', '#ccm_cookie_preferences', this.cookiePreferences )\n\t\t\t\t.on( 'click', '#ccm-save-preference,.custom-cookie-message-banner__close,.custom-cookie-message-banner__accept', this.savePreferences );\n\t\t},\n\n\t\tcookiePreferences: function ( event ) {\n\t\t\tevent.stopPropagation();\n\n\t\t\tcustomCookieMessage.showCookieNotice();\n\n\t\t\tsetTimeout( function () {\n\t\t\t\tcustomCookieMessage.changeSettings();\n\t\t\t\t$( '#custom-cookie-message-banner' ).slideUp().remove();\n\t\t\t\t$( 'body' ).animate({marginBottom: '0px', marginTop: '0px'});\n\t\t\t}, 350 );\n\n\t\t},\n\n\t\tchangeSettings: function () {\n\t\t\tlet modalBlock = $( '#custom-cookie-message-modal' );\n\t\t\tif ( modalBlock.hasClass( 'custom-cookie-message-modal--off' ) ) {\n\t\t\t\tmodalBlock.removeClass( 'custom-cookie-message-modal--off' ).addClass( 'custom-cookie-message-modal--on' );\n\t\t\t\t$( '#custom-cookie-message-banner' ).addClass( 'hide' );\n\t\t\t}\n\n\t\t},\n\n\t\tkillModal: function () {\n\t\t\tlet modal = $( '#custom-cookie-message-modal' );\n\t\t\tif ( modal.hasClass( 'custom-cookie-message-modal--on' ) ) {\n\t\t\t\tmodal.removeClass( 'custom-cookie-message-modal--on' ).addClass( 'custom-cookie-message-modal--off' );\n\t\t\t\t$( '#custom-cookie-message-banner' ).removeClass( 'hide' );\n\t\t\t}\n\t\t},\n\n\t\tactionModal: function ( event ) {\n\t\t\tif ( event.target !== event.currentTarget ) {\n\t\t\t\treturn;\n\t\t\t}\n\t\t\tcustomCookieMessage.killModal();\n\t\t},\n\n\t\tactionTab: function () {\n\t\t\tlet self = $( this );\n\t\t\tlet classList = self.attr( 'class' ).split( /\\s+/ );\n\t\t\tlet re = /--(.*)_message$/g;\n\n\t\t\tfor ( let i = 0; i < classList.length; i++ ) {\n\t\t\t\tif ( classList[ i ].match( re ) ) {\n\t\t\t\t\tlet contentBox = re.exec( classList[ i ] );\n\t\t\t\t\tlet baseTabClass = '.custom-cookie-message-modal__item--' + contentBox[ 1 ] + '_message';\n\t\t\t\t\tlet baseMessageClass = '.custom-cookie-message-modal__' + contentBox[ 1 ] + '_message';\n\n\t\t\t\t\tif ( !$( baseTabClass ).hasClass( 'custom-cookie-message-modal__item--active' ) ) {\n\t\t\t\t\t\t$( '.custom-cookie-message-modal__item.custom-cookie-message-modal__item--active' ).removeClass( 'custom-cookie-message-modal__item--active' );\n\t\t\t\t\t\t$( baseTabClass ).addClass( 'custom-cookie-message-modal__item--active' );\n\n\t\t\t\t\t\t$( '.custom-cookie-message-modal__content div' ).not( '.hide' ).addClass( 'hide' );\n\t\t\t\t\t\t$( baseMessageClass ).removeClass( 'hide' );\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t}\n\t\t},\n\n\t\tsavePreferences: function () {\n\t\t\t$( '#custom-cookie-message-modal' ).remove();\n\t\t\t$( '#custom-cookie-message-banner' ).slideUp().remove();\n\t\t\t$( 'body' ).animate({marginBottom: '0px', marginTop: '0px'});\n\n\t\t\t$.ajax( {\n\t\t\t\turl: customCookieMessageLocalize.rest_url_preference,\n\t\t\t\tmethod: 'POST',\n\t\t\t\tcache: false,\n\t\t\t\tdata: {\n\t\t\t\t\t'functional': $( '#ccm-functional' ).prop( 'checked' ),\n\t\t\t\t\t'adsvertising': $( '#ccm-advertising' ).prop( 'checked' ),\n\t\t\t\t},\n\t\t\t\tbeforeSend: function ( xhr ) {\n\t\t\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageLocalize.wp_rest_nonce );\n\t\t\t\t},\n\t\t\t\terror: function ( error ) {\n\t\t\t\t\tconsole.error( 'Custom cookie plugin: Could not save preferences' );\n\t\t\t\t\tconsole.error( error );\n\t\t\t\t}\n\t\t\t} )\n\t\t},\n\n\t\tshowCookieNotice: function () {\n\t\t\t$.ajax( {\n\t\t\t\turl: customCookieMessageLocalize.rest_url_banner,\n\t\t\t\tmethod: 'GET',\n\t\t\t\tcache: false,\n\t\t\t\tcontentType: false,\n\t\t\t\tprocessData: false,\n\t\t\t\tdata: 'lang=' + customCookieMessageLocalize.lang,\n\t\t\t\tbeforeSend: function ( xhr ) {\n\t\t\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageLocalize.wp_rest_nonce );\n\t\t\t\t},\n\t\t\t} )\n\t\t\t .done( function ( response ) {\n\t\t\t\t if ( null !== response.template && '' !== customCookieMessageLocalize.options ) {\n\t\t\t\t\t if ( 'bottom-fixed' === customCookieMessageLocalize.options.general.location_options ) {\n\t\t\t\t\t\t $( 'body' ).append( response.template );\n\t\t\t\t\t }\n\t\t\t\t\t else {\n\t\t\t\t\t\t $( 'body' ).prepend( response.template );\n\t\t\t\t\t }\n\t\t\t\t\t /* Get height of the banner before showing it */\n\t\t\t\t\t var get_height = $( '#custom-cookie-message-banner' ).clone().attr(\"id\", false).css({display:\"block\", position:\"absolute\"});\n\t\t\t\t\t $( 'body' ).append(get_height);\n\t\t\t\t\t var scroll_height = get_height.outerHeight();\n\n\t\t\t\t\t get_height.remove();\n\n\t\t\t\t\t /* banner animation */\n\t\t\t\t\t if ( 'scroll' === customCookieMessageLocalize.options.styles.banner_animation ) {\n\t\t\t\t\t\t $( '#custom-cookie-message-banner' ).slideDown();\n\t\t\t\t\t } else\n\t\t\t\t\t if ( 'fade' === customCookieMessageLocalize.options.styles.banner_animation ) {\n\t\t\t\t\t\t $( '#custom-cookie-message-banner' ).fadeIn();\n\t\t\t\t\t }\n\t\t\t\t\t else {\n\t\t\t\t\t\t $( '#custom-cookie-message-banner' ).show();\n\t\t\t\t\t }\n\t\t\t\t\t /* Scroll content container */\n\t\t\t\t\t if ( 'yes' === customCookieMessageLocalize.options.styles.scroll_body ) {\n\t\t\t\t\t\t if ( 'bottom-fixed' === customCookieMessageLocalize.options.general.location_options ) {\n\t\t\t\t\t\t\t $( 'body' ).animate({marginBottom: scroll_height});\n\t\t\t\t\t\t }\n\t\t\t\t\t\t else {\n\t\t\t\t\t\t\t $( 'body' ).animate({marginTop: scroll_height});\n\t\t\t\t\t\t }\n\t\t\t\t\t }\n\t\t\t\t }\n\t\t\t\t else {\n\t\t\t\t\t console.warn( 'Custom Cookie Message options are not set' );\n\t\t\t\t }\n\t\t\t } );\n\t\t},\n\t};\n\n\n\tcustomCookieMessage.init();\n\n} );\n\n\n//# sourceURL=webpack:///./src/javascript/custom-cookie-message-popup.js?");

/***/ }),

/***/ "./src/sass/style.scss":
/*!*****************************!*\
  !*** ./src/sass/style.scss ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/sass/style.scss?");

/***/ })

/******/ });
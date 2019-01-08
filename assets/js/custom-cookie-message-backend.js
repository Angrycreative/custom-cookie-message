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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/javascript/custom-cookie-message-backend.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/javascript/custom-cookie-message-backend.js":
/*!*********************************************************!*\
  !*** ./src/javascript/custom-cookie-message-backend.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery( function ( $ ) {\n\t'use strict';\n\n\tvar cookies = [];\n\n\t/**\n\t * Retrieve a list of cookies.\n\t *\n\t * @returns {Array|*}\n\t */\n\tfunction getCookies () {\n\t\tvar pairs = document.cookie.split( ';' );\n\t\tvar cookies;\n\n\t\tcookies = pairs.map( function ( cookie ) {\n\t\t\treturn cookie.split( '=' )[ 0 ];\n\t\t});\n\n\t\treturn cookies;\n\t}\n\n\tjQuery.get({\n\t\turl: '/',\n\t\tcrossDomain: true,\n\t\tbeforeSend: function ( xhr ) {\n\t\t\txhr.setRequestHeader( 'withCredentials', true );\n\t\t}\n\t})\n\t      .done( function () {\n\t\t      cookies = getCookies();\n\t\t      var html;\n\n\t\t      cookies.map( function ( cookie ) {\n\t\t\t      html = '<div class=\"cookie\">';\n\t\t\t      html = html + 'Cookie machine name: ' + cookie;\n\t\t\t      html = html + '<label for=\"' + cookie + '-label\">Label: ';\n\t\t\t      html = html + '<input type=\"text\" name=\"cookie_list[' + cookie.trim + '][label]\" id=\"' + cookie + '-label\">';\n\t\t\t      html = html + '</label>';\n\t\t\t      html = html + '</div>';\n\n\t\t\t      // TODO: This code should be refactored.\n\t\t\t      // $( '.cookie_list_wrapper' ).append( html ).fadeIn();\n\t\t      });\n\t      });\n\n\t// Add Color Picker to all inputs that have 'color-field' class\n\t$( '.cpa-color-picker' ).wpColorPicker();\n\n\tlet life_time_value = 0;\n\n\tswitch ( parseInt( $( '#life_time_slider_amount' ).val() ) ) {\n\t\tcase 0:\n\t\t\tlife_time_value = 1;\n\t\t\tbreak;\n\t\tcase customCookieMessageAdminLocalize.life_time.week_seconds:\n\t\t\tlife_time_value = 2;\n\t\t\tbreak;\n\t\tcase customCookieMessageAdminLocalize.life_time.month_seconds:\n\t\t\tlife_time_value = 3;\n\t\t\tbreak;\n\t\tcase customCookieMessageAdminLocalize.life_time.year_seconds:\n\t\t\tlife_time_value = 4;\n\t\t\tbreak;\n\t\tdefault:\n\t\t\tlife_time_value = 5;\n\t\t\tbreak;\n\t}\n\n\t$( '#life_time_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( life_time_value ),\n\t\tmin: 1,\n\t\tmax: 5,\n\t\tcreate: function () {\n\t\t\tswitch ( parseInt( $( '#life_time_slider_amount' ).val() ) ) {\n\t\t\t\tcase 0:\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.no_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase customCookieMessageAdminLocalize.life_time.week_seconds:\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.week_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase customCookieMessageAdminLocalize.life_time.month_seconds:\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.month_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase customCookieMessageAdminLocalize.life_time.year_seconds:\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.year_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tdefault:\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.end_less_life_time );\n\t\t\t\t\tbreak;\n\t\t\t}\n\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\tswitch ( parseInt( ui.value ) ) {\n\t\t\t\tcase 1:\n\t\t\t\t\t$( '#life_time_slider_amount' ).val( 0 );\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.no_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase 2:\n\t\t\t\t\t$( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.week_seconds );\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.week_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase 3:\n\t\t\t\t\t$( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.month_seconds );\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.month_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase 4:\n\t\t\t\t\t$( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.year_seconds );\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.year_life_time );\n\t\t\t\t\tbreak;\n\t\t\t\tcase 5:\n\t\t\t\t\t$( '#life_time_slider_amount' ).val( 9999 * customCookieMessageAdminLocalize.life_time.year_seconds );\n\t\t\t\t\t$( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.end_less_life_time );\n\t\t\t\t\tbreak;\n\t\t\t}\n\t\t},\n\t});\n\n\t$( '#opacity_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( $( '#opacity_slider_amount' ).val() ),\n\t\tmin: 50,\n\t\tmax: 100,\n\t\tcreate: function () {\n\t\t\t$( '#opacity_slider_handle' ).text( $( '#opacity_slider_amount' ).val() + '%' );\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\t$( '#opacity_slider_handle' ).text( ui.value + '%' );\n\t\t\t$( '#opacity_slider_amount' ).val( ui.value );\n\t\t},\n\t});\n\n\t$( '#modal_overlay_opacity_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( $( '#modal_overlay_opacity_amount' ).val() ),\n\t\tmin: 50,\n\t\tmax: 100,\n\t\tcreate: function () {\n\t\t\t$( '#modal_overlay_opacity_slider_handle' ).text( $( '#modal_overlay_opacity_amount' ).val() + '%' );\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\t$( '#modal_overlay_opacity_slider_handle' ).text( ui.value + '%' );\n\t\t\t$( '#modal_overlay_opacity_amount' ).val( ui.value );\n\t\t},\n\t});\n\n\t$( '#message_height_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( $( '#message_height_slider_amount' ).val() ),\n\t\tmin: 5,\n\t\tmax: 40,\n\t\tcreate: function () {\n\t\t\t$( '#message_height_custom_handle' ).text( $( '#message_height_slider_amount' ).val() + ' px' );\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\t$( '#message_height_custom_handle' ).text( ui.value + ' px' );\n\t\t\t$( '#message_height_slider_amount' ).val( ui.value );\n\t\t},\n\t});\n\n\t$( '#button_height_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( $( '#button_height_slider_amount' ).val() ),\n\t\tmin: 0,\n\t\tmax: 40,\n\t\tcreate: function () {\n\t\t\t$( '#button_height_handle' ).text( $( '#button_height_slider_amount' ).val() + ' px' );\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\t$( '#button_height_handle' ).text( ui.value + ' px' );\n\t\t\t$( '#button_height_slider_amount' ).val( ui.value );\n\t\t},\n\t});\n\n\t$( '#button_width_slider' ).slider({\n\t\trange: 'min',\n\t\tvalue: parseInt( $( '#button_width_slider_amount' ).val() ),\n\t\tmin: 5,\n\t\tmax: 40,\n\t\tcreate: function () {\n\t\t\t$( '#button_width_handle' ).text( $( '#button_width_slider_amount' ).val() + ' px' );\n\t\t},\n\t\tslide: function ( event, ui ) {\n\t\t\t$( '#button_width_handle' ).text( ui.value + ' px' );\n\t\t\t$( '#button_width_slider_amount' ).val( ui.value );\n\t\t},\n\t});\n\n\t$( '#cookies_page_link' ).ccmSuggest({\n\t\turl: customCookieMessageAdminLocalize.rest_post_link,\n\t\tcache: false,\n\t\tbeforeSend: function ( xhr ) {\n\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );\n\t\t},\n\t}, {\n\t\tmultiple: false,\n\t});\n\n\t$( '#functional_cookies_ban' ).ccmSuggest({\n\t\turl: customCookieMessageAdminLocalize.rest_cookie_list + '/functional',\n\t\tcache: false,\n\t\tbeforeSend: function ( xhr ) {\n\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );\n\t\t},\n\t}, {\n\t\tmultiple: true,\n\t});\n\n\t$( '#advertising_cookies_ban' ).ccmSuggest({\n\t\turl: customCookieMessageAdminLocalize.rest_cookie_list + '/advertising',\n\t\tcache: false,\n\t\tbeforeSend: function ( xhr ) {\n\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );\n\t\t},\n\t}, {\n\t\tmultiple: true,\n\t});\n\n\t$( '.notice.custom-cookie-message' )\n\t\t.on( 'click', '.custom-cookie-message-upgrade', function ( e ) {\n\t\t\te.preventDefault();\n\n\t\t\tlet formData = new FormData();\n\n\t\t\tformData.append( '_ccm_nonce', customCookieMessageAdminLocalize.ccm_nonce );\n\n\t\t\t$.ajax({\n\t\t\t\turl: customCookieMessageAdminLocalize.rest_url,\n\t\t\t\tmethod: 'POST',\n\t\t\t\tcache: false,\n\t\t\t\tcontentType: false,\n\t\t\t\tprocessData: false,\n\t\t\t\tbeforeSend: function ( xhr ) {\n\t\t\t\t\txhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );\n\t\t\t\t},\n\t\t\t})\n\t\t\t .done( function ( response ) {\n\t\t\t\t $( '.notice.custom-cookie-message' ).fadeOut().remove();\n\t\t\t })\n\t\t\t .fail( function ( response ) {\n\t\t\t\t $( '.notice.custom-cookie-message' ).removeClass( 'notice-info' ).addClass( 'notice-error' );\n\t\t\t });\n\t\t});\n\n\tfunction buttonStyling( checkbox ) {\n\t\tif ( !checkbox.is( ':checked' ) ) {\n\t\t\t$( '#button_height_slider, #button_width_slider' ).css( 'opacity', .5 );\n\t\t\tcheckbox.parents( '.form-table' ).find( '.wp-picker-container' ).css( 'opacity', .5 );\n\t\t\tcheckbox.parents( '.form-table' ).find( '#xclose_styling' ).parents( 'td' ).css( 'opacity', .5 );\n\t\t} else {\n\t\t\t$( '#button_height_slider, #button_width_slider' ).css( 'opacity', 1 );\n\t\t\tcheckbox.parents( '.form-table' ).find( '.wp-picker-container' ).css( 'opacity', 1 );\n\t\t\tcheckbox.parents( '.form-table' ).find( '#xclose_styling' ).parents( 'td' ).css( 'opacity', 1 );\n\t\t}\n\t}\n\n\t$( '#button_styling' ).change( function() {\n\t\tbuttonStyling( $( '#button_styling' ) );\n\t});\n\t$( document ).ready( buttonStyling( $( '#button_styling' ) ) );\n});\n\n\n//# sourceURL=webpack:///./src/javascript/custom-cookie-message-backend.js?");

/***/ })

/******/ });
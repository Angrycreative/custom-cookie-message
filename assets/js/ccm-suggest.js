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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/javascript/ccm-suggest.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/javascript/ccm-suggest.js":
/*!***************************************!*\
  !*** ./src/javascript/ccm-suggest.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/*\n *\tjquery.suggest 1.1b - 2007-08-06\n * Patched by Mark Jaquith with Alexander Dick's \"multiple items\" patch to allow for auto-suggesting of more than one tag before submitting\n * See: http://www.vulgarisoip.com/2007/06/29/jquerysuggest-an-alternative-jquery-based-autocomplete-library/#comment-7228\n *\n *\tUses code and techniques from following libraries:\n *\t1. http://www.dyve.net/jquery/?autocomplete\n *\t2. http://dev.jquery.com/browser/trunk/plugins/interface/iautocompleter.js\n *\n *\tAll the new stuff written by Peter Vulgaris (www.vulgarisoip.com)\n *\tFeel free to do whatever you want with this file\n *\n */\njQuery(function ( $ ) {\n\t$.ccmSuggest = function ( input, options ) {\n\t\tvar $input, $results, timeout, prevLength, cache, cacheSize;\n\n\t\t$input = $( input ).attr( 'autocomplete', 'off' );\n\t\t$results = $( '<ul/>' );\n\n\t\ttimeout = false;\t\t// hold timeout ID for suggestion results to appear\n\t\tprevLength = 0;\t\t\t// last recorded length of $input.val()\n\t\tcache = [];\t\t\t\t// cache MRU list\n\t\tcacheSize = 0;\t\t\t// size of cache in chars (bytes?)\n\n\t\t$results.addClass( options.resultsClass ).appendTo( 'body' );\n\n\t\tresetPosition();\n\t\t$( window )\n\t\t\t.on( 'load', resetPosition ) // just in case user is changing size of page while loading\n\t\t\t.on( 'resize', resetPosition );\n\n\t\t$input.blur( function () {\n\t\t\tsetTimeout( function () { $results.hide(); }, 200 );\n\t\t});\n\n\t\t$input.keydown( processKey );\n\n\t\tfunction resetPosition() {\n\t\t\t// requires jquery.dimension plugin\n\t\t\tvar offset = $input.offset();\n\t\t\t$results.css( {\n\t\t\t\ttop: (offset.top + input.offsetHeight) + 'px',\n\t\t\t\tleft: offset.left + 'px',\n\t\t\t});\n\t\t}\n\n\t\tfunction processKey( e ) {\n\n\t\t\t// handling up/down/escape requires results to be visible\n\t\t\t// handling enter/tab requires that AND a result to be selected\n\t\t\tif ( (/27$|38$|40$/.test( e.keyCode ) && $results.is( ':visible' )) ||\n\t\t\t     (/^13$|^9$/.test( e.keyCode ) && getCurrentResult()) ) {\n\n\t\t\t\tif ( e.preventDefault ) {\n\t\t\t\t\te.preventDefault();\n\t\t\t\t}\n\t\t\t\tif ( e.stopPropagation ) {\n\t\t\t\t\te.stopPropagation();\n\t\t\t\t}\n\n\t\t\t\te.cancelBubble = true;\n\t\t\t\te.returnValue = false;\n\n\t\t\t\tswitch ( e.keyCode ) {\n\n\t\t\t\t\tcase 38: // up\n\t\t\t\t\t\tprevResult();\n\t\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 40: // down\n\t\t\t\t\t\tnextResult();\n\t\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 9:  // tab\n\t\t\t\t\tcase 13: // return\n\t\t\t\t\t\tselectCurrentResult();\n\t\t\t\t\t\tbreak;\n\n\t\t\t\t\tcase 27: //\tescape\n\t\t\t\t\t\t$results.hide();\n\t\t\t\t\t\tbreak;\n\n\t\t\t\t}\n\n\t\t\t}\n\t\t\telse if ( $input.val().length != prevLength ) {\n\n\t\t\t\tif ( timeout ) {\n\t\t\t\t\tclearTimeout( timeout );\n\t\t\t\t}\n\t\t\t\ttimeout = setTimeout( ccm_suggest, options.delay );\n\t\t\t\tprevLength = $input.val().length;\n\n\t\t\t}\n\n\t\t}\n\n\t\tfunction ccmuggest() {\n\n\t\t\tvar q = $.trim( $input.val() ), multipleSepPos, items;\n\n\t\t\tif ( options.multiple ) {\n\t\t\t\tmultipleSepPos = q.lastIndexOf( options.multipleSep );\n\t\t\t\tif ( multipleSepPos != -1 ) {\n\t\t\t\t\tq = $.trim( q.substr( multipleSepPos + options.multipleSep.length ) );\n\t\t\t\t}\n\t\t\t}\n\t\t\tif ( q.length >= options.minchars ) {\n\n\t\t\t\tcached = checkCache( q );\n\n\t\t\t\tif ( cached ) {\n\n\t\t\t\t\tdisplayItems( cached['items'] );\n\n\t\t\t\t}\n\t\t\t\telse {\n\n\t\t\t\t\t$.get( options.source, { q: q }, function ( items ) {\n\n\t\t\t\t\t\t$results.hide();\n\n\t\t\t\t\t\tdisplayItems( items );\n\t\t\t\t\t\taddToCache( q, items, items.length );\n\n\t\t\t\t\t});\n\n\t\t\t\t}\n\n\t\t\t}\n\t\t\telse {\n\n\t\t\t\t$results.hide();\n\n\t\t\t}\n\n\t\t}\n\n\t\tfunction checkCache( q ) {\n\t\t\tvar i;\n\t\t\tfor ( i = 0; i < cache.length; i++ ) {\n\t\t\t\tif ( cache[i]['q'] == q ) {\n\t\t\t\t\tcache.unshift( cache.splice( i, 1 )[0] );\n\t\t\t\t\treturn cache[0];\n\t\t\t\t}\n\t\t\t}\n\n\t\t\treturn false;\n\n\t\t}\n\n\t\tfunction addToCache( q, items, size ) {\n\t\t\tvar cached;\n\t\t\twhile ( cache.length && (cacheSize + size > options.maxCacheSize) ) {\n\t\t\t\tcached = cache.pop();\n\t\t\t\tcacheSize -= cached['size'];\n\t\t\t}\n\n\t\t\tcache.push( {\n\t\t\t\tq: q,\n\t\t\t\tsize: size,\n\t\t\t\titems: items,\n\t\t\t});\n\n\t\t\tcacheSize += size;\n\n\t\t}\n\n\t\tfunction displayItems( items ) {\n\t\t\tvar html = '', i;\n\t\t\tif ( !items ) {\n\t\t\t\treturn;\n\t\t\t}\n\n\t\t\tif ( !items.length ) {\n\t\t\t\t$results.hide();\n\t\t\t\treturn;\n\t\t\t}\n\n\t\t\tresetPosition(); // when the form moves after the page has loaded\n\n\t\t\tfor ( i = 0; i < items.length; i++ ) {\n\t\t\t\thtml += '<li>' + items[i] + '</li>';\n\t\t\t}\n\n\t\t\t$results.html( html ).show();\n\n\t\t\t$results\n\t\t\t\t.children( 'li' )\n\t\t\t\t.mouseover( function () {\n\t\t\t\t\t$results.children( 'li' ).removeClass( options.selectClass );\n\t\t\t\t\t$( this ).addClass( options.selectClass );\n\t\t\t\t})\n\t\t\t\t.click( function ( e ) {\n\t\t\t\t\te.preventDefault();\n\t\t\t\t\te.stopPropagation();\n\t\t\t\t\tselectCurrentResult();\n\t\t\t\t});\n\n\t\t}\n\n\t\tfunction parseTxt( txt, q ) {\n\n\t\t\tvar items = [], tokens = txt.split( options.delimiter ), i, token;\n\n\t\t\t// parse returned data for non-empty items\n\t\t\tfor ( i = 0; i < tokens.length; i++ ) {\n\t\t\t\ttoken = $.trim( tokens[i] );\n\t\t\t\tif ( token ) {\n\t\t\t\t\ttoken = token.replace(\n\t\t\t\t\t\tnew RegExp( q, 'ig' ),\n\t\t\t\t\t\tfunction ( q ) { return '<span class=\"' + options.matchClass + '\">' + q + '</span>'; },\n\t\t\t\t\t);\n\t\t\t\t\titems[items.length] = token;\n\t\t\t\t}\n\t\t\t}\n\n\t\t\treturn items;\n\t\t}\n\n\t\tfunction getCurrentResult() {\n\t\t\tvar $currentResult;\n\t\t\tif ( !$results.is( ':visible' ) ) {\n\t\t\t\treturn false;\n\t\t\t}\n\n\t\t\t$currentResult = $results.children( 'li.' + options.selectClass );\n\n\t\t\tif ( !$currentResult.length ) {\n\t\t\t\t$currentResult = false;\n\t\t\t}\n\n\t\t\treturn $currentResult;\n\n\t\t}\n\n\t\tfunction selectCurrentResult() {\n\n\t\t\t$currentResult = getCurrentResult();\n\n\t\t\tif ( $currentResult ) {\n\t\t\t\tif ( options.multiple ) {\n\t\t\t\t\tif ( $input.val().indexOf( options.multipleSep ) != -1 ) {\n\t\t\t\t\t\t$currentVal = $input.val().substr( 0, ($input.val().lastIndexOf( options.multipleSep ) +\n\t\t\t\t\t\t              options.multipleSep.length) ) + ' ';\n\t\t\t\t\t}\n\t\t\t\t\telse {\n\t\t\t\t\t\t$currentVal = '';\n\t\t\t\t\t}\n\t\t\t\t\t$input.val( $currentVal + $currentResult.text() + options.multipleSep + ' ' );\n\t\t\t\t\t$input.focus();\n\t\t\t\t}\n\t\t\t\telse {\n\t\t\t\t\t$input.val( $currentResult.text() );\n\t\t\t\t}\n\t\t\t\t$results.hide();\n\t\t\t\t$input.trigger( 'change' );\n\n\t\t\t\tif ( options.onSelect ) {\n\t\t\t\t\toptions.onSelect.apply( $input[0] );\n\t\t\t\t}\n\n\t\t\t}\n\n\t\t}\n\n\t\tfunction nextResult() {\n\n\t\t\t$currentResult = getCurrentResult();\n\n\t\t\tif ( $currentResult ) {\n\t\t\t\t$currentResult\n\t\t\t\t\t.removeClass( options.selectClass )\n\t\t\t\t\t.next()\n\t\t\t\t\t.addClass( options.selectClass );\n\t\t\t}\n\t\t\telse {\n\t\t\t\t$results.children( 'li:first-child' ).addClass( options.selectClass );\n\t\t\t}\n\n\t\t}\n\n\t\tfunction prevResult() {\n\t\t\tvar $currentResult = getCurrentResult();\n\n\t\t\tif ( $currentResult ) {\n\t\t\t\t$currentResult\n\t\t\t\t\t.removeClass( options.selectClass )\n\t\t\t\t\t.prev()\n\t\t\t\t\t.addClass( options.selectClass );\n\t\t\t}\n\t\t\telse {\n\t\t\t\t$results.children( 'li:last-child' ).addClass( options.selectClass );\n\t\t\t}\n\n\t\t}\n\t};\n\n\t$.fn.ccmSuggest = function ( source, options ) {\n\n\t\tif ( !source ) {\n\t\t\treturn;\n\t\t}\n\n\t\toptions = options || {};\n\t\toptions.multiple = options.multiple || false;\n\t\toptions.multipleSep = options.multipleSep || ',';\n\t\toptions.source = source;\n\t\toptions.delay = options.delay || 100;\n\t\toptions.resultsClass = options.resultsClass || 'ac_results';\n\t\toptions.selectClass = options.selectClass || 'ac_over';\n\t\toptions.matchClass = options.matchClass || 'ac_match';\n\t\toptions.minchars = options.minchars || 2;\n\t\toptions.delimiter = options.delimiter || '\\n';\n\t\toptions.onSelect = options.onSelect || false;\n\t\toptions.maxCacheSize = options.maxCacheSize || 65536;\n\n\t\tthis.each( function () {\n\t\t\tnew $.ccmSuggest( this, options );\n\t\t});\n\n\t\treturn this;\n\n\t};\n\n});\n\n\n//# sourceURL=webpack:///./src/javascript/ccm-suggest.js?");

/***/ })

/******/ });
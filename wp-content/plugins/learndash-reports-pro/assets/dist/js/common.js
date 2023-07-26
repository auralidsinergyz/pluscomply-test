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
/******/ 	return __webpack_require__(__webpack_require__.s = 24);
/******/ })
/************************************************************************/
/******/ ({

/***/ 24:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(33);

var _prepareZip = __webpack_require__(25);

var _prepareZip2 = _interopRequireDefault(_prepareZip);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.prepareZipFile = _prepareZip2.default;

/***/ }),

/***/ 25:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * This class is used to create zip files for export.
 */
var prepareZipFile = function () {
	function prepareZipFile() {
		_classCallCheck(this, prepareZipFile);
	}
	/**
  * This method is used to handle zip file creation either bulk export or for single exports with more than 30 entries.
  * @param  {string}  format File Format.
  * @param  {integer} ref_id Statistics Ref ID.
  * @param  {integer} page   Page Number.
  * @param  {strgng}  url    AJAX URL.
  * @param  {string}  nonce  zip creation process nonce.
  */


	_createClass(prepareZipFile, [{
		key: 'prepareZip',
		value: function prepareZip(format, ref_id, page, url, nonce) {
			var quiz_id = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : 0;

			var instance = this;
			jQuery.ajax({
				type: 'POST',
				url: url,
				timeout: 10000,
				retry_count: 0,
				retry_limit: 1,
				data: {
					action: 'qre_export_statistics',
					file_format: format,
					ref_id: ref_id,
					page: page,
					quiz_export_nonce: nonce,
					quiz_id: quiz_id
				},
				success: function success(response) {
					if (isNaN(parseInt(response))) {
						console.log('Invalid response');
						return;
					}
					if (response.trim() !== '0') {
						page++;
						instance.prepareZip(format, ref_id, page, url, nonce, quiz_id);
					} else {
						jQuery('.qre-download-' + format).find('form').remove();
						var html_str = '<form id="qre_exp_form_' + nonce + '" method="post" action="" target="_blank" style="display:none;">' + '<input name="file_format" type="hidden" value="' + format + '">' + '<input name="ref_id" type="hidden" value="' + ref_id + '">' + '<input name="quiz_id" type="hidden" value="' + quiz_id + '">' + '<input name="quiz_export_nonce" type="hidden" value="' + nonce + '">' + '<input name="export_zip" type="hidden" value="true">' + '</form>';
						jQuery('.qre-download-' + format).append(html_str);

						if (typeof jQuery('#wpProQuiz_tabHistory').unblock !== 'undefined') {
							jQuery('#wpProQuiz_tabHistory').unblock();
						}
						// console.log(html_str);
						jQuery('#qre_exp_form_' + nonce).submit();
					}
				},
				error: function error(xhr_instance, status, _error) {
					if (status === 'timeout') {
						this.retry_count++;
						if (this.retry_count <= this.retry_limit) {
							console.log('Retrying');
							jQuery.ajax(this);
							return;
						} else {
							console.error('request timed out');
						}
					} else {
						console.error(_error);
					}
				}
			});
		}
	}]);

	return prepareZipFile;
}();

exports.default = prepareZipFile;

/***/ }),

/***/ 33:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })

/******/ });
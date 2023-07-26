/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

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
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*********************************************************!*\
  !*** ./includes/blocks/src/dummy-quiz-reports/index.js ***!
  \*********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



class DummyFilters extends react__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor(props) {
    super(props);
  }
  render() {
    let body = '';
    body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "quiz-report-filters-wrapper wrld-dummy-filters"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wrld-pro-note"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wrld-pro-note-content"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Note: ', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Below is the dummy representation of the Quiz Reports available in WISDM Reports PRO.', 'learndash-reports-by-wisdmlabs')))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "select-view"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Select View', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "quiz-report-types"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      id: "dfr",
      type: "radio",
      name: "quiz-report-types",
      defaultValue: "default-quiz-reports",
      defaultChecked: ""
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      htmlFor: "dfr",
      className: ""
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Default Quiz Report View', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      id: "cqr",
      type: "radio",
      name: "quiz-report-types",
      defaultValue: "custom-quiz-reports",
      checked: true
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      htmlFor: "cqr",
      className: "checked"
    }, " ", (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Customized Quiz Report View', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "quiz-eporting-filter-section custom-filters"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "quiz-reporting-custom-filters"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector-label"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Courses', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "select-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-b62m3t-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      id: "react-select-8-live-region",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      "aria-live": "polite",
      "aria-atomic": "false",
      "aria-relevant": "additions text",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1s2u09g-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-319lph-ValueContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-qc6sy-singleValue"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('All', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-14dclt2-Input",
      "data-value": ""
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      className: "",
      autoCapitalize: "none",
      autoComplete: "off",
      autoCorrect: "off",
      id: "react-select-8-input",
      spellCheck: "false",
      tabIndex: 0,
      type: "text",
      "aria-autocomplete": "list",
      "aria-expanded": "false",
      "aria-haspopup": "true",
      "aria-controls": "react-select-8-listbox",
      "aria-owns": "react-select-8-listbox",
      role: "combobox",
      defaultValue: "",
      style: {
        color: "inherit",
        background: "0px center",
        opacity: 1,
        width: "100%",
        gridArea: "1 / 2 / auto / auto",
        font: "inherit",
        minWidth: 2,
        border: 0,
        margin: 0,
        outline: 0,
        padding: 0
      }
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1hb7zxy-IndicatorsContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z"
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: " css-1okebmr-indicatorSeparator"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z"
    })))))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector-label"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Groups', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "select-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-b62m3t-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      id: "react-select-9-live-region",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      "aria-live": "polite",
      "aria-atomic": "false",
      "aria-relevant": "additions text",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1s2u09g-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-319lph-ValueContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-qc6sy-singleValue"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('All', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-14dclt2-Input",
      "data-value": ""
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      className: "",
      autoCapitalize: "none",
      autoComplete: "off",
      autoCorrect: "off",
      id: "react-select-9-input",
      spellCheck: "false",
      tabIndex: 0,
      type: "text",
      "aria-autocomplete": "list",
      "aria-expanded": "false",
      "aria-haspopup": "true",
      "aria-controls": "react-select-9-listbox",
      "aria-owns": "react-select-9-listbox",
      role: "combobox",
      defaultValue: "",
      style: {
        color: "inherit",
        background: "0px center",
        opacity: 1,
        width: "100%",
        gridArea: "1 / 2 / auto / auto",
        font: "inherit",
        minWidth: 2,
        border: 0,
        margin: 0,
        outline: 0,
        padding: 0
      }
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1hb7zxy-IndicatorsContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z"
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: " css-1okebmr-indicatorSeparator"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z"
    })))))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "selector-label"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Quizzes', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "select-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-b62m3t-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      id: "react-select-10-live-region",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      "aria-live": "polite",
      "aria-atomic": "false",
      "aria-relevant": "additions text",
      className: "css-1f43avz-a11yText-A11yText"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1s2u09g-control"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-319lph-ValueContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-qc6sy-singleValue"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('All', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-14dclt2-Input",
      "data-value": ""
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
      className: "",
      autoCapitalize: "none",
      autoComplete: "off",
      autoCorrect: "off",
      id: "react-select-10-input",
      spellCheck: "false",
      tabIndex: 0,
      type: "text",
      "aria-autocomplete": "list",
      "aria-expanded": "false",
      "aria-haspopup": "true",
      "aria-controls": "react-select-10-listbox",
      "aria-owns": "react-select-10-listbox",
      role: "combobox",
      defaultValue: "",
      style: {
        color: "inherit",
        background: "0px center",
        opacity: 1,
        width: "100%",
        gridArea: "1 / 2 / auto / auto",
        font: "inherit",
        minWidth: 2,
        border: 0,
        margin: 0,
        outline: 0,
        padding: 0
      }
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-1hb7zxy-IndicatorsContainer"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M14.348 14.849c-0.469 0.469-1.229 0.469-1.697 0l-2.651-3.030-2.651 3.029c-0.469 0.469-1.229 0.469-1.697 0-0.469-0.469-0.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-0.469-0.469-0.469-1.228 0-1.697s1.228-0.469 1.697 0l2.652 3.031 2.651-3.031c0.469-0.469 1.228-0.469 1.697 0s0.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c0.469 0.469 0.469 1.229 0 1.698z"
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: " css-1okebmr-indicatorSeparator"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: " css-tlfecz-indicatorContainer",
      "aria-hidden": "true"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      height: 20,
      width: 20,
      viewBox: "0 0 20 20",
      "aria-hidden": "true",
      focusable: "false",
      className: "css-tj5bde-Svg"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747 3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0 1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502 0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0 0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z"
    }))))))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "filter-buttons"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "filter-button-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button-customize-preview"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('CUSTOMIZE REPORT', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: "button-quiz-preview"
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')))))));
    return body;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (DummyFilters);
}();
/******/ })()
;
//# sourceMappingURL=index.js.map
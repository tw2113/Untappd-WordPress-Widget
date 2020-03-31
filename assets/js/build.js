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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/blocks/components/latest-checkins.js":
/*!*****************************************************!*\
  !*** ./assets/blocks/components/latest-checkins.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return LatestCheckins; });\nfunction _typeof(obj) { \"@babel/helpers - typeof\"; if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _createSuper(Derived) { return function () { var Super = _getPrototypeOf(Derived), result; if (_isNativeReflectConstruct()) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _isNativeReflectConstruct() { if (typeof Reflect === \"undefined\" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === \"function\") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\nvar Component = wp.element.Component;\nvar TextControl = wp.components.TextControl;\nvar __ = wp.i18n.__;\n\nvar LatestCheckins = /*#__PURE__*/function (_Component) {\n  _inherits(LatestCheckins, _Component);\n\n  var _super = _createSuper(LatestCheckins);\n\n  /**\n   * Constructor\n   * @param props\n   */\n  function LatestCheckins(props) {\n    _classCallCheck(this, LatestCheckins);\n\n    return _super.call(this, props);\n  }\n\n  _createClass(LatestCheckins, [{\n    key: \"render\",\n    value: function render() {\n      var _this$props = this.props,\n          username = _this$props.attributes.username,\n          className = _this$props.className,\n          setAttributes = _this$props.setAttributes;\n      return /*#__PURE__*/React.createElement(\"div\", {\n        className: className\n      }, /*#__PURE__*/React.createElement(\"p\", null, __('User\\'s Latest Checkins', 'mb_untappd')), /*#__PURE__*/React.createElement(TextControl, {\n        label: __('Username:', 'mb_untappd'),\n        placeholder: __('Enter Username here…'),\n        value: username,\n        onChange: function onChange(username) {\n          return setAttributes({\n            attributes: username\n          });\n        }\n      }));\n    }\n  }]);\n\n  return LatestCheckins;\n}(Component);\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvYmxvY2tzL2NvbXBvbmVudHMvbGF0ZXN0LWNoZWNraW5zLmpzLmpzIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Jsb2Nrcy9jb21wb25lbnRzL2xhdGVzdC1jaGVja2lucy5qcz9kNjE0Il0sInNvdXJjZXNDb250ZW50IjpbImNvbnN0IHsgQ29tcG9uZW50IH0gPSB3cC5lbGVtZW50O1xuY29uc3QgeyBUZXh0Q29udHJvbCB9ID0gd3AuY29tcG9uZW50cztcblxuY29uc3QgeyBfXyB9ID0gd3AuaTE4bjtcblxuZXhwb3J0IGRlZmF1bHQgY2xhc3MgTGF0ZXN0Q2hlY2tpbnMgZXh0ZW5kcyBDb21wb25lbnQge1xuXHQvKipcblx0ICogQ29uc3RydWN0b3Jcblx0ICogQHBhcmFtIHByb3BzXG5cdCAqL1xuXHRjb25zdHJ1Y3RvciggcHJvcHMgKSB7XG5cdFx0c3VwZXIoIHByb3BzICk7XG5cdH1cblxuICAgIHJlbmRlcigpIHtcbiAgICBcdGNvbnN0IHtcbiAgICAgICAgICAgIGF0dHJpYnV0ZXM6IHsgdXNlcm5hbWUgfSxcbiAgICAgICAgICAgIGNsYXNzTmFtZSwgc2V0QXR0cmlidXRlc1xuICAgICAgICB9ID0gdGhpcy5wcm9wcztcblxuXHRcdHJldHVybiAoXG5cdFx0XHQ8ZGl2IGNsYXNzTmFtZT17IGNsYXNzTmFtZSB9PlxuXHRcdFx0XHQ8cD57IF9fKCdVc2VyXFwncyBMYXRlc3QgQ2hlY2tpbnMnLCAnbWJfdW50YXBwZCcgKSB9PC9wPlxuXHRcdFx0XHQ8VGV4dENvbnRyb2xcblx0XHRcdFx0XHRsYWJlbD17IF9fKCdVc2VybmFtZTonLCAnbWJfdW50YXBwZCcgKSB9XG5cdFx0XHRcdFx0cGxhY2Vob2xkZXI9eyBfXyggJ0VudGVyIFVzZXJuYW1lIGhlcmXigKYnICkgfVxuXHRcdFx0XHRcdHZhbHVlPXsgdXNlcm5hbWUgfVxuXHRcdFx0XHRcdG9uQ2hhbmdlPXsgdXNlcm5hbWUgPT4gc2V0QXR0cmlidXRlcyggeyBhdHRyaWJ1dGVzOiB1c2VybmFtZSB9ICkgfVxuXHRcdFx0XHQvPlxuXHRcdFx0PC9kaXY+XG5cdFx0KVxuXHR9XG59XG4iXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7Ozs7O0FBQ0E7Ozs7QUFJQTtBQUFBO0FBQ0E7QUFEQTtBQUVBO0FBQ0E7OztBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFNQTtBQUNBO0FBQUE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBSkE7QUFRQTs7OztBQTFCQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/blocks/components/latest-checkins.js\n");

/***/ }),

/***/ "./assets/blocks/latest-checkins/index.js":
/*!************************************************!*\
  !*** ./assets/blocks/latest-checkins/index.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _components_latest_checkins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/latest-checkins */ \"./assets/blocks/components/latest-checkins.js\");\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (registerBlockType('untappd-mb-gutenberg/latest-checkins', {\n  title: __('Untappd Latest User Checkins', 'mb_untappd'),\n  category: 'widgets',\n  keywords: [__('Beer', 'mb_untappd'), __('Checkin', 'mb_untappd')],\n  attributes: {\n    username: {\n      type: 'string'\n    },\n    displayTotal: {\n      type: 'string'\n    }\n  },\n  edit: _components_latest_checkins__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n  save: function save(props) {\n    var attributes = props.attributes;\n    return /*#__PURE__*/React.createElement(\"p\", null, attributes.username);\n  }\n}));//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvYmxvY2tzL2xhdGVzdC1jaGVja2lucy9pbmRleC5qcy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9ibG9ja3MvbGF0ZXN0LWNoZWNraW5zL2luZGV4LmpzP2RmN2MiXSwic291cmNlc0NvbnRlbnQiOlsiY29uc3QgeyBfXyB9ID0gd3AuaTE4bjtcbmNvbnN0IHtcblx0cmVnaXN0ZXJCbG9ja1R5cGUsXG59ID0gd3AuYmxvY2tzO1xuXG5pbXBvcnQgTGF0ZXN0Q2hlY2tpbnMgZnJvbSAnLi4vY29tcG9uZW50cy9sYXRlc3QtY2hlY2tpbnMnO1xuXG5leHBvcnQgZGVmYXVsdCByZWdpc3RlckJsb2NrVHlwZSgndW50YXBwZC1tYi1ndXRlbmJlcmcvbGF0ZXN0LWNoZWNraW5zJywge1xuXHR0aXRsZTogX18oJ1VudGFwcGQgTGF0ZXN0IFVzZXIgQ2hlY2tpbnMnLCAnbWJfdW50YXBwZCcpLFxuXHRjYXRlZ29yeTogJ3dpZGdldHMnLFxuXHRrZXl3b3JkczogW1xuXHRcdF9fKCAnQmVlcicsICdtYl91bnRhcHBkJyApLFxuXHRcdF9fKCAnQ2hlY2tpbicsICdtYl91bnRhcHBkJyApXG5cdF0sXG5cdGF0dHJpYnV0ZXM6IHtcblx0XHR1c2VybmFtZToge1xuXHRcdFx0dHlwZTogJ3N0cmluZycsXG5cdFx0fSxcblx0XHRkaXNwbGF5VG90YWw6IHtcblx0XHRcdHR5cGU6ICdzdHJpbmcnXG5cdFx0fVxuXHR9LFxuXHRlZGl0OiBMYXRlc3RDaGVja2lucyxcblx0c2F2ZTogcHJvcHMgPT4ge1xuXHRcdGNvbnN0IHsgYXR0cmlidXRlcyB9ID0gcHJvcHM7XG5cdFx0cmV0dXJuIChcblx0XHRcdDxwPnthdHRyaWJ1dGVzLnVzZXJuYW1lfTwvcD5cblx0XHQpXG5cdH1cbn0pO1xuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFFQTtBQUdBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFJQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFEQTtBQUpBO0FBUUE7QUFDQTtBQUFBO0FBRUE7QUFHQTtBQXJCQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/blocks/latest-checkins/index.js\n");

/***/ }),

/***/ 0:
/*!******************************************************!*\
  !*** multi ./assets/blocks/latest-checkins/index.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/blocks/latest-checkins/index.js */"./assets/blocks/latest-checkins/index.js");


/***/ })

/******/ });
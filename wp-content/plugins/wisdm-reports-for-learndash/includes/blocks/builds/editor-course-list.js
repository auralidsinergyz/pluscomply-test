/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/apexcharts/dist/apexcharts.common.js":
/*!***********************************************************!*\
  !*** ./node_modules/apexcharts/dist/apexcharts.common.js ***!
  \***********************************************************/
/***/ (function(module, exports, __webpack_require__) {

"use strict";
var __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * ApexCharts v3.37.1
 * (c) 2018-2023 ApexCharts
 * Released under the MIT License.
 */
function t(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,a)}return i}function e(e){for(var i=1;i<arguments.length;i++){var a=null!=arguments[i]?arguments[i]:{};i%2?t(Object(a),!0).forEach((function(t){o(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):t(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function i(t){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i(t)}function a(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function s(t,e){for(var i=0;i<e.length;i++){var a=e[i];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}function r(t,e,i){return e&&s(t.prototype,e),i&&s(t,i),t}function o(t,e,i){return e in t?Object.defineProperty(t,e,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[e]=i,t}function n(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&h(t,e)}function l(t){return l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},l(t)}function h(t,e){return h=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},h(t,e)}function c(t,e){if(e&&("object"==typeof e||"function"==typeof e))return e;if(void 0!==e)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function d(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var i,a=l(t);if(e){var s=l(this).constructor;i=Reflect.construct(a,arguments,s)}else i=a.apply(this,arguments);return c(this,i)}}function g(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var i=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==i)return;var a,s,r=[],o=!0,n=!1;try{for(i=i.call(t);!(o=(a=i.next()).done)&&(r.push(a.value),!e||r.length!==e);o=!0);}catch(t){n=!0,s=t}finally{try{o||null==i.return||i.return()}finally{if(n)throw s}}return r}(t,e)||f(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function u(t){return function(t){if(Array.isArray(t))return p(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||f(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function f(t,e){if(t){if("string"==typeof t)return p(t,e);var i=Object.prototype.toString.call(t).slice(8,-1);return"Object"===i&&t.constructor&&(i=t.constructor.name),"Map"===i||"Set"===i?Array.from(t):"Arguments"===i||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i)?p(t,e):void 0}}function p(t,e){(null==e||e>t.length)&&(e=t.length);for(var i=0,a=new Array(e);i<e;i++)a[i]=t[i];return a}var x=function(){function t(){a(this,t)}return r(t,[{key:"shadeRGBColor",value:function(t,e){var i=e.split(","),a=t<0?0:255,s=t<0?-1*t:t,r=parseInt(i[0].slice(4),10),o=parseInt(i[1],10),n=parseInt(i[2],10);return"rgb("+(Math.round((a-r)*s)+r)+","+(Math.round((a-o)*s)+o)+","+(Math.round((a-n)*s)+n)+")"}},{key:"shadeHexColor",value:function(t,e){var i=parseInt(e.slice(1),16),a=t<0?0:255,s=t<0?-1*t:t,r=i>>16,o=i>>8&255,n=255&i;return"#"+(16777216+65536*(Math.round((a-r)*s)+r)+256*(Math.round((a-o)*s)+o)+(Math.round((a-n)*s)+n)).toString(16).slice(1)}},{key:"shadeColor",value:function(e,i){return t.isColorHex(i)?this.shadeHexColor(e,i):this.shadeRGBColor(e,i)}}],[{key:"bind",value:function(t,e){return function(){return t.apply(e,arguments)}}},{key:"isObject",value:function(t){return t&&"object"===i(t)&&!Array.isArray(t)&&null!=t}},{key:"is",value:function(t,e){return Object.prototype.toString.call(e)==="[object "+t+"]"}},{key:"listToArray",value:function(t){var e,i=[];for(e=0;e<t.length;e++)i[e]=t[e];return i}},{key:"extend",value:function(t,e){var i=this;"function"!=typeof Object.assign&&(Object.assign=function(t){if(null==t)throw new TypeError("Cannot convert undefined or null to object");for(var e=Object(t),i=1;i<arguments.length;i++){var a=arguments[i];if(null!=a)for(var s in a)a.hasOwnProperty(s)&&(e[s]=a[s])}return e});var a=Object.assign({},t);return this.isObject(t)&&this.isObject(e)&&Object.keys(e).forEach((function(s){i.isObject(e[s])&&s in t?a[s]=i.extend(t[s],e[s]):Object.assign(a,o({},s,e[s]))})),a}},{key:"extendArray",value:function(e,i){var a=[];return e.map((function(e){a.push(t.extend(i,e))})),e=a}},{key:"monthMod",value:function(t){return t%12}},{key:"clone",value:function(e){if(t.is("Array",e)){for(var a=[],s=0;s<e.length;s++)a[s]=this.clone(e[s]);return a}if(t.is("Null",e))return null;if(t.is("Date",e))return e;if("object"===i(e)){var r={};for(var o in e)e.hasOwnProperty(o)&&(r[o]=this.clone(e[o]));return r}return e}},{key:"log10",value:function(t){return Math.log(t)/Math.LN10}},{key:"roundToBase10",value:function(t){return Math.pow(10,Math.floor(Math.log10(t)))}},{key:"roundToBase",value:function(t,e){return Math.pow(e,Math.floor(Math.log(t)/Math.log(e)))}},{key:"parseNumber",value:function(t){return null===t?t:parseFloat(t)}},{key:"randomId",value:function(){return(Math.random()+1).toString(36).substring(4)}},{key:"noExponents",value:function(t){var e=String(t).split(/[eE]/);if(1===e.length)return e[0];var i="",a=t<0?"-":"",s=e[0].replace(".",""),r=Number(e[1])+1;if(r<0){for(i=a+"0.";r++;)i+="0";return i+s.replace(/^-/,"")}for(r-=s.length;r--;)i+="0";return s+i}},{key:"getDimensions",value:function(t){var e=getComputedStyle(t,null),i=t.clientHeight,a=t.clientWidth;return i-=parseFloat(e.paddingTop)+parseFloat(e.paddingBottom),[a-=parseFloat(e.paddingLeft)+parseFloat(e.paddingRight),i]}},{key:"getBoundingClientRect",value:function(t){var e=t.getBoundingClientRect();return{top:e.top,right:e.right,bottom:e.bottom,left:e.left,width:t.clientWidth,height:t.clientHeight,x:e.left,y:e.top}}},{key:"getLargestStringFromArr",value:function(t){return t.reduce((function(t,e){return Array.isArray(e)&&(e=e.reduce((function(t,e){return t.length>e.length?t:e}))),t.length>e.length?t:e}),0)}},{key:"hexToRgba",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"#999999",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:.6;"#"!==t.substring(0,1)&&(t="#999999");var i=t.replace("#","");i=i.match(new RegExp("(.{"+i.length/3+"})","g"));for(var a=0;a<i.length;a++)i[a]=parseInt(1===i[a].length?i[a]+i[a]:i[a],16);return void 0!==e&&i.push(e),"rgba("+i.join(",")+")"}},{key:"getOpacityFromRGBA",value:function(t){return parseFloat(t.replace(/^.*,(.+)\)/,"$1"))}},{key:"rgb2hex",value:function(t){return(t=t.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i))&&4===t.length?"#"+("0"+parseInt(t[1],10).toString(16)).slice(-2)+("0"+parseInt(t[2],10).toString(16)).slice(-2)+("0"+parseInt(t[3],10).toString(16)).slice(-2):""}},{key:"isColorHex",value:function(t){return/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)|(^#[0-9A-F]{8}$)/i.test(t)}},{key:"getPolygonPos",value:function(t,e){for(var i=[],a=2*Math.PI/e,s=0;s<e;s++){var r={};r.x=t*Math.sin(s*a),r.y=-t*Math.cos(s*a),i.push(r)}return i}},{key:"polarToCartesian",value:function(t,e,i,a){var s=(a-90)*Math.PI/180;return{x:t+i*Math.cos(s),y:e+i*Math.sin(s)}}},{key:"escapeString",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"x",i=t.toString().slice();return i=i.replace(/[` ~!@#$%^&*()|+\=?;:'",.<>{}[\]\\/]/gi,e)}},{key:"negToZero",value:function(t){return t<0?0:t}},{key:"moveIndexInArray",value:function(t,e,i){if(i>=t.length)for(var a=i-t.length+1;a--;)t.push(void 0);return t.splice(i,0,t.splice(e,1)[0]),t}},{key:"extractNumber",value:function(t){return parseFloat(t.replace(/[^\d.]*/g,""))}},{key:"findAncestor",value:function(t,e){for(;(t=t.parentElement)&&!t.classList.contains(e););return t}},{key:"setELstyles",value:function(t,e){for(var i in e)e.hasOwnProperty(i)&&(t.style.key=e[i])}},{key:"isNumber",value:function(t){return!isNaN(t)&&parseFloat(Number(t))===t&&!isNaN(parseInt(t,10))}},{key:"isFloat",value:function(t){return Number(t)===t&&t%1!=0}},{key:"isSafari",value:function(){return/^((?!chrome|android).)*safari/i.test(navigator.userAgent)}},{key:"isFirefox",value:function(){return navigator.userAgent.toLowerCase().indexOf("firefox")>-1}},{key:"isIE11",value:function(){if(-1!==window.navigator.userAgent.indexOf("MSIE")||window.navigator.appVersion.indexOf("Trident/")>-1)return!0}},{key:"isIE",value:function(){var t=window.navigator.userAgent,e=t.indexOf("MSIE ");if(e>0)return parseInt(t.substring(e+5,t.indexOf(".",e)),10);if(t.indexOf("Trident/")>0){var i=t.indexOf("rv:");return parseInt(t.substring(i+3,t.indexOf(".",i)),10)}var a=t.indexOf("Edge/");return a>0&&parseInt(t.substring(a+5,t.indexOf(".",a)),10)}}]),t}(),b=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.setEasingFunctions()}return r(t,[{key:"setEasingFunctions",value:function(){var t;if(!this.w.globals.easing){switch(this.w.config.chart.animations.easing){case"linear":t="-";break;case"easein":t="<";break;case"easeout":t=">";break;case"easeinout":default:t="<>";break;case"swing":t=function(t){var e=1.70158;return(t-=1)*t*((e+1)*t+e)+1};break;case"bounce":t=function(t){return t<1/2.75?7.5625*t*t:t<2/2.75?7.5625*(t-=1.5/2.75)*t+.75:t<2.5/2.75?7.5625*(t-=2.25/2.75)*t+.9375:7.5625*(t-=2.625/2.75)*t+.984375};break;case"elastic":t=function(t){return t===!!t?t:Math.pow(2,-10*t)*Math.sin((t-.075)*(2*Math.PI)/.3)+1}}this.w.globals.easing=t}}},{key:"animateLine",value:function(t,e,i,a){t.attr(e).animate(a).attr(i)}},{key:"animateMarker",value:function(t,e,i,a,s,r){e||(e=0),t.attr({r:e,width:e,height:e}).animate(a,s).attr({r:i,width:i.width,height:i.height}).afterAll((function(){r()}))}},{key:"animateCircle",value:function(t,e,i,a,s){t.attr({r:e.r,cx:e.cx,cy:e.cy}).animate(a,s).attr({r:i.r,cx:i.cx,cy:i.cy})}},{key:"animateRect",value:function(t,e,i,a,s){t.attr(e).animate(a).attr(i).afterAll((function(){return s()}))}},{key:"animatePathsGradually",value:function(t){var e=t.el,i=t.realIndex,a=t.j,s=t.fill,r=t.pathFrom,o=t.pathTo,n=t.speed,l=t.delay,h=this.w,c=0;h.config.chart.animations.animateGradually.enabled&&(c=h.config.chart.animations.animateGradually.delay),h.config.chart.animations.dynamicAnimation.enabled&&h.globals.dataChanged&&"bar"!==h.config.chart.type&&(c=0),this.morphSVG(e,i,a,"line"!==h.config.chart.type||h.globals.comboCharts?s:"stroke",r,o,n,l*c)}},{key:"showDelayedElements",value:function(){this.w.globals.delayedElements.forEach((function(t){t.el.classList.remove("apexcharts-element-hidden")}))}},{key:"animationCompleted",value:function(t){var e=this.w;e.globals.animationEnded||(e.globals.animationEnded=!0,this.showDelayedElements(),"function"==typeof e.config.chart.events.animationEnd&&e.config.chart.events.animationEnd(this.ctx,{el:t,w:e}))}},{key:"morphSVG",value:function(t,e,i,a,s,r,o,n){var l=this,h=this.w;s||(s=t.attr("pathFrom")),r||(r=t.attr("pathTo"));var c=function(t){return"radar"===h.config.chart.type&&(o=1),"M 0 ".concat(h.globals.gridHeight)};(!s||s.indexOf("undefined")>-1||s.indexOf("NaN")>-1)&&(s=c()),(!r||r.indexOf("undefined")>-1||r.indexOf("NaN")>-1)&&(r=c()),h.globals.shouldAnimate||(o=1),t.plot(s).animate(1,h.globals.easing,n).plot(s).animate(o,h.globals.easing,n).plot(r).afterAll((function(){x.isNumber(i)?i===h.globals.series[h.globals.maxValsInArrayIndex].length-2&&h.globals.shouldAnimate&&l.animationCompleted(t):"none"!==a&&h.globals.shouldAnimate&&(!h.globals.comboCharts&&e===h.globals.series.length-1||h.globals.comboCharts)&&l.animationCompleted(t),l.showDelayedElements()}))}}]),t}(),v=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"getDefaultFilter",value:function(t,e){var i=this.w;t.unfilter(!0),(new window.SVG.Filter).size("120%","180%","-5%","-40%"),"none"!==i.config.states.normal.filter?this.applyFilter(t,e,i.config.states.normal.filter.type,i.config.states.normal.filter.value):i.config.chart.dropShadow.enabled&&this.dropShadow(t,i.config.chart.dropShadow,e)}},{key:"addNormalFilter",value:function(t,e){var i=this.w;i.config.chart.dropShadow.enabled&&!t.node.classList.contains("apexcharts-marker")&&this.dropShadow(t,i.config.chart.dropShadow,e)}},{key:"addLightenFilter",value:function(t,e,i){var a=this,s=this.w,r=i.intensity;t.unfilter(!0);new window.SVG.Filter;t.filter((function(t){var i=s.config.chart.dropShadow;(i.enabled?a.addShadow(t,e,i):t).componentTransfer({rgb:{type:"linear",slope:1.5,intercept:r}})})),t.filterer.node.setAttribute("filterUnits","userSpaceOnUse"),this._scaleFilterSize(t.filterer.node)}},{key:"addDarkenFilter",value:function(t,e,i){var a=this,s=this.w,r=i.intensity;t.unfilter(!0);new window.SVG.Filter;t.filter((function(t){var i=s.config.chart.dropShadow;(i.enabled?a.addShadow(t,e,i):t).componentTransfer({rgb:{type:"linear",slope:r}})})),t.filterer.node.setAttribute("filterUnits","userSpaceOnUse"),this._scaleFilterSize(t.filterer.node)}},{key:"applyFilter",value:function(t,e,i){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:.5;switch(i){case"none":this.addNormalFilter(t,e);break;case"lighten":this.addLightenFilter(t,e,{intensity:a});break;case"darken":this.addDarkenFilter(t,e,{intensity:a})}}},{key:"addShadow",value:function(t,e,i){var a=i.blur,s=i.top,r=i.left,o=i.color,n=i.opacity,l=t.flood(Array.isArray(o)?o[e]:o,n).composite(t.sourceAlpha,"in").offset(r,s).gaussianBlur(a).merge(t.source);return t.blend(t.source,l)}},{key:"dropShadow",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:0,a=e.top,s=e.left,r=e.blur,o=e.color,n=e.opacity,l=e.noUserSpaceOnUse,h=this.w;return t.unfilter(!0),x.isIE()&&"radialBar"===h.config.chart.type||(o=Array.isArray(o)?o[i]:o,t.filter((function(t){var e=null;e=x.isSafari()||x.isFirefox()||x.isIE()?t.flood(o,n).composite(t.sourceAlpha,"in").offset(s,a).gaussianBlur(r):t.flood(o,n).composite(t.sourceAlpha,"in").offset(s,a).gaussianBlur(r).merge(t.source),t.blend(t.source,e)})),l||t.filterer.node.setAttribute("filterUnits","userSpaceOnUse"),this._scaleFilterSize(t.filterer.node)),t}},{key:"setSelectionFilter",value:function(t,e,i){var a=this.w;if(void 0!==a.globals.selectedDataPoints[e]&&a.globals.selectedDataPoints[e].indexOf(i)>-1){t.node.setAttribute("selected",!0);var s=a.config.states.active.filter;"none"!==s&&this.applyFilter(t,e,s.type,s.value)}}},{key:"_scaleFilterSize",value:function(t){!function(e){for(var i in e)e.hasOwnProperty(i)&&t.setAttribute(i,e[i])}({width:"200%",height:"200%",x:"-50%",y:"-50%"})}}]),t}(),m=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"roundPathCorners",value:function(t,e){function i(t,e,i){var s=e.x-t.x,r=e.y-t.y,o=Math.sqrt(s*s+r*r);return a(t,e,Math.min(1,i/o))}function a(t,e,i){return{x:t.x+(e.x-t.x)*i,y:t.y+(e.y-t.y)*i}}function s(t,e){t.length>2&&(t[t.length-2]=e.x,t[t.length-1]=e.y)}function r(t){return{x:parseFloat(t[t.length-2]),y:parseFloat(t[t.length-1])}}var o=t.split(/[,\s]/).reduce((function(t,e){var i=e.match("([a-zA-Z])(.+)");return i?(t.push(i[1]),t.push(i[2])):t.push(e),t}),[]).reduce((function(t,e){return parseFloat(e)==e&&t.length?t[t.length-1].push(e):t.push([e]),t}),[]),n=[];if(o.length>1){var l=r(o[0]),h=null;"Z"==o[o.length-1][0]&&o[0].length>2&&(h=["L",l.x,l.y],o[o.length-1]=h),n.push(o[0]);for(var c=1;c<o.length;c++){var d=n[n.length-1],g=o[c],u=g==h?o[1]:o[c+1];if(u&&d&&d.length>2&&"L"==g[0]&&u.length>2&&"L"==u[0]){var f,p,x=r(d),b=r(g),v=r(u);f=i(b,x,e),p=i(b,v,e),s(g,f),g.origPoint=b,n.push(g);var m=a(f,b,.5),y=a(b,p,.5),w=["C",m.x,m.y,y.x,y.y,p.x,p.y];w.origPoint=b,n.push(w)}else n.push(g)}if(h){var k=r(n[n.length-1]);n.push(["Z"]),s(n[0],k)}}else n=o;return n.reduce((function(t,e){return t+e.join(" ")+" "}),"")}},{key:"drawLine",value:function(t,e,i,a){var s=arguments.length>4&&void 0!==arguments[4]?arguments[4]:"#a8a8a8",r=arguments.length>5&&void 0!==arguments[5]?arguments[5]:0,o=arguments.length>6&&void 0!==arguments[6]?arguments[6]:null,n=arguments.length>7&&void 0!==arguments[7]?arguments[7]:"butt",l=this.w,h=l.globals.dom.Paper.line().attr({x1:t,y1:e,x2:i,y2:a,stroke:s,"stroke-dasharray":r,"stroke-width":o,"stroke-linecap":n});return h}},{key:"drawRect",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:0,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:0,s=arguments.length>4&&void 0!==arguments[4]?arguments[4]:0,r=arguments.length>5&&void 0!==arguments[5]?arguments[5]:"#fefefe",o=arguments.length>6&&void 0!==arguments[6]?arguments[6]:1,n=arguments.length>7&&void 0!==arguments[7]?arguments[7]:null,l=arguments.length>8&&void 0!==arguments[8]?arguments[8]:null,h=arguments.length>9&&void 0!==arguments[9]?arguments[9]:0,c=this.w,d=c.globals.dom.Paper.rect();return d.attr({x:t,y:e,width:i>0?i:0,height:a>0?a:0,rx:s,ry:s,opacity:o,"stroke-width":null!==n?n:0,stroke:null!==l?l:"none","stroke-dasharray":h}),d.node.setAttribute("fill",r),d}},{key:"drawPolygon",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"#e1e1e1",i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:1,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"none",s=this.w,r=s.globals.dom.Paper.polygon(t).attr({fill:a,stroke:e,"stroke-width":i});return r}},{key:"drawCircle",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,i=this.w;t<0&&(t=0);var a=i.globals.dom.Paper.circle(2*t);return null!==e&&a.attr(e),a}},{key:"drawPath",value:function(t){var e=t.d,i=void 0===e?"":e,a=t.stroke,s=void 0===a?"#a8a8a8":a,r=t.strokeWidth,o=void 0===r?1:r,n=t.fill,l=t.fillOpacity,h=void 0===l?1:l,c=t.strokeOpacity,d=void 0===c?1:c,g=t.classes,u=t.strokeLinecap,f=void 0===u?null:u,p=t.strokeDashArray,x=void 0===p?0:p,b=this.w;return null===f&&(f=b.config.stroke.lineCap),(i.indexOf("undefined")>-1||i.indexOf("NaN")>-1)&&(i="M 0 ".concat(b.globals.gridHeight)),b.globals.dom.Paper.path(i).attr({fill:n,"fill-opacity":h,stroke:s,"stroke-opacity":d,"stroke-linecap":f,"stroke-width":o,"stroke-dasharray":x,class:g})}},{key:"group",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,e=this.w,i=e.globals.dom.Paper.group();return null!==t&&i.attr(t),i}},{key:"move",value:function(t,e){var i=["M",t,e].join(" ");return i}},{key:"line",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=null;return null===i?a=[" L",t,e].join(" "):"H"===i?a=[" H",t].join(" "):"V"===i&&(a=[" V",e].join(" ")),a}},{key:"curve",value:function(t,e,i,a,s,r){var o=["C",t,e,i,a,s,r].join(" ");return o}},{key:"quadraticCurve",value:function(t,e,i,a){return["Q",t,e,i,a].join(" ")}},{key:"arc",value:function(t,e,i,a,s,r,o){var n=arguments.length>7&&void 0!==arguments[7]&&arguments[7],l="A";n&&(l="a");var h=[l,t,e,i,a,s,r,o].join(" ");return h}},{key:"renderPaths",value:function(t){var i,a=t.j,s=t.realIndex,r=t.pathFrom,o=t.pathTo,n=t.stroke,l=t.strokeWidth,h=t.strokeLinecap,c=t.fill,d=t.animationDelay,g=t.initialSpeed,u=t.dataChangeSpeed,f=t.className,p=t.shouldClipToGrid,x=void 0===p||p,m=t.bindEventsOnPaths,y=void 0===m||m,w=t.drawShadow,k=void 0===w||w,A=this.w,S=new v(this.ctx),C=new b(this.ctx),L=this.w.config.chart.animations.enabled,P=L&&this.w.config.chart.animations.dynamicAnimation.enabled,T=!!(L&&!A.globals.resized||P&&A.globals.dataChanged&&A.globals.shouldAnimate);T?i=r:(i=o,A.globals.animationEnded=!0);var M=A.config.stroke.dashArray,I=0;I=Array.isArray(M)?M[s]:A.config.stroke.dashArray;var z=this.drawPath({d:i,stroke:n,strokeWidth:l,fill:c,fillOpacity:1,classes:f,strokeLinecap:h,strokeDashArray:I});if(z.attr("index",s),x&&z.attr({"clip-path":"url(#gridRectMask".concat(A.globals.cuid,")")}),"none"!==A.config.states.normal.filter.type)S.getDefaultFilter(z,s);else if(A.config.chart.dropShadow.enabled&&k&&(!A.config.chart.dropShadow.enabledOnSeries||A.config.chart.dropShadow.enabledOnSeries&&-1!==A.config.chart.dropShadow.enabledOnSeries.indexOf(s))){var X=A.config.chart.dropShadow;S.dropShadow(z,X,s)}y&&(z.node.addEventListener("mouseenter",this.pathMouseEnter.bind(this,z)),z.node.addEventListener("mouseleave",this.pathMouseLeave.bind(this,z)),z.node.addEventListener("mousedown",this.pathMouseDown.bind(this,z))),z.attr({pathTo:o,pathFrom:r});var E={el:z,j:a,realIndex:s,pathFrom:r,pathTo:o,fill:c,strokeWidth:l,delay:d};return!L||A.globals.resized||A.globals.dataChanged?!A.globals.resized&&A.globals.dataChanged||C.showDelayedElements():C.animatePathsGradually(e(e({},E),{},{speed:g})),A.globals.dataChanged&&P&&T&&C.animatePathsGradually(e(e({},E),{},{speed:u})),z}},{key:"drawPattern",value:function(t,e,i){var a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"#a8a8a8",s=arguments.length>4&&void 0!==arguments[4]?arguments[4]:0,r=this.w,o=r.globals.dom.Paper.pattern(e,i,(function(r){"horizontalLines"===t?r.line(0,0,i,0).stroke({color:a,width:s+1}):"verticalLines"===t?r.line(0,0,0,e).stroke({color:a,width:s+1}):"slantedLines"===t?r.line(0,0,e,i).stroke({color:a,width:s}):"squares"===t?r.rect(e,i).fill("none").stroke({color:a,width:s}):"circles"===t&&r.circle(e).fill("none").stroke({color:a,width:s})}));return o}},{key:"drawGradient",value:function(t,e,i,a,s){var r,o=arguments.length>5&&void 0!==arguments[5]?arguments[5]:null,n=arguments.length>6&&void 0!==arguments[6]?arguments[6]:null,l=arguments.length>7&&void 0!==arguments[7]?arguments[7]:null,h=arguments.length>8&&void 0!==arguments[8]?arguments[8]:0,c=this.w;e.length<9&&0===e.indexOf("#")&&(e=x.hexToRgba(e,a)),i.length<9&&0===i.indexOf("#")&&(i=x.hexToRgba(i,s));var d=0,g=1,u=1,f=null;null!==n&&(d=void 0!==n[0]?n[0]/100:0,g=void 0!==n[1]?n[1]/100:1,u=void 0!==n[2]?n[2]/100:1,f=void 0!==n[3]?n[3]/100:null);var p=!("donut"!==c.config.chart.type&&"pie"!==c.config.chart.type&&"polarArea"!==c.config.chart.type&&"bubble"!==c.config.chart.type);if(r=null===l||0===l.length?c.globals.dom.Paper.gradient(p?"radial":"linear",(function(t){t.at(d,e,a),t.at(g,i,s),t.at(u,i,s),null!==f&&t.at(f,e,a)})):c.globals.dom.Paper.gradient(p?"radial":"linear",(function(t){(Array.isArray(l[h])?l[h]:l).forEach((function(e){t.at(e.offset/100,e.color,e.opacity)}))})),p){var b=c.globals.gridWidth/2,v=c.globals.gridHeight/2;"bubble"!==c.config.chart.type?r.attr({gradientUnits:"userSpaceOnUse",cx:b,cy:v,r:o}):r.attr({cx:.5,cy:.5,r:.8,fx:.2,fy:.2})}else"vertical"===t?r.from(0,0).to(0,1):"diagonal"===t?r.from(0,0).to(1,1):"horizontal"===t?r.from(0,1).to(1,1):"diagonal2"===t&&r.from(1,0).to(0,1);return r}},{key:"getTextBasedOnMaxWidth",value:function(t){var e=t.text,i=t.maxWidth,a=t.fontSize,s=t.fontFamily,r=this.getTextRects(e,a,s),o=r.width/e.length,n=Math.floor(i/o);return i<r.width?e.slice(0,n-3)+"...":e}},{key:"drawText",value:function(t){var i=this,a=t.x,s=t.y,r=t.text,o=t.textAnchor,n=t.fontSize,l=t.fontFamily,h=t.fontWeight,c=t.foreColor,d=t.opacity,g=t.maxWidth,u=t.cssClass,f=void 0===u?"":u,p=t.isPlainText,x=void 0===p||p,b=this.w;void 0===r&&(r="");var v=r;o||(o="start"),c&&c.length||(c=b.config.chart.foreColor),l=l||b.config.chart.fontFamily,h=h||"regular";var m,y={maxWidth:g,fontSize:n=n||"11px",fontFamily:l};return Array.isArray(r)?m=b.globals.dom.Paper.text((function(t){for(var a=0;a<r.length;a++)v=r[a],g&&(v=i.getTextBasedOnMaxWidth(e({text:r[a]},y))),0===a?t.tspan(v):t.tspan(v).newLine()})):(g&&(v=this.getTextBasedOnMaxWidth(e({text:r},y))),m=x?b.globals.dom.Paper.plain(r):b.globals.dom.Paper.text((function(t){return t.tspan(v)}))),m.attr({x:a,y:s,"text-anchor":o,"dominant-baseline":"auto","font-size":n,"font-family":l,"font-weight":h,fill:c,class:"apexcharts-text "+f}),m.node.style.fontFamily=l,m.node.style.opacity=d,m}},{key:"drawMarker",value:function(t,e,i){t=t||0;var a=i.pSize||0,s=null;if("square"===i.shape||"rect"===i.shape){var r=void 0===i.pRadius?a/2:i.pRadius;null!==e&&a||(a=0,r=0);var o=1.2*a+r,n=this.drawRect(o,o,o,o,r);n.attr({x:t-o/2,y:e-o/2,cx:t,cy:e,class:i.class?i.class:"",fill:i.pointFillColor,"fill-opacity":i.pointFillOpacity?i.pointFillOpacity:1,stroke:i.pointStrokeColor,"stroke-width":i.pointStrokeWidth?i.pointStrokeWidth:0,"stroke-opacity":i.pointStrokeOpacity?i.pointStrokeOpacity:1}),s=n}else"circle"!==i.shape&&i.shape||(x.isNumber(e)||(a=0,e=0),s=this.drawCircle(a,{cx:t,cy:e,class:i.class?i.class:"",stroke:i.pointStrokeColor,fill:i.pointFillColor,"fill-opacity":i.pointFillOpacity?i.pointFillOpacity:1,"stroke-width":i.pointStrokeWidth?i.pointStrokeWidth:0,"stroke-opacity":i.pointStrokeOpacity?i.pointStrokeOpacity:1}));return s}},{key:"pathMouseEnter",value:function(t,e){var i=this.w,a=new v(this.ctx),s=parseInt(t.node.getAttribute("index"),10),r=parseInt(t.node.getAttribute("j"),10);if("function"==typeof i.config.chart.events.dataPointMouseEnter&&i.config.chart.events.dataPointMouseEnter(e,this.ctx,{seriesIndex:s,dataPointIndex:r,w:i}),this.ctx.events.fireEvent("dataPointMouseEnter",[e,this.ctx,{seriesIndex:s,dataPointIndex:r,w:i}]),("none"===i.config.states.active.filter.type||"true"!==t.node.getAttribute("selected"))&&"none"!==i.config.states.hover.filter.type&&!i.globals.isTouchDevice){var o=i.config.states.hover.filter;a.applyFilter(t,s,o.type,o.value)}}},{key:"pathMouseLeave",value:function(t,e){var i=this.w,a=new v(this.ctx),s=parseInt(t.node.getAttribute("index"),10),r=parseInt(t.node.getAttribute("j"),10);"function"==typeof i.config.chart.events.dataPointMouseLeave&&i.config.chart.events.dataPointMouseLeave(e,this.ctx,{seriesIndex:s,dataPointIndex:r,w:i}),this.ctx.events.fireEvent("dataPointMouseLeave",[e,this.ctx,{seriesIndex:s,dataPointIndex:r,w:i}]),"none"!==i.config.states.active.filter.type&&"true"===t.node.getAttribute("selected")||"none"!==i.config.states.hover.filter.type&&a.getDefaultFilter(t,s)}},{key:"pathMouseDown",value:function(t,e){var i=this.w,a=new v(this.ctx),s=parseInt(t.node.getAttribute("index"),10),r=parseInt(t.node.getAttribute("j"),10),o="false";if("true"===t.node.getAttribute("selected")){if(t.node.setAttribute("selected","false"),i.globals.selectedDataPoints[s].indexOf(r)>-1){var n=i.globals.selectedDataPoints[s].indexOf(r);i.globals.selectedDataPoints[s].splice(n,1)}}else{if(!i.config.states.active.allowMultipleDataPointsSelection&&i.globals.selectedDataPoints.length>0){i.globals.selectedDataPoints=[];var l=i.globals.dom.Paper.select(".apexcharts-series path").members,h=i.globals.dom.Paper.select(".apexcharts-series circle, .apexcharts-series rect").members,c=function(t){Array.prototype.forEach.call(t,(function(t){t.node.setAttribute("selected","false"),a.getDefaultFilter(t,s)}))};c(l),c(h)}t.node.setAttribute("selected","true"),o="true",void 0===i.globals.selectedDataPoints[s]&&(i.globals.selectedDataPoints[s]=[]),i.globals.selectedDataPoints[s].push(r)}if("true"===o){var d=i.config.states.active.filter;if("none"!==d)a.applyFilter(t,s,d.type,d.value);else if("none"!==i.config.states.hover.filter&&!i.globals.isTouchDevice){var g=i.config.states.hover.filter;a.applyFilter(t,s,g.type,g.value)}}else if("none"!==i.config.states.active.filter.type)if("none"===i.config.states.hover.filter.type||i.globals.isTouchDevice)a.getDefaultFilter(t,s);else{g=i.config.states.hover.filter;a.applyFilter(t,s,g.type,g.value)}"function"==typeof i.config.chart.events.dataPointSelection&&i.config.chart.events.dataPointSelection(e,this.ctx,{selectedDataPoints:i.globals.selectedDataPoints,seriesIndex:s,dataPointIndex:r,w:i}),e&&this.ctx.events.fireEvent("dataPointSelection",[e,this.ctx,{selectedDataPoints:i.globals.selectedDataPoints,seriesIndex:s,dataPointIndex:r,w:i}])}},{key:"rotateAroundCenter",value:function(t){var e={};return t&&"function"==typeof t.getBBox&&(e=t.getBBox()),{x:e.x+e.width/2,y:e.y+e.height/2}}},{key:"getTextRects",value:function(t,e,i,a){var s=!(arguments.length>4&&void 0!==arguments[4])||arguments[4],r=this.w,o=this.drawText({x:-200,y:-200,text:t,textAnchor:"start",fontSize:e,fontFamily:i,foreColor:"#fff",opacity:0});a&&o.attr("transform",a),r.globals.dom.Paper.add(o);var n=o.bbox();return s||(n=o.node.getBoundingClientRect()),o.remove(),{width:n.width,height:n.height}}},{key:"placeTextWithEllipsis",value:function(t,e,i){if("function"==typeof t.getComputedTextLength&&(t.textContent=e,e.length>0&&t.getComputedTextLength()>=i/1.1)){for(var a=e.length-3;a>0;a-=3)if(t.getSubStringLength(0,a)<=i/1.1)return void(t.textContent=e.substring(0,a)+"...");t.textContent="."}}}],[{key:"setAttrs",value:function(t,e){for(var i in e)e.hasOwnProperty(i)&&t.setAttribute(i,e[i])}}]),t}(),y=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"getStackedSeriesTotals",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],e=this.w,i=[];if(0===e.globals.series.length)return i;for(var a=0;a<e.globals.series[e.globals.maxValsInArrayIndex].length;a++){for(var s=0,r=0;r<e.globals.series.length;r++)void 0!==e.globals.series[r][a]&&-1===t.indexOf(r)&&(s+=e.globals.series[r][a]);i.push(s)}return i}},{key:"getSeriesTotalByIndex",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;return null===t?this.w.config.series.reduce((function(t,e){return t+e}),0):this.w.globals.series[t].reduce((function(t,e){return t+e}),0)}},{key:"isSeriesNull",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;return 0===(null===t?this.w.config.series.filter((function(t){return null!==t})):this.w.config.series[t].data.filter((function(t){return null!==t}))).length}},{key:"seriesHaveSameValues",value:function(t){return this.w.globals.series[t].every((function(t,e,i){return t===i[0]}))}},{key:"getCategoryLabels",value:function(t){var e=this.w,i=t.slice();return e.config.xaxis.convertedCatToNumeric&&(i=t.map((function(t,i){return e.config.xaxis.labels.formatter(t-e.globals.minX+1)}))),i}},{key:"getLargestSeries",value:function(){var t=this.w;t.globals.maxValsInArrayIndex=t.globals.series.map((function(t){return t.length})).indexOf(Math.max.apply(Math,t.globals.series.map((function(t){return t.length}))))}},{key:"getLargestMarkerSize",value:function(){var t=this.w,e=0;return t.globals.markers.size.forEach((function(t){e=Math.max(e,t)})),t.config.markers.discrete&&t.config.markers.discrete.length&&t.config.markers.discrete.forEach((function(t){e=Math.max(e,t.size)})),e>0&&(e+=t.config.markers.hover.sizeOffset+1),t.globals.markers.largestSize=e,e}},{key:"getSeriesTotals",value:function(){var t=this.w;t.globals.seriesTotals=t.globals.series.map((function(t,e){var i=0;if(Array.isArray(t))for(var a=0;a<t.length;a++)i+=t[a];else i+=t;return i}))}},{key:"getSeriesTotalsXRange",value:function(t,e){var i=this.w;return i.globals.series.map((function(a,s){for(var r=0,o=0;o<a.length;o++)i.globals.seriesX[s][o]>t&&i.globals.seriesX[s][o]<e&&(r+=a[o]);return r}))}},{key:"getPercentSeries",value:function(){var t=this.w;t.globals.seriesPercent=t.globals.series.map((function(e,i){var a=[];if(Array.isArray(e))for(var s=0;s<e.length;s++){var r=t.globals.stackedSeriesTotals[s],o=0;r&&(o=100*e[s]/r),a.push(o)}else{var n=100*e/t.globals.seriesTotals.reduce((function(t,e){return t+e}),0);a.push(n)}return a}))}},{key:"getCalculatedRatios",value:function(){var t,e,i,a,s=this.w.globals,r=[],o=0,n=[],l=.1,h=0;if(s.yRange=[],s.isMultipleYAxis)for(var c=0;c<s.minYArr.length;c++)s.yRange.push(Math.abs(s.minYArr[c]-s.maxYArr[c])),n.push(0);else s.yRange.push(Math.abs(s.minY-s.maxY));s.xRange=Math.abs(s.maxX-s.minX),s.zRange=Math.abs(s.maxZ-s.minZ);for(var d=0;d<s.yRange.length;d++)r.push(s.yRange[d]/s.gridHeight);if(e=s.xRange/s.gridWidth,i=Math.abs(s.initialMaxX-s.initialMinX)/s.gridWidth,t=s.yRange/s.gridWidth,a=s.xRange/s.gridHeight,(o=s.zRange/s.gridHeight*16)||(o=1),s.minY!==Number.MIN_VALUE&&0!==Math.abs(s.minY)&&(s.hasNegs=!0),s.isMultipleYAxis){n=[];for(var g=0;g<r.length;g++)n.push(-s.minYArr[g]/r[g])}else n.push(-s.minY/r[0]),s.minY!==Number.MIN_VALUE&&0!==Math.abs(s.minY)&&(l=-s.minY/t,h=s.minX/e);return{yRatio:r,invertedYRatio:t,zRatio:o,xRatio:e,initialXRatio:i,invertedXRatio:a,baseLineInvertedY:l,baseLineY:n,baseLineX:h}}},{key:"getLogSeries",value:function(t){var e=this,i=this.w;return i.globals.seriesLog=t.map((function(t,a){return i.config.yaxis[a]&&i.config.yaxis[a].logarithmic?t.map((function(t){return null===t?null:e.getLogVal(i.config.yaxis[a].logBase,t,a)})):t})),i.globals.invalidLogScale?t:i.globals.seriesLog}},{key:"getBaseLog",value:function(t,e){return Math.log(e)/Math.log(t)}},{key:"getLogVal",value:function(t,e,i){if(0===e)return 0;var a=this.w,s=0===a.globals.minYArr[i]?-1:this.getBaseLog(t,a.globals.minYArr[i]),r=(0===a.globals.maxYArr[i]?0:this.getBaseLog(t,a.globals.maxYArr[i]))-s;return e<1?e/r:(this.getBaseLog(t,e)-s)/r}},{key:"getLogYRatios",value:function(t){var e=this,i=this.w,a=this.w.globals;return a.yLogRatio=t.slice(),a.logYRange=a.yRange.map((function(t,s){if(i.config.yaxis[s]&&e.w.config.yaxis[s].logarithmic){var r,o=-Number.MAX_VALUE,n=Number.MIN_VALUE;return a.seriesLog.forEach((function(t,e){t.forEach((function(t){i.config.yaxis[e]&&i.config.yaxis[e].logarithmic&&(o=Math.max(t,o),n=Math.min(t,n))}))})),r=Math.pow(a.yRange[s],Math.abs(n-o)/a.yRange[s]),a.yLogRatio[s]=r/a.gridHeight,r}})),a.invalidLogScale?t.slice():a.yLogRatio}}],[{key:"checkComboSeries",value:function(t){var e=!1,i=0,a=0;return t.length&&void 0!==t[0].type&&t.forEach((function(t){"bar"!==t.type&&"column"!==t.type&&"candlestick"!==t.type&&"boxPlot"!==t.type||i++,void 0!==t.type&&a++})),a>0&&(e=!0),{comboBarCount:i,comboCharts:e}}},{key:"extendArrayProps",value:function(t,e,i){return e.yaxis&&(e=t.extendYAxis(e,i)),e.annotations&&(e.annotations.yaxis&&(e=t.extendYAxisAnnotations(e)),e.annotations.xaxis&&(e=t.extendXAxisAnnotations(e)),e.annotations.points&&(e=t.extendPointAnnotations(e))),e}}]),t}(),w=function(){function t(e){a(this,t),this.w=e.w,this.annoCtx=e}return r(t,[{key:"setOrientations",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,i=this.w;if("vertical"===t.label.orientation){var a=null!==e?e:0,s=i.globals.dom.baseEl.querySelector(".apexcharts-xaxis-annotations .apexcharts-xaxis-annotation-label[rel='".concat(a,"']"));if(null!==s){var r=s.getBoundingClientRect();s.setAttribute("x",parseFloat(s.getAttribute("x"))-r.height+4),"top"===t.label.position?s.setAttribute("y",parseFloat(s.getAttribute("y"))+r.width):s.setAttribute("y",parseFloat(s.getAttribute("y"))-r.width);var o=this.annoCtx.graphics.rotateAroundCenter(s),n=o.x,l=o.y;s.setAttribute("transform","rotate(-90 ".concat(n," ").concat(l,")"))}}}},{key:"addBackgroundToAnno",value:function(t,e){var i=this.w;if(!t||void 0===e.label.text||void 0!==e.label.text&&!String(e.label.text).trim())return null;var a=i.globals.dom.baseEl.querySelector(".apexcharts-grid").getBoundingClientRect(),s=t.getBoundingClientRect(),r=e.label.style.padding.left,o=e.label.style.padding.right,n=e.label.style.padding.top,l=e.label.style.padding.bottom;"vertical"===e.label.orientation&&(n=e.label.style.padding.left,l=e.label.style.padding.right,r=e.label.style.padding.top,o=e.label.style.padding.bottom);var h=s.left-a.left-r,c=s.top-a.top-n,d=this.annoCtx.graphics.drawRect(h-i.globals.barPadForNumericAxis,c,s.width+r+o,s.height+n+l,e.label.borderRadius,e.label.style.background,1,e.label.borderWidth,e.label.borderColor,0);return e.id&&d.node.classList.add(e.id),d}},{key:"annotationsBackground",value:function(){var t=this,e=this.w,i=function(i,a,s){var r=e.globals.dom.baseEl.querySelector(".apexcharts-".concat(s,"-annotations .apexcharts-").concat(s,"-annotation-label[rel='").concat(a,"']"));if(r){var o=r.parentNode,n=t.addBackgroundToAnno(r,i);n&&(o.insertBefore(n.node,r),i.label.mouseEnter&&n.node.addEventListener("mouseenter",i.label.mouseEnter.bind(t,i)),i.label.mouseLeave&&n.node.addEventListener("mouseleave",i.label.mouseLeave.bind(t,i)),i.label.click&&n.node.addEventListener("click",i.label.click.bind(t,i)))}};e.config.annotations.xaxis.map((function(t,e){i(t,e,"xaxis")})),e.config.annotations.yaxis.map((function(t,e){i(t,e,"yaxis")})),e.config.annotations.points.map((function(t,e){i(t,e,"point")}))}},{key:"getY1Y2",value:function(t,e){var i,a="y1"===t?e.y:e.y2,s=this.w;if(this.annoCtx.invertAxis){var r=s.globals.labels.indexOf(a);s.config.xaxis.convertedCatToNumeric&&(r=s.globals.categoryLabels.indexOf(a));var o=s.globals.dom.baseEl.querySelector(".apexcharts-yaxis-texts-g text:nth-child("+(r+1)+")");o&&(i=parseFloat(o.getAttribute("y")))}else{var n;if(s.config.yaxis[e.yAxisIndex].logarithmic)n=(a=new y(this.annoCtx.ctx).getLogVal(a,e.yAxisIndex))/s.globals.yLogRatio[e.yAxisIndex];else n=(a-s.globals.minYArr[e.yAxisIndex])/(s.globals.yRange[e.yAxisIndex]/s.globals.gridHeight);i=s.globals.gridHeight-n,!e.marker||void 0!==e.y&&null!==e.y||(i=0),s.config.yaxis[e.yAxisIndex]&&s.config.yaxis[e.yAxisIndex].reversed&&(i=n)}return"string"==typeof a&&a.indexOf("px")>-1&&(i=parseFloat(a)),i}},{key:"getX1X2",value:function(t,e){var i=this.w,a=this.annoCtx.invertAxis?i.globals.minY:i.globals.minX,s=this.annoCtx.invertAxis?i.globals.maxY:i.globals.maxX,r=this.annoCtx.invertAxis?i.globals.yRange[0]:i.globals.xRange,o=(e.x-a)/(r/i.globals.gridWidth);this.annoCtx.inversedReversedAxis&&(o=(s-e.x)/(r/i.globals.gridWidth)),"category"!==i.config.xaxis.type&&!i.config.xaxis.convertedCatToNumeric||this.annoCtx.invertAxis||i.globals.dataFormatXNumeric||(o=this.getStringX(e.x));var n=(e.x2-a)/(r/i.globals.gridWidth);return this.annoCtx.inversedReversedAxis&&(n=(s-e.x2)/(r/i.globals.gridWidth)),"category"!==i.config.xaxis.type&&!i.config.xaxis.convertedCatToNumeric||this.annoCtx.invertAxis||i.globals.dataFormatXNumeric||(n=this.getStringX(e.x2)),void 0!==e.x&&null!==e.x||!e.marker||(o=i.globals.gridWidth),"x1"===t&&"string"==typeof e.x&&e.x.indexOf("px")>-1&&(o=parseFloat(e.x)),"x2"===t&&"string"==typeof e.x2&&e.x2.indexOf("px")>-1&&(n=parseFloat(e.x2)),"x1"===t?o:n}},{key:"getStringX",value:function(t){var e=this.w,i=t;e.config.xaxis.convertedCatToNumeric&&e.globals.categoryLabels.length&&(t=e.globals.categoryLabels.indexOf(t)+1);var a=e.globals.labels.indexOf(t),s=e.globals.dom.baseEl.querySelector(".apexcharts-xaxis-texts-g text:nth-child("+(a+1)+")");return s&&(i=parseFloat(s.getAttribute("x"))),i}}]),t}(),k=function(){function t(e){a(this,t),this.w=e.w,this.annoCtx=e,this.invertAxis=this.annoCtx.invertAxis,this.helpers=new w(this.annoCtx)}return r(t,[{key:"addXaxisAnnotation",value:function(t,e,i){var a,s=this.w,r=this.helpers.getX1X2("x1",t),o=t.label.text,n=t.strokeDashArray;if(x.isNumber(r)){if(null===t.x2||void 0===t.x2){var l=this.annoCtx.graphics.drawLine(r+t.offsetX,0+t.offsetY,r+t.offsetX,s.globals.gridHeight+t.offsetY,t.borderColor,n,t.borderWidth);e.appendChild(l.node),t.id&&l.node.classList.add(t.id)}else{if((a=this.helpers.getX1X2("x2",t))<r){var h=r;r=a,a=h}var c=this.annoCtx.graphics.drawRect(r+t.offsetX,0+t.offsetY,a-r,s.globals.gridHeight+t.offsetY,0,t.fillColor,t.opacity,1,t.borderColor,n);c.node.classList.add("apexcharts-annotation-rect"),c.attr("clip-path","url(#gridRectMask".concat(s.globals.cuid,")")),e.appendChild(c.node),t.id&&c.node.classList.add(t.id)}var d=this.annoCtx.graphics.getTextRects(o,parseFloat(t.label.style.fontSize)),g="top"===t.label.position?4:"center"===t.label.position?s.globals.gridHeight/2+("vertical"===t.label.orientation?d.width/2:0):s.globals.gridHeight,u=this.annoCtx.graphics.drawText({x:r+t.label.offsetX,y:g+t.label.offsetY-("vertical"===t.label.orientation?"top"===t.label.position?d.width/2-12:-d.width/2:0),text:o,textAnchor:t.label.textAnchor,fontSize:t.label.style.fontSize,fontFamily:t.label.style.fontFamily,fontWeight:t.label.style.fontWeight,foreColor:t.label.style.color,cssClass:"apexcharts-xaxis-annotation-label ".concat(t.label.style.cssClass," ").concat(t.id?t.id:"")});u.attr({rel:i}),e.appendChild(u.node),this.annoCtx.helpers.setOrientations(t,i)}}},{key:"drawXAxisAnnotations",value:function(){var t=this,e=this.w,i=this.annoCtx.graphics.group({class:"apexcharts-xaxis-annotations"});return e.config.annotations.xaxis.map((function(e,a){t.addXaxisAnnotation(e,i.node,a)})),i}}]),t}(),A=function(){function t(e){a(this,t),this.w=e.w,this.annoCtx=e,this.helpers=new w(this.annoCtx)}return r(t,[{key:"addYaxisAnnotation",value:function(t,e,i){var a,s=this.w,r=t.strokeDashArray,o=this.helpers.getY1Y2("y1",t),n=t.label.text;if(null===t.y2||void 0===t.y2){var l=this.annoCtx.graphics.drawLine(0+t.offsetX,o+t.offsetY,this._getYAxisAnnotationWidth(t),o+t.offsetY,t.borderColor,r,t.borderWidth);e.appendChild(l.node),t.id&&l.node.classList.add(t.id)}else{if((a=this.helpers.getY1Y2("y2",t))>o){var h=o;o=a,a=h}var c=this.annoCtx.graphics.drawRect(0+t.offsetX,a+t.offsetY,this._getYAxisAnnotationWidth(t),o-a,0,t.fillColor,t.opacity,1,t.borderColor,r);c.node.classList.add("apexcharts-annotation-rect"),c.attr("clip-path","url(#gridRectMask".concat(s.globals.cuid,")")),e.appendChild(c.node),t.id&&c.node.classList.add(t.id)}var d="right"===t.label.position?s.globals.gridWidth:"center"===t.label.position?s.globals.gridWidth/2:0,g=this.annoCtx.graphics.drawText({x:d+t.label.offsetX,y:(null!=a?a:o)+t.label.offsetY-3,text:n,textAnchor:t.label.textAnchor,fontSize:t.label.style.fontSize,fontFamily:t.label.style.fontFamily,fontWeight:t.label.style.fontWeight,foreColor:t.label.style.color,cssClass:"apexcharts-yaxis-annotation-label ".concat(t.label.style.cssClass," ").concat(t.id?t.id:"")});g.attr({rel:i}),e.appendChild(g.node)}},{key:"_getYAxisAnnotationWidth",value:function(t){var e=this.w;e.globals.gridWidth;return(t.width.indexOf("%")>-1?e.globals.gridWidth*parseInt(t.width,10)/100:parseInt(t.width,10))+t.offsetX}},{key:"drawYAxisAnnotations",value:function(){var t=this,e=this.w,i=this.annoCtx.graphics.group({class:"apexcharts-yaxis-annotations"});return e.config.annotations.yaxis.map((function(e,a){t.addYaxisAnnotation(e,i.node,a)})),i}}]),t}(),S=function(){function t(e){a(this,t),this.w=e.w,this.annoCtx=e,this.helpers=new w(this.annoCtx)}return r(t,[{key:"addPointAnnotation",value:function(t,e,i){this.w;var a=this.helpers.getX1X2("x1",t),s=this.helpers.getY1Y2("y1",t);if(x.isNumber(a)){var r={pSize:t.marker.size,pointStrokeWidth:t.marker.strokeWidth,pointFillColor:t.marker.fillColor,pointStrokeColor:t.marker.strokeColor,shape:t.marker.shape,pRadius:t.marker.radius,class:"apexcharts-point-annotation-marker ".concat(t.marker.cssClass," ").concat(t.id?t.id:"")},o=this.annoCtx.graphics.drawMarker(a+t.marker.offsetX,s+t.marker.offsetY,r);e.appendChild(o.node);var n=t.label.text?t.label.text:"",l=this.annoCtx.graphics.drawText({x:a+t.label.offsetX,y:s+t.label.offsetY-t.marker.size-parseFloat(t.label.style.fontSize)/1.6,text:n,textAnchor:t.label.textAnchor,fontSize:t.label.style.fontSize,fontFamily:t.label.style.fontFamily,fontWeight:t.label.style.fontWeight,foreColor:t.label.style.color,cssClass:"apexcharts-point-annotation-label ".concat(t.label.style.cssClass," ").concat(t.id?t.id:"")});if(l.attr({rel:i}),e.appendChild(l.node),t.customSVG.SVG){var h=this.annoCtx.graphics.group({class:"apexcharts-point-annotations-custom-svg "+t.customSVG.cssClass});h.attr({transform:"translate(".concat(a+t.customSVG.offsetX,", ").concat(s+t.customSVG.offsetY,")")}),h.node.innerHTML=t.customSVG.SVG,e.appendChild(h.node)}if(t.image.path){var c=t.image.width?t.image.width:20,d=t.image.height?t.image.height:20;o=this.annoCtx.addImage({x:a+t.image.offsetX-c/2,y:s+t.image.offsetY-d/2,width:c,height:d,path:t.image.path,appendTo:".apexcharts-point-annotations"})}t.mouseEnter&&o.node.addEventListener("mouseenter",t.mouseEnter.bind(this,t)),t.mouseLeave&&o.node.addEventListener("mouseleave",t.mouseLeave.bind(this,t)),t.click&&o.node.addEventListener("click",t.click.bind(this,t))}}},{key:"drawPointAnnotations",value:function(){var t=this,e=this.w,i=this.annoCtx.graphics.group({class:"apexcharts-point-annotations"});return e.config.annotations.points.map((function(e,a){t.addPointAnnotation(e,i.node,a)})),i}}]),t}();var C={name:"en",options:{months:["January","February","March","April","May","June","July","August","September","October","November","December"],shortMonths:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],shortDays:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],toolbar:{exportToSVG:"Download SVG",exportToPNG:"Download PNG",exportToCSV:"Download CSV",menu:"Menu",selection:"Selection",selectionZoom:"Selection Zoom",zoomIn:"Zoom In",zoomOut:"Zoom Out",pan:"Panning",reset:"Reset Zoom"}}},L=function(){function t(){a(this,t),this.yAxis={show:!0,showAlways:!1,showForNullSeries:!0,seriesName:void 0,opposite:!1,reversed:!1,logarithmic:!1,logBase:10,tickAmount:void 0,forceNiceScale:!1,max:void 0,min:void 0,floating:!1,decimalsInFloat:void 0,labels:{show:!0,minWidth:0,maxWidth:160,offsetX:0,offsetY:0,align:void 0,rotate:0,padding:20,style:{colors:[],fontSize:"11px",fontWeight:400,fontFamily:void 0,cssClass:""},formatter:void 0},axisBorder:{show:!1,color:"#e0e0e0",width:1,offsetX:0,offsetY:0},axisTicks:{show:!1,color:"#e0e0e0",width:6,offsetX:0,offsetY:0},title:{text:void 0,rotate:-90,offsetY:0,offsetX:0,style:{color:void 0,fontSize:"11px",fontWeight:900,fontFamily:void 0,cssClass:""}},tooltip:{enabled:!1,offsetX:0},crosshairs:{show:!0,position:"front",stroke:{color:"#b6b6b6",width:1,dashArray:0}}},this.pointAnnotation={id:void 0,x:0,y:null,yAxisIndex:0,seriesIndex:0,mouseEnter:void 0,mouseLeave:void 0,click:void 0,marker:{size:4,fillColor:"#fff",strokeWidth:2,strokeColor:"#333",shape:"circle",offsetX:0,offsetY:0,radius:2,cssClass:""},label:{borderColor:"#c2c2c2",borderWidth:1,borderRadius:2,text:void 0,textAnchor:"middle",offsetX:0,offsetY:0,mouseEnter:void 0,mouseLeave:void 0,click:void 0,style:{background:"#fff",color:void 0,fontSize:"11px",fontFamily:void 0,fontWeight:400,cssClass:"",padding:{left:5,right:5,top:2,bottom:2}}},customSVG:{SVG:void 0,cssClass:void 0,offsetX:0,offsetY:0},image:{path:void 0,width:20,height:20,offsetX:0,offsetY:0}},this.yAxisAnnotation={id:void 0,y:0,y2:null,strokeDashArray:1,fillColor:"#c2c2c2",borderColor:"#c2c2c2",borderWidth:1,opacity:.3,offsetX:0,offsetY:0,width:"100%",yAxisIndex:0,label:{borderColor:"#c2c2c2",borderWidth:1,borderRadius:2,text:void 0,textAnchor:"end",position:"right",offsetX:0,offsetY:-3,mouseEnter:void 0,mouseLeave:void 0,click:void 0,style:{background:"#fff",color:void 0,fontSize:"11px",fontFamily:void 0,fontWeight:400,cssClass:"",padding:{left:5,right:5,top:2,bottom:2}}}},this.xAxisAnnotation={id:void 0,x:0,x2:null,strokeDashArray:1,fillColor:"#c2c2c2",borderColor:"#c2c2c2",borderWidth:1,opacity:.3,offsetX:0,offsetY:0,label:{borderColor:"#c2c2c2",borderWidth:1,borderRadius:2,text:void 0,textAnchor:"middle",orientation:"vertical",position:"top",offsetX:0,offsetY:0,mouseEnter:void 0,mouseLeave:void 0,click:void 0,style:{background:"#fff",color:void 0,fontSize:"11px",fontFamily:void 0,fontWeight:400,cssClass:"",padding:{left:5,right:5,top:2,bottom:2}}}},this.text={x:0,y:0,text:"",textAnchor:"start",foreColor:void 0,fontSize:"13px",fontFamily:void 0,fontWeight:400,appendTo:".apexcharts-annotations",backgroundColor:"transparent",borderColor:"#c2c2c2",borderRadius:0,borderWidth:0,paddingLeft:4,paddingRight:4,paddingTop:2,paddingBottom:2}}return r(t,[{key:"init",value:function(){return{annotations:{yaxis:[this.yAxisAnnotation],xaxis:[this.xAxisAnnotation],points:[this.pointAnnotation],texts:[],images:[],shapes:[]},chart:{animations:{enabled:!0,easing:"easeinout",speed:800,animateGradually:{delay:150,enabled:!0},dynamicAnimation:{enabled:!0,speed:350}},background:"transparent",locales:[C],defaultLocale:"en",dropShadow:{enabled:!1,enabledOnSeries:void 0,top:2,left:2,blur:4,color:"#000",opacity:.35},events:{animationEnd:void 0,beforeMount:void 0,mounted:void 0,updated:void 0,click:void 0,mouseMove:void 0,mouseLeave:void 0,xAxisLabelClick:void 0,legendClick:void 0,markerClick:void 0,selection:void 0,dataPointSelection:void 0,dataPointMouseEnter:void 0,dataPointMouseLeave:void 0,beforeZoom:void 0,beforeResetZoom:void 0,zoomed:void 0,scrolled:void 0,brushScrolled:void 0},foreColor:"#373d3f",fontFamily:"Helvetica, Arial, sans-serif",height:"auto",parentHeightOffset:15,redrawOnParentResize:!0,redrawOnWindowResize:!0,id:void 0,group:void 0,offsetX:0,offsetY:0,selection:{enabled:!1,type:"x",fill:{color:"#24292e",opacity:.1},stroke:{width:1,color:"#24292e",opacity:.4,dashArray:3},xaxis:{min:void 0,max:void 0},yaxis:{min:void 0,max:void 0}},sparkline:{enabled:!1},brush:{enabled:!1,autoScaleYaxis:!0,target:void 0},stacked:!1,stackType:"normal",toolbar:{show:!0,offsetX:0,offsetY:0,tools:{download:!0,selection:!0,zoom:!0,zoomin:!0,zoomout:!0,pan:!0,reset:!0,customIcons:[]},export:{csv:{filename:void 0,columnDelimiter:",",headerCategory:"category",headerValue:"value",dateFormatter:function(t){return new Date(t).toDateString()}},png:{filename:void 0},svg:{filename:void 0}},autoSelected:"zoom"},type:"line",width:"100%",zoom:{enabled:!0,type:"x",autoScaleYaxis:!1,zoomedArea:{fill:{color:"#90CAF9",opacity:.4},stroke:{color:"#0D47A1",opacity:.4,width:1}}}},plotOptions:{area:{fillTo:"origin"},bar:{horizontal:!1,columnWidth:"70%",barHeight:"70%",distributed:!1,borderRadius:0,borderRadiusApplication:"around",borderRadiusWhenStacked:"last",rangeBarOverlap:!0,rangeBarGroupRows:!1,hideZeroBarsWhenGrouped:!0,colors:{ranges:[],backgroundBarColors:[],backgroundBarOpacity:1,backgroundBarRadius:0},dataLabels:{position:"top",maxItems:100,hideOverflowingLabels:!0,orientation:"horizontal",total:{enabled:!1,formatter:void 0,offsetX:0,offsetY:0,style:{color:"#373d3f",fontSize:"12px",fontFamily:void 0,fontWeight:600}}}},bubble:{zScaling:!0,minBubbleRadius:void 0,maxBubbleRadius:void 0},candlestick:{colors:{upward:"#00B746",downward:"#EF403C"},wick:{useFillColor:!0}},boxPlot:{colors:{upper:"#00E396",lower:"#008FFB"}},heatmap:{radius:2,enableShades:!0,shadeIntensity:.5,reverseNegativeShade:!1,distributed:!1,useFillColorAsStroke:!1,colorScale:{inverse:!1,ranges:[],min:void 0,max:void 0}},treemap:{enableShades:!0,shadeIntensity:.5,distributed:!1,reverseNegativeShade:!1,useFillColorAsStroke:!1,colorScale:{inverse:!1,ranges:[],min:void 0,max:void 0}},radialBar:{inverseOrder:!1,startAngle:0,endAngle:360,offsetX:0,offsetY:0,hollow:{margin:5,size:"50%",background:"transparent",image:void 0,imageWidth:150,imageHeight:150,imageOffsetX:0,imageOffsetY:0,imageClipped:!0,position:"front",dropShadow:{enabled:!1,top:0,left:0,blur:3,color:"#000",opacity:.5}},track:{show:!0,startAngle:void 0,endAngle:void 0,background:"#f2f2f2",strokeWidth:"97%",opacity:1,margin:5,dropShadow:{enabled:!1,top:0,left:0,blur:3,color:"#000",opacity:.5}},dataLabels:{show:!0,name:{show:!0,fontSize:"16px",fontFamily:void 0,fontWeight:600,color:void 0,offsetY:0,formatter:function(t){return t}},value:{show:!0,fontSize:"14px",fontFamily:void 0,fontWeight:400,color:void 0,offsetY:16,formatter:function(t){return t+"%"}},total:{show:!1,label:"Total",fontSize:"16px",fontWeight:600,fontFamily:void 0,color:void 0,formatter:function(t){return t.globals.seriesTotals.reduce((function(t,e){return t+e}),0)/t.globals.series.length+"%"}}}},pie:{customScale:1,offsetX:0,offsetY:0,startAngle:0,endAngle:360,expandOnClick:!0,dataLabels:{offset:0,minAngleToShowLabel:10},donut:{size:"65%",background:"transparent",labels:{show:!1,name:{show:!0,fontSize:"16px",fontFamily:void 0,fontWeight:600,color:void 0,offsetY:-10,formatter:function(t){return t}},value:{show:!0,fontSize:"20px",fontFamily:void 0,fontWeight:400,color:void 0,offsetY:10,formatter:function(t){return t}},total:{show:!1,showAlways:!1,label:"Total",fontSize:"16px",fontWeight:400,fontFamily:void 0,color:void 0,formatter:function(t){return t.globals.seriesTotals.reduce((function(t,e){return t+e}),0)}}}}},polarArea:{rings:{strokeWidth:1,strokeColor:"#e8e8e8"},spokes:{strokeWidth:1,connectorColors:"#e8e8e8"}},radar:{size:void 0,offsetX:0,offsetY:0,polygons:{strokeWidth:1,strokeColors:"#e8e8e8",connectorColors:"#e8e8e8",fill:{colors:void 0}}}},colors:void 0,dataLabels:{enabled:!0,enabledOnSeries:void 0,formatter:function(t){return null!==t?t:""},textAnchor:"middle",distributed:!1,offsetX:0,offsetY:0,style:{fontSize:"12px",fontFamily:void 0,fontWeight:600,colors:void 0},background:{enabled:!0,foreColor:"#fff",borderRadius:2,padding:4,opacity:.9,borderWidth:1,borderColor:"#fff",dropShadow:{enabled:!1,top:1,left:1,blur:1,color:"#000",opacity:.45}},dropShadow:{enabled:!1,top:1,left:1,blur:1,color:"#000",opacity:.45}},fill:{type:"solid",colors:void 0,opacity:.85,gradient:{shade:"dark",type:"horizontal",shadeIntensity:.5,gradientToColors:void 0,inverseColors:!0,opacityFrom:1,opacityTo:1,stops:[0,50,100],colorStops:[]},image:{src:[],width:void 0,height:void 0},pattern:{style:"squares",width:6,height:6,strokeWidth:2}},forecastDataPoints:{count:0,fillOpacity:.5,strokeWidth:void 0,dashArray:4},grid:{show:!0,borderColor:"#e0e0e0",strokeDashArray:0,position:"back",xaxis:{lines:{show:!1}},yaxis:{lines:{show:!0}},row:{colors:void 0,opacity:.5},column:{colors:void 0,opacity:.5},padding:{top:0,right:10,bottom:0,left:12}},labels:[],legend:{show:!0,showForSingleSeries:!1,showForNullSeries:!0,showForZeroSeries:!0,floating:!1,position:"bottom",horizontalAlign:"center",inverseOrder:!1,fontSize:"12px",fontFamily:void 0,fontWeight:400,width:void 0,height:void 0,formatter:void 0,tooltipHoverFormatter:void 0,offsetX:-20,offsetY:4,customLegendItems:[],labels:{colors:void 0,useSeriesColors:!1},markers:{width:12,height:12,strokeWidth:0,fillColors:void 0,strokeColor:"#fff",radius:12,customHTML:void 0,offsetX:0,offsetY:0,onClick:void 0},itemMargin:{horizontal:5,vertical:2},onItemClick:{toggleDataSeries:!0},onItemHover:{highlightDataSeries:!0}},markers:{discrete:[],size:0,colors:void 0,strokeColors:"#fff",strokeWidth:2,strokeOpacity:.9,strokeDashArray:0,fillOpacity:1,shape:"circle",width:8,height:8,radius:2,offsetX:0,offsetY:0,onClick:void 0,onDblClick:void 0,showNullDataPoints:!0,hover:{size:void 0,sizeOffset:3}},noData:{text:void 0,align:"center",verticalAlign:"middle",offsetX:0,offsetY:0,style:{color:void 0,fontSize:"14px",fontFamily:void 0}},responsive:[],series:void 0,states:{normal:{filter:{type:"none",value:0}},hover:{filter:{type:"lighten",value:.1}},active:{allowMultipleDataPointsSelection:!1,filter:{type:"darken",value:.5}}},title:{text:void 0,align:"left",margin:5,offsetX:0,offsetY:0,floating:!1,style:{fontSize:"14px",fontWeight:900,fontFamily:void 0,color:void 0}},subtitle:{text:void 0,align:"left",margin:5,offsetX:0,offsetY:30,floating:!1,style:{fontSize:"12px",fontWeight:400,fontFamily:void 0,color:void 0}},stroke:{show:!0,curve:"smooth",lineCap:"butt",width:2,colors:void 0,dashArray:0,fill:{type:"solid",colors:void 0,opacity:.85,gradient:{shade:"dark",type:"horizontal",shadeIntensity:.5,gradientToColors:void 0,inverseColors:!0,opacityFrom:1,opacityTo:1,stops:[0,50,100],colorStops:[]}}},tooltip:{enabled:!0,enabledOnSeries:void 0,shared:!0,followCursor:!1,intersect:!1,inverseOrder:!1,custom:void 0,fillSeriesColor:!1,theme:"light",cssClass:"",style:{fontSize:"12px",fontFamily:void 0},onDatasetHover:{highlightDataSeries:!1},x:{show:!0,format:"dd MMM",formatter:void 0},y:{formatter:void 0,title:{formatter:function(t){return t?t+": ":""}}},z:{formatter:void 0,title:"Size: "},marker:{show:!0,fillColors:void 0},items:{display:"flex"},fixed:{enabled:!1,position:"topRight",offsetX:0,offsetY:0}},xaxis:{type:"category",categories:[],convertedCatToNumeric:!1,offsetX:0,offsetY:0,overwriteCategories:void 0,labels:{show:!0,rotate:-45,rotateAlways:!1,hideOverlappingLabels:!0,trim:!1,minHeight:void 0,maxHeight:120,showDuplicates:!0,style:{colors:[],fontSize:"12px",fontWeight:400,fontFamily:void 0,cssClass:""},offsetX:0,offsetY:0,format:void 0,formatter:void 0,datetimeUTC:!0,datetimeFormatter:{year:"yyyy",month:"MMM 'yy",day:"dd MMM",hour:"HH:mm",minute:"HH:mm:ss",second:"HH:mm:ss"}},group:{groups:[],style:{colors:[],fontSize:"12px",fontWeight:400,fontFamily:void 0,cssClass:""}},axisBorder:{show:!0,color:"#e0e0e0",width:"100%",height:1,offsetX:0,offsetY:0},axisTicks:{show:!0,color:"#e0e0e0",height:6,offsetX:0,offsetY:0},tickAmount:void 0,tickPlacement:"on",min:void 0,max:void 0,range:void 0,floating:!1,decimalsInFloat:void 0,position:"bottom",title:{text:void 0,offsetX:0,offsetY:0,style:{color:void 0,fontSize:"12px",fontWeight:900,fontFamily:void 0,cssClass:""}},crosshairs:{show:!0,width:1,position:"back",opacity:.9,stroke:{color:"#b6b6b6",width:1,dashArray:3},fill:{type:"solid",color:"#B1B9C4",gradient:{colorFrom:"#D8E3F0",colorTo:"#BED1E6",stops:[0,100],opacityFrom:.4,opacityTo:.5}},dropShadow:{enabled:!1,left:0,top:0,blur:1,opacity:.4}},tooltip:{enabled:!0,offsetY:0,formatter:void 0,style:{fontSize:"12px",fontFamily:void 0}}},yaxis:this.yAxis,theme:{mode:"light",palette:"palette1",monochrome:{enabled:!1,color:"#008FFB",shadeTo:"light",shadeIntensity:.65}}}}}]),t}(),P=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.graphics=new m(this.ctx),this.w.globals.isBarHorizontal&&(this.invertAxis=!0),this.helpers=new w(this),this.xAxisAnnotations=new k(this),this.yAxisAnnotations=new A(this),this.pointsAnnotations=new S(this),this.w.globals.isBarHorizontal&&this.w.config.yaxis[0].reversed&&(this.inversedReversedAxis=!0),this.xDivision=this.w.globals.gridWidth/this.w.globals.dataPoints}return r(t,[{key:"drawAxesAnnotations",value:function(){var t=this.w;if(t.globals.axisCharts){for(var e=this.yAxisAnnotations.drawYAxisAnnotations(),i=this.xAxisAnnotations.drawXAxisAnnotations(),a=this.pointsAnnotations.drawPointAnnotations(),s=t.config.chart.animations.enabled,r=[e,i,a],o=[i.node,e.node,a.node],n=0;n<3;n++)t.globals.dom.elGraphical.add(r[n]),!s||t.globals.resized||t.globals.dataChanged||"scatter"!==t.config.chart.type&&"bubble"!==t.config.chart.type&&t.globals.dataPoints>1&&o[n].classList.add("apexcharts-element-hidden"),t.globals.delayedElements.push({el:o[n],index:0});this.helpers.annotationsBackground()}}},{key:"drawImageAnnos",value:function(){var t=this;this.w.config.annotations.images.map((function(e,i){t.addImage(e,i)}))}},{key:"drawTextAnnos",value:function(){var t=this;this.w.config.annotations.texts.map((function(e,i){t.addText(e,i)}))}},{key:"addXaxisAnnotation",value:function(t,e,i){this.xAxisAnnotations.addXaxisAnnotation(t,e,i)}},{key:"addYaxisAnnotation",value:function(t,e,i){this.yAxisAnnotations.addYaxisAnnotation(t,e,i)}},{key:"addPointAnnotation",value:function(t,e,i){this.pointsAnnotations.addPointAnnotation(t,e,i)}},{key:"addText",value:function(t,e){var i=t.x,a=t.y,s=t.text,r=t.textAnchor,o=t.foreColor,n=t.fontSize,l=t.fontFamily,h=t.fontWeight,c=t.cssClass,d=t.backgroundColor,g=t.borderWidth,u=t.strokeDashArray,f=t.borderRadius,p=t.borderColor,x=t.appendTo,b=void 0===x?".apexcharts-annotations":x,v=t.paddingLeft,m=void 0===v?4:v,y=t.paddingRight,w=void 0===y?4:y,k=t.paddingBottom,A=void 0===k?2:k,S=t.paddingTop,C=void 0===S?2:S,L=this.w,P=this.graphics.drawText({x:i,y:a,text:s,textAnchor:r||"start",fontSize:n||"12px",fontWeight:h||"regular",fontFamily:l||L.config.chart.fontFamily,foreColor:o||L.config.chart.foreColor,cssClass:c}),T=L.globals.dom.baseEl.querySelector(b);T&&T.appendChild(P.node);var M=P.bbox();if(s){var I=this.graphics.drawRect(M.x-m,M.y-C,M.width+m+w,M.height+A+C,f,d||"transparent",1,g,p,u);T.insertBefore(I.node,P.node)}}},{key:"addImage",value:function(t,e){var i=this.w,a=t.path,s=t.x,r=void 0===s?0:s,o=t.y,n=void 0===o?0:o,l=t.width,h=void 0===l?20:l,c=t.height,d=void 0===c?20:c,g=t.appendTo,u=void 0===g?".apexcharts-annotations":g,f=i.globals.dom.Paper.image(a);f.size(h,d).move(r,n);var p=i.globals.dom.baseEl.querySelector(u);return p&&p.appendChild(f.node),f}},{key:"addXaxisAnnotationExternal",value:function(t,e,i){return this.addAnnotationExternal({params:t,pushToMemory:e,context:i,type:"xaxis",contextMethod:i.addXaxisAnnotation}),i}},{key:"addYaxisAnnotationExternal",value:function(t,e,i){return this.addAnnotationExternal({params:t,pushToMemory:e,context:i,type:"yaxis",contextMethod:i.addYaxisAnnotation}),i}},{key:"addPointAnnotationExternal",value:function(t,e,i){return void 0===this.invertAxis&&(this.invertAxis=i.w.globals.isBarHorizontal),this.addAnnotationExternal({params:t,pushToMemory:e,context:i,type:"point",contextMethod:i.addPointAnnotation}),i}},{key:"addAnnotationExternal",value:function(t){var e=t.params,i=t.pushToMemory,a=t.context,s=t.type,r=t.contextMethod,o=a,n=o.w,l=n.globals.dom.baseEl.querySelector(".apexcharts-".concat(s,"-annotations")),h=l.childNodes.length+1,c=new L,d=Object.assign({},"xaxis"===s?c.xAxisAnnotation:"yaxis"===s?c.yAxisAnnotation:c.pointAnnotation),g=x.extend(d,e);switch(s){case"xaxis":this.addXaxisAnnotation(g,l,h);break;case"yaxis":this.addYaxisAnnotation(g,l,h);break;case"point":this.addPointAnnotation(g,l,h)}var u=n.globals.dom.baseEl.querySelector(".apexcharts-".concat(s,"-annotations .apexcharts-").concat(s,"-annotation-label[rel='").concat(h,"']")),f=this.helpers.addBackgroundToAnno(u,g);return f&&l.insertBefore(f.node,u),i&&n.globals.memory.methodsToExec.push({context:o,id:g.id?g.id:x.randomId(),method:r,label:"addAnnotation",params:e}),a}},{key:"clearAnnotations",value:function(t){var e=t.w,i=e.globals.dom.baseEl.querySelectorAll(".apexcharts-yaxis-annotations, .apexcharts-xaxis-annotations, .apexcharts-point-annotations");e.globals.memory.methodsToExec.map((function(t,i){"addText"!==t.label&&"addAnnotation"!==t.label||e.globals.memory.methodsToExec.splice(i,1)})),i=x.listToArray(i),Array.prototype.forEach.call(i,(function(t){for(;t.firstChild;)t.removeChild(t.firstChild)}))}},{key:"removeAnnotation",value:function(t,e){var i=t.w,a=i.globals.dom.baseEl.querySelectorAll(".".concat(e));a&&(i.globals.memory.methodsToExec.map((function(t,a){t.id===e&&i.globals.memory.methodsToExec.splice(a,1)})),Array.prototype.forEach.call(a,(function(t){t.parentElement.removeChild(t)})))}}]),t}(),T=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.months31=[1,3,5,7,8,10,12],this.months30=[2,4,6,9,11],this.daysCntOfYear=[0,31,59,90,120,151,181,212,243,273,304,334]}return r(t,[{key:"isValidDate",value:function(t){return!isNaN(this.parseDate(t))}},{key:"getTimeStamp",value:function(t){return Date.parse(t)?this.w.config.xaxis.labels.datetimeUTC?new Date(new Date(t).toISOString().substr(0,25)).getTime():new Date(t).getTime():t}},{key:"getDate",value:function(t){return this.w.config.xaxis.labels.datetimeUTC?new Date(new Date(t).toUTCString()):new Date(t)}},{key:"parseDate",value:function(t){var e=Date.parse(t);if(!isNaN(e))return this.getTimeStamp(t);var i=Date.parse(t.replace(/-/g,"/").replace(/[a-z]+/gi," "));return i=this.getTimeStamp(i)}},{key:"parseDateWithTimezone",value:function(t){return Date.parse(t.replace(/-/g,"/").replace(/[a-z]+/gi," "))}},{key:"formatDate",value:function(t,e){var i=this.w.globals.locale,a=this.w.config.xaxis.labels.datetimeUTC,s=["\0"].concat(u(i.months)),r=["\x01"].concat(u(i.shortMonths)),o=["\x02"].concat(u(i.days)),n=["\x03"].concat(u(i.shortDays));function l(t,e){var i=t+"";for(e=e||2;i.length<e;)i="0"+i;return i}var h=a?t.getUTCFullYear():t.getFullYear();e=(e=(e=e.replace(/(^|[^\\])yyyy+/g,"$1"+h)).replace(/(^|[^\\])yy/g,"$1"+h.toString().substr(2,2))).replace(/(^|[^\\])y/g,"$1"+h);var c=(a?t.getUTCMonth():t.getMonth())+1;e=(e=(e=(e=e.replace(/(^|[^\\])MMMM+/g,"$1"+s[0])).replace(/(^|[^\\])MMM/g,"$1"+r[0])).replace(/(^|[^\\])MM/g,"$1"+l(c))).replace(/(^|[^\\])M/g,"$1"+c);var d=a?t.getUTCDate():t.getDate();e=(e=(e=(e=e.replace(/(^|[^\\])dddd+/g,"$1"+o[0])).replace(/(^|[^\\])ddd/g,"$1"+n[0])).replace(/(^|[^\\])dd/g,"$1"+l(d))).replace(/(^|[^\\])d/g,"$1"+d);var g=a?t.getUTCHours():t.getHours(),f=g>12?g-12:0===g?12:g;e=(e=(e=(e=e.replace(/(^|[^\\])HH+/g,"$1"+l(g))).replace(/(^|[^\\])H/g,"$1"+g)).replace(/(^|[^\\])hh+/g,"$1"+l(f))).replace(/(^|[^\\])h/g,"$1"+f);var p=a?t.getUTCMinutes():t.getMinutes();e=(e=e.replace(/(^|[^\\])mm+/g,"$1"+l(p))).replace(/(^|[^\\])m/g,"$1"+p);var x=a?t.getUTCSeconds():t.getSeconds();e=(e=e.replace(/(^|[^\\])ss+/g,"$1"+l(x))).replace(/(^|[^\\])s/g,"$1"+x);var b=a?t.getUTCMilliseconds():t.getMilliseconds();e=e.replace(/(^|[^\\])fff+/g,"$1"+l(b,3)),b=Math.round(b/10),e=e.replace(/(^|[^\\])ff/g,"$1"+l(b)),b=Math.round(b/10);var v=g<12?"AM":"PM";e=(e=(e=e.replace(/(^|[^\\])f/g,"$1"+b)).replace(/(^|[^\\])TT+/g,"$1"+v)).replace(/(^|[^\\])T/g,"$1"+v.charAt(0));var m=v.toLowerCase();e=(e=e.replace(/(^|[^\\])tt+/g,"$1"+m)).replace(/(^|[^\\])t/g,"$1"+m.charAt(0));var y=-t.getTimezoneOffset(),w=a||!y?"Z":y>0?"+":"-";if(!a){var k=(y=Math.abs(y))%60;w+=l(Math.floor(y/60))+":"+l(k)}e=e.replace(/(^|[^\\])K/g,"$1"+w);var A=(a?t.getUTCDay():t.getDay())+1;return e=(e=(e=(e=(e=e.replace(new RegExp(o[0],"g"),o[A])).replace(new RegExp(n[0],"g"),n[A])).replace(new RegExp(s[0],"g"),s[c])).replace(new RegExp(r[0],"g"),r[c])).replace(/\\(.)/g,"$1")}},{key:"getTimeUnitsfromTimestamp",value:function(t,e,i){var a=this.w;void 0!==a.config.xaxis.min&&(t=a.config.xaxis.min),void 0!==a.config.xaxis.max&&(e=a.config.xaxis.max);var s=this.getDate(t),r=this.getDate(e),o=this.formatDate(s,"yyyy MM dd HH mm ss fff").split(" "),n=this.formatDate(r,"yyyy MM dd HH mm ss fff").split(" ");return{minMillisecond:parseInt(o[6],10),maxMillisecond:parseInt(n[6],10),minSecond:parseInt(o[5],10),maxSecond:parseInt(n[5],10),minMinute:parseInt(o[4],10),maxMinute:parseInt(n[4],10),minHour:parseInt(o[3],10),maxHour:parseInt(n[3],10),minDate:parseInt(o[2],10),maxDate:parseInt(n[2],10),minMonth:parseInt(o[1],10)-1,maxMonth:parseInt(n[1],10)-1,minYear:parseInt(o[0],10),maxYear:parseInt(n[0],10)}}},{key:"isLeapYear",value:function(t){return t%4==0&&t%100!=0||t%400==0}},{key:"calculcateLastDaysOfMonth",value:function(t,e,i){return this.determineDaysOfMonths(t,e)-i}},{key:"determineDaysOfYear",value:function(t){var e=365;return this.isLeapYear(t)&&(e=366),e}},{key:"determineRemainingDaysOfYear",value:function(t,e,i){var a=this.daysCntOfYear[e]+i;return e>1&&this.isLeapYear()&&a++,a}},{key:"determineDaysOfMonths",value:function(t,e){var i=30;switch(t=x.monthMod(t),!0){case this.months30.indexOf(t)>-1:2===t&&(i=this.isLeapYear(e)?29:28);break;case this.months31.indexOf(t)>-1:default:i=31}return i}}]),t}(),M=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.tooltipKeyFormat="dd MMM"}return r(t,[{key:"xLabelFormat",value:function(t,e,i,a){var s=this.w;if("datetime"===s.config.xaxis.type&&void 0===s.config.xaxis.labels.formatter&&void 0===s.config.tooltip.x.formatter){var r=new T(this.ctx);return r.formatDate(r.getDate(e),s.config.tooltip.x.format)}return t(e,i,a)}},{key:"defaultGeneralFormatter",value:function(t){return Array.isArray(t)?t.map((function(t){return t})):t}},{key:"defaultYFormatter",value:function(t,e,i){var a=this.w;return x.isNumber(t)&&(t=0!==a.globals.yValueDecimal?t.toFixed(void 0!==e.decimalsInFloat?e.decimalsInFloat:a.globals.yValueDecimal):a.globals.maxYArr[i]-a.globals.minYArr[i]<5?t.toFixed(1):t.toFixed(0)),t}},{key:"setLabelFormatters",value:function(){var t=this,e=this.w;return e.globals.xaxisTooltipFormatter=function(e){return t.defaultGeneralFormatter(e)},e.globals.ttKeyFormatter=function(e){return t.defaultGeneralFormatter(e)},e.globals.ttZFormatter=function(t){return t},e.globals.legendFormatter=function(e){return t.defaultGeneralFormatter(e)},void 0!==e.config.xaxis.labels.formatter?e.globals.xLabelFormatter=e.config.xaxis.labels.formatter:e.globals.xLabelFormatter=function(t){if(x.isNumber(t)){if(!e.config.xaxis.convertedCatToNumeric&&"numeric"===e.config.xaxis.type){if(x.isNumber(e.config.xaxis.decimalsInFloat))return t.toFixed(e.config.xaxis.decimalsInFloat);var i=e.globals.maxX-e.globals.minX;return i>0&&i<100?t.toFixed(1):t.toFixed(0)}if(e.globals.isBarHorizontal)if(e.globals.maxY-e.globals.minYArr<4)return t.toFixed(1);return t.toFixed(0)}return t},"function"==typeof e.config.tooltip.x.formatter?e.globals.ttKeyFormatter=e.config.tooltip.x.formatter:e.globals.ttKeyFormatter=e.globals.xLabelFormatter,"function"==typeof e.config.xaxis.tooltip.formatter&&(e.globals.xaxisTooltipFormatter=e.config.xaxis.tooltip.formatter),(Array.isArray(e.config.tooltip.y)||void 0!==e.config.tooltip.y.formatter)&&(e.globals.ttVal=e.config.tooltip.y),void 0!==e.config.tooltip.z.formatter&&(e.globals.ttZFormatter=e.config.tooltip.z.formatter),void 0!==e.config.legend.formatter&&(e.globals.legendFormatter=e.config.legend.formatter),e.config.yaxis.forEach((function(i,a){void 0!==i.labels.formatter?e.globals.yLabelFormatters[a]=i.labels.formatter:e.globals.yLabelFormatters[a]=function(s){return e.globals.xyCharts?Array.isArray(s)?s.map((function(e){return t.defaultYFormatter(e,i,a)})):t.defaultYFormatter(s,i,a):s}})),e.globals}},{key:"heatmapLabelFormatters",value:function(){var t=this.w;if("heatmap"===t.config.chart.type){t.globals.yAxisScale[0].result=t.globals.seriesNames.slice();var e=t.globals.seriesNames.reduce((function(t,e){return t.length>e.length?t:e}),0);t.globals.yAxisScale[0].niceMax=e,t.globals.yAxisScale[0].niceMin=e}}}]),t}(),I=function(t){var e,i=t.isTimeline,a=t.ctx,s=t.seriesIndex,r=t.dataPointIndex,o=t.y1,n=t.y2,l=t.w,h=l.globals.seriesRangeStart[s][r],c=l.globals.seriesRangeEnd[s][r],d=l.globals.labels[r],g=l.config.series[s].name?l.config.series[s].name:"",u=l.globals.ttKeyFormatter,f=l.config.tooltip.y.title.formatter,p={w:l,seriesIndex:s,dataPointIndex:r,start:h,end:c};("function"==typeof f&&(g=f(g,p)),null!==(e=l.config.series[s].data[r])&&void 0!==e&&e.x&&(d=l.config.series[s].data[r].x),i)||"datetime"===l.config.xaxis.type&&(d=new M(a).xLabelFormat(l.globals.ttKeyFormatter,d,d,{i:void 0,dateFormatter:new T(a).formatDate,w:l}));"function"==typeof u&&(d=u(d,p)),Number.isFinite(o)&&Number.isFinite(n)&&(h=o,c=n);var x="",b="",v=l.globals.colors[s];if(void 0===l.config.tooltip.x.formatter)if("datetime"===l.config.xaxis.type){var m=new T(a);x=m.formatDate(m.getDate(h),l.config.tooltip.x.format),b=m.formatDate(m.getDate(c),l.config.tooltip.x.format)}else x=h,b=c;else x=l.config.tooltip.x.formatter(h),b=l.config.tooltip.x.formatter(c);return{start:h,end:c,startVal:x,endVal:b,ylabel:d,color:v,seriesName:g}},z=function(t){var e=t.color,i=t.seriesName,a=t.ylabel,s=t.start,r=t.end,o=t.seriesIndex,n=t.dataPointIndex,l=t.ctx.tooltip.tooltipLabels.getFormatters(o);s=l.yLbFormatter(s),r=l.yLbFormatter(r);var h=l.yLbFormatter(t.w.globals.series[o][n]),c='<span class="value start-value">\n  '.concat(s,'\n  </span> <span class="separator">-</span> <span class="value end-value">\n  ').concat(r,"\n  </span>");return'<div class="apexcharts-tooltip-rangebar"><div> <span class="series-name" style="color: '+e+'">'+(i||"")+'</span></div><div> <span class="category">'+a+": </span> "+(t.w.globals.comboCharts?"rangeArea"===t.w.config.series[o].type||"rangeBar"===t.w.config.series[o].type?c:"<span>".concat(h,"</span>"):c)+" </div></div>"},X=function(){function t(e){a(this,t),this.opts=e}return r(t,[{key:"line",value:function(){return{chart:{animations:{easing:"swing"}},dataLabels:{enabled:!1},stroke:{width:5,curve:"straight"},markers:{size:0,hover:{sizeOffset:6}},xaxis:{crosshairs:{width:1}}}}},{key:"sparkline",value:function(t){this.opts.yaxis[0].show=!1,this.opts.yaxis[0].title.text="",this.opts.yaxis[0].axisBorder.show=!1,this.opts.yaxis[0].axisTicks.show=!1,this.opts.yaxis[0].floating=!0;return x.extend(t,{grid:{show:!1,padding:{left:0,right:0,top:0,bottom:0}},legend:{show:!1},xaxis:{labels:{show:!1},tooltip:{enabled:!1},axisBorder:{show:!1},axisTicks:{show:!1}},chart:{toolbar:{show:!1},zoom:{enabled:!1}},dataLabels:{enabled:!1}})}},{key:"bar",value:function(){return{chart:{stacked:!1,animations:{easing:"swing"}},plotOptions:{bar:{dataLabels:{position:"center"}}},dataLabels:{style:{colors:["#fff"]},background:{enabled:!1}},stroke:{width:0,lineCap:"round"},fill:{opacity:.85},legend:{markers:{shape:"square",radius:2,size:8}},tooltip:{shared:!1,intersect:!0},xaxis:{tooltip:{enabled:!1},tickPlacement:"between",crosshairs:{width:"barWidth",position:"back",fill:{type:"gradient"},dropShadow:{enabled:!1},stroke:{width:0}}}}}},{key:"candlestick",value:function(){var t=this;return{stroke:{width:1,colors:["#333"]},fill:{opacity:1},dataLabels:{enabled:!1},tooltip:{shared:!0,custom:function(e){var i=e.seriesIndex,a=e.dataPointIndex,s=e.w;return t._getBoxTooltip(s,i,a,["Open","High","","Low","Close"],"candlestick")}},states:{active:{filter:{type:"none"}}},xaxis:{crosshairs:{width:1}}}}},{key:"boxPlot",value:function(){var t=this;return{chart:{animations:{dynamicAnimation:{enabled:!1}}},stroke:{width:1,colors:["#24292e"]},dataLabels:{enabled:!1},tooltip:{shared:!0,custom:function(e){var i=e.seriesIndex,a=e.dataPointIndex,s=e.w;return t._getBoxTooltip(s,i,a,["Minimum","Q1","Median","Q3","Maximum"],"boxPlot")}},markers:{size:5,strokeWidth:1,strokeColors:"#111"},xaxis:{crosshairs:{width:1}}}}},{key:"rangeBar",value:function(){return{stroke:{width:0,lineCap:"square"},plotOptions:{bar:{borderRadius:0,dataLabels:{position:"center"}}},dataLabels:{enabled:!1,formatter:function(t,e){e.ctx;var i=e.seriesIndex,a=e.dataPointIndex,s=e.w,r=function(){var t=s.globals.seriesRangeStart[i][a];return s.globals.seriesRangeEnd[i][a]-t};return s.globals.comboCharts?"rangeBar"===s.config.series[i].type||"rangeArea"===s.config.series[i].type?r():t:r()},background:{enabled:!1},style:{colors:["#fff"]}},tooltip:{shared:!1,followCursor:!0,custom:function(t){return t.w.config.plotOptions&&t.w.config.plotOptions.bar&&t.w.config.plotOptions.bar.horizontal?function(t){var i=I(e(e({},t),{},{isTimeline:!0})),a=i.color,s=i.seriesName,r=i.ylabel,o=i.startVal,n=i.endVal;return z(e(e({},t),{},{color:a,seriesName:s,ylabel:r,start:o,end:n}))}(t):function(t){var i=I(t),a=i.color,s=i.seriesName,r=i.ylabel,o=i.start,n=i.end;return z(e(e({},t),{},{color:a,seriesName:s,ylabel:r,start:o,end:n}))}(t)}},xaxis:{tickPlacement:"between",tooltip:{enabled:!1},crosshairs:{stroke:{width:0}}}}}},{key:"area",value:function(){return{stroke:{width:4,fill:{type:"solid",gradient:{inverseColors:!1,shade:"light",type:"vertical",opacityFrom:.65,opacityTo:.5,stops:[0,100,100]}}},fill:{type:"gradient",gradient:{inverseColors:!1,shade:"light",type:"vertical",opacityFrom:.65,opacityTo:.5,stops:[0,100,100]}},markers:{size:0,hover:{sizeOffset:6}},tooltip:{followCursor:!1}}}},{key:"rangeArea",value:function(){return{stroke:{curve:"straight",width:0},fill:{type:"solid",opacity:.6},markers:{size:0},states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}},tooltip:{intersect:!1,shared:!0,followCursor:!0,custom:function(t){return function(t){var i=I(t),a=i.color,s=i.seriesName,r=i.ylabel,o=i.start,n=i.end;return z(e(e({},t),{},{color:a,seriesName:s,ylabel:r,start:o,end:n}))}(t)}}}}},{key:"brush",value:function(t){return x.extend(t,{chart:{toolbar:{autoSelected:"selection",show:!1},zoom:{enabled:!1}},dataLabels:{enabled:!1},stroke:{width:1},tooltip:{enabled:!1},xaxis:{tooltip:{enabled:!1}}})}},{key:"stacked100",value:function(t){t.dataLabels=t.dataLabels||{},t.dataLabels.formatter=t.dataLabels.formatter||void 0;var e=t.dataLabels.formatter;return t.yaxis.forEach((function(e,i){t.yaxis[i].min=0,t.yaxis[i].max=100})),"bar"===t.chart.type&&(t.dataLabels.formatter=e||function(t){return"number"==typeof t&&t?t.toFixed(0)+"%":t}),t}},{key:"stackedBars",value:function(){var t=this.bar();return e(e({},t),{},{plotOptions:e(e({},t.plotOptions),{},{bar:e(e({},t.plotOptions.bar),{},{borderRadiusApplication:"end",borderRadiusWhenStacked:"last"})})})}},{key:"convertCatToNumeric",value:function(t){return t.xaxis.convertedCatToNumeric=!0,t}},{key:"convertCatToNumericXaxis",value:function(t,e,i){t.xaxis.type="numeric",t.xaxis.labels=t.xaxis.labels||{},t.xaxis.labels.formatter=t.xaxis.labels.formatter||function(t){return x.isNumber(t)?Math.floor(t):t};var a=t.xaxis.labels.formatter,s=t.xaxis.categories&&t.xaxis.categories.length?t.xaxis.categories:t.labels;return i&&i.length&&(s=i.map((function(t){return Array.isArray(t)?t:String(t)}))),s&&s.length&&(t.xaxis.labels.formatter=function(t){return x.isNumber(t)?a(s[Math.floor(t)-1]):a(t)}),t.xaxis.categories=[],t.labels=[],t.xaxis.tickAmount=t.xaxis.tickAmount||"dataPoints",t}},{key:"bubble",value:function(){return{dataLabels:{style:{colors:["#fff"]}},tooltip:{shared:!1,intersect:!0},xaxis:{crosshairs:{width:0}},fill:{type:"solid",gradient:{shade:"light",inverse:!0,shadeIntensity:.55,opacityFrom:.4,opacityTo:.8}}}}},{key:"scatter",value:function(){return{dataLabels:{enabled:!1},tooltip:{shared:!1,intersect:!0},markers:{size:6,strokeWidth:1,hover:{sizeOffset:2}}}}},{key:"heatmap",value:function(){return{chart:{stacked:!1},fill:{opacity:1},dataLabels:{style:{colors:["#fff"]}},stroke:{colors:["#fff"]},tooltip:{followCursor:!0,marker:{show:!1},x:{show:!1}},legend:{position:"top",markers:{shape:"square",size:10,offsetY:2}},grid:{padding:{right:20}}}}},{key:"treemap",value:function(){return{chart:{zoom:{enabled:!1}},dataLabels:{style:{fontSize:14,fontWeight:600,colors:["#fff"]}},stroke:{show:!0,width:2,colors:["#fff"]},legend:{show:!1},fill:{gradient:{stops:[0,100]}},tooltip:{followCursor:!0,x:{show:!1}},grid:{padding:{left:0,right:0}},xaxis:{crosshairs:{show:!1},tooltip:{enabled:!1}}}}},{key:"pie",value:function(){return{chart:{toolbar:{show:!1}},plotOptions:{pie:{donut:{labels:{show:!1}}}},dataLabels:{formatter:function(t){return t.toFixed(1)+"%"},style:{colors:["#fff"]},background:{enabled:!1},dropShadow:{enabled:!0}},stroke:{colors:["#fff"]},fill:{opacity:1,gradient:{shade:"light",stops:[0,100]}},tooltip:{theme:"dark",fillSeriesColor:!0},legend:{position:"right"}}}},{key:"donut",value:function(){return{chart:{toolbar:{show:!1}},dataLabels:{formatter:function(t){return t.toFixed(1)+"%"},style:{colors:["#fff"]},background:{enabled:!1},dropShadow:{enabled:!0}},stroke:{colors:["#fff"]},fill:{opacity:1,gradient:{shade:"light",shadeIntensity:.35,stops:[80,100],opacityFrom:1,opacityTo:1}},tooltip:{theme:"dark",fillSeriesColor:!0},legend:{position:"right"}}}},{key:"polarArea",value:function(){return this.opts.yaxis[0].tickAmount=this.opts.yaxis[0].tickAmount?this.opts.yaxis[0].tickAmount:6,{chart:{toolbar:{show:!1}},dataLabels:{formatter:function(t){return t.toFixed(1)+"%"},enabled:!1},stroke:{show:!0,width:2},fill:{opacity:.7},tooltip:{theme:"dark",fillSeriesColor:!0},legend:{position:"right"}}}},{key:"radar",value:function(){return this.opts.yaxis[0].labels.offsetY=this.opts.yaxis[0].labels.offsetY?this.opts.yaxis[0].labels.offsetY:6,{dataLabels:{enabled:!1,style:{fontSize:"11px"}},stroke:{width:2},markers:{size:3,strokeWidth:1,strokeOpacity:1},fill:{opacity:.2},tooltip:{shared:!1,intersect:!0,followCursor:!0},grid:{show:!1},xaxis:{labels:{formatter:function(t){return t},style:{colors:["#a8a8a8"],fontSize:"11px"}},tooltip:{enabled:!1},crosshairs:{show:!1}}}}},{key:"radialBar",value:function(){return{chart:{animations:{dynamicAnimation:{enabled:!0,speed:800}},toolbar:{show:!1}},fill:{gradient:{shade:"dark",shadeIntensity:.4,inverseColors:!1,type:"diagonal2",opacityFrom:1,opacityTo:1,stops:[70,98,100]}},legend:{show:!1,position:"right"},tooltip:{enabled:!1,fillSeriesColor:!0}}}},{key:"_getBoxTooltip",value:function(t,e,i,a,s){var r=t.globals.seriesCandleO[e][i],o=t.globals.seriesCandleH[e][i],n=t.globals.seriesCandleM[e][i],l=t.globals.seriesCandleL[e][i],h=t.globals.seriesCandleC[e][i];return t.config.series[e].type&&t.config.series[e].type!==s?'<div class="apexcharts-custom-tooltip">\n          '.concat(t.config.series[e].name?t.config.series[e].name:"series-"+(e+1),": <strong>").concat(t.globals.series[e][i],"</strong>\n        </div>"):'<div class="apexcharts-tooltip-box apexcharts-tooltip-'.concat(t.config.chart.type,'">')+"<div>".concat(a[0],': <span class="value">')+r+"</span></div>"+"<div>".concat(a[1],': <span class="value">')+o+"</span></div>"+(n?"<div>".concat(a[2],': <span class="value">')+n+"</span></div>":"")+"<div>".concat(a[3],': <span class="value">')+l+"</span></div>"+"<div>".concat(a[4],': <span class="value">')+h+"</span></div></div>"}}]),t}(),E=function(){function t(e){a(this,t),this.opts=e}return r(t,[{key:"init",value:function(t){var e=t.responsiveOverride,a=this.opts,s=new L,r=new X(a);this.chartType=a.chart.type,a=this.extendYAxis(a),a=this.extendAnnotations(a);var o=s.init(),n={};if(a&&"object"===i(a)){var l={};l=-1!==["line","area","bar","candlestick","boxPlot","rangeBar","rangeArea","bubble","scatter","heatmap","treemap","pie","polarArea","donut","radar","radialBar"].indexOf(a.chart.type)?r[a.chart.type]():r.line(),a.chart.stacked&&"bar"===a.chart.type&&(l=r.stackedBars()),a.chart.brush&&a.chart.brush.enabled&&(l=r.brush(l)),a.chart.stacked&&"100%"===a.chart.stackType&&(a=r.stacked100(a)),this.checkForDarkTheme(window.Apex),this.checkForDarkTheme(a),a.xaxis=a.xaxis||window.Apex.xaxis||{},e||(a.xaxis.convertedCatToNumeric=!1),((a=this.checkForCatToNumericXAxis(this.chartType,l,a)).chart.sparkline&&a.chart.sparkline.enabled||window.Apex.chart&&window.Apex.chart.sparkline&&window.Apex.chart.sparkline.enabled)&&(l=r.sparkline(l)),n=x.extend(o,l)}var h=x.extend(n,window.Apex);return o=x.extend(h,a),o=this.handleUserInputErrors(o)}},{key:"checkForCatToNumericXAxis",value:function(t,e,i){var a=new X(i),s=("bar"===t||"boxPlot"===t)&&i.plotOptions&&i.plotOptions.bar&&i.plotOptions.bar.horizontal,r="pie"===t||"polarArea"===t||"donut"===t||"radar"===t||"radialBar"===t||"heatmap"===t,o="datetime"!==i.xaxis.type&&"numeric"!==i.xaxis.type,n=i.xaxis.tickPlacement?i.xaxis.tickPlacement:e.xaxis&&e.xaxis.tickPlacement;return s||r||!o||"between"===n||(i=a.convertCatToNumeric(i)),i}},{key:"extendYAxis",value:function(t,e){var i=new L;(void 0===t.yaxis||!t.yaxis||Array.isArray(t.yaxis)&&0===t.yaxis.length)&&(t.yaxis={}),t.yaxis.constructor!==Array&&window.Apex.yaxis&&window.Apex.yaxis.constructor!==Array&&(t.yaxis=x.extend(t.yaxis,window.Apex.yaxis)),t.yaxis.constructor!==Array?t.yaxis=[x.extend(i.yAxis,t.yaxis)]:t.yaxis=x.extendArray(t.yaxis,i.yAxis);var a=!1;t.yaxis.forEach((function(t){t.logarithmic&&(a=!0)}));var s=t.series;return e&&!s&&(s=e.config.series),a&&s.length!==t.yaxis.length&&s.length&&(t.yaxis=s.map((function(e,a){if(e.name||(s[a].name="series-".concat(a+1)),t.yaxis[a])return t.yaxis[a].seriesName=s[a].name,t.yaxis[a];var r=x.extend(i.yAxis,t.yaxis[0]);return r.show=!1,r}))),a&&s.length>1&&s.length!==t.yaxis.length&&console.warn("A multi-series logarithmic chart should have equal number of series and y-axes. Please make sure to equalize both."),t}},{key:"extendAnnotations",value:function(t){return void 0===t.annotations&&(t.annotations={},t.annotations.yaxis=[],t.annotations.xaxis=[],t.annotations.points=[]),t=this.extendYAxisAnnotations(t),t=this.extendXAxisAnnotations(t),t=this.extendPointAnnotations(t)}},{key:"extendYAxisAnnotations",value:function(t){var e=new L;return t.annotations.yaxis=x.extendArray(void 0!==t.annotations.yaxis?t.annotations.yaxis:[],e.yAxisAnnotation),t}},{key:"extendXAxisAnnotations",value:function(t){var e=new L;return t.annotations.xaxis=x.extendArray(void 0!==t.annotations.xaxis?t.annotations.xaxis:[],e.xAxisAnnotation),t}},{key:"extendPointAnnotations",value:function(t){var e=new L;return t.annotations.points=x.extendArray(void 0!==t.annotations.points?t.annotations.points:[],e.pointAnnotation),t}},{key:"checkForDarkTheme",value:function(t){t.theme&&"dark"===t.theme.mode&&(t.tooltip||(t.tooltip={}),"light"!==t.tooltip.theme&&(t.tooltip.theme="dark"),t.chart.foreColor||(t.chart.foreColor="#f6f7f8"),t.chart.background||(t.chart.background="#424242"),t.theme.palette||(t.theme.palette="palette4"))}},{key:"handleUserInputErrors",value:function(t){var e=t;if(e.tooltip.shared&&e.tooltip.intersect)throw new Error("tooltip.shared cannot be enabled when tooltip.intersect is true. Turn off any other option by setting it to false.");if("bar"===e.chart.type&&e.plotOptions.bar.horizontal){if(e.yaxis.length>1)throw new Error("Multiple Y Axis for bars are not supported. Switch to column chart by setting plotOptions.bar.horizontal=false");e.yaxis[0].reversed&&(e.yaxis[0].opposite=!0),e.xaxis.tooltip.enabled=!1,e.yaxis[0].tooltip.enabled=!1,e.chart.zoom.enabled=!1}return"bar"!==e.chart.type&&"rangeBar"!==e.chart.type||e.tooltip.shared&&"barWidth"===e.xaxis.crosshairs.width&&e.series.length>1&&(e.xaxis.crosshairs.width="tickWidth"),"candlestick"!==e.chart.type&&"boxPlot"!==e.chart.type||e.yaxis[0].reversed&&(console.warn("Reversed y-axis in ".concat(e.chart.type," chart is not supported.")),e.yaxis[0].reversed=!1),e}}]),t}(),Y=function(){function t(){a(this,t)}return r(t,[{key:"initGlobalVars",value:function(t){t.series=[],t.seriesCandleO=[],t.seriesCandleH=[],t.seriesCandleM=[],t.seriesCandleL=[],t.seriesCandleC=[],t.seriesRangeStart=[],t.seriesRangeEnd=[],t.seriesRange=[],t.seriesPercent=[],t.seriesGoals=[],t.seriesX=[],t.seriesZ=[],t.seriesNames=[],t.seriesTotals=[],t.seriesLog=[],t.seriesColors=[],t.stackedSeriesTotals=[],t.seriesXvalues=[],t.seriesYvalues=[],t.labels=[],t.hasGroups=!1,t.groups=[],t.categoryLabels=[],t.timescaleLabels=[],t.noLabelsProvided=!1,t.resizeTimer=null,t.selectionResizeTimer=null,t.delayedElements=[],t.pointsArray=[],t.dataLabelsRects=[],t.isXNumeric=!1,t.skipLastTimelinelabel=!1,t.skipFirstTimelinelabel=!1,t.isDataXYZ=!1,t.isMultiLineX=!1,t.isMultipleYAxis=!1,t.maxY=-Number.MAX_VALUE,t.minY=Number.MIN_VALUE,t.minYArr=[],t.maxYArr=[],t.maxX=-Number.MAX_VALUE,t.minX=Number.MAX_VALUE,t.initialMaxX=-Number.MAX_VALUE,t.initialMinX=Number.MAX_VALUE,t.maxDate=0,t.minDate=Number.MAX_VALUE,t.minZ=Number.MAX_VALUE,t.maxZ=-Number.MAX_VALUE,t.minXDiff=Number.MAX_VALUE,t.yAxisScale=[],t.xAxisScale=null,t.xAxisTicksPositions=[],t.yLabelsCoords=[],t.yTitleCoords=[],t.barPadForNumericAxis=0,t.padHorizontal=0,t.xRange=0,t.yRange=[],t.zRange=0,t.dataPoints=0,t.xTickAmount=0}},{key:"globalVars",value:function(t){return{chartID:null,cuid:null,events:{beforeMount:[],mounted:[],updated:[],clicked:[],selection:[],dataPointSelection:[],zoomed:[],scrolled:[]},colors:[],clientX:null,clientY:null,fill:{colors:[]},stroke:{colors:[]},dataLabels:{style:{colors:[]}},radarPolygons:{fill:{colors:[]}},markers:{colors:[],size:t.markers.size,largestSize:0},animationEnded:!1,isTouchDevice:"ontouchstart"in window||navigator.msMaxTouchPoints,isDirty:!1,isExecCalled:!1,initialConfig:null,initialSeries:[],lastXAxis:[],lastYAxis:[],columnSeries:null,labels:[],timescaleLabels:[],noLabelsProvided:!1,allSeriesCollapsed:!1,collapsedSeries:[],collapsedSeriesIndices:[],ancillaryCollapsedSeries:[],ancillaryCollapsedSeriesIndices:[],risingSeries:[],dataFormatXNumeric:!1,capturedSeriesIndex:-1,capturedDataPointIndex:-1,selectedDataPoints:[],goldenPadding:35,invalidLogScale:!1,ignoreYAxisIndexes:[],yAxisSameScaleIndices:[],maxValsInArrayIndex:0,radialSize:0,selection:void 0,zoomEnabled:"zoom"===t.chart.toolbar.autoSelected&&t.chart.toolbar.tools.zoom&&t.chart.zoom.enabled,panEnabled:"pan"===t.chart.toolbar.autoSelected&&t.chart.toolbar.tools.pan,selectionEnabled:"selection"===t.chart.toolbar.autoSelected&&t.chart.toolbar.tools.selection,yaxis:null,mousedown:!1,lastClientPosition:{},visibleXRange:void 0,yValueDecimal:0,total:0,SVGNS:"http://www.w3.org/2000/svg",svgWidth:0,svgHeight:0,noData:!1,locale:{},dom:{},memory:{methodsToExec:[]},shouldAnimate:!0,skipLastTimelinelabel:!1,skipFirstTimelinelabel:!1,delayedElements:[],axisCharts:!0,isDataXYZ:!1,resized:!1,resizeTimer:null,comboCharts:!1,dataChanged:!1,previousPaths:[],allSeriesHasEqualX:!0,pointsArray:[],dataLabelsRects:[],lastDrawnDataLabelsIndexes:[],hasNullValues:!1,easing:null,zoomed:!1,gridWidth:0,gridHeight:0,rotateXLabels:!1,defaultLabels:!1,xLabelFormatter:void 0,yLabelFormatters:[],xaxisTooltipFormatter:void 0,ttKeyFormatter:void 0,ttVal:void 0,ttZFormatter:void 0,LINE_HEIGHT_RATIO:1.618,xAxisLabelsHeight:0,xAxisGroupLabelsHeight:0,xAxisLabelsWidth:0,yAxisLabelsWidth:0,scaleX:1,scaleY:1,translateX:0,translateY:0,translateYAxisX:[],yAxisWidths:[],translateXAxisY:0,translateXAxisX:0,tooltip:null}}},{key:"init",value:function(t){var e=this.globalVars(t);return this.initGlobalVars(e),e.initialConfig=x.extend({},t),e.initialSeries=x.clone(t.series),e.lastXAxis=x.clone(e.initialConfig.xaxis),e.lastYAxis=x.clone(e.initialConfig.yaxis),e}}]),t}(),F=function(){function t(e){a(this,t),this.opts=e}return r(t,[{key:"init",value:function(){var t=new E(this.opts).init({responsiveOverride:!1});return{config:t,globals:(new Y).init(t)}}}]),t}(),R=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.opts=null,this.seriesIndex=0}return r(t,[{key:"clippedImgArea",value:function(t){var e=this.w,i=e.config,a=parseInt(e.globals.gridWidth,10),s=parseInt(e.globals.gridHeight,10),r=a>s?a:s,o=t.image,n=0,l=0;void 0===t.width&&void 0===t.height?void 0!==i.fill.image.width&&void 0!==i.fill.image.height?(n=i.fill.image.width+1,l=i.fill.image.height):(n=r+1,l=r):(n=t.width,l=t.height);var h=document.createElementNS(e.globals.SVGNS,"pattern");m.setAttrs(h,{id:t.patternID,patternUnits:t.patternUnits?t.patternUnits:"userSpaceOnUse",width:n+"px",height:l+"px"});var c=document.createElementNS(e.globals.SVGNS,"image");h.appendChild(c),c.setAttributeNS(window.SVG.xlink,"href",o),m.setAttrs(c,{x:0,y:0,preserveAspectRatio:"none",width:n+"px",height:l+"px"}),c.style.opacity=t.opacity,e.globals.dom.elDefs.node.appendChild(h)}},{key:"getSeriesIndex",value:function(t){var e=this.w;return("bar"===e.config.chart.type||"rangeBar"===e.config.chart.type)&&e.config.plotOptions.bar.distributed||"heatmap"===e.config.chart.type||"treemap"===e.config.chart.type?this.seriesIndex=t.seriesNumber:this.seriesIndex=t.seriesNumber%e.globals.series.length,this.seriesIndex}},{key:"fillPath",value:function(t){var e=this.w;this.opts=t;var i,a,s,r=this.w.config;this.seriesIndex=this.getSeriesIndex(t);var o=this.getFillColors()[this.seriesIndex];void 0!==e.globals.seriesColors[this.seriesIndex]&&(o=e.globals.seriesColors[this.seriesIndex]),"function"==typeof o&&(o=o({seriesIndex:this.seriesIndex,dataPointIndex:t.dataPointIndex,value:t.value,w:e}));var n=t.fillType?t.fillType:this.getFillType(this.seriesIndex),l=Array.isArray(r.fill.opacity)?r.fill.opacity[this.seriesIndex]:r.fill.opacity;t.color&&(o=t.color);var h=o;if(-1===o.indexOf("rgb")?o.length<9&&(h=x.hexToRgba(o,l)):o.indexOf("rgba")>-1&&(l=x.getOpacityFromRGBA(o)),t.opacity&&(l=t.opacity),"pattern"===n&&(a=this.handlePatternFill({fillConfig:t.fillConfig,patternFill:a,fillColor:o,fillOpacity:l,defaultColor:h})),"gradient"===n&&(s=this.handleGradientFill({fillConfig:t.fillConfig,fillColor:o,fillOpacity:l,i:this.seriesIndex})),"image"===n){var c=r.fill.image.src,d=t.patternID?t.patternID:"";this.clippedImgArea({opacity:l,image:Array.isArray(c)?t.seriesNumber<c.length?c[t.seriesNumber]:c[0]:c,width:t.width?t.width:void 0,height:t.height?t.height:void 0,patternUnits:t.patternUnits,patternID:"pattern".concat(e.globals.cuid).concat(t.seriesNumber+1).concat(d)}),i="url(#pattern".concat(e.globals.cuid).concat(t.seriesNumber+1).concat(d,")")}else i="gradient"===n?s:"pattern"===n?a:h;return t.solid&&(i=h),i}},{key:"getFillType",value:function(t){var e=this.w;return Array.isArray(e.config.fill.type)?e.config.fill.type[t]:e.config.fill.type}},{key:"getFillColors",value:function(){var t=this.w,e=t.config,i=this.opts,a=[];return t.globals.comboCharts?"line"===t.config.series[this.seriesIndex].type?Array.isArray(t.globals.stroke.colors)?a=t.globals.stroke.colors:a.push(t.globals.stroke.colors):Array.isArray(t.globals.fill.colors)?a=t.globals.fill.colors:a.push(t.globals.fill.colors):"line"===e.chart.type?Array.isArray(t.globals.stroke.colors)?a=t.globals.stroke.colors:a.push(t.globals.stroke.colors):Array.isArray(t.globals.fill.colors)?a=t.globals.fill.colors:a.push(t.globals.fill.colors),void 0!==i.fillColors&&(a=[],Array.isArray(i.fillColors)?a=i.fillColors.slice():a.push(i.fillColors)),a}},{key:"handlePatternFill",value:function(t){var e=t.fillConfig,i=t.patternFill,a=t.fillColor,s=t.fillOpacity,r=t.defaultColor,o=this.w.config.fill;e&&(o=e);var n=this.opts,l=new m(this.ctx),h=Array.isArray(o.pattern.strokeWidth)?o.pattern.strokeWidth[this.seriesIndex]:o.pattern.strokeWidth,c=a;Array.isArray(o.pattern.style)?i=void 0!==o.pattern.style[n.seriesNumber]?l.drawPattern(o.pattern.style[n.seriesNumber],o.pattern.width,o.pattern.height,c,h,s):r:i=l.drawPattern(o.pattern.style,o.pattern.width,o.pattern.height,c,h,s);return i}},{key:"handleGradientFill",value:function(t){var i=t.fillColor,a=t.fillOpacity,s=t.fillConfig,r=t.i,o=this.w.config.fill;s&&(o=e(e({},o),s));var n,l=this.opts,h=new m(this.ctx),c=new x,d=o.gradient.type,g=i,u=void 0===o.gradient.opacityFrom?a:Array.isArray(o.gradient.opacityFrom)?o.gradient.opacityFrom[r]:o.gradient.opacityFrom;g.indexOf("rgba")>-1&&(u=x.getOpacityFromRGBA(g));var f=void 0===o.gradient.opacityTo?a:Array.isArray(o.gradient.opacityTo)?o.gradient.opacityTo[r]:o.gradient.opacityTo;if(void 0===o.gradient.gradientToColors||0===o.gradient.gradientToColors.length)n="dark"===o.gradient.shade?c.shadeColor(-1*parseFloat(o.gradient.shadeIntensity),i.indexOf("rgb")>-1?x.rgb2hex(i):i):c.shadeColor(parseFloat(o.gradient.shadeIntensity),i.indexOf("rgb")>-1?x.rgb2hex(i):i);else if(o.gradient.gradientToColors[l.seriesNumber]){var p=o.gradient.gradientToColors[l.seriesNumber];n=p,p.indexOf("rgba")>-1&&(f=x.getOpacityFromRGBA(p))}else n=i;if(o.gradient.gradientFrom&&(g=o.gradient.gradientFrom),o.gradient.gradientTo&&(n=o.gradient.gradientTo),o.gradient.inverseColors){var b=g;g=n,n=b}return g.indexOf("rgb")>-1&&(g=x.rgb2hex(g)),n.indexOf("rgb")>-1&&(n=x.rgb2hex(n)),h.drawGradient(d,g,n,u,f,l.size,o.gradient.stops,o.gradient.colorStops,r)}}]),t}(),D=function(){function t(e,i){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"setGlobalMarkerSize",value:function(){var t=this.w;if(t.globals.markers.size=Array.isArray(t.config.markers.size)?t.config.markers.size:[t.config.markers.size],t.globals.markers.size.length>0){if(t.globals.markers.size.length<t.globals.series.length+1)for(var e=0;e<=t.globals.series.length;e++)void 0===t.globals.markers.size[e]&&t.globals.markers.size.push(t.globals.markers.size[0])}else t.globals.markers.size=t.config.series.map((function(e){return t.config.markers.size}))}},{key:"plotChartMarkers",value:function(t,e,i,a){var s,r=arguments.length>4&&void 0!==arguments[4]&&arguments[4],o=this.w,n=e,l=t,h=null,c=new m(this.ctx),d=o.config.markers.discrete&&o.config.markers.discrete.length;if((o.globals.markers.size[e]>0||r||d)&&(h=c.group({class:r||d?"":"apexcharts-series-markers"})).attr("clip-path","url(#gridRectMarkerMask".concat(o.globals.cuid,")")),Array.isArray(l.x))for(var g=0;g<l.x.length;g++){var u=i;1===i&&0===g&&(u=0),1===i&&1===g&&(u=1);var f="apexcharts-marker";"line"!==o.config.chart.type&&"area"!==o.config.chart.type||o.globals.comboCharts||o.config.tooltip.intersect||(f+=" no-pointer-events");var p=Array.isArray(o.config.markers.size)?o.globals.markers.size[e]>0:o.config.markers.size>0;if(p||r||d){x.isNumber(l.y[g])?f+=" w".concat(x.randomId()):f="apexcharts-nullpoint";var b=this.getMarkerConfig({cssClass:f,seriesIndex:e,dataPointIndex:u});o.config.series[n].data[u]&&(o.config.series[n].data[u].fillColor&&(b.pointFillColor=o.config.series[n].data[u].fillColor),o.config.series[n].data[u].strokeColor&&(b.pointStrokeColor=o.config.series[n].data[u].strokeColor)),a&&(b.pSize=a),(s=c.drawMarker(l.x[g],l.y[g],b)).attr("rel",u),s.attr("j",u),s.attr("index",e),s.node.setAttribute("default-marker-size",b.pSize);var y=new v(this.ctx);y.setSelectionFilter(s,e,u),this.addEvents(s),h&&h.add(s)}else void 0===o.globals.pointsArray[e]&&(o.globals.pointsArray[e]=[]),o.globals.pointsArray[e].push([l.x[g],l.y[g]])}return h}},{key:"getMarkerConfig",value:function(t){var e=t.cssClass,i=t.seriesIndex,a=t.dataPointIndex,s=void 0===a?null:a,r=t.finishRadius,o=void 0===r?null:r,n=this.w,l=this.getMarkerStyle(i),h=n.globals.markers.size[i],c=n.config.markers;return null!==s&&c.discrete.length&&c.discrete.map((function(t){t.seriesIndex===i&&t.dataPointIndex===s&&(l.pointStrokeColor=t.strokeColor,l.pointFillColor=t.fillColor,h=t.size,l.pointShape=t.shape)})),{pSize:null===o?h:o,pRadius:c.radius,width:Array.isArray(c.width)?c.width[i]:c.width,height:Array.isArray(c.height)?c.height[i]:c.height,pointStrokeWidth:Array.isArray(c.strokeWidth)?c.strokeWidth[i]:c.strokeWidth,pointStrokeColor:l.pointStrokeColor,pointFillColor:l.pointFillColor,shape:l.pointShape||(Array.isArray(c.shape)?c.shape[i]:c.shape),class:e,pointStrokeOpacity:Array.isArray(c.strokeOpacity)?c.strokeOpacity[i]:c.strokeOpacity,pointStrokeDashArray:Array.isArray(c.strokeDashArray)?c.strokeDashArray[i]:c.strokeDashArray,pointFillOpacity:Array.isArray(c.fillOpacity)?c.fillOpacity[i]:c.fillOpacity,seriesIndex:i}}},{key:"addEvents",value:function(t){var e=this.w,i=new m(this.ctx);t.node.addEventListener("mouseenter",i.pathMouseEnter.bind(this.ctx,t)),t.node.addEventListener("mouseleave",i.pathMouseLeave.bind(this.ctx,t)),t.node.addEventListener("mousedown",i.pathMouseDown.bind(this.ctx,t)),t.node.addEventListener("click",e.config.markers.onClick),t.node.addEventListener("dblclick",e.config.markers.onDblClick),t.node.addEventListener("touchstart",i.pathMouseDown.bind(this.ctx,t),{passive:!0})}},{key:"getMarkerStyle",value:function(t){var e=this.w,i=e.globals.markers.colors,a=e.config.markers.strokeColor||e.config.markers.strokeColors;return{pointStrokeColor:Array.isArray(a)?a[t]:a,pointFillColor:Array.isArray(i)?i[t]:i}}}]),t}(),H=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.initialAnim=this.w.config.chart.animations.enabled,this.dynamicAnim=this.initialAnim&&this.w.config.chart.animations.dynamicAnimation.enabled}return r(t,[{key:"draw",value:function(t,e,i){var a=this.w,s=new m(this.ctx),r=i.realIndex,o=i.pointsPos,n=i.zRatio,l=i.elParent,h=s.group({class:"apexcharts-series-markers apexcharts-series-".concat(a.config.chart.type)});if(h.attr("clip-path","url(#gridRectMarkerMask".concat(a.globals.cuid,")")),Array.isArray(o.x))for(var c=0;c<o.x.length;c++){var d=e+1,g=!0;0===e&&0===c&&(d=0),0===e&&1===c&&(d=1);var u=0,f=a.globals.markers.size[r];if(n!==1/0){var p=a.config.plotOptions.bubble;f=a.globals.seriesZ[r][d],p.zScaling&&(f/=n),p.minBubbleRadius&&f<p.minBubbleRadius&&(f=p.minBubbleRadius),p.maxBubbleRadius&&f>p.maxBubbleRadius&&(f=p.maxBubbleRadius)}a.config.chart.animations.enabled||(u=f);var x=o.x[c],b=o.y[c];if(u=u||0,null!==b&&void 0!==a.globals.series[r][d]||(g=!1),g){var v=this.drawPoint(x,b,u,f,r,d,e);h.add(v)}l.add(h)}}},{key:"drawPoint",value:function(t,e,i,a,s,r,o){var n=this.w,l=s,h=new b(this.ctx),c=new v(this.ctx),d=new R(this.ctx),g=new D(this.ctx),u=new m(this.ctx),f=g.getMarkerConfig({cssClass:"apexcharts-marker",seriesIndex:l,dataPointIndex:r,finishRadius:"bubble"===n.config.chart.type||n.globals.comboCharts&&n.config.series[s]&&"bubble"===n.config.series[s].type?a:null});a=f.pSize;var p,x=d.fillPath({seriesNumber:s,dataPointIndex:r,color:f.pointFillColor,patternUnits:"objectBoundingBox",value:n.globals.series[s][o]});if("circle"===f.shape?p=u.drawCircle(i):"square"!==f.shape&&"rect"!==f.shape||(p=u.drawRect(0,0,f.width-f.pointStrokeWidth/2,f.height-f.pointStrokeWidth/2,f.pRadius)),n.config.series[l].data[r]&&n.config.series[l].data[r].fillColor&&(x=n.config.series[l].data[r].fillColor),p.attr({x:t-f.width/2-f.pointStrokeWidth/2,y:e-f.height/2-f.pointStrokeWidth/2,cx:t,cy:e,fill:x,"fill-opacity":f.pointFillOpacity,stroke:f.pointStrokeColor,r:a,"stroke-width":f.pointStrokeWidth,"stroke-dasharray":f.pointStrokeDashArray,"stroke-opacity":f.pointStrokeOpacity}),n.config.chart.dropShadow.enabled){var y=n.config.chart.dropShadow;c.dropShadow(p,y,s)}if(!this.initialAnim||n.globals.dataChanged||n.globals.resized)n.globals.animationEnded=!0;else{var w=n.config.chart.animations.speed;h.animateMarker(p,0,"circle"===f.shape?a:{width:f.width,height:f.height},w,n.globals.easing,(function(){window.setTimeout((function(){h.animationCompleted(p)}),100)}))}if(n.globals.dataChanged&&"circle"===f.shape)if(this.dynamicAnim){var k,A,S,C,L=n.config.chart.animations.dynamicAnimation.speed;null!=(C=n.globals.previousPaths[s]&&n.globals.previousPaths[s][o])&&(k=C.x,A=C.y,S=void 0!==C.r?C.r:a);for(var P=0;P<n.globals.collapsedSeries.length;P++)n.globals.collapsedSeries[P].index===s&&(L=1,a=0);0===t&&0===e&&(a=0),h.animateCircle(p,{cx:k,cy:A,r:S},{cx:t,cy:e,r:a},L,n.globals.easing)}else p.attr({r:a});return p.attr({rel:r,j:r,index:s,"default-marker-size":a}),c.setSelectionFilter(p,s,r),g.addEvents(p),p.node.classList.add("apexcharts-marker"),p}},{key:"centerTextInBubble",value:function(t){var e=this.w;return{y:t+=parseInt(e.config.dataLabels.style.fontSize,10)/4}}}]),t}(),O=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"dataLabelsCorrection",value:function(t,e,i,a,s,r,o){var n=this.w,l=!1,h=new m(this.ctx).getTextRects(i,o),c=h.width,d=h.height;e<0&&(e=0),e>n.globals.gridHeight+d&&(e=n.globals.gridHeight+d/2),void 0===n.globals.dataLabelsRects[a]&&(n.globals.dataLabelsRects[a]=[]),n.globals.dataLabelsRects[a].push({x:t,y:e,width:c,height:d});var g=n.globals.dataLabelsRects[a].length-2,u=void 0!==n.globals.lastDrawnDataLabelsIndexes[a]?n.globals.lastDrawnDataLabelsIndexes[a][n.globals.lastDrawnDataLabelsIndexes[a].length-1]:0;if(void 0!==n.globals.dataLabelsRects[a][g]){var f=n.globals.dataLabelsRects[a][u];(t>f.x+f.width+2||e>f.y+f.height+2||t+c<f.x)&&(l=!0)}return(0===s||r)&&(l=!0),{x:t,y:e,textRects:h,drawnextLabel:l}}},{key:"drawDataLabel",value:function(t){var e=this,i=t.type,a=t.pos,s=t.i,r=t.j,o=t.isRangeStart,n=t.strokeWidth,l=void 0===n?2:n,h=this.w,c=new m(this.ctx),d=h.config.dataLabels,g=0,u=0,f=r,p=null;if(!d.enabled||!Array.isArray(a.x))return p;p=c.group({class:"apexcharts-data-labels"});for(var x=0;x<a.x.length;x++)if(g=a.x[x]+d.offsetX,u=a.y[x]+d.offsetY+l,!isNaN(g)){1===r&&0===x&&(f=0),1===r&&1===x&&(f=1);var b=h.globals.series[s][f];"rangeArea"===i&&(b=o?h.globals.seriesRangeStart[s][f]:h.globals.seriesRangeEnd[s][f]);var v="",y=function(t){return h.config.dataLabels.formatter(t,{ctx:e.ctx,seriesIndex:s,dataPointIndex:f,w:h})};if("bubble"===h.config.chart.type)v=y(b=h.globals.seriesZ[s][f]),u=a.y[x],u=new H(this.ctx).centerTextInBubble(u,s,f).y;else void 0!==b&&(v=y(b));this.plotDataLabelsText({x:g,y:u,text:v,i:s,j:f,parent:p,offsetCorrection:!0,dataLabelsConfig:h.config.dataLabels})}return p}},{key:"plotDataLabelsText",value:function(t){var e=this.w,i=new m(this.ctx),a=t.x,s=t.y,r=t.i,o=t.j,n=t.text,l=t.textAnchor,h=t.fontSize,c=t.parent,d=t.dataLabelsConfig,g=t.color,u=t.alwaysDrawDataLabel,f=t.offsetCorrection;if(!(Array.isArray(e.config.dataLabels.enabledOnSeries)&&e.config.dataLabels.enabledOnSeries.indexOf(r)<0)){var p={x:a,y:s,drawnextLabel:!0,textRects:null};f&&(p=this.dataLabelsCorrection(a,s,n,r,o,u,parseInt(d.style.fontSize,10))),e.globals.zoomed||(a=p.x,s=p.y),p.textRects&&(a<-10-p.textRects.width||a>e.globals.gridWidth+p.textRects.width+10)&&(n="");var x=e.globals.dataLabels.style.colors[r];(("bar"===e.config.chart.type||"rangeBar"===e.config.chart.type)&&e.config.plotOptions.bar.distributed||e.config.dataLabels.distributed)&&(x=e.globals.dataLabels.style.colors[o]),"function"==typeof x&&(x=x({series:e.globals.series,seriesIndex:r,dataPointIndex:o,w:e})),g&&(x=g);var b=d.offsetX,y=d.offsetY;if("bar"!==e.config.chart.type&&"rangeBar"!==e.config.chart.type||(b=0,y=0),p.drawnextLabel){var w=i.drawText({width:100,height:parseInt(d.style.fontSize,10),x:a+b,y:s+y,foreColor:x,textAnchor:l||d.textAnchor,text:n,fontSize:h||d.style.fontSize,fontFamily:d.style.fontFamily,fontWeight:d.style.fontWeight||"normal"});if(w.attr({class:"apexcharts-datalabel",cx:a,cy:s}),d.dropShadow.enabled){var k=d.dropShadow;new v(this.ctx).dropShadow(w,k)}c.add(w),void 0===e.globals.lastDrawnDataLabelsIndexes[r]&&(e.globals.lastDrawnDataLabelsIndexes[r]=[]),e.globals.lastDrawnDataLabelsIndexes[r].push(o)}}}},{key:"addBackgroundToDataLabel",value:function(t,e){var i=this.w,a=i.config.dataLabels.background,s=a.padding,r=a.padding/2,o=e.width,n=e.height,l=new m(this.ctx).drawRect(e.x-s,e.y-r/2,o+2*s,n+r,a.borderRadius,"transparent"===i.config.chart.background?"#fff":i.config.chart.background,a.opacity,a.borderWidth,a.borderColor);a.dropShadow.enabled&&new v(this.ctx).dropShadow(l,a.dropShadow);return l}},{key:"dataLabelsBackground",value:function(){var t=this.w;if("bubble"!==t.config.chart.type)for(var e=t.globals.dom.baseEl.querySelectorAll(".apexcharts-datalabels text"),i=0;i<e.length;i++){var a=e[i],s=a.getBBox(),r=null;if(s.width&&s.height&&(r=this.addBackgroundToDataLabel(a,s)),r){a.parentNode.insertBefore(r.node,a);var o=a.getAttribute("fill");t.config.chart.animations.enabled&&!t.globals.resized&&!t.globals.dataChanged?r.animate().attr({fill:o}):r.attr({fill:o}),a.setAttribute("fill",t.config.dataLabels.background.foreColor)}}}},{key:"bringForward",value:function(){for(var t=this.w,e=t.globals.dom.baseEl.querySelectorAll(".apexcharts-datalabels"),i=t.globals.dom.baseEl.querySelector(".apexcharts-plot-series:last-child"),a=0;a<e.length;a++)i&&i.insertBefore(e[a],i.nextSibling)}}]),t}(),N=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.legendInactiveClass="legend-mouseover-inactive"}return r(t,[{key:"getAllSeriesEls",value:function(){return this.w.globals.dom.baseEl.getElementsByClassName("apexcharts-series")}},{key:"getSeriesByName",value:function(t){return this.w.globals.dom.baseEl.querySelector(".apexcharts-inner .apexcharts-series[seriesName='".concat(x.escapeString(t),"']"))}},{key:"isSeriesHidden",value:function(t){var e=this.getSeriesByName(t),i=parseInt(e.getAttribute("data:realIndex"),10);return{isHidden:e.classList.contains("apexcharts-series-collapsed"),realIndex:i}}},{key:"addCollapsedClassToSeries",value:function(t,e){var i=this.w;function a(i){for(var a=0;a<i.length;a++)i[a].index===e&&t.node.classList.add("apexcharts-series-collapsed")}a(i.globals.collapsedSeries),a(i.globals.ancillaryCollapsedSeries)}},{key:"toggleSeries",value:function(t){var e=this.isSeriesHidden(t);return this.ctx.legend.legendHelpers.toggleDataSeries(e.realIndex,e.isHidden),e.isHidden}},{key:"showSeries",value:function(t){var e=this.isSeriesHidden(t);e.isHidden&&this.ctx.legend.legendHelpers.toggleDataSeries(e.realIndex,!0)}},{key:"hideSeries",value:function(t){var e=this.isSeriesHidden(t);e.isHidden||this.ctx.legend.legendHelpers.toggleDataSeries(e.realIndex,!1)}},{key:"resetSeries",value:function(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],a=this.w,s=x.clone(a.globals.initialSeries);a.globals.previousPaths=[],i?(a.globals.collapsedSeries=[],a.globals.ancillaryCollapsedSeries=[],a.globals.collapsedSeriesIndices=[],a.globals.ancillaryCollapsedSeriesIndices=[]):s=this.emptyCollapsedSeries(s),a.config.series=s,t&&(e&&(a.globals.zoomed=!1,this.ctx.updateHelpers.revertDefaultAxisMinMax()),this.ctx.updateHelpers._updateSeries(s,a.config.chart.animations.dynamicAnimation.enabled))}},{key:"emptyCollapsedSeries",value:function(t){for(var e=this.w,i=0;i<t.length;i++)e.globals.collapsedSeriesIndices.indexOf(i)>-1&&(t[i].data=[]);return t}},{key:"toggleSeriesOnHover",value:function(t,e){var i=this.w;e||(e=t.target);var a=i.globals.dom.baseEl.querySelectorAll(".apexcharts-series, .apexcharts-datalabels");if("mousemove"===t.type){var s=parseInt(e.getAttribute("rel"),10)-1,r=null,o=null;i.globals.axisCharts||"radialBar"===i.config.chart.type?i.globals.axisCharts?(r=i.globals.dom.baseEl.querySelector(".apexcharts-series[data\\:realIndex='".concat(s,"']")),o=i.globals.dom.baseEl.querySelector(".apexcharts-datalabels[data\\:realIndex='".concat(s,"']"))):r=i.globals.dom.baseEl.querySelector(".apexcharts-series[rel='".concat(s+1,"']")):r=i.globals.dom.baseEl.querySelector(".apexcharts-series[rel='".concat(s+1,"'] path"));for(var n=0;n<a.length;n++)a[n].classList.add(this.legendInactiveClass);null!==r&&(i.globals.axisCharts||r.parentNode.classList.remove(this.legendInactiveClass),r.classList.remove(this.legendInactiveClass),null!==o&&o.classList.remove(this.legendInactiveClass))}else if("mouseout"===t.type)for(var l=0;l<a.length;l++)a[l].classList.remove(this.legendInactiveClass)}},{key:"highlightRangeInSeries",value:function(t,e){var i=this,a=this.w,s=a.globals.dom.baseEl.getElementsByClassName("apexcharts-heatmap-rect"),r=function(t){for(var e=0;e<s.length;e++)s[e].classList[t](i.legendInactiveClass)};if("mousemove"===t.type){var o=parseInt(e.getAttribute("rel"),10)-1;r("add"),function(t){for(var e=0;e<s.length;e++){var a=parseInt(s[e].getAttribute("val"),10);a>=t.from&&a<=t.to&&s[e].classList.remove(i.legendInactiveClass)}}(a.config.plotOptions.heatmap.colorScale.ranges[o])}else"mouseout"===t.type&&r("remove")}},{key:"getActiveConfigSeriesIndex",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"asc",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[],i=this.w,a=0;if(i.config.series.length>1)for(var s=i.config.series.map((function(t,a){return t.data&&t.data.length>0&&-1===i.globals.collapsedSeriesIndices.indexOf(a)&&(!i.globals.comboCharts||0===e.length||e.length&&e.indexOf(i.config.series[a].type)>-1)?a:-1})),r="asc"===t?0:s.length-1;"asc"===t?r<s.length:r>=0;"asc"===t?r++:r--)if(-1!==s[r]){a=s[r];break}return a}},{key:"getBarSeriesIndices",value:function(){return this.w.globals.comboCharts?this.w.config.series.map((function(t,e){return"bar"===t.type||"column"===t.type?e:-1})).filter((function(t){return-1!==t})):this.w.config.series.map((function(t,e){return e}))}},{key:"getPreviousPaths",value:function(){var t=this.w;function e(e,i,a){for(var s=e[i].childNodes,r={type:a,paths:[],realIndex:e[i].getAttribute("data:realIndex")},o=0;o<s.length;o++)if(s[o].hasAttribute("pathTo")){var n=s[o].getAttribute("pathTo");r.paths.push({d:n})}t.globals.previousPaths.push(r)}t.globals.previousPaths=[];["line","area","bar","rangebar","rangeArea","candlestick","radar"].forEach((function(i){for(var a,s=(a=i,t.globals.dom.baseEl.querySelectorAll(".apexcharts-".concat(a,"-series .apexcharts-series"))),r=0;r<s.length;r++)e(s,r,i)})),this.handlePrevBubbleScatterPaths("bubble"),this.handlePrevBubbleScatterPaths("scatter");var i=t.globals.dom.baseEl.querySelectorAll(".apexcharts-".concat(t.config.chart.type," .apexcharts-series"));if(i.length>0)for(var a=function(e){for(var i=t.globals.dom.baseEl.querySelectorAll(".apexcharts-".concat(t.config.chart.type," .apexcharts-series[data\\:realIndex='").concat(e,"'] rect")),a=[],s=function(t){var e=function(e){return i[t].getAttribute(e)},s={x:parseFloat(e("x")),y:parseFloat(e("y")),width:parseFloat(e("width")),height:parseFloat(e("height"))};a.push({rect:s,color:i[t].getAttribute("color")})},r=0;r<i.length;r++)s(r);t.globals.previousPaths.push(a)},s=0;s<i.length;s++)a(s);t.globals.axisCharts||(t.globals.previousPaths=t.globals.series)}},{key:"handlePrevBubbleScatterPaths",value:function(t){var e=this.w,i=e.globals.dom.baseEl.querySelectorAll(".apexcharts-".concat(t,"-series .apexcharts-series"));if(i.length>0)for(var a=0;a<i.length;a++){for(var s=e.globals.dom.baseEl.querySelectorAll(".apexcharts-".concat(t,"-series .apexcharts-series[data\\:realIndex='").concat(a,"'] circle")),r=[],o=0;o<s.length;o++)r.push({x:s[o].getAttribute("cx"),y:s[o].getAttribute("cy"),r:s[o].getAttribute("r")});e.globals.previousPaths.push(r)}}},{key:"clearPreviousPaths",value:function(){var t=this.w;t.globals.previousPaths=[],t.globals.allSeriesCollapsed=!1}},{key:"handleNoData",value:function(){var t=this.w,e=t.config.noData,i=new m(this.ctx),a=t.globals.svgWidth/2,s=t.globals.svgHeight/2,r="middle";if(t.globals.noData=!0,t.globals.animationEnded=!0,"left"===e.align?(a=10,r="start"):"right"===e.align&&(a=t.globals.svgWidth-10,r="end"),"top"===e.verticalAlign?s=50:"bottom"===e.verticalAlign&&(s=t.globals.svgHeight-50),a+=e.offsetX,s=s+parseInt(e.style.fontSize,10)+2+e.offsetY,void 0!==e.text&&""!==e.text){var o=i.drawText({x:a,y:s,text:e.text,textAnchor:r,fontSize:e.style.fontSize,fontFamily:e.style.fontFamily,foreColor:e.style.color,opacity:1,class:"apexcharts-text-nodata"});t.globals.dom.Paper.add(o)}}},{key:"setNullSeriesToZeroValues",value:function(t){for(var e=this.w,i=0;i<t.length;i++)if(0===t[i].length)for(var a=0;a<t[e.globals.maxValsInArrayIndex].length;a++)t[i].push(0);return t}},{key:"hasAllSeriesEqualX",value:function(){for(var t=!0,e=this.w,i=this.filteredSeriesX(),a=0;a<i.length-1;a++)if(i[a][0]!==i[a+1][0]){t=!1;break}return e.globals.allSeriesHasEqualX=t,t}},{key:"filteredSeriesX",value:function(){var t=this.w.globals.seriesX.map((function(t){return t.length>0?t:[]}));return t}}]),t}(),W=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.twoDSeries=[],this.threeDSeries=[],this.twoDSeriesX=[],this.seriesGoals=[],this.coreUtils=new y(this.ctx)}return r(t,[{key:"isMultiFormat",value:function(){return this.isFormatXY()||this.isFormat2DArray()}},{key:"isFormatXY",value:function(){var t=this.w.config.series.slice(),e=new N(this.ctx);if(this.activeSeriesIndex=e.getActiveConfigSeriesIndex(),void 0!==t[this.activeSeriesIndex].data&&t[this.activeSeriesIndex].data.length>0&&null!==t[this.activeSeriesIndex].data[0]&&void 0!==t[this.activeSeriesIndex].data[0].x&&null!==t[this.activeSeriesIndex].data[0])return!0}},{key:"isFormat2DArray",value:function(){var t=this.w.config.series.slice(),e=new N(this.ctx);if(this.activeSeriesIndex=e.getActiveConfigSeriesIndex(),void 0!==t[this.activeSeriesIndex].data&&t[this.activeSeriesIndex].data.length>0&&void 0!==t[this.activeSeriesIndex].data[0]&&null!==t[this.activeSeriesIndex].data[0]&&t[this.activeSeriesIndex].data[0].constructor===Array)return!0}},{key:"handleFormat2DArray",value:function(t,e){for(var i=this.w.config,a=this.w.globals,s="boxPlot"===i.chart.type||"boxPlot"===i.series[e].type,r=0;r<t[e].data.length;r++)if(void 0!==t[e].data[r][1]&&(Array.isArray(t[e].data[r][1])&&4===t[e].data[r][1].length&&!s?this.twoDSeries.push(x.parseNumber(t[e].data[r][1][3])):t[e].data[r].length>=5?this.twoDSeries.push(x.parseNumber(t[e].data[r][4])):this.twoDSeries.push(x.parseNumber(t[e].data[r][1])),a.dataFormatXNumeric=!0),"datetime"===i.xaxis.type){var o=new Date(t[e].data[r][0]);o=new Date(o).getTime(),this.twoDSeriesX.push(o)}else this.twoDSeriesX.push(t[e].data[r][0]);for(var n=0;n<t[e].data.length;n++)void 0!==t[e].data[n][2]&&(this.threeDSeries.push(t[e].data[n][2]),a.isDataXYZ=!0)}},{key:"handleFormatXY",value:function(t,e){var i=this.w.config,a=this.w.globals,s=new T(this.ctx),r=e;a.collapsedSeriesIndices.indexOf(e)>-1&&(r=this.activeSeriesIndex);for(var o=0;o<t[e].data.length;o++)void 0!==t[e].data[o].y&&(Array.isArray(t[e].data[o].y)?this.twoDSeries.push(x.parseNumber(t[e].data[o].y[t[e].data[o].y.length-1])):this.twoDSeries.push(x.parseNumber(t[e].data[o].y))),void 0!==t[e].data[o].goals&&Array.isArray(t[e].data[o].goals)?(void 0===this.seriesGoals[e]&&(this.seriesGoals[e]=[]),this.seriesGoals[e].push(t[e].data[o].goals)):(void 0===this.seriesGoals[e]&&(this.seriesGoals[e]=[]),this.seriesGoals[e].push(null));for(var n=0;n<t[r].data.length;n++){var l="string"==typeof t[r].data[n].x,h=Array.isArray(t[r].data[n].x),c=!h&&!!s.isValidDate(t[r].data[n].x.toString());if(l||c)if(l||i.xaxis.convertedCatToNumeric){var d=a.isBarHorizontal&&a.isRangeData;"datetime"!==i.xaxis.type||d?(this.fallbackToCategory=!0,this.twoDSeriesX.push(t[r].data[n].x)):this.twoDSeriesX.push(s.parseDate(t[r].data[n].x))}else"datetime"===i.xaxis.type?this.twoDSeriesX.push(s.parseDate(t[r].data[n].x.toString())):(a.dataFormatXNumeric=!0,a.isXNumeric=!0,this.twoDSeriesX.push(parseFloat(t[r].data[n].x)));else h?(this.fallbackToCategory=!0,this.twoDSeriesX.push(t[r].data[n].x)):(a.isXNumeric=!0,a.dataFormatXNumeric=!0,this.twoDSeriesX.push(t[r].data[n].x))}if(t[e].data[0]&&void 0!==t[e].data[0].z){for(var g=0;g<t[e].data.length;g++)this.threeDSeries.push(t[e].data[g].z);a.isDataXYZ=!0}}},{key:"handleRangeData",value:function(t,e){var i=this.w.globals,a={};return this.isFormat2DArray()?a=this.handleRangeDataFormat("array",t,e):this.isFormatXY()&&(a=this.handleRangeDataFormat("xy",t,e)),i.seriesRangeStart.push(a.start),i.seriesRangeEnd.push(a.end),i.seriesRange.push(a.rangeUniques),i.seriesRange.forEach((function(t,e){t&&t.forEach((function(t,e){t.y.forEach((function(e,i){for(var a=0;a<t.y.length;a++)if(i!==a){var s=e.y1,r=e.y2,o=t.y[a].y1;s<=t.y[a].y2&&o<=r&&(t.overlaps.indexOf(e.rangeName)<0&&t.overlaps.push(e.rangeName),t.overlaps.indexOf(t.y[a].rangeName)<0&&t.overlaps.push(t.y[a].rangeName))}}))}))})),a}},{key:"handleCandleStickBoxData",value:function(t,e){var i=this.w.globals,a={};return this.isFormat2DArray()?a=this.handleCandleStickBoxDataFormat("array",t,e):this.isFormatXY()&&(a=this.handleCandleStickBoxDataFormat("xy",t,e)),i.seriesCandleO[e]=a.o,i.seriesCandleH[e]=a.h,i.seriesCandleM[e]=a.m,i.seriesCandleL[e]=a.l,i.seriesCandleC[e]=a.c,a}},{key:"handleRangeDataFormat",value:function(t,e,i){var a=[],s=[],r=e[i].data.filter((function(t,e,i){return e===i.findIndex((function(e){return e.x===t.x}))})).map((function(t,e){return{x:t.x,overlaps:[],y:[]}}));if("array"===t)for(var o=0;o<e[i].data.length;o++)Array.isArray(e[i].data[o])?(a.push(e[i].data[o][1][0]),s.push(e[i].data[o][1][1])):(a.push(e[i].data[o]),s.push(e[i].data[o]));else if("xy"===t)for(var n=function(t){var o=Array.isArray(e[i].data[t].y),n=x.randomId(),l=e[i].data[t].x,h={y1:o?e[i].data[t].y[0]:e[i].data[t].y,y2:o?e[i].data[t].y[1]:e[i].data[t].y,rangeName:n};e[i].data[t].rangeName=n;var c=r.findIndex((function(t){return t.x===l}));r[c].y.push(h),a.push(h.y1),s.push(h.y2)},l=0;l<e[i].data.length;l++)n(l);return{start:a,end:s,rangeUniques:r}}},{key:"handleCandleStickBoxDataFormat",value:function(t,e,i){var a=this.w,s="boxPlot"===a.config.chart.type||"boxPlot"===a.config.series[i].type,r=[],o=[],n=[],l=[],h=[];if("array"===t)if(s&&6===e[i].data[0].length||!s&&5===e[i].data[0].length)for(var c=0;c<e[i].data.length;c++)r.push(e[i].data[c][1]),o.push(e[i].data[c][2]),s?(n.push(e[i].data[c][3]),l.push(e[i].data[c][4]),h.push(e[i].data[c][5])):(l.push(e[i].data[c][3]),h.push(e[i].data[c][4]));else for(var d=0;d<e[i].data.length;d++)Array.isArray(e[i].data[d][1])&&(r.push(e[i].data[d][1][0]),o.push(e[i].data[d][1][1]),s?(n.push(e[i].data[d][1][2]),l.push(e[i].data[d][1][3]),h.push(e[i].data[d][1][4])):(l.push(e[i].data[d][1][2]),h.push(e[i].data[d][1][3])));else if("xy"===t)for(var g=0;g<e[i].data.length;g++)Array.isArray(e[i].data[g].y)&&(r.push(e[i].data[g].y[0]),o.push(e[i].data[g].y[1]),s?(n.push(e[i].data[g].y[2]),l.push(e[i].data[g].y[3]),h.push(e[i].data[g].y[4])):(l.push(e[i].data[g].y[2]),h.push(e[i].data[g].y[3])));return{o:r,h:o,m:n,l:l,c:h}}},{key:"parseDataAxisCharts",value:function(t){var e=this,i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:this.ctx,a=this.w.config,s=this.w.globals,r=new T(i),o=a.labels.length>0?a.labels.slice():a.xaxis.categories.slice();s.isRangeBar="rangeBar"===a.chart.type&&s.isBarHorizontal,s.hasGroups="category"===a.xaxis.type&&a.xaxis.group.groups.length>0,s.hasGroups&&(s.groups=a.xaxis.group.groups);for(var n=function(){for(var t=0;t<o.length;t++)if("string"==typeof o[t]){if(!r.isValidDate(o[t]))throw new Error("You have provided invalid Date format. Please provide a valid JavaScript Date");e.twoDSeriesX.push(r.parseDate(o[t]))}else e.twoDSeriesX.push(o[t])},l=0;l<t.length;l++){if(this.twoDSeries=[],this.twoDSeriesX=[],this.threeDSeries=[],void 0===t[l].data)return void console.error("It is a possibility that you may have not included 'data' property in series.");if("rangeBar"!==a.chart.type&&"rangeArea"!==a.chart.type&&"rangeBar"!==t[l].type&&"rangeArea"!==t[l].type||(s.isRangeData=!0,s.isComboCharts?"rangeBar"!==t[l].type&&"rangeArea"!==t[l].type||this.handleRangeData(t,l):"rangeBar"!==a.chart.type&&"rangeArea"!==a.chart.type||this.handleRangeData(t,l)),this.isMultiFormat())this.isFormat2DArray()?this.handleFormat2DArray(t,l):this.isFormatXY()&&this.handleFormatXY(t,l),"candlestick"!==a.chart.type&&"candlestick"!==t[l].type&&"boxPlot"!==a.chart.type&&"boxPlot"!==t[l].type||this.handleCandleStickBoxData(t,l),s.series.push(this.twoDSeries),s.labels.push(this.twoDSeriesX),s.seriesX.push(this.twoDSeriesX),s.seriesGoals=this.seriesGoals,l!==this.activeSeriesIndex||this.fallbackToCategory||(s.isXNumeric=!0);else{"datetime"===a.xaxis.type?(s.isXNumeric=!0,n(),s.seriesX.push(this.twoDSeriesX)):"numeric"===a.xaxis.type&&(s.isXNumeric=!0,o.length>0&&(this.twoDSeriesX=o,s.seriesX.push(this.twoDSeriesX))),s.labels.push(this.twoDSeriesX);var h=t[l].data.map((function(t){return x.parseNumber(t)}));s.series.push(h)}s.seriesZ.push(this.threeDSeries),void 0!==t[l].name?s.seriesNames.push(t[l].name):s.seriesNames.push("series-"+parseInt(l+1,10)),void 0!==t[l].color?s.seriesColors.push(t[l].color):s.seriesColors.push(void 0)}return this.w}},{key:"parseDataNonAxisCharts",value:function(t){var e=this.w.globals,i=this.w.config;e.series=t.slice(),e.seriesNames=i.labels.slice();for(var a=0;a<e.series.length;a++)void 0===e.seriesNames[a]&&e.seriesNames.push("series-"+(a+1));return this.w}},{key:"handleExternalLabelsData",value:function(t){var e=this.w.config,i=this.w.globals;if(e.xaxis.categories.length>0)i.labels=e.xaxis.categories;else if(e.labels.length>0)i.labels=e.labels.slice();else if(this.fallbackToCategory){if(i.labels=i.labels[0],i.seriesRange.length&&(i.seriesRange.map((function(t){t.forEach((function(t){i.labels.indexOf(t.x)<0&&t.x&&i.labels.push(t.x)}))})),i.labels=i.labels.filter((function(t,e,i){return i.indexOf(t)===e}))),e.xaxis.convertedCatToNumeric)new X(e).convertCatToNumericXaxis(e,this.ctx,i.seriesX[0]),this._generateExternalLabels(t)}else this._generateExternalLabels(t)}},{key:"_generateExternalLabels",value:function(t){var e=this.w.globals,i=this.w.config,a=[];if(e.axisCharts){if(e.series.length>0)if(this.isFormatXY())for(var s=i.series.map((function(t,e){return t.data.filter((function(t,e,i){return i.findIndex((function(e){return e.x===t.x}))===e}))})),r=s.reduce((function(t,e,i,a){return a[t].length>e.length?t:i}),0),o=0;o<s[r].length;o++)a.push(o+1);else for(var n=0;n<e.series[e.maxValsInArrayIndex].length;n++)a.push(n+1);e.seriesX=[];for(var l=0;l<t.length;l++)e.seriesX.push(a);e.isXNumeric=!0}if(0===a.length){a=e.axisCharts?[]:e.series.map((function(t,e){return e+1}));for(var h=0;h<t.length;h++)e.seriesX.push(a)}e.labels=a,i.xaxis.convertedCatToNumeric&&(e.categoryLabels=a.map((function(t){return i.xaxis.labels.formatter(t)}))),e.noLabelsProvided=!0}},{key:"parseData",value:function(t){var e=this.w,i=e.config,a=e.globals;if(this.excludeCollapsedSeriesInYAxis(),this.fallbackToCategory=!1,this.ctx.core.resetGlobals(),this.ctx.core.isMultipleY(),a.axisCharts?(this.parseDataAxisCharts(t),this.coreUtils.getLargestSeries()):this.parseDataNonAxisCharts(t),"bar"===i.chart.type&&i.chart.stacked){var s=new N(this.ctx);a.series=s.setNullSeriesToZeroValues(a.series)}this.coreUtils.getSeriesTotals(),a.axisCharts&&(a.stackedSeriesTotals=this.coreUtils.getStackedSeriesTotals()),this.coreUtils.getPercentSeries(),a.dataFormatXNumeric||a.isXNumeric&&("numeric"!==i.xaxis.type||0!==i.labels.length||0!==i.xaxis.categories.length)||this.handleExternalLabelsData(t);for(var r=this.coreUtils.getCategoryLabels(a.labels),o=0;o<r.length;o++)if(Array.isArray(r[o])){a.isMultiLineX=!0;break}}},{key:"excludeCollapsedSeriesInYAxis",value:function(){var t=this,e=this.w;e.globals.ignoreYAxisIndexes=e.globals.collapsedSeries.map((function(i,a){if(t.w.globals.isMultipleYAxis&&!e.config.chart.stacked)return i.index}))}}]),t}(),B=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"getLabel",value:function(t,e,i,a){var s=arguments.length>4&&void 0!==arguments[4]?arguments[4]:[],r=arguments.length>5&&void 0!==arguments[5]?arguments[5]:"12px",o=!(arguments.length>6&&void 0!==arguments[6])||arguments[6],n=this.w,l=void 0===t[a]?"":t[a],h=l,c=n.globals.xLabelFormatter,d=n.config.xaxis.labels.formatter,g=!1,u=new M(this.ctx),f=l;o&&(h=u.xLabelFormat(c,l,f,{i:a,dateFormatter:new T(this.ctx).formatDate,w:n}),void 0!==d&&(h=d(l,t[a],{i:a,dateFormatter:new T(this.ctx).formatDate,w:n})));var p=function(t){var i=null;return e.forEach((function(t){"month"===t.unit?i="year":"day"===t.unit?i="month":"hour"===t.unit?i="day":"minute"===t.unit&&(i="hour")})),i===t};e.length>0?(g=p(e[a].unit),i=e[a].position,h=e[a].value):"datetime"===n.config.xaxis.type&&void 0===d&&(h=""),void 0===h&&(h=""),h=Array.isArray(h)?h:h.toString();var x=new m(this.ctx),b={};b=n.globals.rotateXLabels&&o?x.getTextRects(h,parseInt(r,10),null,"rotate(".concat(n.config.xaxis.labels.rotate," 0 0)"),!1):x.getTextRects(h,parseInt(r,10));var v=!n.config.xaxis.labels.showDuplicates&&this.ctx.timeScale;return!Array.isArray(h)&&(0===h.indexOf("NaN")||0===h.toLowerCase().indexOf("invalid")||h.toLowerCase().indexOf("infinity")>=0||s.indexOf(h)>=0&&v)&&(h=""),{x:i,text:h,textRect:b,isBold:g}}},{key:"checkLabelBasedOnTickamount",value:function(t,e,i){var a=this.w,s=a.config.xaxis.tickAmount;return"dataPoints"===s&&(s=Math.round(a.globals.gridWidth/120)),s>i||t%Math.round(i/(s+1))==0||(e.text=""),e}},{key:"checkForOverflowingLabels",value:function(t,e,i,a,s){var r=this.w;if(0===t&&r.globals.skipFirstTimelinelabel&&(e.text=""),t===i-1&&r.globals.skipLastTimelinelabel&&(e.text=""),r.config.xaxis.labels.hideOverlappingLabels&&a.length>0){var o=s[s.length-1];e.x<o.textRect.width/(r.globals.rotateXLabels?Math.abs(r.config.xaxis.labels.rotate)/12:1.01)+o.x&&(e.text="")}return e}},{key:"checkForReversedLabels",value:function(t,e){var i=this.w;return i.config.yaxis[t]&&i.config.yaxis[t].reversed&&e.reverse(),e}},{key:"isYAxisHidden",value:function(t){var e=this.w,i=new y(this.ctx);return!e.config.yaxis[t].show||!e.config.yaxis[t].showForNullSeries&&i.isSeriesNull(t)&&-1===e.globals.collapsedSeriesIndices.indexOf(t)}},{key:"getYAxisForeColor",value:function(t,e){var i=this.w;return Array.isArray(t)&&i.globals.yAxisScale[e]&&this.ctx.theme.pushExtraColors(t,i.globals.yAxisScale[e].result.length,!1),t}},{key:"drawYAxisTicks",value:function(t,e,i,a,s,r,o){var n=this.w,l=new m(this.ctx),h=n.globals.translateY;if(a.show&&e>0){!0===n.config.yaxis[s].opposite&&(t+=a.width);for(var c=e;c>=0;c--){var d=h+e/10+n.config.yaxis[s].labels.offsetY-1;n.globals.isBarHorizontal&&(d=r*c),"heatmap"===n.config.chart.type&&(d+=r/2);var g=l.drawLine(t+i.offsetX-a.width+a.offsetX,d+a.offsetY,t+i.offsetX+a.offsetX,d+a.offsetY,a.color);o.add(g),h+=r}}}}]),t}(),G=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"scaleSvgNode",value:function(t,e){var i=parseFloat(t.getAttributeNS(null,"width")),a=parseFloat(t.getAttributeNS(null,"height"));t.setAttributeNS(null,"width",i*e),t.setAttributeNS(null,"height",a*e),t.setAttributeNS(null,"viewBox","0 0 "+i+" "+a)}},{key:"fixSvgStringForIe11",value:function(t){if(!x.isIE11())return t.replace(/&nbsp;/g,"&#160;");var e=0,i=t.replace(/xmlns="http:\/\/www.w3.org\/2000\/svg"/g,(function(t){return 2===++e?'xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"':t}));return i=(i=i.replace(/xmlns:NS\d+=""/g,"")).replace(/NS\d+:(\w+:\w+=")/g,"$1")}},{key:"getSvgString",value:function(t){null==t&&(t=1);var e=this.w.globals.dom.Paper.svg();if(1!==t){var i=this.w.globals.dom.Paper.node.cloneNode(!0);this.scaleSvgNode(i,t),e=(new XMLSerializer).serializeToString(i)}return this.fixSvgStringForIe11(e)}},{key:"cleanup",value:function(){var t=this.w,e=t.globals.dom.baseEl.getElementsByClassName("apexcharts-xcrosshairs"),i=t.globals.dom.baseEl.getElementsByClassName("apexcharts-ycrosshairs"),a=t.globals.dom.baseEl.querySelectorAll(".apexcharts-zoom-rect, .apexcharts-selection-rect");Array.prototype.forEach.call(a,(function(t){t.setAttribute("width",0)})),e&&e[0]&&(e[0].setAttribute("x",-500),e[0].setAttribute("x1",-500),e[0].setAttribute("x2",-500)),i&&i[0]&&(i[0].setAttribute("y",-100),i[0].setAttribute("y1",-100),i[0].setAttribute("y2",-100))}},{key:"svgUrl",value:function(){this.cleanup();var t=this.getSvgString(),e=new Blob([t],{type:"image/svg+xml;charset=utf-8"});return URL.createObjectURL(e)}},{key:"dataURI",value:function(t){var e=this;return new Promise((function(i){var a=e.w,s=t?t.scale||t.width/a.globals.svgWidth:1;e.cleanup();var r=document.createElement("canvas");r.width=a.globals.svgWidth*s,r.height=parseInt(a.globals.dom.elWrap.style.height,10)*s;var o="transparent"===a.config.chart.background?"#fff":a.config.chart.background,n=r.getContext("2d");n.fillStyle=o,n.fillRect(0,0,r.width*s,r.height*s);var l=e.getSvgString(s);if(window.canvg&&x.isIE11()){var h=window.canvg.Canvg.fromString(n,l,{ignoreClear:!0,ignoreDimensions:!0});h.start();var c=r.msToBlob();h.stop(),i({blob:c})}else{var d="data:image/svg+xml,"+encodeURIComponent(l),g=new Image;g.crossOrigin="anonymous",g.onload=function(){if(n.drawImage(g,0,0),r.msToBlob){var t=r.msToBlob();i({blob:t})}else{var e=r.toDataURL("image/png");i({imgURI:e})}},g.src=d}}))}},{key:"exportToSVG",value:function(){this.triggerDownload(this.svgUrl(),this.w.config.chart.toolbar.export.svg.filename,".svg")}},{key:"exportToPng",value:function(){var t=this;this.dataURI().then((function(e){var i=e.imgURI,a=e.blob;a?navigator.msSaveOrOpenBlob(a,t.w.globals.chartID+".png"):t.triggerDownload(i,t.w.config.chart.toolbar.export.png.filename,".png")}))}},{key:"exportToCSV",value:function(t){var e=this,i=t.series,a=t.fileName,s=t.columnDelimiter,r=void 0===s?",":s,o=t.lineDelimiter,n=void 0===o?"\n":o,l=this.w;i||(i=l.config.series);var h=[],c=[],d="",g=l.globals.series.map((function(t,e){return-1===l.globals.collapsedSeriesIndices.indexOf(e)?t:[]})),f=Math.max.apply(Math,u(i.map((function(t){return t.data?t.data.length:0})))),p=new W(this.ctx),b=new B(this.ctx),v=function(t){var i="";if(l.globals.axisCharts){if("category"===l.config.xaxis.type||l.config.xaxis.convertedCatToNumeric)if(l.globals.isBarHorizontal){var a=l.globals.yLabelFormatters[0],s=new N(e.ctx).getActiveConfigSeriesIndex();i=a(l.globals.labels[t],{seriesIndex:s,dataPointIndex:t,w:l})}else i=b.getLabel(l.globals.labels,l.globals.timescaleLabels,0,t).text;"datetime"===l.config.xaxis.type&&(l.config.xaxis.categories.length?i=l.config.xaxis.categories[t]:l.config.labels.length&&(i=l.config.labels[t]))}else i=l.config.labels[t];return Array.isArray(i)&&(i=i.join(" ")),x.isNumber(i)?i:i.split(r).join("")},m=function(t,e){if(h.length&&0===e&&c.push(h.join(r)),t.data){t.data=t.data.length&&t.data||u(Array(f)).map((function(){return""}));for(var a=0;a<t.data.length;a++){h=[];var s=v(a);if(s||(p.isFormatXY()?s=i[e].data[a].x:p.isFormat2DArray()&&(s=i[e].data[a]?i[e].data[a][0]:"")),0===e){h.push((n=s,"datetime"===l.config.xaxis.type&&String(n).length>=10?l.config.chart.toolbar.export.csv.dateFormatter(s):x.isNumber(s)?s:s.split(r).join("")));for(var o=0;o<l.globals.series.length;o++)p.isFormatXY()?h.push(i[o].data[a].y):h.push(g[o][a])}("candlestick"===l.config.chart.type||t.type&&"candlestick"===t.type)&&(h.pop(),h.push(l.globals.seriesCandleO[e][a]),h.push(l.globals.seriesCandleH[e][a]),h.push(l.globals.seriesCandleL[e][a]),h.push(l.globals.seriesCandleC[e][a])),("boxPlot"===l.config.chart.type||t.type&&"boxPlot"===t.type)&&(h.pop(),h.push(l.globals.seriesCandleO[e][a]),h.push(l.globals.seriesCandleH[e][a]),h.push(l.globals.seriesCandleM[e][a]),h.push(l.globals.seriesCandleL[e][a]),h.push(l.globals.seriesCandleC[e][a])),"rangeBar"===l.config.chart.type&&(h.pop(),h.push(l.globals.seriesRangeStart[e][a]),h.push(l.globals.seriesRangeEnd[e][a])),h.length&&c.push(h.join(r))}}var n};h.push(l.config.chart.toolbar.export.csv.headerCategory),"boxPlot"===l.config.chart.type?(h.push("minimum"),h.push("q1"),h.push("median"),h.push("q3"),h.push("maximum")):"candlestick"===l.config.chart.type?(h.push("open"),h.push("high"),h.push("low"),h.push("close")):"rangeBar"===l.config.chart.type?(h.push("minimum"),h.push("maximum")):i.map((function(t,e){var i=t.name?t.name:"series-".concat(e);l.globals.axisCharts&&h.push(i.split(r).join("")?i.split(r).join(""):"series-".concat(e))})),l.globals.axisCharts||(h.push(l.config.chart.toolbar.export.csv.headerValue),c.push(h.join(r))),i.map((function(t,e){l.globals.axisCharts?m(t,e):((h=[]).push(l.globals.labels[e].split(r).join("")),h.push(g[e]),c.push(h.join(r)))})),d+=c.join(n),this.triggerDownload("data:text/csv; charset=utf-8,"+encodeURIComponent("\ufeff"+d),a||l.config.chart.toolbar.export.csv.filename,".csv")}},{key:"triggerDownload",value:function(t,e,i){var a=document.createElement("a");a.href=t,a.download=(e||this.w.globals.chartID)+i,document.body.appendChild(a),a.click(),document.body.removeChild(a)}}]),t}(),V=function(){function t(e,i){a(this,t),this.ctx=e,this.elgrid=i,this.w=e.w;var s=this.w;this.axesUtils=new B(e),this.xaxisLabels=s.globals.labels.slice(),s.globals.timescaleLabels.length>0&&!s.globals.isBarHorizontal&&(this.xaxisLabels=s.globals.timescaleLabels.slice()),s.config.xaxis.overwriteCategories&&(this.xaxisLabels=s.config.xaxis.overwriteCategories),this.drawnLabels=[],this.drawnLabelsRects=[],"top"===s.config.xaxis.position?this.offY=0:this.offY=s.globals.gridHeight+1,this.offY=this.offY+s.config.xaxis.axisBorder.offsetY,this.isCategoryBarHorizontal="bar"===s.config.chart.type&&s.config.plotOptions.bar.horizontal,this.xaxisFontSize=s.config.xaxis.labels.style.fontSize,this.xaxisFontFamily=s.config.xaxis.labels.style.fontFamily,this.xaxisForeColors=s.config.xaxis.labels.style.colors,this.xaxisBorderWidth=s.config.xaxis.axisBorder.width,this.isCategoryBarHorizontal&&(this.xaxisBorderWidth=s.config.yaxis[0].axisBorder.width.toString()),this.xaxisBorderWidth.indexOf("%")>-1?this.xaxisBorderWidth=s.globals.gridWidth*parseInt(this.xaxisBorderWidth,10)/100:this.xaxisBorderWidth=parseInt(this.xaxisBorderWidth,10),this.xaxisBorderHeight=s.config.xaxis.axisBorder.height,this.yaxis=s.config.yaxis[0]}return r(t,[{key:"drawXaxis",value:function(){var t=this.w,e=new m(this.ctx),i=e.group({class:"apexcharts-xaxis",transform:"translate(".concat(t.config.xaxis.offsetX,", ").concat(t.config.xaxis.offsetY,")")}),a=e.group({class:"apexcharts-xaxis-texts-g",transform:"translate(".concat(t.globals.translateXAxisX,", ").concat(t.globals.translateXAxisY,")")});i.add(a);for(var s=[],r=0;r<this.xaxisLabels.length;r++)s.push(this.xaxisLabels[r]);if(this.drawXAxisLabelAndGroup(!0,e,a,s,t.globals.isXNumeric,(function(t,e){return e})),t.globals.hasGroups){var o=t.globals.groups;s=[];for(var n=0;n<o.length;n++)s.push(o[n].title);var l={};t.config.xaxis.group.style&&(l.xaxisFontSize=t.config.xaxis.group.style.fontSize,l.xaxisFontFamily=t.config.xaxis.group.style.fontFamily,l.xaxisForeColors=t.config.xaxis.group.style.colors,l.fontWeight=t.config.xaxis.group.style.fontWeight,l.cssClass=t.config.xaxis.group.style.cssClass),this.drawXAxisLabelAndGroup(!1,e,a,s,!1,(function(t,e){return o[t].cols*e}),l)}if(void 0!==t.config.xaxis.title.text){var h=e.group({class:"apexcharts-xaxis-title"}),c=e.drawText({x:t.globals.gridWidth/2+t.config.xaxis.title.offsetX,y:this.offY+parseFloat(this.xaxisFontSize)+("bottom"===t.config.xaxis.position?t.globals.xAxisLabelsHeight:-t.globals.xAxisLabelsHeight-10)+t.config.xaxis.title.offsetY,text:t.config.xaxis.title.text,textAnchor:"middle",fontSize:t.config.xaxis.title.style.fontSize,fontFamily:t.config.xaxis.title.style.fontFamily,fontWeight:t.config.xaxis.title.style.fontWeight,foreColor:t.config.xaxis.title.style.color,cssClass:"apexcharts-xaxis-title-text "+t.config.xaxis.title.style.cssClass});h.add(c),i.add(h)}if(t.config.xaxis.axisBorder.show){var d=t.globals.barPadForNumericAxis,g=e.drawLine(t.globals.padHorizontal+t.config.xaxis.axisBorder.offsetX-d,this.offY,this.xaxisBorderWidth+d,this.offY,t.config.xaxis.axisBorder.color,0,this.xaxisBorderHeight);this.elgrid&&this.elgrid.elGridBorders?this.elgrid.elGridBorders.add(g):i.add(g)}return i}},{key:"drawXAxisLabelAndGroup",value:function(t,e,i,a,s,r){var o,n=this,l=arguments.length>6&&void 0!==arguments[6]?arguments[6]:{},h=[],c=[],d=this.w,g=l.xaxisFontSize||this.xaxisFontSize,u=l.xaxisFontFamily||this.xaxisFontFamily,f=l.xaxisForeColors||this.xaxisForeColors,p=l.fontWeight||d.config.xaxis.labels.style.fontWeight,x=l.cssClass||d.config.xaxis.labels.style.cssClass,b=d.globals.padHorizontal,v=a.length,m="category"===d.config.xaxis.type?d.globals.dataPoints:v;if(0===m&&v>m&&(m=v),s){var y=m>1?m-1:m;o=d.globals.gridWidth/y,b=b+r(0,o)/2+d.config.xaxis.labels.offsetX}else o=d.globals.gridWidth/m,b=b+r(0,o)+d.config.xaxis.labels.offsetX;for(var w=function(s){var l=b-r(s,o)/2+d.config.xaxis.labels.offsetX;0===s&&1===v&&o/2===b&&1===m&&(l=d.globals.gridWidth/2);var y=n.axesUtils.getLabel(a,d.globals.timescaleLabels,l,s,h,g,t),w=28;d.globals.rotateXLabels&&t&&(w=22),d.config.xaxis.title.text&&"top"===d.config.xaxis.position&&(w+=parseFloat(d.config.xaxis.title.style.fontSize)+2),t||(w=w+parseFloat(g)+(d.globals.xAxisLabelsHeight-d.globals.xAxisGroupLabelsHeight)+(d.globals.rotateXLabels?10:0)),y=void 0!==d.config.xaxis.tickAmount&&"dataPoints"!==d.config.xaxis.tickAmount&&"datetime"!==d.config.xaxis.type?n.axesUtils.checkLabelBasedOnTickamount(s,y,v):n.axesUtils.checkForOverflowingLabels(s,y,v,h,c);if(d.config.xaxis.labels.show){var k=e.drawText({x:y.x,y:n.offY+d.config.xaxis.labels.offsetY+w-("top"===d.config.xaxis.position?d.globals.xAxisHeight+d.config.xaxis.axisTicks.height-2:0),text:y.text,textAnchor:"middle",fontWeight:y.isBold?600:p,fontSize:g,fontFamily:u,foreColor:Array.isArray(f)?t&&d.config.xaxis.convertedCatToNumeric?f[d.globals.minX+s-1]:f[s]:f,isPlainText:!1,cssClass:(t?"apexcharts-xaxis-label ":"apexcharts-xaxis-group-label ")+x});if(i.add(k),k.on("click",(function(t){if("function"==typeof d.config.chart.events.xAxisLabelClick){var e=Object.assign({},d,{labelIndex:s});d.config.chart.events.xAxisLabelClick(t,n.ctx,e)}})),t){var A=document.createElementNS(d.globals.SVGNS,"title");A.textContent=Array.isArray(y.text)?y.text.join(" "):y.text,k.node.appendChild(A),""!==y.text&&(h.push(y.text),c.push(y))}}s<v-1&&(b+=r(s+1,o))},k=0;k<=v-1;k++)w(k)}},{key:"drawXaxisInversed",value:function(t){var e,i,a=this,s=this.w,r=new m(this.ctx),o=s.config.yaxis[0].opposite?s.globals.translateYAxisX[t]:0,n=r.group({class:"apexcharts-yaxis apexcharts-xaxis-inversed",rel:t}),l=r.group({class:"apexcharts-yaxis-texts-g apexcharts-xaxis-inversed-texts-g",transform:"translate("+o+", 0)"});n.add(l);var h=[];if(s.config.yaxis[t].show)for(var c=0;c<this.xaxisLabels.length;c++)h.push(this.xaxisLabels[c]);e=s.globals.gridHeight/h.length,i=-e/2.2;var d=s.globals.yLabelFormatters[0],g=s.config.yaxis[0].labels;if(g.show)for(var u=function(o){var n=void 0===h[o]?"":h[o];n=d(n,{seriesIndex:t,dataPointIndex:o,w:s});var c=a.axesUtils.getYAxisForeColor(g.style.colors,t),u=0;Array.isArray(n)&&(u=n.length/2*parseInt(g.style.fontSize,10));var f=g.offsetX-15,p="end";a.yaxis.opposite&&(p="start"),"left"===s.config.yaxis[0].labels.align?(f=g.offsetX,p="start"):"center"===s.config.yaxis[0].labels.align?(f=g.offsetX,p="middle"):"right"===s.config.yaxis[0].labels.align&&(p="end");var x=r.drawText({x:f,y:i+e+g.offsetY-u,text:n,textAnchor:p,foreColor:Array.isArray(c)?c[o]:c,fontSize:g.style.fontSize,fontFamily:g.style.fontFamily,fontWeight:g.style.fontWeight,isPlainText:!1,cssClass:"apexcharts-yaxis-label "+g.style.cssClass,maxWidth:g.maxWidth});l.add(x),x.on("click",(function(t){if("function"==typeof s.config.chart.events.xAxisLabelClick){var e=Object.assign({},s,{labelIndex:o});s.config.chart.events.xAxisLabelClick(t,a.ctx,e)}}));var b=document.createElementNS(s.globals.SVGNS,"title");if(b.textContent=Array.isArray(n)?n.join(" "):n,x.node.appendChild(b),0!==s.config.yaxis[t].labels.rotate){var v=r.rotateAroundCenter(x.node);x.node.setAttribute("transform","rotate(".concat(s.config.yaxis[t].labels.rotate," 0 ").concat(v.y,")"))}i+=e},f=0;f<=h.length-1;f++)u(f);if(void 0!==s.config.yaxis[0].title.text){var p=r.group({class:"apexcharts-yaxis-title apexcharts-xaxis-title-inversed",transform:"translate("+o+", 0)"}),x=r.drawText({x:s.config.yaxis[0].title.offsetX,y:s.globals.gridHeight/2+s.config.yaxis[0].title.offsetY,text:s.config.yaxis[0].title.text,textAnchor:"middle",foreColor:s.config.yaxis[0].title.style.color,fontSize:s.config.yaxis[0].title.style.fontSize,fontWeight:s.config.yaxis[0].title.style.fontWeight,fontFamily:s.config.yaxis[0].title.style.fontFamily,cssClass:"apexcharts-yaxis-title-text "+s.config.yaxis[0].title.style.cssClass});p.add(x),n.add(p)}var b=0;this.isCategoryBarHorizontal&&s.config.yaxis[0].opposite&&(b=s.globals.gridWidth);var v=s.config.xaxis.axisBorder;if(v.show){var y=r.drawLine(s.globals.padHorizontal+v.offsetX+b,1+v.offsetY,s.globals.padHorizontal+v.offsetX+b,s.globals.gridHeight+v.offsetY,v.color,0);this.elgrid&&this.elgrid.elGridBorders?this.elgrid.elGridBorders.add(y):n.add(y)}return s.config.yaxis[0].axisTicks.show&&this.axesUtils.drawYAxisTicks(b,h.length,s.config.yaxis[0].axisBorder,s.config.yaxis[0].axisTicks,0,e,n),n}},{key:"drawXaxisTicks",value:function(t,e,i){var a=this.w,s=t;if(!(t<0||t-2>a.globals.gridWidth)){var r=this.offY+a.config.xaxis.axisTicks.offsetY;if(e=e+r+a.config.xaxis.axisTicks.height,"top"===a.config.xaxis.position&&(e=r-a.config.xaxis.axisTicks.height),a.config.xaxis.axisTicks.show){var o=new m(this.ctx).drawLine(t+a.config.xaxis.axisTicks.offsetX,r+a.config.xaxis.offsetY,s+a.config.xaxis.axisTicks.offsetX,e+a.config.xaxis.offsetY,a.config.xaxis.axisTicks.color);i.add(o),o.node.classList.add("apexcharts-xaxis-tick")}}}},{key:"getXAxisTicksPositions",value:function(){var t=this.w,e=[],i=this.xaxisLabels.length,a=t.globals.padHorizontal;if(t.globals.timescaleLabels.length>0)for(var s=0;s<i;s++)a=this.xaxisLabels[s].position,e.push(a);else for(var r=i,o=0;o<r;o++){var n=r;t.globals.isXNumeric&&"bar"!==t.config.chart.type&&(n-=1),a+=t.globals.gridWidth/n,e.push(a)}return e}},{key:"xAxisLabelCorrections",value:function(){var t=this.w,e=new m(this.ctx),i=t.globals.dom.baseEl.querySelector(".apexcharts-xaxis-texts-g"),a=t.globals.dom.baseEl.querySelectorAll(".apexcharts-xaxis-texts-g text:not(.apexcharts-xaxis-group-label)"),s=t.globals.dom.baseEl.querySelectorAll(".apexcharts-yaxis-inversed text"),r=t.globals.dom.baseEl.querySelectorAll(".apexcharts-xaxis-inversed-texts-g text tspan");if(t.globals.rotateXLabels||t.config.xaxis.labels.rotateAlways)for(var o=0;o<a.length;o++){var n=e.rotateAroundCenter(a[o]);n.y=n.y-1,n.x=n.x+1,a[o].setAttribute("transform","rotate(".concat(t.config.xaxis.labels.rotate," ").concat(n.x," ").concat(n.y,")")),a[o].setAttribute("text-anchor","end");i.setAttribute("transform","translate(0, ".concat(-10,")"));var l=a[o].childNodes;t.config.xaxis.labels.trim&&Array.prototype.forEach.call(l,(function(i){e.placeTextWithEllipsis(i,i.textContent,t.globals.xAxisLabelsHeight-("bottom"===t.config.legend.position?20:10))}))}else!function(){for(var i=t.globals.gridWidth/(t.globals.labels.length+1),s=0;s<a.length;s++){var r=a[s].childNodes;t.config.xaxis.labels.trim&&"datetime"!==t.config.xaxis.type&&Array.prototype.forEach.call(r,(function(t){e.placeTextWithEllipsis(t,t.textContent,i)}))}}();if(s.length>0){var h=s[s.length-1].getBBox(),c=s[0].getBBox();h.x<-20&&s[s.length-1].parentNode.removeChild(s[s.length-1]),c.x+c.width>t.globals.gridWidth&&!t.globals.isBarHorizontal&&s[0].parentNode.removeChild(s[0]);for(var d=0;d<r.length;d++)e.placeTextWithEllipsis(r[d],r[d].textContent,t.config.yaxis[0].labels.maxWidth-(t.config.yaxis[0].title.text?2*parseFloat(t.config.yaxis[0].title.style.fontSize):0)-15)}}}]),t}(),j=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w;var i=this.w;this.xaxisLabels=i.globals.labels.slice(),this.axesUtils=new B(e),this.isRangeBar=i.globals.seriesRange.length,i.globals.timescaleLabels.length>0&&(this.xaxisLabels=i.globals.timescaleLabels.slice())}return r(t,[{key:"drawGridArea",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,e=this.w,i=new m(this.ctx);null===t&&(t=i.group({class:"apexcharts-grid"}));var a=i.drawLine(e.globals.padHorizontal,1,e.globals.padHorizontal,e.globals.gridHeight,"transparent"),s=i.drawLine(e.globals.padHorizontal,e.globals.gridHeight,e.globals.gridWidth,e.globals.gridHeight,"transparent");return t.add(s),t.add(a),t}},{key:"drawGrid",value:function(){var t=null;return this.w.globals.axisCharts&&(t=this.renderGrid(),this.drawGridArea(t.el)),t}},{key:"createGridMask",value:function(){var t=this.w,e=t.globals,i=new m(this.ctx),a=Array.isArray(t.config.stroke.width)?0:t.config.stroke.width;if(Array.isArray(t.config.stroke.width)){var s=0;t.config.stroke.width.forEach((function(t){s=Math.max(s,t)})),a=s}e.dom.elGridRectMask=document.createElementNS(e.SVGNS,"clipPath"),e.dom.elGridRectMask.setAttribute("id","gridRectMask".concat(e.cuid)),e.dom.elGridRectMarkerMask=document.createElementNS(e.SVGNS,"clipPath"),e.dom.elGridRectMarkerMask.setAttribute("id","gridRectMarkerMask".concat(e.cuid)),e.dom.elForecastMask=document.createElementNS(e.SVGNS,"clipPath"),e.dom.elForecastMask.setAttribute("id","forecastMask".concat(e.cuid)),e.dom.elNonForecastMask=document.createElementNS(e.SVGNS,"clipPath"),e.dom.elNonForecastMask.setAttribute("id","nonForecastMask".concat(e.cuid));var r=t.config.chart.type,o=0,n=0;("bar"===r||"rangeBar"===r||"candlestick"===r||"boxPlot"===r||t.globals.comboBarCount>0)&&t.globals.isXNumeric&&!t.globals.isBarHorizontal&&(o=t.config.grid.padding.left,n=t.config.grid.padding.right,e.barPadForNumericAxis>o&&(o=e.barPadForNumericAxis,n=e.barPadForNumericAxis)),e.dom.elGridRect=i.drawRect(-a/2-o-2,-a/2,e.gridWidth+a+n+o+4,e.gridHeight+a,0,"#fff");var l=t.globals.markers.largestSize+1;e.dom.elGridRectMarker=i.drawRect(2*-l,2*-l,e.gridWidth+4*l,e.gridHeight+4*l,0,"#fff"),e.dom.elGridRectMask.appendChild(e.dom.elGridRect.node),e.dom.elGridRectMarkerMask.appendChild(e.dom.elGridRectMarker.node);var h=e.dom.baseEl.querySelector("defs");h.appendChild(e.dom.elGridRectMask),h.appendChild(e.dom.elForecastMask),h.appendChild(e.dom.elNonForecastMask),h.appendChild(e.dom.elGridRectMarkerMask)}},{key:"_drawGridLines",value:function(t){var e=t.i,i=t.x1,a=t.y1,s=t.x2,r=t.y2,o=t.xCount,n=t.parent,l=this.w;if(!(0===e&&l.globals.skipFirstTimelinelabel||e===o-1&&l.globals.skipLastTimelinelabel&&!l.config.xaxis.labels.formatter||"radar"===l.config.chart.type)){l.config.grid.xaxis.lines.show&&this._drawGridLine({i:e,x1:i,y1:a,x2:s,y2:r,xCount:o,parent:n});var h=0;if(l.globals.hasGroups&&"between"===l.config.xaxis.tickPlacement){var c=l.globals.groups;if(c){for(var d=0,g=0;d<e&&g<c.length;g++)d+=c[g].cols;d===e&&(h=.6*l.globals.xAxisLabelsHeight)}}new V(this.ctx).drawXaxisTicks(i,h,l.globals.dom.elGraphical)}}},{key:"_drawGridLine",value:function(t){var e=t.i,i=t.x1,a=t.y1,s=t.x2,r=t.y2,o=t.xCount,n=t.parent,l=this.w,h=!1,c=n.node.classList.contains("apexcharts-gridlines-horizontal"),d=l.config.grid.strokeDashArray,g=l.globals.barPadForNumericAxis;(0===a&&0===r||0===i&&0===s)&&(h=!0),a===l.globals.gridHeight&&r===l.globals.gridHeight&&(h=!0),!l.globals.isBarHorizontal||0!==e&&e!==o-1||(h=!0);var u=new m(this).drawLine(i-(c?g:0),a,s+(c?g:0),r,l.config.grid.borderColor,d);u.node.classList.add("apexcharts-gridline"),h?this.elGridBorders.add(u):n.add(u)}},{key:"_drawGridBandRect",value:function(t){var e=t.c,i=t.x1,a=t.y1,s=t.x2,r=t.y2,o=t.type,n=this.w,l=new m(this.ctx),h=n.globals.barPadForNumericAxis;if("column"!==o||"datetime"!==n.config.xaxis.type){var c=n.config.grid[o].colors[e],d=l.drawRect(i-("row"===o?h:0),a,s+("row"===o?2*h:0),r,0,c,n.config.grid[o].opacity);this.elg.add(d),d.attr("clip-path","url(#gridRectMask".concat(n.globals.cuid,")")),d.node.classList.add("apexcharts-grid-".concat(o))}}},{key:"_drawXYLines",value:function(t){var e=this,i=t.xCount,a=t.tickAmount,s=this.w;if(s.config.grid.xaxis.lines.show||s.config.xaxis.axisTicks.show){var r,o=s.globals.padHorizontal,n=s.globals.gridHeight;s.globals.timescaleLabels.length?function(t){for(var a=t.xC,s=t.x1,r=t.y1,o=t.x2,n=t.y2,l=0;l<a;l++)s=e.xaxisLabels[l].position,o=e.xaxisLabels[l].position,e._drawGridLines({i:l,x1:s,y1:r,x2:o,y2:n,xCount:i,parent:e.elgridLinesV})}({xC:i,x1:o,y1:0,x2:r,y2:n}):(s.globals.isXNumeric&&(i=s.globals.xAxisScale.result.length),function(t){for(var a=t.xC,r=t.x1,o=t.y1,n=t.x2,l=t.y2,h=0;h<a+(s.globals.isXNumeric?0:1);h++)0===h&&1===a&&1===s.globals.dataPoints&&(n=r=s.globals.gridWidth/2),e._drawGridLines({i:h,x1:r,y1:o,x2:n,y2:l,xCount:i,parent:e.elgridLinesV}),n=r+=s.globals.gridWidth/(s.globals.isXNumeric?a-1:a)}({xC:i,x1:o,y1:0,x2:r,y2:n}))}if(s.config.grid.yaxis.lines.show){var l=0,h=0,c=s.globals.gridWidth,d=a+1;this.isRangeBar&&(d=s.globals.labels.length);for(var g=0;g<d+(this.isRangeBar?1:0);g++)this._drawGridLine({i:g,xCount:d+(this.isRangeBar?1:0),x1:0,y1:l,x2:c,y2:h,parent:this.elgridLinesH}),h=l+=s.globals.gridHeight/(this.isRangeBar?d:a)}}},{key:"_drawInvertedXYLines",value:function(t){var e=t.xCount,i=this.w;if(i.config.grid.xaxis.lines.show||i.config.xaxis.axisTicks.show)for(var a,s=i.globals.padHorizontal,r=i.globals.gridHeight,o=0;o<e+1;o++){i.config.grid.xaxis.lines.show&&this._drawGridLine({i:o,xCount:e+1,x1:s,y1:0,x2:a,y2:r,parent:this.elgridLinesV}),new V(this.ctx).drawXaxisTicks(s,0,i.globals.dom.elGraphical),a=s=s+i.globals.gridWidth/e+.3}if(i.config.grid.yaxis.lines.show)for(var n=0,l=0,h=i.globals.gridWidth,c=0;c<i.globals.dataPoints+1;c++)this._drawGridLine({i:c,xCount:i.globals.dataPoints+1,x1:0,y1:n,x2:h,y2:l,parent:this.elgridLinesH}),l=n+=i.globals.gridHeight/i.globals.dataPoints}},{key:"renderGrid",value:function(){var t=this.w,e=new m(this.ctx);this.elg=e.group({class:"apexcharts-grid"}),this.elgridLinesH=e.group({class:"apexcharts-gridlines-horizontal"}),this.elgridLinesV=e.group({class:"apexcharts-gridlines-vertical"}),this.elGridBorders=e.group({class:"apexcharts-grid-borders"}),this.elg.add(this.elgridLinesH),this.elg.add(this.elgridLinesV),t.config.grid.show||(this.elgridLinesV.hide(),this.elgridLinesH.hide(),this.elGridBorders.hide());for(var i,a=t.globals.yAxisScale.length?t.globals.yAxisScale[0].result.length-1:5,s=0;s<t.globals.series.length&&(void 0!==t.globals.yAxisScale[s]&&(a=t.globals.yAxisScale[s].result.length-1),!(a>2));s++);return!t.globals.isBarHorizontal||this.isRangeBar?(i=this.xaxisLabels.length,this.isRangeBar&&(a=t.globals.labels.length,t.config.xaxis.tickAmount&&t.config.xaxis.labels.formatter&&(i=t.config.xaxis.tickAmount)),this._drawXYLines({xCount:i,tickAmount:a})):(i=a,a=t.globals.xTickAmount,this._drawInvertedXYLines({xCount:i,tickAmount:a})),this.drawGridBands(i,a),{el:this.elg,elGridBorders:this.elGridBorders,xAxisTickWidth:t.globals.gridWidth/i}}},{key:"drawGridBands",value:function(t,e){var i=this.w;if(void 0!==i.config.grid.row.colors&&i.config.grid.row.colors.length>0)for(var a=0,s=i.globals.gridHeight/e,r=i.globals.gridWidth,o=0,n=0;o<e;o++,n++)n>=i.config.grid.row.colors.length&&(n=0),this._drawGridBandRect({c:n,x1:0,y1:a,x2:r,y2:s,type:"row"}),a+=i.globals.gridHeight/e;if(void 0!==i.config.grid.column.colors&&i.config.grid.column.colors.length>0)for(var l=i.globals.isBarHorizontal||"category"!==i.config.xaxis.type&&!i.config.xaxis.convertedCatToNumeric?t:t-1,h=i.globals.padHorizontal,c=i.globals.padHorizontal+i.globals.gridWidth/l,d=i.globals.gridHeight,g=0,u=0;g<t;g++,u++)u>=i.config.grid.column.colors.length&&(u=0),this._drawGridBandRect({c:u,x1:h,y1:0,x2:c,y2:d,type:"column"}),h+=i.globals.gridWidth/l}}]),t}(),_=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"niceScale",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:10,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:0,s=arguments.length>4?arguments[4]:void 0,r=this.w,o=Math.abs(e-t);if("dataPoints"===(i=this._adjustTicksForSmallRange(i,a,o))&&(i=r.globals.dataPoints-1),t===Number.MIN_VALUE&&0===e||!x.isNumber(t)&&!x.isNumber(e)||t===Number.MIN_VALUE&&e===-Number.MAX_VALUE){t=0,e=i;var n=this.linearScale(t,e,i);return n}t>e?(console.warn("axis.min cannot be greater than axis.max"),e=t+.1):t===e&&(t=0===t?0:t-.5,e=0===e?2:e+.5);var l=[];o<1&&s&&("candlestick"===r.config.chart.type||"candlestick"===r.config.series[a].type||"boxPlot"===r.config.chart.type||"boxPlot"===r.config.series[a].type||r.globals.isRangeData)&&(e*=1.01);var h=i+1;h<2?h=2:h>2&&(h-=2);var c=o/h,d=Math.floor(x.log10(c)),g=Math.pow(10,d),u=Math.round(c/g);u<1&&(u=1);var f=u*g,p=f*Math.floor(t/f),b=f*Math.ceil(e/f),v=p;if(s&&o>2){for(;l.push(v),!((v+=f)>b););return{result:l,niceMin:l[0],niceMax:l[l.length-1]}}var m=t;(l=[]).push(m);for(var y=Math.abs(e-t)/i,w=0;w<=i;w++)m+=y,l.push(m);return l[l.length-2]>=e&&l.pop(),{result:l,niceMin:l[0],niceMax:l[l.length-1]}}},{key:"linearScale",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:10,a=arguments.length>3?arguments[3]:void 0,s=Math.abs(e-t);"dataPoints"===(i=this._adjustTicksForSmallRange(i,a,s))&&(i=this.w.globals.dataPoints-1);var r=s/i;i===Number.MAX_VALUE&&(i=10,r=1);for(var o=[],n=t;i>=0;)o.push(n),n+=r,i-=1;return{result:o,niceMin:o[0],niceMax:o[o.length-1]}}},{key:"logarithmicScaleNice",value:function(t,e,i){e<=0&&(e=Math.max(t,i)),t<=0&&(t=Math.min(e,i));for(var a=[],s=Math.ceil(Math.log(e)/Math.log(i)+1),r=Math.floor(Math.log(t)/Math.log(i));r<s;r++)a.push(Math.pow(i,r));return{result:a,niceMin:a[0],niceMax:a[a.length-1]}}},{key:"logarithmicScale",value:function(t,e,i){e<=0&&(e=Math.max(t,i)),t<=0&&(t=Math.min(e,i));for(var a=[],s=Math.log(e)/Math.log(i),r=Math.log(t)/Math.log(i),o=s-r,n=Math.round(o),l=o/n,h=0,c=r;h<n;h++,c+=l)a.push(Math.pow(i,c));return a.push(Math.pow(i,s)),{result:a,niceMin:t,niceMax:e}}},{key:"_adjustTicksForSmallRange",value:function(t,e,i){var a=t;if(void 0!==e&&this.w.config.yaxis[e].labels.formatter&&void 0===this.w.config.yaxis[e].tickAmount){var s=Number(this.w.config.yaxis[e].labels.formatter(1));x.isNumber(s)&&0===this.w.globals.yValueDecimal&&(a=Math.ceil(i))}return a<t?a:t}},{key:"setYScaleForIndex",value:function(t,e,i){var a=this.w.globals,s=this.w.config,r=a.isBarHorizontal?s.xaxis:s.yaxis[t];void 0===a.yAxisScale[t]&&(a.yAxisScale[t]=[]);var o=Math.abs(i-e);if(r.logarithmic&&o<=5&&(a.invalidLogScale=!0),r.logarithmic&&o>5)a.allSeriesCollapsed=!1,a.yAxisScale[t]=this.logarithmicScale(e,i,r.logBase),a.yAxisScale[t]=r.forceNiceScale?this.logarithmicScaleNice(e,i,r.logBase):this.logarithmicScale(e,i,r.logBase);else if(i!==-Number.MAX_VALUE&&x.isNumber(i))if(a.allSeriesCollapsed=!1,void 0===r.min&&void 0===r.max||r.forceNiceScale){var n=void 0===s.yaxis[t].max&&void 0===s.yaxis[t].min||s.yaxis[t].forceNiceScale;a.yAxisScale[t]=this.niceScale(e,i,r.tickAmount?r.tickAmount:o<5&&o>1?o+1:5,t,n)}else a.yAxisScale[t]=this.linearScale(e,i,r.tickAmount,t);else a.yAxisScale[t]=this.linearScale(0,5,5)}},{key:"setXScale",value:function(t,e){var i=this.w,a=i.globals,s=i.config.xaxis,r=Math.abs(e-t);return e!==-Number.MAX_VALUE&&x.isNumber(e)?a.xAxisScale=this.linearScale(t,e,s.tickAmount?s.tickAmount:r<5&&r>1?r+1:5,0):a.xAxisScale=this.linearScale(0,5,5),a.xAxisScale}},{key:"setMultipleYScales",value:function(){var t=this,e=this.w.globals,i=this.w.config,a=e.minYArr.concat([]),s=e.maxYArr.concat([]),r=[];i.yaxis.forEach((function(e,o){var n=o;i.series.forEach((function(t,i){t.name===e.seriesName&&(n=i,o!==i?r.push({index:i,similarIndex:o,alreadyExists:!0}):r.push({index:i}))}));var l=a[n],h=s[n];t.setYScaleForIndex(o,l,h)})),this.sameScaleInMultipleAxes(a,s,r)}},{key:"sameScaleInMultipleAxes",value:function(t,e,i){var a=this,s=this.w.config,r=this.w.globals,o=[];i.forEach((function(t){t.alreadyExists&&(void 0===o[t.index]&&(o[t.index]=[]),o[t.index].push(t.index),o[t.index].push(t.similarIndex))})),r.yAxisSameScaleIndices=o,o.forEach((function(t,e){o.forEach((function(i,a){var s,r;e!==a&&(s=t,r=i,s.filter((function(t){return-1!==r.indexOf(t)}))).length>0&&(o[e]=o[e].concat(o[a]))}))}));var n=o.map((function(t){return t.filter((function(e,i){return t.indexOf(e)===i}))})).map((function(t){return t.sort()}));o=o.filter((function(t){return!!t}));var l=n.slice(),h=l.map((function(t){return JSON.stringify(t)}));l=l.filter((function(t,e){return h.indexOf(JSON.stringify(t))===e}));var c=[],d=[];t.forEach((function(t,i){l.forEach((function(a,s){a.indexOf(i)>-1&&(void 0===c[s]&&(c[s]=[],d[s]=[]),c[s].push({key:i,value:t}),d[s].push({key:i,value:e[i]}))}))}));var g=Array.apply(null,Array(l.length)).map(Number.prototype.valueOf,Number.MIN_VALUE),u=Array.apply(null,Array(l.length)).map(Number.prototype.valueOf,-Number.MAX_VALUE);c.forEach((function(t,e){t.forEach((function(t,i){g[e]=Math.min(t.value,g[e])}))})),d.forEach((function(t,e){t.forEach((function(t,i){u[e]=Math.max(t.value,u[e])}))})),t.forEach((function(t,e){d.forEach((function(t,i){var o=g[i],n=u[i];s.chart.stacked&&(n=0,t.forEach((function(t,e){t.value!==-Number.MAX_VALUE&&(n+=t.value),o!==Number.MIN_VALUE&&(o+=c[i][e].value)}))),t.forEach((function(i,l){t[l].key===e&&(void 0!==s.yaxis[e].min&&(o="function"==typeof s.yaxis[e].min?s.yaxis[e].min(r.minY):s.yaxis[e].min),void 0!==s.yaxis[e].max&&(n="function"==typeof s.yaxis[e].max?s.yaxis[e].max(r.maxY):s.yaxis[e].max),a.setYScaleForIndex(e,o,n))}))}))}))}},{key:"autoScaleY",value:function(t,e,i){t||(t=this);var a=t.w;if(a.globals.isMultipleYAxis||a.globals.collapsedSeries.length)return console.warn("autoScaleYaxis is not supported in a multi-yaxis chart."),e;var s=a.globals.seriesX[0],r=a.config.chart.stacked;return e.forEach((function(t,o){for(var n=0,l=0;l<s.length;l++)if(s[l]>=i.xaxis.min){n=l;break}var h,c,d=a.globals.minYArr[o],g=a.globals.maxYArr[o],u=a.globals.stackedSeriesTotals;a.globals.series.forEach((function(o,l){var f=o[n];r?(f=u[n],h=c=f,u.forEach((function(t,e){s[e]<=i.xaxis.max&&s[e]>=i.xaxis.min&&(t>c&&null!==t&&(c=t),o[e]<h&&null!==o[e]&&(h=o[e]))}))):(h=c=f,o.forEach((function(t,e){if(s[e]<=i.xaxis.max&&s[e]>=i.xaxis.min){var r=t,o=t;a.globals.series.forEach((function(i,a){null!==t&&(r=Math.min(i[e],r),o=Math.max(i[e],o))})),o>c&&null!==o&&(c=o),r<h&&null!==r&&(h=r)}}))),void 0===h&&void 0===c&&(h=d,c=g),c*=c<0?.9:1.1,0===(h*=h<0?1.1:.9)&&0===c&&(h=-1,c=1),c<0&&c<g&&(c=g),h<0&&h>d&&(h=d),e.length>1?(e[l].min=void 0===t.min?h:t.min,e[l].max=void 0===t.max?c:t.max):(e[0].min=void 0===t.min?h:t.min,e[0].max=void 0===t.max?c:t.max)}))})),e}}]),t}(),U=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.scales=new _(e)}return r(t,[{key:"init",value:function(){this.setYRange(),this.setXRange(),this.setZRange()}},{key:"getMinYMaxY",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:Number.MAX_VALUE,i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:-Number.MAX_VALUE,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null,s=this.w.config,r=this.w.globals,o=-Number.MAX_VALUE,n=Number.MIN_VALUE;null===a&&(a=t+1);var l=r.series,h=l,c=l;"candlestick"===s.chart.type?(h=r.seriesCandleL,c=r.seriesCandleH):"boxPlot"===s.chart.type?(h=r.seriesCandleO,c=r.seriesCandleC):r.isRangeData&&(h=r.seriesRangeStart,c=r.seriesRangeEnd);for(var d=t;d<a;d++){r.dataPoints=Math.max(r.dataPoints,l[d].length),r.categoryLabels.length&&(r.dataPoints=r.categoryLabels.filter((function(t){return void 0!==t})).length);for(var g=0;g<r.series[d].length;g++){var u=l[d][g];null!==u&&x.isNumber(u)?(void 0!==c[d][g]&&(o=Math.max(o,c[d][g]),e=Math.min(e,c[d][g])),void 0!==h[d][g]&&(e=Math.min(e,h[d][g]),i=Math.max(i,h[d][g])),"candlestick"!==this.w.config.chart.type&&"boxPlot"!==this.w.config.chart.type&&"rangeArea"===this.w.config.chart.type&&"rangeBar"===this.w.config.chart.type||("candlestick"!==this.w.config.chart.type&&"boxPlot"!==this.w.config.chart.type||void 0!==r.seriesCandleC[d][g]&&(o=Math.max(o,r.seriesCandleO[d][g]),o=Math.max(o,r.seriesCandleH[d][g]),o=Math.max(o,r.seriesCandleL[d][g]),o=Math.max(o,r.seriesCandleC[d][g]),"boxPlot"===this.w.config.chart.type&&(o=Math.max(o,r.seriesCandleM[d][g]))),!s.series[d].type||"candlestick"===s.series[d].type&&"boxPlot"===s.series[d].type&&"rangeArea"===s.series[d].type&&"rangeBar"===s.series[d].type||(o=Math.max(o,r.series[d][g]),e=Math.min(e,r.series[d][g])),i=o),r.seriesGoals[d]&&r.seriesGoals[d][g]&&Array.isArray(r.seriesGoals[d][g])&&r.seriesGoals[d][g].forEach((function(t){n!==Number.MIN_VALUE&&(n=Math.min(n,t.value),e=n),o=Math.max(o,t.value),i=o})),x.isFloat(u)&&(u=x.noExponents(u),r.yValueDecimal=Math.max(r.yValueDecimal,u.toString().split(".")[1].length)),n>h[d][g]&&h[d][g]<0&&(n=h[d][g])):r.hasNullValues=!0}}return"rangeBar"===s.chart.type&&r.seriesRangeStart.length&&r.isBarHorizontal&&(n=e),"bar"===s.chart.type&&(n<0&&o<0&&(o=0),n===Number.MIN_VALUE&&(n=0)),{minY:n,maxY:o,lowestY:e,highestY:i}}},{key:"setYRange",value:function(){var t=this.w.globals,e=this.w.config;t.maxY=-Number.MAX_VALUE,t.minY=Number.MIN_VALUE;var i=Number.MAX_VALUE;if(t.isMultipleYAxis)for(var a=0;a<t.series.length;a++){var s=this.getMinYMaxY(a,i,null,a+1);t.minYArr.push(s.minY),t.maxYArr.push(s.maxY),i=s.lowestY}var r=this.getMinYMaxY(0,i,null,t.series.length);if(t.minY=r.minY,t.maxY=r.maxY,i=r.lowestY,e.chart.stacked&&this._setStackedMinMax(),("line"===e.chart.type||"area"===e.chart.type||"candlestick"===e.chart.type||"boxPlot"===e.chart.type||"rangeBar"===e.chart.type&&!t.isBarHorizontal)&&t.minY===Number.MIN_VALUE&&i!==-Number.MAX_VALUE&&i!==t.maxY){var o=t.maxY-i;(i>=0&&i<=10||void 0!==e.yaxis[0].min||void 0!==e.yaxis[0].max)&&(o=0),t.minY=i-5*o/100,i>0&&t.minY<0&&(t.minY=0),t.maxY=t.maxY+5*o/100}if(e.yaxis.forEach((function(e,i){void 0!==e.max&&("number"==typeof e.max?t.maxYArr[i]=e.max:"function"==typeof e.max&&(t.maxYArr[i]=e.max(t.isMultipleYAxis?t.maxYArr[i]:t.maxY)),t.maxY=t.maxYArr[i]),void 0!==e.min&&("number"==typeof e.min?t.minYArr[i]=e.min:"function"==typeof e.min&&(t.minYArr[i]=e.min(t.isMultipleYAxis?t.minYArr[i]===Number.MIN_VALUE?0:t.minYArr[i]:t.minY)),t.minY=t.minYArr[i])})),t.isBarHorizontal){["min","max"].forEach((function(i){void 0!==e.xaxis[i]&&"number"==typeof e.xaxis[i]&&("min"===i?t.minY=e.xaxis[i]:t.maxY=e.xaxis[i])}))}return t.isMultipleYAxis?(this.scales.setMultipleYScales(),t.minY=i,t.yAxisScale.forEach((function(e,i){t.minYArr[i]=e.niceMin,t.maxYArr[i]=e.niceMax}))):(this.scales.setYScaleForIndex(0,t.minY,t.maxY),t.minY=t.yAxisScale[0].niceMin,t.maxY=t.yAxisScale[0].niceMax,t.minYArr[0]=t.yAxisScale[0].niceMin,t.maxYArr[0]=t.yAxisScale[0].niceMax),{minY:t.minY,maxY:t.maxY,minYArr:t.minYArr,maxYArr:t.maxYArr,yAxisScale:t.yAxisScale}}},{key:"setXRange",value:function(){var t=this.w.globals,e=this.w.config,i="numeric"===e.xaxis.type||"datetime"===e.xaxis.type||"category"===e.xaxis.type&&!t.noLabelsProvided||t.noLabelsProvided||t.isXNumeric;if(t.isXNumeric&&function(){for(var e=0;e<t.series.length;e++)if(t.labels[e])for(var i=0;i<t.labels[e].length;i++)null!==t.labels[e][i]&&x.isNumber(t.labels[e][i])&&(t.maxX=Math.max(t.maxX,t.labels[e][i]),t.initialMaxX=Math.max(t.maxX,t.labels[e][i]),t.minX=Math.min(t.minX,t.labels[e][i]),t.initialMinX=Math.min(t.minX,t.labels[e][i]))}(),t.noLabelsProvided&&0===e.xaxis.categories.length&&(t.maxX=t.labels[t.labels.length-1],t.initialMaxX=t.labels[t.labels.length-1],t.minX=1,t.initialMinX=1),t.isXNumeric||t.noLabelsProvided||t.dataFormatXNumeric){var a;if(void 0===e.xaxis.tickAmount?(a=Math.round(t.svgWidth/150),"numeric"===e.xaxis.type&&t.dataPoints<30&&(a=t.dataPoints-1),a>t.dataPoints&&0!==t.dataPoints&&(a=t.dataPoints-1)):"dataPoints"===e.xaxis.tickAmount?(t.series.length>1&&(a=t.series[t.maxValsInArrayIndex].length-1),t.isXNumeric&&(a=t.maxX-t.minX-1)):a=e.xaxis.tickAmount,t.xTickAmount=a,void 0!==e.xaxis.max&&"number"==typeof e.xaxis.max&&(t.maxX=e.xaxis.max),void 0!==e.xaxis.min&&"number"==typeof e.xaxis.min&&(t.minX=e.xaxis.min),void 0!==e.xaxis.range&&(t.minX=t.maxX-e.xaxis.range),t.minX!==Number.MAX_VALUE&&t.maxX!==-Number.MAX_VALUE)if(e.xaxis.convertedCatToNumeric&&!t.dataFormatXNumeric){for(var s=[],r=t.minX-1;r<t.maxX;r++)s.push(r+1);t.xAxisScale={result:s,niceMin:s[0],niceMax:s[s.length-1]}}else t.xAxisScale=this.scales.setXScale(t.minX,t.maxX);else t.xAxisScale=this.scales.linearScale(1,a,a),t.noLabelsProvided&&t.labels.length>0&&(t.xAxisScale=this.scales.linearScale(1,t.labels.length,a-1),t.seriesX=t.labels.slice());i&&(t.labels=t.xAxisScale.result.slice())}return t.isBarHorizontal&&t.labels.length&&(t.xTickAmount=t.labels.length),this._handleSingleDataPoint(),this._getMinXDiff(),{minX:t.minX,maxX:t.maxX}}},{key:"setZRange",value:function(){var t=this.w.globals;if(t.isDataXYZ)for(var e=0;e<t.series.length;e++)if(void 0!==t.seriesZ[e])for(var i=0;i<t.seriesZ[e].length;i++)null!==t.seriesZ[e][i]&&x.isNumber(t.seriesZ[e][i])&&(t.maxZ=Math.max(t.maxZ,t.seriesZ[e][i]),t.minZ=Math.min(t.minZ,t.seriesZ[e][i]))}},{key:"_handleSingleDataPoint",value:function(){var t=this.w.globals,e=this.w.config;if(t.minX===t.maxX){var i=new T(this.ctx);if("datetime"===e.xaxis.type){var a=i.getDate(t.minX);e.xaxis.labels.datetimeUTC?a.setUTCDate(a.getUTCDate()-2):a.setDate(a.getDate()-2),t.minX=new Date(a).getTime();var s=i.getDate(t.maxX);e.xaxis.labels.datetimeUTC?s.setUTCDate(s.getUTCDate()+2):s.setDate(s.getDate()+2),t.maxX=new Date(s).getTime()}else("numeric"===e.xaxis.type||"category"===e.xaxis.type&&!t.noLabelsProvided)&&(t.minX=t.minX-2,t.initialMinX=t.minX,t.maxX=t.maxX+2,t.initialMaxX=t.maxX)}}},{key:"_getMinXDiff",value:function(){var t=this.w.globals;t.isXNumeric&&t.seriesX.forEach((function(e,i){1===e.length&&e.push(t.seriesX[t.maxValsInArrayIndex][t.seriesX[t.maxValsInArrayIndex].length-1]);var a=e.slice();a.sort((function(t,e){return t-e})),a.forEach((function(e,i){if(i>0){var s=e-a[i-1];s>0&&(t.minXDiff=Math.min(s,t.minXDiff))}})),1!==t.dataPoints&&t.minXDiff!==Number.MAX_VALUE||(t.minXDiff=.5)}))}},{key:"_setStackedMinMax",value:function(){var t=this.w.globals,e=[],i=[];if(t.series.length)for(var a=0;a<t.series[t.maxValsInArrayIndex].length;a++)for(var s=0,r=0,o=0;o<t.series.length;o++)null!==t.series[o][a]&&x.isNumber(t.series[o][a])&&(t.series[o][a]>0?s=s+parseFloat(t.series[o][a])+1e-4:r+=parseFloat(t.series[o][a])),o===t.series.length-1&&(e.push(s),i.push(r));for(var n=0;n<e.length;n++)t.maxY=Math.max(t.maxY,e[n]),t.minY=Math.min(t.minY,i[n])}}]),t}(),q=function(){function t(e,i){a(this,t),this.ctx=e,this.elgrid=i,this.w=e.w;var s=this.w;this.xaxisFontSize=s.config.xaxis.labels.style.fontSize,this.axisFontFamily=s.config.xaxis.labels.style.fontFamily,this.xaxisForeColors=s.config.xaxis.labels.style.colors,this.isCategoryBarHorizontal="bar"===s.config.chart.type&&s.config.plotOptions.bar.horizontal,this.xAxisoffX=0,"bottom"===s.config.xaxis.position&&(this.xAxisoffX=s.globals.gridHeight),this.drawnLabels=[],this.axesUtils=new B(e)}return r(t,[{key:"drawYaxis",value:function(t){var e=this,i=this.w,a=new m(this.ctx),s=i.config.yaxis[t].labels.style,r=s.fontSize,o=s.fontFamily,n=s.fontWeight,l=a.group({class:"apexcharts-yaxis",rel:t,transform:"translate("+i.globals.translateYAxisX[t]+", 0)"});if(this.axesUtils.isYAxisHidden(t))return l;var h=a.group({class:"apexcharts-yaxis-texts-g"});l.add(h);var c=i.globals.yAxisScale[t].result.length-1,d=i.globals.gridHeight/c,g=i.globals.translateY,u=i.globals.yLabelFormatters[t],f=i.globals.yAxisScale[t].result.slice();f=this.axesUtils.checkForReversedLabels(t,f);var p="";if(i.config.yaxis[t].labels.show)for(var x=function(l){var x=f[l];x=u(x,l,i);var b=i.config.yaxis[t].labels.padding;i.config.yaxis[t].opposite&&0!==i.config.yaxis.length&&(b*=-1);var v="end";i.config.yaxis[t].opposite&&(v="start"),"left"===i.config.yaxis[t].labels.align?v="start":"center"===i.config.yaxis[t].labels.align?v="middle":"right"===i.config.yaxis[t].labels.align&&(v="end");var m=e.axesUtils.getYAxisForeColor(s.colors,t),y=a.drawText({x:b,y:g+c/10+i.config.yaxis[t].labels.offsetY+1,text:x,textAnchor:v,fontSize:r,fontFamily:o,fontWeight:n,maxWidth:i.config.yaxis[t].labels.maxWidth,foreColor:Array.isArray(m)?m[l]:m,isPlainText:!1,cssClass:"apexcharts-yaxis-label "+s.cssClass});l===c&&(p=y),h.add(y);var w=document.createElementNS(i.globals.SVGNS,"title");if(w.textContent=Array.isArray(x)?x.join(" "):x,y.node.appendChild(w),0!==i.config.yaxis[t].labels.rotate){var k=a.rotateAroundCenter(p.node),A=a.rotateAroundCenter(y.node);y.node.setAttribute("transform","rotate(".concat(i.config.yaxis[t].labels.rotate," ").concat(k.x," ").concat(A.y,")"))}g+=d},b=c;b>=0;b--)x(b);if(void 0!==i.config.yaxis[t].title.text){var v=a.group({class:"apexcharts-yaxis-title"}),y=0;i.config.yaxis[t].opposite&&(y=i.globals.translateYAxisX[t]);var w=a.drawText({x:y,y:i.globals.gridHeight/2+i.globals.translateY+i.config.yaxis[t].title.offsetY,text:i.config.yaxis[t].title.text,textAnchor:"end",foreColor:i.config.yaxis[t].title.style.color,fontSize:i.config.yaxis[t].title.style.fontSize,fontWeight:i.config.yaxis[t].title.style.fontWeight,fontFamily:i.config.yaxis[t].title.style.fontFamily,cssClass:"apexcharts-yaxis-title-text "+i.config.yaxis[t].title.style.cssClass});v.add(w),l.add(v)}var k=i.config.yaxis[t].axisBorder,A=31+k.offsetX;if(i.config.yaxis[t].opposite&&(A=-31-k.offsetX),k.show){var S=a.drawLine(A,i.globals.translateY+k.offsetY-2,A,i.globals.gridHeight+i.globals.translateY+k.offsetY+2,k.color,0,k.width);l.add(S)}return i.config.yaxis[t].axisTicks.show&&this.axesUtils.drawYAxisTicks(A,c,k,i.config.yaxis[t].axisTicks,t,d,l),l}},{key:"drawYaxisInversed",value:function(t){var e=this.w,i=new m(this.ctx),a=i.group({class:"apexcharts-xaxis apexcharts-yaxis-inversed"}),s=i.group({class:"apexcharts-xaxis-texts-g",transform:"translate(".concat(e.globals.translateXAxisX,", ").concat(e.globals.translateXAxisY,")")});a.add(s);var r=e.globals.yAxisScale[t].result.length-1,o=e.globals.gridWidth/r+.1,n=o+e.config.xaxis.labels.offsetX,l=e.globals.xLabelFormatter,h=e.globals.yAxisScale[t].result.slice(),c=e.globals.timescaleLabels;c.length>0&&(this.xaxisLabels=c.slice(),r=(h=c.slice()).length),h=this.axesUtils.checkForReversedLabels(t,h);var d=c.length;if(e.config.xaxis.labels.show)for(var g=d?0:r;d?g<d:g>=0;d?g++:g--){var u=h[g];u=l(u,g,e);var f=e.globals.gridWidth+e.globals.padHorizontal-(n-o+e.config.xaxis.labels.offsetX);if(c.length){var p=this.axesUtils.getLabel(h,c,f,g,this.drawnLabels,this.xaxisFontSize);f=p.x,u=p.text,this.drawnLabels.push(p.text),0===g&&e.globals.skipFirstTimelinelabel&&(u=""),g===h.length-1&&e.globals.skipLastTimelinelabel&&(u="")}var x=i.drawText({x:f,y:this.xAxisoffX+e.config.xaxis.labels.offsetY+30-("top"===e.config.xaxis.position?e.globals.xAxisHeight+e.config.xaxis.axisTicks.height-2:0),text:u,textAnchor:"middle",foreColor:Array.isArray(this.xaxisForeColors)?this.xaxisForeColors[t]:this.xaxisForeColors,fontSize:this.xaxisFontSize,fontFamily:this.xaxisFontFamily,fontWeight:e.config.xaxis.labels.style.fontWeight,isPlainText:!1,cssClass:"apexcharts-xaxis-label "+e.config.xaxis.labels.style.cssClass});s.add(x),x.tspan(u);var b=document.createElementNS(e.globals.SVGNS,"title");b.textContent=u,x.node.appendChild(b),n+=o}return this.inversedYAxisTitleText(a),this.inversedYAxisBorder(a),a}},{key:"inversedYAxisBorder",value:function(t){var e=this.w,i=new m(this.ctx),a=e.config.xaxis.axisBorder;if(a.show){var s=0;"bar"===e.config.chart.type&&e.globals.isXNumeric&&(s-=15);var r=i.drawLine(e.globals.padHorizontal+s+a.offsetX,this.xAxisoffX,e.globals.gridWidth,this.xAxisoffX,a.color,0,a.height);this.elgrid&&this.elgrid.elGridBorders?this.elgrid.elGridBorders.add(r):t.add(r)}}},{key:"inversedYAxisTitleText",value:function(t){var e=this.w,i=new m(this.ctx);if(void 0!==e.config.xaxis.title.text){var a=i.group({class:"apexcharts-xaxis-title apexcharts-yaxis-title-inversed"}),s=i.drawText({x:e.globals.gridWidth/2+e.config.xaxis.title.offsetX,y:this.xAxisoffX+parseFloat(this.xaxisFontSize)+parseFloat(e.config.xaxis.title.style.fontSize)+e.config.xaxis.title.offsetY+20,text:e.config.xaxis.title.text,textAnchor:"middle",fontSize:e.config.xaxis.title.style.fontSize,fontFamily:e.config.xaxis.title.style.fontFamily,fontWeight:e.config.xaxis.title.style.fontWeight,foreColor:e.config.xaxis.title.style.color,cssClass:"apexcharts-xaxis-title-text "+e.config.xaxis.title.style.cssClass});a.add(s),t.add(a)}}},{key:"yAxisTitleRotate",value:function(t,e){var i=this.w,a=new m(this.ctx),s={width:0,height:0},r={width:0,height:0},o=i.globals.dom.baseEl.querySelector(" .apexcharts-yaxis[rel='".concat(t,"'] .apexcharts-yaxis-texts-g"));null!==o&&(s=o.getBoundingClientRect());var n=i.globals.dom.baseEl.querySelector(".apexcharts-yaxis[rel='".concat(t,"'] .apexcharts-yaxis-title text"));if(null!==n&&(r=n.getBoundingClientRect()),null!==n){var l=this.xPaddingForYAxisTitle(t,s,r,e);n.setAttribute("x",l.xPos-(e?10:0))}if(null!==n){var h=a.rotateAroundCenter(n);n.setAttribute("transform","rotate(".concat(e?-1*i.config.yaxis[t].title.rotate:i.config.yaxis[t].title.rotate," ").concat(h.x," ").concat(h.y,")"))}}},{key:"xPaddingForYAxisTitle",value:function(t,e,i,a){var s=this.w,r=0,o=0,n=10;return void 0===s.config.yaxis[t].title.text||t<0?{xPos:o,padd:0}:(a?(o=e.width+s.config.yaxis[t].title.offsetX+i.width/2+n/2,0===(r+=1)&&(o-=n/2)):(o=-1*e.width+s.config.yaxis[t].title.offsetX+n/2+i.width/2,s.globals.isBarHorizontal&&(n=25,o=-1*e.width-s.config.yaxis[t].title.offsetX-n)),{xPos:o,padd:n})}},{key:"setYAxisXPosition",value:function(t,e){var i=this.w,a=0,s=0,r=18,o=1;i.config.yaxis.length>1&&(this.multipleYs=!0),i.config.yaxis.map((function(n,l){var h=i.globals.ignoreYAxisIndexes.indexOf(l)>-1||!n.show||n.floating||0===t[l].width,c=t[l].width+e[l].width;n.opposite?i.globals.isBarHorizontal?(s=i.globals.gridWidth+i.globals.translateX-1,i.globals.translateYAxisX[l]=s-n.labels.offsetX):(s=i.globals.gridWidth+i.globals.translateX+o,h||(o=o+c+20),i.globals.translateYAxisX[l]=s-n.labels.offsetX+20):(a=i.globals.translateX-r,h||(r=r+c+20),i.globals.translateYAxisX[l]=a+n.labels.offsetX)}))}},{key:"setYAxisTextAlignments",value:function(){var t=this.w,e=t.globals.dom.baseEl.getElementsByClassName("apexcharts-yaxis");(e=x.listToArray(e)).forEach((function(e,i){var a=t.config.yaxis[i];if(a&&!a.floating&&void 0!==a.labels.align){var s=t.globals.dom.baseEl.querySelector(".apexcharts-yaxis[rel='".concat(i,"'] .apexcharts-yaxis-texts-g")),r=t.globals.dom.baseEl.querySelectorAll(".apexcharts-yaxis[rel='".concat(i,"'] .apexcharts-yaxis-label"));r=x.listToArray(r);var o=s.getBoundingClientRect();"left"===a.labels.align?(r.forEach((function(t,e){t.setAttribute("text-anchor","start")})),a.opposite||s.setAttribute("transform","translate(-".concat(o.width,", 0)"))):"center"===a.labels.align?(r.forEach((function(t,e){t.setAttribute("text-anchor","middle")})),s.setAttribute("transform","translate(".concat(o.width/2*(a.opposite?1:-1),", 0)"))):"right"===a.labels.align&&(r.forEach((function(t,e){t.setAttribute("text-anchor","end")})),a.opposite&&s.setAttribute("transform","translate(".concat(o.width,", 0)")))}}))}}]),t}(),Z=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.documentEvent=x.bind(this.documentEvent,this)}return r(t,[{key:"addEventListener",value:function(t,e){var i=this.w;i.globals.events.hasOwnProperty(t)?i.globals.events[t].push(e):i.globals.events[t]=[e]}},{key:"removeEventListener",value:function(t,e){var i=this.w;if(i.globals.events.hasOwnProperty(t)){var a=i.globals.events[t].indexOf(e);-1!==a&&i.globals.events[t].splice(a,1)}}},{key:"fireEvent",value:function(t,e){var i=this.w;if(i.globals.events.hasOwnProperty(t)){e&&e.length||(e=[]);for(var a=i.globals.events[t],s=a.length,r=0;r<s;r++)a[r].apply(null,e)}}},{key:"setupEventHandlers",value:function(){var t=this,e=this.w,i=this.ctx,a=e.globals.dom.baseEl.querySelector(e.globals.chartClass);this.ctx.eventList.forEach((function(t){a.addEventListener(t,(function(t){var a=Object.assign({},e,{seriesIndex:e.globals.capturedSeriesIndex,dataPointIndex:e.globals.capturedDataPointIndex});"mousemove"===t.type||"touchmove"===t.type?"function"==typeof e.config.chart.events.mouseMove&&e.config.chart.events.mouseMove(t,i,a):"mouseleave"===t.type||"touchleave"===t.type?"function"==typeof e.config.chart.events.mouseLeave&&e.config.chart.events.mouseLeave(t,i,a):("mouseup"===t.type&&1===t.which||"touchend"===t.type)&&("function"==typeof e.config.chart.events.click&&e.config.chart.events.click(t,i,a),i.ctx.events.fireEvent("click",[t,i,a]))}),{capture:!1,passive:!0})})),this.ctx.eventList.forEach((function(i){e.globals.dom.baseEl.addEventListener(i,t.documentEvent,{passive:!0})})),this.ctx.core.setupBrushHandler()}},{key:"documentEvent",value:function(t){var e=this.w,i=t.target.className;if("click"===t.type){var a=e.globals.dom.baseEl.querySelector(".apexcharts-menu");a&&a.classList.contains("apexcharts-menu-open")&&"apexcharts-menu-icon"!==i&&a.classList.remove("apexcharts-menu-open")}e.globals.clientX="touchmove"===t.type?t.touches[0].clientX:t.clientX,e.globals.clientY="touchmove"===t.type?t.touches[0].clientY:t.clientY}}]),t}(),$=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"setCurrentLocaleValues",value:function(t){var e=this.w.config.chart.locales;window.Apex.chart&&window.Apex.chart.locales&&window.Apex.chart.locales.length>0&&(e=this.w.config.chart.locales.concat(window.Apex.chart.locales));var i=e.filter((function(e){return e.name===t}))[0];if(!i)throw new Error("Wrong locale name provided. Please make sure you set the correct locale name in options");var a=x.extend(C,i);this.w.globals.locale=a.options}}]),t}(),J=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"drawAxis",value:function(t,e){var i,a,s=this,r=this.w.globals,o=this.w.config,n=new V(this.ctx,e),l=new q(this.ctx,e);r.axisCharts&&"radar"!==t&&(r.isBarHorizontal?(a=l.drawYaxisInversed(0),i=n.drawXaxisInversed(0),r.dom.elGraphical.add(i),r.dom.elGraphical.add(a)):(i=n.drawXaxis(),r.dom.elGraphical.add(i),o.yaxis.map((function(t,e){if(-1===r.ignoreYAxisIndexes.indexOf(e)&&(a=l.drawYaxis(e),r.dom.Paper.add(a),"back"===s.w.config.grid.position)){var i=r.dom.Paper.children()[1];i.remove(),r.dom.Paper.add(i)}}))))}}]),t}(),Q=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"drawXCrosshairs",value:function(){var t=this.w,e=new m(this.ctx),i=new v(this.ctx),a=t.config.xaxis.crosshairs.fill.gradient,s=t.config.xaxis.crosshairs.dropShadow,r=t.config.xaxis.crosshairs.fill.type,o=a.colorFrom,n=a.colorTo,l=a.opacityFrom,h=a.opacityTo,c=a.stops,d=s.enabled,g=s.left,u=s.top,f=s.blur,p=s.color,b=s.opacity,y=t.config.xaxis.crosshairs.fill.color;if(t.config.xaxis.crosshairs.show){"gradient"===r&&(y=e.drawGradient("vertical",o,n,l,h,null,c,null));var w=e.drawRect();1===t.config.xaxis.crosshairs.width&&(w=e.drawLine());var k=t.globals.gridHeight;(!x.isNumber(k)||k<0)&&(k=0);var A=t.config.xaxis.crosshairs.width;(!x.isNumber(A)||A<0)&&(A=0),w.attr({class:"apexcharts-xcrosshairs",x:0,y:0,y2:k,width:A,height:k,fill:y,filter:"none","fill-opacity":t.config.xaxis.crosshairs.opacity,stroke:t.config.xaxis.crosshairs.stroke.color,"stroke-width":t.config.xaxis.crosshairs.stroke.width,"stroke-dasharray":t.config.xaxis.crosshairs.stroke.dashArray}),d&&(w=i.dropShadow(w,{left:g,top:u,blur:f,color:p,opacity:b})),t.globals.dom.elGraphical.add(w)}}},{key:"drawYCrosshairs",value:function(){var t=this.w,e=new m(this.ctx),i=t.config.yaxis[0].crosshairs,a=t.globals.barPadForNumericAxis;if(t.config.yaxis[0].crosshairs.show){var s=e.drawLine(-a,0,t.globals.gridWidth+a,0,i.stroke.color,i.stroke.dashArray,i.stroke.width);s.attr({class:"apexcharts-ycrosshairs"}),t.globals.dom.elGraphical.add(s)}var r=e.drawLine(-a,0,t.globals.gridWidth+a,0,i.stroke.color,0,0);r.attr({class:"apexcharts-ycrosshairs-hidden"}),t.globals.dom.elGraphical.add(r)}}]),t}(),K=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"checkResponsiveConfig",value:function(t){var e=this,i=this.w,a=i.config;if(0!==a.responsive.length){var s=a.responsive.slice();s.sort((function(t,e){return t.breakpoint>e.breakpoint?1:e.breakpoint>t.breakpoint?-1:0})).reverse();var r=new E({}),o=function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},a=s[0].breakpoint,o=window.innerWidth>0?window.innerWidth:screen.width;if(o>a){var n=y.extendArrayProps(r,i.globals.initialConfig,i);t=x.extend(n,t),t=x.extend(i.config,t),e.overrideResponsiveOptions(t)}else for(var l=0;l<s.length;l++)o<s[l].breakpoint&&(t=y.extendArrayProps(r,s[l].options,i),t=x.extend(i.config,t),e.overrideResponsiveOptions(t))};if(t){var n=y.extendArrayProps(r,t,i);n=x.extend(i.config,n),o(n=x.extend(n,t))}else o({})}}},{key:"overrideResponsiveOptions",value:function(t){var e=new E(t).init({responsiveOverride:!0});this.w.config=e}}]),t}(),tt=function(){function t(e){a(this,t),this.ctx=e,this.colors=[],this.w=e.w;var i=this.w;this.isColorFn=!1,this.isHeatmapDistributed="treemap"===i.config.chart.type&&i.config.plotOptions.treemap.distributed||"heatmap"===i.config.chart.type&&i.config.plotOptions.heatmap.distributed,this.isBarDistributed=i.config.plotOptions.bar.distributed&&("bar"===i.config.chart.type||"rangeBar"===i.config.chart.type)}return r(t,[{key:"init",value:function(){this.setDefaultColors()}},{key:"setDefaultColors",value:function(){var t=this,e=this.w,i=new x;if(e.globals.dom.elWrap.classList.add("apexcharts-theme-".concat(e.config.theme.mode)),void 0===e.config.colors?e.globals.colors=this.predefined():(e.globals.colors=e.config.colors,Array.isArray(e.config.colors)&&e.config.colors.length>0&&"function"==typeof e.config.colors[0]&&(e.globals.colors=e.config.series.map((function(i,a){var s=e.config.colors[a];return s||(s=e.config.colors[0]),"function"==typeof s?(t.isColorFn=!0,s({value:e.globals.axisCharts?e.globals.series[a][0]?e.globals.series[a][0]:0:e.globals.series[a],seriesIndex:a,dataPointIndex:a,w:e})):s})))),e.globals.seriesColors.map((function(t,i){t&&(e.globals.colors[i]=t)})),e.config.theme.monochrome.enabled){var a=[],s=e.globals.series.length;(this.isBarDistributed||this.isHeatmapDistributed)&&(s=e.globals.series[0].length*e.globals.series.length);for(var r=e.config.theme.monochrome.color,o=1/(s/e.config.theme.monochrome.shadeIntensity),n=e.config.theme.monochrome.shadeTo,l=0,h=0;h<s;h++){var c=void 0;"dark"===n?(c=i.shadeColor(-1*l,r),l+=o):(c=i.shadeColor(l,r),l+=o),a.push(c)}e.globals.colors=a.slice()}var d=e.globals.colors.slice();this.pushExtraColors(e.globals.colors);["fill","stroke"].forEach((function(i){void 0===e.config[i].colors?e.globals[i].colors=t.isColorFn?e.config.colors:d:e.globals[i].colors=e.config[i].colors.slice(),t.pushExtraColors(e.globals[i].colors)})),void 0===e.config.dataLabels.style.colors?e.globals.dataLabels.style.colors=d:e.globals.dataLabels.style.colors=e.config.dataLabels.style.colors.slice(),this.pushExtraColors(e.globals.dataLabels.style.colors,50),void 0===e.config.plotOptions.radar.polygons.fill.colors?e.globals.radarPolygons.fill.colors=["dark"===e.config.theme.mode?"#424242":"none"]:e.globals.radarPolygons.fill.colors=e.config.plotOptions.radar.polygons.fill.colors.slice(),this.pushExtraColors(e.globals.radarPolygons.fill.colors,20),void 0===e.config.markers.colors?e.globals.markers.colors=d:e.globals.markers.colors=e.config.markers.colors.slice(),this.pushExtraColors(e.globals.markers.colors)}},{key:"pushExtraColors",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=this.w,s=e||a.globals.series.length;if(null===i&&(i=this.isBarDistributed||this.isHeatmapDistributed||"heatmap"===a.config.chart.type&&a.config.plotOptions.heatmap.colorScale.inverse),i&&a.globals.series.length&&(s=a.globals.series[a.globals.maxValsInArrayIndex].length*a.globals.series.length),t.length<s)for(var r=s-t.length,o=0;o<r;o++)t.push(t[o])}},{key:"updateThemeOptions",value:function(t){t.chart=t.chart||{},t.tooltip=t.tooltip||{};var e=t.theme.mode||"light",i=t.theme.palette?t.theme.palette:"dark"===e?"palette4":"palette1",a=t.chart.foreColor?t.chart.foreColor:"dark"===e?"#f6f7f8":"#373d3f";return t.tooltip.theme=e,t.chart.foreColor=a,t.theme.palette=i,t}},{key:"predefined",value:function(){switch(this.w.config.theme.palette){case"palette1":default:this.colors=["#008FFB","#00E396","#FEB019","#FF4560","#775DD0"];break;case"palette2":this.colors=["#3f51b5","#03a9f4","#4caf50","#f9ce1d","#FF9800"];break;case"palette3":this.colors=["#33b2df","#546E7A","#d4526e","#13d8aa","#A5978B"];break;case"palette4":this.colors=["#4ecdc4","#c7f464","#81D4FA","#fd6a6a","#546E7A"];break;case"palette5":this.colors=["#2b908f","#f9a3a4","#90ee7e","#fa4443","#69d2e7"];break;case"palette6":this.colors=["#449DD1","#F86624","#EA3546","#662E9B","#C5D86D"];break;case"palette7":this.colors=["#D7263D","#1B998B","#2E294E","#F46036","#E2C044"];break;case"palette8":this.colors=["#662E9B","#F86624","#F9C80E","#EA3546","#43BCCD"];break;case"palette9":this.colors=["#5C4742","#A5978B","#8D5B4C","#5A2A27","#C4BBAF"];break;case"palette10":this.colors=["#A300D6","#7D02EB","#5653FE","#2983FF","#00B1F2"]}return this.colors}}]),t}(),et=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"draw",value:function(){this.drawTitleSubtitle("title"),this.drawTitleSubtitle("subtitle")}},{key:"drawTitleSubtitle",value:function(t){var e=this.w,i="title"===t?e.config.title:e.config.subtitle,a=e.globals.svgWidth/2,s=i.offsetY,r="middle";if("left"===i.align?(a=10,r="start"):"right"===i.align&&(a=e.globals.svgWidth-10,r="end"),a+=i.offsetX,s=s+parseInt(i.style.fontSize,10)+i.margin/2,void 0!==i.text){var o=new m(this.ctx).drawText({x:a,y:s,text:i.text,textAnchor:r,fontSize:i.style.fontSize,fontFamily:i.style.fontFamily,fontWeight:i.style.fontWeight,foreColor:i.style.color,opacity:1});o.node.setAttribute("class","apexcharts-".concat(t,"-text")),e.globals.dom.Paper.add(o)}}}]),t}(),it=function(){function t(e){a(this,t),this.w=e.w,this.dCtx=e}return r(t,[{key:"getTitleSubtitleCoords",value:function(t){var e=this.w,i=0,a=0,s="title"===t?e.config.title.floating:e.config.subtitle.floating,r=e.globals.dom.baseEl.querySelector(".apexcharts-".concat(t,"-text"));if(null!==r&&!s){var o=r.getBoundingClientRect();i=o.width,a=e.globals.axisCharts?o.height+5:o.height}return{width:i,height:a}}},{key:"getLegendsRect",value:function(){var t=this.w,e=t.globals.dom.baseEl.querySelector(".apexcharts-legend");t.config.legend.height||"top"!==t.config.legend.position&&"bottom"!==t.config.legend.position||(e.style.maxHeight=t.globals.svgHeight/2+"px");var i=Object.assign({},x.getBoundingClientRect(e));return null!==e&&!t.config.legend.floating&&t.config.legend.show?this.dCtx.lgRect={x:i.x,y:i.y,height:i.height,width:0===i.height?0:i.width}:this.dCtx.lgRect={x:0,y:0,height:0,width:0},"left"!==t.config.legend.position&&"right"!==t.config.legend.position||1.5*this.dCtx.lgRect.width>t.globals.svgWidth&&(this.dCtx.lgRect.width=t.globals.svgWidth/1.5),this.dCtx.lgRect}},{key:"getLargestStringFromMultiArr",value:function(t,e){var i=t;if(this.w.globals.isMultiLineX){var a=e.map((function(t,e){return Array.isArray(t)?t.length:1})),s=Math.max.apply(Math,u(a));i=e[a.indexOf(s)]}return i}}]),t}(),at=function(){function t(e){a(this,t),this.w=e.w,this.dCtx=e}return r(t,[{key:"getxAxisLabelsCoords",value:function(){var t,e=this.w,i=e.globals.labels.slice();if(e.config.xaxis.convertedCatToNumeric&&0===i.length&&(i=e.globals.categoryLabels),e.globals.timescaleLabels.length>0){var a=this.getxAxisTimeScaleLabelsCoords();t={width:a.width,height:a.height},e.globals.rotateXLabels=!1}else{this.dCtx.lgWidthForSideLegends="left"!==e.config.legend.position&&"right"!==e.config.legend.position||e.config.legend.floating?0:this.dCtx.lgRect.width;var s=e.globals.xLabelFormatter,r=x.getLargestStringFromArr(i),o=this.dCtx.dimHelpers.getLargestStringFromMultiArr(r,i);e.globals.isBarHorizontal&&(o=r=e.globals.yAxisScale[0].result.reduce((function(t,e){return t.length>e.length?t:e}),0));var n=new M(this.dCtx.ctx),l=r;r=n.xLabelFormat(s,r,l,{i:void 0,dateFormatter:new T(this.dCtx.ctx).formatDate,w:e}),o=n.xLabelFormat(s,o,l,{i:void 0,dateFormatter:new T(this.dCtx.ctx).formatDate,w:e}),(e.config.xaxis.convertedCatToNumeric&&void 0===r||""===String(r).trim())&&(o=r="1");var h=new m(this.dCtx.ctx),c=h.getTextRects(r,e.config.xaxis.labels.style.fontSize),d=c;if(r!==o&&(d=h.getTextRects(o,e.config.xaxis.labels.style.fontSize)),(t={width:c.width>=d.width?c.width:d.width,height:c.height>=d.height?c.height:d.height}).width*i.length>e.globals.svgWidth-this.dCtx.lgWidthForSideLegends-this.dCtx.yAxisWidth-this.dCtx.gridPad.left-this.dCtx.gridPad.right&&0!==e.config.xaxis.labels.rotate||e.config.xaxis.labels.rotateAlways){if(!e.globals.isBarHorizontal){e.globals.rotateXLabels=!0;var g=function(t){return h.getTextRects(t,e.config.xaxis.labels.style.fontSize,e.config.xaxis.labels.style.fontFamily,"rotate(".concat(e.config.xaxis.labels.rotate," 0 0)"),!1)};c=g(r),r!==o&&(d=g(o)),t.height=(c.height>d.height?c.height:d.height)/1.5,t.width=c.width>d.width?c.width:d.width}}else e.globals.rotateXLabels=!1}return e.config.xaxis.labels.show||(t={width:0,height:0}),{width:t.width,height:t.height}}},{key:"getxAxisGroupLabelsCoords",value:function(){var t,e=this.w;if(!e.globals.hasGroups)return{width:0,height:0};var i,a=(null===(t=e.config.xaxis.group.style)||void 0===t?void 0:t.fontSize)||e.config.xaxis.labels.style.fontSize,s=e.globals.groups.map((function(t){return t.title})),r=x.getLargestStringFromArr(s),o=this.dCtx.dimHelpers.getLargestStringFromMultiArr(r,s),n=new m(this.dCtx.ctx),l=n.getTextRects(r,a),h=l;return r!==o&&(h=n.getTextRects(o,a)),i={width:l.width>=h.width?l.width:h.width,height:l.height>=h.height?l.height:h.height},e.config.xaxis.labels.show||(i={width:0,height:0}),{width:i.width,height:i.height}}},{key:"getxAxisTitleCoords",value:function(){var t=this.w,e=0,i=0;if(void 0!==t.config.xaxis.title.text){var a=new m(this.dCtx.ctx).getTextRects(t.config.xaxis.title.text,t.config.xaxis.title.style.fontSize);e=a.width,i=a.height}return{width:e,height:i}}},{key:"getxAxisTimeScaleLabelsCoords",value:function(){var t,e=this.w;this.dCtx.timescaleLabels=e.globals.timescaleLabels.slice();var i=this.dCtx.timescaleLabels.map((function(t){return t.value})),a=i.reduce((function(t,e){return void 0===t?(console.error("You have possibly supplied invalid Date format. Please supply a valid JavaScript Date"),0):t.length>e.length?t:e}),0);return 1.05*(t=new m(this.dCtx.ctx).getTextRects(a,e.config.xaxis.labels.style.fontSize)).width*i.length>e.globals.gridWidth&&0!==e.config.xaxis.labels.rotate&&(e.globals.overlappingXLabels=!0),t}},{key:"additionalPaddingXLabels",value:function(t){var e=this,i=this.w,a=i.globals,s=i.config,r=s.xaxis.type,o=t.width;a.skipLastTimelinelabel=!1,a.skipFirstTimelinelabel=!1;var n=i.config.yaxis[0].opposite&&i.globals.isBarHorizontal,l=function(t,n){(function(t){return-1!==a.collapsedSeriesIndices.indexOf(t)})(n)||function(t){if(e.dCtx.timescaleLabels&&e.dCtx.timescaleLabels.length){var n=e.dCtx.timescaleLabels[0],l=e.dCtx.timescaleLabels[e.dCtx.timescaleLabels.length-1].position+o/1.75-e.dCtx.yAxisWidthRight,h=n.position-o/1.75+e.dCtx.yAxisWidthLeft,c="right"===i.config.legend.position&&e.dCtx.lgRect.width>0?e.dCtx.lgRect.width:0;l>a.svgWidth-a.translateX-c&&(a.skipLastTimelinelabel=!0),h<-(t.show&&!t.floating||"bar"!==s.chart.type&&"candlestick"!==s.chart.type&&"rangeBar"!==s.chart.type&&"boxPlot"!==s.chart.type?10:o/1.75)&&(a.skipFirstTimelinelabel=!0)}else"datetime"===r?e.dCtx.gridPad.right<o&&!a.rotateXLabels&&(a.skipLastTimelinelabel=!0):"datetime"!==r&&e.dCtx.gridPad.right<o/2-e.dCtx.yAxisWidthRight&&!a.rotateXLabels&&!i.config.xaxis.labels.trim&&("between"!==i.config.xaxis.tickPlacement||i.globals.isBarHorizontal)&&(e.dCtx.xPadRight=o/2+1)}(t)};s.yaxis.forEach((function(t,i){n?(e.dCtx.gridPad.left<o&&(e.dCtx.xPadLeft=o/2+1),e.dCtx.xPadRight=o/2+1):l(t,i)}))}}]),t}(),st=function(){function t(e){a(this,t),this.w=e.w,this.dCtx=e}return r(t,[{key:"getyAxisLabelsCoords",value:function(){var t=this,e=this.w,i=[],a=10,s=new B(this.dCtx.ctx);return e.config.yaxis.map((function(r,o){var n=e.globals.yAxisScale[o],l=0;if(!s.isYAxisHidden(o)&&r.labels.show&&void 0!==r.labels.minWidth&&(l=r.labels.minWidth),!s.isYAxisHidden(o)&&r.labels.show&&n.result.length){var h=e.globals.yLabelFormatters[o],c=n.niceMin===Number.MIN_VALUE?0:n.niceMin,d=String(c).length>String(n.niceMax).length?c:n.niceMax,g=h(d,{seriesIndex:o,dataPointIndex:-1,w:e}),u=g;if(void 0!==g&&0!==g.length||(g=d),e.globals.isBarHorizontal){a=0;var f=e.globals.labels.slice();g=h(g=x.getLargestStringFromArr(f),{seriesIndex:o,dataPointIndex:-1,w:e}),u=t.dCtx.dimHelpers.getLargestStringFromMultiArr(g,f)}var p=new m(t.dCtx.ctx),b="rotate(".concat(r.labels.rotate," 0 0)"),v=p.getTextRects(g,r.labels.style.fontSize,r.labels.style.fontFamily,b,!1),y=v;g!==u&&(y=p.getTextRects(u,r.labels.style.fontSize,r.labels.style.fontFamily,b,!1)),i.push({width:(l>y.width||l>v.width?l:y.width>v.width?y.width:v.width)+a,height:y.height>v.height?y.height:v.height})}else i.push({width:0,height:0})})),i}},{key:"getyAxisTitleCoords",value:function(){var t=this,e=this.w,i=[];return e.config.yaxis.map((function(e,a){if(e.show&&void 0!==e.title.text){var s=new m(t.dCtx.ctx),r="rotate(".concat(e.title.rotate," 0 0)"),o=s.getTextRects(e.title.text,e.title.style.fontSize,e.title.style.fontFamily,r,!1);i.push({width:o.width,height:o.height})}else i.push({width:0,height:0})})),i}},{key:"getTotalYAxisWidth",value:function(){var t=this.w,e=0,i=0,a=0,s=t.globals.yAxisScale.length>1?10:0,r=new B(this.dCtx.ctx),o=function(o,n){var l=t.config.yaxis[n].floating,h=0;o.width>0&&!l?(h=o.width+s,function(e){return t.globals.ignoreYAxisIndexes.indexOf(e)>-1}(n)&&(h=h-o.width-s)):h=l||r.isYAxisHidden(n)?0:5,t.config.yaxis[n].opposite?a+=h:i+=h,e+=h};return t.globals.yLabelsCoords.map((function(t,e){o(t,e)})),t.globals.yTitleCoords.map((function(t,e){o(t,e)})),t.globals.isBarHorizontal&&!t.config.yaxis[0].floating&&(e=t.globals.yLabelsCoords[0].width+t.globals.yTitleCoords[0].width+15),this.dCtx.yAxisWidthLeft=i,this.dCtx.yAxisWidthRight=a,e}}]),t}(),rt=function(){function t(e){a(this,t),this.w=e.w,this.dCtx=e}return r(t,[{key:"gridPadForColumnsInNumericAxis",value:function(t){var e=this.w;if(e.globals.noData||e.globals.allSeriesCollapsed)return 0;var i=function(t){return"bar"===t||"rangeBar"===t||"candlestick"===t||"boxPlot"===t},a=e.config.chart.type,s=0,r=i(a)?e.config.series.length:1;if(e.globals.comboBarCount>0&&(r=e.globals.comboBarCount),e.globals.collapsedSeries.forEach((function(t){i(t.type)&&(r-=1)})),e.config.chart.stacked&&(r=1),(i(a)||e.globals.comboBarCount>0)&&e.globals.isXNumeric&&!e.globals.isBarHorizontal&&r>0){var o,n,l=Math.abs(e.globals.initialMaxX-e.globals.initialMinX);l<=3&&(l=e.globals.dataPoints),o=l/t,e.globals.minXDiff&&e.globals.minXDiff/o>0&&(n=e.globals.minXDiff/o),n>t/2&&(n/=2),(s=n/r*parseInt(e.config.plotOptions.bar.columnWidth,10)/100)<1&&(s=1),s=s/(r>1?1:1.5)+5,e.globals.barPadForNumericAxis=s}return s}},{key:"gridPadFortitleSubtitle",value:function(){var t=this,e=this.w,i=e.globals,a=this.dCtx.isSparkline||!e.globals.axisCharts?0:10;["title","subtitle"].forEach((function(i){void 0!==e.config[i].text?a+=e.config[i].margin:a+=t.dCtx.isSparkline||!e.globals.axisCharts?0:5})),!e.config.legend.show||"bottom"!==e.config.legend.position||e.config.legend.floating||e.globals.axisCharts||(a+=10);var s=this.dCtx.dimHelpers.getTitleSubtitleCoords("title"),r=this.dCtx.dimHelpers.getTitleSubtitleCoords("subtitle");i.gridHeight=i.gridHeight-s.height-r.height-a,i.translateY=i.translateY+s.height+r.height+a}},{key:"setGridXPosForDualYAxis",value:function(t,e){var i=this.w,a=new B(this.dCtx.ctx);i.config.yaxis.map((function(s,r){-1!==i.globals.ignoreYAxisIndexes.indexOf(r)||s.floating||a.isYAxisHidden(r)||(s.opposite&&(i.globals.translateX=i.globals.translateX-(e[r].width+t[r].width)-parseInt(i.config.yaxis[r].labels.style.fontSize,10)/1.2-12),i.globals.translateX<2&&(i.globals.translateX=2))}))}}]),t}(),ot=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.lgRect={},this.yAxisWidth=0,this.yAxisWidthLeft=0,this.yAxisWidthRight=0,this.xAxisHeight=0,this.isSparkline=this.w.config.chart.sparkline.enabled,this.dimHelpers=new it(this),this.dimYAxis=new st(this),this.dimXAxis=new at(this),this.dimGrid=new rt(this),this.lgWidthForSideLegends=0,this.gridPad=this.w.config.grid.padding,this.xPadRight=0,this.xPadLeft=0}return r(t,[{key:"plotCoords",value:function(){var t=this,e=this.w,i=e.globals;this.lgRect=this.dimHelpers.getLegendsRect(),this.isSparkline&&(e.config.markers.discrete.length>0||e.config.markers.size>0)&&Object.entries(this.gridPad).forEach((function(e){var i=g(e,2),a=i[0],s=i[1];t.gridPad[a]=Math.max(s,t.w.globals.markers.largestSize/1.5)})),i.axisCharts?this.setDimensionsForAxisCharts():this.setDimensionsForNonAxisCharts(),this.dimGrid.gridPadFortitleSubtitle(),i.gridHeight=i.gridHeight-this.gridPad.top-this.gridPad.bottom,i.gridWidth=i.gridWidth-this.gridPad.left-this.gridPad.right-this.xPadRight-this.xPadLeft;var a=this.dimGrid.gridPadForColumnsInNumericAxis(i.gridWidth);i.gridWidth=i.gridWidth-2*a,i.translateX=i.translateX+this.gridPad.left+this.xPadLeft+(a>0?a+4:0),i.translateY=i.translateY+this.gridPad.top}},{key:"setDimensionsForAxisCharts",value:function(){var t=this,e=this.w,i=e.globals,a=this.dimYAxis.getyAxisLabelsCoords(),s=this.dimYAxis.getyAxisTitleCoords();e.globals.yLabelsCoords=[],e.globals.yTitleCoords=[],e.config.yaxis.map((function(t,i){e.globals.yLabelsCoords.push({width:a[i].width,index:i}),e.globals.yTitleCoords.push({width:s[i].width,index:i})})),this.yAxisWidth=this.dimYAxis.getTotalYAxisWidth();var r=this.dimXAxis.getxAxisLabelsCoords(),o=this.dimXAxis.getxAxisGroupLabelsCoords(),n=this.dimXAxis.getxAxisTitleCoords();this.conditionalChecksForAxisCoords(r,n,o),i.translateXAxisY=e.globals.rotateXLabels?this.xAxisHeight/8:-4,i.translateXAxisX=e.globals.rotateXLabels&&e.globals.isXNumeric&&e.config.xaxis.labels.rotate<=-45?-this.xAxisWidth/4:0,e.globals.isBarHorizontal&&(i.rotateXLabels=!1,i.translateXAxisY=parseInt(e.config.xaxis.labels.style.fontSize,10)/1.5*-1),i.translateXAxisY=i.translateXAxisY+e.config.xaxis.labels.offsetY,i.translateXAxisX=i.translateXAxisX+e.config.xaxis.labels.offsetX;var l=this.yAxisWidth,h=this.xAxisHeight;i.xAxisLabelsHeight=this.xAxisHeight-n.height,i.xAxisGroupLabelsHeight=i.xAxisLabelsHeight-r.height,i.xAxisLabelsWidth=this.xAxisWidth,i.xAxisHeight=this.xAxisHeight;var c=10;("radar"===e.config.chart.type||this.isSparkline)&&(l=0,h=i.goldenPadding),this.isSparkline&&(this.lgRect={height:0,width:0}),(this.isSparkline||"treemap"===e.config.chart.type)&&(l=0,h=0,c=0),this.isSparkline||this.dimXAxis.additionalPaddingXLabels(r);var d=function(){i.translateX=l,i.gridHeight=i.svgHeight-t.lgRect.height-h-(t.isSparkline||"treemap"===e.config.chart.type?0:e.globals.rotateXLabels?10:15),i.gridWidth=i.svgWidth-l};switch("top"===e.config.xaxis.position&&(c=i.xAxisHeight-e.config.xaxis.axisTicks.height-5),e.config.legend.position){case"bottom":i.translateY=c,d();break;case"top":i.translateY=this.lgRect.height+c,d();break;case"left":i.translateY=c,i.translateX=this.lgRect.width+l,i.gridHeight=i.svgHeight-h-12,i.gridWidth=i.svgWidth-this.lgRect.width-l;break;case"right":i.translateY=c,i.translateX=l,i.gridHeight=i.svgHeight-h-12,i.gridWidth=i.svgWidth-this.lgRect.width-l-5;break;default:throw new Error("Legend position not supported")}this.dimGrid.setGridXPosForDualYAxis(s,a),new q(this.ctx).setYAxisXPosition(a,s)}},{key:"setDimensionsForNonAxisCharts",value:function(){var t=this.w,e=t.globals,i=t.config,a=0;t.config.legend.show&&!t.config.legend.floating&&(a=20);var s="pie"===i.chart.type||"polarArea"===i.chart.type||"donut"===i.chart.type?"pie":"radialBar",r=i.plotOptions[s].offsetY,o=i.plotOptions[s].offsetX;if(!i.legend.show||i.legend.floating)return e.gridHeight=e.svgHeight-i.grid.padding.left+i.grid.padding.right,e.gridWidth=e.gridHeight,e.translateY=r,void(e.translateX=o+(e.svgWidth-e.gridWidth)/2);switch(i.legend.position){case"bottom":e.gridHeight=e.svgHeight-this.lgRect.height-e.goldenPadding,e.gridWidth=e.svgWidth,e.translateY=r-10,e.translateX=o+(e.svgWidth-e.gridWidth)/2;break;case"top":e.gridHeight=e.svgHeight-this.lgRect.height-e.goldenPadding,e.gridWidth=e.svgWidth,e.translateY=this.lgRect.height+r+10,e.translateX=o+(e.svgWidth-e.gridWidth)/2;break;case"left":e.gridWidth=e.svgWidth-this.lgRect.width-a,e.gridHeight="auto"!==i.chart.height?e.svgHeight:e.gridWidth,e.translateY=r,e.translateX=o+this.lgRect.width+a;break;case"right":e.gridWidth=e.svgWidth-this.lgRect.width-a-5,e.gridHeight="auto"!==i.chart.height?e.svgHeight:e.gridWidth,e.translateY=r,e.translateX=o+10;break;default:throw new Error("Legend position not supported")}}},{key:"conditionalChecksForAxisCoords",value:function(t,e,i){var a=this.w,s=a.globals.hasGroups?2:1,r=i.height+t.height+e.height,o=a.globals.isMultiLineX?1.2:a.globals.LINE_HEIGHT_RATIO,n=a.globals.rotateXLabels?22:10,l=a.globals.rotateXLabels&&"bottom"===a.config.legend.position?10:0;this.xAxisHeight=r*o+s*n+l,this.xAxisWidth=t.width,this.xAxisHeight-e.height>a.config.xaxis.labels.maxHeight&&(this.xAxisHeight=a.config.xaxis.labels.maxHeight),a.config.xaxis.labels.minHeight&&this.xAxisHeight<a.config.xaxis.labels.minHeight&&(this.xAxisHeight=a.config.xaxis.labels.minHeight),a.config.xaxis.floating&&(this.xAxisHeight=0);var h=0,c=0;a.config.yaxis.forEach((function(t){h+=t.labels.minWidth,c+=t.labels.maxWidth})),this.yAxisWidth<h&&(this.yAxisWidth=h),this.yAxisWidth>c&&(this.yAxisWidth=c)}}]),t}(),nt=function(){function t(e){a(this,t),this.w=e.w,this.lgCtx=e}return r(t,[{key:"getLegendBBox",value:function(){var t=this.w.globals.dom.baseEl.querySelector(".apexcharts-legend").getBoundingClientRect(),e=t.width;return{clwh:t.height,clww:e}}},{key:"toggleDataSeries",value:function(t,e){var i=this,a=this.w;if(a.globals.axisCharts||"radialBar"===a.config.chart.type){a.globals.resized=!0;var s=null,r=null;if(a.globals.risingSeries=[],a.globals.axisCharts?(s=a.globals.dom.baseEl.querySelector(".apexcharts-series[data\\:realIndex='".concat(t,"']")),r=parseInt(s.getAttribute("data:realIndex"),10)):(s=a.globals.dom.baseEl.querySelector(".apexcharts-series[rel='".concat(t+1,"']")),r=parseInt(s.getAttribute("rel"),10)-1),e)[{cs:a.globals.collapsedSeries,csi:a.globals.collapsedSeriesIndices},{cs:a.globals.ancillaryCollapsedSeries,csi:a.globals.ancillaryCollapsedSeriesIndices}].forEach((function(t){i.riseCollapsedSeries(t.cs,t.csi,r)}));else this.hideSeries({seriesEl:s,realIndex:r})}else{var o=a.globals.dom.Paper.select(" .apexcharts-series[rel='".concat(t+1,"'] path")),n=a.config.chart.type;if("pie"===n||"polarArea"===n||"donut"===n){var l=a.config.plotOptions.pie.donut.labels;new m(this.lgCtx.ctx).pathMouseDown(o.members[0],null),this.lgCtx.ctx.pie.printDataLabelsInner(o.members[0].node,l)}o.fire("click")}}},{key:"hideSeries",value:function(t){var e=t.seriesEl,i=t.realIndex,a=this.w,s=x.clone(a.config.series);if(a.globals.axisCharts){var r=!1;if(a.config.yaxis[i]&&a.config.yaxis[i].show&&a.config.yaxis[i].showAlways&&(r=!0,a.globals.ancillaryCollapsedSeriesIndices.indexOf(i)<0&&(a.globals.ancillaryCollapsedSeries.push({index:i,data:s[i].data.slice(),type:e.parentNode.className.baseVal.split("-")[1]}),a.globals.ancillaryCollapsedSeriesIndices.push(i))),!r){a.globals.collapsedSeries.push({index:i,data:s[i].data.slice(),type:e.parentNode.className.baseVal.split("-")[1]}),a.globals.collapsedSeriesIndices.push(i);var o=a.globals.risingSeries.indexOf(i);a.globals.risingSeries.splice(o,1)}}else a.globals.collapsedSeries.push({index:i,data:s[i]}),a.globals.collapsedSeriesIndices.push(i);for(var n=e.childNodes,l=0;l<n.length;l++)n[l].classList.contains("apexcharts-series-markers-wrap")&&(n[l].classList.contains("apexcharts-hide")?n[l].classList.remove("apexcharts-hide"):n[l].classList.add("apexcharts-hide"));a.globals.allSeriesCollapsed=a.globals.collapsedSeries.length===a.config.series.length,s=this._getSeriesBasedOnCollapsedState(s),this.lgCtx.ctx.updateHelpers._updateSeries(s,a.config.chart.animations.dynamicAnimation.enabled)}},{key:"riseCollapsedSeries",value:function(t,e,i){var a=this.w,s=x.clone(a.config.series);if(t.length>0){for(var r=0;r<t.length;r++)t[r].index===i&&(a.globals.axisCharts?(s[i].data=t[r].data.slice(),t.splice(r,1),e.splice(r,1),a.globals.risingSeries.push(i)):(s[i]=t[r].data,t.splice(r,1),e.splice(r,1),a.globals.risingSeries.push(i)));s=this._getSeriesBasedOnCollapsedState(s),this.lgCtx.ctx.updateHelpers._updateSeries(s,a.config.chart.animations.dynamicAnimation.enabled)}}},{key:"_getSeriesBasedOnCollapsedState",value:function(t){var e=this.w;return e.globals.axisCharts?t.forEach((function(i,a){e.globals.collapsedSeriesIndices.indexOf(a)>-1&&(t[a].data=[])})):t.forEach((function(i,a){e.globals.collapsedSeriesIndices.indexOf(a)>-1&&(t[a]=0)})),t}}]),t}(),lt=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.onLegendClick=this.onLegendClick.bind(this),this.onLegendHovered=this.onLegendHovered.bind(this),this.isBarsDistributed="bar"===this.w.config.chart.type&&this.w.config.plotOptions.bar.distributed&&1===this.w.config.series.length,this.legendHelpers=new nt(this)}return r(t,[{key:"init",value:function(){var t=this.w,e=t.globals,i=t.config;if((i.legend.showForSingleSeries&&1===e.series.length||this.isBarsDistributed||e.series.length>1||!e.axisCharts)&&i.legend.show){for(;e.dom.elLegendWrap.firstChild;)e.dom.elLegendWrap.removeChild(e.dom.elLegendWrap.firstChild);this.drawLegends(),"bottom"===i.legend.position||"top"===i.legend.position?this.legendAlignHorizontal():"right"!==i.legend.position&&"left"!==i.legend.position||this.legendAlignVertical()}}},{key:"drawLegends",value:function(){var t=this,e=this.w,i=e.config.legend.fontFamily,a=e.globals.seriesNames,s=e.globals.colors.slice();if("heatmap"===e.config.chart.type){var r=e.config.plotOptions.heatmap.colorScale.ranges;a=r.map((function(t){return t.name?t.name:t.from+" - "+t.to})),s=r.map((function(t){return t.color}))}else this.isBarsDistributed&&(a=e.globals.labels.slice());e.config.legend.customLegendItems.length&&(a=e.config.legend.customLegendItems);for(var o=e.globals.legendFormatter,n=e.config.legend.inverseOrder,l=n?a.length-1:0;n?l>=0:l<=a.length-1;n?l--:l++){var h=o(a[l],{seriesIndex:l,w:e}),c=!1,d=!1;if(e.globals.collapsedSeries.length>0)for(var g=0;g<e.globals.collapsedSeries.length;g++)e.globals.collapsedSeries[g].index===l&&(c=!0);if(e.globals.ancillaryCollapsedSeriesIndices.length>0)for(var u=0;u<e.globals.ancillaryCollapsedSeriesIndices.length;u++)e.globals.ancillaryCollapsedSeriesIndices[u]===l&&(d=!0);var f=document.createElement("span");f.classList.add("apexcharts-legend-marker");var p=e.config.legend.markers.offsetX,b=e.config.legend.markers.offsetY,v=e.config.legend.markers.height,w=e.config.legend.markers.width,k=e.config.legend.markers.strokeWidth,A=e.config.legend.markers.strokeColor,S=e.config.legend.markers.radius,C=f.style;C.background=s[l],C.color=s[l],C.setProperty("background",s[l],"important"),e.config.legend.markers.fillColors&&e.config.legend.markers.fillColors[l]&&(C.background=e.config.legend.markers.fillColors[l]),void 0!==e.globals.seriesColors[l]&&(C.background=e.globals.seriesColors[l],C.color=e.globals.seriesColors[l]),C.height=Array.isArray(v)?parseFloat(v[l])+"px":parseFloat(v)+"px",C.width=Array.isArray(w)?parseFloat(w[l])+"px":parseFloat(w)+"px",C.left=(Array.isArray(p)?parseFloat(p[l]):parseFloat(p))+"px",C.top=(Array.isArray(b)?parseFloat(b[l]):parseFloat(b))+"px",C.borderWidth=Array.isArray(k)?k[l]:k,C.borderColor=Array.isArray(A)?A[l]:A,C.borderRadius=Array.isArray(S)?parseFloat(S[l])+"px":parseFloat(S)+"px",e.config.legend.markers.customHTML&&(Array.isArray(e.config.legend.markers.customHTML)?e.config.legend.markers.customHTML[l]&&(f.innerHTML=e.config.legend.markers.customHTML[l]()):f.innerHTML=e.config.legend.markers.customHTML()),m.setAttrs(f,{rel:l+1,"data:collapsed":c||d}),(c||d)&&f.classList.add("apexcharts-inactive-legend");var L=document.createElement("div"),P=document.createElement("span");P.classList.add("apexcharts-legend-text"),P.innerHTML=Array.isArray(h)?h.join(" "):h;var T=e.config.legend.labels.useSeriesColors?e.globals.colors[l]:e.config.legend.labels.colors;T||(T=e.config.chart.foreColor),P.style.color=T,P.style.fontSize=parseFloat(e.config.legend.fontSize)+"px",P.style.fontWeight=e.config.legend.fontWeight,P.style.fontFamily=i||e.config.chart.fontFamily,m.setAttrs(P,{rel:l+1,i:l,"data:default-text":encodeURIComponent(h),"data:collapsed":c||d}),L.appendChild(f),L.appendChild(P);var M=new y(this.ctx);if(!e.config.legend.showForZeroSeries)0===M.getSeriesTotalByIndex(l)&&M.seriesHaveSameValues(l)&&!M.isSeriesNull(l)&&-1===e.globals.collapsedSeriesIndices.indexOf(l)&&-1===e.globals.ancillaryCollapsedSeriesIndices.indexOf(l)&&L.classList.add("apexcharts-hidden-zero-series");e.config.legend.showForNullSeries||M.isSeriesNull(l)&&-1===e.globals.collapsedSeriesIndices.indexOf(l)&&-1===e.globals.ancillaryCollapsedSeriesIndices.indexOf(l)&&L.classList.add("apexcharts-hidden-null-series"),e.globals.dom.elLegendWrap.appendChild(L),e.globals.dom.elLegendWrap.classList.add("apexcharts-align-".concat(e.config.legend.horizontalAlign)),e.globals.dom.elLegendWrap.classList.add("apx-legend-position-"+e.config.legend.position),L.classList.add("apexcharts-legend-series"),L.style.margin="".concat(e.config.legend.itemMargin.vertical,"px ").concat(e.config.legend.itemMargin.horizontal,"px"),e.globals.dom.elLegendWrap.style.width=e.config.legend.width?e.config.legend.width+"px":"",e.globals.dom.elLegendWrap.style.height=e.config.legend.height?e.config.legend.height+"px":"",m.setAttrs(L,{rel:l+1,seriesName:x.escapeString(a[l]),"data:collapsed":c||d}),(c||d)&&L.classList.add("apexcharts-inactive-legend"),e.config.legend.onItemClick.toggleDataSeries||L.classList.add("apexcharts-no-click")}e.globals.dom.elWrap.addEventListener("click",t.onLegendClick,!0),e.globals.dom.elWrap.appendChild(e.globals.dom.elLegendWrap),e.config.legend.onItemHover.highlightDataSeries&&0===e.config.legend.customLegendItems.length&&(e.globals.dom.elWrap.addEventListener("mousemove",t.onLegendHovered,!0),e.globals.dom.elWrap.addEventListener("mouseout",t.onLegendHovered,!0))}},{key:"setLegendWrapXY",value:function(t,e){var i=this.w,a=i.globals.dom.elLegendWrap,s=a.getBoundingClientRect(),r=0,o=0;if("bottom"===i.config.legend.position)o+=i.globals.svgHeight-s.height/2;else if("top"===i.config.legend.position){var n=new ot(this.ctx),l=n.dimHelpers.getTitleSubtitleCoords("title").height,h=n.dimHelpers.getTitleSubtitleCoords("subtitle").height;o=o+(l>0?l-10:0)+(h>0?h-10:0)}a.style.position="absolute",r=r+t+i.config.legend.offsetX,o=o+e+i.config.legend.offsetY,a.style.left=r+"px",a.style.top=o+"px","bottom"===i.config.legend.position?(a.style.top="auto",a.style.bottom=5-i.config.legend.offsetY+"px"):"right"===i.config.legend.position&&(a.style.left="auto",a.style.right=25+i.config.legend.offsetX+"px");["width","height"].forEach((function(t){a.style[t]&&(a.style[t]=parseInt(i.config.legend[t],10)+"px")}))}},{key:"legendAlignHorizontal",value:function(){var t=this.w;t.globals.dom.elLegendWrap.style.right=0;var e=this.legendHelpers.getLegendBBox(),i=new ot(this.ctx),a=i.dimHelpers.getTitleSubtitleCoords("title"),s=i.dimHelpers.getTitleSubtitleCoords("subtitle"),r=0;"bottom"===t.config.legend.position?r=-e.clwh/1.8:"top"===t.config.legend.position&&(r=a.height+s.height+t.config.title.margin+t.config.subtitle.margin-10),this.setLegendWrapXY(20,r)}},{key:"legendAlignVertical",value:function(){var t=this.w,e=this.legendHelpers.getLegendBBox(),i=0;"left"===t.config.legend.position&&(i=20),"right"===t.config.legend.position&&(i=t.globals.svgWidth-e.clww-10),this.setLegendWrapXY(i,20)}},{key:"onLegendHovered",value:function(t){var e=this.w,i=t.target.classList.contains("apexcharts-legend-text")||t.target.classList.contains("apexcharts-legend-marker");if("heatmap"===e.config.chart.type||this.isBarsDistributed){if(i){var a=parseInt(t.target.getAttribute("rel"),10)-1;this.ctx.events.fireEvent("legendHover",[this.ctx,a,this.w]),new N(this.ctx).highlightRangeInSeries(t,t.target)}}else!t.target.classList.contains("apexcharts-inactive-legend")&&i&&new N(this.ctx).toggleSeriesOnHover(t,t.target)}},{key:"onLegendClick",value:function(t){var e=this.w;if(!e.config.legend.customLegendItems.length&&(t.target.classList.contains("apexcharts-legend-text")||t.target.classList.contains("apexcharts-legend-marker"))){var i=parseInt(t.target.getAttribute("rel"),10)-1,a="true"===t.target.getAttribute("data:collapsed"),s=this.w.config.chart.events.legendClick;"function"==typeof s&&s(this.ctx,i,this.w),this.ctx.events.fireEvent("legendClick",[this.ctx,i,this.w]);var r=this.w.config.legend.markers.onClick;"function"==typeof r&&t.target.classList.contains("apexcharts-legend-marker")&&(r(this.ctx,i,this.w),this.ctx.events.fireEvent("legendMarkerClick",[this.ctx,i,this.w])),"treemap"!==e.config.chart.type&&"heatmap"!==e.config.chart.type&&!this.isBarsDistributed&&e.config.legend.onItemClick.toggleDataSeries&&this.legendHelpers.toggleDataSeries(i,a)}}}]),t}(),ht=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w;var i=this.w;this.ev=this.w.config.chart.events,this.selectedClass="apexcharts-selected",this.localeValues=this.w.globals.locale.toolbar,this.minX=i.globals.minX,this.maxX=i.globals.maxX}return r(t,[{key:"createToolbar",value:function(){var t=this,e=this.w,i=function(){return document.createElement("div")},a=i();if(a.setAttribute("class","apexcharts-toolbar"),a.style.top=e.config.chart.toolbar.offsetY+"px",a.style.right=3-e.config.chart.toolbar.offsetX+"px",e.globals.dom.elWrap.appendChild(a),this.elZoom=i(),this.elZoomIn=i(),this.elZoomOut=i(),this.elPan=i(),this.elSelection=i(),this.elZoomReset=i(),this.elMenuIcon=i(),this.elMenu=i(),this.elCustomIcons=[],this.t=e.config.chart.toolbar.tools,Array.isArray(this.t.customIcons))for(var s=0;s<this.t.customIcons.length;s++)this.elCustomIcons.push(i());var r=[],o=function(i,a,s){var o=i.toLowerCase();t.t[o]&&e.config.chart.zoom.enabled&&r.push({el:a,icon:"string"==typeof t.t[o]?t.t[o]:s,title:t.localeValues[i],class:"apexcharts-".concat(o,"-icon")})};o("zoomIn",this.elZoomIn,'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">\n    <path d="M0 0h24v24H0z" fill="none"/>\n    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>\n</svg>\n'),o("zoomOut",this.elZoomOut,'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">\n    <path d="M0 0h24v24H0z" fill="none"/>\n    <path d="M7 11v2h10v-2H7zm5-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>\n</svg>\n');var n=function(i){t.t[i]&&e.config.chart[i].enabled&&r.push({el:"zoom"===i?t.elZoom:t.elSelection,icon:"string"==typeof t.t[i]?t.t[i]:"zoom"===i?'<svg xmlns="http://www.w3.org/2000/svg" fill="#000000" height="24" viewBox="0 0 24 24" width="24">\n    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>\n    <path d="M0 0h24v24H0V0z" fill="none"/>\n    <path d="M12 10h-2v2H9v-2H7V9h2V7h1v2h2v1z"/>\n</svg>':'<svg fill="#6E8192" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">\n    <path d="M0 0h24v24H0z" fill="none"/>\n    <path d="M3 5h2V3c-1.1 0-2 .9-2 2zm0 8h2v-2H3v2zm4 8h2v-2H7v2zM3 9h2V7H3v2zm10-6h-2v2h2V3zm6 0v2h2c0-1.1-.9-2-2-2zM5 21v-2H3c0 1.1.9 2 2 2zm-2-4h2v-2H3v2zM9 3H7v2h2V3zm2 18h2v-2h-2v2zm8-8h2v-2h-2v2zm0 8c1.1 0 2-.9 2-2h-2v2zm0-12h2V7h-2v2zm0 8h2v-2h-2v2zm-4 4h2v-2h-2v2zm0-16h2V3h-2v2z"/>\n</svg>',title:t.localeValues["zoom"===i?"selectionZoom":"selection"],class:e.globals.isTouchDevice?"apexcharts-element-hidden":"apexcharts-".concat(i,"-icon")})};n("zoom"),n("selection"),this.t.pan&&e.config.chart.zoom.enabled&&r.push({el:this.elPan,icon:"string"==typeof this.t.pan?this.t.pan:'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="24" viewBox="0 0 24 24" width="24">\n    <defs>\n        <path d="M0 0h24v24H0z" id="a"/>\n    </defs>\n    <clipPath id="b">\n        <use overflow="visible" xlink:href="#a"/>\n    </clipPath>\n    <path clip-path="url(#b)" d="M23 5.5V20c0 2.2-1.8 4-4 4h-7.3c-1.08 0-2.1-.43-2.85-1.19L1 14.83s1.26-1.23 1.3-1.25c.22-.19.49-.29.79-.29.22 0 .42.06.6.16.04.01 4.31 2.46 4.31 2.46V4c0-.83.67-1.5 1.5-1.5S11 3.17 11 4v7h1V1.5c0-.83.67-1.5 1.5-1.5S15 .67 15 1.5V11h1V2.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5V11h1V5.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5z"/>\n</svg>',title:this.localeValues.pan,class:e.globals.isTouchDevice?"apexcharts-element-hidden":"apexcharts-pan-icon"}),o("reset",this.elZoomReset,'<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">\n    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>\n    <path d="M0 0h24v24H0z" fill="none"/>\n</svg>'),this.t.download&&r.push({el:this.elMenuIcon,icon:"string"==typeof this.t.download?this.t.download:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',title:this.localeValues.menu,class:"apexcharts-menu-icon"});for(var l=0;l<this.elCustomIcons.length;l++)r.push({el:this.elCustomIcons[l],icon:this.t.customIcons[l].icon,title:this.t.customIcons[l].title,index:this.t.customIcons[l].index,class:"apexcharts-toolbar-custom-icon "+this.t.customIcons[l].class});r.forEach((function(t,e){t.index&&x.moveIndexInArray(r,e,t.index)}));for(var h=0;h<r.length;h++)m.setAttrs(r[h].el,{class:r[h].class,title:r[h].title}),r[h].el.innerHTML=r[h].icon,a.appendChild(r[h].el);this._createHamburgerMenu(a),e.globals.zoomEnabled?this.elZoom.classList.add(this.selectedClass):e.globals.panEnabled?this.elPan.classList.add(this.selectedClass):e.globals.selectionEnabled&&this.elSelection.classList.add(this.selectedClass),this.addToolbarEventListeners()}},{key:"_createHamburgerMenu",value:function(t){this.elMenuItems=[],t.appendChild(this.elMenu),m.setAttrs(this.elMenu,{class:"apexcharts-menu"});var e=[{name:"exportSVG",title:this.localeValues.exportToSVG},{name:"exportPNG",title:this.localeValues.exportToPNG},{name:"exportCSV",title:this.localeValues.exportToCSV}];this.w.globals.allSeriesHasEqualX||e.splice(2,1);for(var i=0;i<e.length;i++)this.elMenuItems.push(document.createElement("div")),this.elMenuItems[i].innerHTML=e[i].title,m.setAttrs(this.elMenuItems[i],{class:"apexcharts-menu-item ".concat(e[i].name),title:e[i].title}),this.elMenu.appendChild(this.elMenuItems[i])}},{key:"addToolbarEventListeners",value:function(){var t=this;this.elZoomReset.addEventListener("click",this.handleZoomReset.bind(this)),this.elSelection.addEventListener("click",this.toggleZoomSelection.bind(this,"selection")),this.elZoom.addEventListener("click",this.toggleZoomSelection.bind(this,"zoom")),this.elZoomIn.addEventListener("click",this.handleZoomIn.bind(this)),this.elZoomOut.addEventListener("click",this.handleZoomOut.bind(this)),this.elPan.addEventListener("click",this.togglePanning.bind(this)),this.elMenuIcon.addEventListener("click",this.toggleMenu.bind(this)),this.elMenuItems.forEach((function(e){e.classList.contains("exportSVG")?e.addEventListener("click",t.handleDownload.bind(t,"svg")):e.classList.contains("exportPNG")?e.addEventListener("click",t.handleDownload.bind(t,"png")):e.classList.contains("exportCSV")&&e.addEventListener("click",t.handleDownload.bind(t,"csv"))}));for(var e=0;e<this.t.customIcons.length;e++)this.elCustomIcons[e].addEventListener("click",this.t.customIcons[e].click.bind(this,this.ctx,this.ctx.w))}},{key:"toggleZoomSelection",value:function(t){this.ctx.getSyncedCharts().forEach((function(e){e.ctx.toolbar.toggleOtherControls();var i="selection"===t?e.ctx.toolbar.elSelection:e.ctx.toolbar.elZoom,a="selection"===t?"selectionEnabled":"zoomEnabled";e.w.globals[a]=!e.w.globals[a],i.classList.contains(e.ctx.toolbar.selectedClass)?i.classList.remove(e.ctx.toolbar.selectedClass):i.classList.add(e.ctx.toolbar.selectedClass)}))}},{key:"getToolbarIconsReference",value:function(){var t=this.w;this.elZoom||(this.elZoom=t.globals.dom.baseEl.querySelector(".apexcharts-zoom-icon")),this.elPan||(this.elPan=t.globals.dom.baseEl.querySelector(".apexcharts-pan-icon")),this.elSelection||(this.elSelection=t.globals.dom.baseEl.querySelector(".apexcharts-selection-icon"))}},{key:"enableZoomPanFromToolbar",value:function(t){this.toggleOtherControls(),"pan"===t?this.w.globals.panEnabled=!0:this.w.globals.zoomEnabled=!0;var e="pan"===t?this.elPan:this.elZoom,i="pan"===t?this.elZoom:this.elPan;e&&e.classList.add(this.selectedClass),i&&i.classList.remove(this.selectedClass)}},{key:"togglePanning",value:function(){this.ctx.getSyncedCharts().forEach((function(t){t.ctx.toolbar.toggleOtherControls(),t.w.globals.panEnabled=!t.w.globals.panEnabled,t.ctx.toolbar.elPan.classList.contains(t.ctx.toolbar.selectedClass)?t.ctx.toolbar.elPan.classList.remove(t.ctx.toolbar.selectedClass):t.ctx.toolbar.elPan.classList.add(t.ctx.toolbar.selectedClass)}))}},{key:"toggleOtherControls",value:function(){var t=this,e=this.w;e.globals.panEnabled=!1,e.globals.zoomEnabled=!1,e.globals.selectionEnabled=!1,this.getToolbarIconsReference(),[this.elPan,this.elSelection,this.elZoom].forEach((function(e){e&&e.classList.remove(t.selectedClass)}))}},{key:"handleZoomIn",value:function(){var t=this.w;t.globals.isRangeBar&&(this.minX=t.globals.minY,this.maxX=t.globals.maxY);var e=(this.minX+this.maxX)/2,i=(this.minX+e)/2,a=(this.maxX+e)/2,s=this._getNewMinXMaxX(i,a);t.globals.disableZoomIn||this.zoomUpdateOptions(s.minX,s.maxX)}},{key:"handleZoomOut",value:function(){var t=this.w;if(t.globals.isRangeBar&&(this.minX=t.globals.minY,this.maxX=t.globals.maxY),!("datetime"===t.config.xaxis.type&&new Date(this.minX).getUTCFullYear()<1e3)){var e=(this.minX+this.maxX)/2,i=this.minX-(e-this.minX),a=this.maxX-(e-this.maxX),s=this._getNewMinXMaxX(i,a);t.globals.disableZoomOut||this.zoomUpdateOptions(s.minX,s.maxX)}}},{key:"_getNewMinXMaxX",value:function(t,e){var i=this.w.config.xaxis.convertedCatToNumeric;return{minX:i?Math.floor(t):t,maxX:i?Math.floor(e):e}}},{key:"zoomUpdateOptions",value:function(t,e){var i=this.w;if(void 0!==t||void 0!==e){if(!(i.config.xaxis.convertedCatToNumeric&&(t<1&&(t=1,e=i.globals.dataPoints),e-t<2))){var a={min:t,max:e},s=this.getBeforeZoomRange(a);s&&(a=s.xaxis);var r={xaxis:a},o=x.clone(i.globals.initialConfig.yaxis);if(i.config.chart.zoom.autoScaleYaxis)o=new _(this.ctx).autoScaleY(this.ctx,o,{xaxis:a});i.config.chart.group||(r.yaxis=o),this.w.globals.zoomed=!0,this.ctx.updateHelpers._updateOptions(r,!1,this.w.config.chart.animations.dynamicAnimation.enabled),this.zoomCallback(a,o)}}else this.handleZoomReset()}},{key:"zoomCallback",value:function(t,e){"function"==typeof this.ev.zoomed&&this.ev.zoomed(this.ctx,{xaxis:t,yaxis:e})}},{key:"getBeforeZoomRange",value:function(t,e){var i=null;return"function"==typeof this.ev.beforeZoom&&(i=this.ev.beforeZoom(this,{xaxis:t,yaxis:e})),i}},{key:"toggleMenu",value:function(){var t=this;window.setTimeout((function(){t.elMenu.classList.contains("apexcharts-menu-open")?t.elMenu.classList.remove("apexcharts-menu-open"):t.elMenu.classList.add("apexcharts-menu-open")}),0)}},{key:"handleDownload",value:function(t){var e=this.w,i=new G(this.ctx);switch(t){case"svg":i.exportToSVG(this.ctx);break;case"png":i.exportToPng(this.ctx);break;case"csv":i.exportToCSV({series:e.config.series,columnDelimiter:e.config.chart.toolbar.export.csv.columnDelimiter})}}},{key:"handleZoomReset",value:function(t){this.ctx.getSyncedCharts().forEach((function(t){var e=t.w;if(e.globals.lastXAxis.min=void 0,e.globals.lastXAxis.max=void 0,t.updateHelpers.revertDefaultAxisMinMax(),"function"==typeof e.config.chart.events.beforeResetZoom){var i=e.config.chart.events.beforeResetZoom(t,e);i&&t.updateHelpers.revertDefaultAxisMinMax(i)}"function"==typeof e.config.chart.events.zoomed&&t.ctx.toolbar.zoomCallback({min:e.config.xaxis.min,max:e.config.xaxis.max}),e.globals.zoomed=!1;var a=t.ctx.series.emptyCollapsedSeries(x.clone(e.globals.initialSeries));t.updateHelpers._updateSeries(a,e.config.chart.animations.dynamicAnimation.enabled)}))}},{key:"destroy",value:function(){this.elZoom=null,this.elZoomIn=null,this.elZoomOut=null,this.elPan=null,this.elSelection=null,this.elZoomReset=null,this.elMenuIcon=null}}]),t}(),ct=function(t){n(i,ht);var e=d(i);function i(t){var s;return a(this,i),(s=e.call(this,t)).ctx=t,s.w=t.w,s.dragged=!1,s.graphics=new m(s.ctx),s.eventList=["mousedown","mouseleave","mousemove","touchstart","touchmove","mouseup","touchend"],s.clientX=0,s.clientY=0,s.startX=0,s.endX=0,s.dragX=0,s.startY=0,s.endY=0,s.dragY=0,s.moveDirection="none",s}return r(i,[{key:"init",value:function(t){var e=this,i=t.xyRatios,a=this.w,s=this;this.xyRatios=i,this.zoomRect=this.graphics.drawRect(0,0,0,0),this.selectionRect=this.graphics.drawRect(0,0,0,0),this.gridRect=a.globals.dom.baseEl.querySelector(".apexcharts-grid"),this.zoomRect.node.classList.add("apexcharts-zoom-rect"),this.selectionRect.node.classList.add("apexcharts-selection-rect"),a.globals.dom.elGraphical.add(this.zoomRect),a.globals.dom.elGraphical.add(this.selectionRect),"x"===a.config.chart.selection.type?this.slDraggableRect=this.selectionRect.draggable({minX:0,minY:0,maxX:a.globals.gridWidth,maxY:a.globals.gridHeight}).on("dragmove",this.selectionDragging.bind(this,"dragging")):"y"===a.config.chart.selection.type?this.slDraggableRect=this.selectionRect.draggable({minX:0,maxX:a.globals.gridWidth}).on("dragmove",this.selectionDragging.bind(this,"dragging")):this.slDraggableRect=this.selectionRect.draggable().on("dragmove",this.selectionDragging.bind(this,"dragging")),this.preselectedSelection(),this.hoverArea=a.globals.dom.baseEl.querySelector("".concat(a.globals.chartClass," .apexcharts-svg")),this.hoverArea.classList.add("apexcharts-zoomable"),this.eventList.forEach((function(t){e.hoverArea.addEventListener(t,s.svgMouseEvents.bind(s,i),{capture:!1,passive:!0})}))}},{key:"destroy",value:function(){this.slDraggableRect&&(this.slDraggableRect.draggable(!1),this.slDraggableRect.off(),this.selectionRect.off()),this.selectionRect=null,this.zoomRect=null,this.gridRect=null}},{key:"svgMouseEvents",value:function(t,e){var i=this.w,a=this,s=this.ctx.toolbar,r=i.globals.zoomEnabled?i.config.chart.zoom.type:i.config.chart.selection.type,o=i.config.chart.toolbar.autoSelected;if(e.shiftKey?(this.shiftWasPressed=!0,s.enableZoomPanFromToolbar("pan"===o?"zoom":"pan")):this.shiftWasPressed&&(s.enableZoomPanFromToolbar(o),this.shiftWasPressed=!1),e.target){var n,l=e.target.classList;if(e.target.parentNode&&null!==e.target.parentNode&&(n=e.target.parentNode.classList),!(l.contains("apexcharts-selection-rect")||l.contains("apexcharts-legend-marker")||l.contains("apexcharts-legend-text")||n&&n.contains("apexcharts-toolbar"))){if(a.clientX="touchmove"===e.type||"touchstart"===e.type?e.touches[0].clientX:"touchend"===e.type?e.changedTouches[0].clientX:e.clientX,a.clientY="touchmove"===e.type||"touchstart"===e.type?e.touches[0].clientY:"touchend"===e.type?e.changedTouches[0].clientY:e.clientY,"mousedown"===e.type&&1===e.which){var h=a.gridRect.getBoundingClientRect();a.startX=a.clientX-h.left,a.startY=a.clientY-h.top,a.dragged=!1,a.w.globals.mousedown=!0}if(("mousemove"===e.type&&1===e.which||"touchmove"===e.type)&&(a.dragged=!0,i.globals.panEnabled?(i.globals.selection=null,a.w.globals.mousedown&&a.panDragging({context:a,zoomtype:r,xyRatios:t})):(a.w.globals.mousedown&&i.globals.zoomEnabled||a.w.globals.mousedown&&i.globals.selectionEnabled)&&(a.selection=a.selectionDrawing({context:a,zoomtype:r}))),"mouseup"===e.type||"touchend"===e.type||"mouseleave"===e.type){var c=a.gridRect.getBoundingClientRect();a.w.globals.mousedown&&(a.endX=a.clientX-c.left,a.endY=a.clientY-c.top,a.dragX=Math.abs(a.endX-a.startX),a.dragY=Math.abs(a.endY-a.startY),(i.globals.zoomEnabled||i.globals.selectionEnabled)&&a.selectionDrawn({context:a,zoomtype:r}),i.globals.panEnabled&&i.config.xaxis.convertedCatToNumeric&&a.delayedPanScrolled()),i.globals.zoomEnabled&&a.hideSelectionRect(this.selectionRect),a.dragged=!1,a.w.globals.mousedown=!1}this.makeSelectionRectDraggable()}}}},{key:"makeSelectionRectDraggable",value:function(){var t=this.w;if(this.selectionRect){var e=this.selectionRect.node.getBoundingClientRect();e.width>0&&e.height>0&&this.slDraggableRect.selectize({points:"l, r",pointSize:8,pointType:"rect"}).resize({constraint:{minX:0,minY:0,maxX:t.globals.gridWidth,maxY:t.globals.gridHeight}}).on("resizing",this.selectionDragging.bind(this,"resizing"))}}},{key:"preselectedSelection",value:function(){var t=this.w,e=this.xyRatios;if(!t.globals.zoomEnabled)if(void 0!==t.globals.selection&&null!==t.globals.selection)this.drawSelectionRect(t.globals.selection);else if(void 0!==t.config.chart.selection.xaxis.min&&void 0!==t.config.chart.selection.xaxis.max){var i=(t.config.chart.selection.xaxis.min-t.globals.minX)/e.xRatio,a={x:i,y:0,width:t.globals.gridWidth-(t.globals.maxX-t.config.chart.selection.xaxis.max)/e.xRatio-i,height:t.globals.gridHeight,translateX:0,translateY:0,selectionEnabled:!0};this.drawSelectionRect(a),this.makeSelectionRectDraggable(),"function"==typeof t.config.chart.events.selection&&t.config.chart.events.selection(this.ctx,{xaxis:{min:t.config.chart.selection.xaxis.min,max:t.config.chart.selection.xaxis.max},yaxis:{}})}}},{key:"drawSelectionRect",value:function(t){var e=t.x,i=t.y,a=t.width,s=t.height,r=t.translateX,o=void 0===r?0:r,n=t.translateY,l=void 0===n?0:n,h=this.w,c=this.zoomRect,d=this.selectionRect;if(this.dragged||null!==h.globals.selection){var g={transform:"translate("+o+", "+l+")"};h.globals.zoomEnabled&&this.dragged&&(a<0&&(a=1),c.attr({x:e,y:i,width:a,height:s,fill:h.config.chart.zoom.zoomedArea.fill.color,"fill-opacity":h.config.chart.zoom.zoomedArea.fill.opacity,stroke:h.config.chart.zoom.zoomedArea.stroke.color,"stroke-width":h.config.chart.zoom.zoomedArea.stroke.width,"stroke-opacity":h.config.chart.zoom.zoomedArea.stroke.opacity}),m.setAttrs(c.node,g)),h.globals.selectionEnabled&&(d.attr({x:e,y:i,width:a>0?a:0,height:s>0?s:0,fill:h.config.chart.selection.fill.color,"fill-opacity":h.config.chart.selection.fill.opacity,stroke:h.config.chart.selection.stroke.color,"stroke-width":h.config.chart.selection.stroke.width,"stroke-dasharray":h.config.chart.selection.stroke.dashArray,"stroke-opacity":h.config.chart.selection.stroke.opacity}),m.setAttrs(d.node,g))}}},{key:"hideSelectionRect",value:function(t){t&&t.attr({x:0,y:0,width:0,height:0})}},{key:"selectionDrawing",value:function(t){var e=t.context,i=t.zoomtype,a=this.w,s=e,r=this.gridRect.getBoundingClientRect(),o=s.startX-1,n=s.startY,l=!1,h=!1,c=s.clientX-r.left-o,d=s.clientY-r.top-n,g={};return Math.abs(c+o)>a.globals.gridWidth?c=a.globals.gridWidth-o:s.clientX-r.left<0&&(c=o),o>s.clientX-r.left&&(l=!0,c=Math.abs(c)),n>s.clientY-r.top&&(h=!0,d=Math.abs(d)),g="x"===i?{x:l?o-c:o,y:0,width:c,height:a.globals.gridHeight}:"y"===i?{x:0,y:h?n-d:n,width:a.globals.gridWidth,height:d}:{x:l?o-c:o,y:h?n-d:n,width:c,height:d},s.drawSelectionRect(g),s.selectionDragging("resizing"),g}},{key:"selectionDragging",value:function(t,e){var i=this,a=this.w,s=this.xyRatios,r=this.selectionRect,o=0;"resizing"===t&&(o=30);var n=function(t){return parseFloat(r.node.getAttribute(t))},l={x:n("x"),y:n("y"),width:n("width"),height:n("height")};a.globals.selection=l,"function"==typeof a.config.chart.events.selection&&a.globals.selectionEnabled&&(clearTimeout(this.w.globals.selectionResizeTimer),this.w.globals.selectionResizeTimer=window.setTimeout((function(){var t=i.gridRect.getBoundingClientRect(),e=r.node.getBoundingClientRect(),o={xaxis:{min:a.globals.xAxisScale.niceMin+(e.left-t.left)*s.xRatio,max:a.globals.xAxisScale.niceMin+(e.right-t.left)*s.xRatio},yaxis:{min:a.globals.yAxisScale[0].niceMin+(t.bottom-e.bottom)*s.yRatio[0],max:a.globals.yAxisScale[0].niceMax-(e.top-t.top)*s.yRatio[0]}};a.config.chart.events.selection(i.ctx,o),a.config.chart.brush.enabled&&void 0!==a.config.chart.events.brushScrolled&&a.config.chart.events.brushScrolled(i.ctx,o)}),o))}},{key:"selectionDrawn",value:function(t){var e=t.context,i=t.zoomtype,a=this.w,s=e,r=this.xyRatios,o=this.ctx.toolbar;if(s.startX>s.endX){var n=s.startX;s.startX=s.endX,s.endX=n}if(s.startY>s.endY){var l=s.startY;s.startY=s.endY,s.endY=l}var h=void 0,c=void 0;a.globals.isRangeBar?(h=a.globals.yAxisScale[0].niceMin+s.startX*r.invertedYRatio,c=a.globals.yAxisScale[0].niceMin+s.endX*r.invertedYRatio):(h=a.globals.xAxisScale.niceMin+s.startX*r.xRatio,c=a.globals.xAxisScale.niceMin+s.endX*r.xRatio);var d=[],g=[];if(a.config.yaxis.forEach((function(t,e){d.push(a.globals.yAxisScale[e].niceMax-r.yRatio[e]*s.startY),g.push(a.globals.yAxisScale[e].niceMax-r.yRatio[e]*s.endY)})),s.dragged&&(s.dragX>10||s.dragY>10)&&h!==c)if(a.globals.zoomEnabled){var u=x.clone(a.globals.initialConfig.yaxis),f=x.clone(a.globals.initialConfig.xaxis);if(a.globals.zoomed=!0,a.config.xaxis.convertedCatToNumeric&&(h=Math.floor(h),c=Math.floor(c),h<1&&(h=1,c=a.globals.dataPoints),c-h<2&&(c=h+1)),"xy"!==i&&"x"!==i||(f={min:h,max:c}),"xy"!==i&&"y"!==i||u.forEach((function(t,e){u[e].min=g[e],u[e].max=d[e]})),a.config.chart.zoom.autoScaleYaxis){var p=new _(s.ctx);u=p.autoScaleY(s.ctx,u,{xaxis:f})}if(o){var b=o.getBeforeZoomRange(f,u);b&&(f=b.xaxis?b.xaxis:f,u=b.yaxis?b.yaxis:u)}var v={xaxis:f};a.config.chart.group||(v.yaxis=u),s.ctx.updateHelpers._updateOptions(v,!1,s.w.config.chart.animations.dynamicAnimation.enabled),"function"==typeof a.config.chart.events.zoomed&&o.zoomCallback(f,u)}else if(a.globals.selectionEnabled){var m,y=null;m={min:h,max:c},"xy"!==i&&"y"!==i||(y=x.clone(a.config.yaxis)).forEach((function(t,e){y[e].min=g[e],y[e].max=d[e]})),a.globals.selection=s.selection,"function"==typeof a.config.chart.events.selection&&a.config.chart.events.selection(s.ctx,{xaxis:m,yaxis:y})}}},{key:"panDragging",value:function(t){var e=t.context,i=this.w,a=e;if(void 0!==i.globals.lastClientPosition.x){var s=i.globals.lastClientPosition.x-a.clientX,r=i.globals.lastClientPosition.y-a.clientY;Math.abs(s)>Math.abs(r)&&s>0?this.moveDirection="left":Math.abs(s)>Math.abs(r)&&s<0?this.moveDirection="right":Math.abs(r)>Math.abs(s)&&r>0?this.moveDirection="up":Math.abs(r)>Math.abs(s)&&r<0&&(this.moveDirection="down")}i.globals.lastClientPosition={x:a.clientX,y:a.clientY};var o=i.globals.isRangeBar?i.globals.minY:i.globals.minX,n=i.globals.isRangeBar?i.globals.maxY:i.globals.maxX;i.config.xaxis.convertedCatToNumeric||a.panScrolled(o,n)}},{key:"delayedPanScrolled",value:function(){var t=this.w,e=t.globals.minX,i=t.globals.maxX,a=(t.globals.maxX-t.globals.minX)/2;"left"===this.moveDirection?(e=t.globals.minX+a,i=t.globals.maxX+a):"right"===this.moveDirection&&(e=t.globals.minX-a,i=t.globals.maxX-a),e=Math.floor(e),i=Math.floor(i),this.updateScrolledChart({xaxis:{min:e,max:i}},e,i)}},{key:"panScrolled",value:function(t,e){var i=this.w,a=this.xyRatios,s=x.clone(i.globals.initialConfig.yaxis),r=a.xRatio,o=i.globals.minX,n=i.globals.maxX;i.globals.isRangeBar&&(r=a.invertedYRatio,o=i.globals.minY,n=i.globals.maxY),"left"===this.moveDirection?(t=o+i.globals.gridWidth/15*r,e=n+i.globals.gridWidth/15*r):"right"===this.moveDirection&&(t=o-i.globals.gridWidth/15*r,e=n-i.globals.gridWidth/15*r),i.globals.isRangeBar||(t<i.globals.initialMinX||e>i.globals.initialMaxX)&&(t=o,e=n);var l={min:t,max:e};i.config.chart.zoom.autoScaleYaxis&&(s=new _(this.ctx).autoScaleY(this.ctx,s,{xaxis:l}));var h={xaxis:{min:t,max:e}};i.config.chart.group||(h.yaxis=s),this.updateScrolledChart(h,t,e)}},{key:"updateScrolledChart",value:function(t,e,i){var a=this.w;this.ctx.updateHelpers._updateOptions(t,!1,!1),"function"==typeof a.config.chart.events.scrolled&&a.config.chart.events.scrolled(this.ctx,{xaxis:{min:e,max:i}})}}]),i}(),dt=function(){function t(e){a(this,t),this.w=e.w,this.ttCtx=e,this.ctx=e.ctx}return r(t,[{key:"getNearestValues",value:function(t){var e=t.hoverArea,i=t.elGrid,a=t.clientX,s=t.clientY,r=this.w,o=i.getBoundingClientRect(),n=o.width,l=o.height,h=n/(r.globals.dataPoints-1),c=l/r.globals.dataPoints,d=this.hasBars();!r.globals.comboCharts&&!d||r.config.xaxis.convertedCatToNumeric||(h=n/r.globals.dataPoints);var g=a-o.left-r.globals.barPadForNumericAxis,u=s-o.top;g<0||u<0||g>n||u>l?(e.classList.remove("hovering-zoom"),e.classList.remove("hovering-pan")):r.globals.zoomEnabled?(e.classList.remove("hovering-pan"),e.classList.add("hovering-zoom")):r.globals.panEnabled&&(e.classList.remove("hovering-zoom"),e.classList.add("hovering-pan"));var f=Math.round(g/h),p=Math.floor(u/c);d&&!r.config.xaxis.convertedCatToNumeric&&(f=Math.ceil(g/h),f-=1);var b=null,v=null,m=[],y=[];if(r.globals.seriesXvalues.forEach((function(t){m.push([t[0]+1e-6].concat(t))})),r.globals.seriesYvalues.forEach((function(t){y.push([t[0]+1e-6].concat(t))})),m=m.map((function(t){return t.filter((function(t){return x.isNumber(t)}))})),y=y.map((function(t){return t.filter((function(t){return x.isNumber(t)}))})),r.globals.isXNumeric){var w=this.ttCtx.getElGrid().getBoundingClientRect(),k=g*(w.width/n),A=u*(w.height/l);b=(v=this.closestInMultiArray(k,A,m,y)).index,f=v.j,null!==b&&(m=r.globals.seriesXvalues[b],f=(v=this.closestInArray(k,m)).index)}return r.globals.capturedSeriesIndex=null===b?-1:b,(!f||f<1)&&(f=0),r.globals.isBarHorizontal?r.globals.capturedDataPointIndex=p:r.globals.capturedDataPointIndex=f,{capturedSeries:b,j:r.globals.isBarHorizontal?p:f,hoverX:g,hoverY:u}}},{key:"closestInMultiArray",value:function(t,e,i,a){var s=this.w,r=0,o=null,n=-1;s.globals.series.length>1?r=this.getFirstActiveXArray(i):o=0;var l=i[r][0],h=Math.abs(t-l);if(i.forEach((function(e){e.forEach((function(e,i){var a=Math.abs(t-e);a<h&&(h=a,n=i)}))})),-1!==n){var c=a[r][n],d=Math.abs(e-c);o=r,a.forEach((function(t,i){var a=Math.abs(e-t[n]);a<d&&(d=a,o=i)}))}return{index:o,j:n}}},{key:"getFirstActiveXArray",value:function(t){for(var e=this.w,i=0,a=t.map((function(t,e){return t.length>0?e:-1})),s=0;s<a.length;s++)if(-1!==a[s]&&-1===e.globals.collapsedSeriesIndices.indexOf(s)&&-1===e.globals.ancillaryCollapsedSeriesIndices.indexOf(s)){i=a[s];break}return i}},{key:"closestInArray",value:function(t,e){for(var i=e[0],a=null,s=Math.abs(t-i),r=0;r<e.length;r++){var o=Math.abs(t-e[r]);o<s&&(s=o,a=r)}return{index:a}}},{key:"isXoverlap",value:function(t){var e=[],i=this.w.globals.seriesX.filter((function(t){return void 0!==t[0]}));if(i.length>0)for(var a=0;a<i.length-1;a++)void 0!==i[a][t]&&void 0!==i[a+1][t]&&i[a][t]!==i[a+1][t]&&e.push("unEqual");return 0===e.length}},{key:"isInitialSeriesSameLen",value:function(){for(var t=!0,e=this.w.globals.initialSeries,i=0;i<e.length-1;i++)if(e[i].data.length!==e[i+1].data.length){t=!1;break}return t}},{key:"getBarsHeight",value:function(t){return u(t).reduce((function(t,e){return t+e.getBBox().height}),0)}},{key:"getElMarkers",value:function(){return this.w.globals.dom.baseEl.querySelectorAll(" .apexcharts-series-markers")}},{key:"getAllMarkers",value:function(){var t=this.w.globals.dom.baseEl.querySelectorAll(".apexcharts-series-markers-wrap");(t=u(t)).sort((function(t,e){var i=Number(t.getAttribute("data:realIndex")),a=Number(e.getAttribute("data:realIndex"));return a<i?1:a>i?-1:0}));var e=[];return t.forEach((function(t){e.push(t.querySelector(".apexcharts-marker"))})),e}},{key:"hasMarkers",value:function(){return this.getElMarkers().length>0}},{key:"getElBars",value:function(){return this.w.globals.dom.baseEl.querySelectorAll(".apexcharts-bar-series,  .apexcharts-candlestick-series, .apexcharts-boxPlot-series, .apexcharts-rangebar-series")}},{key:"hasBars",value:function(){return this.getElBars().length>0}},{key:"getHoverMarkerSize",value:function(t){var e=this.w,i=e.config.markers.hover.size;return void 0===i&&(i=e.globals.markers.size[t]+e.config.markers.hover.sizeOffset),i}},{key:"toggleAllTooltipSeriesGroups",value:function(t){var e=this.w,i=this.ttCtx;0===i.allTooltipSeriesGroups.length&&(i.allTooltipSeriesGroups=e.globals.dom.baseEl.querySelectorAll(".apexcharts-tooltip-series-group"));for(var a=i.allTooltipSeriesGroups,s=0;s<a.length;s++)"enable"===t?(a[s].classList.add("apexcharts-active"),a[s].style.display=e.config.tooltip.items.display):(a[s].classList.remove("apexcharts-active"),a[s].style.display="none")}}]),t}(),gt=function(){function t(e){a(this,t),this.w=e.w,this.ctx=e.ctx,this.ttCtx=e,this.tooltipUtil=new dt(e)}return r(t,[{key:"drawSeriesTexts",value:function(t){var e=t.shared,i=void 0===e||e,a=t.ttItems,s=t.i,r=void 0===s?0:s,o=t.j,n=void 0===o?null:o,l=t.y1,h=t.y2,c=t.e,d=this.w;void 0!==d.config.tooltip.custom?this.handleCustomTooltip({i:r,j:n,y1:l,y2:h,w:d}):this.toggleActiveInactiveSeries(i);var g=this.getValuesToPrint({i:r,j:n});this.printLabels({i:r,j:n,values:g,ttItems:a,shared:i,e:c});var u=this.ttCtx.getElTooltip();this.ttCtx.tooltipRect.ttWidth=u.getBoundingClientRect().width,this.ttCtx.tooltipRect.ttHeight=u.getBoundingClientRect().height}},{key:"printLabels",value:function(t){var i,a=this,s=t.i,r=t.j,o=t.values,n=t.ttItems,l=t.shared,h=t.e,c=this.w,d=[],g=function(t){return c.globals.seriesGoals[t]&&c.globals.seriesGoals[t][r]&&Array.isArray(c.globals.seriesGoals[t][r])},u=o.xVal,f=o.zVal,p=o.xAxisTTVal,x="",b=c.globals.colors[s];null!==r&&c.config.plotOptions.bar.distributed&&(b=c.globals.colors[r]);for(var v=function(t,o){var v=a.getFormatters(s);x=a.getSeriesName({fn:v.yLbTitleFormatter,index:s,seriesIndex:s,j:r}),"treemap"===c.config.chart.type&&(x=v.yLbTitleFormatter(String(c.config.series[s].data[r].x),{series:c.globals.series,seriesIndex:s,dataPointIndex:r,w:c}));var m=c.config.tooltip.inverseOrder?o:t;if(c.globals.axisCharts){var y=function(t){var e,i,a="";c.globals.isRangeData&&(a+=v.yLbFormatter(null===(e=c.globals.seriesRangeStart)||void 0===e||null===(i=e[t])||void 0===i?void 0:i[r],{series:c.globals.seriesRangeStart,seriesIndex:t,dataPointIndex:r,w:c})+" - ");return a+=v.yLbFormatter(c.globals.series[t][r],{series:c.globals.series,seriesIndex:t,dataPointIndex:r,w:c})};if(l)v=a.getFormatters(m),x=a.getSeriesName({fn:v.yLbTitleFormatter,index:m,seriesIndex:s,j:r}),b=c.globals.colors[m],i=y(m),g(m)&&(d=c.globals.seriesGoals[m][r].map((function(t){return{attrs:t,val:v.yLbFormatter(t.value,{seriesIndex:m,dataPointIndex:r,w:c})}})));else{var w,k=null==h||null===(w=h.target)||void 0===w?void 0:w.getAttribute("fill");k&&(b=-1!==k.indexOf("url")?document.querySelector(k.substr(4).slice(0,-1)).childNodes[0].getAttribute("stroke"):k),i=y(s),g(s)&&Array.isArray(c.globals.seriesGoals[s][r])&&(d=c.globals.seriesGoals[s][r].map((function(t){return{attrs:t,val:v.yLbFormatter(t.value,{seriesIndex:s,dataPointIndex:r,w:c})}})))}}null===r&&(i=v.yLbFormatter(c.globals.series[s],e(e({},c),{},{seriesIndex:s,dataPointIndex:s}))),a.DOMHandling({i:s,t:m,j:r,ttItems:n,values:{val:i,goalVals:d,xVal:u,xAxisTTVal:p,zVal:f},seriesName:x,shared:l,pColor:b})},m=0,y=c.globals.series.length-1;m<c.globals.series.length;m++,y--)v(m,y)}},{key:"getFormatters",value:function(t){var e,i=this.w,a=i.globals.yLabelFormatters[t];return void 0!==i.globals.ttVal?Array.isArray(i.globals.ttVal)?(a=i.globals.ttVal[t]&&i.globals.ttVal[t].formatter,e=i.globals.ttVal[t]&&i.globals.ttVal[t].title&&i.globals.ttVal[t].title.formatter):(a=i.globals.ttVal.formatter,"function"==typeof i.globals.ttVal.title.formatter&&(e=i.globals.ttVal.title.formatter)):e=i.config.tooltip.y.title.formatter,"function"!=typeof a&&(a=i.globals.yLabelFormatters[0]?i.globals.yLabelFormatters[0]:function(t){return t}),"function"!=typeof e&&(e=function(t){return t}),{yLbFormatter:a,yLbTitleFormatter:e}}},{key:"getSeriesName",value:function(t){var e=t.fn,i=t.index,a=t.seriesIndex,s=t.j,r=this.w;return e(String(r.globals.seriesNames[i]),{series:r.globals.series,seriesIndex:a,dataPointIndex:s,w:r})}},{key:"DOMHandling",value:function(t){t.i;var e=t.t,i=t.j,a=t.ttItems,s=t.values,r=t.seriesName,o=t.shared,n=t.pColor,l=this.w,h=this.ttCtx,c=s.val,d=s.goalVals,g=s.xVal,u=s.xAxisTTVal,f=s.zVal,p=null;p=a[e].children,l.config.tooltip.fillSeriesColor&&(a[e].style.backgroundColor=n,p[0].style.display="none"),h.showTooltipTitle&&(null===h.tooltipTitle&&(h.tooltipTitle=l.globals.dom.baseEl.querySelector(".apexcharts-tooltip-title")),h.tooltipTitle.innerHTML=g),h.isXAxisTooltipEnabled&&(h.xaxisTooltipText.innerHTML=""!==u?u:g);var x=a[e].querySelector(".apexcharts-tooltip-text-y-label");x&&(x.innerHTML=r||"");var b=a[e].querySelector(".apexcharts-tooltip-text-y-value");b&&(b.innerHTML=void 0!==c?c:""),p[0]&&p[0].classList.contains("apexcharts-tooltip-marker")&&(l.config.tooltip.marker.fillColors&&Array.isArray(l.config.tooltip.marker.fillColors)&&(n=l.config.tooltip.marker.fillColors[e]),p[0].style.backgroundColor=n),l.config.tooltip.marker.show||(p[0].style.display="none");var v=a[e].querySelector(".apexcharts-tooltip-text-goals-label"),m=a[e].querySelector(".apexcharts-tooltip-text-goals-value");if(d.length&&l.globals.seriesGoals[e]){var y=function(){var t="<div >",e="<div>";d.forEach((function(i,a){t+=' <div style="display: flex"><span class="apexcharts-tooltip-marker" style="background-color: '.concat(i.attrs.strokeColor,'; height: 3px; border-radius: 0; top: 5px;"></span> ').concat(i.attrs.name,"</div>"),e+="<div>".concat(i.val,"</div>")})),v.innerHTML=t+"</div>",m.innerHTML=e+"</div>"};o?l.globals.seriesGoals[e][i]&&Array.isArray(l.globals.seriesGoals[e][i])?y():(v.innerHTML="",m.innerHTML=""):y()}else v.innerHTML="",m.innerHTML="";null!==f&&(a[e].querySelector(".apexcharts-tooltip-text-z-label").innerHTML=l.config.tooltip.z.title,a[e].querySelector(".apexcharts-tooltip-text-z-value").innerHTML=void 0!==f?f:"");o&&p[0]&&(null==c||l.globals.ancillaryCollapsedSeriesIndices.indexOf(e)>-1||l.globals.collapsedSeriesIndices.indexOf(e)>-1?p[0].parentNode.style.display="none":p[0].parentNode.style.display=l.config.tooltip.items.display)}},{key:"toggleActiveInactiveSeries",value:function(t){var e=this.w;if(t)this.tooltipUtil.toggleAllTooltipSeriesGroups("enable");else{this.tooltipUtil.toggleAllTooltipSeriesGroups("disable");var i=e.globals.dom.baseEl.querySelector(".apexcharts-tooltip-series-group");i&&(i.classList.add("apexcharts-active"),i.style.display=e.config.tooltip.items.display)}}},{key:"getValuesToPrint",value:function(t){var e=t.i,i=t.j,a=this.w,s=this.ctx.series.filteredSeriesX(),r="",o="",n=null,l=null,h={series:a.globals.series,seriesIndex:e,dataPointIndex:i,w:a},c=a.globals.ttZFormatter;null===i?l=a.globals.series[e]:a.globals.isXNumeric&&"treemap"!==a.config.chart.type?(r=s[e][i],0===s[e].length&&(r=s[this.tooltipUtil.getFirstActiveXArray(s)][i])):r=void 0!==a.globals.labels[i]?a.globals.labels[i]:"";var d=r;a.globals.isXNumeric&&"datetime"===a.config.xaxis.type?r=new M(this.ctx).xLabelFormat(a.globals.ttKeyFormatter,d,d,{i:void 0,dateFormatter:new T(this.ctx).formatDate,w:this.w}):r=a.globals.isBarHorizontal?a.globals.yLabelFormatters[0](d,h):a.globals.xLabelFormatter(d,h);return void 0!==a.config.tooltip.x.formatter&&(r=a.globals.ttKeyFormatter(d,h)),a.globals.seriesZ.length>0&&a.globals.seriesZ[e].length>0&&(n=c(a.globals.seriesZ[e][i],a)),o="function"==typeof a.config.xaxis.tooltip.formatter?a.globals.xaxisTooltipFormatter(d,h):r,{val:Array.isArray(l)?l.join(" "):l,xVal:Array.isArray(r)?r.join(" "):r,xAxisTTVal:Array.isArray(o)?o.join(" "):o,zVal:n}}},{key:"handleCustomTooltip",value:function(t){var e=t.i,i=t.j,a=t.y1,s=t.y2,r=t.w,o=this.ttCtx.getElTooltip(),n=r.config.tooltip.custom;Array.isArray(n)&&n[e]&&(n=n[e]),o.innerHTML=n({ctx:this.ctx,series:r.globals.series,seriesIndex:e,dataPointIndex:i,y1:a,y2:s,w:r})}}]),t}(),ut=function(){function t(e){a(this,t),this.ttCtx=e,this.ctx=e.ctx,this.w=e.w}return r(t,[{key:"moveXCrosshairs",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,i=this.ttCtx,a=this.w,s=i.getElXCrosshairs(),r=t-i.xcrosshairsWidth/2,o=a.globals.labels.slice().length;if(null!==e&&(r=a.globals.gridWidth/o*e),null===s||a.globals.isBarHorizontal||(s.setAttribute("x",r),s.setAttribute("x1",r),s.setAttribute("x2",r),s.setAttribute("y2",a.globals.gridHeight),s.classList.add("apexcharts-active")),r<0&&(r=0),r>a.globals.gridWidth&&(r=a.globals.gridWidth),i.isXAxisTooltipEnabled){var n=r;"tickWidth"!==a.config.xaxis.crosshairs.width&&"barWidth"!==a.config.xaxis.crosshairs.width||(n=r+i.xcrosshairsWidth/2),this.moveXAxisTooltip(n)}}},{key:"moveYCrosshairs",value:function(t){var e=this.ttCtx;null!==e.ycrosshairs&&m.setAttrs(e.ycrosshairs,{y1:t,y2:t}),null!==e.ycrosshairsHidden&&m.setAttrs(e.ycrosshairsHidden,{y1:t,y2:t})}},{key:"moveXAxisTooltip",value:function(t){var e=this.w,i=this.ttCtx;if(null!==i.xaxisTooltip&&0!==i.xcrosshairsWidth){i.xaxisTooltip.classList.add("apexcharts-active");var a=i.xaxisOffY+e.config.xaxis.tooltip.offsetY+e.globals.translateY+1+e.config.xaxis.offsetY;if(t-=i.xaxisTooltip.getBoundingClientRect().width/2,!isNaN(t)){t+=e.globals.translateX;var s;s=new m(this.ctx).getTextRects(i.xaxisTooltipText.innerHTML),i.xaxisTooltipText.style.minWidth=s.width+"px",i.xaxisTooltip.style.left=t+"px",i.xaxisTooltip.style.top=a+"px"}}}},{key:"moveYAxisTooltip",value:function(t){var e=this.w,i=this.ttCtx;null===i.yaxisTTEls&&(i.yaxisTTEls=e.globals.dom.baseEl.querySelectorAll(".apexcharts-yaxistooltip"));var a=parseInt(i.ycrosshairsHidden.getAttribute("y1"),10),s=e.globals.translateY+a,r=i.yaxisTTEls[t].getBoundingClientRect().height,o=e.globals.translateYAxisX[t]-2;e.config.yaxis[t].opposite&&(o-=26),s-=r/2,-1===e.globals.ignoreYAxisIndexes.indexOf(t)?(i.yaxisTTEls[t].classList.add("apexcharts-active"),i.yaxisTTEls[t].style.top=s+"px",i.yaxisTTEls[t].style.left=o+e.config.yaxis[t].tooltip.offsetX+"px"):i.yaxisTTEls[t].classList.remove("apexcharts-active")}},{key:"moveTooltip",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=this.w,s=this.ttCtx,r=s.getElTooltip(),o=s.tooltipRect,n=null!==i?parseFloat(i):1,l=parseFloat(t)+n+5,h=parseFloat(e)+n/2;if(l>a.globals.gridWidth/2&&(l=l-o.ttWidth-n-10),l>a.globals.gridWidth-o.ttWidth-10&&(l=a.globals.gridWidth-o.ttWidth),l<-20&&(l=-20),a.config.tooltip.followCursor){var c=s.getElGrid(),d=c.getBoundingClientRect();h=s.e.clientY+a.globals.translateY-d.top-o.ttHeight/2}else a.globals.isBarHorizontal||o.ttHeight/2+h>a.globals.gridHeight&&(h=a.globals.gridHeight-o.ttHeight+a.globals.translateY);isNaN(l)||(l+=a.globals.translateX,r.style.left=l+"px",r.style.top=h+"px")}},{key:"moveMarkers",value:function(t,e){var i=this.w,a=this.ttCtx;if(i.globals.markers.size[t]>0)for(var s=i.globals.dom.baseEl.querySelectorAll(" .apexcharts-series[data\\:realIndex='".concat(t,"'] .apexcharts-marker")),r=0;r<s.length;r++)parseInt(s[r].getAttribute("rel"),10)===e&&(a.marker.resetPointsSize(),a.marker.enlargeCurrentPoint(e,s[r]));else a.marker.resetPointsSize(),this.moveDynamicPointOnHover(e,t)}},{key:"moveDynamicPointOnHover",value:function(t,e){var i,a,s=this.w,r=this.ttCtx,o=s.globals.pointsArray,n=r.tooltipUtil.getHoverMarkerSize(e),l=s.config.series[e].type;if(!l||"column"!==l&&"candlestick"!==l&&"boxPlot"!==l){i=o[e][t][0],a=o[e][t][1]?o[e][t][1]:0;var h=s.globals.dom.baseEl.querySelector(".apexcharts-series[data\\:realIndex='".concat(e,"'] .apexcharts-series-markers circle"));h&&a<s.globals.gridHeight&&a>0&&(h.setAttribute("r",n),h.setAttribute("cx",i),h.setAttribute("cy",a)),this.moveXCrosshairs(i),r.fixedTooltip||this.moveTooltip(i,a,n)}}},{key:"moveDynamicPointsOnHover",value:function(t){var e,i=this.ttCtx,a=i.w,s=0,r=0,o=a.globals.pointsArray;e=new N(this.ctx).getActiveConfigSeriesIndex("asc",["line","area","scatter","bubble"]);var n=i.tooltipUtil.getHoverMarkerSize(e);o[e]&&(s=o[e][t][0],r=o[e][t][1]);var l=i.tooltipUtil.getAllMarkers();if(null!==l)for(var h=0;h<a.globals.series.length;h++){var c=o[h];if(a.globals.comboCharts&&void 0===c&&l.splice(h,0,null),c&&c.length){var d=o[h][t][1],g=void 0;if(l[h].setAttribute("cx",s),"rangeArea"===a.config.chart.type&&!a.globals.comboCharts){var u=t+a.globals.series[h].length;g=o[h][u][1],d-=Math.abs(d-g)/2}null!==d&&!isNaN(d)&&d<a.globals.gridHeight+n&&d+n>0?(l[h]&&l[h].setAttribute("r",n),l[h]&&l[h].setAttribute("cy",d)):l[h]&&l[h].setAttribute("r",0)}}if(this.moveXCrosshairs(s),!i.fixedTooltip){var f=r||a.globals.gridHeight;this.moveTooltip(s,f,n)}}},{key:"moveStickyTooltipOverBars",value:function(t){var e=this.w,i=this.ttCtx,a=e.globals.columnSeries?e.globals.columnSeries.length:e.globals.series.length,s=a>=2&&a%2==0?Math.floor(a/2):Math.floor(a/2)+1;e.globals.isBarHorizontal&&(s=new N(this.ctx).getActiveConfigSeriesIndex("desc")+1);var r=e.globals.dom.baseEl.querySelector(".apexcharts-bar-series .apexcharts-series[rel='".concat(s,"'] path[j='").concat(t,"'], .apexcharts-candlestick-series .apexcharts-series[rel='").concat(s,"'] path[j='").concat(t,"'], .apexcharts-boxPlot-series .apexcharts-series[rel='").concat(s,"'] path[j='").concat(t,"'], .apexcharts-rangebar-series .apexcharts-series[rel='").concat(s,"'] path[j='").concat(t,"']")),o=r?parseFloat(r.getAttribute("cx")):0,n=r?parseFloat(r.getAttribute("cy")):0,l=r?parseFloat(r.getAttribute("barWidth")):0,h=i.getElGrid().getBoundingClientRect(),c=r.classList.contains("apexcharts-candlestick-area")||r.classList.contains("apexcharts-boxPlot-area");if(e.globals.isXNumeric?(r&&!c&&(o-=a%2!=0?l/2:0),r&&c&&e.globals.comboCharts&&(o-=l/2)):e.globals.isBarHorizontal||(o=i.xAxisTicksPositions[t-1]+i.dataPointsDividedWidth/2,isNaN(o)&&(o=i.xAxisTicksPositions[t]-i.dataPointsDividedWidth/2)),e.globals.isBarHorizontal?n-=i.tooltipRect.ttHeight:e.config.tooltip.followCursor?n=i.e.clientY-h.top-i.tooltipRect.ttHeight/2:n+i.tooltipRect.ttHeight+15>e.globals.gridHeight&&(n=e.globals.gridHeight),e.globals.isBarHorizontal||this.moveXCrosshairs(o),!i.fixedTooltip){var d=n||e.globals.gridHeight;this.moveTooltip(o,d)}}}]),t}(),ft=function(){function t(e){a(this,t),this.w=e.w,this.ttCtx=e,this.ctx=e.ctx,this.tooltipPosition=new ut(e)}return r(t,[{key:"drawDynamicPoints",value:function(){var t=this.w,e=new m(this.ctx),i=new D(this.ctx),a=t.globals.dom.baseEl.querySelectorAll(".apexcharts-series");a=u(a),t.config.chart.stacked&&a.sort((function(t,e){return parseFloat(t.getAttribute("data:realIndex"))-parseFloat(e.getAttribute("data:realIndex"))}));for(var s=0;s<a.length;s++){var r=a[s].querySelector(".apexcharts-series-markers-wrap");if(null!==r){var o=void 0,n="apexcharts-marker w".concat((Math.random()+1).toString(36).substring(4));"line"!==t.config.chart.type&&"area"!==t.config.chart.type||t.globals.comboCharts||t.config.tooltip.intersect||(n+=" no-pointer-events");var l=i.getMarkerConfig({cssClass:n,seriesIndex:Number(r.getAttribute("data:realIndex"))});(o=e.drawMarker(0,0,l)).node.setAttribute("default-marker-size",0);var h=document.createElementNS(t.globals.SVGNS,"g");h.classList.add("apexcharts-series-markers"),h.appendChild(o.node),r.appendChild(h)}}}},{key:"enlargeCurrentPoint",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null,s=this.w;"bubble"!==s.config.chart.type&&this.newPointSize(t,e);var r=e.getAttribute("cx"),o=e.getAttribute("cy");if(null!==i&&null!==a&&(r=i,o=a),this.tooltipPosition.moveXCrosshairs(r),!this.fixedTooltip){if("radar"===s.config.chart.type){var n=this.ttCtx.getElGrid(),l=n.getBoundingClientRect();r=this.ttCtx.e.clientX-l.left}this.tooltipPosition.moveTooltip(r,o,s.config.markers.hover.size)}}},{key:"enlargePoints",value:function(t){for(var e=this.w,i=this,a=this.ttCtx,s=t,r=e.globals.dom.baseEl.querySelectorAll(".apexcharts-series:not(.apexcharts-series-collapsed) .apexcharts-marker"),o=e.config.markers.hover.size,n=0;n<r.length;n++){var l=r[n].getAttribute("rel"),h=r[n].getAttribute("index");if(void 0===o&&(o=e.globals.markers.size[h]+e.config.markers.hover.sizeOffset),s===parseInt(l,10)){i.newPointSize(s,r[n]);var c=r[n].getAttribute("cx"),d=r[n].getAttribute("cy");i.tooltipPosition.moveXCrosshairs(c),a.fixedTooltip||i.tooltipPosition.moveTooltip(c,d,o)}else i.oldPointSize(r[n])}}},{key:"newPointSize",value:function(t,e){var i=this.w,a=i.config.markers.hover.size,s=0===t?e.parentNode.firstChild:e.parentNode.lastChild;if("0"!==s.getAttribute("default-marker-size")){var r=parseInt(s.getAttribute("index"),10);void 0===a&&(a=i.globals.markers.size[r]+i.config.markers.hover.sizeOffset),a<0&&(a=0),s.setAttribute("r",a)}}},{key:"oldPointSize",value:function(t){var e=parseFloat(t.getAttribute("default-marker-size"));t.setAttribute("r",e)}},{key:"resetPointsSize",value:function(){for(var t=this.w.globals.dom.baseEl.querySelectorAll(".apexcharts-series:not(.apexcharts-series-collapsed) .apexcharts-marker"),e=0;e<t.length;e++){var i=parseFloat(t[e].getAttribute("default-marker-size"));x.isNumber(i)&&i>=0?t[e].setAttribute("r",i):t[e].setAttribute("r",0)}}}]),t}(),pt=function(){function t(e){a(this,t),this.w=e.w,this.ttCtx=e}return r(t,[{key:"getAttr",value:function(t,e){return parseFloat(t.target.getAttribute(e))}},{key:"handleHeatTreeTooltip",value:function(t){var e=t.e,i=t.opt,a=t.x,s=t.y,r=t.type,o=this.ttCtx,n=this.w;if(e.target.classList.contains("apexcharts-".concat(r,"-rect"))){var l=this.getAttr(e,"i"),h=this.getAttr(e,"j"),c=this.getAttr(e,"cx"),d=this.getAttr(e,"cy"),g=this.getAttr(e,"width"),u=this.getAttr(e,"height");if(o.tooltipLabels.drawSeriesTexts({ttItems:i.ttItems,i:l,j:h,shared:!1,e:e}),n.globals.capturedSeriesIndex=l,n.globals.capturedDataPointIndex=h,a=c+o.tooltipRect.ttWidth/2+g,s=d+o.tooltipRect.ttHeight/2-u/2,o.tooltipPosition.moveXCrosshairs(c+g/2),a>n.globals.gridWidth/2&&(a=c-o.tooltipRect.ttWidth/2+g),o.w.config.tooltip.followCursor){var f=n.globals.dom.elWrap.getBoundingClientRect();a=n.globals.clientX-f.left-(a>n.globals.gridWidth/2?o.tooltipRect.ttWidth:0),s=n.globals.clientY-f.top-(s>n.globals.gridHeight/2?o.tooltipRect.ttHeight:0)}}return{x:a,y:s}}},{key:"handleMarkerTooltip",value:function(t){var e,i,a=t.e,s=t.opt,r=t.x,o=t.y,n=this.w,l=this.ttCtx;if(a.target.classList.contains("apexcharts-marker")){var h=parseInt(s.paths.getAttribute("cx"),10),c=parseInt(s.paths.getAttribute("cy"),10),d=parseFloat(s.paths.getAttribute("val"));if(i=parseInt(s.paths.getAttribute("rel"),10),e=parseInt(s.paths.parentNode.parentNode.parentNode.getAttribute("rel"),10)-1,l.intersect){var g=x.findAncestor(s.paths,"apexcharts-series");g&&(e=parseInt(g.getAttribute("data:realIndex"),10))}if(l.tooltipLabels.drawSeriesTexts({ttItems:s.ttItems,i:e,j:i,shared:!l.showOnIntersect&&n.config.tooltip.shared,e:a}),"mouseup"===a.type&&l.markerClick(a,e,i),n.globals.capturedSeriesIndex=e,n.globals.capturedDataPointIndex=i,r=h,o=c+n.globals.translateY-1.4*l.tooltipRect.ttHeight,l.w.config.tooltip.followCursor){var u=l.getElGrid().getBoundingClientRect();o=l.e.clientY+n.globals.translateY-u.top}d<0&&(o=c),l.marker.enlargeCurrentPoint(i,s.paths,r,o)}return{x:r,y:o}}},{key:"handleBarTooltip",value:function(t){var e,i,a=t.e,s=t.opt,r=this.w,o=this.ttCtx,n=o.getElTooltip(),l=0,h=0,c=0,d=this.getBarTooltipXY({e:a,opt:s});e=d.i;var g=d.barHeight,u=d.j;r.globals.capturedSeriesIndex=e,r.globals.capturedDataPointIndex=u,r.globals.isBarHorizontal&&o.tooltipUtil.hasBars()||!r.config.tooltip.shared?(h=d.x,c=d.y,i=Array.isArray(r.config.stroke.width)?r.config.stroke.width[e]:r.config.stroke.width,l=h):r.globals.comboCharts||r.config.tooltip.shared||(l/=2),isNaN(c)&&(c=r.globals.svgHeight-o.tooltipRect.ttHeight);var f=parseInt(s.paths.parentNode.getAttribute("data:realIndex"),10),p=r.globals.isMultipleYAxis?r.config.yaxis[f]&&r.config.yaxis[f].reversed:r.config.yaxis[0].reversed;if(h+o.tooltipRect.ttWidth>r.globals.gridWidth&&!p?h-=o.tooltipRect.ttWidth:h<0&&(h=0),o.w.config.tooltip.followCursor){var x=o.getElGrid().getBoundingClientRect();c=o.e.clientY-x.top}null===o.tooltip&&(o.tooltip=r.globals.dom.baseEl.querySelector(".apexcharts-tooltip")),r.config.tooltip.shared||(r.globals.comboBarCount>0?o.tooltipPosition.moveXCrosshairs(l+i/2):o.tooltipPosition.moveXCrosshairs(l)),!o.fixedTooltip&&(!r.config.tooltip.shared||r.globals.isBarHorizontal&&o.tooltipUtil.hasBars())&&(p&&(h-=o.tooltipRect.ttWidth)<0&&(h=0),!p||r.globals.isBarHorizontal&&o.tooltipUtil.hasBars()||(c=c+g-2*(r.globals.series[e][u]<0?g:0)),c=c+r.globals.translateY-o.tooltipRect.ttHeight/2,n.style.left=h+r.globals.translateX+"px",n.style.top=c+"px")}},{key:"getBarTooltipXY",value:function(t){var e=t.e,i=t.opt,a=this.w,s=null,r=this.ttCtx,o=0,n=0,l=0,h=0,c=0,d=e.target.classList;if(d.contains("apexcharts-bar-area")||d.contains("apexcharts-candlestick-area")||d.contains("apexcharts-boxPlot-area")||d.contains("apexcharts-rangebar-area")){var g=e.target,u=g.getBoundingClientRect(),f=i.elGrid.getBoundingClientRect(),p=u.height;c=u.height;var x=u.width,b=parseInt(g.getAttribute("cx"),10),v=parseInt(g.getAttribute("cy"),10);h=parseFloat(g.getAttribute("barWidth"));var m="touchmove"===e.type?e.touches[0].clientX:e.clientX;s=parseInt(g.getAttribute("j"),10),o=parseInt(g.parentNode.getAttribute("rel"),10)-1;var y=g.getAttribute("data-range-y1"),w=g.getAttribute("data-range-y2");a.globals.comboCharts&&(o=parseInt(g.parentNode.getAttribute("data:realIndex"),10)),r.tooltipLabels.drawSeriesTexts({ttItems:i.ttItems,i:o,j:s,y1:y?parseInt(y,10):null,y2:w?parseInt(w,10):null,shared:!r.showOnIntersect&&a.config.tooltip.shared,e:e}),a.config.tooltip.followCursor?a.globals.isBarHorizontal?(n=m-f.left+15,l=v-r.dataPointsDividedHeight+p/2-r.tooltipRect.ttHeight/2):(n=a.globals.isXNumeric?b-x/2:b-r.dataPointsDividedWidth+x/2,l=e.clientY-f.top-r.tooltipRect.ttHeight/2-15):a.globals.isBarHorizontal?((n=b)<r.xyRatios.baseLineInvertedY&&(n=b-r.tooltipRect.ttWidth),l=v-r.dataPointsDividedHeight+p/2-r.tooltipRect.ttHeight/2):(n=a.globals.isXNumeric?b-x/2:b-r.dataPointsDividedWidth+x/2,l=v)}return{x:n,y:l,barHeight:c,barWidth:h,i:o,j:s}}}]),t}(),xt=function(){function t(e){a(this,t),this.w=e.w,this.ttCtx=e}return r(t,[{key:"drawXaxisTooltip",value:function(){var t=this.w,e=this.ttCtx,i="bottom"===t.config.xaxis.position;e.xaxisOffY=i?t.globals.gridHeight+1:-t.globals.xAxisHeight-t.config.xaxis.axisTicks.height+3;var a=i?"apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom":"apexcharts-xaxistooltip apexcharts-xaxistooltip-top",s=t.globals.dom.elWrap;e.isXAxisTooltipEnabled&&(null===t.globals.dom.baseEl.querySelector(".apexcharts-xaxistooltip")&&(e.xaxisTooltip=document.createElement("div"),e.xaxisTooltip.setAttribute("class",a+" apexcharts-theme-"+t.config.tooltip.theme),s.appendChild(e.xaxisTooltip),e.xaxisTooltipText=document.createElement("div"),e.xaxisTooltipText.classList.add("apexcharts-xaxistooltip-text"),e.xaxisTooltipText.style.fontFamily=t.config.xaxis.tooltip.style.fontFamily||t.config.chart.fontFamily,e.xaxisTooltipText.style.fontSize=t.config.xaxis.tooltip.style.fontSize,e.xaxisTooltip.appendChild(e.xaxisTooltipText)))}},{key:"drawYaxisTooltip",value:function(){for(var t=this.w,e=this.ttCtx,i=function(i){var a=t.config.yaxis[i].opposite||t.config.yaxis[i].crosshairs.opposite;e.yaxisOffX=a?t.globals.gridWidth+1:1;var s="apexcharts-yaxistooltip apexcharts-yaxistooltip-".concat(i,a?" apexcharts-yaxistooltip-right":" apexcharts-yaxistooltip-left");t.globals.yAxisSameScaleIndices.map((function(e,a){e.map((function(e,a){a===i&&(s+=t.config.yaxis[a].show?" ":" apexcharts-yaxistooltip-hidden")}))}));var r=t.globals.dom.elWrap;null===t.globals.dom.baseEl.querySelector(".apexcharts-yaxistooltip apexcharts-yaxistooltip-".concat(i))&&(e.yaxisTooltip=document.createElement("div"),e.yaxisTooltip.setAttribute("class",s+" apexcharts-theme-"+t.config.tooltip.theme),r.appendChild(e.yaxisTooltip),0===i&&(e.yaxisTooltipText=[]),e.yaxisTooltipText[i]=document.createElement("div"),e.yaxisTooltipText[i].classList.add("apexcharts-yaxistooltip-text"),e.yaxisTooltip.appendChild(e.yaxisTooltipText[i]))},a=0;a<t.config.yaxis.length;a++)i(a)}},{key:"setXCrosshairWidth",value:function(){var t=this.w,e=this.ttCtx,i=e.getElXCrosshairs();if(e.xcrosshairsWidth=parseInt(t.config.xaxis.crosshairs.width,10),t.globals.comboCharts){var a=t.globals.dom.baseEl.querySelector(".apexcharts-bar-area");if(null!==a&&"barWidth"===t.config.xaxis.crosshairs.width){var s=parseFloat(a.getAttribute("barWidth"));e.xcrosshairsWidth=s}else if("tickWidth"===t.config.xaxis.crosshairs.width){var r=t.globals.labels.length;e.xcrosshairsWidth=t.globals.gridWidth/r}}else if("tickWidth"===t.config.xaxis.crosshairs.width){var o=t.globals.labels.length;e.xcrosshairsWidth=t.globals.gridWidth/o}else if("barWidth"===t.config.xaxis.crosshairs.width){var n=t.globals.dom.baseEl.querySelector(".apexcharts-bar-area");if(null!==n){var l=parseFloat(n.getAttribute("barWidth"));e.xcrosshairsWidth=l}else e.xcrosshairsWidth=1}t.globals.isBarHorizontal&&(e.xcrosshairsWidth=0),null!==i&&e.xcrosshairsWidth>0&&i.setAttribute("width",e.xcrosshairsWidth)}},{key:"handleYCrosshair",value:function(){var t=this.w,e=this.ttCtx;e.ycrosshairs=t.globals.dom.baseEl.querySelector(".apexcharts-ycrosshairs"),e.ycrosshairsHidden=t.globals.dom.baseEl.querySelector(".apexcharts-ycrosshairs-hidden")}},{key:"drawYaxisTooltipText",value:function(t,e,i){var a=this.ttCtx,s=this.w,r=s.globals.yLabelFormatters[t];if(a.yaxisTooltips[t]){var o=a.getElGrid().getBoundingClientRect(),n=(e-o.top)*i.yRatio[t],l=s.globals.maxYArr[t]-s.globals.minYArr[t],h=s.globals.minYArr[t]+(l-n);a.tooltipPosition.moveYCrosshairs(e-o.top),a.yaxisTooltipText[t].innerHTML=r(h),a.tooltipPosition.moveYAxisTooltip(t)}}}]),t}(),bt=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w;var i=this.w;this.tConfig=i.config.tooltip,this.tooltipUtil=new dt(this),this.tooltipLabels=new gt(this),this.tooltipPosition=new ut(this),this.marker=new ft(this),this.intersect=new pt(this),this.axesTooltip=new xt(this),this.showOnIntersect=this.tConfig.intersect,this.showTooltipTitle=this.tConfig.x.show,this.fixedTooltip=this.tConfig.fixed.enabled,this.xaxisTooltip=null,this.yaxisTTEls=null,this.isBarShared=!i.globals.isBarHorizontal&&this.tConfig.shared,this.lastHoverTime=Date.now()}return r(t,[{key:"getElTooltip",value:function(t){return t||(t=this),t.w.globals.dom.baseEl?t.w.globals.dom.baseEl.querySelector(".apexcharts-tooltip"):null}},{key:"getElXCrosshairs",value:function(){return this.w.globals.dom.baseEl.querySelector(".apexcharts-xcrosshairs")}},{key:"getElGrid",value:function(){return this.w.globals.dom.baseEl.querySelector(".apexcharts-grid")}},{key:"drawTooltip",value:function(t){var e=this.w;this.xyRatios=t,this.isXAxisTooltipEnabled=e.config.xaxis.tooltip.enabled&&e.globals.axisCharts,this.yaxisTooltips=e.config.yaxis.map((function(t,i){return!!(t.show&&t.tooltip.enabled&&e.globals.axisCharts)})),this.allTooltipSeriesGroups=[],e.globals.axisCharts||(this.showTooltipTitle=!1);var i=document.createElement("div");if(i.classList.add("apexcharts-tooltip"),e.config.tooltip.cssClass&&i.classList.add(e.config.tooltip.cssClass),i.classList.add("apexcharts-theme-".concat(this.tConfig.theme)),e.globals.dom.elWrap.appendChild(i),e.globals.axisCharts){this.axesTooltip.drawXaxisTooltip(),this.axesTooltip.drawYaxisTooltip(),this.axesTooltip.setXCrosshairWidth(),this.axesTooltip.handleYCrosshair();var a=new V(this.ctx);this.xAxisTicksPositions=a.getXAxisTicksPositions()}if(!e.globals.comboCharts&&!this.tConfig.intersect&&"rangeBar"!==e.config.chart.type||this.tConfig.shared||(this.showOnIntersect=!0),0!==e.config.markers.size&&0!==e.globals.markers.largestSize||this.marker.drawDynamicPoints(this),e.globals.collapsedSeries.length!==e.globals.series.length){this.dataPointsDividedHeight=e.globals.gridHeight/e.globals.dataPoints,this.dataPointsDividedWidth=e.globals.gridWidth/e.globals.dataPoints,this.showTooltipTitle&&(this.tooltipTitle=document.createElement("div"),this.tooltipTitle.classList.add("apexcharts-tooltip-title"),this.tooltipTitle.style.fontFamily=this.tConfig.style.fontFamily||e.config.chart.fontFamily,this.tooltipTitle.style.fontSize=this.tConfig.style.fontSize,i.appendChild(this.tooltipTitle));var s=e.globals.series.length;(e.globals.xyCharts||e.globals.comboCharts)&&this.tConfig.shared&&(s=this.showOnIntersect?1:e.globals.series.length),this.legendLabels=e.globals.dom.baseEl.querySelectorAll(".apexcharts-legend-text"),this.ttItems=this.createTTElements(s),this.addSVGEvents()}}},{key:"createTTElements",value:function(t){for(var e=this,i=this.w,a=[],s=this.getElTooltip(),r=function(r){var o=document.createElement("div");o.classList.add("apexcharts-tooltip-series-group"),o.style.order=i.config.tooltip.inverseOrder?t-r:r+1,e.tConfig.shared&&e.tConfig.enabledOnSeries&&Array.isArray(e.tConfig.enabledOnSeries)&&e.tConfig.enabledOnSeries.indexOf(r)<0&&o.classList.add("apexcharts-tooltip-series-group-hidden");var n=document.createElement("span");n.classList.add("apexcharts-tooltip-marker"),n.style.backgroundColor=i.globals.colors[r],o.appendChild(n);var l=document.createElement("div");l.classList.add("apexcharts-tooltip-text"),l.style.fontFamily=e.tConfig.style.fontFamily||i.config.chart.fontFamily,l.style.fontSize=e.tConfig.style.fontSize,["y","goals","z"].forEach((function(t){var e=document.createElement("div");e.classList.add("apexcharts-tooltip-".concat(t,"-group"));var i=document.createElement("span");i.classList.add("apexcharts-tooltip-text-".concat(t,"-label")),e.appendChild(i);var a=document.createElement("span");a.classList.add("apexcharts-tooltip-text-".concat(t,"-value")),e.appendChild(a),l.appendChild(e)})),o.appendChild(l),s.appendChild(o),a.push(o)},o=0;o<t;o++)r(o);return a}},{key:"addSVGEvents",value:function(){var t=this.w,e=t.config.chart.type,i=this.getElTooltip(),a=!("bar"!==e&&"candlestick"!==e&&"boxPlot"!==e&&"rangeBar"!==e),s="area"===e||"line"===e||"scatter"===e||"bubble"===e||"radar"===e,r=t.globals.dom.Paper.node,o=this.getElGrid();o&&(this.seriesBound=o.getBoundingClientRect());var n,l=[],h=[],c={hoverArea:r,elGrid:o,tooltipEl:i,tooltipY:l,tooltipX:h,ttItems:this.ttItems};if(t.globals.axisCharts&&(s?n=t.globals.dom.baseEl.querySelectorAll(".apexcharts-series[data\\:longestSeries='true'] .apexcharts-marker"):a?n=t.globals.dom.baseEl.querySelectorAll(".apexcharts-series .apexcharts-bar-area, .apexcharts-series .apexcharts-candlestick-area, .apexcharts-series .apexcharts-boxPlot-area, .apexcharts-series .apexcharts-rangebar-area"):"heatmap"!==e&&"treemap"!==e||(n=t.globals.dom.baseEl.querySelectorAll(".apexcharts-series .apexcharts-heatmap, .apexcharts-series .apexcharts-treemap")),n&&n.length))for(var d=0;d<n.length;d++)l.push(n[d].getAttribute("cy")),h.push(n[d].getAttribute("cx"));if(t.globals.xyCharts&&!this.showOnIntersect||t.globals.comboCharts&&!this.showOnIntersect||a&&this.tooltipUtil.hasBars()&&this.tConfig.shared)this.addPathsEventListeners([r],c);else if(a&&!t.globals.comboCharts||s&&this.showOnIntersect)this.addDatapointEventsListeners(c);else if(!t.globals.axisCharts||"heatmap"===e||"treemap"===e){var g=t.globals.dom.baseEl.querySelectorAll(".apexcharts-series");this.addPathsEventListeners(g,c)}if(this.showOnIntersect){var u=t.globals.dom.baseEl.querySelectorAll(".apexcharts-line-series .apexcharts-marker, .apexcharts-area-series .apexcharts-marker");u.length>0&&this.addPathsEventListeners(u,c),this.tooltipUtil.hasBars()&&!this.tConfig.shared&&this.addDatapointEventsListeners(c)}}},{key:"drawFixedTooltipRect",value:function(){var t=this.w,e=this.getElTooltip(),i=e.getBoundingClientRect(),a=i.width+10,s=i.height+10,r=this.tConfig.fixed.offsetX,o=this.tConfig.fixed.offsetY,n=this.tConfig.fixed.position.toLowerCase();return n.indexOf("right")>-1&&(r=r+t.globals.svgWidth-a+10),n.indexOf("bottom")>-1&&(o=o+t.globals.svgHeight-s-10),e.style.left=r+"px",e.style.top=o+"px",{x:r,y:o,ttWidth:a,ttHeight:s}}},{key:"addDatapointEventsListeners",value:function(t){var e=this.w.globals.dom.baseEl.querySelectorAll(".apexcharts-series-markers .apexcharts-marker, .apexcharts-bar-area, .apexcharts-candlestick-area, .apexcharts-boxPlot-area, .apexcharts-rangebar-area");this.addPathsEventListeners(e,t)}},{key:"addPathsEventListeners",value:function(t,e){for(var i=this,a=function(a){var s={paths:t[a],tooltipEl:e.tooltipEl,tooltipY:e.tooltipY,tooltipX:e.tooltipX,elGrid:e.elGrid,hoverArea:e.hoverArea,ttItems:e.ttItems};["mousemove","mouseup","touchmove","mouseout","touchend"].map((function(e){return t[a].addEventListener(e,i.onSeriesHover.bind(i,s),{capture:!1,passive:!0})}))},s=0;s<t.length;s++)a(s)}},{key:"onSeriesHover",value:function(t,e){var i=this,a=Date.now()-this.lastHoverTime;a>=100?this.seriesHover(t,e):(clearTimeout(this.seriesHoverTimeout),this.seriesHoverTimeout=setTimeout((function(){i.seriesHover(t,e)}),100-a))}},{key:"seriesHover",value:function(t,e){var i=this;this.lastHoverTime=Date.now();var a=[],s=this.w;s.config.chart.group&&(a=this.ctx.getGroupedCharts()),s.globals.axisCharts&&(s.globals.minX===-1/0&&s.globals.maxX===1/0||0===s.globals.dataPoints)||(a.length?a.forEach((function(a){var s=i.getElTooltip(a),r={paths:t.paths,tooltipEl:s,tooltipY:t.tooltipY,tooltipX:t.tooltipX,elGrid:t.elGrid,hoverArea:t.hoverArea,ttItems:a.w.globals.tooltip.ttItems};a.w.globals.minX===i.w.globals.minX&&a.w.globals.maxX===i.w.globals.maxX&&a.w.globals.tooltip.seriesHoverByContext({chartCtx:a,ttCtx:a.w.globals.tooltip,opt:r,e:e})})):this.seriesHoverByContext({chartCtx:this.ctx,ttCtx:this.w.globals.tooltip,opt:t,e:e}))}},{key:"seriesHoverByContext",value:function(t){var e=t.chartCtx,i=t.ttCtx,a=t.opt,s=t.e,r=e.w,o=this.getElTooltip();if(o){if(i.tooltipRect={x:0,y:0,ttWidth:o.getBoundingClientRect().width,ttHeight:o.getBoundingClientRect().height},i.e=s,i.tooltipUtil.hasBars()&&!r.globals.comboCharts&&!i.isBarShared)if(this.tConfig.onDatasetHover.highlightDataSeries)new N(e).toggleSeriesOnHover(s,s.target.parentNode);i.fixedTooltip&&i.drawFixedTooltipRect(),r.globals.axisCharts?i.axisChartsTooltips({e:s,opt:a,tooltipRect:i.tooltipRect}):i.nonAxisChartsTooltips({e:s,opt:a,tooltipRect:i.tooltipRect})}}},{key:"axisChartsTooltips",value:function(t){var e,i,a=t.e,s=t.opt,r=this.w,o=s.elGrid.getBoundingClientRect(),n="touchmove"===a.type?a.touches[0].clientX:a.clientX,l="touchmove"===a.type?a.touches[0].clientY:a.clientY;if(this.clientY=l,this.clientX=n,r.globals.capturedSeriesIndex=-1,r.globals.capturedDataPointIndex=-1,l<o.top||l>o.top+o.height)this.handleMouseOut(s);else{if(Array.isArray(this.tConfig.enabledOnSeries)&&!r.config.tooltip.shared){var h=parseInt(s.paths.getAttribute("index"),10);if(this.tConfig.enabledOnSeries.indexOf(h)<0)return void this.handleMouseOut(s)}var c=this.getElTooltip(),d=this.getElXCrosshairs(),g=r.globals.xyCharts||"bar"===r.config.chart.type&&!r.globals.isBarHorizontal&&this.tooltipUtil.hasBars()&&this.tConfig.shared||r.globals.comboCharts&&this.tooltipUtil.hasBars();if("mousemove"===a.type||"touchmove"===a.type||"mouseup"===a.type){if(r.globals.collapsedSeries.length+r.globals.ancillaryCollapsedSeries.length===r.globals.series.length)return;null!==d&&d.classList.add("apexcharts-active");var u=this.yaxisTooltips.filter((function(t){return!0===t}));if(null!==this.ycrosshairs&&u.length&&this.ycrosshairs.classList.add("apexcharts-active"),g&&!this.showOnIntersect)this.handleStickyTooltip(a,n,l,s);else if("heatmap"===r.config.chart.type||"treemap"===r.config.chart.type){var f=this.intersect.handleHeatTreeTooltip({e:a,opt:s,x:e,y:i,type:r.config.chart.type});e=f.x,i=f.y,c.style.left=e+"px",c.style.top=i+"px"}else this.tooltipUtil.hasBars()&&this.intersect.handleBarTooltip({e:a,opt:s}),this.tooltipUtil.hasMarkers()&&this.intersect.handleMarkerTooltip({e:a,opt:s,x:e,y:i});if(this.yaxisTooltips.length)for(var p=0;p<r.config.yaxis.length;p++)this.axesTooltip.drawYaxisTooltipText(p,l,this.xyRatios);s.tooltipEl.classList.add("apexcharts-active")}else"mouseout"!==a.type&&"touchend"!==a.type||this.handleMouseOut(s)}}},{key:"nonAxisChartsTooltips",value:function(t){var e=t.e,i=t.opt,a=t.tooltipRect,s=this.w,r=i.paths.getAttribute("rel"),o=this.getElTooltip(),n=s.globals.dom.elWrap.getBoundingClientRect();if("mousemove"===e.type||"touchmove"===e.type){o.classList.add("apexcharts-active"),this.tooltipLabels.drawSeriesTexts({ttItems:i.ttItems,i:parseInt(r,10)-1,shared:!1});var l=s.globals.clientX-n.left-a.ttWidth/2,h=s.globals.clientY-n.top-a.ttHeight-10;if(o.style.left=l+"px",o.style.top=h+"px",s.config.legend.tooltipHoverFormatter){var c=r-1,d=(0,s.config.legend.tooltipHoverFormatter)(this.legendLabels[c].getAttribute("data:default-text"),{seriesIndex:c,dataPointIndex:c,w:s});this.legendLabels[c].innerHTML=d}}else"mouseout"!==e.type&&"touchend"!==e.type||(o.classList.remove("apexcharts-active"),s.config.legend.tooltipHoverFormatter&&this.legendLabels.forEach((function(t){var e=t.getAttribute("data:default-text");t.innerHTML=decodeURIComponent(e)})))}},{key:"handleStickyTooltip",value:function(t,e,i,a){var s=this.w,r=this.tooltipUtil.getNearestValues({context:this,hoverArea:a.hoverArea,elGrid:a.elGrid,clientX:e,clientY:i}),o=r.j,n=r.capturedSeries,l=a.elGrid.getBoundingClientRect();r.hoverX<0||r.hoverX>l.width?this.handleMouseOut(a):null!==n?this.handleStickyCapturedSeries(t,n,a,o):(this.tooltipUtil.isXoverlap(o)||s.globals.isBarHorizontal)&&this.create(t,this,0,o,a.ttItems)}},{key:"handleStickyCapturedSeries",value:function(t,e,i,a){var s=this.w;if(!this.tConfig.shared&&null===s.globals.series[e][a])return void this.handleMouseOut(i);void 0!==s.globals.series[e][a]?this.tConfig.shared&&this.tooltipUtil.isXoverlap(a)&&this.tooltipUtil.isInitialSeriesSameLen()?this.create(t,this,e,a,i.ttItems):this.create(t,this,e,a,i.ttItems,!1):this.tooltipUtil.isXoverlap(a)&&this.create(t,this,0,a,i.ttItems)}},{key:"deactivateHoverFilter",value:function(){for(var t=this.w,e=new m(this.ctx),i=t.globals.dom.Paper.select(".apexcharts-bar-area"),a=0;a<i.length;a++)e.pathMouseLeave(i[a])}},{key:"handleMouseOut",value:function(t){var e=this.w,i=this.getElXCrosshairs();if(t.tooltipEl.classList.remove("apexcharts-active"),this.deactivateHoverFilter(),"bubble"!==e.config.chart.type&&this.marker.resetPointsSize(),null!==i&&i.classList.remove("apexcharts-active"),null!==this.ycrosshairs&&this.ycrosshairs.classList.remove("apexcharts-active"),this.isXAxisTooltipEnabled&&this.xaxisTooltip.classList.remove("apexcharts-active"),this.yaxisTooltips.length){null===this.yaxisTTEls&&(this.yaxisTTEls=e.globals.dom.baseEl.querySelectorAll(".apexcharts-yaxistooltip"));for(var a=0;a<this.yaxisTTEls.length;a++)this.yaxisTTEls[a].classList.remove("apexcharts-active")}e.config.legend.tooltipHoverFormatter&&this.legendLabels.forEach((function(t){var e=t.getAttribute("data:default-text");t.innerHTML=decodeURIComponent(e)}))}},{key:"markerClick",value:function(t,e,i){var a=this.w;"function"==typeof a.config.chart.events.markerClick&&a.config.chart.events.markerClick(t,this.ctx,{seriesIndex:e,dataPointIndex:i,w:a}),this.ctx.events.fireEvent("markerClick",[t,this.ctx,{seriesIndex:e,dataPointIndex:i,w:a}])}},{key:"create",value:function(t,i,a,s,r){var o,n,l,h,c,d,g,u,f,p,x,b,v,y,w,k,A=arguments.length>5&&void 0!==arguments[5]?arguments[5]:null,S=this.w,C=i;"mouseup"===t.type&&this.markerClick(t,a,s),null===A&&(A=this.tConfig.shared);var L=this.tooltipUtil.hasMarkers(),P=this.tooltipUtil.getElBars();if(S.config.legend.tooltipHoverFormatter){var T=S.config.legend.tooltipHoverFormatter,M=Array.from(this.legendLabels);M.forEach((function(t){var e=t.getAttribute("data:default-text");t.innerHTML=decodeURIComponent(e)}));for(var I=0;I<M.length;I++){var z=M[I],X=parseInt(z.getAttribute("i"),10),E=decodeURIComponent(z.getAttribute("data:default-text")),Y=T(E,{seriesIndex:A?X:a,dataPointIndex:s,w:S});if(A)z.innerHTML=S.globals.collapsedSeriesIndices.indexOf(X)<0?Y:E;else if(z.innerHTML=X===a?Y:E,a===X)break}}var F=e(e({ttItems:r,i:a,j:s},void 0!==(null===(o=S.globals.seriesRange)||void 0===o||null===(n=o[a])||void 0===n||null===(l=n[s])||void 0===l||null===(h=l.y[0])||void 0===h?void 0:h.y1)&&{y1:null===(c=S.globals.seriesRange)||void 0===c||null===(d=c[a])||void 0===d||null===(g=d[s])||void 0===g||null===(u=g.y[0])||void 0===u?void 0:u.y1}),void 0!==(null===(f=S.globals.seriesRange)||void 0===f||null===(p=f[a])||void 0===p||null===(x=p[s])||void 0===x||null===(b=x.y[0])||void 0===b?void 0:b.y2)&&{y2:null===(v=S.globals.seriesRange)||void 0===v||null===(y=v[a])||void 0===y||null===(w=y[s])||void 0===w||null===(k=w.y[0])||void 0===k?void 0:k.y2});if(A){if(C.tooltipLabels.drawSeriesTexts(e(e({},F),{},{shared:!this.showOnIntersect&&this.tConfig.shared})),L&&(S.globals.markers.largestSize>0?C.marker.enlargePoints(s):C.tooltipPosition.moveDynamicPointsOnHover(s)),this.tooltipUtil.hasBars()&&(this.barSeriesHeight=this.tooltipUtil.getBarsHeight(P),this.barSeriesHeight>0)){var R=new m(this.ctx),D=S.globals.dom.Paper.select(".apexcharts-bar-area[j='".concat(s,"']"));this.deactivateHoverFilter(),this.tooltipPosition.moveStickyTooltipOverBars(s);for(var H=0;H<D.length;H++)R.pathMouseEnter(D[H])}}else C.tooltipLabels.drawSeriesTexts(e({shared:!1},F)),this.tooltipUtil.hasBars()&&C.tooltipPosition.moveStickyTooltipOverBars(s),L&&C.tooltipPosition.moveMarkers(a,s)}}]),t}(),vt=function(){function t(e){a(this,t),this.w=e.w,this.barCtx=e,this.totalFormatter=this.w.config.plotOptions.bar.dataLabels.total.formatter,this.totalFormatter||(this.totalFormatter=this.w.config.dataLabels.formatter)}return r(t,[{key:"handleBarDataLabels",value:function(t){var e=t.x,i=t.y,a=t.y1,s=t.y2,r=t.i,o=t.j,n=t.realIndex,l=t.series,h=t.barHeight,c=t.barWidth,d=t.barYPosition,g=t.visibleSeries,u=t.renderedPath,f=this.w,p=new m(this.barCtx.ctx),x=Array.isArray(this.barCtx.strokeWidth)?this.barCtx.strokeWidth[n]:this.barCtx.strokeWidth,b=e+parseFloat(c*g),v=i+parseFloat(h*g);f.globals.isXNumeric&&!f.globals.isBarHorizontal&&(b=e+parseFloat(c*(g+1)),v=i+parseFloat(h*(g+1))-x);var y,w=null,k=e,A=i,S={},C=f.config.dataLabels,L=this.barCtx.barOptions.dataLabels,P=this.barCtx.barOptions.dataLabels.total;void 0!==d&&this.barCtx.isRangeBar&&(v=d,A=d);var T=C.offsetX,M=C.offsetY,I={width:0,height:0};if(f.config.dataLabels.enabled){var z=this.barCtx.series[r][o];I=p.getTextRects(f.globals.yLabelFormatters[0](z),parseFloat(C.style.fontSize))}var X={x:e,y:i,i:r,j:o,realIndex:n,renderedPath:u,bcx:b,bcy:v,barHeight:h,barWidth:c,textRects:I,strokeWidth:x,dataLabelsX:k,dataLabelsY:A,dataLabelsConfig:C,barDataLabelsConfig:L,barTotalDataLabelsConfig:P,offX:T,offY:M};return S=this.barCtx.isHorizontal?this.calculateBarsDataLabelsPosition(X):this.calculateColumnsDataLabelsPosition(X),u.attr({cy:S.bcy,cx:S.bcx,j:o,val:l[r][o],barHeight:h,barWidth:c}),y=this.drawCalculatedDataLabels({x:S.dataLabelsX,y:S.dataLabelsY,val:this.barCtx.isRangeBar?[a,s]:l[r][o],i:n,j:o,barWidth:c,barHeight:h,textRects:I,dataLabelsConfig:C}),f.config.chart.stacked&&P.enabled&&(w=this.drawTotalDataLabels({x:S.totalDataLabelsX,y:S.totalDataLabelsY,realIndex:n,textAnchor:S.totalDataLabelsAnchor,val:this.getStackedTotalDataLabel({realIndex:n,j:o}),dataLabelsConfig:C,barTotalDataLabelsConfig:P})),{dataLabels:y,totalDataLabels:w}}},{key:"getStackedTotalDataLabel",value:function(t){var i=t.realIndex,a=t.j,s=this.w,r=this.barCtx.stackedSeriesTotals[a];return this.totalFormatter&&(r=this.totalFormatter(r,e(e({},s),{},{seriesIndex:i,dataPointIndex:a,w:s}))),r}},{key:"calculateColumnsDataLabelsPosition",value:function(t){var e,i,a,s=this.w,r=t.i,o=t.j,n=t.realIndex,l=t.y,h=t.bcx,c=t.barWidth,d=t.barHeight,g=t.textRects,u=t.dataLabelsY,f=t.dataLabelsConfig,p=t.barDataLabelsConfig,x=t.barTotalDataLabelsConfig,b=t.strokeWidth,v=t.offX,y=t.offY;d=Math.abs(d);var w="vertical"===s.config.plotOptions.bar.dataLabels.orientation;h-=b/2;var k=s.globals.gridWidth/s.globals.dataPoints;if(e=s.globals.isXNumeric?h-c/2+v:h-k+c/2+v,w){e=e+g.height/2-b/2-2}var A=this.barCtx.series[r][o]<0,S=l;switch(this.barCtx.isReversed&&(S=l-d+(A?2*d:0),l-=d),p.position){case"center":u=w?A?S+d/2+y:S+d/2-y:A?S-d/2+g.height/2+y:S+d/2+g.height/2-y;break;case"bottom":u=w?A?S+d+y:S+d-y:A?S-d+g.height+b+y:S+d-g.height/2+b-y;break;case"top":u=w?A?S+y:S-y:A?S-g.height/2-y:S+g.height+y}if(this.barCtx.lastActiveBarSerieIndex===n&&x.enabled){var C=new m(this.barCtx.ctx).getTextRects(this.getStackedTotalDataLabel({realIndex:n,j:o}),f.fontSize);i=A?S-C.height/2-y-x.offsetY+18:S+C.height+y+x.offsetY-18,a=e+x.offsetX}return s.config.chart.stacked||(u<0?u=0+b:u+g.height/3>s.globals.gridHeight&&(u=s.globals.gridHeight-b)),{bcx:h,bcy:l,dataLabelsX:e,dataLabelsY:u,totalDataLabelsX:a,totalDataLabelsY:i,totalDataLabelsAnchor:"middle"}}},{key:"calculateBarsDataLabelsPosition",value:function(t){var e=this.w,i=t.x,a=t.i,s=t.j,r=t.realIndex,o=t.bcy,n=t.barHeight,l=t.barWidth,h=t.textRects,c=t.dataLabelsX,d=t.strokeWidth,g=t.dataLabelsConfig,u=t.barDataLabelsConfig,f=t.barTotalDataLabelsConfig,p=t.offX,x=t.offY,b=e.globals.gridHeight/e.globals.dataPoints;l=Math.abs(l);var v,y,w=o-(this.barCtx.isRangeBar?0:b)+n/2+h.height/2+x-3,k="start",A=this.barCtx.series[a][s]<0,S=i;switch(this.barCtx.isReversed&&(S=i+l-(A?2*l:0),i=e.globals.gridWidth-l),u.position){case"center":c=A?S+l/2-p:Math.max(h.width/2,S-l/2)+p;break;case"bottom":c=A?S+l-d-Math.round(h.width/2)-p:S-l+d+Math.round(h.width/2)+p;break;case"top":c=A?S-d+Math.round(h.width/2)-p:S-d-Math.round(h.width/2)+p}if(this.barCtx.lastActiveBarSerieIndex===r&&f.enabled){var C=new m(this.barCtx.ctx).getTextRects(this.getStackedTotalDataLabel({realIndex:r,j:s}),g.fontSize);A?(v=S-d+Math.round(C.width/2)-p-f.offsetX-15,k="end"):v=S-d-Math.round(C.width/2)+p+f.offsetX+15,y=w+f.offsetY}return e.config.chart.stacked||(c<0?c=c+h.width+d:c+h.width/2>e.globals.gridWidth&&(c=e.globals.gridWidth-h.width-d)),{bcx:i,bcy:o,dataLabelsX:c,dataLabelsY:w,totalDataLabelsX:v,totalDataLabelsY:y,totalDataLabelsAnchor:k}}},{key:"drawCalculatedDataLabels",value:function(t){var i=t.x,a=t.y,s=t.val,r=t.i,o=t.j,n=t.textRects,l=t.barHeight,h=t.barWidth,c=t.dataLabelsConfig,d=this.w,g="rotate(0)";"vertical"===d.config.plotOptions.bar.dataLabels.orientation&&(g="rotate(-90, ".concat(i,", ").concat(a,")"));var u=new O(this.barCtx.ctx),f=new m(this.barCtx.ctx),p=c.formatter,x=null,b=d.globals.collapsedSeriesIndices.indexOf(r)>-1;if(c.enabled&&!b){x=f.group({class:"apexcharts-data-labels",transform:g});var v="";void 0!==s&&(v=p(s,e(e({},d),{},{seriesIndex:r,dataPointIndex:o,w:d})));var y=d.globals.series[r][o]<0,w=d.config.plotOptions.bar.dataLabels.position;if("vertical"===d.config.plotOptions.bar.dataLabels.orientation&&("top"===w&&(c.textAnchor=y?"end":"start"),"center"===w&&(c.textAnchor="middle"),"bottom"===w&&(c.textAnchor=y?"end":"start")),this.barCtx.isRangeBar&&this.barCtx.barOptions.dataLabels.hideOverflowingLabels)h<f.getTextRects(v,parseFloat(c.style.fontSize)).width&&(v="");d.config.chart.stacked&&this.barCtx.barOptions.dataLabels.hideOverflowingLabels&&(this.barCtx.isHorizontal?n.width/1.6>Math.abs(h)&&(v=""):n.height/1.6>Math.abs(l)&&(v=""));var k=e({},c);this.barCtx.isHorizontal&&s<0&&("start"===c.textAnchor?k.textAnchor="end":"end"===c.textAnchor&&(k.textAnchor="start")),u.plotDataLabelsText({x:i,y:a,text:v,i:r,j:o,parent:x,dataLabelsConfig:k,alwaysDrawDataLabel:!0,offsetCorrection:!0})}return x}},{key:"drawTotalDataLabels",value:function(t){var e,i=t.x,a=t.y,s=t.val,r=t.realIndex,o=t.textAnchor,n=t.barTotalDataLabelsConfig,l=new m(this.barCtx.ctx);return n.enabled&&void 0!==i&&void 0!==a&&this.barCtx.lastActiveBarSerieIndex===r&&(e=l.drawText({x:i,y:a,foreColor:n.style.color,text:s,textAnchor:o,fontFamily:n.style.fontFamily,fontSize:n.style.fontSize,fontWeight:n.style.fontWeight})),e}}]),t}(),mt=function(){function t(e){a(this,t),this.w=e.w,this.barCtx=e}return r(t,[{key:"initVariables",value:function(t){var e=this.w;this.barCtx.series=t,this.barCtx.totalItems=0,this.barCtx.seriesLen=0,this.barCtx.visibleI=-1,this.barCtx.visibleItems=1;for(var i=0;i<t.length;i++)if(t[i].length>0&&(this.barCtx.seriesLen=this.barCtx.seriesLen+1,this.barCtx.totalItems+=t[i].length),e.globals.isXNumeric)for(var a=0;a<t[i].length;a++)e.globals.seriesX[i][a]>e.globals.minX&&e.globals.seriesX[i][a]<e.globals.maxX&&this.barCtx.visibleItems++;else this.barCtx.visibleItems=e.globals.dataPoints;0===this.barCtx.seriesLen&&(this.barCtx.seriesLen=1),this.barCtx.zeroSerieses=[],this.barCtx.radiusOnSeriesNumber=t.length-1,e.globals.comboCharts||this.checkZeroSeries({series:t})}},{key:"initialPositions",value:function(){var t,e,i,a,s,r,o,n,l=this.w,h=l.globals.dataPoints;this.barCtx.isRangeBar&&(h=l.globals.labels.length);var c=this.barCtx.seriesLen;if(l.config.plotOptions.bar.rangeBarGroupRows&&(c=1),this.barCtx.isHorizontal)s=(i=l.globals.gridHeight/h)/c,l.globals.isXNumeric&&(s=(i=l.globals.gridHeight/this.barCtx.totalItems)/this.barCtx.seriesLen),s=s*parseInt(this.barCtx.barOptions.barHeight,10)/100,n=this.barCtx.baseLineInvertedY+l.globals.padHorizontal+(this.barCtx.isReversed?l.globals.gridWidth:0)-(this.barCtx.isReversed?2*this.barCtx.baseLineInvertedY:0),e=(i-s*this.barCtx.seriesLen)/2;else{if(a=l.globals.gridWidth/this.barCtx.visibleItems,l.config.xaxis.convertedCatToNumeric&&(a=l.globals.gridWidth/l.globals.dataPoints),r=a/this.barCtx.seriesLen*parseInt(this.barCtx.barOptions.columnWidth,10)/100,l.globals.isXNumeric){var d=this.barCtx.xRatio;l.config.xaxis.convertedCatToNumeric&&(d=this.barCtx.initialXRatio),l.globals.minXDiff&&.5!==l.globals.minXDiff&&l.globals.minXDiff/d>0&&(a=l.globals.minXDiff/d),(r=a/this.barCtx.seriesLen*parseInt(this.barCtx.barOptions.columnWidth,10)/100)<1&&(r=1)}o=l.globals.gridHeight-this.barCtx.baseLineY[this.barCtx.yaxisIndex]-(this.barCtx.isReversed?l.globals.gridHeight:0)+(this.barCtx.isReversed?2*this.barCtx.baseLineY[this.barCtx.yaxisIndex]:0),t=l.globals.padHorizontal+(a-r*this.barCtx.seriesLen)/2}return{x:t,y:e,yDivision:i,xDivision:a,barHeight:s,barWidth:r,zeroH:o,zeroW:n}}},{key:"getPathFillColor",value:function(t,e,i,a){var s,r,o,n,l=this.w,h=new R(this.barCtx.ctx),c=null,d=this.barCtx.barOptions.distributed?i:e;this.barCtx.barOptions.colors.ranges.length>0&&this.barCtx.barOptions.colors.ranges.map((function(a){t[e][i]>=a.from&&t[e][i]<=a.to&&(c=a.color)}));return l.config.series[e].data[i]&&l.config.series[e].data[i].fillColor&&(c=l.config.series[e].data[i].fillColor),h.fillPath({seriesNumber:this.barCtx.barOptions.distributed?d:a,dataPointIndex:i,color:c,value:t[e][i],fillConfig:null===(s=l.config.series[e].data[i])||void 0===s?void 0:s.fill,fillType:null!==(r=l.config.series[e].data[i])&&void 0!==r&&null!==(o=r.fill)&&void 0!==o&&o.type?null===(n=l.config.series[e].data[i])||void 0===n?void 0:n.fill.type:l.config.fill.type})}},{key:"getStrokeWidth",value:function(t,e,i){var a=0,s=this.w;return this.barCtx.series[t][e]?this.barCtx.isNullValue=!1:this.barCtx.isNullValue=!0,s.config.stroke.show&&(this.barCtx.isNullValue||(a=Array.isArray(this.barCtx.strokeWidth)?this.barCtx.strokeWidth[i]:this.barCtx.strokeWidth)),a}},{key:"shouldApplyRadius",value:function(t){var e=this.w,i=!1;return e.config.plotOptions.bar.borderRadius>0&&(e.config.chart.stacked&&"last"===e.config.plotOptions.bar.borderRadiusWhenStacked?this.barCtx.lastActiveBarSerieIndex===t&&(i=!0):i=!0),i}},{key:"barBackground",value:function(t){var e=t.j,i=t.i,a=t.x1,s=t.x2,r=t.y1,o=t.y2,n=t.elSeries,l=this.w,h=new m(this.barCtx.ctx),c=new N(this.barCtx.ctx).getActiveConfigSeriesIndex();if(this.barCtx.barOptions.colors.backgroundBarColors.length>0&&c===i){e>=this.barCtx.barOptions.colors.backgroundBarColors.length&&(e%=this.barCtx.barOptions.colors.backgroundBarColors.length);var d=this.barCtx.barOptions.colors.backgroundBarColors[e],g=h.drawRect(void 0!==a?a:0,void 0!==r?r:0,void 0!==s?s:l.globals.gridWidth,void 0!==o?o:l.globals.gridHeight,this.barCtx.barOptions.colors.backgroundBarRadius,d,this.barCtx.barOptions.colors.backgroundBarOpacity);n.add(g),g.node.classList.add("apexcharts-backgroundBar")}}},{key:"getColumnPaths",value:function(t){var e,i=t.barWidth,a=t.barXPosition,s=t.y1,r=t.y2,o=t.strokeWidth,n=t.realIndex,l=t.i,h=t.j,c=t.w,d=new m(this.barCtx.ctx);(o=Array.isArray(o)?o[n]:o)||(o=0);var g=i,u=a;null!==(e=c.config.series[n].data[h])&&void 0!==e&&e.columnWidthOffset&&(u=a-c.config.series[n].data[h].columnWidthOffset/2,g=i+c.config.series[n].data[h].columnWidthOffset);var f=u,p=u+g;s+=.001,r+=.001;var x=d.move(f,s),b=d.move(f,s),v=d.line(p-o,s);return c.globals.previousPaths.length>0&&(b=this.barCtx.getPreviousPath(n,h,!1)),x=x+d.line(f,r)+d.line(p-o,r)+d.line(p-o,s)+("around"===c.config.plotOptions.bar.borderRadiusApplication?" Z":" z"),b=b+d.line(f,s)+v+v+v+v+v+d.line(f,s)+("around"===c.config.plotOptions.bar.borderRadiusApplication?" Z":" z"),this.shouldApplyRadius(n)&&(x=d.roundPathCorners(x,c.config.plotOptions.bar.borderRadius)),c.config.chart.stacked&&(this.barCtx.yArrj.push(r),this.barCtx.yArrjF.push(Math.abs(s-r)),this.barCtx.yArrjVal.push(this.barCtx.series[l][h])),{pathTo:x,pathFrom:b}}},{key:"getBarpaths",value:function(t){var e,i=t.barYPosition,a=t.barHeight,s=t.x1,r=t.x2,o=t.strokeWidth,n=t.realIndex,l=t.i,h=t.j,c=t.w,d=new m(this.barCtx.ctx);(o=Array.isArray(o)?o[n]:o)||(o=0);var g=i,u=a;null!==(e=c.config.series[n].data[h])&&void 0!==e&&e.barHeightOffset&&(g=i-c.config.series[n].data[h].barHeightOffset/2,u=a+c.config.series[n].data[h].barHeightOffset);var f=g,p=g+u;s+=.001,r+=.001;var x=d.move(s,f),b=d.move(s,f);c.globals.previousPaths.length>0&&(b=this.barCtx.getPreviousPath(n,h,!1));var v=d.line(s,p-o);return x=x+d.line(r,f)+d.line(r,p-o)+v+("around"===c.config.plotOptions.bar.borderRadiusApplication?" Z":" z"),b=b+d.line(s,f)+v+v+v+v+v+d.line(s,f)+("around"===c.config.plotOptions.bar.borderRadiusApplication?" Z":" z"),this.shouldApplyRadius(n)&&(x=d.roundPathCorners(x,c.config.plotOptions.bar.borderRadius)),c.config.chart.stacked&&(this.barCtx.xArrj.push(r),this.barCtx.xArrjF.push(Math.abs(s-r)),this.barCtx.xArrjVal.push(this.barCtx.series[l][h])),{pathTo:x,pathFrom:b}}},{key:"checkZeroSeries",value:function(t){for(var e=t.series,i=this.w,a=0;a<e.length;a++){for(var s=0,r=0;r<e[i.globals.maxValsInArrayIndex].length;r++)s+=e[a][r];0===s&&this.barCtx.zeroSerieses.push(a)}for(var o=e.length-1;o>=0;o--)this.barCtx.zeroSerieses.indexOf(o)>-1&&o===this.radiusOnSeriesNumber&&(this.barCtx.radiusOnSeriesNumber-=1);for(var n=e.length-1;n>=0;n--)i.globals.collapsedSeriesIndices.indexOf(this.barCtx.radiusOnSeriesNumber)>-1&&(this.barCtx.radiusOnSeriesNumber-=1)}},{key:"getXForValue",value:function(t,e){var i=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],a=i?e:null;return null!=t&&(a=e+t/this.barCtx.invertedYRatio-2*(this.barCtx.isReversed?t/this.barCtx.invertedYRatio:0)),a}},{key:"getYForValue",value:function(t,e){var i=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],a=i?e:null;return null!=t&&(a=e-t/this.barCtx.yRatio[this.barCtx.yaxisIndex]+2*(this.barCtx.isReversed?t/this.barCtx.yRatio[this.barCtx.yaxisIndex]:0)),a}},{key:"getGoalValues",value:function(t,e,i,a,s){var r=this,n=this.w,l=[];return n.globals.seriesGoals[a]&&n.globals.seriesGoals[a][s]&&Array.isArray(n.globals.seriesGoals[a][s])&&n.globals.seriesGoals[a][s].forEach((function(a){var s;l.push((o(s={},t,"x"===t?r.getXForValue(a.value,e,!1):r.getYForValue(a.value,i,!1)),o(s,"attrs",a),s))})),l}},{key:"drawGoalLine",value:function(t){var e=t.barXPosition,i=t.barYPosition,a=t.goalX,s=t.goalY,r=t.barWidth,o=t.barHeight,n=new m(this.barCtx.ctx),l=n.group({className:"apexcharts-bar-goals-groups"}),h=null;return this.barCtx.isHorizontal?Array.isArray(a)&&a.forEach((function(t){var e=void 0!==t.attrs.strokeHeight?t.attrs.strokeHeight:o/2,a=i+e+o/2;h=n.drawLine(t.x,a-2*e,t.x,a,t.attrs.strokeColor?t.attrs.strokeColor:void 0,t.attrs.strokeDashArray,t.attrs.strokeWidth?t.attrs.strokeWidth:2,t.attrs.strokeLineCap),l.add(h)})):Array.isArray(s)&&s.forEach((function(t){var i=void 0!==t.attrs.strokeWidth?t.attrs.strokeWidth:r/2,a=e+i+r/2;h=n.drawLine(a-2*i,t.y,a,t.y,t.attrs.strokeColor?t.attrs.strokeColor:void 0,t.attrs.strokeDashArray,t.attrs.strokeHeight?t.attrs.strokeHeight:2,t.attrs.strokeLineCap),l.add(h)})),l}}]),t}(),yt=function(){function t(e,i){a(this,t),this.ctx=e,this.w=e.w;var s=this.w;this.barOptions=s.config.plotOptions.bar,this.isHorizontal=this.barOptions.horizontal,this.strokeWidth=s.config.stroke.width,this.isNullValue=!1,this.isRangeBar=s.globals.seriesRange.length&&this.isHorizontal,this.xyRatios=i,null!==this.xyRatios&&(this.xRatio=i.xRatio,this.initialXRatio=i.initialXRatio,this.yRatio=i.yRatio,this.invertedXRatio=i.invertedXRatio,this.invertedYRatio=i.invertedYRatio,this.baseLineY=i.baseLineY,this.baseLineInvertedY=i.baseLineInvertedY),this.yaxisIndex=0,this.seriesLen=0;var r=new N(this.ctx);this.lastActiveBarSerieIndex=r.getActiveConfigSeriesIndex("desc",["bar","column"]);var o=r.getBarSeriesIndices(),n=new y(this.ctx);this.stackedSeriesTotals=n.getStackedSeriesTotals(this.w.config.series.map((function(t,e){return-1===o.indexOf(e)?e:-1})).filter((function(t){return-1!==t}))),this.barHelpers=new mt(this)}return r(t,[{key:"draw",value:function(t,i){var a=this.w,s=new m(this.ctx),r=new y(this.ctx,a);t=r.getLogSeries(t),this.series=t,this.yRatio=r.getLogYRatios(this.yRatio),this.barHelpers.initVariables(t);var o=s.group({class:"apexcharts-bar-series apexcharts-plot-series"});a.config.dataLabels.enabled&&this.totalItems>this.barOptions.dataLabels.maxItems&&console.warn("WARNING: DataLabels are enabled but there are too many to display. This may cause performance issue when rendering.");for(var n=0,l=0;n<t.length;n++,l++){var h,c,d,g,u=void 0,f=void 0,p=[],b=[],v=a.globals.comboCharts?i[n]:n,w=s.group({class:"apexcharts-series",rel:n+1,seriesName:x.escapeString(a.globals.seriesNames[v]),"data:realIndex":v});this.ctx.series.addCollapsedClassToSeries(w,v),t[n].length>0&&(this.visibleI=this.visibleI+1);var k=0,A=0;this.yRatio.length>1&&(this.yaxisIndex=v),this.isReversed=a.config.yaxis[this.yaxisIndex]&&a.config.yaxis[this.yaxisIndex].reversed;var S=this.barHelpers.initialPositions();f=S.y,k=S.barHeight,c=S.yDivision,g=S.zeroW,u=S.x,A=S.barWidth,h=S.xDivision,d=S.zeroH,this.horizontal||b.push(u+A/2);for(var C=s.group({class:"apexcharts-datalabels","data:realIndex":v}),L=s.group({class:"apexcharts-bar-goals-markers",style:"pointer-events: none"}),P=0;P<a.globals.dataPoints;P++){var T=this.barHelpers.getStrokeWidth(n,P,v),M=null,I={indexes:{i:n,j:P,realIndex:v,bc:l},x:u,y:f,strokeWidth:T,elSeries:w};this.isHorizontal?(M=this.drawBarPaths(e(e({},I),{},{barHeight:k,zeroW:g,yDivision:c})),A=this.series[n][P]/this.invertedYRatio):(M=this.drawColumnPaths(e(e({},I),{},{xDivision:h,barWidth:A,zeroH:d})),k=this.series[n][P]/this.yRatio[this.yaxisIndex]);var z=this.barHelpers.drawGoalLine({barXPosition:M.barXPosition,barYPosition:M.barYPosition,goalX:M.goalX,goalY:M.goalY,barHeight:k,barWidth:A});z&&L.add(z),f=M.y,u=M.x,P>0&&b.push(u+A/2),p.push(f);var X=this.barHelpers.getPathFillColor(t,n,P,v);this.renderSeries({realIndex:v,pathFill:X,j:P,i:n,pathFrom:M.pathFrom,pathTo:M.pathTo,strokeWidth:T,elSeries:w,x:u,y:f,series:t,barHeight:k,barWidth:A,elDataLabelsWrap:C,elGoalsMarkers:L,visibleSeries:this.visibleI,type:"bar"})}a.globals.seriesXvalues[v]=b,a.globals.seriesYvalues[v]=p,o.add(w)}return o}},{key:"renderSeries",value:function(t){var e=t.realIndex,i=t.pathFill,a=t.lineFill,s=t.j,r=t.i,o=t.pathFrom,n=t.pathTo,l=t.strokeWidth,h=t.elSeries,c=t.x,d=t.y,g=t.y1,u=t.y2,f=t.series,p=t.barHeight,x=t.barWidth,b=t.barYPosition,y=t.elDataLabelsWrap,w=t.elGoalsMarkers,k=t.visibleSeries,A=t.type,S=this.w,C=new m(this.ctx);a||(a=this.barOptions.distributed?S.globals.stroke.colors[s]:S.globals.stroke.colors[e]),S.config.series[r].data[s]&&S.config.series[r].data[s].strokeColor&&(a=S.config.series[r].data[s].strokeColor),this.isNullValue&&(i="none");var L=s/S.config.chart.animations.animateGradually.delay*(S.config.chart.animations.speed/S.globals.dataPoints)/2.4,P=C.renderPaths({i:r,j:s,realIndex:e,pathFrom:o,pathTo:n,stroke:a,strokeWidth:l,strokeLineCap:S.config.stroke.lineCap,fill:i,animationDelay:L,initialSpeed:S.config.chart.animations.speed,dataChangeSpeed:S.config.chart.animations.dynamicAnimation.speed,className:"apexcharts-".concat(A,"-area")});P.attr("clip-path","url(#gridRectMask".concat(S.globals.cuid,")"));var T=S.config.forecastDataPoints;T.count>0&&s>=S.globals.dataPoints-T.count&&(P.node.setAttribute("stroke-dasharray",T.dashArray),P.node.setAttribute("stroke-width",T.strokeWidth),P.node.setAttribute("fill-opacity",T.fillOpacity)),void 0!==g&&void 0!==u&&(P.attr("data-range-y1",g),P.attr("data-range-y2",u)),new v(this.ctx).setSelectionFilter(P,e,s),h.add(P);var M=new vt(this).handleBarDataLabels({x:c,y:d,y1:g,y2:u,i:r,j:s,series:f,realIndex:e,barHeight:p,barWidth:x,barYPosition:b,renderedPath:P,visibleSeries:k});return null!==M.dataLabels&&y.add(M.dataLabels),M.totalDataLabels&&y.add(M.totalDataLabels),h.add(y),w&&h.add(w),h}},{key:"drawBarPaths",value:function(t){var e,i=t.indexes,a=t.barHeight,s=t.strokeWidth,r=t.zeroW,o=t.x,n=t.y,l=t.yDivision,h=t.elSeries,c=this.w,d=i.i,g=i.j;if(c.globals.isXNumeric)e=(n=(c.globals.seriesX[d][g]-c.globals.minX)/this.invertedXRatio-a)+a*this.visibleI;else if(c.config.plotOptions.bar.hideZeroBarsWhenGrouped){var u=0,f=0;c.globals.seriesPercent.forEach((function(t,e){t[g]&&u++,e<d&&0===t[g]&&f++})),e=n+(a=this.seriesLen*a/u)*this.visibleI,e-=a*f}o=this.barHelpers.getXForValue(this.series[d][g],r);var p=this.barHelpers.getBarpaths({barYPosition:e,barHeight:a,x1:r,x2:o,strokeWidth:s,series:this.series,realIndex:i.realIndex,i:d,j:g,w:c});return c.globals.isXNumeric||(n+=l),this.barHelpers.barBackground({j:g,i:d,y1:e-a*this.visibleI,y2:a*this.seriesLen,elSeries:h}),{pathTo:p.pathTo,pathFrom:p.pathFrom,x:o,y:n,goalX:this.barHelpers.getGoalValues("x",r,null,d,g),barYPosition:e}}},{key:"drawColumnPaths",value:function(t){var e,i=t.indexes,a=t.x,s=t.y,r=t.xDivision,o=t.barWidth,n=t.zeroH,l=t.strokeWidth,h=t.elSeries,c=this.w,d=i.realIndex,g=i.i,u=i.j,f=i.bc;if(c.globals.isXNumeric){var p=d;c.globals.seriesX[d].length||(p=c.globals.maxValsInArrayIndex),c.globals.seriesX[p][u]&&(a=(c.globals.seriesX[p][u]-c.globals.minX)/this.xRatio-o*this.seriesLen/2),e=a+o*this.visibleI}else if(c.config.plotOptions.bar.hideZeroBarsWhenGrouped){var x=0,b=0;c.globals.seriesPercent.forEach((function(t,e){t[u]&&x++,e<g&&0===t[u]&&b++})),e=a+(o=this.seriesLen*o/x)*this.visibleI,e-=o*b}s=this.barHelpers.getYForValue(this.series[g][u],n);var v=this.barHelpers.getColumnPaths({barXPosition:e,barWidth:o,y1:n,y2:s,strokeWidth:l,series:this.series,realIndex:i.realIndex,i:g,j:u,w:c});return c.globals.isXNumeric||(a+=r),this.barHelpers.barBackground({bc:f,j:u,i:g,x1:e-l/2-o*this.visibleI,x2:o*this.seriesLen+l/2,elSeries:h}),{pathTo:v.pathTo,pathFrom:v.pathFrom,x:a,y:s,goalY:this.barHelpers.getGoalValues("y",null,n,g,u),barXPosition:e}}},{key:"getPreviousPath",value:function(t,e){for(var i,a=this.w,s=0;s<a.globals.previousPaths.length;s++){var r=a.globals.previousPaths[s];r.paths&&r.paths.length>0&&parseInt(r.realIndex,10)===parseInt(t,10)&&void 0!==a.globals.previousPaths[s].paths[e]&&(i=a.globals.previousPaths[s].paths[e].d)}return i}}]),t}(),wt=function(t){n(s,yt);var i=d(s);function s(){return a(this,s),i.apply(this,arguments)}return r(s,[{key:"draw",value:function(t,i){var a=this,s=this.w;this.graphics=new m(this.ctx),this.bar=new yt(this.ctx,this.xyRatios);var r=new y(this.ctx,s);t=r.getLogSeries(t),this.yRatio=r.getLogYRatios(this.yRatio),this.barHelpers.initVariables(t),"100%"===s.config.chart.stackType&&(t=s.globals.seriesPercent.slice()),this.series=t,this.totalItems=0,this.prevY=[],this.prevX=[],this.prevYF=[],this.prevXF=[],this.prevYVal=[],this.prevXVal=[],this.xArrj=[],this.xArrjF=[],this.xArrjVal=[],this.yArrj=[],this.yArrjF=[],this.yArrjVal=[];for(var o=0;o<t.length;o++)t[o].length>0&&(this.totalItems+=t[o].length);for(var n=this.graphics.group({class:"apexcharts-bar-series apexcharts-plot-series"}),l=0,h=0,c=function(r,o){var c=void 0,d=void 0,g=void 0,u=void 0,f=[],p=[],b=s.globals.comboCharts?i[r]:r;a.yRatio.length>1&&(a.yaxisIndex=b),a.isReversed=s.config.yaxis[a.yaxisIndex]&&s.config.yaxis[a.yaxisIndex].reversed;var v=a.graphics.group({class:"apexcharts-series",seriesName:x.escapeString(s.globals.seriesNames[b]),rel:r+1,"data:realIndex":b});a.ctx.series.addCollapsedClassToSeries(v,b);var m=a.graphics.group({class:"apexcharts-datalabels","data:realIndex":b}),y=a.graphics.group({class:"apexcharts-bar-goals-markers",style:"pointer-events: none"}),w=0,k=0,A=a.initialPositions(l,h,c,d,g,u);h=A.y,w=A.barHeight,d=A.yDivision,u=A.zeroW,l=A.x,k=A.barWidth,c=A.xDivision,g=A.zeroH,a.yArrj=[],a.yArrjF=[],a.yArrjVal=[],a.xArrj=[],a.xArrjF=[],a.xArrjVal=[],1===a.prevY.length&&a.prevY[0].every((function(t){return isNaN(t)}))&&(a.prevY[0]=a.prevY[0].map((function(t){return g})),a.prevYF[0]=a.prevYF[0].map((function(t){return 0})));for(var S=0;S<s.globals.dataPoints;S++){var C=a.barHelpers.getStrokeWidth(r,S,b),L={indexes:{i:r,j:S,realIndex:b,bc:o},strokeWidth:C,x:l,y:h,elSeries:v},P=null;a.isHorizontal?(P=a.drawStackedBarPaths(e(e({},L),{},{zeroW:u,barHeight:w,yDivision:d})),k=a.series[r][S]/a.invertedYRatio):(P=a.drawStackedColumnPaths(e(e({},L),{},{xDivision:c,barWidth:k,zeroH:g})),w=a.series[r][S]/a.yRatio[a.yaxisIndex]);var T=a.barHelpers.drawGoalLine({barXPosition:P.barXPosition,barYPosition:P.barYPosition,goalX:P.goalX,goalY:P.goalY,barHeight:w,barWidth:k});T&&y.add(T),h=P.y,l=P.x,f.push(l),p.push(h);var M=a.barHelpers.getPathFillColor(t,r,S,b);v=a.renderSeries({realIndex:b,pathFill:M,j:S,i:r,pathFrom:P.pathFrom,pathTo:P.pathTo,strokeWidth:C,elSeries:v,x:l,y:h,series:t,barHeight:w,barWidth:k,elDataLabelsWrap:m,elGoalsMarkers:y,type:"bar",visibleSeries:0})}s.globals.seriesXvalues[b]=f,s.globals.seriesYvalues[b]=p,a.prevY.push(a.yArrj),a.prevYF.push(a.yArrjF),a.prevYVal.push(a.yArrjVal),a.prevX.push(a.xArrj),a.prevXF.push(a.xArrjF),a.prevXVal.push(a.xArrjVal),n.add(v)},d=0,g=0;d<t.length;d++,g++)c(d,g);return n}},{key:"initialPositions",value:function(t,e,i,a,s,r){var o,n,l=this.w;return this.isHorizontal?(o=(o=a=l.globals.gridHeight/l.globals.dataPoints)*parseInt(l.config.plotOptions.bar.barHeight,10)/100,r=this.baseLineInvertedY+l.globals.padHorizontal+(this.isReversed?l.globals.gridWidth:0)-(this.isReversed?2*this.baseLineInvertedY:0),e=(a-o)/2):(n=i=l.globals.gridWidth/l.globals.dataPoints,n=l.globals.isXNumeric&&l.globals.dataPoints>1?(i=l.globals.minXDiff/this.xRatio)*parseInt(this.barOptions.columnWidth,10)/100:n*parseInt(l.config.plotOptions.bar.columnWidth,10)/100,s=l.globals.gridHeight-this.baseLineY[this.yaxisIndex]-(this.isReversed?l.globals.gridHeight:0)+(this.isReversed?2*this.baseLineY[this.yaxisIndex]:0),t=l.globals.padHorizontal+(i-n)/2),{x:t,y:e,yDivision:a,xDivision:i,barHeight:o,barWidth:n,zeroH:s,zeroW:r}}},{key:"drawStackedBarPaths",value:function(t){for(var e,i=t.indexes,a=t.barHeight,s=t.strokeWidth,r=t.zeroW,o=t.x,n=t.y,l=t.yDivision,h=t.elSeries,c=this.w,d=n,g=i.i,u=i.j,f=0,p=0;p<this.prevXF.length;p++)f+=this.prevXF[p][u];if(g>0){var x=r;this.prevXVal[g-1][u]<0?x=this.series[g][u]>=0?this.prevX[g-1][u]+f-2*(this.isReversed?f:0):this.prevX[g-1][u]:this.prevXVal[g-1][u]>=0&&(x=this.series[g][u]>=0?this.prevX[g-1][u]:this.prevX[g-1][u]-f+2*(this.isReversed?f:0)),e=x}else e=r;o=null===this.series[g][u]?e:e+this.series[g][u]/this.invertedYRatio-2*(this.isReversed?this.series[g][u]/this.invertedYRatio:0);var b=this.barHelpers.getBarpaths({barYPosition:d,barHeight:a,x1:e,x2:o,strokeWidth:s,series:this.series,realIndex:i.realIndex,i:g,j:u,w:c});return this.barHelpers.barBackground({j:u,i:g,y1:d,y2:a,elSeries:h}),n+=l,{pathTo:b.pathTo,pathFrom:b.pathFrom,goalX:this.barHelpers.getGoalValues("x",r,null,g,u),barYPosition:d,x:o,y:n}}},{key:"drawStackedColumnPaths",value:function(t){var e=t.indexes,i=t.x,a=t.y,s=t.xDivision,r=t.barWidth,o=t.zeroH;t.strokeWidth;var n=t.elSeries,l=this.w,h=e.i,c=e.j,d=e.bc;if(l.globals.isXNumeric){var g=l.globals.seriesX[h][c];g||(g=0),i=(g-l.globals.minX)/this.xRatio-r/2}for(var u,f=i,p=0,x=0;x<this.prevYF.length;x++)p+=isNaN(this.prevYF[x][c])?0:this.prevYF[x][c];if(h>0&&!l.globals.isXNumeric||h>0&&l.globals.isXNumeric&&l.globals.seriesX[h-1][c]===l.globals.seriesX[h][c]){var b,v,m=Math.min(this.yRatio.length+1,h+1);if(void 0!==this.prevY[h-1])for(var y=1;y<m;y++)if(!isNaN(this.prevY[h-y][c])){v=this.prevY[h-y][c];break}for(var w=1;w<m;w++){if(this.prevYVal[h-w][c]<0){b=this.series[h][c]>=0?v-p+2*(this.isReversed?p:0):v;break}if(this.prevYVal[h-w][c]>=0){b=this.series[h][c]>=0?v:v+p-2*(this.isReversed?p:0);break}}void 0===b&&(b=l.globals.gridHeight),u=this.prevYF[0].every((function(t){return 0===t}))&&this.prevYF.slice(1,h).every((function(t){return t.every((function(t){return isNaN(t)}))}))?o:b}else u=o;a=this.series[h][c]?u-this.series[h][c]/this.yRatio[this.yaxisIndex]+2*(this.isReversed?this.series[h][c]/this.yRatio[this.yaxisIndex]:0):u;var k=this.barHelpers.getColumnPaths({barXPosition:f,barWidth:r,y1:u,y2:a,yRatio:this.yRatio[this.yaxisIndex],strokeWidth:this.strokeWidth,series:this.series,realIndex:e.realIndex,i:h,j:c,w:l});return this.barHelpers.barBackground({bc:d,j:c,i:h,x1:f,x2:r,elSeries:n}),i+=s,{pathTo:k.pathTo,pathFrom:k.pathFrom,goalY:this.barHelpers.getGoalValues("y",null,o,h,c),barXPosition:f,x:l.globals.isXNumeric?i-s:i,y:a}}}]),s}(),kt=function(t){n(s,yt);var i=d(s);function s(){return a(this,s),i.apply(this,arguments)}return r(s,[{key:"draw",value:function(t,i){var a=this,s=this.w,r=new m(this.ctx),o=new R(this.ctx);this.candlestickOptions=this.w.config.plotOptions.candlestick,this.boxOptions=this.w.config.plotOptions.boxPlot,this.isHorizontal=s.config.plotOptions.bar.horizontal;var n=new y(this.ctx,s);t=n.getLogSeries(t),this.series=t,this.yRatio=n.getLogYRatios(this.yRatio),this.barHelpers.initVariables(t);for(var l=r.group({class:"apexcharts-".concat(s.config.chart.type,"-series apexcharts-plot-series")}),h=function(n){a.isBoxPlot="boxPlot"===s.config.chart.type||"boxPlot"===s.config.series[n].type;var h,c,d,g,u=void 0,f=void 0,p=[],b=[],v=s.globals.comboCharts?i[n]:n,m=r.group({class:"apexcharts-series",seriesName:x.escapeString(s.globals.seriesNames[v]),rel:n+1,"data:realIndex":v});a.ctx.series.addCollapsedClassToSeries(m,v),t[n].length>0&&(a.visibleI=a.visibleI+1);var y,w;a.yRatio.length>1&&(a.yaxisIndex=v);var k=a.barHelpers.initialPositions();f=k.y,y=k.barHeight,c=k.yDivision,g=k.zeroW,u=k.x,w=k.barWidth,h=k.xDivision,d=k.zeroH,b.push(u+w/2);for(var A=r.group({class:"apexcharts-datalabels","data:realIndex":v}),S=function(i){var r=a.barHelpers.getStrokeWidth(n,i,v),l=null,x={indexes:{i:n,j:i,realIndex:v},x:u,y:f,strokeWidth:r,elSeries:m};l=a.isHorizontal?a.drawHorizontalBoxPaths(e(e({},x),{},{yDivision:c,barHeight:y,zeroW:g})):a.drawVerticalBoxPaths(e(e({},x),{},{xDivision:h,barWidth:w,zeroH:d})),f=l.y,u=l.x,i>0&&b.push(u+w/2),p.push(f),l.pathTo.forEach((function(e,h){var c=!a.isBoxPlot&&a.candlestickOptions.wick.useFillColor?l.color[h]:s.globals.stroke.colors[n],d=o.fillPath({seriesNumber:v,dataPointIndex:i,color:l.color[h],value:t[n][i]});a.renderSeries({realIndex:v,pathFill:d,lineFill:c,j:i,i:n,pathFrom:l.pathFrom,pathTo:e,strokeWidth:r,elSeries:m,x:u,y:f,series:t,barHeight:y,barWidth:w,elDataLabelsWrap:A,visibleSeries:a.visibleI,type:s.config.chart.type})}))},C=0;C<s.globals.dataPoints;C++)S(C);s.globals.seriesXvalues[v]=b,s.globals.seriesYvalues[v]=p,l.add(m)},c=0;c<t.length;c++)h(c);return l}},{key:"drawVerticalBoxPaths",value:function(t){var e=t.indexes,i=t.x;t.y;var a=t.xDivision,s=t.barWidth,r=t.zeroH,o=t.strokeWidth,n=this.w,l=new m(this.ctx),h=e.i,c=e.j,d=!0,g=n.config.plotOptions.candlestick.colors.upward,u=n.config.plotOptions.candlestick.colors.downward,f="";this.isBoxPlot&&(f=[this.boxOptions.colors.lower,this.boxOptions.colors.upper]);var p=this.yRatio[this.yaxisIndex],x=e.realIndex,b=this.getOHLCValue(x,c),v=r,y=r;b.o>b.c&&(d=!1);var w=Math.min(b.o,b.c),k=Math.max(b.o,b.c),A=b.m;n.globals.isXNumeric&&(i=(n.globals.seriesX[x][c]-n.globals.minX)/this.xRatio-s/2);var S=i+s*this.visibleI;void 0===this.series[h][c]||null===this.series[h][c]?(w=r,k=r):(w=r-w/p,k=r-k/p,v=r-b.h/p,y=r-b.l/p,A=r-b.m/p);var C=l.move(S,r),L=l.move(S+s/2,w);return n.globals.previousPaths.length>0&&(L=this.getPreviousPath(x,c,!0)),C=this.isBoxPlot?[l.move(S,w)+l.line(S+s/2,w)+l.line(S+s/2,v)+l.line(S+s/4,v)+l.line(S+s-s/4,v)+l.line(S+s/2,v)+l.line(S+s/2,w)+l.line(S+s,w)+l.line(S+s,A)+l.line(S,A)+l.line(S,w+o/2),l.move(S,A)+l.line(S+s,A)+l.line(S+s,k)+l.line(S+s/2,k)+l.line(S+s/2,y)+l.line(S+s-s/4,y)+l.line(S+s/4,y)+l.line(S+s/2,y)+l.line(S+s/2,k)+l.line(S,k)+l.line(S,A)+"z"]:[l.move(S,k)+l.line(S+s/2,k)+l.line(S+s/2,v)+l.line(S+s/2,k)+l.line(S+s,k)+l.line(S+s,w)+l.line(S+s/2,w)+l.line(S+s/2,y)+l.line(S+s/2,w)+l.line(S,w)+l.line(S,k-o/2)],L+=l.move(S,w),n.globals.isXNumeric||(i+=a),{pathTo:C,pathFrom:L,x:i,y:k,barXPosition:S,color:this.isBoxPlot?f:d?[g]:[u]}}},{key:"drawHorizontalBoxPaths",value:function(t){var e=t.indexes;t.x;var i=t.y,a=t.yDivision,s=t.barHeight,r=t.zeroW,o=t.strokeWidth,n=this.w,l=new m(this.ctx),h=e.i,c=e.j,d=this.boxOptions.colors.lower;this.isBoxPlot&&(d=[this.boxOptions.colors.lower,this.boxOptions.colors.upper]);var g=this.invertedYRatio,u=e.realIndex,f=this.getOHLCValue(u,c),p=r,x=r,b=Math.min(f.o,f.c),v=Math.max(f.o,f.c),y=f.m;n.globals.isXNumeric&&(i=(n.globals.seriesX[u][c]-n.globals.minX)/this.invertedXRatio-s/2);var w=i+s*this.visibleI;void 0===this.series[h][c]||null===this.series[h][c]?(b=r,v=r):(b=r+b/g,v=r+v/g,p=r+f.h/g,x=r+f.l/g,y=r+f.m/g);var k=l.move(r,w),A=l.move(b,w+s/2);return n.globals.previousPaths.length>0&&(A=this.getPreviousPath(u,c,!0)),k=[l.move(b,w)+l.line(b,w+s/2)+l.line(p,w+s/2)+l.line(p,w+s/2-s/4)+l.line(p,w+s/2+s/4)+l.line(p,w+s/2)+l.line(b,w+s/2)+l.line(b,w+s)+l.line(y,w+s)+l.line(y,w)+l.line(b+o/2,w),l.move(y,w)+l.line(y,w+s)+l.line(v,w+s)+l.line(v,w+s/2)+l.line(x,w+s/2)+l.line(x,w+s-s/4)+l.line(x,w+s/4)+l.line(x,w+s/2)+l.line(v,w+s/2)+l.line(v,w)+l.line(y,w)+"z"],A+=l.move(b,w),n.globals.isXNumeric||(i+=a),{pathTo:k,pathFrom:A,x:v,y:i,barYPosition:w,color:d}}},{key:"getOHLCValue",value:function(t,e){var i=this.w;return{o:this.isBoxPlot?i.globals.seriesCandleH[t][e]:i.globals.seriesCandleO[t][e],h:this.isBoxPlot?i.globals.seriesCandleO[t][e]:i.globals.seriesCandleH[t][e],m:i.globals.seriesCandleM[t][e],l:this.isBoxPlot?i.globals.seriesCandleC[t][e]:i.globals.seriesCandleL[t][e],c:this.isBoxPlot?i.globals.seriesCandleL[t][e]:i.globals.seriesCandleC[t][e]}}}]),s}(),At=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"checkColorRange",value:function(){var t=this.w,e=!1,i=t.config.plotOptions[t.config.chart.type];return i.colorScale.ranges.length>0&&i.colorScale.ranges.map((function(t,i){t.from<=0&&(e=!0)})),e}},{key:"getShadeColor",value:function(t,e,i,a){var s=this.w,r=1,o=s.config.plotOptions[t].shadeIntensity,n=this.determineColor(t,e,i);s.globals.hasNegs||a?r=s.config.plotOptions[t].reverseNegativeShade?n.percent<0?n.percent/100*(1.25*o):(1-n.percent/100)*(1.25*o):n.percent<=0?1-(1+n.percent/100)*o:(1-n.percent/100)*o:(r=1-n.percent/100,"treemap"===t&&(r=(1-n.percent/100)*(1.25*o)));var l=n.color,h=new x;return s.config.plotOptions[t].enableShades&&(l="dark"===this.w.config.theme.mode?x.hexToRgba(h.shadeColor(-1*r,n.color),s.config.fill.opacity):x.hexToRgba(h.shadeColor(r,n.color),s.config.fill.opacity)),{color:l,colorProps:n}}},{key:"determineColor",value:function(t,e,i){var a=this.w,s=a.globals.series[e][i],r=a.config.plotOptions[t],o=r.colorScale.inverse?i:e;r.distributed&&"treemap"===a.config.chart.type&&(o=i);var n=a.globals.colors[o],l=null,h=Math.min.apply(Math,u(a.globals.series[e])),c=Math.max.apply(Math,u(a.globals.series[e]));r.distributed||"heatmap"!==t||(h=a.globals.minY,c=a.globals.maxY),void 0!==r.colorScale.min&&(h=r.colorScale.min<a.globals.minY?r.colorScale.min:a.globals.minY,c=r.colorScale.max>a.globals.maxY?r.colorScale.max:a.globals.maxY);var d=Math.abs(c)+Math.abs(h),g=100*s/(0===d?d-1e-6:d);r.colorScale.ranges.length>0&&r.colorScale.ranges.map((function(t,e){if(s>=t.from&&s<=t.to){n=t.color,l=t.foreColor?t.foreColor:null,h=t.from,c=t.to;var i=Math.abs(c)+Math.abs(h);g=100*s/(0===i?i-1e-6:i)}}));return{color:n,foreColor:l,percent:g}}},{key:"calculateDataLabels",value:function(t){var e=t.text,i=t.x,a=t.y,s=t.i,r=t.j,o=t.colorProps,n=t.fontSize,l=this.w.config.dataLabels,h=new m(this.ctx),c=new O(this.ctx),d=null;if(l.enabled){d=h.group({class:"apexcharts-data-labels"});var g=l.offsetX,u=l.offsetY,f=i+g,p=a+parseFloat(l.style.fontSize)/3+u;c.plotDataLabelsText({x:f,y:p,text:e,i:s,j:r,color:o.foreColor,parent:d,fontSize:n,dataLabelsConfig:l})}return d}},{key:"addListeners",value:function(t){var e=new m(this.ctx);t.node.addEventListener("mouseenter",e.pathMouseEnter.bind(this,t)),t.node.addEventListener("mouseleave",e.pathMouseLeave.bind(this,t)),t.node.addEventListener("mousedown",e.pathMouseDown.bind(this,t))}}]),t}(),St=function(){function t(e,i){a(this,t),this.ctx=e,this.w=e.w,this.xRatio=i.xRatio,this.yRatio=i.yRatio,this.dynamicAnim=this.w.config.chart.animations.dynamicAnimation,this.helpers=new At(e),this.rectRadius=this.w.config.plotOptions.heatmap.radius,this.strokeWidth=this.w.config.stroke.show?this.w.config.stroke.width:0}return r(t,[{key:"draw",value:function(t){var e=this.w,i=new m(this.ctx),a=i.group({class:"apexcharts-heatmap"});a.attr("clip-path","url(#gridRectMask".concat(e.globals.cuid,")"));var s=e.globals.gridWidth/e.globals.dataPoints,r=e.globals.gridHeight/e.globals.series.length,o=0,n=!1;this.negRange=this.helpers.checkColorRange();var l=t.slice();e.config.yaxis[0].reversed&&(n=!0,l.reverse());for(var h=n?0:l.length-1;n?h<l.length:h>=0;n?h++:h--){var c=i.group({class:"apexcharts-series apexcharts-heatmap-series",seriesName:x.escapeString(e.globals.seriesNames[h]),rel:h+1,"data:realIndex":h});if(this.ctx.series.addCollapsedClassToSeries(c,h),e.config.chart.dropShadow.enabled){var d=e.config.chart.dropShadow;new v(this.ctx).dropShadow(c,d,h)}for(var g=0,u=e.config.plotOptions.heatmap.shadeIntensity,f=0;f<l[h].length;f++){var p=this.helpers.getShadeColor(e.config.chart.type,h,f,this.negRange),b=p.color,y=p.colorProps;if("image"===e.config.fill.type)b=new R(this.ctx).fillPath({seriesNumber:h,dataPointIndex:f,opacity:e.globals.hasNegs?y.percent<0?1-(1+y.percent/100):u+y.percent/100:y.percent/100,patternID:x.randomId(),width:e.config.fill.image.width?e.config.fill.image.width:s,height:e.config.fill.image.height?e.config.fill.image.height:r});var w=this.rectRadius,k=i.drawRect(g,o,s,r,w);if(k.attr({cx:g,cy:o}),k.node.classList.add("apexcharts-heatmap-rect"),c.add(k),k.attr({fill:b,i:h,index:h,j:f,val:l[h][f],"stroke-width":this.strokeWidth,stroke:e.config.plotOptions.heatmap.useFillColorAsStroke?b:e.globals.stroke.colors[0],color:b}),this.helpers.addListeners(k),e.config.chart.animations.enabled&&!e.globals.dataChanged){var A=1;e.globals.resized||(A=e.config.chart.animations.speed),this.animateHeatMap(k,g,o,s,r,A)}if(e.globals.dataChanged){var S=1;if(this.dynamicAnim.enabled&&e.globals.shouldAnimate){S=this.dynamicAnim.speed;var C=e.globals.previousPaths[h]&&e.globals.previousPaths[h][f]&&e.globals.previousPaths[h][f].color;C||(C="rgba(255, 255, 255, 0)"),this.animateHeatColor(k,x.isColorHex(C)?C:x.rgb2hex(C),x.isColorHex(b)?b:x.rgb2hex(b),S)}}var L=(0,e.config.dataLabels.formatter)(e.globals.series[h][f],{value:e.globals.series[h][f],seriesIndex:h,dataPointIndex:f,w:e}),P=this.helpers.calculateDataLabels({text:L,x:g+s/2,y:o+r/2,i:h,j:f,colorProps:y,series:l});null!==P&&c.add(P),g+=s}o+=r,a.add(c)}var T=e.globals.yAxisScale[0].result.slice();e.config.yaxis[0].reversed?T.unshift(""):T.push(""),e.globals.yAxisScale[0].result=T;var M=e.globals.gridHeight/e.globals.series.length;return e.config.yaxis[0].labels.offsetY=-M/2,a}},{key:"animateHeatMap",value:function(t,e,i,a,s,r){var o=new b(this.ctx);o.animateRect(t,{x:e+a/2,y:i+s/2,width:0,height:0},{x:e,y:i,width:a,height:s},r,(function(){o.animationCompleted(t)}))}},{key:"animateHeatColor",value:function(t,e,i,a){t.attr({fill:e}).animate(a).attr({fill:i})}}]),t}(),Ct=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"drawYAxisTexts",value:function(t,e,i,a){var s=this.w,r=s.config.yaxis[0],o=s.globals.yLabelFormatters[0];return new m(this.ctx).drawText({x:t+r.labels.offsetX,y:e+r.labels.offsetY,text:o(a,i),textAnchor:"middle",fontSize:r.labels.style.fontSize,fontFamily:r.labels.style.fontFamily,foreColor:Array.isArray(r.labels.style.colors)?r.labels.style.colors[i]:r.labels.style.colors})}}]),t}(),Lt=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w;var i=this.w;this.chartType=this.w.config.chart.type,this.initialAnim=this.w.config.chart.animations.enabled,this.dynamicAnim=this.initialAnim&&this.w.config.chart.animations.dynamicAnimation.enabled,this.animBeginArr=[0],this.animDur=0,this.donutDataLabels=this.w.config.plotOptions.pie.donut.labels,this.lineColorArr=void 0!==i.globals.stroke.colors?i.globals.stroke.colors:i.globals.colors,this.defaultSize=Math.min(i.globals.gridWidth,i.globals.gridHeight),this.centerY=this.defaultSize/2,this.centerX=i.globals.gridWidth/2,"radialBar"===i.config.chart.type?this.fullAngle=360:this.fullAngle=Math.abs(i.config.plotOptions.pie.endAngle-i.config.plotOptions.pie.startAngle),this.initialAngle=i.config.plotOptions.pie.startAngle%this.fullAngle,i.globals.radialSize=this.defaultSize/2.05-i.config.stroke.width-(i.config.chart.sparkline.enabled?0:i.config.chart.dropShadow.blur),this.donutSize=i.globals.radialSize*parseInt(i.config.plotOptions.pie.donut.size,10)/100,this.maxY=0,this.sliceLabels=[],this.sliceSizes=[],this.prevSectorAngleArr=[]}return r(t,[{key:"draw",value:function(t){var e=this,i=this.w,a=new m(this.ctx);if(this.ret=a.group({class:"apexcharts-pie"}),i.globals.noData)return this.ret;for(var s=0,r=0;r<t.length;r++)s+=x.negToZero(t[r]);var o=[],n=a.group();0===s&&(s=1e-5),t.forEach((function(t){e.maxY=Math.max(e.maxY,t)})),i.config.yaxis[0].max&&(this.maxY=i.config.yaxis[0].max),"back"===i.config.grid.position&&"polarArea"===this.chartType&&this.drawPolarElements(this.ret);for(var l=0;l<t.length;l++){var h=this.fullAngle*x.negToZero(t[l])/s;o.push(h),"polarArea"===this.chartType?(o[l]=this.fullAngle/t.length,this.sliceSizes.push(i.globals.radialSize*t[l]/this.maxY)):this.sliceSizes.push(i.globals.radialSize)}if(i.globals.dataChanged){for(var c,d=0,g=0;g<i.globals.previousPaths.length;g++)d+=x.negToZero(i.globals.previousPaths[g]);for(var u=0;u<i.globals.previousPaths.length;u++)c=this.fullAngle*x.negToZero(i.globals.previousPaths[u])/d,this.prevSectorAngleArr.push(c)}this.donutSize<0&&(this.donutSize=0);var f=i.config.plotOptions.pie.customScale,p=i.globals.gridWidth/2,b=i.globals.gridHeight/2,v=p-i.globals.gridWidth/2*f,y=b-i.globals.gridHeight/2*f;if("donut"===this.chartType){var w=a.drawCircle(this.donutSize);w.attr({cx:this.centerX,cy:this.centerY,fill:i.config.plotOptions.pie.donut.background?i.config.plotOptions.pie.donut.background:"transparent"}),n.add(w)}var k=this.drawArcs(o,t);if(this.sliceLabels.forEach((function(t){k.add(t)})),n.attr({transform:"translate(".concat(v,", ").concat(y,") scale(").concat(f,")")}),n.add(k),this.ret.add(n),this.donutDataLabels.show){var A=this.renderInnerDataLabels(this.donutDataLabels,{hollowSize:this.donutSize,centerX:this.centerX,centerY:this.centerY,opacity:this.donutDataLabels.show,translateX:v,translateY:y});this.ret.add(A)}return"front"===i.config.grid.position&&"polarArea"===this.chartType&&this.drawPolarElements(this.ret),this.ret}},{key:"drawArcs",value:function(t,e){var i=this.w,a=new v(this.ctx),s=new m(this.ctx),r=new R(this.ctx),o=s.group({class:"apexcharts-slices"}),n=this.initialAngle,l=this.initialAngle,h=this.initialAngle,c=this.initialAngle;this.strokeWidth=i.config.stroke.show?i.config.stroke.width:0;for(var d=0;d<t.length;d++){var g=s.group({class:"apexcharts-series apexcharts-pie-series",seriesName:x.escapeString(i.globals.seriesNames[d]),rel:d+1,"data:realIndex":d});o.add(g),l=c,h=(n=h)+t[d],c=l+this.prevSectorAngleArr[d];var u=h<n?this.fullAngle+h-n:h-n,f=r.fillPath({seriesNumber:d,size:this.sliceSizes[d],value:e[d]}),p=this.getChangedPath(l,c),b=s.drawPath({d:p,stroke:Array.isArray(this.lineColorArr)?this.lineColorArr[d]:this.lineColorArr,strokeWidth:0,fill:f,fillOpacity:i.config.fill.opacity,classes:"apexcharts-pie-area apexcharts-".concat(this.chartType.toLowerCase(),"-slice-").concat(d)});if(b.attr({index:0,j:d}),a.setSelectionFilter(b,0,d),i.config.chart.dropShadow.enabled){var y=i.config.chart.dropShadow;a.dropShadow(b,y,d)}this.addListeners(b,this.donutDataLabels),m.setAttrs(b.node,{"data:angle":u,"data:startAngle":n,"data:strokeWidth":this.strokeWidth,"data:value":e[d]});var w={x:0,y:0};"pie"===this.chartType||"polarArea"===this.chartType?w=x.polarToCartesian(this.centerX,this.centerY,i.globals.radialSize/1.25+i.config.plotOptions.pie.dataLabels.offset,(n+u/2)%this.fullAngle):"donut"===this.chartType&&(w=x.polarToCartesian(this.centerX,this.centerY,(i.globals.radialSize+this.donutSize)/2+i.config.plotOptions.pie.dataLabels.offset,(n+u/2)%this.fullAngle)),g.add(b);var k=0;if(!this.initialAnim||i.globals.resized||i.globals.dataChanged?this.animBeginArr.push(0):(0===(k=u/this.fullAngle*i.config.chart.animations.speed)&&(k=1),this.animDur=k+this.animDur,this.animBeginArr.push(this.animDur)),this.dynamicAnim&&i.globals.dataChanged?this.animatePaths(b,{size:this.sliceSizes[d],endAngle:h,startAngle:n,prevStartAngle:l,prevEndAngle:c,animateStartingPos:!0,i:d,animBeginArr:this.animBeginArr,shouldSetPrevPaths:!0,dur:i.config.chart.animations.dynamicAnimation.speed}):this.animatePaths(b,{size:this.sliceSizes[d],endAngle:h,startAngle:n,i:d,totalItems:t.length-1,animBeginArr:this.animBeginArr,dur:k}),i.config.plotOptions.pie.expandOnClick&&"polarArea"!==this.chartType&&b.click(this.pieClicked.bind(this,d)),void 0!==i.globals.selectedDataPoints[0]&&i.globals.selectedDataPoints[0].indexOf(d)>-1&&this.pieClicked(d),i.config.dataLabels.enabled){var A=w.x,S=w.y,C=100*u/this.fullAngle+"%";if(0!==u&&i.config.plotOptions.pie.dataLabels.minAngleToShowLabel<t[d]){var L=i.config.dataLabels.formatter;void 0!==L&&(C=L(i.globals.seriesPercent[d][0],{seriesIndex:d,w:i}));var P=i.globals.dataLabels.style.colors[d],T=s.group({class:"apexcharts-datalabels"}),M=s.drawText({x:A,y:S,text:C,textAnchor:"middle",fontSize:i.config.dataLabels.style.fontSize,fontFamily:i.config.dataLabels.style.fontFamily,fontWeight:i.config.dataLabels.style.fontWeight,foreColor:P});if(T.add(M),i.config.dataLabels.dropShadow.enabled){var I=i.config.dataLabels.dropShadow;a.dropShadow(M,I)}M.node.classList.add("apexcharts-pie-label"),i.config.chart.animations.animate&&!1===i.globals.resized&&(M.node.classList.add("apexcharts-pie-label-delay"),M.node.style.animationDelay=i.config.chart.animations.speed/940+"s"),this.sliceLabels.push(T)}}}return o}},{key:"addListeners",value:function(t,e){var i=new m(this.ctx);t.node.addEventListener("mouseenter",i.pathMouseEnter.bind(this,t)),t.node.addEventListener("mouseleave",i.pathMouseLeave.bind(this,t)),t.node.addEventListener("mouseleave",this.revertDataLabelsInner.bind(this,t.node,e)),t.node.addEventListener("mousedown",i.pathMouseDown.bind(this,t)),this.donutDataLabels.total.showAlways||(t.node.addEventListener("mouseenter",this.printDataLabelsInner.bind(this,t.node,e)),t.node.addEventListener("mousedown",this.printDataLabelsInner.bind(this,t.node,e)))}},{key:"animatePaths",value:function(t,e){var i=this.w,a=e.endAngle<e.startAngle?this.fullAngle+e.endAngle-e.startAngle:e.endAngle-e.startAngle,s=a,r=e.startAngle,o=e.startAngle;void 0!==e.prevStartAngle&&void 0!==e.prevEndAngle&&(r=e.prevEndAngle,s=e.prevEndAngle<e.prevStartAngle?this.fullAngle+e.prevEndAngle-e.prevStartAngle:e.prevEndAngle-e.prevStartAngle),e.i===i.config.series.length-1&&(a+o>this.fullAngle?e.endAngle=e.endAngle-(a+o):a+o<this.fullAngle&&(e.endAngle=e.endAngle+(this.fullAngle-(a+o)))),a===this.fullAngle&&(a=this.fullAngle-.01),this.animateArc(t,r,o,a,s,e)}},{key:"animateArc",value:function(t,e,i,a,s,r){var o,n=this,l=this.w,h=new b(this.ctx),c=r.size;(isNaN(e)||isNaN(s))&&(e=i,s=a,r.dur=0);var d=a,g=i,u=e<i?this.fullAngle+e-i:e-i;l.globals.dataChanged&&r.shouldSetPrevPaths&&r.prevEndAngle&&(o=n.getPiePath({me:n,startAngle:r.prevStartAngle,angle:r.prevEndAngle<r.prevStartAngle?this.fullAngle+r.prevEndAngle-r.prevStartAngle:r.prevEndAngle-r.prevStartAngle,size:c}),t.attr({d:o})),0!==r.dur?t.animate(r.dur,l.globals.easing,r.animBeginArr[r.i]).afterAll((function(){"pie"!==n.chartType&&"donut"!==n.chartType&&"polarArea"!==n.chartType||this.animate(l.config.chart.animations.dynamicAnimation.speed).attr({"stroke-width":n.strokeWidth}),r.i===l.config.series.length-1&&h.animationCompleted(t)})).during((function(l){d=u+(a-u)*l,r.animateStartingPos&&(d=s+(a-s)*l,g=e-s+(i-(e-s))*l),o=n.getPiePath({me:n,startAngle:g,angle:d,size:c}),t.node.setAttribute("data:pathOrig",o),t.attr({d:o})})):(o=n.getPiePath({me:n,startAngle:g,angle:a,size:c}),r.isTrack||(l.globals.animationEnded=!0),t.node.setAttribute("data:pathOrig",o),t.attr({d:o,"stroke-width":n.strokeWidth}))}},{key:"pieClicked",value:function(t){var e,i=this.w,a=this,s=a.sliceSizes[t]+(i.config.plotOptions.pie.expandOnClick?4:0),r=i.globals.dom.Paper.select(".apexcharts-".concat(a.chartType.toLowerCase(),"-slice-").concat(t)).members[0];if("true"!==r.attr("data:pieClicked")){var o=i.globals.dom.baseEl.getElementsByClassName("apexcharts-pie-area");Array.prototype.forEach.call(o,(function(t){t.setAttribute("data:pieClicked","false");var e=t.getAttribute("data:pathOrig");t.setAttribute("d",e)})),r.attr("data:pieClicked","true");var n=parseInt(r.attr("data:startAngle"),10),l=parseInt(r.attr("data:angle"),10);e=a.getPiePath({me:a,startAngle:n,angle:l,size:s}),360!==l&&r.plot(e)}else{r.attr({"data:pieClicked":"false"}),this.revertDataLabelsInner(r.node,this.donutDataLabels);var h=r.attr("data:pathOrig");r.attr({d:h})}}},{key:"getChangedPath",value:function(t,e){var i="";return this.dynamicAnim&&this.w.globals.dataChanged&&(i=this.getPiePath({me:this,startAngle:t,angle:e-t,size:this.size})),i}},{key:"getPiePath",value:function(t){var e=t.me,i=t.startAngle,a=t.angle,s=t.size,r=i,o=Math.PI*(r-90)/180,n=a+i;Math.ceil(n)>=this.fullAngle+this.w.config.plotOptions.pie.startAngle%this.fullAngle&&(n=this.fullAngle+this.w.config.plotOptions.pie.startAngle%this.fullAngle-.01),Math.ceil(n)>this.fullAngle&&(n-=this.fullAngle);var l=Math.PI*(n-90)/180,h=e.centerX+s*Math.cos(o),c=e.centerY+s*Math.sin(o),d=e.centerX+s*Math.cos(l),g=e.centerY+s*Math.sin(l),u=x.polarToCartesian(e.centerX,e.centerY,e.donutSize,n),f=x.polarToCartesian(e.centerX,e.centerY,e.donutSize,r),p=a>180?1:0,b=["M",h,c,"A",s,s,0,p,1,d,g];return"donut"===e.chartType?[].concat(b,["L",u.x,u.y,"A",e.donutSize,e.donutSize,0,p,0,f.x,f.y,"L",h,c,"z"]).join(" "):"pie"===e.chartType||"polarArea"===e.chartType?[].concat(b,["L",e.centerX,e.centerY,"L",h,c]).join(" "):[].concat(b).join(" ")}},{key:"drawPolarElements",value:function(t){var e=this.w,i=new _(this.ctx),a=new m(this.ctx),s=new Ct(this.ctx),r=a.group(),o=a.group(),n=i.niceScale(0,Math.ceil(this.maxY),e.config.yaxis[0].tickAmount,0,!0),l=n.result.reverse(),h=n.result.length;this.maxY=n.niceMax;for(var c=e.globals.radialSize,d=c/(h-1),g=0;g<h-1;g++){var u=a.drawCircle(c);if(u.attr({cx:this.centerX,cy:this.centerY,fill:"none","stroke-width":e.config.plotOptions.polarArea.rings.strokeWidth,stroke:e.config.plotOptions.polarArea.rings.strokeColor}),e.config.yaxis[0].show){var f=s.drawYAxisTexts(this.centerX,this.centerY-c+parseInt(e.config.yaxis[0].labels.style.fontSize,10)/2,g,l[g]);o.add(f)}r.add(u),c-=d}this.drawSpokes(t),t.add(r),t.add(o)}},{key:"renderInnerDataLabels",value:function(t,e){var i=this.w,a=new m(this.ctx),s=a.group({class:"apexcharts-datalabels-group",transform:"translate(".concat(e.translateX?e.translateX:0,", ").concat(e.translateY?e.translateY:0,") scale(").concat(i.config.plotOptions.pie.customScale,")")}),r=t.total.show;s.node.style.opacity=e.opacity;var o,n,l=e.centerX,h=e.centerY;o=void 0===t.name.color?i.globals.colors[0]:t.name.color;var c=t.name.fontSize,d=t.name.fontFamily,g=t.name.fontWeight;n=void 0===t.value.color?i.config.chart.foreColor:t.value.color;var u=t.value.formatter,f="",p="";if(r?(o=t.total.color,c=t.total.fontSize,d=t.total.fontFamily,g=t.total.fontWeight,p=t.total.label,f=t.total.formatter(i)):1===i.globals.series.length&&(f=u(i.globals.series[0],i),p=i.globals.seriesNames[0]),p&&(p=t.name.formatter(p,t.total.show,i)),t.name.show){var x=a.drawText({x:l,y:h+parseFloat(t.name.offsetY),text:p,textAnchor:"middle",foreColor:o,fontSize:c,fontWeight:g,fontFamily:d});x.node.classList.add("apexcharts-datalabel-label"),s.add(x)}if(t.value.show){var b=t.name.show?parseFloat(t.value.offsetY)+16:t.value.offsetY,v=a.drawText({x:l,y:h+b,text:f,textAnchor:"middle",foreColor:n,fontWeight:t.value.fontWeight,fontSize:t.value.fontSize,fontFamily:t.value.fontFamily});v.node.classList.add("apexcharts-datalabel-value"),s.add(v)}return s}},{key:"printInnerLabels",value:function(t,e,i,a){var s,r=this.w;a?s=void 0===t.name.color?r.globals.colors[parseInt(a.parentNode.getAttribute("rel"),10)-1]:t.name.color:r.globals.series.length>1&&t.total.show&&(s=t.total.color);var o=r.globals.dom.baseEl.querySelector(".apexcharts-datalabel-label"),n=r.globals.dom.baseEl.querySelector(".apexcharts-datalabel-value");i=(0,t.value.formatter)(i,r),a||"function"!=typeof t.total.formatter||(i=t.total.formatter(r));var l=e===t.total.label;e=t.name.formatter(e,l,r),null!==o&&(o.textContent=e),null!==n&&(n.textContent=i),null!==o&&(o.style.fill=s)}},{key:"printDataLabelsInner",value:function(t,e){var i=this.w,a=t.getAttribute("data:value"),s=i.globals.seriesNames[parseInt(t.parentNode.getAttribute("rel"),10)-1];i.globals.series.length>1&&this.printInnerLabels(e,s,a,t);var r=i.globals.dom.baseEl.querySelector(".apexcharts-datalabels-group");null!==r&&(r.style.opacity=1)}},{key:"drawSpokes",value:function(t){var e=this,i=this.w,a=new m(this.ctx),s=i.config.plotOptions.polarArea.spokes;if(0!==s.strokeWidth){for(var r=[],o=360/i.globals.series.length,n=0;n<i.globals.series.length;n++)r.push(x.polarToCartesian(this.centerX,this.centerY,i.globals.radialSize,i.config.plotOptions.pie.startAngle+o*n));r.forEach((function(i,r){var o=a.drawLine(i.x,i.y,e.centerX,e.centerY,Array.isArray(s.connectorColors)?s.connectorColors[r]:s.connectorColors);t.add(o)}))}}},{key:"revertDataLabelsInner",value:function(t,e,i){var a=this,s=this.w,r=s.globals.dom.baseEl.querySelector(".apexcharts-datalabels-group"),o=!1,n=s.globals.dom.baseEl.getElementsByClassName("apexcharts-pie-area"),l=function(t){var i=t.makeSliceOut,s=t.printLabel;Array.prototype.forEach.call(n,(function(t){"true"===t.getAttribute("data:pieClicked")&&(i&&(o=!0),s&&a.printDataLabelsInner(t,e))}))};if(l({makeSliceOut:!0,printLabel:!1}),e.total.show&&s.globals.series.length>1)o&&!e.total.showAlways?l({makeSliceOut:!1,printLabel:!0}):this.printInnerLabels(e,e.total.label,e.total.formatter(s));else if(l({makeSliceOut:!1,printLabel:!0}),!o)if(s.globals.selectedDataPoints.length&&s.globals.series.length>1)if(s.globals.selectedDataPoints[0].length>0){var h=s.globals.selectedDataPoints[0],c=s.globals.dom.baseEl.querySelector(".apexcharts-".concat(this.chartType.toLowerCase(),"-slice-").concat(h));this.printDataLabelsInner(c,e)}else r&&s.globals.selectedDataPoints.length&&0===s.globals.selectedDataPoints[0].length&&(r.style.opacity=0);else r&&s.globals.series.length>1&&(r.style.opacity=0)}}]),t}(),Pt=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.chartType=this.w.config.chart.type,this.initialAnim=this.w.config.chart.animations.enabled,this.dynamicAnim=this.initialAnim&&this.w.config.chart.animations.dynamicAnimation.enabled,this.animDur=0;var i=this.w;this.graphics=new m(this.ctx),this.lineColorArr=void 0!==i.globals.stroke.colors?i.globals.stroke.colors:i.globals.colors,this.defaultSize=i.globals.svgHeight<i.globals.svgWidth?i.globals.gridHeight+1.5*i.globals.goldenPadding:i.globals.gridWidth,this.isLog=i.config.yaxis[0].logarithmic,this.coreUtils=new y(this.ctx),this.maxValue=this.isLog?this.coreUtils.getLogVal(i.globals.maxY,0):i.globals.maxY,this.minValue=this.isLog?this.coreUtils.getLogVal(this.w.globals.minY,0):i.globals.minY,this.polygons=i.config.plotOptions.radar.polygons,this.strokeWidth=i.config.stroke.show?i.config.stroke.width:0,this.size=this.defaultSize/2.1-this.strokeWidth-i.config.chart.dropShadow.blur,i.config.xaxis.labels.show&&(this.size=this.size-i.globals.xAxisLabelsWidth/1.75),void 0!==i.config.plotOptions.radar.size&&(this.size=i.config.plotOptions.radar.size),this.dataRadiusOfPercent=[],this.dataRadius=[],this.angleArr=[],this.yaxisLabelsTextsPos=[]}return r(t,[{key:"draw",value:function(t){var i=this,a=this.w,s=new R(this.ctx),r=[],o=new O(this.ctx);t.length&&(this.dataPointsLen=t[a.globals.maxValsInArrayIndex].length),this.disAngle=2*Math.PI/this.dataPointsLen;var n=a.globals.gridWidth/2,l=a.globals.gridHeight/2,h=n+a.config.plotOptions.radar.offsetX,c=l+a.config.plotOptions.radar.offsetY,d=this.graphics.group({class:"apexcharts-radar-series apexcharts-plot-series",transform:"translate(".concat(h||0,", ").concat(c||0,")")}),g=[],u=null,f=null;if(this.yaxisLabels=this.graphics.group({class:"apexcharts-yaxis"}),t.forEach((function(t,n){var l=t.length===a.globals.dataPoints,h=i.graphics.group().attr({class:"apexcharts-series","data:longestSeries":l,seriesName:x.escapeString(a.globals.seriesNames[n]),rel:n+1,"data:realIndex":n});i.dataRadiusOfPercent[n]=[],i.dataRadius[n]=[],i.angleArr[n]=[],t.forEach((function(t,e){var a=Math.abs(i.maxValue-i.minValue);t+=Math.abs(i.minValue),i.isLog&&(t=i.coreUtils.getLogVal(t,0)),i.dataRadiusOfPercent[n][e]=t/a,i.dataRadius[n][e]=i.dataRadiusOfPercent[n][e]*i.size,i.angleArr[n][e]=e*i.disAngle})),g=i.getDataPointsPos(i.dataRadius[n],i.angleArr[n]);var c=i.createPaths(g,{x:0,y:0});u=i.graphics.group({class:"apexcharts-series-markers-wrap apexcharts-element-hidden"}),f=i.graphics.group({class:"apexcharts-datalabels","data:realIndex":n}),a.globals.delayedElements.push({el:u.node,index:n});var d={i:n,realIndex:n,animationDelay:n,initialSpeed:a.config.chart.animations.speed,dataChangeSpeed:a.config.chart.animations.dynamicAnimation.speed,className:"apexcharts-radar",shouldClipToGrid:!1,bindEventsOnPaths:!1,stroke:a.globals.stroke.colors[n],strokeLineCap:a.config.stroke.lineCap},p=null;a.globals.previousPaths.length>0&&(p=i.getPreviousPath(n));for(var b=0;b<c.linePathsTo.length;b++){var m=i.graphics.renderPaths(e(e({},d),{},{pathFrom:null===p?c.linePathsFrom[b]:p,pathTo:c.linePathsTo[b],strokeWidth:Array.isArray(i.strokeWidth)?i.strokeWidth[n]:i.strokeWidth,fill:"none",drawShadow:!1}));h.add(m);var y=s.fillPath({seriesNumber:n}),w=i.graphics.renderPaths(e(e({},d),{},{pathFrom:null===p?c.areaPathsFrom[b]:p,pathTo:c.areaPathsTo[b],strokeWidth:0,fill:y,drawShadow:!1}));if(a.config.chart.dropShadow.enabled){var k=new v(i.ctx),A=a.config.chart.dropShadow;k.dropShadow(w,Object.assign({},A,{noUserSpaceOnUse:!0}),n)}h.add(w)}t.forEach((function(t,s){var r=new D(i.ctx).getMarkerConfig({cssClass:"apexcharts-marker",seriesIndex:n,dataPointIndex:s}),l=i.graphics.drawMarker(g[s].x,g[s].y,r);l.attr("rel",s),l.attr("j",s),l.attr("index",n),l.node.setAttribute("default-marker-size",r.pSize);var c=i.graphics.group({class:"apexcharts-series-markers"});c&&c.add(l),u.add(c),h.add(u);var d=a.config.dataLabels;if(d.enabled){var p=d.formatter(a.globals.series[n][s],{seriesIndex:n,dataPointIndex:s,w:a});o.plotDataLabelsText({x:g[s].x,y:g[s].y,text:p,textAnchor:"middle",i:n,j:n,parent:f,offsetCorrection:!1,dataLabelsConfig:e({},d)})}h.add(f)})),r.push(h)})),this.drawPolygons({parent:d}),a.config.xaxis.labels.show){var p=this.drawXAxisTexts();d.add(p)}return r.forEach((function(t){d.add(t)})),d.add(this.yaxisLabels),d}},{key:"drawPolygons",value:function(t){for(var e=this,i=this.w,a=t.parent,s=new Ct(this.ctx),r=i.globals.yAxisScale[0].result.reverse(),o=r.length,n=[],l=this.size/(o-1),h=0;h<o;h++)n[h]=l*h;n.reverse();var c=[],d=[];n.forEach((function(t,i){var a=x.getPolygonPos(t,e.dataPointsLen),s="";a.forEach((function(t,a){if(0===i){var r=e.graphics.drawLine(t.x,t.y,0,0,Array.isArray(e.polygons.connectorColors)?e.polygons.connectorColors[a]:e.polygons.connectorColors);d.push(r)}0===a&&e.yaxisLabelsTextsPos.push({x:t.x,y:t.y}),s+=t.x+","+t.y+" "})),c.push(s)})),c.forEach((function(t,s){var r=e.polygons.strokeColors,o=e.polygons.strokeWidth,n=e.graphics.drawPolygon(t,Array.isArray(r)?r[s]:r,Array.isArray(o)?o[s]:o,i.globals.radarPolygons.fill.colors[s]);a.add(n)})),d.forEach((function(t){a.add(t)})),i.config.yaxis[0].show&&this.yaxisLabelsTextsPos.forEach((function(t,i){var a=s.drawYAxisTexts(t.x,t.y,i,r[i]);e.yaxisLabels.add(a)}))}},{key:"drawXAxisTexts",value:function(){var t=this,i=this.w,a=i.config.xaxis.labels,s=this.graphics.group({class:"apexcharts-xaxis"}),r=x.getPolygonPos(this.size,this.dataPointsLen);return i.globals.labels.forEach((function(o,n){var l=i.config.xaxis.labels.formatter,h=new O(t.ctx);if(r[n]){var c=t.getTextPos(r[n],t.size),d=l(o,{seriesIndex:-1,dataPointIndex:n,w:i});h.plotDataLabelsText({x:c.newX,y:c.newY,text:d,textAnchor:c.textAnchor,i:n,j:n,parent:s,color:Array.isArray(a.style.colors)&&a.style.colors[n]?a.style.colors[n]:"#a8a8a8",dataLabelsConfig:e({textAnchor:c.textAnchor,dropShadow:{enabled:!1}},a),offsetCorrection:!1})}})),s}},{key:"createPaths",value:function(t,e){var i=this,a=[],s=[],r=[],o=[];if(t.length){s=[this.graphics.move(e.x,e.y)],o=[this.graphics.move(e.x,e.y)];var n=this.graphics.move(t[0].x,t[0].y),l=this.graphics.move(t[0].x,t[0].y);t.forEach((function(e,a){n+=i.graphics.line(e.x,e.y),l+=i.graphics.line(e.x,e.y),a===t.length-1&&(n+="Z",l+="Z")})),a.push(n),r.push(l)}return{linePathsFrom:s,linePathsTo:a,areaPathsFrom:o,areaPathsTo:r}}},{key:"getTextPos",value:function(t,e){var i="middle",a=t.x,s=t.y;return Math.abs(t.x)>=10?t.x>0?(i="start",a+=10):t.x<0&&(i="end",a-=10):i="middle",Math.abs(t.y)>=e-10&&(t.y<0?s-=10:t.y>0&&(s+=10)),{textAnchor:i,newX:a,newY:s}}},{key:"getPreviousPath",value:function(t){for(var e=this.w,i=null,a=0;a<e.globals.previousPaths.length;a++){var s=e.globals.previousPaths[a];s.paths.length>0&&parseInt(s.realIndex,10)===parseInt(t,10)&&void 0!==e.globals.previousPaths[a].paths[0]&&(i=e.globals.previousPaths[a].paths[0].d)}return i}},{key:"getDataPointsPos",value:function(t,e){var i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:this.dataPointsLen;t=t||[],e=e||[];for(var a=[],s=0;s<i;s++){var r={};r.x=t[s]*Math.sin(e[s]),r.y=-t[s]*Math.cos(e[s]),a.push(r)}return a}}]),t}(),Tt=function(t){n(i,Lt);var e=d(i);function i(t){var s;a(this,i),(s=e.call(this,t)).ctx=t,s.w=t.w,s.animBeginArr=[0],s.animDur=0;var r=s.w;return s.startAngle=r.config.plotOptions.radialBar.startAngle,s.endAngle=r.config.plotOptions.radialBar.endAngle,s.totalAngle=Math.abs(r.config.plotOptions.radialBar.endAngle-r.config.plotOptions.radialBar.startAngle),s.trackStartAngle=r.config.plotOptions.radialBar.track.startAngle,s.trackEndAngle=r.config.plotOptions.radialBar.track.endAngle,s.donutDataLabels=s.w.config.plotOptions.radialBar.dataLabels,s.radialDataLabels=s.donutDataLabels,s.trackStartAngle||(s.trackStartAngle=s.startAngle),s.trackEndAngle||(s.trackEndAngle=s.endAngle),360===s.endAngle&&(s.endAngle=359.99),s.margin=parseInt(r.config.plotOptions.radialBar.track.margin,10),s}return r(i,[{key:"draw",value:function(t){var e=this.w,i=new m(this.ctx),a=i.group({class:"apexcharts-radialbar"});if(e.globals.noData)return a;var s=i.group(),r=this.defaultSize/2,o=e.globals.gridWidth/2,n=this.defaultSize/2.05;e.config.chart.sparkline.enabled||(n=n-e.config.stroke.width-e.config.chart.dropShadow.blur);var l=e.globals.fill.colors;if(e.config.plotOptions.radialBar.track.show){var h=this.drawTracks({size:n,centerX:o,centerY:r,colorArr:l,series:t});s.add(h)}var c=this.drawArcs({size:n,centerX:o,centerY:r,colorArr:l,series:t}),d=360;e.config.plotOptions.radialBar.startAngle<0&&(d=this.totalAngle);var g=(360-d)/360;if(e.globals.radialSize=n-n*g,this.radialDataLabels.value.show){var u=Math.max(this.radialDataLabels.value.offsetY,this.radialDataLabels.name.offsetY);e.globals.radialSize+=u*g}return s.add(c.g),"front"===e.config.plotOptions.radialBar.hollow.position&&(c.g.add(c.elHollow),c.dataLabels&&c.g.add(c.dataLabels)),a.add(s),a}},{key:"drawTracks",value:function(t){var e=this.w,i=new m(this.ctx),a=i.group({class:"apexcharts-tracks"}),s=new v(this.ctx),r=new R(this.ctx),o=this.getStrokeWidth(t);t.size=t.size-o/2;for(var n=0;n<t.series.length;n++){var l=i.group({class:"apexcharts-radialbar-track apexcharts-track"});a.add(l),l.attr({rel:n+1}),t.size=t.size-o-this.margin;var h=e.config.plotOptions.radialBar.track,c=r.fillPath({seriesNumber:0,size:t.size,fillColors:Array.isArray(h.background)?h.background[n]:h.background,solid:!0}),d=this.trackStartAngle,g=this.trackEndAngle;Math.abs(g)+Math.abs(d)>=360&&(g=360-Math.abs(this.startAngle)-.1);var u=i.drawPath({d:"",stroke:c,strokeWidth:o*parseInt(h.strokeWidth,10)/100,fill:"none",strokeOpacity:h.opacity,classes:"apexcharts-radialbar-area"});if(h.dropShadow.enabled){var f=h.dropShadow;s.dropShadow(u,f)}l.add(u),u.attr("id","apexcharts-radialbarTrack-"+n),this.animatePaths(u,{centerX:t.centerX,centerY:t.centerY,endAngle:g,startAngle:d,size:t.size,i:n,totalItems:2,animBeginArr:0,dur:0,isTrack:!0,easing:e.globals.easing})}return a}},{key:"drawArcs",value:function(t){var e=this.w,i=new m(this.ctx),a=new R(this.ctx),s=new v(this.ctx),r=i.group(),o=this.getStrokeWidth(t);t.size=t.size-o/2;var n=e.config.plotOptions.radialBar.hollow.background,l=t.size-o*t.series.length-this.margin*t.series.length-o*parseInt(e.config.plotOptions.radialBar.track.strokeWidth,10)/100/2,h=l-e.config.plotOptions.radialBar.hollow.margin;void 0!==e.config.plotOptions.radialBar.hollow.image&&(n=this.drawHollowImage(t,r,l,n));var c=this.drawHollow({size:h,centerX:t.centerX,centerY:t.centerY,fill:n||"transparent"});if(e.config.plotOptions.radialBar.hollow.dropShadow.enabled){var d=e.config.plotOptions.radialBar.hollow.dropShadow;s.dropShadow(c,d)}var g=1;!this.radialDataLabels.total.show&&e.globals.series.length>1&&(g=0);var u=null;this.radialDataLabels.show&&(u=this.renderInnerDataLabels(this.radialDataLabels,{hollowSize:l,centerX:t.centerX,centerY:t.centerY,opacity:g})),"back"===e.config.plotOptions.radialBar.hollow.position&&(r.add(c),u&&r.add(u));var f=!1;e.config.plotOptions.radialBar.inverseOrder&&(f=!0);for(var p=f?t.series.length-1:0;f?p>=0:p<t.series.length;f?p--:p++){var b=i.group({class:"apexcharts-series apexcharts-radial-series",seriesName:x.escapeString(e.globals.seriesNames[p])});r.add(b),b.attr({rel:p+1,"data:realIndex":p}),this.ctx.series.addCollapsedClassToSeries(b,p),t.size=t.size-o-this.margin;var y=a.fillPath({seriesNumber:p,size:t.size,value:t.series[p]}),w=this.startAngle,k=void 0,A=x.negToZero(t.series[p]>100?100:t.series[p])/100,S=Math.round(this.totalAngle*A)+this.startAngle,C=void 0;e.globals.dataChanged&&(k=this.startAngle,C=Math.round(this.totalAngle*x.negToZero(e.globals.previousPaths[p])/100)+k),Math.abs(S)+Math.abs(w)>=360&&(S-=.01),Math.abs(C)+Math.abs(k)>=360&&(C-=.01);var L=S-w,P=Array.isArray(e.config.stroke.dashArray)?e.config.stroke.dashArray[p]:e.config.stroke.dashArray,T=i.drawPath({d:"",stroke:y,strokeWidth:o,fill:"none",fillOpacity:e.config.fill.opacity,classes:"apexcharts-radialbar-area apexcharts-radialbar-slice-"+p,strokeDashArray:P});if(m.setAttrs(T.node,{"data:angle":L,"data:value":t.series[p]}),e.config.chart.dropShadow.enabled){var M=e.config.chart.dropShadow;s.dropShadow(T,M,p)}s.setSelectionFilter(T,0,p),this.addListeners(T,this.radialDataLabels),b.add(T),T.attr({index:0,j:p});var I=0;!this.initialAnim||e.globals.resized||e.globals.dataChanged||(I=e.config.chart.animations.speed),e.globals.dataChanged&&(I=e.config.chart.animations.dynamicAnimation.speed),this.animDur=I/(1.2*t.series.length)+this.animDur,this.animBeginArr.push(this.animDur),this.animatePaths(T,{centerX:t.centerX,centerY:t.centerY,endAngle:S,startAngle:w,prevEndAngle:C,prevStartAngle:k,size:t.size,i:p,totalItems:2,animBeginArr:this.animBeginArr,dur:I,shouldSetPrevPaths:!0,easing:e.globals.easing})}return{g:r,elHollow:c,dataLabels:u}}},{key:"drawHollow",value:function(t){var e=new m(this.ctx).drawCircle(2*t.size);return e.attr({class:"apexcharts-radialbar-hollow",cx:t.centerX,cy:t.centerY,r:t.size,fill:t.fill}),e}},{key:"drawHollowImage",value:function(t,e,i,a){var s=this.w,r=new R(this.ctx),o=x.randomId(),n=s.config.plotOptions.radialBar.hollow.image;if(s.config.plotOptions.radialBar.hollow.imageClipped)r.clippedImgArea({width:i,height:i,image:n,patternID:"pattern".concat(s.globals.cuid).concat(o)}),a="url(#pattern".concat(s.globals.cuid).concat(o,")");else{var l=s.config.plotOptions.radialBar.hollow.imageWidth,h=s.config.plotOptions.radialBar.hollow.imageHeight;if(void 0===l&&void 0===h){var c=s.globals.dom.Paper.image(n).loaded((function(e){this.move(t.centerX-e.width/2+s.config.plotOptions.radialBar.hollow.imageOffsetX,t.centerY-e.height/2+s.config.plotOptions.radialBar.hollow.imageOffsetY)}));e.add(c)}else{var d=s.globals.dom.Paper.image(n).loaded((function(e){this.move(t.centerX-l/2+s.config.plotOptions.radialBar.hollow.imageOffsetX,t.centerY-h/2+s.config.plotOptions.radialBar.hollow.imageOffsetY),this.size(l,h)}));e.add(d)}}return a}},{key:"getStrokeWidth",value:function(t){var e=this.w;return t.size*(100-parseInt(e.config.plotOptions.radialBar.hollow.size,10))/100/(t.series.length+1)-this.margin}}]),i}(),Mt=function(t){n(s,yt);var i=d(s);function s(){return a(this,s),i.apply(this,arguments)}return r(s,[{key:"draw",value:function(t,i){var a=this.w,s=new m(this.ctx);this.rangeBarOptions=this.w.config.plotOptions.rangeBar,this.series=t,this.seriesRangeStart=a.globals.seriesRangeStart,this.seriesRangeEnd=a.globals.seriesRangeEnd,this.barHelpers.initVariables(t);for(var r=s.group({class:"apexcharts-rangebar-series apexcharts-plot-series"}),o=0;o<t.length;o++){var n,l,h,c=void 0,d=void 0,g=void 0,u=a.globals.comboCharts?i[o]:o,f=s.group({class:"apexcharts-series",seriesName:x.escapeString(a.globals.seriesNames[u]),rel:o+1,"data:realIndex":u});this.ctx.series.addCollapsedClassToSeries(f,u),t[o].length>0&&(this.visibleI=this.visibleI+1);var p=0,b=0;this.yRatio.length>1&&(this.yaxisIndex=u);var v=this.barHelpers.initialPositions();d=v.y,h=v.zeroW,c=v.x,b=v.barWidth,n=v.xDivision,l=v.zeroH;for(var y=s.group({class:"apexcharts-datalabels","data:realIndex":u}),w=s.group({class:"apexcharts-rangebar-goals-markers",style:"pointer-events: none"}),k=0;k<a.globals.dataPoints;k++){var A=this.barHelpers.getStrokeWidth(o,k,u),S=this.seriesRangeStart[o][k],C=this.seriesRangeEnd[o][k],L=null,P=null,T={x:c,y:d,strokeWidth:A,elSeries:f};if(g=v.yDivision,p=v.barHeight,this.isHorizontal){P=d+p*this.visibleI;var M=this.seriesLen;a.config.plotOptions.bar.rangeBarGroupRows&&(M=1);var I=(g-p*M)/2;if(void 0===a.config.series[o].data[k])break;if(a.config.series[o].data[k].x){var z=this.detectOverlappingBars({i:o,j:k,barYPosition:P,srty:I,barHeight:p,yDivision:g,initPositions:v});p=z.barHeight,P=z.barYPosition}b=(L=this.drawRangeBarPaths(e({indexes:{i:o,j:k,realIndex:u},barHeight:p,barYPosition:P,zeroW:h,yDivision:g,y1:S,y2:C},T))).barWidth}else p=(L=this.drawRangeColumnPaths(e({indexes:{i:o,j:k,realIndex:u},zeroH:l,barWidth:b,xDivision:n},T))).barHeight;var X=this.barHelpers.drawGoalLine({barXPosition:L.barXPosition,barYPosition:P,goalX:L.goalX,goalY:L.goalY,barHeight:p,barWidth:b});X&&w.add(X),d=L.y,c=L.x;var E=this.barHelpers.getPathFillColor(t,o,k,u),Y=a.globals.stroke.colors[u];this.renderSeries({realIndex:u,pathFill:E,lineFill:Y,j:k,i:o,x:c,y:d,y1:S,y2:C,pathFrom:L.pathFrom,pathTo:L.pathTo,strokeWidth:A,elSeries:f,series:t,barHeight:p,barYPosition:P,barWidth:b,elDataLabelsWrap:y,elGoalsMarkers:w,visibleSeries:this.visibleI,type:"rangebar"})}r.add(f)}return r}},{key:"detectOverlappingBars",value:function(t){var e=t.i,i=t.j,a=t.barYPosition,s=t.srty,r=t.barHeight,o=t.yDivision,n=t.initPositions,l=this.w,h=[],c=l.config.series[e].data[i].rangeName,d=l.config.series[e].data[i].x,g=l.globals.labels.indexOf(d),u=l.globals.seriesRange[e].findIndex((function(t){return t.x===d&&t.overlaps.length>0}));return a=l.config.plotOptions.bar.rangeBarGroupRows?s+o*g:s+r*this.visibleI+o*g,u>-1&&!l.config.plotOptions.bar.rangeBarOverlap&&(h=l.globals.seriesRange[e][u].overlaps).indexOf(c)>-1&&(a=(r=n.barHeight/h.length)*this.visibleI+o*(100-parseInt(this.barOptions.barHeight,10))/100/2+r*(this.visibleI+h.indexOf(c))+o*g),{barYPosition:a,barHeight:r}}},{key:"drawRangeColumnPaths",value:function(t){var e=t.indexes,i=t.x;t.strokeWidth;var a=t.xDivision,s=t.barWidth,r=t.zeroH,o=this.w,n=e.i,l=e.j,h=this.yRatio[this.yaxisIndex],c=e.realIndex,d=this.getRangeValue(c,l),g=Math.min(d.start,d.end),u=Math.max(d.start,d.end);o.globals.isXNumeric&&(i=(o.globals.seriesX[n][l]-o.globals.minX)/this.xRatio-s/2);var f=i+s*this.visibleI;void 0===this.series[n][l]||null===this.series[n][l]?g=r:(g=r-g/h,u=r-u/h);var p=Math.abs(u-g),x=this.barHelpers.getColumnPaths({barXPosition:f,barWidth:s,y1:g,y2:u,strokeWidth:this.strokeWidth,series:this.seriesRangeEnd,realIndex:e.realIndex,i:c,j:l,w:o});return o.globals.isXNumeric||(i+=a),{pathTo:x.pathTo,pathFrom:x.pathFrom,barHeight:p,x:i,y:u,goalY:this.barHelpers.getGoalValues("y",null,r,n,l),barXPosition:f}}},{key:"drawRangeBarPaths",value:function(t){var e=t.indexes,i=t.y,a=t.y1,s=t.y2,r=t.yDivision,o=t.barHeight,n=t.barYPosition,l=t.zeroW,h=this.w,c=l+a/this.invertedYRatio,d=l+s/this.invertedYRatio,g=Math.abs(d-c),u=this.barHelpers.getBarpaths({barYPosition:n,barHeight:o,x1:c,x2:d,strokeWidth:this.strokeWidth,series:this.seriesRangeEnd,i:e.realIndex,realIndex:e.realIndex,j:e.j,w:h});return h.globals.isXNumeric||(i+=r),{pathTo:u.pathTo,pathFrom:u.pathFrom,barWidth:g,x:d,goalX:this.barHelpers.getGoalValues("x",l,null,e.realIndex,e.j),y:i}}},{key:"getRangeValue",value:function(t,e){var i=this.w;return{start:i.globals.seriesRangeStart[t][e],end:i.globals.seriesRangeEnd[t][e]}}}]),s}(),It=function(){function t(e){a(this,t),this.w=e.w,this.lineCtx=e}return r(t,[{key:"sameValueSeriesFix",value:function(t,e){var i=this.w;if(("gradient"===i.config.fill.type||"gradient"===i.config.fill.type[t])&&new y(this.lineCtx.ctx,i).seriesHaveSameValues(t)){var a=e[t].slice();a[a.length-1]=a[a.length-1]+1e-6,e[t]=a}return e}},{key:"calculatePoints",value:function(t){var e=t.series,i=t.realIndex,a=t.x,s=t.y,r=t.i,o=t.j,n=t.prevY,l=this.w,h=[],c=[];if(0===o){var d=this.lineCtx.categoryAxisCorrection+l.config.markers.offsetX;l.globals.isXNumeric&&(d=(l.globals.seriesX[i][0]-l.globals.minX)/this.lineCtx.xRatio+l.config.markers.offsetX),h.push(d),c.push(x.isNumber(e[r][0])?n+l.config.markers.offsetY:null),h.push(a+l.config.markers.offsetX),c.push(x.isNumber(e[r][o+1])?s+l.config.markers.offsetY:null)}else h.push(a+l.config.markers.offsetX),c.push(x.isNumber(e[r][o+1])?s+l.config.markers.offsetY:null);return{x:h,y:c}}},{key:"checkPreviousPaths",value:function(t){for(var e=t.pathFromLine,i=t.pathFromArea,a=t.realIndex,s=this.w,r=0;r<s.globals.previousPaths.length;r++){var o=s.globals.previousPaths[r];("line"===o.type||"area"===o.type)&&o.paths.length>0&&parseInt(o.realIndex,10)===parseInt(a,10)&&("line"===o.type?(this.lineCtx.appendPathFrom=!1,e=s.globals.previousPaths[r].paths[0].d):"area"===o.type&&(this.lineCtx.appendPathFrom=!1,i=s.globals.previousPaths[r].paths[0].d,s.config.stroke.show&&s.globals.previousPaths[r].paths[1]&&(e=s.globals.previousPaths[r].paths[1].d)))}return{pathFromLine:e,pathFromArea:i}}},{key:"determineFirstPrevY",value:function(t){var e,i=t.i,a=t.series,s=t.prevY,r=t.lineYPosition,o=this.w;if(void 0!==(null===(e=a[i])||void 0===e?void 0:e[0]))s=(r=o.config.chart.stacked&&i>0?this.lineCtx.prevSeriesY[i-1][0]:this.lineCtx.zeroY)-a[i][0]/this.lineCtx.yRatio[this.lineCtx.yaxisIndex]+2*(this.lineCtx.isReversed?a[i][0]/this.lineCtx.yRatio[this.lineCtx.yaxisIndex]:0);else if(o.config.chart.stacked&&i>0&&void 0===a[i][0])for(var n=i-1;n>=0;n--)if(null!==a[n][0]&&void 0!==a[n][0]){s=r=this.lineCtx.prevSeriesY[n][0];break}return{prevY:s,lineYPosition:r}}}]),t}(),zt=function(){function t(e,i,s){a(this,t),this.ctx=e,this.w=e.w,this.xyRatios=i,this.pointsChart=!("bubble"!==this.w.config.chart.type&&"scatter"!==this.w.config.chart.type)||s,this.scatter=new H(this.ctx),this.noNegatives=this.w.globals.minX===Number.MAX_VALUE,this.lineHelpers=new It(this),this.markers=new D(this.ctx),this.prevSeriesY=[],this.categoryAxisCorrection=0,this.yaxisIndex=0}return r(t,[{key:"draw",value:function(t,i,a,s){var r=this.w,o=new m(this.ctx),n=r.globals.comboCharts?i:r.config.chart.type,l=o.group({class:"apexcharts-".concat(n,"-series apexcharts-plot-series")}),h=new y(this.ctx,r);this.yRatio=this.xyRatios.yRatio,this.zRatio=this.xyRatios.zRatio,this.xRatio=this.xyRatios.xRatio,this.baseLineY=this.xyRatios.baseLineY,t=h.getLogSeries(t),this.yRatio=h.getLogYRatios(this.yRatio);for(var c=[],d=0;d<t.length;d++){t=this.lineHelpers.sameValueSeriesFix(d,t);var g=r.globals.comboCharts?a[d]:d;this._initSerieVariables(t,d,g);var u=[],f=[],p=r.globals.padHorizontal+this.categoryAxisCorrection;this.ctx.series.addCollapsedClassToSeries(this.elSeries,g),r.globals.isXNumeric&&r.globals.seriesX.length>0&&(p=(r.globals.seriesX[g][0]-r.globals.minX)/this.xRatio),f.push(p);var x,b=p,v=void 0,w=b,k=this.zeroY,A=this.zeroY;k=this.lineHelpers.determineFirstPrevY({i:d,series:t,prevY:k,lineYPosition:0}).prevY,u.push(k),x=k;"rangeArea"===n&&(v=A=this.lineHelpers.determineFirstPrevY({i:d,series:s,prevY:A,lineYPosition:0}).prevY);var S={type:n,series:t,realIndex:g,i:d,x:p,y:1,pX:b,pY:x,pathsFrom:this._calculatePathsFrom({type:n,series:t,i:d,realIndex:g,prevX:w,prevY:k,prevY2:A}),linePaths:[],areaPaths:[],seriesIndex:a,lineYPosition:0,xArrj:f,yArrj:u,seriesRangeEnd:s},C=this._iterateOverDataPoints(e(e({},S),{},{iterations:"rangeArea"===n?t[d].length-1:void 0,isRangeStart:!0}));if("rangeArea"===n){var L=this._calculatePathsFrom({series:s,i:d,realIndex:g,prevX:w,prevY:A}),P=this._iterateOverDataPoints(e(e({},S),{},{series:s,pY:v,pathsFrom:L,iterations:s[d].length-1,isRangeStart:!1}));C.linePaths[0]=P.linePath+C.linePath,C.pathFromLine=P.pathFromLine+C.pathFromLine}this._handlePaths({type:n,realIndex:g,i:d,paths:C}),this.elSeries.add(this.elPointsMain),this.elSeries.add(this.elDataLabelsWrap),c.push(this.elSeries)}if(r.config.chart.stacked)for(var T=c.length;T>0;T--)l.add(c[T-1]);else for(var M=0;M<c.length;M++)l.add(c[M]);return l}},{key:"_initSerieVariables",value:function(t,e,i){var a=this.w,s=new m(this.ctx);this.xDivision=a.globals.gridWidth/(a.globals.dataPoints-("on"===a.config.xaxis.tickPlacement?1:0)),this.strokeWidth=Array.isArray(a.config.stroke.width)?a.config.stroke.width[i]:a.config.stroke.width,this.yRatio.length>1&&(this.yaxisIndex=i),this.isReversed=a.config.yaxis[this.yaxisIndex]&&a.config.yaxis[this.yaxisIndex].reversed,this.zeroY=a.globals.gridHeight-this.baseLineY[this.yaxisIndex]-(this.isReversed?a.globals.gridHeight:0)+(this.isReversed?2*this.baseLineY[this.yaxisIndex]:0),this.areaBottomY=this.zeroY,(this.zeroY>a.globals.gridHeight||"end"===a.config.plotOptions.area.fillTo)&&(this.areaBottomY=a.globals.gridHeight),this.categoryAxisCorrection=this.xDivision/2,this.elSeries=s.group({class:"apexcharts-series",seriesName:x.escapeString(a.globals.seriesNames[i])}),this.elPointsMain=s.group({class:"apexcharts-series-markers-wrap","data:realIndex":i}),this.elDataLabelsWrap=s.group({class:"apexcharts-datalabels","data:realIndex":i});var r=t[e].length===a.globals.dataPoints;this.elSeries.attr({"data:longestSeries":r,rel:e+1,"data:realIndex":i}),this.appendPathFrom=!0}},{key:"_calculatePathsFrom",value:function(t){var e,i,a,s,r=t.type,o=t.series,n=t.i,l=t.realIndex,h=t.prevX,c=t.prevY,d=t.prevY2,g=this.w,u=new m(this.ctx);if(null===o[n][0]){for(var f=0;f<o[n].length;f++)if(null!==o[n][f]){h=this.xDivision*f,c=this.zeroY-o[n][f]/this.yRatio[this.yaxisIndex],e=u.move(h,c),i=u.move(h,this.areaBottomY);break}}else e=u.move(h,c),"rangeArea"===r&&(e=u.move(h,d)+u.line(h,c)),i=u.move(h,this.areaBottomY)+u.line(h,c);if(a=u.move(-1,this.zeroY)+u.line(-1,this.zeroY),s=u.move(-1,this.zeroY)+u.line(-1,this.zeroY),g.globals.previousPaths.length>0){var p=this.lineHelpers.checkPreviousPaths({pathFromLine:a,pathFromArea:s,realIndex:l});a=p.pathFromLine,s=p.pathFromArea}return{prevX:h,prevY:c,linePath:e,areaPath:i,pathFromLine:a,pathFromArea:s}}},{key:"_handlePaths",value:function(t){var i=t.type,a=t.realIndex,s=t.i,r=t.paths,o=this.w,n=new m(this.ctx),l=new R(this.ctx);this.prevSeriesY.push(r.yArrj),o.globals.seriesXvalues[a]=r.xArrj,o.globals.seriesYvalues[a]=r.yArrj;var h=o.config.forecastDataPoints;if(h.count>0&&"rangeArea"!==i){var c=o.globals.seriesXvalues[a][o.globals.seriesXvalues[a].length-h.count-1],d=n.drawRect(c,0,o.globals.gridWidth,o.globals.gridHeight,0);o.globals.dom.elForecastMask.appendChild(d.node);var g=n.drawRect(0,0,c,o.globals.gridHeight,0);o.globals.dom.elNonForecastMask.appendChild(g.node)}this.pointsChart||o.globals.delayedElements.push({el:this.elPointsMain.node,index:a});var u={i:s,realIndex:a,animationDelay:s,initialSpeed:o.config.chart.animations.speed,dataChangeSpeed:o.config.chart.animations.dynamicAnimation.speed,className:"apexcharts-".concat(i)};if("area"===i)for(var f=l.fillPath({seriesNumber:a}),p=0;p<r.areaPaths.length;p++){var x=n.renderPaths(e(e({},u),{},{pathFrom:r.pathFromArea,pathTo:r.areaPaths[p],stroke:"none",strokeWidth:0,strokeLineCap:null,fill:f}));this.elSeries.add(x)}if(o.config.stroke.show&&!this.pointsChart){var b=null;if("line"===i)b=l.fillPath({seriesNumber:a,i:s});else if("solid"===o.config.stroke.fill.type)b=o.globals.stroke.colors[a];else{var v=o.config.fill;o.config.fill=o.config.stroke.fill,b=l.fillPath({seriesNumber:a,i:s}),o.config.fill=v}for(var y=0;y<r.linePaths.length;y++){var w=b;"rangeArea"===i&&(w=l.fillPath({seriesNumber:a}));var k=e(e({},u),{},{pathFrom:r.pathFromLine,pathTo:r.linePaths[y],stroke:b,strokeWidth:this.strokeWidth,strokeLineCap:o.config.stroke.lineCap,fill:"rangeArea"===i?w:"none"}),A=n.renderPaths(k);if(this.elSeries.add(A),A.attr("fill-rule","evenodd"),h.count>0&&"rangeArea"!==i){var S=n.renderPaths(k);S.node.setAttribute("stroke-dasharray",h.dashArray),h.strokeWidth&&S.node.setAttribute("stroke-width",h.strokeWidth),this.elSeries.add(S),S.attr("clip-path","url(#forecastMask".concat(o.globals.cuid,")")),A.attr("clip-path","url(#nonForecastMask".concat(o.globals.cuid,")"))}}}}},{key:"_iterateOverDataPoints",value:function(t){var e=t.type,i=t.series,a=t.iterations,s=t.realIndex,r=t.i,o=t.x,n=t.y,l=t.pX,h=t.pY,c=t.pathsFrom,d=t.linePaths,g=t.areaPaths,u=t.seriesIndex,f=t.lineYPosition,p=t.xArrj,b=t.yArrj,v=t.isRangeStart,y=t.seriesRangeEnd,w=this.w,k=new m(this.ctx),A=this.yRatio,S=c.prevY,C=c.linePath,L=c.areaPath,P=c.pathFromLine,T=c.pathFromArea,M=x.isNumber(w.globals.minYArr[s])?w.globals.minYArr[s]:w.globals.minY;a||(a=w.globals.dataPoints>1?w.globals.dataPoints-1:w.globals.dataPoints);for(var I=n,z=0;z<a;z++){var X=void 0===i[r][z+1]||null===i[r][z+1];if(w.globals.isXNumeric){var E=w.globals.seriesX[s][z+1];void 0===w.globals.seriesX[s][z+1]&&(E=w.globals.seriesX[s][a-1]),o=(E-w.globals.minX)/this.xRatio}else o+=this.xDivision;if(w.config.chart.stacked)if(r>0&&w.globals.collapsedSeries.length<w.config.series.length-1){f=this.prevSeriesY[function(t){for(var e=t,i=0;i<w.globals.series.length;i++)if(w.globals.collapsedSeriesIndices.indexOf(t)>-1){e--;break}return e>=0?e:0}(r-1)][z+1]}else f=this.zeroY;else f=this.zeroY;X?n=f-M/A[this.yaxisIndex]+2*(this.isReversed?M/A[this.yaxisIndex]:0):(n=f-i[r][z+1]/A[this.yaxisIndex]+2*(this.isReversed?i[r][z+1]/A[this.yaxisIndex]:0),"rangeArea"===e&&(I=f-y[r][z+1]/A[this.yaxisIndex]+2*(this.isReversed?y[r][z+1]/A[this.yaxisIndex]:0))),p.push(o),b.push(n);var Y=this.lineHelpers.calculatePoints({series:i,x:o,y:n,realIndex:s,i:r,j:z,prevY:S}),F=this._createPaths({type:e,series:i,i:r,realIndex:s,j:z,x:o,y:n,y2:I,pX:l,pY:h,linePath:C,areaPath:L,linePaths:d,areaPaths:g,seriesIndex:u,isRangeStart:v});g=F.areaPaths,d=F.linePaths,l=F.pX,h=F.pY,L=F.areaPath,C=F.linePath,this.appendPathFrom&&(P+=k.line(o,this.zeroY),T+=k.line(o,this.zeroY)),this.handleNullDataPoints(i,Y,r,z,s),this._handleMarkersAndLabels({type:e,pointsPos:Y,i:r,j:z,realIndex:s,isRangeStart:v})}return{yArrj:b,xArrj:p,pathFromArea:T,areaPaths:g,pathFromLine:P,linePaths:d,linePath:C,areaPath:L}}},{key:"_handleMarkersAndLabels",value:function(t){var e=t.type,i=t.pointsPos,a=t.isRangeStart,s=t.i,r=t.j,o=t.realIndex,n=this.w,l=new O(this.ctx);if(this.pointsChart)this.scatter.draw(this.elSeries,r,{realIndex:o,pointsPos:i,zRatio:this.zRatio,elParent:this.elPointsMain});else{n.globals.series[s].length>1&&this.elPointsMain.node.classList.add("apexcharts-element-hidden");var h=this.markers.plotChartMarkers(i,o,r+1);null!==h&&this.elPointsMain.add(h)}var c=l.drawDataLabel({type:e,isRangeStart:a,pos:i,i:o,j:r+1});null!==c&&this.elDataLabelsWrap.add(c)}},{key:"_createPaths",value:function(t){var e=t.type,i=t.series,a=t.i,s=t.realIndex,r=t.j,o=t.x,n=t.y,l=t.y2,h=t.pX,c=t.pY,d=t.linePath,g=t.areaPath,u=t.linePaths,f=t.areaPaths,p=t.seriesIndex,x=t.isRangeStart,b=this.w,v=new m(this.ctx),y=b.config.stroke.curve,w=this.areaBottomY;if(Array.isArray(b.config.stroke.curve)&&(y=Array.isArray(p)?b.config.stroke.curve[p[a]]:b.config.stroke.curve[a]),"smooth"===y){var k=.35*(o-h);b.globals.hasNullValues?(null!==i[a][r]&&(null!==i[a][r+1]?(d=v.move(h,c)+v.curve(h+k,c,o-k,n,o+1,n),g=v.move(h+1,c)+v.curve(h+k,c,o-k,n,o+1,n)+v.line(o,w)+v.line(h,w)+"z"):(d=v.move(h,c),g=v.move(h,c)+"z")),u.push(d),f.push(g)):(d+=v.curve(h+k,c,o-k,n,o,n),g+=v.curve(h+k,c,o-k,n,o,n)),h=o,c=n,r===i[a].length-2&&(g=g+v.curve(h,c,o,n,o,w)+v.move(o,n)+"z","rangeArea"===e&&x?d=d+v.curve(h,c,o,n,o,l)+v.move(o,l)+"z":b.globals.hasNullValues||(u.push(d),f.push(g)))}else{if(null===i[a][r+1]){d+=v.move(o,n);var A=b.globals.isXNumeric?(b.globals.seriesX[s][r]-b.globals.minX)/this.xRatio:o-this.xDivision;g=g+v.line(A,w)+v.move(o,n)+"z"}null===i[a][r]&&(d+=v.move(o,n),g+=v.move(o,w)),"stepline"===y?(d=d+v.line(o,null,"H")+v.line(null,n,"V"),g=g+v.line(o,null,"H")+v.line(null,n,"V")):"straight"===y&&(d+=v.line(o,n),g+=v.line(o,n)),r===i[a].length-2&&(g=g+v.line(o,w)+v.move(o,n)+"z","rangeArea"===e&&x?d=d+v.line(o,l)+v.move(o,l)+"z":(u.push(d),f.push(g)))}return{linePaths:u,areaPaths:f,pX:h,pY:c,linePath:d,areaPath:g}}},{key:"handleNullDataPoints",value:function(t,e,i,a,s){var r=this.w;if(null===t[i][a]&&r.config.markers.showNullDataPoints||1===t[i].length){var o=this.markers.plotChartMarkers(e,s,a+1,this.strokeWidth-r.config.markers.strokeWidth/2,!0);null!==o&&this.elPointsMain.add(o)}}}]),t}();window.TreemapSquared={},window.TreemapSquared.generate=function(){function t(e,i,a,s){this.xoffset=e,this.yoffset=i,this.height=s,this.width=a,this.shortestEdge=function(){return Math.min(this.height,this.width)},this.getCoordinates=function(t){var e,i=[],a=this.xoffset,s=this.yoffset,o=r(t)/this.height,n=r(t)/this.width;if(this.width>=this.height)for(e=0;e<t.length;e++)i.push([a,s,a+o,s+t[e]/o]),s+=t[e]/o;else for(e=0;e<t.length;e++)i.push([a,s,a+t[e]/n,s+n]),a+=t[e]/n;return i},this.cutArea=function(e){var i;if(this.width>=this.height){var a=e/this.height,s=this.width-a;i=new t(this.xoffset+a,this.yoffset,s,this.height)}else{var r=e/this.width,o=this.height-r;i=new t(this.xoffset,this.yoffset+r,this.width,o)}return i}}function e(e,a,s,o,n){o=void 0===o?0:o,n=void 0===n?0:n;var l=i(function(t,e){var i,a=[],s=e/r(t);for(i=0;i<t.length;i++)a[i]=t[i]*s;return a}(e,a*s),[],new t(o,n,a,s),[]);return function(t){var e,i,a=[];for(e=0;e<t.length;e++)for(i=0;i<t[e].length;i++)a.push(t[e][i]);return a}(l)}function i(t,e,s,o){var n,l,h;if(0!==t.length)return n=s.shortestEdge(),function(t,e,i){var s;if(0===t.length)return!0;(s=t.slice()).push(e);var r=a(t,i),o=a(s,i);return r>=o}(e,l=t[0],n)?(e.push(l),i(t.slice(1),e,s,o)):(h=s.cutArea(r(e),o),o.push(s.getCoordinates(e)),i(t,[],h,o)),o;o.push(s.getCoordinates(e))}function a(t,e){var i=Math.min.apply(Math,t),a=Math.max.apply(Math,t),s=r(t);return Math.max(Math.pow(e,2)*a/Math.pow(s,2),Math.pow(s,2)/(Math.pow(e,2)*i))}function s(t){return t&&t.constructor===Array}function r(t){var e,i=0;for(e=0;e<t.length;e++)i+=t[e];return i}function o(t){var e,i=0;if(s(t[0]))for(e=0;e<t.length;e++)i+=o(t[e]);else i=r(t);return i}return function t(i,a,r,n,l){n=void 0===n?0:n,l=void 0===l?0:l;var h,c,d=[],g=[];if(s(i[0])){for(c=0;c<i.length;c++)d[c]=o(i[c]);for(h=e(d,a,r,n,l),c=0;c<i.length;c++)g.push(t(i[c],h[c][2]-h[c][0],h[c][3]-h[c][1],h[c][0],h[c][1]))}else g=e(i,a,r,n,l);return g}}();var Xt,Et,Yt=function(){function t(e,i){a(this,t),this.ctx=e,this.w=e.w,this.strokeWidth=this.w.config.stroke.width,this.helpers=new At(e),this.dynamicAnim=this.w.config.chart.animations.dynamicAnimation,this.labels=[]}return r(t,[{key:"draw",value:function(t){var e=this,i=this.w,a=new m(this.ctx),s=new R(this.ctx),r=a.group({class:"apexcharts-treemap"});if(i.globals.noData)return r;var o=[];return t.forEach((function(t){var e=t.map((function(t){return Math.abs(t)}));o.push(e)})),this.negRange=this.helpers.checkColorRange(),i.config.series.forEach((function(t,i){t.data.forEach((function(t){Array.isArray(e.labels[i])||(e.labels[i]=[]),e.labels[i].push(t.x)}))})),window.TreemapSquared.generate(o,i.globals.gridWidth,i.globals.gridHeight).forEach((function(o,n){var l=a.group({class:"apexcharts-series apexcharts-treemap-series",seriesName:x.escapeString(i.globals.seriesNames[n]),rel:n+1,"data:realIndex":n});if(i.config.chart.dropShadow.enabled){var h=i.config.chart.dropShadow;new v(e.ctx).dropShadow(r,h,n)}var c=a.group({class:"apexcharts-data-labels"});o.forEach((function(r,o){var h=r[0],c=r[1],d=r[2],g=r[3],u=a.drawRect(h,c,d-h,g-c,0,"#fff",1,e.strokeWidth,i.config.plotOptions.treemap.useFillColorAsStroke?p:i.globals.stroke.colors[n]);u.attr({cx:h,cy:c,index:n,i:n,j:o,width:d-h,height:g-c});var f=e.helpers.getShadeColor(i.config.chart.type,n,o,e.negRange),p=f.color;void 0!==i.config.series[n].data[o]&&i.config.series[n].data[o].fillColor&&(p=i.config.series[n].data[o].fillColor);var x=s.fillPath({color:p,seriesNumber:n,dataPointIndex:o});u.node.classList.add("apexcharts-treemap-rect"),u.attr({fill:x}),e.helpers.addListeners(u);var b={x:h+(d-h)/2,y:c+(g-c)/2,width:0,height:0},v={x:h,y:c,width:d-h,height:g-c};if(i.config.chart.animations.enabled&&!i.globals.dataChanged){var m=1;i.globals.resized||(m=i.config.chart.animations.speed),e.animateTreemap(u,b,v,m)}if(i.globals.dataChanged){var y=1;e.dynamicAnim.enabled&&i.globals.shouldAnimate&&(y=e.dynamicAnim.speed,i.globals.previousPaths[n]&&i.globals.previousPaths[n][o]&&i.globals.previousPaths[n][o].rect&&(b=i.globals.previousPaths[n][o].rect),e.animateTreemap(u,b,v,y))}var w=e.getFontSize(r),k=i.config.dataLabels.formatter(e.labels[n][o],{value:i.globals.series[n][o],seriesIndex:n,dataPointIndex:o,w:i}),A=e.helpers.calculateDataLabels({text:k,x:(h+d)/2,y:(c+g)/2+e.strokeWidth/2+w/3,i:n,j:o,colorProps:f,fontSize:w,series:t});i.config.dataLabels.enabled&&A&&e.rotateToFitLabel(A,w,k,h,c,d,g),l.add(u),null!==A&&l.add(A)})),l.add(c),r.add(l)})),r}},{key:"getFontSize",value:function(t){var e=this.w;var i,a,s,r,o=function t(e){var i,a=0;if(Array.isArray(e[0]))for(i=0;i<e.length;i++)a+=t(e[i]);else for(i=0;i<e.length;i++)a+=e[i].length;return a}(this.labels)/function t(e){var i,a=0;if(Array.isArray(e[0]))for(i=0;i<e.length;i++)a+=t(e[i]);else for(i=0;i<e.length;i++)a+=1;return a}(this.labels);return i=t[2]-t[0],a=t[3]-t[1],s=i*a,r=Math.pow(s,.5),Math.min(r/o,parseInt(e.config.dataLabels.style.fontSize,10))}},{key:"rotateToFitLabel",value:function(t,e,i,a,s,r,o){var n=new m(this.ctx),l=n.getTextRects(i,e);if(l.width+this.w.config.stroke.width+5>r-a&&l.width<=o-s){var h=n.rotateAroundCenter(t.node);t.node.setAttribute("transform","rotate(-90 ".concat(h.x," ").concat(h.y,")"))}}},{key:"animateTreemap",value:function(t,e,i,a){var s=new b(this.ctx);s.animateRect(t,{x:e.x,y:e.y,width:e.width,height:e.height},{x:i.x,y:i.y,width:i.width,height:i.height},a,(function(){s.animationCompleted(t)}))}}]),t}(),Ft=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w,this.timeScaleArray=[],this.utc=this.w.config.xaxis.labels.datetimeUTC}return r(t,[{key:"calculateTimeScaleTicks",value:function(t,i){var a=this,s=this.w;if(s.globals.allSeriesCollapsed)return s.globals.labels=[],s.globals.timescaleLabels=[],[];var r=new T(this.ctx),o=(i-t)/864e5;this.determineInterval(o),s.globals.disableZoomIn=!1,s.globals.disableZoomOut=!1,o<.00011574074074074075?s.globals.disableZoomIn=!0:o>5e4&&(s.globals.disableZoomOut=!0);var n=r.getTimeUnitsfromTimestamp(t,i,this.utc),l=s.globals.gridWidth/o,h=l/24,c=h/60,d=c/60,g=Math.floor(24*o),u=Math.floor(1440*o),f=Math.floor(86400*o),p=Math.floor(o),x=Math.floor(o/30),b=Math.floor(o/365),v={minMillisecond:n.minMillisecond,minSecond:n.minSecond,minMinute:n.minMinute,minHour:n.minHour,minDate:n.minDate,minMonth:n.minMonth,minYear:n.minYear},m={firstVal:v,currentMillisecond:v.minMillisecond,currentSecond:v.minSecond,currentMinute:v.minMinute,currentHour:v.minHour,currentMonthDate:v.minDate,currentDate:v.minDate,currentMonth:v.minMonth,currentYear:v.minYear,daysWidthOnXAxis:l,hoursWidthOnXAxis:h,minutesWidthOnXAxis:c,secondsWidthOnXAxis:d,numberOfSeconds:f,numberOfMinutes:u,numberOfHours:g,numberOfDays:p,numberOfMonths:x,numberOfYears:b};switch(this.tickInterval){case"years":this.generateYearScale(m);break;case"months":case"half_year":this.generateMonthScale(m);break;case"months_days":case"months_fortnight":case"days":case"week_days":this.generateDayScale(m);break;case"hours":this.generateHourScale(m);break;case"minutes_fives":case"minutes":this.generateMinuteScale(m);break;case"seconds_tens":case"seconds_fives":case"seconds":this.generateSecondScale(m)}var y=this.timeScaleArray.map((function(t){var i={position:t.position,unit:t.unit,year:t.year,day:t.day?t.day:1,hour:t.hour?t.hour:0,month:t.month+1};return"month"===t.unit?e(e({},i),{},{day:1,value:t.value+1}):"day"===t.unit||"hour"===t.unit?e(e({},i),{},{value:t.value}):"minute"===t.unit?e(e({},i),{},{value:t.value,minute:t.value}):"second"===t.unit?e(e({},i),{},{value:t.value,minute:t.minute,second:t.second}):t}));return y.filter((function(t){var e=1,i=Math.ceil(s.globals.gridWidth/120),r=t.value;void 0!==s.config.xaxis.tickAmount&&(i=s.config.xaxis.tickAmount),y.length>i&&(e=Math.floor(y.length/i));var o=!1,n=!1;switch(a.tickInterval){case"years":"year"===t.unit&&(o=!0);break;case"half_year":e=7,"year"===t.unit&&(o=!0);break;case"months":e=1,"year"===t.unit&&(o=!0);break;case"months_fortnight":e=15,"year"!==t.unit&&"month"!==t.unit||(o=!0),30===r&&(n=!0);break;case"months_days":e=10,"month"===t.unit&&(o=!0),30===r&&(n=!0);break;case"week_days":e=8,"month"===t.unit&&(o=!0);break;case"days":e=1,"month"===t.unit&&(o=!0);break;case"hours":"day"===t.unit&&(o=!0);break;case"minutes_fives":case"seconds_fives":r%5!=0&&(n=!0);break;case"seconds_tens":r%10!=0&&(n=!0)}if("hours"===a.tickInterval||"minutes_fives"===a.tickInterval||"seconds_tens"===a.tickInterval||"seconds_fives"===a.tickInterval){if(!n)return!0}else if((r%e==0||o)&&!n)return!0}))}},{key:"recalcDimensionsBasedOnFormat",value:function(t,e){var i=this.w,a=this.formatDates(t),s=this.removeOverlappingTS(a);i.globals.timescaleLabels=s.slice(),new ot(this.ctx).plotCoords()}},{key:"determineInterval",value:function(t){var e=24*t,i=60*e;switch(!0){case t/365>5:this.tickInterval="years";break;case t>800:this.tickInterval="half_year";break;case t>180:this.tickInterval="months";break;case t>90:this.tickInterval="months_fortnight";break;case t>60:this.tickInterval="months_days";break;case t>30:this.tickInterval="week_days";break;case t>2:this.tickInterval="days";break;case e>2.4:this.tickInterval="hours";break;case i>15:this.tickInterval="minutes_fives";break;case i>5:this.tickInterval="minutes";break;case i>1:this.tickInterval="seconds_tens";break;case 60*i>20:this.tickInterval="seconds_fives";break;default:this.tickInterval="seconds"}}},{key:"generateYearScale",value:function(t){var e=t.firstVal,i=t.currentMonth,a=t.currentYear,s=t.daysWidthOnXAxis,r=t.numberOfYears,o=e.minYear,n=0,l=new T(this.ctx),h="year";if(e.minDate>1||e.minMonth>0){var c=l.determineRemainingDaysOfYear(e.minYear,e.minMonth,e.minDate);n=(l.determineDaysOfYear(e.minYear)-c+1)*s,o=e.minYear+1,this.timeScaleArray.push({position:n,value:o,unit:h,year:o,month:x.monthMod(i+1)})}else 1===e.minDate&&0===e.minMonth&&this.timeScaleArray.push({position:n,value:o,unit:h,year:a,month:x.monthMod(i+1)});for(var d=o,g=n,u=0;u<r;u++)d++,g=l.determineDaysOfYear(d-1)*s+g,this.timeScaleArray.push({position:g,value:d,unit:h,year:d,month:1})}},{key:"generateMonthScale",value:function(t){var e=t.firstVal,i=t.currentMonthDate,a=t.currentMonth,s=t.currentYear,r=t.daysWidthOnXAxis,o=t.numberOfMonths,n=a,l=0,h=new T(this.ctx),c="month",d=0;if(e.minDate>1){l=(h.determineDaysOfMonths(a+1,e.minYear)-i+1)*r,n=x.monthMod(a+1);var g=s+d,u=x.monthMod(n),f=n;0===n&&(c="year",f=g,u=1,g+=d+=1),this.timeScaleArray.push({position:l,value:f,unit:c,year:g,month:u})}else this.timeScaleArray.push({position:l,value:n,unit:c,year:s,month:x.monthMod(a)});for(var p=n+1,b=l,v=0,m=1;v<o;v++,m++){0===(p=x.monthMod(p))?(c="year",d+=1):c="month";var y=this._getYear(s,p,d);b=h.determineDaysOfMonths(p,y)*r+b;var w=0===p?y:p;this.timeScaleArray.push({position:b,value:w,unit:c,year:y,month:0===p?1:p}),p++}}},{key:"generateDayScale",value:function(t){var e=t.firstVal,i=t.currentMonth,a=t.currentYear,s=t.hoursWidthOnXAxis,r=t.numberOfDays,o=new T(this.ctx),n="day",l=e.minDate+1,h=l,c=function(t,e,i){return t>o.determineDaysOfMonths(e+1,i)?(h=1,n="month",g=e+=1,e):e},d=(24-e.minHour)*s,g=l,u=c(h,i,a);0===e.minHour&&1===e.minDate?(d=0,g=x.monthMod(e.minMonth),n="month",h=e.minDate,r++):1!==e.minDate&&0===e.minHour&&0===e.minMinute&&(d=0,l=e.minDate,g=l,u=c(h=l,i,a)),this.timeScaleArray.push({position:d,value:g,unit:n,year:this._getYear(a,u,0),month:x.monthMod(u),day:h});for(var f=d,p=0;p<r;p++){n="day",u=c(h+=1,u,this._getYear(a,u,0));var b=this._getYear(a,u,0);f=24*s+f;var v=1===h?x.monthMod(u):h;this.timeScaleArray.push({position:f,value:v,unit:n,year:b,month:x.monthMod(u),day:v})}}},{key:"generateHourScale",value:function(t){var e=t.firstVal,i=t.currentDate,a=t.currentMonth,s=t.currentYear,r=t.minutesWidthOnXAxis,o=t.numberOfHours,n=new T(this.ctx),l="hour",h=function(t,e){return t>n.determineDaysOfMonths(e+1,s)&&(p=1,e+=1),{month:e,date:p}},c=function(t,e){return t>n.determineDaysOfMonths(e+1,s)?e+=1:e},d=60-(e.minMinute+e.minSecond/60),g=d*r,u=e.minHour+1,f=u+1;60===d&&(g=0,f=(u=e.minHour)+1);var p=i,b=c(p,a);this.timeScaleArray.push({position:g,value:u,unit:l,day:p,hour:f,year:s,month:x.monthMod(b)});for(var v=g,m=0;m<o;m++){if(l="hour",f>=24)f=0,l="day",b=h(p+=1,b).month,b=c(p,b);var y=this._getYear(s,b,0);v=60*r+v;var w=0===f?p:f;this.timeScaleArray.push({position:v,value:w,unit:l,hour:f,day:p,year:y,month:x.monthMod(b)}),f++}}},{key:"generateMinuteScale",value:function(t){for(var e=t.currentMillisecond,i=t.currentSecond,a=t.currentMinute,s=t.currentHour,r=t.currentDate,o=t.currentMonth,n=t.currentYear,l=t.minutesWidthOnXAxis,h=t.secondsWidthOnXAxis,c=t.numberOfMinutes,d=a+1,g=r,u=o,f=n,p=s,b=(60-i-e/1e3)*h,v=0;v<c;v++)d>=60&&(d=0,24===(p+=1)&&(p=0)),this.timeScaleArray.push({position:b,value:d,unit:"minute",hour:p,minute:d,day:g,year:this._getYear(f,u,0),month:x.monthMod(u)}),b+=l,d++}},{key:"generateSecondScale",value:function(t){for(var e=t.currentMillisecond,i=t.currentSecond,a=t.currentMinute,s=t.currentHour,r=t.currentDate,o=t.currentMonth,n=t.currentYear,l=t.secondsWidthOnXAxis,h=t.numberOfSeconds,c=i+1,d=a,g=r,u=o,f=n,p=s,b=(1e3-e)/1e3*l,v=0;v<h;v++)c>=60&&(c=0,++d>=60&&(d=0,24===++p&&(p=0))),this.timeScaleArray.push({position:b,value:c,unit:"second",hour:p,minute:d,second:c,day:g,year:this._getYear(f,u,0),month:x.monthMod(u)}),b+=l,c++}},{key:"createRawDateString",value:function(t,e){var i=t.year;return 0===t.month&&(t.month=1),i+="-"+("0"+t.month.toString()).slice(-2),"day"===t.unit?i+="day"===t.unit?"-"+("0"+e).slice(-2):"-01":i+="-"+("0"+(t.day?t.day:"1")).slice(-2),"hour"===t.unit?i+="hour"===t.unit?"T"+("0"+e).slice(-2):"T00":i+="T"+("0"+(t.hour?t.hour:"0")).slice(-2),"minute"===t.unit?i+=":"+("0"+e).slice(-2):i+=":"+(t.minute?("0"+t.minute).slice(-2):"00"),"second"===t.unit?i+=":"+("0"+e).slice(-2):i+=":00",this.utc&&(i+=".000Z"),i}},{key:"formatDates",value:function(t){var e=this,i=this.w;return t.map((function(t){var a=t.value.toString(),s=new T(e.ctx),r=e.createRawDateString(t,a),o=s.getDate(s.parseDate(r));if(e.utc||(o=s.getDate(s.parseDateWithTimezone(r))),void 0===i.config.xaxis.labels.format){var n="dd MMM",l=i.config.xaxis.labels.datetimeFormatter;"year"===t.unit&&(n=l.year),"month"===t.unit&&(n=l.month),"day"===t.unit&&(n=l.day),"hour"===t.unit&&(n=l.hour),"minute"===t.unit&&(n=l.minute),"second"===t.unit&&(n=l.second),a=s.formatDate(o,n)}else a=s.formatDate(o,i.config.xaxis.labels.format);return{dateString:r,position:t.position,value:a,unit:t.unit,year:t.year,month:t.month}}))}},{key:"removeOverlappingTS",value:function(t){var e,i=this,a=new m(this.ctx),s=!1;t.length>0&&t[0].value&&t.every((function(e){return e.value.length===t[0].value.length}))&&(s=!0,e=a.getTextRects(t[0].value).width);var r=0,o=t.map((function(o,n){if(n>0&&i.w.config.xaxis.labels.hideOverlappingLabels){var l=s?e:a.getTextRects(t[r].value).width,h=t[r].position;return o.position>h+l+10?(r=n,o):null}return o}));return o=o.filter((function(t){return null!==t}))}},{key:"_getYear",value:function(t,e,i){return t+Math.floor(e/12)+i}}]),t}(),Rt=function(){function t(e,i){a(this,t),this.ctx=i,this.w=i.w,this.el=e}return r(t,[{key:"setupElements",value:function(){var t=this.w.globals,e=this.w.config,i=e.chart.type;t.axisCharts=["line","area","bar","rangeBar","rangeArea","candlestick","boxPlot","scatter","bubble","radar","heatmap","treemap"].indexOf(i)>-1,t.xyCharts=["line","area","bar","rangeBar","rangeArea","candlestick","boxPlot","scatter","bubble"].indexOf(i)>-1,t.isBarHorizontal=("bar"===e.chart.type||"rangeBar"===e.chart.type||"boxPlot"===e.chart.type)&&e.plotOptions.bar.horizontal,t.chartClass=".apexcharts"+t.chartID,t.dom.baseEl=this.el,t.dom.elWrap=document.createElement("div"),m.setAttrs(t.dom.elWrap,{id:t.chartClass.substring(1),class:"apexcharts-canvas "+t.chartClass.substring(1)}),this.el.appendChild(t.dom.elWrap),t.dom.Paper=new window.SVG.Doc(t.dom.elWrap),t.dom.Paper.attr({class:"apexcharts-svg","xmlns:data":"ApexChartsNS",transform:"translate(".concat(e.chart.offsetX,", ").concat(e.chart.offsetY,")")}),t.dom.Paper.node.style.background=e.chart.background,this.setSVGDimensions(),t.dom.elGraphical=t.dom.Paper.group().attr({class:"apexcharts-inner apexcharts-graphical"}),t.dom.elAnnotations=t.dom.Paper.group().attr({class:"apexcharts-annotations"}),t.dom.elDefs=t.dom.Paper.defs(),t.dom.elLegendWrap=document.createElement("div"),t.dom.elLegendWrap.classList.add("apexcharts-legend"),t.dom.elWrap.appendChild(t.dom.elLegendWrap),t.dom.Paper.add(t.dom.elGraphical),t.dom.elGraphical.add(t.dom.elDefs)}},{key:"plotChartType",value:function(t,e){var i=this.w,a=i.config,s=i.globals,r={series:[],i:[]},o={series:[],i:[]},n={series:[],i:[]},l={series:[],i:[]},h={series:[],i:[]},c={series:[],i:[]},d={series:[],i:[]},g={series:[],i:[]},u={series:[],seriesRangeEnd:[],i:[]};s.series.map((function(e,f){var p=0;void 0!==t[f].type?("column"===t[f].type||"bar"===t[f].type?(s.series.length>1&&a.plotOptions.bar.horizontal&&console.warn("Horizontal bars are not supported in a mixed/combo chart. Please turn off `plotOptions.bar.horizontal`"),h.series.push(e),h.i.push(f),p++,i.globals.columnSeries=h.series):"area"===t[f].type?(o.series.push(e),o.i.push(f),p++):"line"===t[f].type?(r.series.push(e),r.i.push(f),p++):"scatter"===t[f].type?(n.series.push(e),n.i.push(f)):"bubble"===t[f].type?(l.series.push(e),l.i.push(f),p++):"candlestick"===t[f].type?(c.series.push(e),c.i.push(f),p++):"boxPlot"===t[f].type?(d.series.push(e),d.i.push(f),p++):"rangeBar"===t[f].type?(g.series.push(e),g.i.push(f),p++):"rangeArea"===t[f].type?(u.series.push(s.seriesRangeStart[f]),u.seriesRangeEnd.push(s.seriesRangeEnd[f]),u.i.push(f),p++):console.warn("You have specified an unrecognized chart type. Available types for this property are line/area/column/bar/scatter/bubble"),p>1&&(s.comboCharts=!0)):(r.series.push(e),r.i.push(f))}));var f=new zt(this.ctx,e),p=new kt(this.ctx,e);this.ctx.pie=new Lt(this.ctx);var x=new Tt(this.ctx);this.ctx.rangeBar=new Mt(this.ctx,e);var b=new Pt(this.ctx),v=[];if(s.comboCharts){if(o.series.length>0&&v.push(f.draw(o.series,"area",o.i)),h.series.length>0)if(i.config.chart.stacked){var m=new wt(this.ctx,e);v.push(m.draw(h.series,h.i))}else this.ctx.bar=new yt(this.ctx,e),v.push(this.ctx.bar.draw(h.series,h.i));if(u.series.length>0&&v.push(f.draw(u.series,"rangeArea",u.i,u.seriesRangeEnd)),r.series.length>0&&v.push(f.draw(r.series,"line",r.i)),c.series.length>0&&v.push(p.draw(c.series,c.i)),d.series.length>0&&v.push(p.draw(d.series,d.i)),g.series.length>0&&v.push(this.ctx.rangeBar.draw(g.series,g.i)),n.series.length>0){var y=new zt(this.ctx,e,!0);v.push(y.draw(n.series,"scatter",n.i))}if(l.series.length>0){var w=new zt(this.ctx,e,!0);v.push(w.draw(l.series,"bubble",l.i))}}else switch(a.chart.type){case"line":v=f.draw(s.series,"line");break;case"area":v=f.draw(s.series,"area");break;case"bar":if(a.chart.stacked)v=new wt(this.ctx,e).draw(s.series);else this.ctx.bar=new yt(this.ctx,e),v=this.ctx.bar.draw(s.series);break;case"candlestick":v=new kt(this.ctx,e).draw(s.series);break;case"boxPlot":v=new kt(this.ctx,e).draw(s.series);break;case"rangeBar":v=this.ctx.rangeBar.draw(s.series);break;case"rangeArea":v=f.draw(s.seriesRangeStart,"rangeArea",void 0,s.seriesRangeEnd);break;case"heatmap":v=new St(this.ctx,e).draw(s.series);break;case"treemap":v=new Yt(this.ctx,e).draw(s.series);break;case"pie":case"donut":case"polarArea":v=this.ctx.pie.draw(s.series);break;case"radialBar":v=x.draw(s.series);break;case"radar":v=b.draw(s.series);break;default:v=f.draw(s.series)}return v}},{key:"setSVGDimensions",value:function(){var t=this.w.globals,e=this.w.config;t.svgWidth=e.chart.width,t.svgHeight=e.chart.height;var i=x.getDimensions(this.el),a=e.chart.width.toString().split(/[0-9]+/g).pop();"%"===a?x.isNumber(i[0])&&(0===i[0].width&&(i=x.getDimensions(this.el.parentNode)),t.svgWidth=i[0]*parseInt(e.chart.width,10)/100):"px"!==a&&""!==a||(t.svgWidth=parseInt(e.chart.width,10));var s=e.chart.height.toString().split(/[0-9]+/g).pop();if("auto"!==t.svgHeight&&""!==t.svgHeight)if("%"===s){var r=x.getDimensions(this.el.parentNode);t.svgHeight=r[1]*parseInt(e.chart.height,10)/100}else t.svgHeight=parseInt(e.chart.height,10);else t.axisCharts?t.svgHeight=t.svgWidth/1.61:t.svgHeight=t.svgWidth/1.2;if(t.svgWidth<0&&(t.svgWidth=0),t.svgHeight<0&&(t.svgHeight=0),m.setAttrs(t.dom.Paper.node,{width:t.svgWidth,height:t.svgHeight}),"%"!==s){var o=e.chart.sparkline.enabled?0:t.axisCharts?e.chart.parentHeightOffset:0;t.dom.Paper.node.parentNode.parentNode.style.minHeight=t.svgHeight+o+"px"}t.dom.elWrap.style.width=t.svgWidth+"px",t.dom.elWrap.style.height=t.svgHeight+"px"}},{key:"shiftGraphPosition",value:function(){var t=this.w.globals,e=t.translateY,i={transform:"translate("+t.translateX+", "+e+")"};m.setAttrs(t.dom.elGraphical.node,i)}},{key:"resizeNonAxisCharts",value:function(){var t=this.w,e=t.globals,i=0,a=t.config.chart.sparkline.enabled?1:15;a+=t.config.grid.padding.bottom,"top"!==t.config.legend.position&&"bottom"!==t.config.legend.position||!t.config.legend.show||t.config.legend.floating||(i=new lt(this.ctx).legendHelpers.getLegendBBox().clwh+10);var s=t.globals.dom.baseEl.querySelector(".apexcharts-radialbar, .apexcharts-pie"),r=2.05*t.globals.radialSize;if(s&&!t.config.chart.sparkline.enabled&&0!==t.config.plotOptions.radialBar.startAngle){var o=x.getBoundingClientRect(s);r=o.bottom;var n=o.bottom-o.top;r=Math.max(2.05*t.globals.radialSize,n)}var l=r+e.translateY+i+a;e.dom.elLegendForeign&&e.dom.elLegendForeign.setAttribute("height",l),t.config.chart.height&&String(t.config.chart.height).indexOf("%")>0||(e.dom.elWrap.style.height=l+"px",m.setAttrs(e.dom.Paper.node,{height:l}),e.dom.Paper.node.parentNode.parentNode.style.minHeight=l+"px")}},{key:"coreCalculations",value:function(){new U(this.ctx).init()}},{key:"resetGlobals",value:function(){var t=this,e=function(){return t.w.config.series.map((function(t){return[]}))},i=new Y,a=this.w.globals;i.initGlobalVars(a),a.seriesXvalues=e(),a.seriesYvalues=e()}},{key:"isMultipleY",value:function(){if(this.w.config.yaxis.constructor===Array&&this.w.config.yaxis.length>1)return this.w.globals.isMultipleYAxis=!0,!0}},{key:"xySettings",value:function(){var t=null,e=this.w;if(e.globals.axisCharts){if("back"===e.config.xaxis.crosshairs.position)new Q(this.ctx).drawXCrosshairs();if("back"===e.config.yaxis[0].crosshairs.position)new Q(this.ctx).drawYCrosshairs();if("datetime"===e.config.xaxis.type&&void 0===e.config.xaxis.labels.formatter){this.ctx.timeScale=new Ft(this.ctx);var i=[];isFinite(e.globals.minX)&&isFinite(e.globals.maxX)&&!e.globals.isBarHorizontal?i=this.ctx.timeScale.calculateTimeScaleTicks(e.globals.minX,e.globals.maxX):e.globals.isBarHorizontal&&(i=this.ctx.timeScale.calculateTimeScaleTicks(e.globals.minY,e.globals.maxY)),this.ctx.timeScale.recalcDimensionsBasedOnFormat(i)}t=new y(this.ctx).getCalculatedRatios()}return t}},{key:"updateSourceChart",value:function(t){this.ctx.w.globals.selection=void 0,this.ctx.updateHelpers._updateOptions({chart:{selection:{xaxis:{min:t.w.globals.minX,max:t.w.globals.maxX}}}},!1,!1)}},{key:"setupBrushHandler",value:function(){var t=this,i=this.w;if(i.config.chart.brush.enabled&&"function"!=typeof i.config.chart.events.selection){var a=i.config.chart.brush.targets||[i.config.chart.brush.target];a.forEach((function(e){var i=ApexCharts.getChartByID(e);i.w.globals.brushSource=t.ctx,"function"!=typeof i.w.config.chart.events.zoomed&&(i.w.config.chart.events.zoomed=function(){t.updateSourceChart(i)}),"function"!=typeof i.w.config.chart.events.scrolled&&(i.w.config.chart.events.scrolled=function(){t.updateSourceChart(i)})})),i.config.chart.events.selection=function(t,s){a.forEach((function(t){var a=ApexCharts.getChartByID(t),r=x.clone(i.config.yaxis);if(i.config.chart.brush.autoScaleYaxis&&1===a.w.globals.series.length){var o=new _(a);r=o.autoScaleY(a,r,s)}var n=a.w.config.yaxis.reduce((function(t,i,s){return[].concat(u(t),[e(e({},a.w.config.yaxis[s]),{},{min:r[0].min,max:r[0].max})])}),[]);a.ctx.updateHelpers._updateOptions({xaxis:{min:s.xaxis.min,max:s.xaxis.max},yaxis:n},!1,!1,!1,!1)}))}}}}]),t}(),Dt=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"_updateOptions",value:function(t){var e=this,a=arguments.length>1&&void 0!==arguments[1]&&arguments[1],s=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],r=!(arguments.length>3&&void 0!==arguments[3])||arguments[3],o=arguments.length>4&&void 0!==arguments[4]&&arguments[4];return new Promise((function(n){var l=[e.ctx];r&&(l=e.ctx.getSyncedCharts()),e.ctx.w.globals.isExecCalled&&(l=[e.ctx],e.ctx.w.globals.isExecCalled=!1),l.forEach((function(r,h){var c=r.w;if(c.globals.shouldAnimate=s,a||(c.globals.resized=!0,c.globals.dataChanged=!0,s&&r.series.getPreviousPaths()),t&&"object"===i(t)&&(r.config=new E(t),t=y.extendArrayProps(r.config,t,c),r.w.globals.chartID!==e.ctx.w.globals.chartID&&delete t.series,c.config=x.extend(c.config,t),o&&(c.globals.lastXAxis=t.xaxis?x.clone(t.xaxis):[],c.globals.lastYAxis=t.yaxis?x.clone(t.yaxis):[],c.globals.initialConfig=x.extend({},c.config),c.globals.initialSeries=x.clone(c.config.series),t.series))){for(var d=0;d<c.globals.collapsedSeriesIndices.length;d++){var g=c.config.series[c.globals.collapsedSeriesIndices[d]];c.globals.collapsedSeries[d].data=c.globals.axisCharts?g.data.slice():g}for(var u=0;u<c.globals.ancillaryCollapsedSeriesIndices.length;u++){var f=c.config.series[c.globals.ancillaryCollapsedSeriesIndices[u]];c.globals.ancillaryCollapsedSeries[u].data=c.globals.axisCharts?f.data.slice():f}r.series.emptyCollapsedSeries(c.config.series)}return r.update(t).then((function(){h===l.length-1&&n(r)}))}))}))}},{key:"_updateSeries",value:function(t,e){var i=this,a=arguments.length>2&&void 0!==arguments[2]&&arguments[2];return new Promise((function(s){var r,o=i.w;return o.globals.shouldAnimate=e,o.globals.dataChanged=!0,e&&i.ctx.series.getPreviousPaths(),o.globals.axisCharts?(0===(r=t.map((function(t,e){return i._extendSeries(t,e)}))).length&&(r=[{data:[]}]),o.config.series=r):o.config.series=t.slice(),a&&(o.globals.initialConfig.series=x.clone(o.config.series),o.globals.initialSeries=x.clone(o.config.series)),i.ctx.update().then((function(){s(i.ctx)}))}))}},{key:"_extendSeries",value:function(t,i){var a=this.w,s=a.config.series[i];return e(e({},a.config.series[i]),{},{name:t.name?t.name:s&&s.name,color:t.color?t.color:s&&s.color,type:t.type?t.type:s&&s.type,data:t.data?t.data:s&&s.data})}},{key:"toggleDataPointSelection",value:function(t,e){var i=this.w,a=null,s=".apexcharts-series[data\\:realIndex='".concat(t,"']");return i.globals.axisCharts?a=i.globals.dom.Paper.select("".concat(s," path[j='").concat(e,"'], ").concat(s," circle[j='").concat(e,"'], ").concat(s," rect[j='").concat(e,"']")).members[0]:void 0===e&&(a=i.globals.dom.Paper.select("".concat(s," path[j='").concat(t,"']")).members[0],"pie"!==i.config.chart.type&&"polarArea"!==i.config.chart.type&&"donut"!==i.config.chart.type||this.ctx.pie.pieClicked(t)),a?(new m(this.ctx).pathMouseDown(a,null),a.node?a.node:null):(console.warn("toggleDataPointSelection: Element not found"),null)}},{key:"forceXAxisUpdate",value:function(t){var e=this.w;if(["min","max"].forEach((function(i){void 0!==t.xaxis[i]&&(e.config.xaxis[i]=t.xaxis[i],e.globals.lastXAxis[i]=t.xaxis[i])})),t.xaxis.categories&&t.xaxis.categories.length&&(e.config.xaxis.categories=t.xaxis.categories),e.config.xaxis.convertedCatToNumeric){var i=new X(t);t=i.convertCatToNumericXaxis(t,this.ctx)}return t}},{key:"forceYAxisUpdate",value:function(t){return t.chart&&t.chart.stacked&&"100%"===t.chart.stackType&&(Array.isArray(t.yaxis)?t.yaxis.forEach((function(e,i){t.yaxis[i].min=0,t.yaxis[i].max=100})):(t.yaxis.min=0,t.yaxis.max=100)),t}},{key:"revertDefaultAxisMinMax",value:function(t){var e=this,i=this.w,a=i.globals.lastXAxis,s=i.globals.lastYAxis;t&&t.xaxis&&(a=t.xaxis),t&&t.yaxis&&(s=t.yaxis),i.config.xaxis.min=a.min,i.config.xaxis.max=a.max;var r=function(t){void 0!==s[t]&&(i.config.yaxis[t].min=s[t].min,i.config.yaxis[t].max=s[t].max)};i.config.yaxis.map((function(t,a){i.globals.zoomed||void 0!==s[a]?r(a):void 0!==e.ctx.opts.yaxis[a]&&(t.min=e.ctx.opts.yaxis[a].min,t.max=e.ctx.opts.yaxis[a].max)}))}}]),t}();Xt="undefined"!=typeof window?window:void 0,Et=function(t,e){var a=(void 0!==this?this:t).SVG=function(t){if(a.supported)return t=new a.Doc(t),a.parser.draw||a.prepare(),t};if(a.ns="http://www.w3.org/2000/svg",a.xmlns="http://www.w3.org/2000/xmlns/",a.xlink="http://www.w3.org/1999/xlink",a.svgjs="http://svgjs.dev",a.supported=!0,!a.supported)return!1;a.did=1e3,a.eid=function(t){return"Svgjs"+d(t)+a.did++},a.create=function(t){var i=e.createElementNS(this.ns,t);return i.setAttribute("id",this.eid(t)),i},a.extend=function(){var t,e;e=(t=[].slice.call(arguments)).pop();for(var i=t.length-1;i>=0;i--)if(t[i])for(var s in e)t[i].prototype[s]=e[s];a.Set&&a.Set.inherit&&a.Set.inherit()},a.invent=function(t){var e="function"==typeof t.create?t.create:function(){this.constructor.call(this,a.create(t.create))};return t.inherit&&(e.prototype=new t.inherit),t.extend&&a.extend(e,t.extend),t.construct&&a.extend(t.parent||a.Container,t.construct),e},a.adopt=function(e){return e?e.instance?e.instance:((i="svg"==e.nodeName?e.parentNode instanceof t.SVGElement?new a.Nested:new a.Doc:"linearGradient"==e.nodeName?new a.Gradient("linear"):"radialGradient"==e.nodeName?new a.Gradient("radial"):a[d(e.nodeName)]?new(a[d(e.nodeName)]):new a.Element(e)).type=e.nodeName,i.node=e,e.instance=i,i instanceof a.Doc&&i.namespace().defs(),i.setData(JSON.parse(e.getAttribute("svgjs:data"))||{}),i):null;var i},a.prepare=function(){var t=e.getElementsByTagName("body")[0],i=(t?new a.Doc(t):a.adopt(e.documentElement).nested()).size(2,0);a.parser={body:t||e.documentElement,draw:i.style("opacity:0;position:absolute;left:-100%;top:-100%;overflow:hidden").node,poly:i.polyline().node,path:i.path().node,native:a.create("svg")}},a.parser={native:a.create("svg")},e.addEventListener("DOMContentLoaded",(function(){a.parser.draw||a.prepare()}),!1),a.regex={numberAndUnit:/^([+-]?(\d+(\.\d*)?|\.\d+)(e[+-]?\d+)?)([a-z%]*)$/i,hex:/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i,rgb:/rgb\((\d+),(\d+),(\d+)\)/,reference:/#([a-z0-9\-_]+)/i,transforms:/\)\s*,?\s*/,whitespace:/\s/g,isHex:/^#[a-f0-9]{3,6}$/i,isRgb:/^rgb\(/,isCss:/[^:]+:[^;]+;?/,isBlank:/^(\s+)?$/,isNumber:/^[+-]?(\d+(\.\d*)?|\.\d+)(e[+-]?\d+)?$/i,isPercent:/^-?[\d\.]+%$/,isImage:/\.(jpg|jpeg|png|gif|svg)(\?[^=]+.*)?/i,delimiter:/[\s,]+/,hyphen:/([^e])\-/gi,pathLetters:/[MLHVCSQTAZ]/gi,isPathLetter:/[MLHVCSQTAZ]/i,numbersWithDots:/((\d?\.\d+(?:e[+-]?\d+)?)((?:\.\d+(?:e[+-]?\d+)?)+))+/gi,dots:/\./g},a.utils={map:function(t,e){for(var i=t.length,a=[],s=0;s<i;s++)a.push(e(t[s]));return a},filter:function(t,e){for(var i=t.length,a=[],s=0;s<i;s++)e(t[s])&&a.push(t[s]);return a},filterSVGElements:function(e){return this.filter(e,(function(e){return e instanceof t.SVGElement}))}},a.defaults={attrs:{"fill-opacity":1,"stroke-opacity":1,"stroke-width":0,"stroke-linejoin":"miter","stroke-linecap":"butt",fill:"#000000",stroke:"#000000",opacity:1,x:0,y:0,cx:0,cy:0,width:0,height:0,r:0,rx:0,ry:0,offset:0,"stop-opacity":1,"stop-color":"#000000","font-size":16,"font-family":"Helvetica, Arial, sans-serif","text-anchor":"start"}},a.Color=function(t){var e,s;this.r=0,this.g=0,this.b=0,t&&("string"==typeof t?a.regex.isRgb.test(t)?(e=a.regex.rgb.exec(t.replace(a.regex.whitespace,"")),this.r=parseInt(e[1]),this.g=parseInt(e[2]),this.b=parseInt(e[3])):a.regex.isHex.test(t)&&(e=a.regex.hex.exec(4==(s=t).length?["#",s.substring(1,2),s.substring(1,2),s.substring(2,3),s.substring(2,3),s.substring(3,4),s.substring(3,4)].join(""):s),this.r=parseInt(e[1],16),this.g=parseInt(e[2],16),this.b=parseInt(e[3],16)):"object"===i(t)&&(this.r=t.r,this.g=t.g,this.b=t.b))},a.extend(a.Color,{toString:function(){return this.toHex()},toHex:function(){return"#"+g(this.r)+g(this.g)+g(this.b)},toRgb:function(){return"rgb("+[this.r,this.g,this.b].join()+")"},brightness:function(){return this.r/255*.3+this.g/255*.59+this.b/255*.11},morph:function(t){return this.destination=new a.Color(t),this},at:function(t){return this.destination?(t=t<0?0:t>1?1:t,new a.Color({r:~~(this.r+(this.destination.r-this.r)*t),g:~~(this.g+(this.destination.g-this.g)*t),b:~~(this.b+(this.destination.b-this.b)*t)})):this}}),a.Color.test=function(t){return t+="",a.regex.isHex.test(t)||a.regex.isRgb.test(t)},a.Color.isRgb=function(t){return t&&"number"==typeof t.r&&"number"==typeof t.g&&"number"==typeof t.b},a.Color.isColor=function(t){return a.Color.isRgb(t)||a.Color.test(t)},a.Array=function(t,e){0==(t=(t||[]).valueOf()).length&&e&&(t=e.valueOf()),this.value=this.parse(t)},a.extend(a.Array,{toString:function(){return this.value.join(" ")},valueOf:function(){return this.value},parse:function(t){return t=t.valueOf(),Array.isArray(t)?t:this.split(t)}}),a.PointArray=function(t,e){a.Array.call(this,t,e||[[0,0]])},a.PointArray.prototype=new a.Array,a.PointArray.prototype.constructor=a.PointArray;for(var s={M:function(t,e,i){return e.x=i.x=t[0],e.y=i.y=t[1],["M",e.x,e.y]},L:function(t,e){return e.x=t[0],e.y=t[1],["L",t[0],t[1]]},H:function(t,e){return e.x=t[0],["H",t[0]]},V:function(t,e){return e.y=t[0],["V",t[0]]},C:function(t,e){return e.x=t[4],e.y=t[5],["C",t[0],t[1],t[2],t[3],t[4],t[5]]},Q:function(t,e){return e.x=t[2],e.y=t[3],["Q",t[0],t[1],t[2],t[3]]},Z:function(t,e,i){return e.x=i.x,e.y=i.y,["Z"]}},r="mlhvqtcsaz".split(""),o=0,n=r.length;o<n;++o)s[r[o]]=function(t){return function(e,i,a){if("H"==t)e[0]=e[0]+i.x;else if("V"==t)e[0]=e[0]+i.y;else if("A"==t)e[5]=e[5]+i.x,e[6]=e[6]+i.y;else for(var r=0,o=e.length;r<o;++r)e[r]=e[r]+(r%2?i.y:i.x);if(s&&"function"==typeof s[t])return s[t](e,i,a)}}(r[o].toUpperCase());a.PathArray=function(t,e){a.Array.call(this,t,e||[["M",0,0]])},a.PathArray.prototype=new a.Array,a.PathArray.prototype.constructor=a.PathArray,a.extend(a.PathArray,{toString:function(){return function(t){for(var e=0,i=t.length,a="";e<i;e++)a+=t[e][0],null!=t[e][1]&&(a+=t[e][1],null!=t[e][2]&&(a+=" ",a+=t[e][2],null!=t[e][3]&&(a+=" ",a+=t[e][3],a+=" ",a+=t[e][4],null!=t[e][5]&&(a+=" ",a+=t[e][5],a+=" ",a+=t[e][6],null!=t[e][7]&&(a+=" ",a+=t[e][7])))));return a+" "}(this.value)},move:function(t,e){var i=this.bbox();return i.x,i.y,this},at:function(t){if(!this.destination)return this;for(var e=this.value,i=this.destination.value,s=[],r=new a.PathArray,o=0,n=e.length;o<n;o++){s[o]=[e[o][0]];for(var l=1,h=e[o].length;l<h;l++)s[o][l]=e[o][l]+(i[o][l]-e[o][l])*t;"A"===s[o][0]&&(s[o][4]=+(0!=s[o][4]),s[o][5]=+(0!=s[o][5]))}return r.value=s,r},parse:function(t){if(t instanceof a.PathArray)return t.valueOf();var e,i={M:2,L:2,H:1,V:1,C:6,S:4,Q:4,T:2,A:7,Z:0};t="string"==typeof t?t.replace(a.regex.numbersWithDots,h).replace(a.regex.pathLetters," $& ").replace(a.regex.hyphen,"$1 -").trim().split(a.regex.delimiter):t.reduce((function(t,e){return[].concat.call(t,e)}),[]);var r=[],o=new a.Point,n=new a.Point,l=0,c=t.length;do{a.regex.isPathLetter.test(t[l])?(e=t[l],++l):"M"==e?e="L":"m"==e&&(e="l"),r.push(s[e].call(null,t.slice(l,l+=i[e.toUpperCase()]).map(parseFloat),o,n))}while(c>l);return r},bbox:function(){return a.parser.draw||a.prepare(),a.parser.path.setAttribute("d",this.toString()),a.parser.path.getBBox()}}),a.Number=a.invent({create:function(t,e){this.value=0,this.unit=e||"","number"==typeof t?this.value=isNaN(t)?0:isFinite(t)?t:t<0?-34e37:34e37:"string"==typeof t?(e=t.match(a.regex.numberAndUnit))&&(this.value=parseFloat(e[1]),"%"==e[5]?this.value/=100:"s"==e[5]&&(this.value*=1e3),this.unit=e[5]):t instanceof a.Number&&(this.value=t.valueOf(),this.unit=t.unit)},extend:{toString:function(){return("%"==this.unit?~~(1e8*this.value)/1e6:"s"==this.unit?this.value/1e3:this.value)+this.unit},toJSON:function(){return this.toString()},valueOf:function(){return this.value},plus:function(t){return t=new a.Number(t),new a.Number(this+t,this.unit||t.unit)},minus:function(t){return t=new a.Number(t),new a.Number(this-t,this.unit||t.unit)},times:function(t){return t=new a.Number(t),new a.Number(this*t,this.unit||t.unit)},divide:function(t){return t=new a.Number(t),new a.Number(this/t,this.unit||t.unit)},to:function(t){var e=new a.Number(this);return"string"==typeof t&&(e.unit=t),e},morph:function(t){return this.destination=new a.Number(t),t.relative&&(this.destination.value+=this.value),this},at:function(t){return this.destination?new a.Number(this.destination).minus(this).times(t).plus(this):this}}}),a.Element=a.invent({create:function(t){this._stroke=a.defaults.attrs.stroke,this._event=null,this.dom={},(this.node=t)&&(this.type=t.nodeName,this.node.instance=this,this._stroke=t.getAttribute("stroke")||this._stroke)},extend:{x:function(t){return this.attr("x",t)},y:function(t){return this.attr("y",t)},cx:function(t){return null==t?this.x()+this.width()/2:this.x(t-this.width()/2)},cy:function(t){return null==t?this.y()+this.height()/2:this.y(t-this.height()/2)},move:function(t,e){return this.x(t).y(e)},center:function(t,e){return this.cx(t).cy(e)},width:function(t){return this.attr("width",t)},height:function(t){return this.attr("height",t)},size:function(t,e){var i=u(this,t,e);return this.width(new a.Number(i.width)).height(new a.Number(i.height))},clone:function(t){this.writeDataToDom();var e=x(this.node.cloneNode(!0));return t?t.add(e):this.after(e),e},remove:function(){return this.parent()&&this.parent().removeElement(this),this},replace:function(t){return this.after(t).remove(),t},addTo:function(t){return t.put(this)},putIn:function(t){return t.add(this)},id:function(t){return this.attr("id",t)},show:function(){return this.style("display","")},hide:function(){return this.style("display","none")},visible:function(){return"none"!=this.style("display")},toString:function(){return this.attr("id")},classes:function(){var t=this.attr("class");return null==t?[]:t.trim().split(a.regex.delimiter)},hasClass:function(t){return-1!=this.classes().indexOf(t)},addClass:function(t){if(!this.hasClass(t)){var e=this.classes();e.push(t),this.attr("class",e.join(" "))}return this},removeClass:function(t){return this.hasClass(t)&&this.attr("class",this.classes().filter((function(e){return e!=t})).join(" ")),this},toggleClass:function(t){return this.hasClass(t)?this.removeClass(t):this.addClass(t)},reference:function(t){return a.get(this.attr(t))},parent:function(e){var i=this;if(!i.node.parentNode)return null;if(i=a.adopt(i.node.parentNode),!e)return i;for(;i&&i.node instanceof t.SVGElement;){if("string"==typeof e?i.matches(e):i instanceof e)return i;if(!i.node.parentNode||"#document"==i.node.parentNode.nodeName)return null;i=a.adopt(i.node.parentNode)}},doc:function(){return this instanceof a.Doc?this:this.parent(a.Doc)},parents:function(t){var e=[],i=this;do{if(!(i=i.parent(t))||!i.node)break;e.push(i)}while(i.parent);return e},matches:function(t){return function(t,e){return(t.matches||t.matchesSelector||t.msMatchesSelector||t.mozMatchesSelector||t.webkitMatchesSelector||t.oMatchesSelector).call(t,e)}(this.node,t)},native:function(){return this.node},svg:function(t){var i=e.createElement("svg");if(!(t&&this instanceof a.Parent))return i.appendChild(t=e.createElement("svg")),this.writeDataToDom(),t.appendChild(this.node.cloneNode(!0)),i.innerHTML.replace(/^<svg>/,"").replace(/<\/svg>$/,"");i.innerHTML="<svg>"+t.replace(/\n/,"").replace(/<([\w:-]+)([^<]+?)\/>/g,"<$1$2></$1>")+"</svg>";for(var s=0,r=i.firstChild.childNodes.length;s<r;s++)this.node.appendChild(i.firstChild.firstChild);return this},writeDataToDom:function(){return(this.each||this.lines)&&(this.each?this:this.lines()).each((function(){this.writeDataToDom()})),this.node.removeAttribute("svgjs:data"),Object.keys(this.dom).length&&this.node.setAttribute("svgjs:data",JSON.stringify(this.dom)),this},setData:function(t){return this.dom=t,this},is:function(t){return function(t,e){return t instanceof e}(this,t)}}}),a.easing={"-":function(t){return t},"<>":function(t){return-Math.cos(t*Math.PI)/2+.5},">":function(t){return Math.sin(t*Math.PI/2)},"<":function(t){return 1-Math.cos(t*Math.PI/2)}},a.morph=function(t){return function(e,i){return new a.MorphObj(e,i).at(t)}},a.Situation=a.invent({create:function(t){this.init=!1,this.reversed=!1,this.reversing=!1,this.duration=new a.Number(t.duration).valueOf(),this.delay=new a.Number(t.delay).valueOf(),this.start=+new Date+this.delay,this.finish=this.start+this.duration,this.ease=t.ease,this.loop=0,this.loops=!1,this.animations={},this.attrs={},this.styles={},this.transforms=[],this.once={}}}),a.FX=a.invent({create:function(t){this._target=t,this.situations=[],this.active=!1,this.situation=null,this.paused=!1,this.lastPos=0,this.pos=0,this.absPos=0,this._speed=1},extend:{animate:function(t,e,s){"object"===i(t)&&(e=t.ease,s=t.delay,t=t.duration);var r=new a.Situation({duration:t||1e3,delay:s||0,ease:a.easing[e||"-"]||e});return this.queue(r),this},target:function(t){return t&&t instanceof a.Element?(this._target=t,this):this._target},timeToAbsPos:function(t){return(t-this.situation.start)/(this.situation.duration/this._speed)},absPosToTime:function(t){return this.situation.duration/this._speed*t+this.situation.start},startAnimFrame:function(){this.stopAnimFrame(),this.animationFrame=t.requestAnimationFrame(function(){this.step()}.bind(this))},stopAnimFrame:function(){t.cancelAnimationFrame(this.animationFrame)},start:function(){return!this.active&&this.situation&&(this.active=!0,this.startCurrent()),this},startCurrent:function(){return this.situation.start=+new Date+this.situation.delay/this._speed,this.situation.finish=this.situation.start+this.situation.duration/this._speed,this.initAnimations().step()},queue:function(t){return("function"==typeof t||t instanceof a.Situation)&&this.situations.push(t),this.situation||(this.situation=this.situations.shift()),this},dequeue:function(){return this.stop(),this.situation=this.situations.shift(),this.situation&&(this.situation instanceof a.Situation?this.start():this.situation.call(this)),this},initAnimations:function(){var t,e=this.situation;if(e.init)return this;for(var i in e.animations){t=this.target()[i](),Array.isArray(t)||(t=[t]),Array.isArray(e.animations[i])||(e.animations[i]=[e.animations[i]]);for(var s=t.length;s--;)e.animations[i][s]instanceof a.Number&&(t[s]=new a.Number(t[s])),e.animations[i][s]=t[s].morph(e.animations[i][s])}for(var i in e.attrs)e.attrs[i]=new a.MorphObj(this.target().attr(i),e.attrs[i]);for(var i in e.styles)e.styles[i]=new a.MorphObj(this.target().style(i),e.styles[i]);return e.initialTransformation=this.target().matrixify(),e.init=!0,this},clearQueue:function(){return this.situations=[],this},clearCurrent:function(){return this.situation=null,this},stop:function(t,e){var i=this.active;return this.active=!1,e&&this.clearQueue(),t&&this.situation&&(!i&&this.startCurrent(),this.atEnd()),this.stopAnimFrame(),this.clearCurrent()},after:function(t){var e=this.last();return this.target().on("finished.fx",(function i(a){a.detail.situation==e&&(t.call(this,e),this.off("finished.fx",i))})),this._callStart()},during:function(t){var e=this.last(),i=function(i){i.detail.situation==e&&t.call(this,i.detail.pos,a.morph(i.detail.pos),i.detail.eased,e)};return this.target().off("during.fx",i).on("during.fx",i),this.after((function(){this.off("during.fx",i)})),this._callStart()},afterAll:function(t){var e=function e(i){t.call(this),this.off("allfinished.fx",e)};return this.target().off("allfinished.fx",e).on("allfinished.fx",e),this._callStart()},last:function(){return this.situations.length?this.situations[this.situations.length-1]:this.situation},add:function(t,e,i){return this.last()[i||"animations"][t]=e,this._callStart()},step:function(t){var e,i,a;t||(this.absPos=this.timeToAbsPos(+new Date)),!1!==this.situation.loops?(e=Math.max(this.absPos,0),i=Math.floor(e),!0===this.situation.loops||i<this.situation.loops?(this.pos=e-i,a=this.situation.loop,this.situation.loop=i):(this.absPos=this.situation.loops,this.pos=1,a=this.situation.loop-1,this.situation.loop=this.situation.loops),this.situation.reversing&&(this.situation.reversed=this.situation.reversed!=Boolean((this.situation.loop-a)%2))):(this.absPos=Math.min(this.absPos,1),this.pos=this.absPos),this.pos<0&&(this.pos=0),this.situation.reversed&&(this.pos=1-this.pos);var s=this.situation.ease(this.pos);for(var r in this.situation.once)r>this.lastPos&&r<=s&&(this.situation.once[r].call(this.target(),this.pos,s),delete this.situation.once[r]);return this.active&&this.target().fire("during",{pos:this.pos,eased:s,fx:this,situation:this.situation}),this.situation?(this.eachAt(),1==this.pos&&!this.situation.reversed||this.situation.reversed&&0==this.pos?(this.stopAnimFrame(),this.target().fire("finished",{fx:this,situation:this.situation}),this.situations.length||(this.target().fire("allfinished"),this.situations.length||(this.target().off(".fx"),this.active=!1)),this.active?this.dequeue():this.clearCurrent()):!this.paused&&this.active&&this.startAnimFrame(),this.lastPos=s,this):this},eachAt:function(){var t,e=this,i=this.target(),s=this.situation;for(var r in s.animations)t=[].concat(s.animations[r]).map((function(t){return"string"!=typeof t&&t.at?t.at(s.ease(e.pos),e.pos):t})),i[r].apply(i,t);for(var r in s.attrs)t=[r].concat(s.attrs[r]).map((function(t){return"string"!=typeof t&&t.at?t.at(s.ease(e.pos),e.pos):t})),i.attr.apply(i,t);for(var r in s.styles)t=[r].concat(s.styles[r]).map((function(t){return"string"!=typeof t&&t.at?t.at(s.ease(e.pos),e.pos):t})),i.style.apply(i,t);if(s.transforms.length){t=s.initialTransformation,r=0;for(var o=s.transforms.length;r<o;r++){var n=s.transforms[r];n instanceof a.Matrix?t=n.relative?t.multiply((new a.Matrix).morph(n).at(s.ease(this.pos))):t.morph(n).at(s.ease(this.pos)):(n.relative||n.undo(t.extract()),t=t.multiply(n.at(s.ease(this.pos))))}i.matrix(t)}return this},once:function(t,e,i){var a=this.last();return i||(t=a.ease(t)),a.once[t]=e,this},_callStart:function(){return setTimeout(function(){this.start()}.bind(this),0),this}},parent:a.Element,construct:{animate:function(t,e,i){return(this.fx||(this.fx=new a.FX(this))).animate(t,e,i)},delay:function(t){return(this.fx||(this.fx=new a.FX(this))).delay(t)},stop:function(t,e){return this.fx&&this.fx.stop(t,e),this},finish:function(){return this.fx&&this.fx.finish(),this}}}),a.MorphObj=a.invent({create:function(t,e){return a.Color.isColor(e)?new a.Color(t).morph(e):a.regex.delimiter.test(t)?a.regex.pathLetters.test(t)?new a.PathArray(t).morph(e):new a.Array(t).morph(e):a.regex.numberAndUnit.test(e)?new a.Number(t).morph(e):(this.value=t,void(this.destination=e))},extend:{at:function(t,e){return e<1?this.value:this.destination},valueOf:function(){return this.value}}}),a.extend(a.FX,{attr:function(t,e,a){if("object"===i(t))for(var s in t)this.attr(s,t[s]);else this.add(t,e,"attrs");return this},plot:function(t,e,i,a){return 4==arguments.length?this.plot([t,e,i,a]):this.add("plot",new(this.target().morphArray)(t))}}),a.Box=a.invent({create:function(t,e,s,r){if(!("object"!==i(t)||t instanceof a.Element))return a.Box.call(this,null!=t.left?t.left:t.x,null!=t.top?t.top:t.y,t.width,t.height);4==arguments.length&&(this.x=t,this.y=e,this.width=s,this.height=r),b(this)}}),a.BBox=a.invent({create:function(t){if(a.Box.apply(this,[].slice.call(arguments)),t instanceof a.Element){var i;try{if(!e.documentElement.contains){for(var s=t.node;s.parentNode;)s=s.parentNode;if(s!=e)throw new Error("Element not in the dom")}i=t.node.getBBox()}catch(e){if(t instanceof a.Shape){a.parser.draw||a.prepare();var r=t.clone(a.parser.draw.instance).show();r&&r.node&&"function"==typeof r.node.getBBox&&(i=r.node.getBBox()),r&&"function"==typeof r.remove&&r.remove()}else i={x:t.node.clientLeft,y:t.node.clientTop,width:t.node.clientWidth,height:t.node.clientHeight}}a.Box.call(this,i)}},inherit:a.Box,parent:a.Element,construct:{bbox:function(){return new a.BBox(this)}}}),a.BBox.prototype.constructor=a.BBox,a.Matrix=a.invent({create:function(t){var e=p([1,0,0,1,0,0]);t=null===t?e:t instanceof a.Element?t.matrixify():"string"==typeof t?p(t.split(a.regex.delimiter).map(parseFloat)):6==arguments.length?p([].slice.call(arguments)):Array.isArray(t)?p(t):t&&"object"===i(t)?t:e;for(var s=m.length-1;s>=0;--s)this[m[s]]=null!=t[m[s]]?t[m[s]]:e[m[s]]},extend:{extract:function(){var t=f(this,0,1);f(this,1,0);var e=180/Math.PI*Math.atan2(t.y,t.x)-90;return{x:this.e,y:this.f,transformedX:(this.e*Math.cos(e*Math.PI/180)+this.f*Math.sin(e*Math.PI/180))/Math.sqrt(this.a*this.a+this.b*this.b),transformedY:(this.f*Math.cos(e*Math.PI/180)+this.e*Math.sin(-e*Math.PI/180))/Math.sqrt(this.c*this.c+this.d*this.d),rotation:e,a:this.a,b:this.b,c:this.c,d:this.d,e:this.e,f:this.f,matrix:new a.Matrix(this)}},clone:function(){return new a.Matrix(this)},morph:function(t){return this.destination=new a.Matrix(t),this},multiply:function(t){return new a.Matrix(this.native().multiply(function(t){return t instanceof a.Matrix||(t=new a.Matrix(t)),t}(t).native()))},inverse:function(){return new a.Matrix(this.native().inverse())},translate:function(t,e){return new a.Matrix(this.native().translate(t||0,e||0))},native:function(){for(var t=a.parser.native.createSVGMatrix(),e=m.length-1;e>=0;e--)t[m[e]]=this[m[e]];return t},toString:function(){return"matrix("+v(this.a)+","+v(this.b)+","+v(this.c)+","+v(this.d)+","+v(this.e)+","+v(this.f)+")"}},parent:a.Element,construct:{ctm:function(){return new a.Matrix(this.node.getCTM())},screenCTM:function(){if(this instanceof a.Nested){var t=this.rect(1,1),e=t.node.getScreenCTM();return t.remove(),new a.Matrix(e)}return new a.Matrix(this.node.getScreenCTM())}}}),a.Point=a.invent({create:function(t,e){var a;a=Array.isArray(t)?{x:t[0],y:t[1]}:"object"===i(t)?{x:t.x,y:t.y}:null!=t?{x:t,y:null!=e?e:t}:{x:0,y:0},this.x=a.x,this.y=a.y},extend:{clone:function(){return new a.Point(this)},morph:function(t,e){return this.destination=new a.Point(t,e),this}}}),a.extend(a.Element,{point:function(t,e){return new a.Point(t,e).transform(this.screenCTM().inverse())}}),a.extend(a.Element,{attr:function(t,e,s){if(null==t){for(t={},s=(e=this.node.attributes).length-1;s>=0;s--)t[e[s].nodeName]=a.regex.isNumber.test(e[s].nodeValue)?parseFloat(e[s].nodeValue):e[s].nodeValue;return t}if("object"===i(t))for(var r in t)this.attr(r,t[r]);else if(null===e)this.node.removeAttribute(t);else{if(null==e)return null==(e=this.node.getAttribute(t))?a.defaults.attrs[t]:a.regex.isNumber.test(e)?parseFloat(e):e;"stroke-width"==t?this.attr("stroke",parseFloat(e)>0?this._stroke:null):"stroke"==t&&(this._stroke=e),"fill"!=t&&"stroke"!=t||(a.regex.isImage.test(e)&&(e=this.doc().defs().image(e,0,0)),e instanceof a.Image&&(e=this.doc().defs().pattern(0,0,(function(){this.add(e)})))),"number"==typeof e?e=new a.Number(e):a.Color.isColor(e)?e=new a.Color(e):Array.isArray(e)&&(e=new a.Array(e)),"leading"==t?this.leading&&this.leading(e):"string"==typeof s?this.node.setAttributeNS(s,t,e.toString()):this.node.setAttribute(t,e.toString()),!this.rebuild||"font-size"!=t&&"x"!=t||this.rebuild(t,e)}return this}}),a.extend(a.Element,{transform:function(t,e){var s;return"object"!==i(t)?(s=new a.Matrix(this).extract(),"string"==typeof t?s[t]:s):(s=new a.Matrix(this),e=!!e||!!t.relative,null!=t.a&&(s=e?s.multiply(new a.Matrix(t)):new a.Matrix(t)),this.attr("transform",s))}}),a.extend(a.Element,{untransform:function(){return this.attr("transform",null)},matrixify:function(){return(this.attr("transform")||"").split(a.regex.transforms).slice(0,-1).map((function(t){var e=t.trim().split("(");return[e[0],e[1].split(a.regex.delimiter).map((function(t){return parseFloat(t)}))]})).reduce((function(t,e){return"matrix"==e[0]?t.multiply(p(e[1])):t[e[0]].apply(t,e[1])}),new a.Matrix)},toParent:function(t){if(this==t)return this;var e=this.screenCTM(),i=t.screenCTM().inverse();return this.addTo(t).untransform().transform(i.multiply(e)),this},toDoc:function(){return this.toParent(this.doc())}}),a.Transformation=a.invent({create:function(t,e){if(arguments.length>1&&"boolean"!=typeof e)return this.constructor.call(this,[].slice.call(arguments));if(Array.isArray(t))for(var a=0,s=this.arguments.length;a<s;++a)this[this.arguments[a]]=t[a];else if(t&&"object"===i(t))for(a=0,s=this.arguments.length;a<s;++a)this[this.arguments[a]]=t[this.arguments[a]];this.inversed=!1,!0===e&&(this.inversed=!0)}}),a.Translate=a.invent({parent:a.Matrix,inherit:a.Transformation,create:function(t,e){this.constructor.apply(this,[].slice.call(arguments))},extend:{arguments:["transformedX","transformedY"],method:"translate"}}),a.extend(a.Element,{style:function(t,e){if(0==arguments.length)return this.node.style.cssText||"";if(arguments.length<2)if("object"===i(t))for(var s in t)this.style(s,t[s]);else{if(!a.regex.isCss.test(t))return this.node.style[c(t)];for(t=t.split(/\s*;\s*/).filter((function(t){return!!t})).map((function(t){return t.split(/\s*:\s*/)}));e=t.pop();)this.style(e[0],e[1])}else this.node.style[c(t)]=null===e||a.regex.isBlank.test(e)?"":e;return this}}),a.Parent=a.invent({create:function(t){this.constructor.call(this,t)},inherit:a.Element,extend:{children:function(){return a.utils.map(a.utils.filterSVGElements(this.node.childNodes),(function(t){return a.adopt(t)}))},add:function(t,e){return null==e?this.node.appendChild(t.node):t.node!=this.node.childNodes[e]&&this.node.insertBefore(t.node,this.node.childNodes[e]),this},put:function(t,e){return this.add(t,e),t},has:function(t){return this.index(t)>=0},index:function(t){return[].slice.call(this.node.childNodes).indexOf(t.node)},get:function(t){return a.adopt(this.node.childNodes[t])},first:function(){return this.get(0)},last:function(){return this.get(this.node.childNodes.length-1)},each:function(t,e){for(var i=this.children(),s=0,r=i.length;s<r;s++)i[s]instanceof a.Element&&t.apply(i[s],[s,i]),e&&i[s]instanceof a.Container&&i[s].each(t,e);return this},removeElement:function(t){return this.node.removeChild(t.node),this},clear:function(){for(;this.node.hasChildNodes();)this.node.removeChild(this.node.lastChild);return delete this._defs,this},defs:function(){return this.doc().defs()}}}),a.extend(a.Parent,{ungroup:function(t,e){return 0===e||this instanceof a.Defs||this.node==a.parser.draw||(t=t||(this instanceof a.Doc?this:this.parent(a.Parent)),e=e||1/0,this.each((function(){return this instanceof a.Defs?this:this instanceof a.Parent?this.ungroup(t,e-1):this.toParent(t)})),this.node.firstChild||this.remove()),this},flatten:function(t,e){return this.ungroup(t,e)}}),a.Container=a.invent({create:function(t){this.constructor.call(this,t)},inherit:a.Parent}),a.ViewBox=a.invent({parent:a.Container,construct:{}}),["click","dblclick","mousedown","mouseup","mouseover","mouseout","mousemove","touchstart","touchmove","touchleave","touchend","touchcancel"].forEach((function(t){a.Element.prototype[t]=function(e){return a.on(this.node,t,e),this}})),a.listeners=[],a.handlerMap=[],a.listenerId=0,a.on=function(t,e,i,s,r){var o=i.bind(s||t.instance||t),n=(a.handlerMap.indexOf(t)+1||a.handlerMap.push(t))-1,l=e.split(".")[0],h=e.split(".")[1]||"*";a.listeners[n]=a.listeners[n]||{},a.listeners[n][l]=a.listeners[n][l]||{},a.listeners[n][l][h]=a.listeners[n][l][h]||{},i._svgjsListenerId||(i._svgjsListenerId=++a.listenerId),a.listeners[n][l][h][i._svgjsListenerId]=o,t.addEventListener(l,o,r||{passive:!0})},a.off=function(t,e,i){var s=a.handlerMap.indexOf(t),r=e&&e.split(".")[0],o=e&&e.split(".")[1],n="";if(-1!=s)if(i){if("function"==typeof i&&(i=i._svgjsListenerId),!i)return;a.listeners[s][r]&&a.listeners[s][r][o||"*"]&&(t.removeEventListener(r,a.listeners[s][r][o||"*"][i],!1),delete a.listeners[s][r][o||"*"][i])}else if(o&&r){if(a.listeners[s][r]&&a.listeners[s][r][o]){for(var l in a.listeners[s][r][o])a.off(t,[r,o].join("."),l);delete a.listeners[s][r][o]}}else if(o)for(var h in a.listeners[s])for(var n in a.listeners[s][h])o===n&&a.off(t,[h,o].join("."));else if(r){if(a.listeners[s][r]){for(var n in a.listeners[s][r])a.off(t,[r,n].join("."));delete a.listeners[s][r]}}else{for(var h in a.listeners[s])a.off(t,h);delete a.listeners[s],delete a.handlerMap[s]}},a.extend(a.Element,{on:function(t,e,i,s){return a.on(this.node,t,e,i,s),this},off:function(t,e){return a.off(this.node,t,e),this},fire:function(e,i){return e instanceof t.Event?this.node.dispatchEvent(e):this.node.dispatchEvent(e=new a.CustomEvent(e,{detail:i,cancelable:!0})),this._event=e,this},event:function(){return this._event}}),a.Defs=a.invent({create:"defs",inherit:a.Container}),a.G=a.invent({create:"g",inherit:a.Container,extend:{x:function(t){return null==t?this.transform("x"):this.transform({x:t-this.x()},!0)}},construct:{group:function(){return this.put(new a.G)}}}),a.Doc=a.invent({create:function(t){t&&("svg"==(t="string"==typeof t?e.getElementById(t):t).nodeName?this.constructor.call(this,t):(this.constructor.call(this,a.create("svg")),t.appendChild(this.node),this.size("100%","100%")),this.namespace().defs())},inherit:a.Container,extend:{namespace:function(){return this.attr({xmlns:a.ns,version:"1.1"}).attr("xmlns:xlink",a.xlink,a.xmlns).attr("xmlns:svgjs",a.svgjs,a.xmlns)},defs:function(){var t;return this._defs||((t=this.node.getElementsByTagName("defs")[0])?this._defs=a.adopt(t):this._defs=new a.Defs,this.node.appendChild(this._defs.node)),this._defs},parent:function(){return this.node.parentNode&&"#document"!=this.node.parentNode.nodeName?this.node.parentNode:null},remove:function(){return this.parent()&&this.parent().removeChild(this.node),this},clear:function(){for(;this.node.hasChildNodes();)this.node.removeChild(this.node.lastChild);return delete this._defs,a.parser.draw&&!a.parser.draw.parentNode&&this.node.appendChild(a.parser.draw),this},clone:function(t){this.writeDataToDom();var e=this.node,i=x(e.cloneNode(!0));return t?(t.node||t).appendChild(i.node):e.parentNode.insertBefore(i.node,e.nextSibling),i}}}),a.extend(a.Element,{}),a.Gradient=a.invent({create:function(t){this.constructor.call(this,a.create(t+"Gradient")),this.type=t},inherit:a.Container,extend:{at:function(t,e,i){return this.put(new a.Stop).update(t,e,i)},update:function(t){return this.clear(),"function"==typeof t&&t.call(this,this),this},fill:function(){return"url(#"+this.id()+")"},toString:function(){return this.fill()},attr:function(t,e,i){return"transform"==t&&(t="gradientTransform"),a.Container.prototype.attr.call(this,t,e,i)}},construct:{gradient:function(t,e){return this.defs().gradient(t,e)}}}),a.extend(a.Gradient,a.FX,{from:function(t,e){return"radial"==(this._target||this).type?this.attr({fx:new a.Number(t),fy:new a.Number(e)}):this.attr({x1:new a.Number(t),y1:new a.Number(e)})},to:function(t,e){return"radial"==(this._target||this).type?this.attr({cx:new a.Number(t),cy:new a.Number(e)}):this.attr({x2:new a.Number(t),y2:new a.Number(e)})}}),a.extend(a.Defs,{gradient:function(t,e){return this.put(new a.Gradient(t)).update(e)}}),a.Stop=a.invent({create:"stop",inherit:a.Element,extend:{update:function(t){return("number"==typeof t||t instanceof a.Number)&&(t={offset:arguments[0],color:arguments[1],opacity:arguments[2]}),null!=t.opacity&&this.attr("stop-opacity",t.opacity),null!=t.color&&this.attr("stop-color",t.color),null!=t.offset&&this.attr("offset",new a.Number(t.offset)),this}}}),a.Pattern=a.invent({create:"pattern",inherit:a.Container,extend:{fill:function(){return"url(#"+this.id()+")"},update:function(t){return this.clear(),"function"==typeof t&&t.call(this,this),this},toString:function(){return this.fill()},attr:function(t,e,i){return"transform"==t&&(t="patternTransform"),a.Container.prototype.attr.call(this,t,e,i)}},construct:{pattern:function(t,e,i){return this.defs().pattern(t,e,i)}}}),a.extend(a.Defs,{pattern:function(t,e,i){return this.put(new a.Pattern).update(i).attr({x:0,y:0,width:t,height:e,patternUnits:"userSpaceOnUse"})}}),a.Shape=a.invent({create:function(t){this.constructor.call(this,t)},inherit:a.Element}),a.Symbol=a.invent({create:"symbol",inherit:a.Container,construct:{symbol:function(){return this.put(new a.Symbol)}}}),a.Use=a.invent({create:"use",inherit:a.Shape,extend:{element:function(t,e){return this.attr("href",(e||"")+"#"+t,a.xlink)}},construct:{use:function(t,e){return this.put(new a.Use).element(t,e)}}}),a.Rect=a.invent({create:"rect",inherit:a.Shape,construct:{rect:function(t,e){return this.put(new a.Rect).size(t,e)}}}),a.Circle=a.invent({create:"circle",inherit:a.Shape,construct:{circle:function(t){return this.put(new a.Circle).rx(new a.Number(t).divide(2)).move(0,0)}}}),a.extend(a.Circle,a.FX,{rx:function(t){return this.attr("r",t)},ry:function(t){return this.rx(t)}}),a.Ellipse=a.invent({create:"ellipse",inherit:a.Shape,construct:{ellipse:function(t,e){return this.put(new a.Ellipse).size(t,e).move(0,0)}}}),a.extend(a.Ellipse,a.Rect,a.FX,{rx:function(t){return this.attr("rx",t)},ry:function(t){return this.attr("ry",t)}}),a.extend(a.Circle,a.Ellipse,{x:function(t){return null==t?this.cx()-this.rx():this.cx(t+this.rx())},y:function(t){return null==t?this.cy()-this.ry():this.cy(t+this.ry())},cx:function(t){return null==t?this.attr("cx"):this.attr("cx",t)},cy:function(t){return null==t?this.attr("cy"):this.attr("cy",t)},width:function(t){return null==t?2*this.rx():this.rx(new a.Number(t).divide(2))},height:function(t){return null==t?2*this.ry():this.ry(new a.Number(t).divide(2))},size:function(t,e){var i=u(this,t,e);return this.rx(new a.Number(i.width).divide(2)).ry(new a.Number(i.height).divide(2))}}),a.Line=a.invent({create:"line",inherit:a.Shape,extend:{array:function(){return new a.PointArray([[this.attr("x1"),this.attr("y1")],[this.attr("x2"),this.attr("y2")]])},plot:function(t,e,i,s){return null==t?this.array():(t=void 0!==e?{x1:t,y1:e,x2:i,y2:s}:new a.PointArray(t).toLine(),this.attr(t))},move:function(t,e){return this.attr(this.array().move(t,e).toLine())},size:function(t,e){var i=u(this,t,e);return this.attr(this.array().size(i.width,i.height).toLine())}},construct:{line:function(t,e,i,s){return a.Line.prototype.plot.apply(this.put(new a.Line),null!=t?[t,e,i,s]:[0,0,0,0])}}}),a.Polyline=a.invent({create:"polyline",inherit:a.Shape,construct:{polyline:function(t){return this.put(new a.Polyline).plot(t||new a.PointArray)}}}),a.Polygon=a.invent({create:"polygon",inherit:a.Shape,construct:{polygon:function(t){return this.put(new a.Polygon).plot(t||new a.PointArray)}}}),a.extend(a.Polyline,a.Polygon,{array:function(){return this._array||(this._array=new a.PointArray(this.attr("points")))},plot:function(t){return null==t?this.array():this.clear().attr("points","string"==typeof t?t:this._array=new a.PointArray(t))},clear:function(){return delete this._array,this},move:function(t,e){return this.attr("points",this.array().move(t,e))},size:function(t,e){var i=u(this,t,e);return this.attr("points",this.array().size(i.width,i.height))}}),a.extend(a.Line,a.Polyline,a.Polygon,{morphArray:a.PointArray,x:function(t){return null==t?this.bbox().x:this.move(t,this.bbox().y)},y:function(t){return null==t?this.bbox().y:this.move(this.bbox().x,t)},width:function(t){var e=this.bbox();return null==t?e.width:this.size(t,e.height)},height:function(t){var e=this.bbox();return null==t?e.height:this.size(e.width,t)}}),a.Path=a.invent({create:"path",inherit:a.Shape,extend:{morphArray:a.PathArray,array:function(){return this._array||(this._array=new a.PathArray(this.attr("d")))},plot:function(t){return null==t?this.array():this.clear().attr("d","string"==typeof t?t:this._array=new a.PathArray(t))},clear:function(){return delete this._array,this}},construct:{path:function(t){return this.put(new a.Path).plot(t||new a.PathArray)}}}),a.Image=a.invent({create:"image",inherit:a.Shape,extend:{load:function(e){if(!e)return this;var i=this,s=new t.Image;return a.on(s,"load",(function(){a.off(s);var t=i.parent(a.Pattern);null!==t&&(0==i.width()&&0==i.height()&&i.size(s.width,s.height),t&&0==t.width()&&0==t.height()&&t.size(i.width(),i.height()),"function"==typeof i._loaded&&i._loaded.call(i,{width:s.width,height:s.height,ratio:s.width/s.height,url:e}))})),a.on(s,"error",(function(t){a.off(s),"function"==typeof i._error&&i._error.call(i,t)})),this.attr("href",s.src=this.src=e,a.xlink)},loaded:function(t){return this._loaded=t,this},error:function(t){return this._error=t,this}},construct:{image:function(t,e,i){return this.put(new a.Image).load(t).size(e||0,i||e||0)}}}),a.Text=a.invent({create:function(){this.constructor.call(this,a.create("text")),this.dom.leading=new a.Number(1.3),this._rebuild=!0,this._build=!1,this.attr("font-family",a.defaults.attrs["font-family"])},inherit:a.Shape,extend:{x:function(t){return null==t?this.attr("x"):this.attr("x",t)},text:function(t){if(void 0===t){t="";for(var e=this.node.childNodes,i=0,s=e.length;i<s;++i)0!=i&&3!=e[i].nodeType&&1==a.adopt(e[i]).dom.newLined&&(t+="\n"),t+=e[i].textContent;return t}if(this.clear().build(!0),"function"==typeof t)t.call(this,this);else{i=0;for(var r=(t=t.split("\n")).length;i<r;i++)this.tspan(t[i]).newLine()}return this.build(!1).rebuild()},size:function(t){return this.attr("font-size",t).rebuild()},leading:function(t){return null==t?this.dom.leading:(this.dom.leading=new a.Number(t),this.rebuild())},lines:function(){var t=(this.textPath&&this.textPath()||this).node,e=a.utils.map(a.utils.filterSVGElements(t.childNodes),(function(t){return a.adopt(t)}));return new a.Set(e)},rebuild:function(t){if("boolean"==typeof t&&(this._rebuild=t),this._rebuild){var e=this,i=0,s=this.dom.leading*new a.Number(this.attr("font-size"));this.lines().each((function(){this.dom.newLined&&(e.textPath()||this.attr("x",e.attr("x")),"\n"==this.text()?i+=s:(this.attr("dy",s+i),i=0))})),this.fire("rebuild")}return this},build:function(t){return this._build=!!t,this},setData:function(t){return this.dom=t,this.dom.leading=new a.Number(t.leading||1.3),this}},construct:{text:function(t){return this.put(new a.Text).text(t)},plain:function(t){return this.put(new a.Text).plain(t)}}}),a.Tspan=a.invent({create:"tspan",inherit:a.Shape,extend:{text:function(t){return null==t?this.node.textContent+(this.dom.newLined?"\n":""):("function"==typeof t?t.call(this,this):this.plain(t),this)},dx:function(t){return this.attr("dx",t)},dy:function(t){return this.attr("dy",t)},newLine:function(){var t=this.parent(a.Text);return this.dom.newLined=!0,this.dy(t.dom.leading*t.attr("font-size")).attr("x",t.x())}}}),a.extend(a.Text,a.Tspan,{plain:function(t){return!1===this._build&&this.clear(),this.node.appendChild(e.createTextNode(t)),this},tspan:function(t){var e=(this.textPath&&this.textPath()||this).node,i=new a.Tspan;return!1===this._build&&this.clear(),e.appendChild(i.node),i.text(t)},clear:function(){for(var t=(this.textPath&&this.textPath()||this).node;t.hasChildNodes();)t.removeChild(t.lastChild);return this},length:function(){return this.node.getComputedTextLength()}}),a.TextPath=a.invent({create:"textPath",inherit:a.Parent,parent:a.Text,construct:{morphArray:a.PathArray,array:function(){var t=this.track();return t?t.array():null},plot:function(t){var e=this.track(),i=null;return e&&(i=e.plot(t)),null==t?i:this},track:function(){var t=this.textPath();if(t)return t.reference("href")},textPath:function(){if(this.node.firstChild&&"textPath"==this.node.firstChild.nodeName)return a.adopt(this.node.firstChild)}}}),a.Nested=a.invent({create:function(){this.constructor.call(this,a.create("svg")),this.style("overflow","visible")},inherit:a.Container,construct:{nested:function(){return this.put(new a.Nested)}}});var l={stroke:["color","width","opacity","linecap","linejoin","miterlimit","dasharray","dashoffset"],fill:["color","opacity","rule"],prefix:function(t,e){return"color"==e?t:t+"-"+e}};function h(t,e,i,s){return i+s.replace(a.regex.dots," .")}function c(t){return t.toLowerCase().replace(/-(.)/g,(function(t,e){return e.toUpperCase()}))}function d(t){return t.charAt(0).toUpperCase()+t.slice(1)}function g(t){var e=t.toString(16);return 1==e.length?"0"+e:e}function u(t,e,i){if(null==e||null==i){var a=t.bbox();null==e?e=a.width/a.height*i:null==i&&(i=a.height/a.width*e)}return{width:e,height:i}}function f(t,e,i){return{x:e*t.a+i*t.c+0,y:e*t.b+i*t.d+0}}function p(t){return{a:t[0],b:t[1],c:t[2],d:t[3],e:t[4],f:t[5]}}function x(e){for(var i=e.childNodes.length-1;i>=0;i--)e.childNodes[i]instanceof t.SVGElement&&x(e.childNodes[i]);return a.adopt(e).id(a.eid(e.nodeName))}function b(t){return null==t.x&&(t.x=0,t.y=0,t.width=0,t.height=0),t.w=t.width,t.h=t.height,t.x2=t.x+t.width,t.y2=t.y+t.height,t.cx=t.x+t.width/2,t.cy=t.y+t.height/2,t}function v(t){return Math.abs(t)>1e-37?t:0}["fill","stroke"].forEach((function(t){var e={};e[t]=function(e){if(void 0===e)return this;if("string"==typeof e||a.Color.isRgb(e)||e&&"function"==typeof e.fill)this.attr(t,e);else for(var i=l[t].length-1;i>=0;i--)null!=e[l[t][i]]&&this.attr(l.prefix(t,l[t][i]),e[l[t][i]]);return this},a.extend(a.Element,a.FX,e)})),a.extend(a.Element,a.FX,{translate:function(t,e){return this.transform({x:t,y:e})},matrix:function(t){return this.attr("transform",new a.Matrix(6==arguments.length?[].slice.call(arguments):t))},opacity:function(t){return this.attr("opacity",t)},dx:function(t){return this.x(new a.Number(t).plus(this instanceof a.FX?0:this.x()),!0)},dy:function(t){return this.y(new a.Number(t).plus(this instanceof a.FX?0:this.y()),!0)}}),a.extend(a.Path,{length:function(){return this.node.getTotalLength()},pointAt:function(t){return this.node.getPointAtLength(t)}}),a.Set=a.invent({create:function(t){Array.isArray(t)?this.members=t:this.clear()},extend:{add:function(){for(var t=[].slice.call(arguments),e=0,i=t.length;e<i;e++)this.members.push(t[e]);return this},remove:function(t){var e=this.index(t);return e>-1&&this.members.splice(e,1),this},each:function(t){for(var e=0,i=this.members.length;e<i;e++)t.apply(this.members[e],[e,this.members]);return this},clear:function(){return this.members=[],this},length:function(){return this.members.length},has:function(t){return this.index(t)>=0},index:function(t){return this.members.indexOf(t)},get:function(t){return this.members[t]},first:function(){return this.get(0)},last:function(){return this.get(this.members.length-1)},valueOf:function(){return this.members}},construct:{set:function(t){return new a.Set(t)}}}),a.FX.Set=a.invent({create:function(t){this.set=t}}),a.Set.inherit=function(){var t=[];for(var e in a.Shape.prototype)"function"==typeof a.Shape.prototype[e]&&"function"!=typeof a.Set.prototype[e]&&t.push(e);for(var e in t.forEach((function(t){a.Set.prototype[t]=function(){for(var e=0,i=this.members.length;e<i;e++)this.members[e]&&"function"==typeof this.members[e][t]&&this.members[e][t].apply(this.members[e],arguments);return"animate"==t?this.fx||(this.fx=new a.FX.Set(this)):this}})),t=[],a.FX.prototype)"function"==typeof a.FX.prototype[e]&&"function"!=typeof a.FX.Set.prototype[e]&&t.push(e);t.forEach((function(t){a.FX.Set.prototype[t]=function(){for(var e=0,i=this.set.members.length;e<i;e++)this.set.members[e].fx[t].apply(this.set.members[e].fx,arguments);return this}}))},a.extend(a.Element,{}),a.extend(a.Element,{remember:function(t,e){if("object"===i(arguments[0]))for(var a in t)this.remember(a,t[a]);else{if(1==arguments.length)return this.memory()[t];this.memory()[t]=e}return this},forget:function(){if(0==arguments.length)this._memory={};else for(var t=arguments.length-1;t>=0;t--)delete this.memory()[arguments[t]];return this},memory:function(){return this._memory||(this._memory={})}}),a.get=function(t){var i=e.getElementById(function(t){var e=(t||"").toString().match(a.regex.reference);if(e)return e[1]}(t)||t);return a.adopt(i)},a.select=function(t,i){return new a.Set(a.utils.map((i||e).querySelectorAll(t),(function(t){return a.adopt(t)})))},a.extend(a.Parent,{select:function(t){return a.select(t,this.node)}});var m="abcdef".split("");if("function"!=typeof t.CustomEvent){var y=function(t,i){i=i||{bubbles:!1,cancelable:!1,detail:void 0};var a=e.createEvent("CustomEvent");return a.initCustomEvent(t,i.bubbles,i.cancelable,i.detail),a};y.prototype=t.Event.prototype,a.CustomEvent=y}else a.CustomEvent=t.CustomEvent;return a}, true?!(__WEBPACK_AMD_DEFINE_RESULT__ = (function(){return Et(Xt,Xt.document)}).call(exports, __webpack_require__, exports, module),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)):0,
/*! svg.filter.js - v2.0.2 - 2016-02-24
* https://github.com/wout/svg.filter.js
* Copyright (c) 2016 Wout Fierens; Licensed MIT */
function(){SVG.Filter=SVG.invent({create:"filter",inherit:SVG.Parent,extend:{source:"SourceGraphic",sourceAlpha:"SourceAlpha",background:"BackgroundImage",backgroundAlpha:"BackgroundAlpha",fill:"FillPaint",stroke:"StrokePaint",autoSetIn:!0,put:function(t,e){return this.add(t,e),!t.attr("in")&&this.autoSetIn&&t.attr("in",this.source),t.attr("result")||t.attr("result",t),t},blend:function(t,e,i){return this.put(new SVG.BlendEffect(t,e,i))},colorMatrix:function(t,e){return this.put(new SVG.ColorMatrixEffect(t,e))},convolveMatrix:function(t){return this.put(new SVG.ConvolveMatrixEffect(t))},componentTransfer:function(t){return this.put(new SVG.ComponentTransferEffect(t))},composite:function(t,e,i){return this.put(new SVG.CompositeEffect(t,e,i))},flood:function(t,e){return this.put(new SVG.FloodEffect(t,e))},offset:function(t,e){return this.put(new SVG.OffsetEffect(t,e))},image:function(t){return this.put(new SVG.ImageEffect(t))},merge:function(){var t=[void 0];for(var e in arguments)t.push(arguments[e]);return this.put(new(SVG.MergeEffect.bind.apply(SVG.MergeEffect,t)))},gaussianBlur:function(t,e){return this.put(new SVG.GaussianBlurEffect(t,e))},morphology:function(t,e){return this.put(new SVG.MorphologyEffect(t,e))},diffuseLighting:function(t,e,i){return this.put(new SVG.DiffuseLightingEffect(t,e,i))},displacementMap:function(t,e,i,a,s){return this.put(new SVG.DisplacementMapEffect(t,e,i,a,s))},specularLighting:function(t,e,i,a){return this.put(new SVG.SpecularLightingEffect(t,e,i,a))},tile:function(){return this.put(new SVG.TileEffect)},turbulence:function(t,e,i,a,s){return this.put(new SVG.TurbulenceEffect(t,e,i,a,s))},toString:function(){return"url(#"+this.attr("id")+")"}}}),SVG.extend(SVG.Defs,{filter:function(t){var e=this.put(new SVG.Filter);return"function"==typeof t&&t.call(e,e),e}}),SVG.extend(SVG.Container,{filter:function(t){return this.defs().filter(t)}}),SVG.extend(SVG.Element,SVG.G,SVG.Nested,{filter:function(t){return this.filterer=t instanceof SVG.Element?t:this.doc().filter(t),this.doc()&&this.filterer.doc()!==this.doc()&&this.doc().defs().add(this.filterer),this.attr("filter",this.filterer),this.filterer},unfilter:function(t){return this.filterer&&!0===t&&this.filterer.remove(),delete this.filterer,this.attr("filter",null)}}),SVG.Effect=SVG.invent({create:function(){this.constructor.call(this)},inherit:SVG.Element,extend:{in:function(t){return null==t?this.parent()&&this.parent().select('[result="'+this.attr("in")+'"]').get(0)||this.attr("in"):this.attr("in",t)},result:function(t){return null==t?this.attr("result"):this.attr("result",t)},toString:function(){return this.result()}}}),SVG.ParentEffect=SVG.invent({create:function(){this.constructor.call(this)},inherit:SVG.Parent,extend:{in:function(t){return null==t?this.parent()&&this.parent().select('[result="'+this.attr("in")+'"]').get(0)||this.attr("in"):this.attr("in",t)},result:function(t){return null==t?this.attr("result"):this.attr("result",t)},toString:function(){return this.result()}}});var t={blend:function(t,e){return this.parent()&&this.parent().blend(this,t,e)},colorMatrix:function(t,e){return this.parent()&&this.parent().colorMatrix(t,e).in(this)},convolveMatrix:function(t){return this.parent()&&this.parent().convolveMatrix(t).in(this)},componentTransfer:function(t){return this.parent()&&this.parent().componentTransfer(t).in(this)},composite:function(t,e){return this.parent()&&this.parent().composite(this,t,e)},flood:function(t,e){return this.parent()&&this.parent().flood(t,e)},offset:function(t,e){return this.parent()&&this.parent().offset(t,e).in(this)},image:function(t){return this.parent()&&this.parent().image(t)},merge:function(){return this.parent()&&this.parent().merge.apply(this.parent(),[this].concat(arguments))},gaussianBlur:function(t,e){return this.parent()&&this.parent().gaussianBlur(t,e).in(this)},morphology:function(t,e){return this.parent()&&this.parent().morphology(t,e).in(this)},diffuseLighting:function(t,e,i){return this.parent()&&this.parent().diffuseLighting(t,e,i).in(this)},displacementMap:function(t,e,i,a){return this.parent()&&this.parent().displacementMap(this,t,e,i,a)},specularLighting:function(t,e,i,a){return this.parent()&&this.parent().specularLighting(t,e,i,a).in(this)},tile:function(){return this.parent()&&this.parent().tile().in(this)},turbulence:function(t,e,i,a,s){return this.parent()&&this.parent().turbulence(t,e,i,a,s).in(this)}};SVG.extend(SVG.Effect,t),SVG.extend(SVG.ParentEffect,t),SVG.ChildEffect=SVG.invent({create:function(){this.constructor.call(this)},inherit:SVG.Element,extend:{in:function(t){this.attr("in",t)}}});var e={blend:function(t,e,i){this.attr({in:t,in2:e,mode:i||"normal"})},colorMatrix:function(t,e){"matrix"==t&&(e=s(e)),this.attr({type:t,values:void 0===e?null:e})},convolveMatrix:function(t){t=s(t),this.attr({order:Math.sqrt(t.split(" ").length),kernelMatrix:t})},composite:function(t,e,i){this.attr({in:t,in2:e,operator:i})},flood:function(t,e){this.attr("flood-color",t),null!=e&&this.attr("flood-opacity",e)},offset:function(t,e){this.attr({dx:t,dy:e})},image:function(t){this.attr("href",t,SVG.xlink)},displacementMap:function(t,e,i,a,s){this.attr({in:t,in2:e,scale:i,xChannelSelector:a,yChannelSelector:s})},gaussianBlur:function(t,e){null!=t||null!=e?this.attr("stdDeviation",r(Array.prototype.slice.call(arguments))):this.attr("stdDeviation","0 0")},morphology:function(t,e){this.attr({operator:t,radius:e})},tile:function(){},turbulence:function(t,e,i,a,s){this.attr({numOctaves:e,seed:i,stitchTiles:a,baseFrequency:t,type:s})}},i={merge:function(){var t;if(arguments[0]instanceof SVG.Set){var e=this;arguments[0].each((function(t){this instanceof SVG.MergeNode?e.put(this):(this instanceof SVG.Effect||this instanceof SVG.ParentEffect)&&e.put(new SVG.MergeNode(this))}))}else{t=Array.isArray(arguments[0])?arguments[0]:arguments;for(var i=0;i<t.length;i++)t[i]instanceof SVG.MergeNode?this.put(t[i]):this.put(new SVG.MergeNode(t[i]))}},componentTransfer:function(t){if(this.rgb=new SVG.Set,["r","g","b","a"].forEach(function(t){this[t]=new(SVG["Func"+t.toUpperCase()])("identity"),this.rgb.add(this[t]),this.node.appendChild(this[t].node)}.bind(this)),t)for(var e in t.rgb&&(["r","g","b"].forEach(function(e){this[e].attr(t.rgb)}.bind(this)),delete t.rgb),t)this[e].attr(t[e])},diffuseLighting:function(t,e,i){this.attr({surfaceScale:t,diffuseConstant:e,kernelUnitLength:i})},specularLighting:function(t,e,i,a){this.attr({surfaceScale:t,diffuseConstant:e,specularExponent:i,kernelUnitLength:a})}},a={distantLight:function(t,e){this.attr({azimuth:t,elevation:e})},pointLight:function(t,e,i){this.attr({x:t,y:e,z:i})},spotLight:function(t,e,i,a,s,r){this.attr({x:t,y:e,z:i,pointsAtX:a,pointsAtY:s,pointsAtZ:r})},mergeNode:function(t){this.attr("in",t)}};function s(t){return Array.isArray(t)&&(t=new SVG.Array(t)),t.toString().replace(/^\s+/,"").replace(/\s+$/,"").replace(/\s+/g," ")}function r(t){if(!Array.isArray(t))return t;for(var e=0,i=t.length,a=[];e<i;e++)a.push(t[e]);return a.join(" ")}function o(){var t=function(){};for(var e in"function"==typeof arguments[arguments.length-1]&&(t=arguments[arguments.length-1],Array.prototype.splice.call(arguments,arguments.length-1,1)),arguments)for(var i in arguments[e])t(arguments[e][i],i,arguments[e])}["r","g","b","a"].forEach((function(t){a["Func"+t.toUpperCase()]=function(t){switch(this.attr("type",t),t){case"table":this.attr("tableValues",arguments[1]);break;case"linear":this.attr("slope",arguments[1]),this.attr("intercept",arguments[2]);break;case"gamma":this.attr("amplitude",arguments[1]),this.attr("exponent",arguments[2]),this.attr("offset",arguments[2])}}})),o(e,(function(t,e){var i=e.charAt(0).toUpperCase()+e.slice(1);SVG[i+"Effect"]=SVG.invent({create:function(){this.constructor.call(this,SVG.create("fe"+i)),t.apply(this,arguments),this.result(this.attr("id")+"Out")},inherit:SVG.Effect,extend:{}})})),o(i,(function(t,e){var i=e.charAt(0).toUpperCase()+e.slice(1);SVG[i+"Effect"]=SVG.invent({create:function(){this.constructor.call(this,SVG.create("fe"+i)),t.apply(this,arguments),this.result(this.attr("id")+"Out")},inherit:SVG.ParentEffect,extend:{}})})),o(a,(function(t,e){var i=e.charAt(0).toUpperCase()+e.slice(1);SVG[i]=SVG.invent({create:function(){this.constructor.call(this,SVG.create("fe"+i)),t.apply(this,arguments)},inherit:SVG.ChildEffect,extend:{}})})),SVG.extend(SVG.MergeEffect,{in:function(t){return t instanceof SVG.MergeNode?this.add(t,0):this.add(new SVG.MergeNode(t),0),this}}),SVG.extend(SVG.CompositeEffect,SVG.BlendEffect,SVG.DisplacementMapEffect,{in2:function(t){return null==t?this.parent()&&this.parent().select('[result="'+this.attr("in2")+'"]').get(0)||this.attr("in2"):this.attr("in2",t)}}),SVG.filter={sepiatone:[.343,.669,.119,0,0,.249,.626,.13,0,0,.172,.334,.111,0,0,0,0,0,1,0]}}.call(void 0),function(){function t(t,s,r,o,n,l,h){for(var c=t.slice(s,r||h),d=o.slice(n,l||h),g=0,u={pos:[0,0],start:[0,0]},f={pos:[0,0],start:[0,0]};;){if(c[g]=e.call(u,c[g]),d[g]=e.call(f,d[g]),c[g][0]!=d[g][0]||"M"==c[g][0]||"A"==c[g][0]&&(c[g][4]!=d[g][4]||c[g][5]!=d[g][5])?(Array.prototype.splice.apply(c,[g,1].concat(a.call(u,c[g]))),Array.prototype.splice.apply(d,[g,1].concat(a.call(f,d[g])))):(c[g]=i.call(u,c[g]),d[g]=i.call(f,d[g])),++g==c.length&&g==d.length)break;g==c.length&&c.push(["C",u.pos[0],u.pos[1],u.pos[0],u.pos[1],u.pos[0],u.pos[1]]),g==d.length&&d.push(["C",f.pos[0],f.pos[1],f.pos[0],f.pos[1],f.pos[0],f.pos[1]])}return{start:c,dest:d}}function e(t){switch(t[0]){case"z":case"Z":t[0]="L",t[1]=this.start[0],t[2]=this.start[1];break;case"H":t[0]="L",t[2]=this.pos[1];break;case"V":t[0]="L",t[2]=t[1],t[1]=this.pos[0];break;case"T":t[0]="Q",t[3]=t[1],t[4]=t[2],t[1]=this.reflection[1],t[2]=this.reflection[0];break;case"S":t[0]="C",t[6]=t[4],t[5]=t[3],t[4]=t[2],t[3]=t[1],t[2]=this.reflection[1],t[1]=this.reflection[0]}return t}function i(t){var e=t.length;return this.pos=[t[e-2],t[e-1]],-1!="SCQT".indexOf(t[0])&&(this.reflection=[2*this.pos[0]-t[e-4],2*this.pos[1]-t[e-3]]),t}function a(t){var e=[t];switch(t[0]){case"M":return this.pos=this.start=[t[1],t[2]],e;case"L":t[5]=t[3]=t[1],t[6]=t[4]=t[2],t[1]=this.pos[0],t[2]=this.pos[1];break;case"Q":t[6]=t[4],t[5]=t[3],t[4]=1*t[4]/3+2*t[2]/3,t[3]=1*t[3]/3+2*t[1]/3,t[2]=1*this.pos[1]/3+2*t[2]/3,t[1]=1*this.pos[0]/3+2*t[1]/3;break;case"A":e=function(t,e){var i,a,s,r,o,n,l,h,c,d,g,u,f,p,x,b,v,m,y,w,k,A,S,C,L,P,T=Math.abs(e[1]),M=Math.abs(e[2]),I=e[3]%360,z=e[4],X=e[5],E=e[6],Y=e[7],F=new SVG.Point(t),R=new SVG.Point(E,Y),D=[];if(0===T||0===M||F.x===R.x&&F.y===R.y)return[["C",F.x,F.y,R.x,R.y,R.x,R.y]];i=new SVG.Point((F.x-R.x)/2,(F.y-R.y)/2).transform((new SVG.Matrix).rotate(I)),(a=i.x*i.x/(T*T)+i.y*i.y/(M*M))>1&&(T*=a=Math.sqrt(a),M*=a);s=(new SVG.Matrix).rotate(I).scale(1/T,1/M).rotate(-I),F=F.transform(s),R=R.transform(s),r=[R.x-F.x,R.y-F.y],n=r[0]*r[0]+r[1]*r[1],o=Math.sqrt(n),r[0]/=o,r[1]/=o,l=n<4?Math.sqrt(1-n/4):0,z===X&&(l*=-1);h=new SVG.Point((R.x+F.x)/2+l*-r[1],(R.y+F.y)/2+l*r[0]),c=new SVG.Point(F.x-h.x,F.y-h.y),d=new SVG.Point(R.x-h.x,R.y-h.y),g=Math.acos(c.x/Math.sqrt(c.x*c.x+c.y*c.y)),c.y<0&&(g*=-1);u=Math.acos(d.x/Math.sqrt(d.x*d.x+d.y*d.y)),d.y<0&&(u*=-1);X&&g>u&&(u+=2*Math.PI);!X&&g<u&&(u-=2*Math.PI);for(p=Math.ceil(2*Math.abs(g-u)/Math.PI),b=[],v=g,f=(u-g)/p,x=4*Math.tan(f/4)/3,k=0;k<=p;k++)y=Math.cos(v),m=Math.sin(v),w=new SVG.Point(h.x+y,h.y+m),b[k]=[new SVG.Point(w.x+x*m,w.y-x*y),w,new SVG.Point(w.x-x*m,w.y+x*y)],v+=f;for(b[0][0]=b[0][1].clone(),b[b.length-1][2]=b[b.length-1][1].clone(),s=(new SVG.Matrix).rotate(I).scale(T,M).rotate(-I),k=0,A=b.length;k<A;k++)b[k][0]=b[k][0].transform(s),b[k][1]=b[k][1].transform(s),b[k][2]=b[k][2].transform(s);for(k=1,A=b.length;k<A;k++)S=(w=b[k-1][2]).x,C=w.y,L=(w=b[k][0]).x,P=w.y,E=(w=b[k][1]).x,Y=w.y,D.push(["C",S,C,L,P,E,Y]);return D}(this.pos,t),t=e[0]}return t[0]="C",this.pos=[t[5],t[6]],this.reflection=[2*t[5]-t[3],2*t[6]-t[4]],e}function s(t,e){if(!1===e)return!1;for(var i=e,a=t.length;i<a;++i)if("M"==t[i][0])return i;return!1}SVG.extend(SVG.PathArray,{morph:function(e){for(var i=this.value,a=this.parse(e),r=0,o=0,n=!1,l=!1;!1!==r||!1!==o;){var h;n=s(i,!1!==r&&r+1),l=s(a,!1!==o&&o+1),!1===r&&(r=0==(h=new SVG.PathArray(c.start).bbox()).height||0==h.width?i.push(i[0])-1:i.push(["M",h.x+h.width/2,h.y+h.height/2])-1),!1===o&&(o=0==(h=new SVG.PathArray(c.dest).bbox()).height||0==h.width?a.push(a[0])-1:a.push(["M",h.x+h.width/2,h.y+h.height/2])-1);var c=t(i,r,n,a,o,l);i=i.slice(0,r).concat(c.start,!1===n?[]:i.slice(n)),a=a.slice(0,o).concat(c.dest,!1===l?[]:a.slice(l)),r=!1!==n&&r+c.start.length,o=!1!==l&&o+c.dest.length}return this.value=i,this.destination=new SVG.PathArray,this.destination.value=a,this}})}(),
/*! svg.draggable.js - v2.2.2 - 2019-01-08
* https://github.com/svgdotjs/svg.draggable.js
* Copyright (c) 2019 Wout Fierens; Licensed MIT */
function(){function t(t){t.remember("_draggable",this),this.el=t}t.prototype.init=function(t,e){var i=this;this.constraint=t,this.value=e,this.el.on("mousedown.drag",(function(t){i.start(t)})),this.el.on("touchstart.drag",(function(t){i.start(t)}))},t.prototype.transformPoint=function(t,e){var i=(t=t||window.event).changedTouches&&t.changedTouches[0]||t;return this.p.x=i.clientX-(e||0),this.p.y=i.clientY,this.p.matrixTransform(this.m)},t.prototype.getBBox=function(){var t=this.el.bbox();return this.el instanceof SVG.Nested&&(t=this.el.rbox()),(this.el instanceof SVG.G||this.el instanceof SVG.Use||this.el instanceof SVG.Nested)&&(t.x=this.el.x(),t.y=this.el.y()),t},t.prototype.start=function(t){if("click"!=t.type&&"mousedown"!=t.type&&"mousemove"!=t.type||1==(t.which||t.buttons)){var e=this;if(this.el.fire("beforedrag",{event:t,handler:this}),!this.el.event().defaultPrevented){t.preventDefault(),t.stopPropagation(),this.parent=this.parent||this.el.parent(SVG.Nested)||this.el.parent(SVG.Doc),this.p=this.parent.node.createSVGPoint(),this.m=this.el.node.getScreenCTM().inverse();var i,a=this.getBBox();if(this.el instanceof SVG.Text)switch(i=this.el.node.getComputedTextLength(),this.el.attr("text-anchor")){case"middle":i/=2;break;case"start":i=0}this.startPoints={point:this.transformPoint(t,i),box:a,transform:this.el.transform()},SVG.on(window,"mousemove.drag",(function(t){e.drag(t)})),SVG.on(window,"touchmove.drag",(function(t){e.drag(t)})),SVG.on(window,"mouseup.drag",(function(t){e.end(t)})),SVG.on(window,"touchend.drag",(function(t){e.end(t)})),this.el.fire("dragstart",{event:t,p:this.startPoints.point,m:this.m,handler:this})}}},t.prototype.drag=function(t){var e=this.getBBox(),i=this.transformPoint(t),a=this.startPoints.box.x+i.x-this.startPoints.point.x,s=this.startPoints.box.y+i.y-this.startPoints.point.y,r=this.constraint,o=i.x-this.startPoints.point.x,n=i.y-this.startPoints.point.y;if(this.el.fire("dragmove",{event:t,p:i,m:this.m,handler:this}),this.el.event().defaultPrevented)return i;if("function"==typeof r){var l=r.call(this.el,a,s,this.m);"boolean"==typeof l&&(l={x:l,y:l}),!0===l.x?this.el.x(a):!1!==l.x&&this.el.x(l.x),!0===l.y?this.el.y(s):!1!==l.y&&this.el.y(l.y)}else"object"==typeof r&&(null!=r.minX&&a<r.minX?o=(a=r.minX)-this.startPoints.box.x:null!=r.maxX&&a>r.maxX-e.width&&(o=(a=r.maxX-e.width)-this.startPoints.box.x),null!=r.minY&&s<r.minY?n=(s=r.minY)-this.startPoints.box.y:null!=r.maxY&&s>r.maxY-e.height&&(n=(s=r.maxY-e.height)-this.startPoints.box.y),null!=r.snapToGrid&&(a-=a%r.snapToGrid,s-=s%r.snapToGrid,o-=o%r.snapToGrid,n-=n%r.snapToGrid),this.el instanceof SVG.G?this.el.matrix(this.startPoints.transform).transform({x:o,y:n},!0):this.el.move(a,s));return i},t.prototype.end=function(t){var e=this.drag(t);this.el.fire("dragend",{event:t,p:e,m:this.m,handler:this}),SVG.off(window,"mousemove.drag"),SVG.off(window,"touchmove.drag"),SVG.off(window,"mouseup.drag"),SVG.off(window,"touchend.drag")},SVG.extend(SVG.Element,{draggable:function(e,i){"function"!=typeof e&&"object"!=typeof e||(i=e,e=!0);var a=this.remember("_draggable")||new t(this);return(e=void 0===e||e)?a.init(i||{},e):(this.off("mousedown.drag"),this.off("touchstart.drag")),this}})}.call(void 0),function(){function t(t){this.el=t,t.remember("_selectHandler",this),this.pointSelection={isSelected:!1},this.rectSelection={isSelected:!1},this.pointsList={lt:[0,0],rt:["width",0],rb:["width","height"],lb:[0,"height"],t:["width",0],r:["width","height"],b:["width","height"],l:[0,"height"]},this.pointCoord=function(t,e,i){var a="string"!=typeof t?t:e[t];return i?a/2:a},this.pointCoords=function(t,e){var i=this.pointsList[t];return{x:this.pointCoord(i[0],e,"t"===t||"b"===t),y:this.pointCoord(i[1],e,"r"===t||"l"===t)}}}t.prototype.init=function(t,e){var i=this.el.bbox();this.options={};var a=this.el.selectize.defaults.points;for(var s in this.el.selectize.defaults)this.options[s]=this.el.selectize.defaults[s],void 0!==e[s]&&(this.options[s]=e[s]);var r=["points","pointsExclude"];for(var s in r){var o=this.options[r[s]];"string"==typeof o?o=o.length>0?o.split(/\s*,\s*/i):[]:"boolean"==typeof o&&"points"===r[s]&&(o=o?a:[]),this.options[r[s]]=o}this.options.points=[a,this.options.points].reduce((function(t,e){return t.filter((function(t){return e.indexOf(t)>-1}))})),this.options.points=[this.options.points,this.options.pointsExclude].reduce((function(t,e){return t.filter((function(t){return e.indexOf(t)<0}))})),this.parent=this.el.parent(),this.nested=this.nested||this.parent.group(),this.nested.matrix(new SVG.Matrix(this.el).translate(i.x,i.y)),this.options.deepSelect&&-1!==["line","polyline","polygon"].indexOf(this.el.type)?this.selectPoints(t):this.selectRect(t),this.observe(),this.cleanup()},t.prototype.selectPoints=function(t){return this.pointSelection.isSelected=t,this.pointSelection.set||(this.pointSelection.set=this.parent.set(),this.drawPoints()),this},t.prototype.getPointArray=function(){var t=this.el.bbox();return this.el.array().valueOf().map((function(e){return[e[0]-t.x,e[1]-t.y]}))},t.prototype.drawPoints=function(){for(var t=this,e=this.getPointArray(),i=0,a=e.length;i<a;++i){var s=function(e){return function(i){(i=i||window.event).preventDefault?i.preventDefault():i.returnValue=!1,i.stopPropagation();var a=i.pageX||i.touches[0].pageX,s=i.pageY||i.touches[0].pageY;t.el.fire("point",{x:a,y:s,i:e,event:i})}}(i),r=this.drawPoint(e[i][0],e[i][1]).addClass(this.options.classPoints).addClass(this.options.classPoints+"_point").on("touchstart",s).on("mousedown",s);this.pointSelection.set.add(r)}},t.prototype.drawPoint=function(t,e){var i=this.options.pointType;switch(i){case"circle":return this.drawCircle(t,e);case"rect":return this.drawRect(t,e);default:if("function"==typeof i)return i.call(this,t,e);throw new Error("Unknown "+i+" point type!")}},t.prototype.drawCircle=function(t,e){return this.nested.circle(this.options.pointSize).center(t,e)},t.prototype.drawRect=function(t,e){return this.nested.rect(this.options.pointSize,this.options.pointSize).center(t,e)},t.prototype.updatePointSelection=function(){var t=this.getPointArray();this.pointSelection.set.each((function(e){this.cx()===t[e][0]&&this.cy()===t[e][1]||this.center(t[e][0],t[e][1])}))},t.prototype.updateRectSelection=function(){var t=this,e=this.el.bbox();if(this.rectSelection.set.get(0).attr({width:e.width,height:e.height}),this.options.points.length&&this.options.points.map((function(i,a){var s=t.pointCoords(i,e);t.rectSelection.set.get(a+1).center(s.x,s.y)})),this.options.rotationPoint){var i=this.rectSelection.set.length();this.rectSelection.set.get(i-1).center(e.width/2,20)}},t.prototype.selectRect=function(t){var e=this,i=this.el.bbox();function a(t){return function(i){(i=i||window.event).preventDefault?i.preventDefault():i.returnValue=!1,i.stopPropagation();var a=i.pageX||i.touches[0].pageX,s=i.pageY||i.touches[0].pageY;e.el.fire(t,{x:a,y:s,event:i})}}if(this.rectSelection.isSelected=t,this.rectSelection.set=this.rectSelection.set||this.parent.set(),this.rectSelection.set.get(0)||this.rectSelection.set.add(this.nested.rect(i.width,i.height).addClass(this.options.classRect)),this.options.points.length&&this.rectSelection.set.length()<2){this.options.points.map((function(t,s){var r=e.pointCoords(t,i),o=e.drawPoint(r.x,r.y).attr("class",e.options.classPoints+"_"+t).on("mousedown",a(t)).on("touchstart",a(t));e.rectSelection.set.add(o)})),this.rectSelection.set.each((function(){this.addClass(e.options.classPoints)}))}if(this.options.rotationPoint&&(this.options.points&&!this.rectSelection.set.get(9)||!this.options.points&&!this.rectSelection.set.get(1))){var s=function(t){(t=t||window.event).preventDefault?t.preventDefault():t.returnValue=!1,t.stopPropagation();var i=t.pageX||t.touches[0].pageX,a=t.pageY||t.touches[0].pageY;e.el.fire("rot",{x:i,y:a,event:t})},r=this.drawPoint(i.width/2,20).attr("class",this.options.classPoints+"_rot").on("touchstart",s).on("mousedown",s);this.rectSelection.set.add(r)}},t.prototype.handler=function(){var t=this.el.bbox();this.nested.matrix(new SVG.Matrix(this.el).translate(t.x,t.y)),this.rectSelection.isSelected&&this.updateRectSelection(),this.pointSelection.isSelected&&this.updatePointSelection()},t.prototype.observe=function(){var t=this;if(MutationObserver)if(this.rectSelection.isSelected||this.pointSelection.isSelected)this.observerInst=this.observerInst||new MutationObserver((function(){t.handler()})),this.observerInst.observe(this.el.node,{attributes:!0});else try{this.observerInst.disconnect(),delete this.observerInst}catch(t){}else this.el.off("DOMAttrModified.select"),(this.rectSelection.isSelected||this.pointSelection.isSelected)&&this.el.on("DOMAttrModified.select",(function(){t.handler()}))},t.prototype.cleanup=function(){!this.rectSelection.isSelected&&this.rectSelection.set&&(this.rectSelection.set.each((function(){this.remove()})),this.rectSelection.set.clear(),delete this.rectSelection.set),!this.pointSelection.isSelected&&this.pointSelection.set&&(this.pointSelection.set.each((function(){this.remove()})),this.pointSelection.set.clear(),delete this.pointSelection.set),this.pointSelection.isSelected||this.rectSelection.isSelected||(this.nested.remove(),delete this.nested)},SVG.extend(SVG.Element,{selectize:function(e,i){return"object"==typeof e&&(i=e,e=!0),(this.remember("_selectHandler")||new t(this)).init(void 0===e||e,i||{}),this}}),SVG.Element.prototype.selectize.defaults={points:["lt","rt","rb","lb","t","r","b","l"],pointsExclude:[],classRect:"svg_select_boundingRect",classPoints:"svg_select_points",pointSize:7,rotationPoint:!0,deepSelect:!1,pointType:"circle"}}(),function(){(function(){function t(t){t.remember("_resizeHandler",this),this.el=t,this.parameters={},this.lastUpdateCall=null,this.p=t.doc().node.createSVGPoint()}t.prototype.transformPoint=function(t,e,i){return this.p.x=t-(this.offset.x-window.pageXOffset),this.p.y=e-(this.offset.y-window.pageYOffset),this.p.matrixTransform(i||this.m)},t.prototype._extractPosition=function(t){return{x:null!=t.clientX?t.clientX:t.touches[0].clientX,y:null!=t.clientY?t.clientY:t.touches[0].clientY}},t.prototype.init=function(t){var e=this;if(this.stop(),"stop"!==t){for(var i in this.options={},this.el.resize.defaults)this.options[i]=this.el.resize.defaults[i],void 0!==t[i]&&(this.options[i]=t[i]);this.el.on("lt.resize",(function(t){e.resize(t||window.event)})),this.el.on("rt.resize",(function(t){e.resize(t||window.event)})),this.el.on("rb.resize",(function(t){e.resize(t||window.event)})),this.el.on("lb.resize",(function(t){e.resize(t||window.event)})),this.el.on("t.resize",(function(t){e.resize(t||window.event)})),this.el.on("r.resize",(function(t){e.resize(t||window.event)})),this.el.on("b.resize",(function(t){e.resize(t||window.event)})),this.el.on("l.resize",(function(t){e.resize(t||window.event)})),this.el.on("rot.resize",(function(t){e.resize(t||window.event)})),this.el.on("point.resize",(function(t){e.resize(t||window.event)})),this.update()}},t.prototype.stop=function(){return this.el.off("lt.resize"),this.el.off("rt.resize"),this.el.off("rb.resize"),this.el.off("lb.resize"),this.el.off("t.resize"),this.el.off("r.resize"),this.el.off("b.resize"),this.el.off("l.resize"),this.el.off("rot.resize"),this.el.off("point.resize"),this},t.prototype.resize=function(t){var e=this;this.m=this.el.node.getScreenCTM().inverse(),this.offset={x:window.pageXOffset,y:window.pageYOffset};var i=this._extractPosition(t.detail.event);if(this.parameters={type:this.el.type,p:this.transformPoint(i.x,i.y),x:t.detail.x,y:t.detail.y,box:this.el.bbox(),rotation:this.el.transform().rotation},"text"===this.el.type&&(this.parameters.fontSize=this.el.attr()["font-size"]),void 0!==t.detail.i){var a=this.el.array().valueOf();this.parameters.i=t.detail.i,this.parameters.pointCoords=[a[t.detail.i][0],a[t.detail.i][1]]}switch(t.type){case"lt":this.calc=function(t,e){var i=this.snapToGrid(t,e);if(this.parameters.box.width-i[0]>0&&this.parameters.box.height-i[1]>0){if("text"===this.parameters.type)return this.el.move(this.parameters.box.x+i[0],this.parameters.box.y),void this.el.attr("font-size",this.parameters.fontSize-i[0]);i=this.checkAspectRatio(i),this.el.move(this.parameters.box.x+i[0],this.parameters.box.y+i[1]).size(this.parameters.box.width-i[0],this.parameters.box.height-i[1])}};break;case"rt":this.calc=function(t,e){var i=this.snapToGrid(t,e,2);if(this.parameters.box.width+i[0]>0&&this.parameters.box.height-i[1]>0){if("text"===this.parameters.type)return this.el.move(this.parameters.box.x-i[0],this.parameters.box.y),void this.el.attr("font-size",this.parameters.fontSize+i[0]);i=this.checkAspectRatio(i,!0),this.el.move(this.parameters.box.x,this.parameters.box.y+i[1]).size(this.parameters.box.width+i[0],this.parameters.box.height-i[1])}};break;case"rb":this.calc=function(t,e){var i=this.snapToGrid(t,e,0);if(this.parameters.box.width+i[0]>0&&this.parameters.box.height+i[1]>0){if("text"===this.parameters.type)return this.el.move(this.parameters.box.x-i[0],this.parameters.box.y),void this.el.attr("font-size",this.parameters.fontSize+i[0]);i=this.checkAspectRatio(i),this.el.move(this.parameters.box.x,this.parameters.box.y).size(this.parameters.box.width+i[0],this.parameters.box.height+i[1])}};break;case"lb":this.calc=function(t,e){var i=this.snapToGrid(t,e,1);if(this.parameters.box.width-i[0]>0&&this.parameters.box.height+i[1]>0){if("text"===this.parameters.type)return this.el.move(this.parameters.box.x+i[0],this.parameters.box.y),void this.el.attr("font-size",this.parameters.fontSize-i[0]);i=this.checkAspectRatio(i,!0),this.el.move(this.parameters.box.x+i[0],this.parameters.box.y).size(this.parameters.box.width-i[0],this.parameters.box.height+i[1])}};break;case"t":this.calc=function(t,e){var i=this.snapToGrid(t,e,2);if(this.parameters.box.height-i[1]>0){if("text"===this.parameters.type)return;this.el.move(this.parameters.box.x,this.parameters.box.y+i[1]).height(this.parameters.box.height-i[1])}};break;case"r":this.calc=function(t,e){var i=this.snapToGrid(t,e,0);if(this.parameters.box.width+i[0]>0){if("text"===this.parameters.type)return;this.el.move(this.parameters.box.x,this.parameters.box.y).width(this.parameters.box.width+i[0])}};break;case"b":this.calc=function(t,e){var i=this.snapToGrid(t,e,0);if(this.parameters.box.height+i[1]>0){if("text"===this.parameters.type)return;this.el.move(this.parameters.box.x,this.parameters.box.y).height(this.parameters.box.height+i[1])}};break;case"l":this.calc=function(t,e){var i=this.snapToGrid(t,e,1);if(this.parameters.box.width-i[0]>0){if("text"===this.parameters.type)return;this.el.move(this.parameters.box.x+i[0],this.parameters.box.y).width(this.parameters.box.width-i[0])}};break;case"rot":this.calc=function(t,e){var i=t+this.parameters.p.x,a=e+this.parameters.p.y,s=Math.atan2(this.parameters.p.y-this.parameters.box.y-this.parameters.box.height/2,this.parameters.p.x-this.parameters.box.x-this.parameters.box.width/2),r=Math.atan2(a-this.parameters.box.y-this.parameters.box.height/2,i-this.parameters.box.x-this.parameters.box.width/2),o=this.parameters.rotation+180*(r-s)/Math.PI+this.options.snapToAngle/2;this.el.center(this.parameters.box.cx,this.parameters.box.cy).rotate(o-o%this.options.snapToAngle,this.parameters.box.cx,this.parameters.box.cy)};break;case"point":this.calc=function(t,e){var i=this.snapToGrid(t,e,this.parameters.pointCoords[0],this.parameters.pointCoords[1]),a=this.el.array().valueOf();a[this.parameters.i][0]=this.parameters.pointCoords[0]+i[0],a[this.parameters.i][1]=this.parameters.pointCoords[1]+i[1],this.el.plot(a)}}this.el.fire("resizestart",{dx:this.parameters.x,dy:this.parameters.y,event:t}),SVG.on(window,"touchmove.resize",(function(t){e.update(t||window.event)})),SVG.on(window,"touchend.resize",(function(){e.done()})),SVG.on(window,"mousemove.resize",(function(t){e.update(t||window.event)})),SVG.on(window,"mouseup.resize",(function(){e.done()}))},t.prototype.update=function(t){if(t){var e=this._extractPosition(t),i=this.transformPoint(e.x,e.y),a=i.x-this.parameters.p.x,s=i.y-this.parameters.p.y;this.lastUpdateCall=[a,s],this.calc(a,s),this.el.fire("resizing",{dx:a,dy:s,event:t})}else this.lastUpdateCall&&this.calc(this.lastUpdateCall[0],this.lastUpdateCall[1])},t.prototype.done=function(){this.lastUpdateCall=null,SVG.off(window,"mousemove.resize"),SVG.off(window,"mouseup.resize"),SVG.off(window,"touchmove.resize"),SVG.off(window,"touchend.resize"),this.el.fire("resizedone")},t.prototype.snapToGrid=function(t,e,i,a){var s;return void 0!==a?s=[(i+t)%this.options.snapToGrid,(a+e)%this.options.snapToGrid]:(i=null==i?3:i,s=[(this.parameters.box.x+t+(1&i?0:this.parameters.box.width))%this.options.snapToGrid,(this.parameters.box.y+e+(2&i?0:this.parameters.box.height))%this.options.snapToGrid]),t<0&&(s[0]-=this.options.snapToGrid),e<0&&(s[1]-=this.options.snapToGrid),t-=Math.abs(s[0])<this.options.snapToGrid/2?s[0]:s[0]-(t<0?-this.options.snapToGrid:this.options.snapToGrid),e-=Math.abs(s[1])<this.options.snapToGrid/2?s[1]:s[1]-(e<0?-this.options.snapToGrid:this.options.snapToGrid),this.constraintToBox(t,e,i,a)},t.prototype.constraintToBox=function(t,e,i,a){var s,r,o=this.options.constraint||{};return void 0!==a?(s=i,r=a):(s=this.parameters.box.x+(1&i?0:this.parameters.box.width),r=this.parameters.box.y+(2&i?0:this.parameters.box.height)),void 0!==o.minX&&s+t<o.minX&&(t=o.minX-s),void 0!==o.maxX&&s+t>o.maxX&&(t=o.maxX-s),void 0!==o.minY&&r+e<o.minY&&(e=o.minY-r),void 0!==o.maxY&&r+e>o.maxY&&(e=o.maxY-r),[t,e]},t.prototype.checkAspectRatio=function(t,e){if(!this.options.saveAspectRatio)return t;var i=t.slice(),a=this.parameters.box.width/this.parameters.box.height,s=this.parameters.box.width+t[0],r=this.parameters.box.height-t[1],o=s/r;return o<a?(i[1]=s/a-this.parameters.box.height,e&&(i[1]=-i[1])):o>a&&(i[0]=this.parameters.box.width-r*a,e&&(i[0]=-i[0])),i},SVG.extend(SVG.Element,{resize:function(e){return(this.remember("_resizeHandler")||new t(this)).init(e||{}),this}}),SVG.Element.prototype.resize.defaults={snapToAngle:.1,snapToGrid:1,constraint:{},saveAspectRatio:!1}}).call(this)}(),void 0===window.Apex&&(window.Apex={});var Ht=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"initModules",value:function(){this.ctx.publicMethods=["updateOptions","updateSeries","appendData","appendSeries","toggleSeries","showSeries","hideSeries","setLocale","resetSeries","zoomX","toggleDataPointSelection","dataURI","exportToCSV","addXaxisAnnotation","addYaxisAnnotation","addPointAnnotation","clearAnnotations","removeAnnotation","paper","destroy"],this.ctx.eventList=["click","mousedown","mousemove","mouseleave","touchstart","touchmove","touchleave","mouseup","touchend"],this.ctx.animations=new b(this.ctx),this.ctx.axes=new J(this.ctx),this.ctx.core=new Rt(this.ctx.el,this.ctx),this.ctx.config=new E({}),this.ctx.data=new W(this.ctx),this.ctx.grid=new j(this.ctx),this.ctx.graphics=new m(this.ctx),this.ctx.coreUtils=new y(this.ctx),this.ctx.crosshairs=new Q(this.ctx),this.ctx.events=new Z(this.ctx),this.ctx.exports=new G(this.ctx),this.ctx.localization=new $(this.ctx),this.ctx.options=new L,this.ctx.responsive=new K(this.ctx),this.ctx.series=new N(this.ctx),this.ctx.theme=new tt(this.ctx),this.ctx.formatters=new M(this.ctx),this.ctx.titleSubtitle=new et(this.ctx),this.ctx.legend=new lt(this.ctx),this.ctx.toolbar=new ht(this.ctx),this.ctx.tooltip=new bt(this.ctx),this.ctx.dimensions=new ot(this.ctx),this.ctx.updateHelpers=new Dt(this.ctx),this.ctx.zoomPanSelection=new ct(this.ctx),this.ctx.w.globals.tooltip=new bt(this.ctx)}}]),t}(),Ot=function(){function t(e){a(this,t),this.ctx=e,this.w=e.w}return r(t,[{key:"clear",value:function(t){var e=t.isUpdating;this.ctx.zoomPanSelection&&this.ctx.zoomPanSelection.destroy(),this.ctx.toolbar&&this.ctx.toolbar.destroy(),this.ctx.animations=null,this.ctx.axes=null,this.ctx.annotations=null,this.ctx.core=null,this.ctx.data=null,this.ctx.grid=null,this.ctx.series=null,this.ctx.responsive=null,this.ctx.theme=null,this.ctx.formatters=null,this.ctx.titleSubtitle=null,this.ctx.legend=null,this.ctx.dimensions=null,this.ctx.options=null,this.ctx.crosshairs=null,this.ctx.zoomPanSelection=null,this.ctx.updateHelpers=null,this.ctx.toolbar=null,this.ctx.localization=null,this.ctx.w.globals.tooltip=null,this.clearDomElements({isUpdating:e})}},{key:"killSVG",value:function(t){t.each((function(t,e){this.removeClass("*"),this.off(),this.stop()}),!0),t.ungroup(),t.clear()}},{key:"clearDomElements",value:function(t){var e=this,i=t.isUpdating,a=this.w.globals.dom.Paper.node;a.parentNode&&a.parentNode.parentNode&&!i&&(a.parentNode.parentNode.style.minHeight="unset");var s=this.w.globals.dom.baseEl;s&&this.ctx.eventList.forEach((function(t){s.removeEventListener(t,e.ctx.events.documentEvent)}));var r=this.w.globals.dom;if(null!==this.ctx.el)for(;this.ctx.el.firstChild;)this.ctx.el.removeChild(this.ctx.el.firstChild);this.killSVG(r.Paper),r.Paper.remove(),r.elWrap=null,r.elGraphical=null,r.elAnnotations=null,r.elLegendWrap=null,r.baseEl=null,r.elGridRect=null,r.elGridRectMask=null,r.elGridRectMarkerMask=null,r.elForecastMask=null,r.elNonForecastMask=null,r.elDefs=null}}]),t}(),Nt=new WeakMap;var Wt=function(){function t(e,i){a(this,t),this.opts=i,this.ctx=this,this.w=new F(i).init(),this.el=e,this.w.globals.cuid=x.randomId(),this.w.globals.chartID=this.w.config.chart.id?x.escapeString(this.w.config.chart.id):this.w.globals.cuid,new Ht(this).initModules(),this.create=x.bind(this.create,this),this.windowResizeHandler=this._windowResizeHandler.bind(this),this.parentResizeHandler=this._parentResizeCallback.bind(this)}return r(t,[{key:"render",value:function(){var t=this;return new Promise((function(e,i){if(null!==t.el){void 0===Apex._chartInstances&&(Apex._chartInstances=[]),t.w.config.chart.id&&Apex._chartInstances.push({id:t.w.globals.chartID,group:t.w.config.chart.group,chart:t}),t.setLocale(t.w.config.chart.defaultLocale);var a=t.w.config.chart.events.beforeMount;if("function"==typeof a&&a(t,t.w),t.events.fireEvent("beforeMount",[t,t.w]),window.addEventListener("resize",t.windowResizeHandler),function(t,e){var i=!1;if(t.nodeType!==Node.DOCUMENT_FRAGMENT_NODE){var a=t.getBoundingClientRect();"none"!==t.style.display&&0!==a.width||(i=!0)}var s=new ResizeObserver((function(a){i&&e.call(t,a),i=!0}));t.nodeType===Node.DOCUMENT_FRAGMENT_NODE?Array.from(t.children).forEach((function(t){return s.observe(t)})):s.observe(t),Nt.set(e,s)}(t.el.parentNode,t.parentResizeHandler),!t.css){var s=t.el.getRootNode&&t.el.getRootNode(),r=x.is("ShadowRoot",s),o=t.el.ownerDocument,n=o.getElementById("apexcharts-css");!r&&n||(t.css=document.createElement("style"),t.css.id="apexcharts-css",t.css.textContent='@keyframes opaque {\n  0% {\n      opacity: 0\n  }\n\n  to {\n      opacity: 1\n  }\n}\n\n@keyframes resizeanim {\n  0%,to {\n      opacity: 0\n  }\n}\n\n.apexcharts-canvas {\n  position: relative;\n  user-select: none\n}\n\n.apexcharts-canvas ::-webkit-scrollbar {\n  -webkit-appearance: none;\n  width: 6px\n}\n\n.apexcharts-canvas ::-webkit-scrollbar-thumb {\n  border-radius: 4px;\n  background-color: rgba(0,0,0,.5);\n  box-shadow: 0 0 1px rgba(255,255,255,.5);\n  -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5)\n}\n\n.apexcharts-inner {\n  position: relative\n}\n\n.apexcharts-text tspan {\n  font-family: inherit\n}\n\n.legend-mouseover-inactive {\n  transition: .15s ease all;\n  opacity: .2\n}\n\n.apexcharts-legend-text {\n  padding-left: 15px;\n  margin-left: -15px;\n}\n\n.apexcharts-series-collapsed {\n  opacity: 0\n}\n\n.apexcharts-tooltip {\n  border-radius: 5px;\n  box-shadow: 2px 2px 6px -4px #999;\n  cursor: default;\n  font-size: 14px;\n  left: 62px;\n  opacity: 0;\n  pointer-events: none;\n  position: absolute;\n  top: 20px;\n  display: flex;\n  flex-direction: column;\n  overflow: hidden;\n  white-space: nowrap;\n  z-index: 12;\n  transition: .15s ease all\n}\n\n.apexcharts-tooltip.apexcharts-active {\n  opacity: 1;\n  transition: .15s ease all\n}\n\n.apexcharts-tooltip.apexcharts-theme-light {\n  border: 1px solid #e3e3e3;\n  background: rgba(255,255,255,.96)\n}\n\n.apexcharts-tooltip.apexcharts-theme-dark {\n  color: #fff;\n  background: rgba(30,30,30,.8)\n}\n\n.apexcharts-tooltip * {\n  font-family: inherit\n}\n\n.apexcharts-tooltip-title {\n  padding: 6px;\n  font-size: 15px;\n  margin-bottom: 4px\n}\n\n.apexcharts-tooltip.apexcharts-theme-light .apexcharts-tooltip-title {\n  background: #eceff1;\n  border-bottom: 1px solid #ddd\n}\n\n.apexcharts-tooltip.apexcharts-theme-dark .apexcharts-tooltip-title {\n  background: rgba(0,0,0,.7);\n  border-bottom: 1px solid #333\n}\n\n.apexcharts-tooltip-text-goals-value,.apexcharts-tooltip-text-y-value,.apexcharts-tooltip-text-z-value {\n  display: inline-block;\n  margin-left: 5px;\n  font-weight: 600\n}\n\n.apexcharts-tooltip-text-goals-label:empty,.apexcharts-tooltip-text-goals-value:empty,.apexcharts-tooltip-text-y-label:empty,.apexcharts-tooltip-text-y-value:empty,.apexcharts-tooltip-text-z-value:empty,.apexcharts-tooltip-title:empty {\n  display: none\n}\n\n.apexcharts-tooltip-text-goals-label,.apexcharts-tooltip-text-goals-value {\n  padding: 6px 0 5px\n}\n\n.apexcharts-tooltip-goals-group,.apexcharts-tooltip-text-goals-label,.apexcharts-tooltip-text-goals-value {\n  display: flex\n}\n\n.apexcharts-tooltip-text-goals-label:not(:empty),.apexcharts-tooltip-text-goals-value:not(:empty) {\n  margin-top: -6px\n}\n\n.apexcharts-tooltip-marker {\n  width: 12px;\n  height: 12px;\n  position: relative;\n  top: 0;\n  margin-right: 10px;\n  border-radius: 50%\n}\n\n.apexcharts-tooltip-series-group {\n  padding: 0 10px;\n  display: none;\n  text-align: left;\n  justify-content: left;\n  align-items: center\n}\n\n.apexcharts-tooltip-series-group.apexcharts-active .apexcharts-tooltip-marker {\n  opacity: 1\n}\n\n.apexcharts-tooltip-series-group.apexcharts-active,.apexcharts-tooltip-series-group:last-child {\n  padding-bottom: 4px\n}\n\n.apexcharts-tooltip-series-group-hidden {\n  opacity: 0;\n  height: 0;\n  line-height: 0;\n  padding: 0!important\n}\n\n.apexcharts-tooltip-y-group {\n  padding: 6px 0 5px\n}\n\n.apexcharts-custom-tooltip,.apexcharts-tooltip-box {\n  padding: 4px 8px\n}\n\n.apexcharts-tooltip-boxPlot {\n  display: flex;\n  flex-direction: column-reverse\n}\n\n.apexcharts-tooltip-box>div {\n  margin: 4px 0\n}\n\n.apexcharts-tooltip-box span.value {\n  font-weight: 700\n}\n\n.apexcharts-tooltip-rangebar {\n  padding: 5px 8px\n}\n\n.apexcharts-tooltip-rangebar .category {\n  font-weight: 600;\n  color: #777\n}\n\n.apexcharts-tooltip-rangebar .series-name {\n  font-weight: 700;\n  display: block;\n  margin-bottom: 5px\n}\n\n.apexcharts-xaxistooltip,.apexcharts-yaxistooltip {\n  opacity: 0;\n  pointer-events: none;\n  color: #373d3f;\n  font-size: 13px;\n  text-align: center;\n  border-radius: 2px;\n  position: absolute;\n  z-index: 10;\n  background: #eceff1;\n  border: 1px solid #90a4ae\n}\n\n.apexcharts-xaxistooltip {\n  padding: 9px 10px;\n  transition: .15s ease all\n}\n\n.apexcharts-xaxistooltip.apexcharts-theme-dark {\n  background: rgba(0,0,0,.7);\n  border: 1px solid rgba(0,0,0,.5);\n  color: #fff\n}\n\n.apexcharts-xaxistooltip:after,.apexcharts-xaxistooltip:before {\n  left: 50%;\n  border: solid transparent;\n  content: " ";\n  height: 0;\n  width: 0;\n  position: absolute;\n  pointer-events: none\n}\n\n.apexcharts-xaxistooltip:after {\n  border-color: transparent;\n  border-width: 6px;\n  margin-left: -6px\n}\n\n.apexcharts-xaxistooltip:before {\n  border-color: transparent;\n  border-width: 7px;\n  margin-left: -7px\n}\n\n.apexcharts-xaxistooltip-bottom:after,.apexcharts-xaxistooltip-bottom:before {\n  bottom: 100%\n}\n\n.apexcharts-xaxistooltip-top:after,.apexcharts-xaxistooltip-top:before {\n  top: 100%\n}\n\n.apexcharts-xaxistooltip-bottom:after {\n  border-bottom-color: #eceff1\n}\n\n.apexcharts-xaxistooltip-bottom:before {\n  border-bottom-color: #90a4ae\n}\n\n.apexcharts-xaxistooltip-bottom.apexcharts-theme-dark:after,.apexcharts-xaxistooltip-bottom.apexcharts-theme-dark:before {\n  border-bottom-color: rgba(0,0,0,.5)\n}\n\n.apexcharts-xaxistooltip-top:after {\n  border-top-color: #eceff1\n}\n\n.apexcharts-xaxistooltip-top:before {\n  border-top-color: #90a4ae\n}\n\n.apexcharts-xaxistooltip-top.apexcharts-theme-dark:after,.apexcharts-xaxistooltip-top.apexcharts-theme-dark:before {\n  border-top-color: rgba(0,0,0,.5)\n}\n\n.apexcharts-xaxistooltip.apexcharts-active {\n  opacity: 1;\n  transition: .15s ease all\n}\n\n.apexcharts-yaxistooltip {\n  padding: 4px 10px\n}\n\n.apexcharts-yaxistooltip.apexcharts-theme-dark {\n  background: rgba(0,0,0,.7);\n  border: 1px solid rgba(0,0,0,.5);\n  color: #fff\n}\n\n.apexcharts-yaxistooltip:after,.apexcharts-yaxistooltip:before {\n  top: 50%;\n  border: solid transparent;\n  content: " ";\n  height: 0;\n  width: 0;\n  position: absolute;\n  pointer-events: none\n}\n\n.apexcharts-yaxistooltip:after {\n  border-color: transparent;\n  border-width: 6px;\n  margin-top: -6px\n}\n\n.apexcharts-yaxistooltip:before {\n  border-color: transparent;\n  border-width: 7px;\n  margin-top: -7px\n}\n\n.apexcharts-yaxistooltip-left:after,.apexcharts-yaxistooltip-left:before {\n  left: 100%\n}\n\n.apexcharts-yaxistooltip-right:after,.apexcharts-yaxistooltip-right:before {\n  right: 100%\n}\n\n.apexcharts-yaxistooltip-left:after {\n  border-left-color: #eceff1\n}\n\n.apexcharts-yaxistooltip-left:before {\n  border-left-color: #90a4ae\n}\n\n.apexcharts-yaxistooltip-left.apexcharts-theme-dark:after,.apexcharts-yaxistooltip-left.apexcharts-theme-dark:before {\n  border-left-color: rgba(0,0,0,.5)\n}\n\n.apexcharts-yaxistooltip-right:after {\n  border-right-color: #eceff1\n}\n\n.apexcharts-yaxistooltip-right:before {\n  border-right-color: #90a4ae\n}\n\n.apexcharts-yaxistooltip-right.apexcharts-theme-dark:after,.apexcharts-yaxistooltip-right.apexcharts-theme-dark:before {\n  border-right-color: rgba(0,0,0,.5)\n}\n\n.apexcharts-yaxistooltip.apexcharts-active {\n  opacity: 1\n}\n\n.apexcharts-yaxistooltip-hidden {\n  display: none\n}\n\n.apexcharts-xcrosshairs,.apexcharts-ycrosshairs {\n  pointer-events: none;\n  opacity: 0;\n  transition: .15s ease all\n}\n\n.apexcharts-xcrosshairs.apexcharts-active,.apexcharts-ycrosshairs.apexcharts-active {\n  opacity: 1;\n  transition: .15s ease all\n}\n\n.apexcharts-ycrosshairs-hidden {\n  opacity: 0\n}\n\n.apexcharts-selection-rect {\n  cursor: move\n}\n\n.svg_select_boundingRect,.svg_select_points_rot {\n  pointer-events: none;\n  opacity: 0;\n  visibility: hidden\n}\n\n.apexcharts-selection-rect+g .svg_select_boundingRect,.apexcharts-selection-rect+g .svg_select_points_rot {\n  opacity: 0;\n  visibility: hidden\n}\n\n.apexcharts-selection-rect+g .svg_select_points_l,.apexcharts-selection-rect+g .svg_select_points_r {\n  cursor: ew-resize;\n  opacity: 1;\n  visibility: visible\n}\n\n.svg_select_points {\n  fill: #efefef;\n  stroke: #333;\n  rx: 2\n}\n\n.apexcharts-svg.apexcharts-zoomable.hovering-zoom {\n  cursor: crosshair\n}\n\n.apexcharts-svg.apexcharts-zoomable.hovering-pan {\n  cursor: move\n}\n\n.apexcharts-menu-icon,.apexcharts-pan-icon,.apexcharts-reset-icon,.apexcharts-selection-icon,.apexcharts-toolbar-custom-icon,.apexcharts-zoom-icon,.apexcharts-zoomin-icon,.apexcharts-zoomout-icon {\n  cursor: pointer;\n  width: 20px;\n  height: 20px;\n  line-height: 24px;\n  color: #6e8192;\n  text-align: center\n}\n\n.apexcharts-menu-icon svg,.apexcharts-reset-icon svg,.apexcharts-zoom-icon svg,.apexcharts-zoomin-icon svg,.apexcharts-zoomout-icon svg {\n  fill: #6e8192\n}\n\n.apexcharts-selection-icon svg {\n  fill: #444;\n  transform: scale(.76)\n}\n\n.apexcharts-theme-dark .apexcharts-menu-icon svg,.apexcharts-theme-dark .apexcharts-pan-icon svg,.apexcharts-theme-dark .apexcharts-reset-icon svg,.apexcharts-theme-dark .apexcharts-selection-icon svg,.apexcharts-theme-dark .apexcharts-toolbar-custom-icon svg,.apexcharts-theme-dark .apexcharts-zoom-icon svg,.apexcharts-theme-dark .apexcharts-zoomin-icon svg,.apexcharts-theme-dark .apexcharts-zoomout-icon svg {\n  fill: #f3f4f5\n}\n\n.apexcharts-canvas .apexcharts-reset-zoom-icon.apexcharts-selected svg,.apexcharts-canvas .apexcharts-selection-icon.apexcharts-selected svg,.apexcharts-canvas .apexcharts-zoom-icon.apexcharts-selected svg {\n  fill: #008ffb\n}\n\n.apexcharts-theme-light .apexcharts-menu-icon:hover svg,.apexcharts-theme-light .apexcharts-reset-icon:hover svg,.apexcharts-theme-light .apexcharts-selection-icon:not(.apexcharts-selected):hover svg,.apexcharts-theme-light .apexcharts-zoom-icon:not(.apexcharts-selected):hover svg,.apexcharts-theme-light .apexcharts-zoomin-icon:hover svg,.apexcharts-theme-light .apexcharts-zoomout-icon:hover svg {\n  fill: #333\n}\n\n.apexcharts-menu-icon,.apexcharts-selection-icon {\n  position: relative\n}\n\n.apexcharts-reset-icon {\n  margin-left: 5px\n}\n\n.apexcharts-menu-icon,.apexcharts-reset-icon,.apexcharts-zoom-icon {\n  transform: scale(.85)\n}\n\n.apexcharts-zoomin-icon,.apexcharts-zoomout-icon {\n  transform: scale(.7)\n}\n\n.apexcharts-zoomout-icon {\n  margin-right: 3px\n}\n\n.apexcharts-pan-icon {\n  transform: scale(.62);\n  position: relative;\n  left: 1px;\n  top: 0\n}\n\n.apexcharts-pan-icon svg {\n  fill: #fff;\n  stroke: #6e8192;\n  stroke-width: 2\n}\n\n.apexcharts-pan-icon.apexcharts-selected svg {\n  stroke: #008ffb\n}\n\n.apexcharts-pan-icon:not(.apexcharts-selected):hover svg {\n  stroke: #333\n}\n\n.apexcharts-toolbar {\n  position: absolute;\n  z-index: 11;\n  max-width: 176px;\n  text-align: right;\n  border-radius: 3px;\n  padding: 0 6px 2px;\n  display: flex;\n  justify-content: space-between;\n  align-items: center\n}\n\n.apexcharts-menu {\n  background: #fff;\n  position: absolute;\n  top: 100%;\n  border: 1px solid #ddd;\n  border-radius: 3px;\n  padding: 3px;\n  right: 10px;\n  opacity: 0;\n  min-width: 110px;\n  transition: .15s ease all;\n  pointer-events: none\n}\n\n.apexcharts-menu.apexcharts-menu-open {\n  opacity: 1;\n  pointer-events: all;\n  transition: .15s ease all\n}\n\n.apexcharts-menu-item {\n  padding: 6px 7px;\n  font-size: 12px;\n  cursor: pointer\n}\n\n.apexcharts-theme-light .apexcharts-menu-item:hover {\n  background: #eee\n}\n\n.apexcharts-theme-dark .apexcharts-menu {\n  background: rgba(0,0,0,.7);\n  color: #fff\n}\n\n@media screen and (min-width:768px) {\n  .apexcharts-canvas:hover .apexcharts-toolbar {\n      opacity: 1\n  }\n}\n\n.apexcharts-canvas .apexcharts-element-hidden,.apexcharts-datalabel.apexcharts-element-hidden,.apexcharts-hide .apexcharts-series-points {\n  opacity: 0\n}\n\n.apexcharts-datalabel,.apexcharts-datalabel-label,.apexcharts-datalabel-value,.apexcharts-datalabels,.apexcharts-pie-label {\n  cursor: default;\n  pointer-events: none\n}\n\n.apexcharts-pie-label-delay {\n  opacity: 0;\n  animation-name: opaque;\n  animation-duration: .3s;\n  animation-fill-mode: forwards;\n  animation-timing-function: ease\n}\n\n.apexcharts-legend {\t\n  display: flex;\t\n  overflow: auto;\t\n  padding: 0 10px;\t\n}\t\n.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {\t\n  flex-wrap: wrap\t\n}\t\n.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {\t\n  flex-direction: column;\t\n  bottom: 0;\t\n}\t\n.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {\t\n  justify-content: flex-start;\t\n}\t\n.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {\t\n  justify-content: center;  \t\n}\t\n.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {\t\n  justify-content: flex-end;\t\n}\t\n.apexcharts-legend-series {\t\n  cursor: pointer;\t\n  line-height: normal;\t\n}\t\n.apexcharts-legend.apx-legend-position-bottom .apexcharts-legend-series, .apexcharts-legend.apx-legend-position-top .apexcharts-legend-series{\t\n  display: flex;\t\n  align-items: center;\t\n}\t\n.apexcharts-legend-text {\t\n  position: relative;\t\n  font-size: 14px;\t\n}\t\n.apexcharts-legend-text *, .apexcharts-legend-marker * {\t\n  pointer-events: none;\t\n}\t\n.apexcharts-legend-marker {\t\n  position: relative;\t\n  display: inline-block;\t\n  cursor: pointer;\t\n  margin-right: 3px;\t\n  border-style: solid;\n}\t\n  \n.apexcharts-legend.apexcharts-align-right .apexcharts-legend-series, .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series{\t\n  display: inline-block;\t\n}\t\n.apexcharts-legend-series.apexcharts-no-click {\t\n  cursor: auto;\t\n}\t\n.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {\t\n  display: none !important;\t\n}\t\n.apexcharts-inactive-legend {\t\n  opacity: 0.45;\t\n}\n\n.apexcharts-annotation-rect,.apexcharts-area-series .apexcharts-area,.apexcharts-area-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events,.apexcharts-gridline,.apexcharts-line,.apexcharts-line-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events,.apexcharts-point-annotation-label,.apexcharts-radar-series path,.apexcharts-radar-series polygon,.apexcharts-toolbar svg,.apexcharts-tooltip .apexcharts-marker,.apexcharts-xaxis-annotation-label,.apexcharts-yaxis-annotation-label,.apexcharts-zoom-rect {\n  pointer-events: none\n}\n\n.apexcharts-marker {\n  transition: .15s ease all\n}\n\n.resize-triggers {\n  animation: 1ms resizeanim;\n  visibility: hidden;\n  opacity: 0;\n  height: 100%;\n  width: 100%;\n  overflow: hidden\n}\n\n.contract-trigger:before,.resize-triggers,.resize-triggers>div {\n  content: " ";\n  display: block;\n  position: absolute;\n  top: 0;\n  left: 0\n}\n\n.resize-triggers>div {\n  height: 100%;\n  width: 100%;\n  background: #eee;\n  overflow: auto\n}\n\n.contract-trigger:before {\n  overflow: hidden;\n  width: 200%;\n  height: 200%\n}\n',r?s.prepend(t.css):o.head.appendChild(t.css))}var l=t.create(t.w.config.series,{});if(!l)return e(t);t.mount(l).then((function(){"function"==typeof t.w.config.chart.events.mounted&&t.w.config.chart.events.mounted(t,t.w),t.events.fireEvent("mounted",[t,t.w]),e(l)})).catch((function(t){i(t)}))}else i(new Error("Element not found"))}))}},{key:"create",value:function(t,e){var i=this.w;new Ht(this).initModules();var a=this.w.globals;(a.noData=!1,a.animationEnded=!1,this.responsive.checkResponsiveConfig(e),i.config.xaxis.convertedCatToNumeric)&&new X(i.config).convertCatToNumericXaxis(i.config,this.ctx);if(null===this.el)return a.animationEnded=!0,null;if(this.core.setupElements(),"treemap"===i.config.chart.type&&(i.config.grid.show=!1,i.config.yaxis[0].show=!1),0===a.svgWidth)return a.animationEnded=!0,null;var s=y.checkComboSeries(t);a.comboCharts=s.comboCharts,a.comboBarCount=s.comboBarCount;var r=t.every((function(t){return t.data&&0===t.data.length}));(0===t.length||r)&&this.series.handleNoData(),this.events.setupEventHandlers(),this.data.parseData(t),this.theme.init(),new D(this).setGlobalMarkerSize(),this.formatters.setLabelFormatters(),this.titleSubtitle.draw(),a.noData&&a.collapsedSeries.length!==a.series.length&&!i.config.legend.showForSingleSeries||this.legend.init(),this.series.hasAllSeriesEqualX(),a.axisCharts&&(this.core.coreCalculations(),"category"!==i.config.xaxis.type&&this.formatters.setLabelFormatters(),this.ctx.toolbar.minX=i.globals.minX,this.ctx.toolbar.maxX=i.globals.maxX),this.formatters.heatmapLabelFormatters(),new y(this).getLargestMarkerSize(),this.dimensions.plotCoords();var o=this.core.xySettings();this.grid.createGridMask();var n=this.core.plotChartType(t,o),l=new O(this);l.bringForward(),i.config.dataLabels.background.enabled&&l.dataLabelsBackground(),this.core.shiftGraphPosition();var h={plot:{left:i.globals.translateX,top:i.globals.translateY,width:i.globals.gridWidth,height:i.globals.gridHeight}};return{elGraph:n,xyRatios:o,elInner:i.globals.dom.elGraphical,dimensions:h}}},{key:"mount",value:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,i=this,a=i.w;return new Promise((function(s,r){if(null===i.el)return r(new Error("Not enough data to display or target element not found"));(null===e||a.globals.allSeriesCollapsed)&&i.series.handleNoData(),i.grid=new j(i);var o=i.grid.drawGrid();if(i.annotations=new P(i),i.annotations.drawImageAnnos(),i.annotations.drawTextAnnos(),"back"===a.config.grid.position&&o&&(a.globals.dom.elGraphical.add(o.el),o&&o.elGridBorders&&o.elGridBorders.node&&a.globals.dom.elGraphical.add(o.elGridBorders)),Array.isArray(e.elGraph))for(var n=0;n<e.elGraph.length;n++)a.globals.dom.elGraphical.add(e.elGraph[n]);else a.globals.dom.elGraphical.add(e.elGraph);"front"===a.config.grid.position&&o&&(a.globals.dom.elGraphical.add(o.el),o&&o.elGridBorders&&o.elGridBorders.node&&a.globals.dom.elGraphical.add(o.elGridBorders)),"front"===a.config.xaxis.crosshairs.position&&i.crosshairs.drawXCrosshairs(),"front"===a.config.yaxis[0].crosshairs.position&&i.crosshairs.drawYCrosshairs(),"treemap"!==a.config.chart.type&&i.axes.drawAxis(a.config.chart.type,o);var l=new V(t.ctx,o),h=new q(t.ctx,o);if(null!==o&&(l.xAxisLabelCorrections(o.xAxisTickWidth),h.setYAxisTextAlignments(),a.config.yaxis.map((function(t,e){-1===a.globals.ignoreYAxisIndexes.indexOf(e)&&h.yAxisTitleRotate(e,t.opposite)}))),a.globals.dom.Paper.add(a.globals.dom.elAnnotations),i.annotations.drawAxesAnnotations(),!a.globals.noData){if(a.config.tooltip.enabled&&!a.globals.noData&&i.w.globals.tooltip.drawTooltip(e.xyRatios),a.globals.axisCharts&&(a.globals.isXNumeric||a.config.xaxis.convertedCatToNumeric||a.globals.isRangeBar))(a.config.chart.zoom.enabled||a.config.chart.selection&&a.config.chart.selection.enabled||a.config.chart.pan&&a.config.chart.pan.enabled)&&i.zoomPanSelection.init({xyRatios:e.xyRatios});else{var c=a.config.chart.toolbar.tools;["zoom","zoomin","zoomout","selection","pan","reset"].forEach((function(t){c[t]=!1}))}a.config.chart.toolbar.show&&!a.globals.allSeriesCollapsed&&i.toolbar.createToolbar()}a.globals.memory.methodsToExec.length>0&&a.globals.memory.methodsToExec.forEach((function(t){t.method(t.params,!1,t.context)})),a.globals.axisCharts||a.globals.noData||i.core.resizeNonAxisCharts(),s(i)}))}},{key:"destroy",value:function(){var t,e;window.removeEventListener("resize",this.windowResizeHandler),this.el.parentNode,t=this.parentResizeHandler,(e=Nt.get(t))&&(e.disconnect(),Nt.delete(t));var i=this.w.config.chart.id;i&&Apex._chartInstances.forEach((function(t,e){t.id===x.escapeString(i)&&Apex._chartInstances.splice(e,1)})),new Ot(this.ctx).clear({isUpdating:!1})}},{key:"updateOptions",value:function(t){var e=this,i=arguments.length>1&&void 0!==arguments[1]&&arguments[1],a=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],s=!(arguments.length>3&&void 0!==arguments[3])||arguments[3],r=!(arguments.length>4&&void 0!==arguments[4])||arguments[4],o=this.w;return o.globals.selection=void 0,t.series&&(this.series.resetSeries(!1,!0,!1),t.series.length&&t.series[0].data&&(t.series=t.series.map((function(t,i){return e.updateHelpers._extendSeries(t,i)}))),this.updateHelpers.revertDefaultAxisMinMax()),t.xaxis&&(t=this.updateHelpers.forceXAxisUpdate(t)),t.yaxis&&(t=this.updateHelpers.forceYAxisUpdate(t)),o.globals.collapsedSeriesIndices.length>0&&this.series.clearPreviousPaths(),t.theme&&(t=this.theme.updateThemeOptions(t)),this.updateHelpers._updateOptions(t,i,a,s,r)}},{key:"updateSeries",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];return this.series.resetSeries(!1),this.updateHelpers.revertDefaultAxisMinMax(),this.updateHelpers._updateSeries(t,e,i)}},{key:"appendSeries",value:function(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=!(arguments.length>2&&void 0!==arguments[2])||arguments[2],a=this.w.config.series.slice();return a.push(t),this.series.resetSeries(!1),this.updateHelpers.revertDefaultAxisMinMax(),this.updateHelpers._updateSeries(a,e,i)}},{key:"appendData",value:function(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=this;i.w.globals.dataChanged=!0,i.series.getPreviousPaths();for(var a=i.w.config.series.slice(),s=0;s<a.length;s++)if(null!==t[s]&&void 0!==t[s])for(var r=0;r<t[s].data.length;r++)a[s].data.push(t[s].data[r]);return i.w.config.series=a,e&&(i.w.globals.initialSeries=x.clone(i.w.config.series)),this.update()}},{key:"update",value:function(t){var e=this;return new Promise((function(i,a){new Ot(e.ctx).clear({isUpdating:!0});var s=e.create(e.w.config.series,t);if(!s)return i(e);e.mount(s).then((function(){"function"==typeof e.w.config.chart.events.updated&&e.w.config.chart.events.updated(e,e.w),e.events.fireEvent("updated",[e,e.w]),e.w.globals.isDirty=!0,i(e)})).catch((function(t){a(t)}))}))}},{key:"getSyncedCharts",value:function(){var t=this.getGroupedCharts(),e=[this];return t.length&&(e=[],t.forEach((function(t){e.push(t)}))),e}},{key:"getGroupedCharts",value:function(){var t=this;return Apex._chartInstances.filter((function(t){if(t.group)return!0})).map((function(e){return t.w.config.chart.group===e.group?e.chart:t}))}},{key:"toggleSeries",value:function(t){return this.series.toggleSeries(t)}},{key:"highlightSeriesOnLegendHover",value:function(t,e){return this.series.toggleSeriesOnHover(t,e)}},{key:"showSeries",value:function(t){this.series.showSeries(t)}},{key:"hideSeries",value:function(t){this.series.hideSeries(t)}},{key:"resetSeries",value:function(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];this.series.resetSeries(t,e)}},{key:"addEventListener",value:function(t,e){this.events.addEventListener(t,e)}},{key:"removeEventListener",value:function(t,e){this.events.removeEventListener(t,e)}},{key:"addXaxisAnnotation",value:function(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:void 0,a=this;i&&(a=i),a.annotations.addXaxisAnnotationExternal(t,e,a)}},{key:"addYaxisAnnotation",value:function(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:void 0,a=this;i&&(a=i),a.annotations.addYaxisAnnotationExternal(t,e,a)}},{key:"addPointAnnotation",value:function(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:void 0,a=this;i&&(a=i),a.annotations.addPointAnnotationExternal(t,e,a)}},{key:"clearAnnotations",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:void 0,e=this;t&&(e=t),e.annotations.clearAnnotations(e)}},{key:"removeAnnotation",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:void 0,i=this;e&&(i=e),i.annotations.removeAnnotation(i,t)}},{key:"getChartArea",value:function(){return this.w.globals.dom.baseEl.querySelector(".apexcharts-inner")}},{key:"getSeriesTotalXRange",value:function(t,e){return this.coreUtils.getSeriesTotalsXRange(t,e)}},{key:"getHighestValueInSeries",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=new U(this.ctx);return e.getMinYMaxY(t).highestY}},{key:"getLowestValueInSeries",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=new U(this.ctx);return e.getMinYMaxY(t).lowestY}},{key:"getSeriesTotal",value:function(){return this.w.globals.seriesTotals}},{key:"toggleDataPointSelection",value:function(t,e){return this.updateHelpers.toggleDataPointSelection(t,e)}},{key:"zoomX",value:function(t,e){this.ctx.toolbar.zoomUpdateOptions(t,e)}},{key:"setLocale",value:function(t){this.localization.setCurrentLocaleValues(t)}},{key:"dataURI",value:function(t){return new G(this.ctx).dataURI(t)}},{key:"exportToCSV",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=new G(this.ctx);return e.exportToCSV(t)}},{key:"paper",value:function(){return this.w.globals.dom.Paper}},{key:"_parentResizeCallback",value:function(){this.w.globals.animationEnded&&this.w.config.chart.redrawOnParentResize&&this._windowResize()}},{key:"_windowResize",value:function(){var t=this;clearTimeout(this.w.globals.resizeTimer),this.w.globals.resizeTimer=window.setTimeout((function(){t.w.globals.resized=!0,t.w.globals.dataChanged=!1,t.ctx.update()}),150)}},{key:"_windowResizeHandler",value:function(){var t=this.w.config.chart.redrawOnWindowResize;"function"==typeof t&&(t=t()),t&&this._windowResize()}}],[{key:"getChartByID",value:function(t){var e=x.escapeString(t),i=Apex._chartInstances.filter((function(t){return t.id===e}))[0];return i&&i.chart}},{key:"initOnLoad",value:function(){for(var e=document.querySelectorAll("[data-apexcharts]"),i=0;i<e.length;i++){new t(e[i],JSON.parse(e[i].getAttribute("data-options"))).render()}}},{key:"exec",value:function(t,e){var i=this.getChartByID(t);if(i){i.w.globals.isExecCalled=!0;var a=null;if(-1!==i.publicMethods.indexOf(e)){for(var s=arguments.length,r=new Array(s>2?s-2:0),o=2;o<s;o++)r[o-2]=arguments[o];a=i[e].apply(i,r)}return a}}},{key:"merge",value:function(t,e){return x.extend(t,e)}}]),t}();module.exports=Wt;


/***/ }),

/***/ "./includes/blocks/src/commons/dummy-reports/index.js":
/*!************************************************************!*\
  !*** ./includes/blocks/src/commons/dummy-reports/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



class DummyReports extends (react__WEBPACK_IMPORTED_MODULE_1___default().Component) {
  constructor(props) {
    super(props);
    this.image = undefined != props.image_path ? props.image_path : '#';
    this.url = undefined != props.url ? props.url : 'https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=learner-reports&utm_campaign=Learner-Reports&utm_term=learner-reports#pricing';
  }
  render() {
    let dummyContent = '';
    let upgrade_button = '';
    let or_txt = '';
    if (wisdm_ld_reports_common_script_data.is_admin_user) {
      upgrade_button = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
        className: "wrld-upgrade-btn",
        target: "__blank",
        href: this.url
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Upgrade to PRO', 'learndash-reports-by-wisdmlabs')));
      or_txt = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('OR', 'learndash-reports-by-wisdmlabs'));
    }
    dummyContent = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-chart-block wrld-dummy-images"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-time-spent-on-a-course graph-card-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wrld-upgrade-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wrld-upgrade-content"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Available in WISDM Reports PRO', 'learndash-reports-by-wisdmlabs')), upgrade_button, " ", or_txt, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      className: "wrld-learn-more",
      target: "__blank",
      href: this.url
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Learn More', 'learndash-reports-by-wisdmlabs'))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/' + this.image
    })));
    return dummyContent;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (DummyReports);

/***/ }),

/***/ "./includes/blocks/src/commons/filters/index.js":
/*!******************************************************!*\
  !*** ./includes/blocks/src/commons/filters/index.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_3__);




class WisdmFilters extends (react__WEBPACK_IMPORTED_MODULE_1___default().Component) {
  constructor(props) {
    super(props);
    if (undefined != props.request_data) {
      this.category = undefined != props.request_data.category ? props.request_data.category : '';
      this.group = undefined != props.request_data.group ? props.request_data.group : '';
      this.course_name = undefined != props.request_data.course ? props.request_data.course : '';
      this.lesson_name = undefined != props.request_data.lesson ? props.request_data.lesson : '';
      this.topic_name = undefined != props.request_data.topic ? props.request_data.topic : '';
      this.learner_name = undefined != props.request_data.learner ? props.request_data.learner : '';
      this.start_date = undefined != props.request_data.start_date ? props.request_data.start_date : '';
      this.end_date = undefined != props.request_data.end_date ? props.request_data.end_date : '';
    }
  }
  getElement(label, value) {
    let html = '';
    if (undefined == value || '' == value) {
      return html;
    }
    html = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-filter-item"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/complete.png'
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, value), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wdm-tooltip"
    }, label, ": ", value));
    return html;
  }
  getDuration(label, start_date, end_date) {
    let html = '';
    if (undefined == start_date || '' == start_date || undefined == end_date || '' == end_date) {
      return html;
    }
    html = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-filter-item"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/complete.png'
    }), moment__WEBPACK_IMPORTED_MODULE_3___default().unix(start_date).format("MMM, DD YYYY"), " - ", moment__WEBPACK_IMPORTED_MODULE_3___default().unix(end_date).format("MMM, DD YYYY"));
    return html;
  }
  render() {
    let filterData = '';
    if ('' == this.category && '' == this.group && '' == this.start_date && '' == this.end_date && '' == this.course_name && '' == this.learner_name) {
      filterData = '';
    } else {
      let category = this.getElement('Category', this.category);
      let group = this.getElement('Group', this.group);
      let course = this.getElement('Course', this.course_name);
      let lesson = this.getElement('Lesson', this.lesson_name);
      let topic = this.getElement('Topic', this.topic_name);
      let learner = this.getElement('Learner', this.learner_name);
      let duration = this.getDuration('Duration', this.start_date, this.end_date);
      filterData = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-applied-filters"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Filters', 'learndash-reports-by-wisdmlabs')), category, group, course, lesson, topic, learner, duration);
    }
    return filterData;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (WisdmFilters);

/***/ }),

/***/ "./includes/blocks/src/commons/loader/index.js":
/*!*****************************************************!*\
  !*** ./includes/blocks/src/commons/loader/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



class WisdmLoader extends (react__WEBPACK_IMPORTED_MODULE_1___default().Component) {
  constructor(props) {
    super(props);
  }
  render() {
    let loadingData = '';
    let show_text = '';
    if (true == this.props.text) {
      show_text = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "supporting-text"
      }, "Your report is being generated.");
    }
    loadingData = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-chart-block"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-revenue-from-courses graph-card-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-graph-loading"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/loader.svg'
    }), show_text)));
    return loadingData;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (WisdmLoader);

/***/ }),

/***/ "./includes/blocks/src/course-list/index-course-list.js":
/*!**************************************************************!*\
  !*** ./includes/blocks/src/course-list/index-course-list.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/course-list/index.scss");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react_table__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react-table */ "./node_modules/react-table/index.js");
/* harmony import */ var react_table__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_table__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var _commons_dummy_reports_index_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../commons/dummy-reports/index.js */ "./includes/blocks/src/commons/dummy-reports/index.js");
/* harmony import */ var _commons_filters_index_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../commons/filters/index.js */ "./includes/blocks/src/commons/filters/index.js");
/* harmony import */ var react_csv__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! react-csv */ "./node_modules/react-csv/index.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_11__);












function Table(_ref) {
  let {
    columns,
    data
  } = _ref;
  // Use the state and functions returned from useTable to build your UI
  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    prepareRow,
    page,
    // Instead of using 'rows', we'll use page,
    // which has only the rows for the active page

    // The rest of these things are super handy, too ;)
    canPreviousPage,
    canNextPage,
    pageOptions,
    pageCount,
    gotoPage,
    nextPage,
    previousPage,
    setPageSize,
    state: {
      pageIndex,
      pageSize
    }
  } = (0,react_table__WEBPACK_IMPORTED_MODULE_6__.useTable)({
    columns,
    data,
    initialState: {
      pageIndex: 0
    }
  }, react_table__WEBPACK_IMPORTED_MODULE_6__.usePagination);

  //tooltip message configuration 
  let tooltip_text = "";
  let icon_enabled = false;
  const time_tracking_enabled = wisdm_learndash_reports_front_end_script_course_list.is_idle_tracking_enabled;
  if (wisdm_learndash_reports_front_end_script_course_list.is_admin_user) {
    //If current user is admin
    if (wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active) {
      //need time tracking module setting
      if (time_tracking_enabled == 'on') {
        //Checking weather time tracking is enabled or not
        tooltip_text = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Idle Time Configured , Activated on ', 'learndash-reports-by-wisdmlabs') + wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from + '. ', " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("a", {
          href: wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('View Idle Time Configuration Log', 'learndash-reports-by-wisdmlabs')), " ");
        icon_enabled = true;
      } else {
        tooltip_text = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
          class: "tooltip_container"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('\"Idle Time\" not configured. Configure the Settings from here ', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("a", {
          href: wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url,
          class: "tooltip_button"
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Time Tracking Setting', 'learndash-reports-by-wisdmlabs')));
      }
    } else {
      tooltip_text = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "tooltip_container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('\"Idle Time\" not configured. This is available in the PRO version of the plugin', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("a", {
        href: wisdm_learndash_reports_front_end_script_report_filters.upgrade_link,
        target: "_blank",
        class: "tooltip_button"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Upgrade To PRO', 'learndash-reports-by-wisdmlabs')));
    }
  } else {
    //For non-admin users group leader , instructor
    if (wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active) {
      //need time tracking module setting
      if (time_tracking_enabled) {
        tooltip_text = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Idle Time Configured , Activated on ', 'learndash-reports-by-wisdmlabs') + wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from;
        icon_enabled = true;
      } else {
        tooltip_text = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Idle Time Not Configured', 'learndash-reports-by-wisdmlabs');
        ;
      }
    } else {
      console.log("Pro version is not active");
    }
  }

  // Render the UI for your table
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "course-reports-wrapper"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "course-table-wrap"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("table", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
    class: "course-list-table"
  }, getTableProps()), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("thead", null, headerGroups.map(headerGroup => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tr", headerGroup.getHeaderGroupProps(), headerGroup.headers.map(column => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("th", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, column.getHeaderProps(), {
    className: column.className
  }), column.render('Header'), column.toolTip && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    class: "cl_tooltip"
  }, icon_enabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
    src: wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time_tracking_active.png'
  }), !icon_enabled && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
    src: wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time-tracking-disabled.png'
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
    class: "cl_tooltiptext wdm-tooltip"
  }, tooltip_text, " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    class: "hover_helper"
  })))))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tbody", getTableBodyProps(), page.map((row, i) => {
    prepareRow(row);
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tr", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
      class: "course-list-table-data-row"
    }, row.getRowProps()), row.cells.map(cell => {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("td", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
        className: cell.column.className
      }, cell.getCellProps()), cell.render("Cell"));
    }));
  })))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
    className: "table-pagination"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
    onClick: () => gotoPage(0),
    disabled: !canPreviousPage
  }, "<<"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
    onClick: () => previousPage(),
    disabled: !canPreviousPage
  }, "<"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Page', 'learndash-reports-by-wisdmlabs') + " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("strong", null, pageIndex + 1, "  ", ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Of', 'learndash-reports-by-wisdmlabs') + ' ', "  ", pageOptions.length), " "), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
    onClick: () => nextPage(),
    disabled: !canNextPage
  }, ">"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
    onClick: () => gotoPage(pageCount - 1),
    disabled: !canNextPage
  }, ">>"), " ")));
}
class CourseList extends react__WEBPACK_IMPORTED_MODULE_4__.Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoaded: false,
      isProVersion: false,
      start_date: moment__WEBPACK_IMPORTED_MODULE_11___default()(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date: moment__WEBPACK_IMPORTED_MODULE_11___default()(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      reportTypeInUse: wisdm_learndash_reports_front_end_script_course_list.report_type,
      error: null,
      request_data: null,
      course_report_type: null,
      show_supporting_text: false
    };
    this.applyFilters = this.applyFilters.bind(this);
    this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
    this.getExcelFile = this.getExcelFile.bind(this);
    this.durationUpdated = this.durationUpdated.bind(this);
    this.showDummyImages = this.showDummyImages.bind(this);
  }
  componentDidMount() {
    let start_date = this.state.start_date;
    let end_date = this.state.end_date;
    let request_url = '/rp/v1/course-list-info/?start_date=' + start_date + '&end_date=' + end_date;
    this.getCourseListStateData(request_url);
    document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
    document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
    document.addEventListener('duration_updated', this.durationUpdated);
    document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
  }
  durationUpdated(event) {
    this.setState({
      isLoaded: false,
      start_date: event.detail.startDate,
      end_date: event.detail.endDate
    });
    let requestUrl = '/rp/v1/course-list-info/';
    if ('duration_updated' == event.type) {
      requestUrl = '/rp/v1/course-list-info/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
    }
    if (window.globalfilters != undefined) {
      let category = window.globalfilters.detail.selected_categories;
      let group = window.globalfilters.detail.selected_groups;
      let course = window.globalfilters.detail.selected_courses;
      let lesson = window.globalfilters.detail.selected_lessons;
      let topic = window.globalfilters.detail.selected_topics;
      let learner = window.globalfilters.detail.selected_learners;
      requestUrl = requestUrl + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
    }
    this.getCourseListStateData(requestUrl);
  }
  handleReportTypeChange(event) {
    this.setState({
      reportTypeInUse: event.detail.active_reports_tab
    });
    if ('quiz-reports' == event.detail.active_reports_tab) {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-list', false);
    } else {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-list', true);
    }
  }
  showDummyImages(event) {
    this.setState({
      course_report_type: event.detail.report_type
    });
  }
  applyFilters(event) {
    let start_date = this.state.start_date;
    let end_date = this.state.end_date;
    let category = event.detail.selected_categories;
    let group = event.detail.selected_groups;
    let course = event.detail.selected_courses;
    let lesson = event.detail.selected_lessons;
    let topic = event.detail.selected_topics;
    let learner = event.detail.selected_learners;
    let request_url = '/rp/v1/course-list-info/?start_date=' + start_date + '&end_date=' + end_date + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
    if (undefined != course) {
      this.setState({
        show_supporting_text: true
      });
    } else {
      this.setState({
        show_supporting_text: false
      });
    }
    this.getCourseListStateData(request_url);
  }
  getTableHeadersByType(response) {
    let headers = [];
    let table_header_names = {
      id: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('ID', 'learndash-reports-by-wisdmlabs'),
      name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Name', 'learndash-reports-by-wisdmlabs'),
      email: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Email ID', 'learndash-reports-by-wisdmlabs'),
      status: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Status', 'learndash-reports-by-wisdmlabs'),
      steps: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Steps Completed', 'learndash-reports-by-wisdmlabs'),
      date: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completion Date', 'learndash-reports-by-wisdmlabs'),
      time: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Time spent', 'learndash-reports-by-wisdmlabs'),
      total_spent_time: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total Time Spent', 'learndash-reports-by-wisdmlabs'),
      category: wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Category', 'learndash-reports-by-wisdmlabs'),
      course: wisdm_reports_get_ld_custom_lebel_if_avaiable('Course'),
      groups: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Groups', 'learndash-reports-by-wisdmlabs'),
      user_name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Learner', 'learndash-reports-by-wisdmlabs'),
      instructors: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Instructor', 'learndash-reports-by-wisdmlabs'),
      students: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of Students', 'learndash-reports-by-wisdmlabs'),
      start_date: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Start Date', 'learndash-reports-by-wisdmlabs'),
      started: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Enrolled On', 'learndash-reports-by-wisdmlabs'),
      end_date: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('End Date', 'learndash-reports-by-wisdmlabs'),
      completed: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completion Date', 'learndash-reports-by-wisdmlabs'),
      completion_rate: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completion %', 'learndash-reports-by-wisdmlabs'),
      completion_rate2: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('% Completion', 'learndash-reports-by-wisdmlabs'),
      completed_users: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completed Learners', 'learndash-reports-by-wisdmlabs'),
      in_progress: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('In Progress', 'learndash-reports-by-wisdmlabs'),
      not_started: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Not Started', 'learndash-reports-by-wisdmlabs'),
      lesson: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Lesson', 'learndash-reports-by-wisdmlabs'),
      course_progress: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completion %', 'learndash-reports-by-wisdmlabs'),
      quizzes: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes'),
      quiz_count: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes'),
      quiz_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Title', 'learndash-reports-by-wisdmlabs'),
      total_attempts: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total Attempts', 'learndash-reports-by-wisdmlabs'),
      attempts: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Attempts', 'learndash-reports-by-wisdmlabs'),
      pass_rate: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Pass %', 'learndash-reports-by-wisdmlabs'),
      avg_score: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Avg', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Score', 'learndash-reports-by-wisdmlabs'),
      pass_count: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of Quizzes Pass', 'learndash-reports-by-wisdmlabs'),
      fail_count: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of Quizzes Fail', 'learndash-reports-by-wisdmlabs'),
      time_spent: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Time Spent', 'learndash-reports-by-wisdmlabs'),
      total_time_spent: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total Time Spent', 'learndash-reports-by-wisdmlabs'),
      avg_total_time_spent: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Avg. Total Time Spent', 'learndash-reports-by-wisdmlabs'),
      course_completion_time: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completion Time', 'learndash-reports-by-wisdmlabs'),
      avg_time_spent: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Avg. Completion Time', 'learndash-reports-by-wisdmlabs'),
      quiz_attendant_count: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of Students Completed Quiz', 'learndash-reports-by-wisdmlabs'),
      quiz_attendant_count: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No. Of Students Completed Quiz', 'learndash-reports-by-wisdmlabs'),
      topic_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Topic') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)(' Title', 'learndash-reports-by-wisdmlabs'),
      topic_completion_count: wisdm_reports_get_ld_custom_lebel_if_avaiable('Topic') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Completed By Students', 'learndash-reports-by-wisdmlabs'),
      quiz_time: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Time', 'learndash-reports-by-wisdmlabs'),
      quiz_attempts: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Attempts', 'learndash-reports-by-wisdmlabs')
    };
    if (null != response && response.length > 0) {
      var response_headers = Object.keys(response[0]);
      if (response_headers.length > 0) {
        for (let i = 0; i < response_headers.length; i++) {
          let name = response_headers[i];
          if (undefined == table_header_names[name]) {
            headers.push({
              "Header": name,
              "accessor": name,
              className: 'table-' + name,
              toolTip: name == 'total_time_spent' || name == 'time_spent' || name == 'time' || name == 'avg_time_spent' || name == 'course_completion_time' || name == 'avg_total_time_spent' ? true : false
            });
          } else {
            headers.push({
              "Header": table_header_names[name],
              "accessor": name,
              className: 'table-' + name,
              toolTip: name == 'total_time_spent' || name == 'time_spent' || name == 'time' || name == 'avg_time_spent' || name == 'course_completion_time' || name == 'avg_total_time_spent' ? true : false
            });
          }
        }
      }
    }
    return headers;
  }
  getCourseListStateData() {
    let request_url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '/rp/v1/course-list-info';
    this.setState({
      isLoaded: false
    });
    let self = this;
    let checkIfEmpty = function () {
      setTimeout(function () {
        if (window.callStack.length > 4) {
          checkIfEmpty();
        } else {
          window.callStack.push(request_url);
          wp.apiFetch({
            path: request_url
          }).then(response => {
            var table = response.table;
            if (undefined == response) {
              table = [];
            }
            self.setState({
              isLoaded: true,
              error: null,
              isProVersion: wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active,
              tableHeaders: self.getTableHeadersByType(response.table),
              tableData: response.table,
              request_data: response.requestData
            });
            window.callStack.pop();
          }).catch(error => {
            self.setState({
              error: error,
              graph_summary: [],
              series: [],
              isLoaded: true,
              request_data: error.data.requestData
            });
            window.callStack.pop();
          });
        }
      }, 500);
    };
    checkIfEmpty();
  }
  getExcelFile() {
    jQuery.ajax({
      type: 'POST',
      url: wisdm_learndash_reports_front_end_script_course_list.ajaxurl,
      dataType: "JSON",
      data: {
        action: 'export_course_statistics',
        report_nonce: wisdm_learndash_reports_front_end_script_course_list.report_nonce,
        tableHeaders: this.state.tableHeaders,
        tableData: JSON.stringify(this.state.tableData)
      },
      error: function (eventData) {
        if ('timeout' === eventData['status']) {
          alert('Request Timed Out');
        }
      },
      success: function (response) {
        if ('success' == response.status) {
          window.open(response.data, '_blank');
          //process download
        } else {
          alert(response.data);
        }
      },
      timeout: 60000 // sets timeout to 60 seconds
    });
  }

  render() {
    let body = '';
    if (this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active) {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_commons_dummy_reports_index_js__WEBPACK_IMPORTED_MODULE_8__["default"], {
        image_path: "dcr.png"
      });
      return body;
    }
    if ('' != this.state.reportTypeInUse && 'default-ld-reports' != this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_commons_loader_index_js__WEBPACK_IMPORTED_MODULE_7__["default"], {
        text: this.state.show_supporting_text
      });
    } else if (this.state.error) {
      // error
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "wisdm-learndash-reports-course-list table-chart-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "course-list-table-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_commons_filters_index_js__WEBPACK_IMPORTED_MODULE_9__["default"], {
        request_data: this.state.request_data
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "chart-summary error"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "error-message"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", null, this.state.error.message)))));
    } else {
      let table_data = this.state.tableData;
      let table_headers = [];
      let table_parsed_data = [];
      for (var itrator = 0; itrator < this.state.tableHeaders.length; itrator++) {
        table_headers[itrator] = this.state.tableHeaders[itrator]['Header'];
      }
      table_parsed_data.push(table_headers);
      for (let letter of table_data.values()) {
        table_parsed_data.push(Object.values(letter));
      }
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "wisdm-learndash-reports-course-list table-chart-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "course-list-table-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_commons_filters_index_js__WEBPACK_IMPORTED_MODULE_9__["default"], {
        request_data: this.state.request_data
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "course-list-table-header"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        className: "chart-title"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Detailed', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Reports', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        class: "course-list-table-download-options"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Download ', 'learndash-reports-by-wisdmlabs')), this.state.isProVersion ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(react_csv__WEBPACK_IMPORTED_MODULE_10__.CSVLink, {
        data: table_parsed_data,
        filename: "exported_table.csv",
        className: "btn btn-primary",
        target: "_blank"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
        className: "download-csv-button"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
        src: wisdm_ld_reports_common_script_data.plugin_asset_url + '/images/csv.svg'
      }))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
        disabled: true,
        className: "disabled download-csv-button",
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Available in pro version', 'learndash-reports-by-wisdmlabs')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
        src: wisdm_ld_reports_common_script_data.plugin_asset_url + '/images/csv.svg'
      })), this.state.isProVersion ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
        onClick: this.getExcelFile,
        className: "btn btn-primary",
        target: "_blank"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
        className: "download-csv-button"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
        src: wisdm_ld_reports_common_script_data.plugin_asset_url + '/images/xls.svg'
      }))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("button", {
        disabled: true,
        className: "disabled download-csv-button",
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Available in pro version', 'learndash-reports-by-wisdmlabs')
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("img", {
        src: wisdm_ld_reports_common_script_data.plugin_asset_url + '/images/xls.svg'
      })))), this.state.tableHeaders.length > 0 ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Table, {
        columns: this.state.tableHeaders,
        data: this.state.tableData
      }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
        className: "error-message"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No Data Found', 'learndash-reports-by-wisdmlabs'))))));
    }
    return body;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (CourseList);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-course-list');
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_4___default().createElement(CourseList), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/course-list/editor.scss":
/*!*****************************************************!*\
  !*** ./includes/blocks/src/course-list/editor.scss ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/course-list/index.scss":
/*!****************************************************!*\
  !*** ./includes/blocks/src/course-list/index.scss ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/object-assign/index.js":
/*!*********************************************!*\
  !*** ./node_modules/object-assign/index.js ***!
  \*********************************************/
/***/ (function(module) {

"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/


/* eslint-disable no-unused-vars */
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var hasOwnProperty = Object.prototype.hasOwnProperty;
var propIsEnumerable = Object.prototype.propertyIsEnumerable;

function toObject(val) {
	if (val === null || val === undefined) {
		throw new TypeError('Object.assign cannot be called with null or undefined');
	}

	return Object(val);
}

function shouldUseNative() {
	try {
		if (!Object.assign) {
			return false;
		}

		// Detect buggy property enumeration order in older V8 versions.

		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
		test1[5] = 'de';
		if (Object.getOwnPropertyNames(test1)[0] === '5') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test2 = {};
		for (var i = 0; i < 10; i++) {
			test2['_' + String.fromCharCode(i)] = i;
		}
		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
			return test2[n];
		});
		if (order2.join('') !== '0123456789') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test3 = {};
		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
			test3[letter] = letter;
		});
		if (Object.keys(Object.assign({}, test3)).join('') !==
				'abcdefghijklmnopqrst') {
			return false;
		}

		return true;
	} catch (err) {
		// We don't expect any of the above to throw, but better to be safe.
		return false;
	}
}

module.exports = shouldUseNative() ? Object.assign : function (target, source) {
	var from;
	var to = toObject(target);
	var symbols;

	for (var s = 1; s < arguments.length; s++) {
		from = Object(arguments[s]);

		for (var key in from) {
			if (hasOwnProperty.call(from, key)) {
				to[key] = from[key];
			}
		}

		if (getOwnPropertySymbols) {
			symbols = getOwnPropertySymbols(from);
			for (var i = 0; i < symbols.length; i++) {
				if (propIsEnumerable.call(from, symbols[i])) {
					to[symbols[i]] = from[symbols[i]];
				}
			}
		}
	}

	return to;
};


/***/ }),

/***/ "./node_modules/prop-types/checkPropTypes.js":
/*!***************************************************!*\
  !*** ./node_modules/prop-types/checkPropTypes.js ***!
  \***************************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var printWarning = function() {};

if (true) {
  var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
  var loggedTypeFailures = {};
  var has = __webpack_require__(/*! ./lib/has */ "./node_modules/prop-types/lib/has.js");

  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) { /**/ }
  };
}

/**
 * Assert that the values match with the type specs.
 * Error messages are memorized and will only be shown once.
 *
 * @param {object} typeSpecs Map of name to a ReactPropType
 * @param {object} values Runtime values that need to be type-checked
 * @param {string} location e.g. "prop", "context", "child context"
 * @param {string} componentName Name of the component for error messages.
 * @param {?Function} getStack Returns the component stack.
 * @private
 */
function checkPropTypes(typeSpecs, values, location, componentName, getStack) {
  if (true) {
    for (var typeSpecName in typeSpecs) {
      if (has(typeSpecs, typeSpecName)) {
        var error;
        // Prop type validation may throw. In case they do, we don't want to
        // fail the render phase where it didn't fail before. So we log it.
        // After these have been cleaned up, we'll let them throw.
        try {
          // This is intentionally an invariant that gets caught. It's the same
          // behavior as without this statement except with a better message.
          if (typeof typeSpecs[typeSpecName] !== 'function') {
            var err = Error(
              (componentName || 'React class') + ': ' + location + ' type `' + typeSpecName + '` is invalid; ' +
              'it must be a function, usually from the `prop-types` package, but received `' + typeof typeSpecs[typeSpecName] + '`.' +
              'This often happens because of typos such as `PropTypes.function` instead of `PropTypes.func`.'
            );
            err.name = 'Invariant Violation';
            throw err;
          }
          error = typeSpecs[typeSpecName](values, typeSpecName, componentName, location, null, ReactPropTypesSecret);
        } catch (ex) {
          error = ex;
        }
        if (error && !(error instanceof Error)) {
          printWarning(
            (componentName || 'React class') + ': type specification of ' +
            location + ' `' + typeSpecName + '` is invalid; the type checker ' +
            'function must return `null` or an `Error` but returned a ' + typeof error + '. ' +
            'You may have forgotten to pass an argument to the type checker ' +
            'creator (arrayOf, instanceOf, objectOf, oneOf, oneOfType, and ' +
            'shape all require an argument).'
          );
        }
        if (error instanceof Error && !(error.message in loggedTypeFailures)) {
          // Only monitor this failure once because there tends to be a lot of the
          // same error.
          loggedTypeFailures[error.message] = true;

          var stack = getStack ? getStack() : '';

          printWarning(
            'Failed ' + location + ' type: ' + error.message + (stack != null ? stack : '')
          );
        }
      }
    }
  }
}

/**
 * Resets warning cache when testing.
 *
 * @private
 */
checkPropTypes.resetWarningCache = function() {
  if (true) {
    loggedTypeFailures = {};
  }
}

module.exports = checkPropTypes;


/***/ }),

/***/ "./node_modules/prop-types/factoryWithTypeCheckers.js":
/*!************************************************************!*\
  !*** ./node_modules/prop-types/factoryWithTypeCheckers.js ***!
  \************************************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/prop-types/node_modules/react-is/index.js");
var assign = __webpack_require__(/*! object-assign */ "./node_modules/object-assign/index.js");

var ReactPropTypesSecret = __webpack_require__(/*! ./lib/ReactPropTypesSecret */ "./node_modules/prop-types/lib/ReactPropTypesSecret.js");
var has = __webpack_require__(/*! ./lib/has */ "./node_modules/prop-types/lib/has.js");
var checkPropTypes = __webpack_require__(/*! ./checkPropTypes */ "./node_modules/prop-types/checkPropTypes.js");

var printWarning = function() {};

if (true) {
  printWarning = function(text) {
    var message = 'Warning: ' + text;
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) {}
  };
}

function emptyFunctionThatReturnsNull() {
  return null;
}

module.exports = function(isValidElement, throwOnDirectAccess) {
  /* global Symbol */
  var ITERATOR_SYMBOL = typeof Symbol === 'function' && Symbol.iterator;
  var FAUX_ITERATOR_SYMBOL = '@@iterator'; // Before Symbol spec.

  /**
   * Returns the iterator method function contained on the iterable object.
   *
   * Be sure to invoke the function with the iterable as context:
   *
   *     var iteratorFn = getIteratorFn(myIterable);
   *     if (iteratorFn) {
   *       var iterator = iteratorFn.call(myIterable);
   *       ...
   *     }
   *
   * @param {?object} maybeIterable
   * @return {?function}
   */
  function getIteratorFn(maybeIterable) {
    var iteratorFn = maybeIterable && (ITERATOR_SYMBOL && maybeIterable[ITERATOR_SYMBOL] || maybeIterable[FAUX_ITERATOR_SYMBOL]);
    if (typeof iteratorFn === 'function') {
      return iteratorFn;
    }
  }

  /**
   * Collection of methods that allow declaration and validation of props that are
   * supplied to React components. Example usage:
   *
   *   var Props = require('ReactPropTypes');
   *   var MyArticle = React.createClass({
   *     propTypes: {
   *       // An optional string prop named "description".
   *       description: Props.string,
   *
   *       // A required enum prop named "category".
   *       category: Props.oneOf(['News','Photos']).isRequired,
   *
   *       // A prop named "dialog" that requires an instance of Dialog.
   *       dialog: Props.instanceOf(Dialog).isRequired
   *     },
   *     render: function() { ... }
   *   });
   *
   * A more formal specification of how these methods are used:
   *
   *   type := array|bool|func|object|number|string|oneOf([...])|instanceOf(...)
   *   decl := ReactPropTypes.{type}(.isRequired)?
   *
   * Each and every declaration produces a function with the same signature. This
   * allows the creation of custom validation functions. For example:
   *
   *  var MyLink = React.createClass({
   *    propTypes: {
   *      // An optional string or URI prop named "href".
   *      href: function(props, propName, componentName) {
   *        var propValue = props[propName];
   *        if (propValue != null && typeof propValue !== 'string' &&
   *            !(propValue instanceof URI)) {
   *          return new Error(
   *            'Expected a string or an URI for ' + propName + ' in ' +
   *            componentName
   *          );
   *        }
   *      }
   *    },
   *    render: function() {...}
   *  });
   *
   * @internal
   */

  var ANONYMOUS = '<<anonymous>>';

  // Important!
  // Keep this list in sync with production version in `./factoryWithThrowingShims.js`.
  var ReactPropTypes = {
    array: createPrimitiveTypeChecker('array'),
    bigint: createPrimitiveTypeChecker('bigint'),
    bool: createPrimitiveTypeChecker('boolean'),
    func: createPrimitiveTypeChecker('function'),
    number: createPrimitiveTypeChecker('number'),
    object: createPrimitiveTypeChecker('object'),
    string: createPrimitiveTypeChecker('string'),
    symbol: createPrimitiveTypeChecker('symbol'),

    any: createAnyTypeChecker(),
    arrayOf: createArrayOfTypeChecker,
    element: createElementTypeChecker(),
    elementType: createElementTypeTypeChecker(),
    instanceOf: createInstanceTypeChecker,
    node: createNodeChecker(),
    objectOf: createObjectOfTypeChecker,
    oneOf: createEnumTypeChecker,
    oneOfType: createUnionTypeChecker,
    shape: createShapeTypeChecker,
    exact: createStrictShapeTypeChecker,
  };

  /**
   * inlined Object.is polyfill to avoid requiring consumers ship their own
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/is
   */
  /*eslint-disable no-self-compare*/
  function is(x, y) {
    // SameValue algorithm
    if (x === y) {
      // Steps 1-5, 7-10
      // Steps 6.b-6.e: +0 != -0
      return x !== 0 || 1 / x === 1 / y;
    } else {
      // Step 6.a: NaN == NaN
      return x !== x && y !== y;
    }
  }
  /*eslint-enable no-self-compare*/

  /**
   * We use an Error-like object for backward compatibility as people may call
   * PropTypes directly and inspect their output. However, we don't use real
   * Errors anymore. We don't inspect their stack anyway, and creating them
   * is prohibitively expensive if they are created too often, such as what
   * happens in oneOfType() for any type before the one that matched.
   */
  function PropTypeError(message, data) {
    this.message = message;
    this.data = data && typeof data === 'object' ? data: {};
    this.stack = '';
  }
  // Make `instanceof Error` still work for returned errors.
  PropTypeError.prototype = Error.prototype;

  function createChainableTypeChecker(validate) {
    if (true) {
      var manualPropTypeCallCache = {};
      var manualPropTypeWarningCount = 0;
    }
    function checkType(isRequired, props, propName, componentName, location, propFullName, secret) {
      componentName = componentName || ANONYMOUS;
      propFullName = propFullName || propName;

      if (secret !== ReactPropTypesSecret) {
        if (throwOnDirectAccess) {
          // New behavior only for users of `prop-types` package
          var err = new Error(
            'Calling PropTypes validators directly is not supported by the `prop-types` package. ' +
            'Use `PropTypes.checkPropTypes()` to call them. ' +
            'Read more at http://fb.me/use-check-prop-types'
          );
          err.name = 'Invariant Violation';
          throw err;
        } else if ( true && typeof console !== 'undefined') {
          // Old behavior for people using React.PropTypes
          var cacheKey = componentName + ':' + propName;
          if (
            !manualPropTypeCallCache[cacheKey] &&
            // Avoid spamming the console because they are often not actionable except for lib authors
            manualPropTypeWarningCount < 3
          ) {
            printWarning(
              'You are manually calling a React.PropTypes validation ' +
              'function for the `' + propFullName + '` prop on `' + componentName + '`. This is deprecated ' +
              'and will throw in the standalone `prop-types` package. ' +
              'You may be seeing this warning due to a third-party PropTypes ' +
              'library. See https://fb.me/react-warning-dont-call-proptypes ' + 'for details.'
            );
            manualPropTypeCallCache[cacheKey] = true;
            manualPropTypeWarningCount++;
          }
        }
      }
      if (props[propName] == null) {
        if (isRequired) {
          if (props[propName] === null) {
            return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required ' + ('in `' + componentName + '`, but its value is `null`.'));
          }
          return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required in ' + ('`' + componentName + '`, but its value is `undefined`.'));
        }
        return null;
      } else {
        return validate(props, propName, componentName, location, propFullName);
      }
    }

    var chainedCheckType = checkType.bind(null, false);
    chainedCheckType.isRequired = checkType.bind(null, true);

    return chainedCheckType;
  }

  function createPrimitiveTypeChecker(expectedType) {
    function validate(props, propName, componentName, location, propFullName, secret) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== expectedType) {
        // `propValue` being instance of, say, date/regexp, pass the 'object'
        // check, but we can offer a more precise error message here rather than
        // 'of type `object`'.
        var preciseType = getPreciseType(propValue);

        return new PropTypeError(
          'Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + preciseType + '` supplied to `' + componentName + '`, expected ') + ('`' + expectedType + '`.'),
          {expectedType: expectedType}
        );
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createAnyTypeChecker() {
    return createChainableTypeChecker(emptyFunctionThatReturnsNull);
  }

  function createArrayOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside arrayOf.');
      }
      var propValue = props[propName];
      if (!Array.isArray(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an array.'));
      }
      for (var i = 0; i < propValue.length; i++) {
        var error = typeChecker(propValue, i, componentName, location, propFullName + '[' + i + ']', ReactPropTypesSecret);
        if (error instanceof Error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!isValidElement(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!ReactIs.isValidElementType(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement type.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createInstanceTypeChecker(expectedClass) {
    function validate(props, propName, componentName, location, propFullName) {
      if (!(props[propName] instanceof expectedClass)) {
        var expectedClassName = expectedClass.name || ANONYMOUS;
        var actualClassName = getClassName(props[propName]);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + actualClassName + '` supplied to `' + componentName + '`, expected ') + ('instance of `' + expectedClassName + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createEnumTypeChecker(expectedValues) {
    if (!Array.isArray(expectedValues)) {
      if (true) {
        if (arguments.length > 1) {
          printWarning(
            'Invalid arguments supplied to oneOf, expected an array, got ' + arguments.length + ' arguments. ' +
            'A common mistake is to write oneOf(x, y, z) instead of oneOf([x, y, z]).'
          );
        } else {
          printWarning('Invalid argument supplied to oneOf, expected an array.');
        }
      }
      return emptyFunctionThatReturnsNull;
    }

    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      for (var i = 0; i < expectedValues.length; i++) {
        if (is(propValue, expectedValues[i])) {
          return null;
        }
      }

      var valuesString = JSON.stringify(expectedValues, function replacer(key, value) {
        var type = getPreciseType(value);
        if (type === 'symbol') {
          return String(value);
        }
        return value;
      });
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of value `' + String(propValue) + '` ' + ('supplied to `' + componentName + '`, expected one of ' + valuesString + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createObjectOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside objectOf.');
      }
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an object.'));
      }
      for (var key in propValue) {
        if (has(propValue, key)) {
          var error = typeChecker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
          if (error instanceof Error) {
            return error;
          }
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createUnionTypeChecker(arrayOfTypeCheckers) {
    if (!Array.isArray(arrayOfTypeCheckers)) {
       true ? printWarning('Invalid argument supplied to oneOfType, expected an instance of array.') : 0;
      return emptyFunctionThatReturnsNull;
    }

    for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
      var checker = arrayOfTypeCheckers[i];
      if (typeof checker !== 'function') {
        printWarning(
          'Invalid argument supplied to oneOfType. Expected an array of check functions, but ' +
          'received ' + getPostfixForTypeWarning(checker) + ' at index ' + i + '.'
        );
        return emptyFunctionThatReturnsNull;
      }
    }

    function validate(props, propName, componentName, location, propFullName) {
      var expectedTypes = [];
      for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
        var checker = arrayOfTypeCheckers[i];
        var checkerResult = checker(props, propName, componentName, location, propFullName, ReactPropTypesSecret);
        if (checkerResult == null) {
          return null;
        }
        if (checkerResult.data && has(checkerResult.data, 'expectedType')) {
          expectedTypes.push(checkerResult.data.expectedType);
        }
      }
      var expectedTypesMessage = (expectedTypes.length > 0) ? ', expected one of type [' + expectedTypes.join(', ') + ']': '';
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`' + expectedTypesMessage + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createNodeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      if (!isNode(props[propName])) {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`, expected a ReactNode.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function invalidValidatorError(componentName, location, propFullName, key, type) {
    return new PropTypeError(
      (componentName || 'React class') + ': ' + location + ' type `' + propFullName + '.' + key + '` is invalid; ' +
      'it must be a function, usually from the `prop-types` package, but received `' + type + '`.'
    );
  }

  function createShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      for (var key in shapeTypes) {
        var checker = shapeTypes[key];
        if (typeof checker !== 'function') {
          return invalidValidatorError(componentName, location, propFullName, key, getPreciseType(checker));
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createStrictShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      // We need to check all keys in case some are required but missing from props.
      var allKeys = assign({}, props[propName], shapeTypes);
      for (var key in allKeys) {
        var checker = shapeTypes[key];
        if (has(shapeTypes, key) && typeof checker !== 'function') {
          return invalidValidatorError(componentName, location, propFullName, key, getPreciseType(checker));
        }
        if (!checker) {
          return new PropTypeError(
            'Invalid ' + location + ' `' + propFullName + '` key `' + key + '` supplied to `' + componentName + '`.' +
            '\nBad object: ' + JSON.stringify(props[propName], null, '  ') +
            '\nValid keys: ' + JSON.stringify(Object.keys(shapeTypes), null, '  ')
          );
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }

    return createChainableTypeChecker(validate);
  }

  function isNode(propValue) {
    switch (typeof propValue) {
      case 'number':
      case 'string':
      case 'undefined':
        return true;
      case 'boolean':
        return !propValue;
      case 'object':
        if (Array.isArray(propValue)) {
          return propValue.every(isNode);
        }
        if (propValue === null || isValidElement(propValue)) {
          return true;
        }

        var iteratorFn = getIteratorFn(propValue);
        if (iteratorFn) {
          var iterator = iteratorFn.call(propValue);
          var step;
          if (iteratorFn !== propValue.entries) {
            while (!(step = iterator.next()).done) {
              if (!isNode(step.value)) {
                return false;
              }
            }
          } else {
            // Iterator will provide entry [k,v] tuples rather than values.
            while (!(step = iterator.next()).done) {
              var entry = step.value;
              if (entry) {
                if (!isNode(entry[1])) {
                  return false;
                }
              }
            }
          }
        } else {
          return false;
        }

        return true;
      default:
        return false;
    }
  }

  function isSymbol(propType, propValue) {
    // Native Symbol.
    if (propType === 'symbol') {
      return true;
    }

    // falsy value can't be a Symbol
    if (!propValue) {
      return false;
    }

    // 19.4.3.5 Symbol.prototype[@@toStringTag] === 'Symbol'
    if (propValue['@@toStringTag'] === 'Symbol') {
      return true;
    }

    // Fallback for non-spec compliant Symbols which are polyfilled.
    if (typeof Symbol === 'function' && propValue instanceof Symbol) {
      return true;
    }

    return false;
  }

  // Equivalent of `typeof` but with special handling for array and regexp.
  function getPropType(propValue) {
    var propType = typeof propValue;
    if (Array.isArray(propValue)) {
      return 'array';
    }
    if (propValue instanceof RegExp) {
      // Old webkits (at least until Android 4.0) return 'function' rather than
      // 'object' for typeof a RegExp. We'll normalize this here so that /bla/
      // passes PropTypes.object.
      return 'object';
    }
    if (isSymbol(propType, propValue)) {
      return 'symbol';
    }
    return propType;
  }

  // This handles more types than `getPropType`. Only used for error messages.
  // See `createPrimitiveTypeChecker`.
  function getPreciseType(propValue) {
    if (typeof propValue === 'undefined' || propValue === null) {
      return '' + propValue;
    }
    var propType = getPropType(propValue);
    if (propType === 'object') {
      if (propValue instanceof Date) {
        return 'date';
      } else if (propValue instanceof RegExp) {
        return 'regexp';
      }
    }
    return propType;
  }

  // Returns a string that is postfixed to a warning about an invalid type.
  // For example, "undefined" or "of type array"
  function getPostfixForTypeWarning(value) {
    var type = getPreciseType(value);
    switch (type) {
      case 'array':
      case 'object':
        return 'an ' + type;
      case 'boolean':
      case 'date':
      case 'regexp':
        return 'a ' + type;
      default:
        return type;
    }
  }

  // Returns class name of the object, if any.
  function getClassName(propValue) {
    if (!propValue.constructor || !propValue.constructor.name) {
      return ANONYMOUS;
    }
    return propValue.constructor.name;
  }

  ReactPropTypes.checkPropTypes = checkPropTypes;
  ReactPropTypes.resetWarningCache = checkPropTypes.resetWarningCache;
  ReactPropTypes.PropTypes = ReactPropTypes;

  return ReactPropTypes;
};


/***/ }),

/***/ "./node_modules/prop-types/index.js":
/*!******************************************!*\
  !*** ./node_modules/prop-types/index.js ***!
  \******************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

if (true) {
  var ReactIs = __webpack_require__(/*! react-is */ "./node_modules/prop-types/node_modules/react-is/index.js");

  // By explicitly using `prop-types` you are opting into new development behavior.
  // http://fb.me/prop-types-in-prod
  var throwOnDirectAccess = true;
  module.exports = __webpack_require__(/*! ./factoryWithTypeCheckers */ "./node_modules/prop-types/factoryWithTypeCheckers.js")(ReactIs.isElement, throwOnDirectAccess);
} else {}


/***/ }),

/***/ "./node_modules/prop-types/lib/ReactPropTypesSecret.js":
/*!*************************************************************!*\
  !*** ./node_modules/prop-types/lib/ReactPropTypesSecret.js ***!
  \*************************************************************/
/***/ (function(module) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */



var ReactPropTypesSecret = 'SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED';

module.exports = ReactPropTypesSecret;


/***/ }),

/***/ "./node_modules/prop-types/lib/has.js":
/*!********************************************!*\
  !*** ./node_modules/prop-types/lib/has.js ***!
  \********************************************/
/***/ (function(module) {

module.exports = Function.call.bind(Object.prototype.hasOwnProperty);


/***/ }),

/***/ "./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js ***!
  \***********************************************************************************/
/***/ (function(__unused_webpack_module, exports) {

"use strict";
/** @license React v16.13.1
 * react-is.development.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */





if (true) {
  (function() {
'use strict';

// The Symbol used to tag the ReactElement-like types. If there is no native Symbol
// nor polyfill, then a plain number is used for performance.
var hasSymbol = typeof Symbol === 'function' && Symbol.for;
var REACT_ELEMENT_TYPE = hasSymbol ? Symbol.for('react.element') : 0xeac7;
var REACT_PORTAL_TYPE = hasSymbol ? Symbol.for('react.portal') : 0xeaca;
var REACT_FRAGMENT_TYPE = hasSymbol ? Symbol.for('react.fragment') : 0xeacb;
var REACT_STRICT_MODE_TYPE = hasSymbol ? Symbol.for('react.strict_mode') : 0xeacc;
var REACT_PROFILER_TYPE = hasSymbol ? Symbol.for('react.profiler') : 0xead2;
var REACT_PROVIDER_TYPE = hasSymbol ? Symbol.for('react.provider') : 0xeacd;
var REACT_CONTEXT_TYPE = hasSymbol ? Symbol.for('react.context') : 0xeace; // TODO: We don't use AsyncMode or ConcurrentMode anymore. They were temporary
// (unstable) APIs that have been removed. Can we remove the symbols?

var REACT_ASYNC_MODE_TYPE = hasSymbol ? Symbol.for('react.async_mode') : 0xeacf;
var REACT_CONCURRENT_MODE_TYPE = hasSymbol ? Symbol.for('react.concurrent_mode') : 0xeacf;
var REACT_FORWARD_REF_TYPE = hasSymbol ? Symbol.for('react.forward_ref') : 0xead0;
var REACT_SUSPENSE_TYPE = hasSymbol ? Symbol.for('react.suspense') : 0xead1;
var REACT_SUSPENSE_LIST_TYPE = hasSymbol ? Symbol.for('react.suspense_list') : 0xead8;
var REACT_MEMO_TYPE = hasSymbol ? Symbol.for('react.memo') : 0xead3;
var REACT_LAZY_TYPE = hasSymbol ? Symbol.for('react.lazy') : 0xead4;
var REACT_BLOCK_TYPE = hasSymbol ? Symbol.for('react.block') : 0xead9;
var REACT_FUNDAMENTAL_TYPE = hasSymbol ? Symbol.for('react.fundamental') : 0xead5;
var REACT_RESPONDER_TYPE = hasSymbol ? Symbol.for('react.responder') : 0xead6;
var REACT_SCOPE_TYPE = hasSymbol ? Symbol.for('react.scope') : 0xead7;

function isValidElementType(type) {
  return typeof type === 'string' || typeof type === 'function' || // Note: its typeof might be other than 'symbol' or 'number' if it's a polyfill.
  type === REACT_FRAGMENT_TYPE || type === REACT_CONCURRENT_MODE_TYPE || type === REACT_PROFILER_TYPE || type === REACT_STRICT_MODE_TYPE || type === REACT_SUSPENSE_TYPE || type === REACT_SUSPENSE_LIST_TYPE || typeof type === 'object' && type !== null && (type.$$typeof === REACT_LAZY_TYPE || type.$$typeof === REACT_MEMO_TYPE || type.$$typeof === REACT_PROVIDER_TYPE || type.$$typeof === REACT_CONTEXT_TYPE || type.$$typeof === REACT_FORWARD_REF_TYPE || type.$$typeof === REACT_FUNDAMENTAL_TYPE || type.$$typeof === REACT_RESPONDER_TYPE || type.$$typeof === REACT_SCOPE_TYPE || type.$$typeof === REACT_BLOCK_TYPE);
}

function typeOf(object) {
  if (typeof object === 'object' && object !== null) {
    var $$typeof = object.$$typeof;

    switch ($$typeof) {
      case REACT_ELEMENT_TYPE:
        var type = object.type;

        switch (type) {
          case REACT_ASYNC_MODE_TYPE:
          case REACT_CONCURRENT_MODE_TYPE:
          case REACT_FRAGMENT_TYPE:
          case REACT_PROFILER_TYPE:
          case REACT_STRICT_MODE_TYPE:
          case REACT_SUSPENSE_TYPE:
            return type;

          default:
            var $$typeofType = type && type.$$typeof;

            switch ($$typeofType) {
              case REACT_CONTEXT_TYPE:
              case REACT_FORWARD_REF_TYPE:
              case REACT_LAZY_TYPE:
              case REACT_MEMO_TYPE:
              case REACT_PROVIDER_TYPE:
                return $$typeofType;

              default:
                return $$typeof;
            }

        }

      case REACT_PORTAL_TYPE:
        return $$typeof;
    }
  }

  return undefined;
} // AsyncMode is deprecated along with isAsyncMode

var AsyncMode = REACT_ASYNC_MODE_TYPE;
var ConcurrentMode = REACT_CONCURRENT_MODE_TYPE;
var ContextConsumer = REACT_CONTEXT_TYPE;
var ContextProvider = REACT_PROVIDER_TYPE;
var Element = REACT_ELEMENT_TYPE;
var ForwardRef = REACT_FORWARD_REF_TYPE;
var Fragment = REACT_FRAGMENT_TYPE;
var Lazy = REACT_LAZY_TYPE;
var Memo = REACT_MEMO_TYPE;
var Portal = REACT_PORTAL_TYPE;
var Profiler = REACT_PROFILER_TYPE;
var StrictMode = REACT_STRICT_MODE_TYPE;
var Suspense = REACT_SUSPENSE_TYPE;
var hasWarnedAboutDeprecatedIsAsyncMode = false; // AsyncMode should be deprecated

function isAsyncMode(object) {
  {
    if (!hasWarnedAboutDeprecatedIsAsyncMode) {
      hasWarnedAboutDeprecatedIsAsyncMode = true; // Using console['warn'] to evade Babel and ESLint

      console['warn']('The ReactIs.isAsyncMode() alias has been deprecated, ' + 'and will be removed in React 17+. Update your code to use ' + 'ReactIs.isConcurrentMode() instead. It has the exact same API.');
    }
  }

  return isConcurrentMode(object) || typeOf(object) === REACT_ASYNC_MODE_TYPE;
}
function isConcurrentMode(object) {
  return typeOf(object) === REACT_CONCURRENT_MODE_TYPE;
}
function isContextConsumer(object) {
  return typeOf(object) === REACT_CONTEXT_TYPE;
}
function isContextProvider(object) {
  return typeOf(object) === REACT_PROVIDER_TYPE;
}
function isElement(object) {
  return typeof object === 'object' && object !== null && object.$$typeof === REACT_ELEMENT_TYPE;
}
function isForwardRef(object) {
  return typeOf(object) === REACT_FORWARD_REF_TYPE;
}
function isFragment(object) {
  return typeOf(object) === REACT_FRAGMENT_TYPE;
}
function isLazy(object) {
  return typeOf(object) === REACT_LAZY_TYPE;
}
function isMemo(object) {
  return typeOf(object) === REACT_MEMO_TYPE;
}
function isPortal(object) {
  return typeOf(object) === REACT_PORTAL_TYPE;
}
function isProfiler(object) {
  return typeOf(object) === REACT_PROFILER_TYPE;
}
function isStrictMode(object) {
  return typeOf(object) === REACT_STRICT_MODE_TYPE;
}
function isSuspense(object) {
  return typeOf(object) === REACT_SUSPENSE_TYPE;
}

exports.AsyncMode = AsyncMode;
exports.ConcurrentMode = ConcurrentMode;
exports.ContextConsumer = ContextConsumer;
exports.ContextProvider = ContextProvider;
exports.Element = Element;
exports.ForwardRef = ForwardRef;
exports.Fragment = Fragment;
exports.Lazy = Lazy;
exports.Memo = Memo;
exports.Portal = Portal;
exports.Profiler = Profiler;
exports.StrictMode = StrictMode;
exports.Suspense = Suspense;
exports.isAsyncMode = isAsyncMode;
exports.isConcurrentMode = isConcurrentMode;
exports.isContextConsumer = isContextConsumer;
exports.isContextProvider = isContextProvider;
exports.isElement = isElement;
exports.isForwardRef = isForwardRef;
exports.isFragment = isFragment;
exports.isLazy = isLazy;
exports.isMemo = isMemo;
exports.isPortal = isPortal;
exports.isProfiler = isProfiler;
exports.isStrictMode = isStrictMode;
exports.isSuspense = isSuspense;
exports.isValidElementType = isValidElementType;
exports.typeOf = typeOf;
  })();
}


/***/ }),

/***/ "./node_modules/prop-types/node_modules/react-is/index.js":
/*!****************************************************************!*\
  !*** ./node_modules/prop-types/node_modules/react-is/index.js ***!
  \****************************************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


if (false) {} else {
  module.exports = __webpack_require__(/*! ./cjs/react-is.development.js */ "./node_modules/prop-types/node_modules/react-is/cjs/react-is.development.js");
}


/***/ }),

/***/ "./node_modules/react-csv/index.js":
/*!*****************************************!*\
  !*** ./node_modules/react-csv/index.js ***!
  \*****************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./lib/index.js */ "./node_modules/react-csv/lib/index.js");


/***/ }),

/***/ "./node_modules/react-csv/lib/components/Download.js":
/*!***********************************************************!*\
  !*** ./node_modules/react-csv/lib/components/Download.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "react");

var _react2 = _interopRequireDefault(_react);

var _core = __webpack_require__(/*! ../core */ "./node_modules/react-csv/lib/core.js");

var _metaProps = __webpack_require__(/*! ../metaProps */ "./node_modules/react-csv/lib/metaProps.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var defaultProps = {
  target: '_blank'
};

var CSVDownload = function (_React$Component) {
  _inherits(CSVDownload, _React$Component);

  function CSVDownload(props) {
    _classCallCheck(this, CSVDownload);

    var _this = _possibleConstructorReturn(this, (CSVDownload.__proto__ || Object.getPrototypeOf(CSVDownload)).call(this, props));

    _this.state = {};
    return _this;
  }

  _createClass(CSVDownload, [{
    key: 'buildURI',
    value: function buildURI() {
      return _core.buildURI.apply(undefined, arguments);
    }
  }, {
    key: 'componentDidMount',
    value: function componentDidMount() {
      var _props = this.props,
          data = _props.data,
          headers = _props.headers,
          separator = _props.separator,
          enclosingCharacter = _props.enclosingCharacter,
          uFEFF = _props.uFEFF,
          target = _props.target,
          specs = _props.specs,
          replace = _props.replace;

      this.state.page = window.open(this.buildURI(data, uFEFF, headers, separator, enclosingCharacter), target, specs, replace);
    }
  }, {
    key: 'getWindow',
    value: function getWindow() {
      return this.state.page;
    }
  }, {
    key: 'render',
    value: function render() {
      return null;
    }
  }]);

  return CSVDownload;
}(_react2.default.Component);

CSVDownload.defaultProps = Object.assign(_metaProps.defaultProps, defaultProps);
CSVDownload.propTypes = _metaProps.propTypes;
exports["default"] = CSVDownload;

/***/ }),

/***/ "./node_modules/react-csv/lib/components/Link.js":
/*!*******************************************************!*\
  !*** ./node_modules/react-csv/lib/components/Link.js ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "react");

var _react2 = _interopRequireDefault(_react);

var _core = __webpack_require__(/*! ../core */ "./node_modules/react-csv/lib/core.js");

var _metaProps = __webpack_require__(/*! ../metaProps */ "./node_modules/react-csv/lib/metaProps.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var CSVLink = function (_React$Component) {
  _inherits(CSVLink, _React$Component);

  function CSVLink(props) {
    _classCallCheck(this, CSVLink);

    var _this = _possibleConstructorReturn(this, (CSVLink.__proto__ || Object.getPrototypeOf(CSVLink)).call(this, props));

    _this.buildURI = _this.buildURI.bind(_this);
    return _this;
  }

  _createClass(CSVLink, [{
    key: 'buildURI',
    value: function buildURI() {
      return _core.buildURI.apply(undefined, arguments);
    }
  }, {
    key: 'handleLegacy',
    value: function handleLegacy(event) {
      var isAsync = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

      if (window.navigator.msSaveOrOpenBlob) {
        event.preventDefault();

        var _props = this.props,
            data = _props.data,
            headers = _props.headers,
            separator = _props.separator,
            filename = _props.filename,
            enclosingCharacter = _props.enclosingCharacter,
            uFEFF = _props.uFEFF;


        var csvData = isAsync && typeof data === 'function' ? data() : data;

        var blob = new Blob([uFEFF ? '\uFEFF' : '', (0, _core.toCSV)(csvData, headers, separator, enclosingCharacter)]);
        window.navigator.msSaveBlob(blob, filename);

        return false;
      }
    }
  }, {
    key: 'handleAsyncClick',
    value: function handleAsyncClick(event) {
      var _this2 = this;

      var done = function done(proceed) {
        if (proceed === false) {
          event.preventDefault();
          return;
        }
        _this2.handleLegacy(event, true);
      };

      this.props.onClick(event, done);
    }
  }, {
    key: 'handleSyncClick',
    value: function handleSyncClick(event) {
      var stopEvent = this.props.onClick(event) === false;
      if (stopEvent) {
        event.preventDefault();
        return;
      }
      this.handleLegacy(event);
    }
  }, {
    key: 'handleClick',
    value: function handleClick() {
      var _this3 = this;

      return function (event) {
        if (typeof _this3.props.onClick === 'function') {
          return _this3.props.asyncOnClick ? _this3.handleAsyncClick(event) : _this3.handleSyncClick(event);
        }
        _this3.handleLegacy(event);
      };
    }
  }, {
    key: 'render',
    value: function render() {
      var _this4 = this;

      var _props2 = this.props,
          data = _props2.data,
          headers = _props2.headers,
          separator = _props2.separator,
          filename = _props2.filename,
          uFEFF = _props2.uFEFF,
          children = _props2.children,
          onClick = _props2.onClick,
          asyncOnClick = _props2.asyncOnClick,
          enclosingCharacter = _props2.enclosingCharacter,
          rest = _objectWithoutProperties(_props2, ['data', 'headers', 'separator', 'filename', 'uFEFF', 'children', 'onClick', 'asyncOnClick', 'enclosingCharacter']);

      var isNodeEnvironment = typeof window === 'undefined';
      var href = isNodeEnvironment ? '' : this.buildURI(data, uFEFF, headers, separator, enclosingCharacter);

      return _react2.default.createElement(
        'a',
        _extends({
          download: filename
        }, rest, {
          ref: function ref(link) {
            return _this4.link = link;
          },
          target: '_self',
          href: href,
          onClick: this.handleClick()
        }),
        children
      );
    }
  }]);

  return CSVLink;
}(_react2.default.Component);

CSVLink.defaultProps = _metaProps.defaultProps;
CSVLink.propTypes = _metaProps.propTypes;
exports["default"] = CSVLink;

/***/ }),

/***/ "./node_modules/react-csv/lib/core.js":
/*!********************************************!*\
  !*** ./node_modules/react-csv/lib/core.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, exports) {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var isSafari = exports.isSafari = function isSafari() {
  return (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)
  );
};

var isJsons = exports.isJsons = function isJsons(array) {
  return Array.isArray(array) && array.every(function (row) {
    return (typeof row === "undefined" ? "undefined" : _typeof(row)) === 'object' && !(row instanceof Array);
  });
};

var isArrays = exports.isArrays = function isArrays(array) {
  return Array.isArray(array) && array.every(function (row) {
    return Array.isArray(row);
  });
};

var jsonsHeaders = exports.jsonsHeaders = function jsonsHeaders(array) {
  return Array.from(array.map(function (json) {
    return Object.keys(json);
  }).reduce(function (a, b) {
    return new Set([].concat(_toConsumableArray(a), _toConsumableArray(b)));
  }, []));
};

var jsons2arrays = exports.jsons2arrays = function jsons2arrays(jsons, headers) {
  headers = headers || jsonsHeaders(jsons);

  var headerLabels = headers;
  var headerKeys = headers;
  if (isJsons(headers)) {
    headerLabels = headers.map(function (header) {
      return header.label;
    });
    headerKeys = headers.map(function (header) {
      return header.key;
    });
  }

  var data = jsons.map(function (object) {
    return headerKeys.map(function (header) {
      return getHeaderValue(header, object);
    });
  });
  return [headerLabels].concat(_toConsumableArray(data));
};

var getHeaderValue = exports.getHeaderValue = function getHeaderValue(property, obj) {
  var foundValue = property.replace(/\[([^\]]+)]/g, ".$1").split(".").reduce(function (o, p, i, arr) {
    var value = o[p];
    if (value === undefined || value === null) {
      arr.splice(1);
    } else {
      return value;
    }
  }, obj);

  return foundValue === undefined ? property in obj ? obj[property] : '' : foundValue;
};

var elementOrEmpty = exports.elementOrEmpty = function elementOrEmpty(element) {
  return typeof element === 'undefined' || element === null ? '' : element;
};

var joiner = exports.joiner = function joiner(data) {
  var separator = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : ',';
  var enclosingCharacter = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '"';

  return data.filter(function (e) {
    return e;
  }).map(function (row) {
    return row.map(function (element) {
      return elementOrEmpty(element);
    }).map(function (column) {
      return "" + enclosingCharacter + column + enclosingCharacter;
    }).join(separator);
  }).join("\n");
};

var arrays2csv = exports.arrays2csv = function arrays2csv(data, headers, separator, enclosingCharacter) {
  return joiner(headers ? [headers].concat(_toConsumableArray(data)) : data, separator, enclosingCharacter);
};

var jsons2csv = exports.jsons2csv = function jsons2csv(data, headers, separator, enclosingCharacter) {
  return joiner(jsons2arrays(data, headers), separator, enclosingCharacter);
};

var string2csv = exports.string2csv = function string2csv(data, headers, separator, enclosingCharacter) {
  return headers ? headers.join(separator) + "\n" + data : data.replace(/"/g, '""');
};

var toCSV = exports.toCSV = function toCSV(data, headers, separator, enclosingCharacter) {
  if (isJsons(data)) return jsons2csv(data, headers, separator, enclosingCharacter);
  if (isArrays(data)) return arrays2csv(data, headers, separator, enclosingCharacter);
  if (typeof data === 'string') return string2csv(data, headers, separator);
  throw new TypeError("Data should be a \"String\", \"Array of arrays\" OR \"Array of objects\" ");
};

var buildURI = exports.buildURI = function buildURI(data, uFEFF, headers, separator, enclosingCharacter) {
  var csv = toCSV(data, headers, separator, enclosingCharacter);
  var type = isSafari() ? 'application/csv' : 'text/csv';
  var blob = new Blob([uFEFF ? "\uFEFF" : '', csv], { type: type });
  var dataURI = "data:" + type + ";charset=utf-8," + (uFEFF ? "\uFEFF" : '') + csv;

  var URL = window.URL || window.webkitURL;

  return typeof URL.createObjectURL === 'undefined' ? dataURI : URL.createObjectURL(blob);
};

/***/ }),

/***/ "./node_modules/react-csv/lib/index.js":
/*!*********************************************!*\
  !*** ./node_modules/react-csv/lib/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.CSVLink = exports.CSVDownload = undefined;

var _Download = __webpack_require__(/*! ./components/Download */ "./node_modules/react-csv/lib/components/Download.js");

var _Download2 = _interopRequireDefault(_Download);

var _Link = __webpack_require__(/*! ./components/Link */ "./node_modules/react-csv/lib/components/Link.js");

var _Link2 = _interopRequireDefault(_Link);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CSVDownload = exports.CSVDownload = _Download2.default;
var CSVLink = exports.CSVLink = _Link2.default;

/***/ }),

/***/ "./node_modules/react-csv/lib/metaProps.js":
/*!*************************************************!*\
  !*** ./node_modules/react-csv/lib/metaProps.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.PropsNotForwarded = exports.defaultProps = exports.propTypes = undefined;

var _react = __webpack_require__(/*! react */ "react");

var _react2 = _interopRequireDefault(_react);

var _propTypes = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var propTypes = exports.propTypes = {
  data: (0, _propTypes.oneOfType)([_propTypes.string, _propTypes.array, _propTypes.func]).isRequired,
  headers: _propTypes.array,
  target: _propTypes.string,
  separator: _propTypes.string,
  filename: _propTypes.string,
  uFEFF: _propTypes.bool,
  onClick: _propTypes.func,
  asyncOnClick: _propTypes.bool,
  enclosingCharacter: _propTypes.string
};

var defaultProps = exports.defaultProps = {
  separator: ',',
  filename: 'generatedBy_react-csv.csv',
  uFEFF: true,
  asyncOnClick: false,
  enclosingCharacter: '"'
};

var PropsNotForwarded = exports.PropsNotForwarded = ['data', 'headers'];

/***/ }),

/***/ "./node_modules/react-table/dist/react-table.development.js":
/*!******************************************************************!*\
  !*** ./node_modules/react-table/dist/react-table.development.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

(function (global, factory) {
   true ? factory(exports, __webpack_require__(/*! react */ "react")) :
  0;
}(this, (function (exports, React) { 'use strict';

  React = React && Object.prototype.hasOwnProperty.call(React, 'default') ? React['default'] : React;

  function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
    try {
      var info = gen[key](arg);
      var value = info.value;
    } catch (error) {
      reject(error);
      return;
    }

    if (info.done) {
      resolve(value);
    } else {
      Promise.resolve(value).then(_next, _throw);
    }
  }

  function _asyncToGenerator(fn) {
    return function () {
      var self = this,
          args = arguments;
      return new Promise(function (resolve, reject) {
        var gen = fn.apply(self, args);

        function _next(value) {
          asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
        }

        function _throw(err) {
          asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
        }

        _next(undefined);
      });
    };
  }

  function _extends() {
    _extends = Object.assign || function (target) {
      for (var i = 1; i < arguments.length; i++) {
        var source = arguments[i];

        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }

      return target;
    };

    return _extends.apply(this, arguments);
  }

  function _objectWithoutPropertiesLoose(source, excluded) {
    if (source == null) return {};
    var target = {};
    var sourceKeys = Object.keys(source);
    var key, i;

    for (i = 0; i < sourceKeys.length; i++) {
      key = sourceKeys[i];
      if (excluded.indexOf(key) >= 0) continue;
      target[key] = source[key];
    }

    return target;
  }

  function _toPrimitive(input, hint) {
    if (typeof input !== "object" || input === null) return input;
    var prim = input[Symbol.toPrimitive];

    if (prim !== undefined) {
      var res = prim.call(input, hint || "default");
      if (typeof res !== "object") return res;
      throw new TypeError("@@toPrimitive must return a primitive value.");
    }

    return (hint === "string" ? String : Number)(input);
  }

  function _toPropertyKey(arg) {
    var key = _toPrimitive(arg, "string");

    return typeof key === "symbol" ? key : String(key);
  }

  var renderErr = 'Renderer Error ☝️';
  var actions = {
    init: 'init'
  };
  var defaultRenderer = function defaultRenderer(_ref) {
    var _ref$value = _ref.value,
        value = _ref$value === void 0 ? '' : _ref$value;
    return value;
  };
  var emptyRenderer = function emptyRenderer() {
    return React.createElement(React.Fragment, null, "\xA0");
  };
  var defaultColumn = {
    Cell: defaultRenderer,
    width: 150,
    minWidth: 0,
    maxWidth: Number.MAX_SAFE_INTEGER
  };

  function mergeProps() {
    for (var _len = arguments.length, propList = new Array(_len), _key = 0; _key < _len; _key++) {
      propList[_key] = arguments[_key];
    }

    return propList.reduce(function (props, next) {
      var style = next.style,
          className = next.className,
          rest = _objectWithoutPropertiesLoose(next, ["style", "className"]);

      props = _extends({}, props, {}, rest);

      if (style) {
        props.style = props.style ? _extends({}, props.style || {}, {}, style || {}) : style;
      }

      if (className) {
        props.className = props.className ? props.className + ' ' + className : className;
      }

      if (props.className === '') {
        delete props.className;
      }

      return props;
    }, {});
  }

  function handlePropGetter(prevProps, userProps, meta) {
    // Handle a lambda, pass it the previous props
    if (typeof userProps === 'function') {
      return handlePropGetter({}, userProps(prevProps, meta));
    } // Handle an array, merge each item as separate props


    if (Array.isArray(userProps)) {
      return mergeProps.apply(void 0, [prevProps].concat(userProps));
    } // Handle an object by default, merge the two objects


    return mergeProps(prevProps, userProps);
  }

  var makePropGetter = function makePropGetter(hooks, meta) {
    if (meta === void 0) {
      meta = {};
    }

    return function (userProps) {
      if (userProps === void 0) {
        userProps = {};
      }

      return [].concat(hooks, [userProps]).reduce(function (prev, next) {
        return handlePropGetter(prev, next, _extends({}, meta, {
          userProps: userProps
        }));
      }, {});
    };
  };
  var reduceHooks = function reduceHooks(hooks, initial, meta, allowUndefined) {
    if (meta === void 0) {
      meta = {};
    }

    return hooks.reduce(function (prev, next) {
      var nextValue = next(prev, meta);

      {
        if (!allowUndefined && typeof nextValue === 'undefined') {
          console.info(next);
          throw new Error('React Table: A reducer hook ☝️ just returned undefined! This is not allowed.');
        }
      }

      return nextValue;
    }, initial);
  };
  var loopHooks = function loopHooks(hooks, context, meta) {
    if (meta === void 0) {
      meta = {};
    }

    return hooks.forEach(function (hook) {
      var nextValue = hook(context, meta);

      {
        if (typeof nextValue !== 'undefined') {
          console.info(hook, nextValue);
          throw new Error('React Table: A loop-type hook ☝️ just returned a value! This is not allowed.');
        }
      }
    });
  };
  function ensurePluginOrder(plugins, befores, pluginName, afters) {
    if ( afters) {
      throw new Error("Defining plugins in the \"after\" section of ensurePluginOrder is no longer supported (see plugin " + pluginName + ")");
    }

    var pluginIndex = plugins.findIndex(function (plugin) {
      return plugin.pluginName === pluginName;
    });

    if (pluginIndex === -1) {
      {
        throw new Error("The plugin \"" + pluginName + "\" was not found in the plugin list!\nThis usually means you need to need to name your plugin hook by setting the 'pluginName' property of the hook function, eg:\n\n  " + pluginName + ".pluginName = '" + pluginName + "'\n");
      }
    }

    befores.forEach(function (before) {
      var beforeIndex = plugins.findIndex(function (plugin) {
        return plugin.pluginName === before;
      });

      if (beforeIndex > -1 && beforeIndex > pluginIndex) {
        {
          throw new Error("React Table: The " + pluginName + " plugin hook must be placed after the " + before + " plugin hook!");
        }
      }
    });
  }
  function functionalUpdate(updater, old) {
    return typeof updater === 'function' ? updater(old) : updater;
  }
  function useGetLatest(obj) {
    var ref = React.useRef();
    ref.current = obj;
    return React.useCallback(function () {
      return ref.current;
    }, []);
  } // SSR has issues with useLayoutEffect still, so use useEffect during SSR

  var safeUseLayoutEffect = typeof document !== 'undefined' ? React.useLayoutEffect : React.useEffect;
  function useMountedLayoutEffect(fn, deps) {
    var mountedRef = React.useRef(false);
    safeUseLayoutEffect(function () {
      if (mountedRef.current) {
        fn();
      }

      mountedRef.current = true; // eslint-disable-next-line
    }, deps);
  }
  function useAsyncDebounce(defaultFn, defaultWait) {
    if (defaultWait === void 0) {
      defaultWait = 0;
    }

    var debounceRef = React.useRef({});
    var getDefaultFn = useGetLatest(defaultFn);
    var getDefaultWait = useGetLatest(defaultWait);
    return React.useCallback(
    /*#__PURE__*/
    function () {
      var _ref2 = _asyncToGenerator(
      /*#__PURE__*/
      regeneratorRuntime.mark(function _callee2() {
        var _len2,
            args,
            _key2,
            _args2 = arguments;

        return regeneratorRuntime.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                for (_len2 = _args2.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
                  args[_key2] = _args2[_key2];
                }

                if (!debounceRef.current.promise) {
                  debounceRef.current.promise = new Promise(function (resolve, reject) {
                    debounceRef.current.resolve = resolve;
                    debounceRef.current.reject = reject;
                  });
                }

                if (debounceRef.current.timeout) {
                  clearTimeout(debounceRef.current.timeout);
                }

                debounceRef.current.timeout = setTimeout(
                /*#__PURE__*/
                _asyncToGenerator(
                /*#__PURE__*/
                regeneratorRuntime.mark(function _callee() {
                  return regeneratorRuntime.wrap(function _callee$(_context) {
                    while (1) {
                      switch (_context.prev = _context.next) {
                        case 0:
                          delete debounceRef.current.timeout;
                          _context.prev = 1;
                          _context.t0 = debounceRef.current;
                          _context.next = 5;
                          return getDefaultFn().apply(void 0, args);

                        case 5:
                          _context.t1 = _context.sent;

                          _context.t0.resolve.call(_context.t0, _context.t1);

                          _context.next = 12;
                          break;

                        case 9:
                          _context.prev = 9;
                          _context.t2 = _context["catch"](1);
                          debounceRef.current.reject(_context.t2);

                        case 12:
                          _context.prev = 12;
                          delete debounceRef.current.promise;
                          return _context.finish(12);

                        case 15:
                        case "end":
                          return _context.stop();
                      }
                    }
                  }, _callee, null, [[1, 9, 12, 15]]);
                })), getDefaultWait());
                return _context2.abrupt("return", debounceRef.current.promise);

              case 5:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }));

      return function () {
        return _ref2.apply(this, arguments);
      };
    }(), [getDefaultFn, getDefaultWait]);
  }
  function makeRenderer(instance, column, meta) {
    if (meta === void 0) {
      meta = {};
    }

    return function (type, userProps) {
      if (userProps === void 0) {
        userProps = {};
      }

      var Comp = typeof type === 'string' ? column[type] : type;

      if (typeof Comp === 'undefined') {
        console.info(column);
        throw new Error(renderErr);
      }

      return flexRender(Comp, _extends({}, instance, {
        column: column
      }, meta, {}, userProps));
    };
  }
  function flexRender(Comp, props) {
    return isReactComponent(Comp) ? React.createElement(Comp, props) : Comp;
  }

  function isReactComponent(component) {
    return isClassComponent(component) || typeof component === 'function' || isExoticComponent(component);
  }

  function isClassComponent(component) {
    return typeof component === 'function' && function () {
      var proto = Object.getPrototypeOf(component);
      return proto.prototype && proto.prototype.isReactComponent;
    }();
  }

  function isExoticComponent(component) {
    return typeof component === 'object' && typeof component.$$typeof === 'symbol' && ['react.memo', 'react.forward_ref'].includes(component.$$typeof.description);
  }

  function linkColumnStructure(columns, parent, depth) {
    if (depth === void 0) {
      depth = 0;
    }

    return columns.map(function (column) {
      column = _extends({}, column, {
        parent: parent,
        depth: depth
      });
      assignColumnAccessor(column);

      if (column.columns) {
        column.columns = linkColumnStructure(column.columns, column, depth + 1);
      }

      return column;
    });
  }
  function flattenColumns(columns) {
    return flattenBy(columns, 'columns');
  }
  function assignColumnAccessor(column) {
    // First check for string accessor
    var id = column.id,
        accessor = column.accessor,
        Header = column.Header;

    if (typeof accessor === 'string') {
      id = id || accessor;
      var accessorPath = accessor.split('.');

      accessor = function accessor(row) {
        return getBy(row, accessorPath);
      };
    }

    if (!id && typeof Header === 'string' && Header) {
      id = Header;
    }

    if (!id && column.columns) {
      console.error(column);
      throw new Error('A column ID (or unique "Header" value) is required!');
    }

    if (!id) {
      console.error(column);
      throw new Error('A column ID (or string accessor) is required!');
    }

    Object.assign(column, {
      id: id,
      accessor: accessor
    });
    return column;
  }
  function decorateColumn(column, userDefaultColumn) {
    if (!userDefaultColumn) {
      throw new Error();
    }

    Object.assign(column, _extends({
      // Make sure there is a fallback header, just in case
      Header: emptyRenderer,
      Footer: emptyRenderer
    }, defaultColumn, {}, userDefaultColumn, {}, column));
    Object.assign(column, {
      originalWidth: column.width
    });
    return column;
  } // Build the header groups from the bottom up

  function makeHeaderGroups(allColumns, defaultColumn, additionalHeaderProperties) {
    if (additionalHeaderProperties === void 0) {
      additionalHeaderProperties = function additionalHeaderProperties() {
        return {};
      };
    }

    var headerGroups = [];
    var scanColumns = allColumns;
    var uid = 0;

    var getUID = function getUID() {
      return uid++;
    };

    var _loop = function _loop() {
      // The header group we are creating
      var headerGroup = {
        headers: []
      }; // The parent columns we're going to scan next

      var parentColumns = [];
      var hasParents = scanColumns.some(function (d) {
        return d.parent;
      }); // Scan each column for parents

      scanColumns.forEach(function (column) {
        // What is the latest (last) parent column?
        var latestParentColumn = [].concat(parentColumns).reverse()[0];
        var newParent;

        if (hasParents) {
          // If the column has a parent, add it if necessary
          if (column.parent) {
            newParent = _extends({}, column.parent, {
              originalId: column.parent.id,
              id: column.parent.id + "_" + getUID(),
              headers: [column]
            }, additionalHeaderProperties(column));
          } else {
            // If other columns have parents, we'll need to add a place holder if necessary
            var originalId = column.id + "_placeholder";
            newParent = decorateColumn(_extends({
              originalId: originalId,
              id: column.id + "_placeholder_" + getUID(),
              placeholderOf: column,
              headers: [column]
            }, additionalHeaderProperties(column)), defaultColumn);
          } // If the resulting parent columns are the same, just add
          // the column and increment the header span


          if (latestParentColumn && latestParentColumn.originalId === newParent.originalId) {
            latestParentColumn.headers.push(column);
          } else {
            parentColumns.push(newParent);
          }
        }

        headerGroup.headers.push(column);
      });
      headerGroups.push(headerGroup); // Start scanning the parent columns

      scanColumns = parentColumns;
    };

    while (scanColumns.length) {
      _loop();
    }

    return headerGroups.reverse();
  }
  var pathObjCache = new Map();
  function getBy(obj, path, def) {
    if (!path) {
      return obj;
    }

    var cacheKey = typeof path === 'function' ? path : JSON.stringify(path);

    var pathObj = pathObjCache.get(cacheKey) || function () {
      var pathObj = makePathArray(path);
      pathObjCache.set(cacheKey, pathObj);
      return pathObj;
    }();

    var val;

    try {
      val = pathObj.reduce(function (cursor, pathPart) {
        return cursor[pathPart];
      }, obj);
    } catch (e) {// continue regardless of error
    }

    return typeof val !== 'undefined' ? val : def;
  }
  function getFirstDefined() {
    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    for (var i = 0; i < args.length; i += 1) {
      if (typeof args[i] !== 'undefined') {
        return args[i];
      }
    }
  }
  function isFunction(a) {
    if (typeof a === 'function') {
      return a;
    }
  }
  function flattenBy(arr, key) {
    var flat = [];

    var recurse = function recurse(arr) {
      arr.forEach(function (d) {
        if (!d[key]) {
          flat.push(d);
        } else {
          recurse(d[key]);
        }
      });
    };

    recurse(arr);
    return flat;
  }
  function expandRows(rows, _ref) {
    var manualExpandedKey = _ref.manualExpandedKey,
        expanded = _ref.expanded,
        _ref$expandSubRows = _ref.expandSubRows,
        expandSubRows = _ref$expandSubRows === void 0 ? true : _ref$expandSubRows;
    var expandedRows = [];

    var handleRow = function handleRow(row, addToExpandedRows) {
      if (addToExpandedRows === void 0) {
        addToExpandedRows = true;
      }

      row.isExpanded = row.original && row.original[manualExpandedKey] || expanded[row.id];
      row.canExpand = row.subRows && !!row.subRows.length;

      if (addToExpandedRows) {
        expandedRows.push(row);
      }

      if (row.subRows && row.subRows.length && row.isExpanded) {
        row.subRows.forEach(function (row) {
          return handleRow(row, expandSubRows);
        });
      }
    };

    rows.forEach(function (row) {
      return handleRow(row);
    });
    return expandedRows;
  }
  function getFilterMethod(filter, userFilterTypes, filterTypes) {
    return isFunction(filter) || userFilterTypes[filter] || filterTypes[filter] || filterTypes.text;
  }
  function shouldAutoRemoveFilter(autoRemove, value, column) {
    return autoRemove ? autoRemove(value, column) : typeof value === 'undefined';
  }
  function unpreparedAccessWarning() {
    throw new Error('React-Table: You have not called prepareRow(row) one or more rows you are attempting to render.');
  }
  var passiveSupported = null;
  function passiveEventSupported() {
    // memoize support to avoid adding multiple test events
    if (typeof passiveSupported === 'boolean') return passiveSupported;
    var supported = false;

    try {
      var options = {
        get passive() {
          supported = true;
          return false;
        }

      };
      window.addEventListener('test', null, options);
      window.removeEventListener('test', null, options);
    } catch (err) {
      supported = false;
    }

    passiveSupported = supported;
    return passiveSupported;
  } //

  var reOpenBracket = /\[/g;
  var reCloseBracket = /\]/g;

  function makePathArray(obj) {
    return flattenDeep(obj) // remove all periods in parts
    .map(function (d) {
      return String(d).replace('.', '_');
    }) // join parts using period
    .join('.') // replace brackets with periods
    .replace(reOpenBracket, '.').replace(reCloseBracket, '') // split it back out on periods
    .split('.');
  }

  function flattenDeep(arr, newArr) {
    if (newArr === void 0) {
      newArr = [];
    }

    if (!Array.isArray(arr)) {
      newArr.push(arr);
    } else {
      for (var i = 0; i < arr.length; i += 1) {
        flattenDeep(arr[i], newArr);
      }
    }

    return newArr;
  }

  var defaultGetTableProps = function defaultGetTableProps(props) {
    return _extends({
      role: 'table'
    }, props);
  };

  var defaultGetTableBodyProps = function defaultGetTableBodyProps(props) {
    return _extends({
      role: 'rowgroup'
    }, props);
  };

  var defaultGetHeaderProps = function defaultGetHeaderProps(props, _ref) {
    var column = _ref.column;
    return _extends({
      key: "header_" + column.id,
      colSpan: column.totalVisibleHeaderCount,
      role: 'columnheader'
    }, props);
  };

  var defaultGetFooterProps = function defaultGetFooterProps(props, _ref2) {
    var column = _ref2.column;
    return _extends({
      key: "footer_" + column.id,
      colSpan: column.totalVisibleHeaderCount
    }, props);
  };

  var defaultGetHeaderGroupProps = function defaultGetHeaderGroupProps(props, _ref3) {
    var index = _ref3.index;
    return _extends({
      key: "headerGroup_" + index,
      role: 'row'
    }, props);
  };

  var defaultGetFooterGroupProps = function defaultGetFooterGroupProps(props, _ref4) {
    var index = _ref4.index;
    return _extends({
      key: "footerGroup_" + index
    }, props);
  };

  var defaultGetRowProps = function defaultGetRowProps(props, _ref5) {
    var row = _ref5.row;
    return _extends({
      key: "row_" + row.id,
      role: 'row'
    }, props);
  };

  var defaultGetCellProps = function defaultGetCellProps(props, _ref6) {
    var cell = _ref6.cell;
    return _extends({
      key: "cell_" + cell.row.id + "_" + cell.column.id,
      role: 'cell'
    }, props);
  };

  function makeDefaultPluginHooks() {
    return {
      useOptions: [],
      stateReducers: [],
      useControlledState: [],
      columns: [],
      columnsDeps: [],
      allColumns: [],
      allColumnsDeps: [],
      accessValue: [],
      materializedColumns: [],
      materializedColumnsDeps: [],
      useInstanceAfterData: [],
      visibleColumns: [],
      visibleColumnsDeps: [],
      headerGroups: [],
      headerGroupsDeps: [],
      useInstanceBeforeDimensions: [],
      useInstance: [],
      prepareRow: [],
      getTableProps: [defaultGetTableProps],
      getTableBodyProps: [defaultGetTableBodyProps],
      getHeaderGroupProps: [defaultGetHeaderGroupProps],
      getFooterGroupProps: [defaultGetFooterGroupProps],
      getHeaderProps: [defaultGetHeaderProps],
      getFooterProps: [defaultGetFooterProps],
      getRowProps: [defaultGetRowProps],
      getCellProps: [defaultGetCellProps],
      useFinalInstance: []
    };
  }

  actions.resetHiddenColumns = 'resetHiddenColumns';
  actions.toggleHideColumn = 'toggleHideColumn';
  actions.setHiddenColumns = 'setHiddenColumns';
  actions.toggleHideAllColumns = 'toggleHideAllColumns';
  var useColumnVisibility = function useColumnVisibility(hooks) {
    hooks.getToggleHiddenProps = [defaultGetToggleHiddenProps];
    hooks.getToggleHideAllColumnsProps = [defaultGetToggleHideAllColumnsProps];
    hooks.stateReducers.push(reducer);
    hooks.useInstanceBeforeDimensions.push(useInstanceBeforeDimensions);
    hooks.headerGroupsDeps.push(function (deps, _ref) {
      var instance = _ref.instance;
      return [].concat(deps, [instance.state.hiddenColumns]);
    });
    hooks.useInstance.push(useInstance);
  };
  useColumnVisibility.pluginName = 'useColumnVisibility';

  var defaultGetToggleHiddenProps = function defaultGetToggleHiddenProps(props, _ref2) {
    var column = _ref2.column;
    return [props, {
      onChange: function onChange(e) {
        column.toggleHidden(!e.target.checked);
      },
      style: {
        cursor: 'pointer'
      },
      checked: column.isVisible,
      title: 'Toggle Column Visible'
    }];
  };

  var defaultGetToggleHideAllColumnsProps = function defaultGetToggleHideAllColumnsProps(props, _ref3) {
    var instance = _ref3.instance;
    return [props, {
      onChange: function onChange(e) {
        instance.toggleHideAllColumns(!e.target.checked);
      },
      style: {
        cursor: 'pointer'
      },
      checked: !instance.allColumnsHidden && !instance.state.hiddenColumns.length,
      title: 'Toggle All Columns Hidden',
      indeterminate: !instance.allColumnsHidden && instance.state.hiddenColumns.length
    }];
  };

  function reducer(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        hiddenColumns: []
      }, state);
    }

    if (action.type === actions.resetHiddenColumns) {
      return _extends({}, state, {
        hiddenColumns: instance.initialState.hiddenColumns || []
      });
    }

    if (action.type === actions.toggleHideColumn) {
      var should = typeof action.value !== 'undefined' ? action.value : !state.hiddenColumns.includes(action.columnId);
      var hiddenColumns = should ? [].concat(state.hiddenColumns, [action.columnId]) : state.hiddenColumns.filter(function (d) {
        return d !== action.columnId;
      });
      return _extends({}, state, {
        hiddenColumns: hiddenColumns
      });
    }

    if (action.type === actions.setHiddenColumns) {
      return _extends({}, state, {
        hiddenColumns: functionalUpdate(action.value, state.hiddenColumns)
      });
    }

    if (action.type === actions.toggleHideAllColumns) {
      var shouldAll = typeof action.value !== 'undefined' ? action.value : !state.hiddenColumns.length;
      return _extends({}, state, {
        hiddenColumns: shouldAll ? instance.allColumns.map(function (d) {
          return d.id;
        }) : []
      });
    }
  }

  function useInstanceBeforeDimensions(instance) {
    var headers = instance.headers,
        hiddenColumns = instance.state.hiddenColumns;
    var isMountedRef = React.useRef(false);

    if (!isMountedRef.current) ;

    var handleColumn = function handleColumn(column, parentVisible) {
      column.isVisible = parentVisible && !hiddenColumns.includes(column.id);
      var totalVisibleHeaderCount = 0;

      if (column.headers && column.headers.length) {
        column.headers.forEach(function (subColumn) {
          return totalVisibleHeaderCount += handleColumn(subColumn, column.isVisible);
        });
      } else {
        totalVisibleHeaderCount = column.isVisible ? 1 : 0;
      }

      column.totalVisibleHeaderCount = totalVisibleHeaderCount;
      return totalVisibleHeaderCount;
    };

    var totalVisibleHeaderCount = 0;
    headers.forEach(function (subHeader) {
      return totalVisibleHeaderCount += handleColumn(subHeader, true);
    });
  }

  function useInstance(instance) {
    var columns = instance.columns,
        flatHeaders = instance.flatHeaders,
        dispatch = instance.dispatch,
        allColumns = instance.allColumns,
        getHooks = instance.getHooks,
        hiddenColumns = instance.state.hiddenColumns,
        _instance$autoResetHi = instance.autoResetHiddenColumns,
        autoResetHiddenColumns = _instance$autoResetHi === void 0 ? true : _instance$autoResetHi;
    var getInstance = useGetLatest(instance);
    var allColumnsHidden = allColumns.length === hiddenColumns.length;
    var toggleHideColumn = React.useCallback(function (columnId, value) {
      return dispatch({
        type: actions.toggleHideColumn,
        columnId: columnId,
        value: value
      });
    }, [dispatch]);
    var setHiddenColumns = React.useCallback(function (value) {
      return dispatch({
        type: actions.setHiddenColumns,
        value: value
      });
    }, [dispatch]);
    var toggleHideAllColumns = React.useCallback(function (value) {
      return dispatch({
        type: actions.toggleHideAllColumns,
        value: value
      });
    }, [dispatch]);
    var getToggleHideAllColumnsProps = makePropGetter(getHooks().getToggleHideAllColumnsProps, {
      instance: getInstance()
    });
    flatHeaders.forEach(function (column) {
      column.toggleHidden = function (value) {
        dispatch({
          type: actions.toggleHideColumn,
          columnId: column.id,
          value: value
        });
      };

      column.getToggleHiddenProps = makePropGetter(getHooks().getToggleHiddenProps, {
        instance: getInstance(),
        column: column
      });
    });
    var getAutoResetHiddenColumns = useGetLatest(autoResetHiddenColumns);
    useMountedLayoutEffect(function () {
      if (getAutoResetHiddenColumns()) {
        dispatch({
          type: actions.resetHiddenColumns
        });
      }
    }, [dispatch, columns]);
    Object.assign(instance, {
      allColumnsHidden: allColumnsHidden,
      toggleHideColumn: toggleHideColumn,
      setHiddenColumns: setHiddenColumns,
      toggleHideAllColumns: toggleHideAllColumns,
      getToggleHideAllColumnsProps: getToggleHideAllColumnsProps
    });
  }

  var defaultInitialState = {};
  var defaultColumnInstance = {};

  var defaultReducer = function defaultReducer(state, action, prevState) {
    return state;
  };

  var defaultGetSubRows = function defaultGetSubRows(row, index) {
    return row.subRows || [];
  };

  var defaultGetRowId = function defaultGetRowId(row, index, parent) {
    return "" + (parent ? [parent.id, index].join('.') : index);
  };

  var defaultUseControlledState = function defaultUseControlledState(d) {
    return d;
  };

  function applyDefaults(props) {
    var _props$initialState = props.initialState,
        initialState = _props$initialState === void 0 ? defaultInitialState : _props$initialState,
        _props$defaultColumn = props.defaultColumn,
        defaultColumn = _props$defaultColumn === void 0 ? defaultColumnInstance : _props$defaultColumn,
        _props$getSubRows = props.getSubRows,
        getSubRows = _props$getSubRows === void 0 ? defaultGetSubRows : _props$getSubRows,
        _props$getRowId = props.getRowId,
        getRowId = _props$getRowId === void 0 ? defaultGetRowId : _props$getRowId,
        _props$stateReducer = props.stateReducer,
        stateReducer = _props$stateReducer === void 0 ? defaultReducer : _props$stateReducer,
        _props$useControlledS = props.useControlledState,
        useControlledState = _props$useControlledS === void 0 ? defaultUseControlledState : _props$useControlledS,
        rest = _objectWithoutPropertiesLoose(props, ["initialState", "defaultColumn", "getSubRows", "getRowId", "stateReducer", "useControlledState"]);

    return _extends({}, rest, {
      initialState: initialState,
      defaultColumn: defaultColumn,
      getSubRows: getSubRows,
      getRowId: getRowId,
      stateReducer: stateReducer,
      useControlledState: useControlledState
    });
  }

  var useTable = function useTable(props) {
    for (var _len = arguments.length, plugins = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      plugins[_key - 1] = arguments[_key];
    }

    // Apply default props
    props = applyDefaults(props); // Add core plugins

    plugins = [useColumnVisibility].concat(plugins); // Create the table instance

    var instanceRef = React.useRef({}); // Create a getter for the instance (helps avoid a lot of potential memory leaks)

    var getInstance = useGetLatest(instanceRef.current); // Assign the props, plugins and hooks to the instance

    Object.assign(getInstance(), _extends({}, props, {
      plugins: plugins,
      hooks: makeDefaultPluginHooks()
    })); // Allow plugins to register hooks as early as possible

    plugins.filter(Boolean).forEach(function (plugin) {
      plugin(getInstance().hooks);
    }); // Consume all hooks and make a getter for them

    var getHooks = useGetLatest(getInstance().hooks);
    getInstance().getHooks = getHooks;
    delete getInstance().hooks; // Allow useOptions hooks to modify the options coming into the table

    Object.assign(getInstance(), reduceHooks(getHooks().useOptions, applyDefaults(props)));

    var _getInstance = getInstance(),
        data = _getInstance.data,
        userColumns = _getInstance.columns,
        initialState = _getInstance.initialState,
        defaultColumn = _getInstance.defaultColumn,
        getSubRows = _getInstance.getSubRows,
        getRowId = _getInstance.getRowId,
        stateReducer = _getInstance.stateReducer,
        useControlledState = _getInstance.useControlledState; // Setup user reducer ref


    var getStateReducer = useGetLatest(stateReducer); // Build the reducer

    var reducer = React.useCallback(function (state, action) {
      // Detect invalid actions
      if (!action.type) {
        console.info({
          action: action
        });
        throw new Error('Unknown Action 👆');
      } // Reduce the state from all plugin reducers


      return [].concat(getHooks().stateReducers, Array.isArray(getStateReducer()) ? getStateReducer() : [getStateReducer()]).reduce(function (s, handler) {
        return handler(s, action, state, getInstance()) || s;
      }, state);
    }, [getHooks, getStateReducer, getInstance]); // Start the reducer

    var _React$useReducer = React.useReducer(reducer, undefined, function () {
      return reducer(initialState, {
        type: actions.init
      });
    }),
        reducerState = _React$useReducer[0],
        dispatch = _React$useReducer[1]; // Allow the user to control the final state with hooks


    var state = reduceHooks([].concat(getHooks().useControlledState, [useControlledState]), reducerState, {
      instance: getInstance()
    });
    Object.assign(getInstance(), {
      state: state,
      dispatch: dispatch
    }); // Decorate All the columns

    var columns = React.useMemo(function () {
      return linkColumnStructure(reduceHooks(getHooks().columns, userColumns, {
        instance: getInstance()
      }));
    }, [getHooks, getInstance, userColumns].concat(reduceHooks(getHooks().columnsDeps, [], {
      instance: getInstance()
    })));
    getInstance().columns = columns; // Get the flat list of all columns and allow hooks to decorate
    // those columns (and trigger this memoization via deps)

    var allColumns = React.useMemo(function () {
      return reduceHooks(getHooks().allColumns, flattenColumns(columns), {
        instance: getInstance()
      }).map(assignColumnAccessor);
    }, [columns, getHooks, getInstance].concat(reduceHooks(getHooks().allColumnsDeps, [], {
      instance: getInstance()
    })));
    getInstance().allColumns = allColumns; // Access the row model using initial columns

    var _React$useMemo = React.useMemo(function () {
      var rows = [];
      var flatRows = [];
      var rowsById = {};
      var allColumnsQueue = [].concat(allColumns);

      while (allColumnsQueue.length) {
        var column = allColumnsQueue.shift();
        accessRowsForColumn({
          data: data,
          rows: rows,
          flatRows: flatRows,
          rowsById: rowsById,
          column: column,
          getRowId: getRowId,
          getSubRows: getSubRows,
          accessValueHooks: getHooks().accessValue,
          getInstance: getInstance
        });
      }

      return [rows, flatRows, rowsById];
    }, [allColumns, data, getRowId, getSubRows, getHooks, getInstance]),
        rows = _React$useMemo[0],
        flatRows = _React$useMemo[1],
        rowsById = _React$useMemo[2];

    Object.assign(getInstance(), {
      rows: rows,
      initialRows: [].concat(rows),
      flatRows: flatRows,
      rowsById: rowsById // materializedColumns,

    });
    loopHooks(getHooks().useInstanceAfterData, getInstance()); // Get the flat list of all columns AFTER the rows
    // have been access, and allow hooks to decorate
    // those columns (and trigger this memoization via deps)

    var visibleColumns = React.useMemo(function () {
      return reduceHooks(getHooks().visibleColumns, allColumns, {
        instance: getInstance()
      }).map(function (d) {
        return decorateColumn(d, defaultColumn);
      });
    }, [getHooks, allColumns, getInstance, defaultColumn].concat(reduceHooks(getHooks().visibleColumnsDeps, [], {
      instance: getInstance()
    }))); // Combine new visible columns with all columns

    allColumns = React.useMemo(function () {
      var columns = [].concat(visibleColumns);
      allColumns.forEach(function (column) {
        if (!columns.find(function (d) {
          return d.id === column.id;
        })) {
          columns.push(column);
        }
      });
      return columns;
    }, [allColumns, visibleColumns]);
    getInstance().allColumns = allColumns;

    {
      var duplicateColumns = allColumns.filter(function (column, i) {
        return allColumns.findIndex(function (d) {
          return d.id === column.id;
        }) !== i;
      });

      if (duplicateColumns.length) {
        console.info(allColumns);
        throw new Error("Duplicate columns were found with ids: \"" + duplicateColumns.map(function (d) {
          return d.id;
        }).join(', ') + "\" in the columns array above");
      }
    } // Make the headerGroups


    var headerGroups = React.useMemo(function () {
      return reduceHooks(getHooks().headerGroups, makeHeaderGroups(visibleColumns, defaultColumn), getInstance());
    }, [getHooks, visibleColumns, defaultColumn, getInstance].concat(reduceHooks(getHooks().headerGroupsDeps, [], {
      instance: getInstance()
    })));
    getInstance().headerGroups = headerGroups; // Get the first level of headers

    var headers = React.useMemo(function () {
      return headerGroups.length ? headerGroups[0].headers : [];
    }, [headerGroups]);
    getInstance().headers = headers; // Provide a flat header list for utilities

    getInstance().flatHeaders = headerGroups.reduce(function (all, headerGroup) {
      return [].concat(all, headerGroup.headers);
    }, []);
    loopHooks(getHooks().useInstanceBeforeDimensions, getInstance()); // Filter columns down to visible ones

    var visibleColumnsDep = visibleColumns.filter(function (d) {
      return d.isVisible;
    }).map(function (d) {
      return d.id;
    }).sort().join('_');
    visibleColumns = React.useMemo(function () {
      return visibleColumns.filter(function (d) {
        return d.isVisible;
      });
    }, // eslint-disable-next-line react-hooks/exhaustive-deps
    [visibleColumns, visibleColumnsDep]);
    getInstance().visibleColumns = visibleColumns; // Header Visibility is needed by this point

    var _calculateHeaderWidth = calculateHeaderWidths(headers),
        totalColumnsMinWidth = _calculateHeaderWidth[0],
        totalColumnsWidth = _calculateHeaderWidth[1],
        totalColumnsMaxWidth = _calculateHeaderWidth[2];

    getInstance().totalColumnsMinWidth = totalColumnsMinWidth;
    getInstance().totalColumnsWidth = totalColumnsWidth;
    getInstance().totalColumnsMaxWidth = totalColumnsMaxWidth;
    loopHooks(getHooks().useInstance, getInstance()) // Each materialized header needs to be assigned a render function and other
    // prop getter properties here.
    ;
    [].concat(getInstance().flatHeaders, getInstance().allColumns).forEach(function (column) {
      // Give columns/headers rendering power
      column.render = makeRenderer(getInstance(), column); // Give columns/headers a default getHeaderProps

      column.getHeaderProps = makePropGetter(getHooks().getHeaderProps, {
        instance: getInstance(),
        column: column
      }); // Give columns/headers a default getFooterProps

      column.getFooterProps = makePropGetter(getHooks().getFooterProps, {
        instance: getInstance(),
        column: column
      });
    });
    getInstance().headerGroups = React.useMemo(function () {
      return headerGroups.filter(function (headerGroup, i) {
        // Filter out any headers and headerGroups that don't have visible columns
        headerGroup.headers = headerGroup.headers.filter(function (column) {
          var recurse = function recurse(headers) {
            return headers.filter(function (column) {
              if (column.headers) {
                return recurse(column.headers);
              }

              return column.isVisible;
            }).length;
          };

          if (column.headers) {
            return recurse(column.headers);
          }

          return column.isVisible;
        }); // Give headerGroups getRowProps

        if (headerGroup.headers.length) {
          headerGroup.getHeaderGroupProps = makePropGetter(getHooks().getHeaderGroupProps, {
            instance: getInstance(),
            headerGroup: headerGroup,
            index: i
          });
          headerGroup.getFooterGroupProps = makePropGetter(getHooks().getFooterGroupProps, {
            instance: getInstance(),
            headerGroup: headerGroup,
            index: i
          });
          return true;
        }

        return false;
      });
    }, [headerGroups, getInstance, getHooks]);
    getInstance().footerGroups = [].concat(getInstance().headerGroups).reverse(); // The prepareRow function is absolutely necessary and MUST be called on
    // any rows the user wishes to be displayed.

    getInstance().prepareRow = React.useCallback(function (row) {
      row.getRowProps = makePropGetter(getHooks().getRowProps, {
        instance: getInstance(),
        row: row
      }); // Build the visible cells for each row

      row.allCells = allColumns.map(function (column) {
        var value = row.values[column.id];
        var cell = {
          column: column,
          row: row,
          value: value
        }; // Give each cell a getCellProps base

        cell.getCellProps = makePropGetter(getHooks().getCellProps, {
          instance: getInstance(),
          cell: cell
        }); // Give each cell a renderer function (supports multiple renderers)

        cell.render = makeRenderer(getInstance(), column, {
          row: row,
          cell: cell,
          value: value
        });
        return cell;
      });
      row.cells = visibleColumns.map(function (column) {
        return row.allCells.find(function (cell) {
          return cell.column.id === column.id;
        });
      }); // need to apply any row specific hooks (useExpanded requires this)

      loopHooks(getHooks().prepareRow, row, {
        instance: getInstance()
      });
    }, [getHooks, getInstance, allColumns, visibleColumns]);
    getInstance().getTableProps = makePropGetter(getHooks().getTableProps, {
      instance: getInstance()
    });
    getInstance().getTableBodyProps = makePropGetter(getHooks().getTableBodyProps, {
      instance: getInstance()
    });
    loopHooks(getHooks().useFinalInstance, getInstance());
    return getInstance();
  };

  function calculateHeaderWidths(headers, left) {
    if (left === void 0) {
      left = 0;
    }

    var sumTotalMinWidth = 0;
    var sumTotalWidth = 0;
    var sumTotalMaxWidth = 0;
    var sumTotalFlexWidth = 0;
    headers.forEach(function (header) {
      var subHeaders = header.headers;
      header.totalLeft = left;

      if (subHeaders && subHeaders.length) {
        var _calculateHeaderWidth2 = calculateHeaderWidths(subHeaders, left),
            totalMinWidth = _calculateHeaderWidth2[0],
            totalWidth = _calculateHeaderWidth2[1],
            totalMaxWidth = _calculateHeaderWidth2[2],
            totalFlexWidth = _calculateHeaderWidth2[3];

        header.totalMinWidth = totalMinWidth;
        header.totalWidth = totalWidth;
        header.totalMaxWidth = totalMaxWidth;
        header.totalFlexWidth = totalFlexWidth;
      } else {
        header.totalMinWidth = header.minWidth;
        header.totalWidth = Math.min(Math.max(header.minWidth, header.width), header.maxWidth);
        header.totalMaxWidth = header.maxWidth;
        header.totalFlexWidth = header.canResize ? header.totalWidth : 0;
      }

      if (header.isVisible) {
        left += header.totalWidth;
        sumTotalMinWidth += header.totalMinWidth;
        sumTotalWidth += header.totalWidth;
        sumTotalMaxWidth += header.totalMaxWidth;
        sumTotalFlexWidth += header.totalFlexWidth;
      }
    });
    return [sumTotalMinWidth, sumTotalWidth, sumTotalMaxWidth, sumTotalFlexWidth];
  }

  function accessRowsForColumn(_ref) {
    var data = _ref.data,
        rows = _ref.rows,
        flatRows = _ref.flatRows,
        rowsById = _ref.rowsById,
        column = _ref.column,
        getRowId = _ref.getRowId,
        getSubRows = _ref.getSubRows,
        accessValueHooks = _ref.accessValueHooks,
        getInstance = _ref.getInstance;

    // Access the row's data column-by-column
    // We do it this way so we can incrementally add materialized
    // columns after the first pass and avoid excessive looping
    var accessRow = function accessRow(originalRow, rowIndex, depth, parent, parentRows) {
      if (depth === void 0) {
        depth = 0;
      }

      // Keep the original reference around
      var original = originalRow;
      var id = getRowId(originalRow, rowIndex, parent);
      var row = rowsById[id]; // If the row hasn't been created, let's make it

      if (!row) {
        row = {
          id: id,
          original: original,
          index: rowIndex,
          depth: depth,
          cells: [{}] // This is a dummy cell

        }; // Override common array functions (and the dummy cell's getCellProps function)
        // to show an error if it is accessed without calling prepareRow

        row.cells.map = unpreparedAccessWarning;
        row.cells.filter = unpreparedAccessWarning;
        row.cells.forEach = unpreparedAccessWarning;
        row.cells[0].getCellProps = unpreparedAccessWarning; // Create the cells and values

        row.values = {}; // Push this row into the parentRows array

        parentRows.push(row); // Keep track of every row in a flat array

        flatRows.push(row); // Also keep track of every row by its ID

        rowsById[id] = row; // Get the original subrows

        row.originalSubRows = getSubRows(originalRow, rowIndex); // Then recursively access them

        if (row.originalSubRows) {
          var subRows = [];
          row.originalSubRows.forEach(function (d, i) {
            return accessRow(d, i, depth + 1, row, subRows);
          }); // Keep the new subRows array on the row

          row.subRows = subRows;
        }
      } else if (row.subRows) {
        // If the row exists, then it's already been accessed
        // Keep recursing, but don't worry about passing the
        // accumlator array (those rows already exist)
        row.originalSubRows.forEach(function (d, i) {
          return accessRow(d, i, depth + 1, row);
        });
      } // If the column has an accessor, use it to get a value


      if (column.accessor) {
        row.values[column.id] = column.accessor(originalRow, rowIndex, row, parentRows, data);
      } // Allow plugins to manipulate the column value


      row.values[column.id] = reduceHooks(accessValueHooks, row.values[column.id], {
        row: row,
        column: column,
        instance: getInstance()
      }, true);
    };

    data.forEach(function (originalRow, rowIndex) {
      return accessRow(originalRow, rowIndex, 0, undefined, rows);
    });
  }

  actions.resetExpanded = 'resetExpanded';
  actions.toggleRowExpanded = 'toggleRowExpanded';
  actions.toggleAllRowsExpanded = 'toggleAllRowsExpanded';
  var useExpanded = function useExpanded(hooks) {
    hooks.getToggleAllRowsExpandedProps = [defaultGetToggleAllRowsExpandedProps];
    hooks.getToggleRowExpandedProps = [defaultGetToggleRowExpandedProps];
    hooks.stateReducers.push(reducer$1);
    hooks.useInstance.push(useInstance$1);
    hooks.prepareRow.push(prepareRow);
  };
  useExpanded.pluginName = 'useExpanded';

  var defaultGetToggleAllRowsExpandedProps = function defaultGetToggleAllRowsExpandedProps(props, _ref) {
    var instance = _ref.instance;
    return [props, {
      onClick: function onClick(e) {
        instance.toggleAllRowsExpanded();
      },
      style: {
        cursor: 'pointer'
      },
      title: 'Toggle All Rows Expanded'
    }];
  };

  var defaultGetToggleRowExpandedProps = function defaultGetToggleRowExpandedProps(props, _ref2) {
    var row = _ref2.row;
    return [props, {
      onClick: function onClick() {
        row.toggleRowExpanded();
      },
      style: {
        cursor: 'pointer'
      },
      title: 'Toggle Row Expanded'
    }];
  }; // Reducer


  function reducer$1(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        expanded: {}
      }, state);
    }

    if (action.type === actions.resetExpanded) {
      return _extends({}, state, {
        expanded: instance.initialState.expanded || {}
      });
    }

    if (action.type === actions.toggleAllRowsExpanded) {
      var value = action.value;
      var rowsById = instance.rowsById;
      var isAllRowsExpanded = Object.keys(rowsById).length === Object.keys(state.expanded).length;
      var expandAll = typeof value !== 'undefined' ? value : !isAllRowsExpanded;

      if (expandAll) {
        var expanded = {};
        Object.keys(rowsById).forEach(function (rowId) {
          expanded[rowId] = true;
        });
        return _extends({}, state, {
          expanded: expanded
        });
      }

      return _extends({}, state, {
        expanded: {}
      });
    }

    if (action.type === actions.toggleRowExpanded) {
      var id = action.id,
          setExpanded = action.value;
      var exists = state.expanded[id];
      var shouldExist = typeof setExpanded !== 'undefined' ? setExpanded : !exists;

      if (!exists && shouldExist) {
        var _extends2;

        return _extends({}, state, {
          expanded: _extends({}, state.expanded, (_extends2 = {}, _extends2[id] = true, _extends2))
        });
      } else if (exists && !shouldExist) {
        var _state$expanded = state.expanded,
            _ = _state$expanded[id],
            rest = _objectWithoutPropertiesLoose(_state$expanded, [id].map(_toPropertyKey));

        return _extends({}, state, {
          expanded: rest
        });
      } else {
        return state;
      }
    }
  }

  function useInstance$1(instance) {
    var data = instance.data,
        rows = instance.rows,
        rowsById = instance.rowsById,
        _instance$manualExpan = instance.manualExpandedKey,
        manualExpandedKey = _instance$manualExpan === void 0 ? 'expanded' : _instance$manualExpan,
        _instance$paginateExp = instance.paginateExpandedRows,
        paginateExpandedRows = _instance$paginateExp === void 0 ? true : _instance$paginateExp,
        _instance$expandSubRo = instance.expandSubRows,
        expandSubRows = _instance$expandSubRo === void 0 ? true : _instance$expandSubRo,
        _instance$autoResetEx = instance.autoResetExpanded,
        autoResetExpanded = _instance$autoResetEx === void 0 ? true : _instance$autoResetEx,
        getHooks = instance.getHooks,
        plugins = instance.plugins,
        expanded = instance.state.expanded,
        dispatch = instance.dispatch;
    ensurePluginOrder(plugins, ['useSortBy', 'useGroupBy', 'usePivotColumns', 'useGlobalFilter'], 'useExpanded');
    var getAutoResetExpanded = useGetLatest(autoResetExpanded);
    var isAllRowsExpanded = Boolean(Object.keys(rowsById).length && Object.keys(expanded).length);

    if (isAllRowsExpanded) {
      if (Object.keys(rowsById).some(function (id) {
        return !expanded[id];
      })) {
        isAllRowsExpanded = false;
      }
    } // Bypass any effects from firing when this changes


    useMountedLayoutEffect(function () {
      if (getAutoResetExpanded()) {
        dispatch({
          type: actions.resetExpanded
        });
      }
    }, [dispatch, data]);
    var toggleRowExpanded = React.useCallback(function (id, value) {
      dispatch({
        type: actions.toggleRowExpanded,
        id: id,
        value: value
      });
    }, [dispatch]);
    var toggleAllRowsExpanded = React.useCallback(function (value) {
      return dispatch({
        type: actions.toggleAllRowsExpanded,
        value: value
      });
    }, [dispatch]);
    var expandedRows = React.useMemo(function () {
      if (paginateExpandedRows) {
        return expandRows(rows, {
          manualExpandedKey: manualExpandedKey,
          expanded: expanded,
          expandSubRows: expandSubRows
        });
      }

      return rows;
    }, [paginateExpandedRows, rows, manualExpandedKey, expanded, expandSubRows]);
    var expandedDepth = React.useMemo(function () {
      return findExpandedDepth(expanded);
    }, [expanded]);
    var getInstance = useGetLatest(instance);
    var getToggleAllRowsExpandedProps = makePropGetter(getHooks().getToggleAllRowsExpandedProps, {
      instance: getInstance()
    });
    Object.assign(instance, {
      preExpandedRows: rows,
      expandedRows: expandedRows,
      rows: expandedRows,
      expandedDepth: expandedDepth,
      isAllRowsExpanded: isAllRowsExpanded,
      toggleRowExpanded: toggleRowExpanded,
      toggleAllRowsExpanded: toggleAllRowsExpanded,
      getToggleAllRowsExpandedProps: getToggleAllRowsExpandedProps
    });
  }

  function prepareRow(row, _ref3) {
    var getHooks = _ref3.instance.getHooks,
        instance = _ref3.instance;

    row.toggleRowExpanded = function (set) {
      return instance.toggleRowExpanded(row.id, set);
    };

    row.getToggleRowExpandedProps = makePropGetter(getHooks().getToggleRowExpandedProps, {
      instance: instance,
      row: row
    });
  }

  function findExpandedDepth(expanded) {
    var maxDepth = 0;
    Object.keys(expanded).forEach(function (id) {
      var splitId = id.split('.');
      maxDepth = Math.max(maxDepth, splitId.length);
    });
    return maxDepth;
  }

  var text = function text(rows, ids, filterValue) {
    rows = rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return String(rowValue).toLowerCase().includes(String(filterValue).toLowerCase());
      });
    });
    return rows;
  };

  text.autoRemove = function (val) {
    return !val;
  };

  var exactText = function exactText(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue !== undefined ? String(rowValue).toLowerCase() === String(filterValue).toLowerCase() : true;
      });
    });
  };

  exactText.autoRemove = function (val) {
    return !val;
  };

  var exactTextCase = function exactTextCase(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue !== undefined ? String(rowValue) === String(filterValue) : true;
      });
    });
  };

  exactTextCase.autoRemove = function (val) {
    return !val;
  };

  var includes = function includes(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue.includes(filterValue);
      });
    });
  };

  includes.autoRemove = function (val) {
    return !val || !val.length;
  };

  var includesAll = function includesAll(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue && rowValue.length && filterValue.every(function (val) {
          return rowValue.includes(val);
        });
      });
    });
  };

  includesAll.autoRemove = function (val) {
    return !val || !val.length;
  };

  var includesSome = function includesSome(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue && rowValue.length && filterValue.some(function (val) {
          return rowValue.includes(val);
        });
      });
    });
  };

  includesSome.autoRemove = function (val) {
    return !val || !val.length;
  };

  var includesValue = function includesValue(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return filterValue.includes(rowValue);
      });
    });
  };

  includesValue.autoRemove = function (val) {
    return !val || !val.length;
  };

  var exact = function exact(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue === filterValue;
      });
    });
  };

  exact.autoRemove = function (val) {
    return typeof val === 'undefined';
  };

  var equals = function equals(rows, ids, filterValue) {
    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id]; // eslint-disable-next-line eqeqeq

        return rowValue == filterValue;
      });
    });
  };

  equals.autoRemove = function (val) {
    return val == null;
  };

  var between = function between(rows, ids, filterValue) {
    var _ref = filterValue || [],
        min = _ref[0],
        max = _ref[1];

    min = typeof min === 'number' ? min : -Infinity;
    max = typeof max === 'number' ? max : Infinity;

    if (min > max) {
      var temp = min;
      min = max;
      max = temp;
    }

    return rows.filter(function (row) {
      return ids.some(function (id) {
        var rowValue = row.values[id];
        return rowValue >= min && rowValue <= max;
      });
    });
  };

  between.autoRemove = function (val) {
    return !val || typeof val[0] !== 'number' && typeof val[1] !== 'number';
  };

  var filterTypes = /*#__PURE__*/Object.freeze({
    __proto__: null,
    text: text,
    exactText: exactText,
    exactTextCase: exactTextCase,
    includes: includes,
    includesAll: includesAll,
    includesSome: includesSome,
    includesValue: includesValue,
    exact: exact,
    equals: equals,
    between: between
  });

  actions.resetFilters = 'resetFilters';
  actions.setFilter = 'setFilter';
  actions.setAllFilters = 'setAllFilters';
  var useFilters = function useFilters(hooks) {
    hooks.stateReducers.push(reducer$2);
    hooks.useInstance.push(useInstance$2);
  };
  useFilters.pluginName = 'useFilters';

  function reducer$2(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        filters: []
      }, state);
    }

    if (action.type === actions.resetFilters) {
      return _extends({}, state, {
        filters: instance.initialState.filters || []
      });
    }

    if (action.type === actions.setFilter) {
      var columnId = action.columnId,
          filterValue = action.filterValue;
      var allColumns = instance.allColumns,
          userFilterTypes = instance.filterTypes;
      var column = allColumns.find(function (d) {
        return d.id === columnId;
      });

      if (!column) {
        throw new Error("React-Table: Could not find a column with id: " + columnId);
      }

      var filterMethod = getFilterMethod(column.filter, userFilterTypes || {}, filterTypes);
      var previousfilter = state.filters.find(function (d) {
        return d.id === columnId;
      });
      var newFilter = functionalUpdate(filterValue, previousfilter && previousfilter.value); //

      if (shouldAutoRemoveFilter(filterMethod.autoRemove, newFilter, column)) {
        return _extends({}, state, {
          filters: state.filters.filter(function (d) {
            return d.id !== columnId;
          })
        });
      }

      if (previousfilter) {
        return _extends({}, state, {
          filters: state.filters.map(function (d) {
            if (d.id === columnId) {
              return {
                id: columnId,
                value: newFilter
              };
            }

            return d;
          })
        });
      }

      return _extends({}, state, {
        filters: [].concat(state.filters, [{
          id: columnId,
          value: newFilter
        }])
      });
    }

    if (action.type === actions.setAllFilters) {
      var filters = action.filters;
      var _allColumns = instance.allColumns,
          _userFilterTypes = instance.filterTypes;
      return _extends({}, state, {
        // Filter out undefined values
        filters: functionalUpdate(filters, state.filters).filter(function (filter) {
          var column = _allColumns.find(function (d) {
            return d.id === filter.id;
          });

          var filterMethod = getFilterMethod(column.filter, _userFilterTypes || {}, filterTypes);

          if (shouldAutoRemoveFilter(filterMethod.autoRemove, filter.value, column)) {
            return false;
          }

          return true;
        })
      });
    }
  }

  function useInstance$2(instance) {
    var data = instance.data,
        rows = instance.rows,
        flatRows = instance.flatRows,
        rowsById = instance.rowsById,
        allColumns = instance.allColumns,
        userFilterTypes = instance.filterTypes,
        manualFilters = instance.manualFilters,
        _instance$defaultCanF = instance.defaultCanFilter,
        defaultCanFilter = _instance$defaultCanF === void 0 ? false : _instance$defaultCanF,
        disableFilters = instance.disableFilters,
        filters = instance.state.filters,
        dispatch = instance.dispatch,
        _instance$autoResetFi = instance.autoResetFilters,
        autoResetFilters = _instance$autoResetFi === void 0 ? true : _instance$autoResetFi;
    var setFilter = React.useCallback(function (columnId, filterValue) {
      dispatch({
        type: actions.setFilter,
        columnId: columnId,
        filterValue: filterValue
      });
    }, [dispatch]);
    var setAllFilters = React.useCallback(function (filters) {
      dispatch({
        type: actions.setAllFilters,
        filters: filters
      });
    }, [dispatch]);
    allColumns.forEach(function (column) {
      var id = column.id,
          accessor = column.accessor,
          columnDefaultCanFilter = column.defaultCanFilter,
          columnDisableFilters = column.disableFilters; // Determine if a column is filterable

      column.canFilter = accessor ? getFirstDefined(columnDisableFilters === true ? false : undefined, disableFilters === true ? false : undefined, true) : getFirstDefined(columnDefaultCanFilter, defaultCanFilter, false); // Provide the column a way of updating the filter value

      column.setFilter = function (val) {
        return setFilter(column.id, val);
      }; // Provide the current filter value to the column for
      // convenience


      var found = filters.find(function (d) {
        return d.id === id;
      });
      column.filterValue = found && found.value;
    });

    var _React$useMemo = React.useMemo(function () {
      if (manualFilters || !filters.length) {
        return [rows, flatRows, rowsById];
      }

      var filteredFlatRows = [];
      var filteredRowsById = {}; // Filters top level and nested rows

      var filterRows = function filterRows(rows, depth) {
        if (depth === void 0) {
          depth = 0;
        }

        var filteredRows = rows;
        filteredRows = filters.reduce(function (filteredSoFar, _ref) {
          var columnId = _ref.id,
              filterValue = _ref.value;
          // Find the filters column
          var column = allColumns.find(function (d) {
            return d.id === columnId;
          });

          if (!column) {
            return filteredSoFar;
          }

          if (depth === 0) {
            column.preFilteredRows = filteredSoFar;
          }

          var filterMethod = getFilterMethod(column.filter, userFilterTypes || {}, filterTypes);

          if (!filterMethod) {
            console.warn("Could not find a valid 'column.filter' for column with the ID: " + column.id + ".");
            return filteredSoFar;
          } // Pass the rows, id, filterValue and column to the filterMethod
          // to get the filtered rows back


          column.filteredRows = filterMethod(filteredSoFar, [columnId], filterValue);
          return column.filteredRows;
        }, rows); // Apply the filter to any subRows
        // We technically could do this recursively in the above loop,
        // but that would severely hinder the API for the user, since they
        // would be required to do that recursion in some scenarios

        filteredRows.forEach(function (row) {
          filteredFlatRows.push(row);
          filteredRowsById[row.id] = row;

          if (!row.subRows) {
            return;
          }

          row.subRows = row.subRows && row.subRows.length > 0 ? filterRows(row.subRows, depth + 1) : row.subRows;
        });
        return filteredRows;
      };

      return [filterRows(rows), filteredFlatRows, filteredRowsById];
    }, [manualFilters, filters, rows, flatRows, rowsById, allColumns, userFilterTypes]),
        filteredRows = _React$useMemo[0],
        filteredFlatRows = _React$useMemo[1],
        filteredRowsById = _React$useMemo[2];

    React.useMemo(function () {
      // Now that each filtered column has it's partially filtered rows,
      // lets assign the final filtered rows to all of the other columns
      var nonFilteredColumns = allColumns.filter(function (column) {
        return !filters.find(function (d) {
          return d.id === column.id;
        });
      }); // This essentially enables faceted filter options to be built easily
      // using every column's preFilteredRows value

      nonFilteredColumns.forEach(function (column) {
        column.preFilteredRows = filteredRows;
        column.filteredRows = filteredRows;
      });
    }, [filteredRows, filters, allColumns]);
    var getAutoResetFilters = useGetLatest(autoResetFilters);
    useMountedLayoutEffect(function () {
      if (getAutoResetFilters()) {
        dispatch({
          type: actions.resetFilters
        });
      }
    }, [dispatch, manualFilters ? null : data]);
    Object.assign(instance, {
      preFilteredRows: rows,
      preFilteredFlatRows: flatRows,
      preFilteredRowsById: rowsById,
      filteredRows: filteredRows,
      filteredFlatRows: filteredFlatRows,
      filteredRowsById: filteredRowsById,
      rows: filteredRows,
      flatRows: filteredFlatRows,
      rowsById: filteredRowsById,
      setFilter: setFilter,
      setAllFilters: setAllFilters
    });
  }

  actions.resetGlobalFilter = 'resetGlobalFilter';
  actions.setGlobalFilter = 'setGlobalFilter';
  var useGlobalFilter = function useGlobalFilter(hooks) {
    hooks.stateReducers.push(reducer$3);
    hooks.useInstance.push(useInstance$3);
  };
  useGlobalFilter.pluginName = 'useGlobalFilter';

  function reducer$3(state, action, previousState, instance) {
    if (action.type === actions.resetGlobalFilter) {
      return _extends({}, state, {
        globalFilter: instance.initialState.globalFilter || undefined
      });
    }

    if (action.type === actions.setGlobalFilter) {
      var filterValue = action.filterValue;
      var userFilterTypes = instance.userFilterTypes;
      var filterMethod = getFilterMethod(instance.globalFilter, userFilterTypes || {}, filterTypes);
      var newFilter = functionalUpdate(filterValue, state.globalFilter); //

      if (shouldAutoRemoveFilter(filterMethod.autoRemove, newFilter)) {
        var globalFilter = state.globalFilter,
            stateWithoutGlobalFilter = _objectWithoutPropertiesLoose(state, ["globalFilter"]);

        return stateWithoutGlobalFilter;
      }

      return _extends({}, state, {
        globalFilter: newFilter
      });
    }
  }

  function useInstance$3(instance) {
    var data = instance.data,
        rows = instance.rows,
        flatRows = instance.flatRows,
        rowsById = instance.rowsById,
        allColumns = instance.allColumns,
        userFilterTypes = instance.filterTypes,
        globalFilter = instance.globalFilter,
        manualGlobalFilter = instance.manualGlobalFilter,
        globalFilterValue = instance.state.globalFilter,
        dispatch = instance.dispatch,
        _instance$autoResetGl = instance.autoResetGlobalFilter,
        autoResetGlobalFilter = _instance$autoResetGl === void 0 ? true : _instance$autoResetGl,
        disableGlobalFilter = instance.disableGlobalFilter;
    var setGlobalFilter = React.useCallback(function (filterValue) {
      dispatch({
        type: actions.setGlobalFilter,
        filterValue: filterValue
      });
    }, [dispatch]); // TODO: Create a filter cache for incremental high speed multi-filtering
    // This gets pretty complicated pretty fast, since you have to maintain a
    // cache for each row group (top-level rows, and each row's recursive subrows)
    // This would make multi-filtering a lot faster though. Too far?

    var _React$useMemo = React.useMemo(function () {
      if (manualGlobalFilter || typeof globalFilterValue === 'undefined') {
        return [rows, flatRows, rowsById];
      }

      var filteredFlatRows = [];
      var filteredRowsById = {};
      var filterMethod = getFilterMethod(globalFilter, userFilterTypes || {}, filterTypes);

      if (!filterMethod) {
        console.warn("Could not find a valid 'globalFilter' option.");
        return rows;
      }

      allColumns.forEach(function (column) {
        var columnDisableGlobalFilter = column.disableGlobalFilter;
        column.canFilter = getFirstDefined(columnDisableGlobalFilter === true ? false : undefined, disableGlobalFilter === true ? false : undefined, true);
      });
      var filterableColumns = allColumns.filter(function (c) {
        return c.canFilter === true;
      }); // Filters top level and nested rows

      var filterRows = function filterRows(filteredRows) {
        filteredRows = filterMethod(filteredRows, filterableColumns.map(function (d) {
          return d.id;
        }), globalFilterValue);
        filteredRows.forEach(function (row) {
          filteredFlatRows.push(row);
          filteredRowsById[row.id] = row;
          row.subRows = row.subRows && row.subRows.length ? filterRows(row.subRows) : row.subRows;
        });
        return filteredRows;
      };

      return [filterRows(rows), filteredFlatRows, filteredRowsById];
    }, [manualGlobalFilter, globalFilterValue, globalFilter, userFilterTypes, allColumns, rows, flatRows, rowsById, disableGlobalFilter]),
        globalFilteredRows = _React$useMemo[0],
        globalFilteredFlatRows = _React$useMemo[1],
        globalFilteredRowsById = _React$useMemo[2];

    var getAutoResetGlobalFilter = useGetLatest(autoResetGlobalFilter);
    useMountedLayoutEffect(function () {
      if (getAutoResetGlobalFilter()) {
        dispatch({
          type: actions.resetGlobalFilter
        });
      }
    }, [dispatch, manualGlobalFilter ? null : data]);
    Object.assign(instance, {
      preGlobalFilteredRows: rows,
      preGlobalFilteredFlatRows: flatRows,
      preGlobalFilteredRowsById: rowsById,
      globalFilteredRows: globalFilteredRows,
      globalFilteredFlatRows: globalFilteredFlatRows,
      globalFilteredRowsById: globalFilteredRowsById,
      rows: globalFilteredRows,
      flatRows: globalFilteredFlatRows,
      rowsById: globalFilteredRowsById,
      setGlobalFilter: setGlobalFilter,
      disableGlobalFilter: disableGlobalFilter
    });
  }

  function sum(values, aggregatedValues) {
    // It's faster to just add the aggregations together instead of
    // process leaf nodes individually
    return aggregatedValues.reduce(function (sum, next) {
      return sum + (typeof next === 'number' ? next : 0);
    }, 0);
  }
  function min(values) {
    var min = values[0] || 0;
    values.forEach(function (value) {
      if (typeof value === 'number') {
        min = Math.min(min, value);
      }
    });
    return min;
  }
  function max(values) {
    var max = values[0] || 0;
    values.forEach(function (value) {
      if (typeof value === 'number') {
        max = Math.max(max, value);
      }
    });
    return max;
  }
  function minMax(values) {
    var min = values[0] || 0;
    var max = values[0] || 0;
    values.forEach(function (value) {
      if (typeof value === 'number') {
        min = Math.min(min, value);
        max = Math.max(max, value);
      }
    });
    return min + ".." + max;
  }
  function average(values) {
    return sum(null, values) / values.length;
  }
  function median(values) {
    if (!values.length) {
      return null;
    }

    var mid = Math.floor(values.length / 2);
    var nums = [].concat(values).sort(function (a, b) {
      return a - b;
    });
    return values.length % 2 !== 0 ? nums[mid] : (nums[mid - 1] + nums[mid]) / 2;
  }
  function unique(values) {
    return Array.from(new Set(values).values());
  }
  function uniqueCount(values) {
    return new Set(values).size;
  }
  function count(values) {
    return values.length;
  }

  var aggregations = /*#__PURE__*/Object.freeze({
    __proto__: null,
    sum: sum,
    min: min,
    max: max,
    minMax: minMax,
    average: average,
    median: median,
    unique: unique,
    uniqueCount: uniqueCount,
    count: count
  });

  var emptyArray = [];
  var emptyObject = {}; // Actions

  actions.resetGroupBy = 'resetGroupBy';
  actions.setGroupBy = 'setGroupBy';
  actions.toggleGroupBy = 'toggleGroupBy';
  var useGroupBy = function useGroupBy(hooks) {
    hooks.getGroupByToggleProps = [defaultGetGroupByToggleProps];
    hooks.stateReducers.push(reducer$4);
    hooks.visibleColumnsDeps.push(function (deps, _ref) {
      var instance = _ref.instance;
      return [].concat(deps, [instance.state.groupBy]);
    });
    hooks.visibleColumns.push(visibleColumns);
    hooks.useInstance.push(useInstance$4);
    hooks.prepareRow.push(prepareRow$1);
  };
  useGroupBy.pluginName = 'useGroupBy';

  var defaultGetGroupByToggleProps = function defaultGetGroupByToggleProps(props, _ref2) {
    var header = _ref2.header;
    return [props, {
      onClick: header.canGroupBy ? function (e) {
        e.persist();
        header.toggleGroupBy();
      } : undefined,
      style: {
        cursor: header.canGroupBy ? 'pointer' : undefined
      },
      title: 'Toggle GroupBy'
    }];
  }; // Reducer


  function reducer$4(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        groupBy: []
      }, state);
    }

    if (action.type === actions.resetGroupBy) {
      return _extends({}, state, {
        groupBy: instance.initialState.groupBy || []
      });
    }

    if (action.type === actions.setGroupBy) {
      var value = action.value;
      return _extends({}, state, {
        groupBy: value
      });
    }

    if (action.type === actions.toggleGroupBy) {
      var columnId = action.columnId,
          setGroupBy = action.value;
      var resolvedGroupBy = typeof setGroupBy !== 'undefined' ? setGroupBy : !state.groupBy.includes(columnId);

      if (resolvedGroupBy) {
        return _extends({}, state, {
          groupBy: [].concat(state.groupBy, [columnId])
        });
      }

      return _extends({}, state, {
        groupBy: state.groupBy.filter(function (d) {
          return d !== columnId;
        })
      });
    }
  }

  function visibleColumns(columns, _ref3) {
    var groupBy = _ref3.instance.state.groupBy;
    // Sort grouped columns to the start of the column list
    // before the headers are built
    var groupByColumns = groupBy.map(function (g) {
      return columns.find(function (col) {
        return col.id === g;
      });
    }).filter(Boolean);
    var nonGroupByColumns = columns.filter(function (col) {
      return !groupBy.includes(col.id);
    });
    columns = [].concat(groupByColumns, nonGroupByColumns);
    columns.forEach(function (column) {
      column.isGrouped = groupBy.includes(column.id);
      column.groupedIndex = groupBy.indexOf(column.id);
    });
    return columns;
  }

  var defaultUserAggregations = {};

  function useInstance$4(instance) {
    var data = instance.data,
        rows = instance.rows,
        flatRows = instance.flatRows,
        rowsById = instance.rowsById,
        allColumns = instance.allColumns,
        flatHeaders = instance.flatHeaders,
        _instance$groupByFn = instance.groupByFn,
        groupByFn = _instance$groupByFn === void 0 ? defaultGroupByFn : _instance$groupByFn,
        manualGroupBy = instance.manualGroupBy,
        _instance$aggregation = instance.aggregations,
        userAggregations = _instance$aggregation === void 0 ? defaultUserAggregations : _instance$aggregation,
        plugins = instance.plugins,
        groupBy = instance.state.groupBy,
        dispatch = instance.dispatch,
        _instance$autoResetGr = instance.autoResetGroupBy,
        autoResetGroupBy = _instance$autoResetGr === void 0 ? true : _instance$autoResetGr,
        disableGroupBy = instance.disableGroupBy,
        defaultCanGroupBy = instance.defaultCanGroupBy,
        getHooks = instance.getHooks;
    ensurePluginOrder(plugins, ['useColumnOrder', 'useFilters'], 'useGroupBy');
    var getInstance = useGetLatest(instance);
    allColumns.forEach(function (column) {
      var accessor = column.accessor,
          defaultColumnGroupBy = column.defaultGroupBy,
          columnDisableGroupBy = column.disableGroupBy;
      column.canGroupBy = accessor ? getFirstDefined(column.canGroupBy, columnDisableGroupBy === true ? false : undefined, disableGroupBy === true ? false : undefined, true) : getFirstDefined(column.canGroupBy, defaultColumnGroupBy, defaultCanGroupBy, false);

      if (column.canGroupBy) {
        column.toggleGroupBy = function () {
          return instance.toggleGroupBy(column.id);
        };
      }

      column.Aggregated = column.Aggregated || column.Cell;
    });
    var toggleGroupBy = React.useCallback(function (columnId, value) {
      dispatch({
        type: actions.toggleGroupBy,
        columnId: columnId,
        value: value
      });
    }, [dispatch]);
    var setGroupBy = React.useCallback(function (value) {
      dispatch({
        type: actions.setGroupBy,
        value: value
      });
    }, [dispatch]);
    flatHeaders.forEach(function (header) {
      header.getGroupByToggleProps = makePropGetter(getHooks().getGroupByToggleProps, {
        instance: getInstance(),
        header: header
      });
    });

    var _React$useMemo = React.useMemo(function () {
      if (manualGroupBy || !groupBy.length) {
        return [rows, flatRows, rowsById, emptyArray, emptyObject, flatRows, rowsById];
      } // Ensure that the list of filtered columns exist


      var existingGroupBy = groupBy.filter(function (g) {
        return allColumns.find(function (col) {
          return col.id === g;
        });
      }); // Find the columns that can or are aggregating
      // Uses each column to aggregate rows into a single value

      var aggregateRowsToValues = function aggregateRowsToValues(leafRows, groupedRows, depth) {
        var values = {};
        allColumns.forEach(function (column) {
          // Don't aggregate columns that are in the groupBy
          if (existingGroupBy.includes(column.id)) {
            values[column.id] = groupedRows[0] ? groupedRows[0].values[column.id] : null;
            return;
          } // Aggregate the values


          var aggregateFn = typeof column.aggregate === 'function' ? column.aggregate : userAggregations[column.aggregate] || aggregations[column.aggregate];

          if (aggregateFn) {
            // Get the columnValues to aggregate
            var groupedValues = groupedRows.map(function (row) {
              return row.values[column.id];
            }); // Get the columnValues to aggregate

            var leafValues = leafRows.map(function (row) {
              var columnValue = row.values[column.id];

              if (!depth && column.aggregateValue) {
                var aggregateValueFn = typeof column.aggregateValue === 'function' ? column.aggregateValue : userAggregations[column.aggregateValue] || aggregations[column.aggregateValue];

                if (!aggregateValueFn) {
                  console.info({
                    column: column
                  });
                  throw new Error("React Table: Invalid column.aggregateValue option for column listed above");
                }

                columnValue = aggregateValueFn(columnValue, row, column);
              }

              return columnValue;
            });
            values[column.id] = aggregateFn(leafValues, groupedValues);
          } else if (column.aggregate) {
            console.info({
              column: column
            });
            throw new Error("React Table: Invalid column.aggregate option for column listed above");
          } else {
            values[column.id] = null;
          }
        });
        return values;
      };

      var groupedFlatRows = [];
      var groupedRowsById = {};
      var onlyGroupedFlatRows = [];
      var onlyGroupedRowsById = {};
      var nonGroupedFlatRows = [];
      var nonGroupedRowsById = {}; // Recursively group the data

      var groupUpRecursively = function groupUpRecursively(rows, depth, parentId) {
        if (depth === void 0) {
          depth = 0;
        }

        // This is the last level, just return the rows
        if (depth === existingGroupBy.length) {
          return rows.map(function (row) {
            return _extends({}, row, {
              depth: depth
            });
          });
        }

        var columnId = existingGroupBy[depth]; // Group the rows together for this level

        var rowGroupsMap = groupByFn(rows, columnId); // Peform aggregations for each group

        var aggregatedGroupedRows = Object.entries(rowGroupsMap).map(function (_ref4, index) {
          var groupByVal = _ref4[0],
              groupedRows = _ref4[1];
          var id = columnId + ":" + groupByVal;
          id = parentId ? parentId + ">" + id : id; // First, Recurse to group sub rows before aggregation

          var subRows = groupUpRecursively(groupedRows, depth + 1, id); // Flatten the leaf rows of the rows in this group

          var leafRows = depth ? flattenBy(groupedRows, 'leafRows') : groupedRows;
          var values = aggregateRowsToValues(leafRows, groupedRows, depth);
          var row = {
            id: id,
            isGrouped: true,
            groupByID: columnId,
            groupByVal: groupByVal,
            values: values,
            subRows: subRows,
            leafRows: leafRows,
            depth: depth,
            index: index
          };
          subRows.forEach(function (subRow) {
            groupedFlatRows.push(subRow);
            groupedRowsById[subRow.id] = subRow;

            if (subRow.isGrouped) {
              onlyGroupedFlatRows.push(subRow);
              onlyGroupedRowsById[subRow.id] = subRow;
            } else {
              nonGroupedFlatRows.push(subRow);
              nonGroupedRowsById[subRow.id] = subRow;
            }
          });
          return row;
        });
        return aggregatedGroupedRows;
      };

      var groupedRows = groupUpRecursively(rows);
      groupedRows.forEach(function (subRow) {
        groupedFlatRows.push(subRow);
        groupedRowsById[subRow.id] = subRow;

        if (subRow.isGrouped) {
          onlyGroupedFlatRows.push(subRow);
          onlyGroupedRowsById[subRow.id] = subRow;
        } else {
          nonGroupedFlatRows.push(subRow);
          nonGroupedRowsById[subRow.id] = subRow;
        }
      }); // Assign the new data

      return [groupedRows, groupedFlatRows, groupedRowsById, onlyGroupedFlatRows, onlyGroupedRowsById, nonGroupedFlatRows, nonGroupedRowsById];
    }, [manualGroupBy, groupBy, rows, flatRows, rowsById, allColumns, userAggregations, groupByFn]),
        groupedRows = _React$useMemo[0],
        groupedFlatRows = _React$useMemo[1],
        groupedRowsById = _React$useMemo[2],
        onlyGroupedFlatRows = _React$useMemo[3],
        onlyGroupedRowsById = _React$useMemo[4],
        nonGroupedFlatRows = _React$useMemo[5],
        nonGroupedRowsById = _React$useMemo[6];

    var getAutoResetGroupBy = useGetLatest(autoResetGroupBy);
    useMountedLayoutEffect(function () {
      if (getAutoResetGroupBy()) {
        dispatch({
          type: actions.resetGroupBy
        });
      }
    }, [dispatch, manualGroupBy ? null : data]);
    Object.assign(instance, {
      preGroupedRows: rows,
      preGroupedFlatRow: flatRows,
      preGroupedRowsById: rowsById,
      groupedRows: groupedRows,
      groupedFlatRows: groupedFlatRows,
      groupedRowsById: groupedRowsById,
      onlyGroupedFlatRows: onlyGroupedFlatRows,
      onlyGroupedRowsById: onlyGroupedRowsById,
      nonGroupedFlatRows: nonGroupedFlatRows,
      nonGroupedRowsById: nonGroupedRowsById,
      rows: groupedRows,
      flatRows: groupedFlatRows,
      rowsById: groupedRowsById,
      toggleGroupBy: toggleGroupBy,
      setGroupBy: setGroupBy
    });
  }

  function prepareRow$1(row) {
    row.allCells.forEach(function (cell) {
      var _row$subRows;

      // Grouped cells are in the groupBy and the pivot cell for the row
      cell.isGrouped = cell.column.isGrouped && cell.column.id === row.groupByID; // Placeholder cells are any columns in the groupBy that are not grouped

      cell.isPlaceholder = !cell.isGrouped && cell.column.isGrouped; // Aggregated cells are not grouped, not repeated, but still have subRows

      cell.isAggregated = !cell.isGrouped && !cell.isPlaceholder && ((_row$subRows = row.subRows) == null ? void 0 : _row$subRows.length);
    });
  }

  function defaultGroupByFn(rows, columnId) {
    return rows.reduce(function (prev, row, i) {
      // TODO: Might want to implement a key serializer here so
      // irregular column values can still be grouped if needed?
      var resKey = "" + row.values[columnId];
      prev[resKey] = Array.isArray(prev[resKey]) ? prev[resKey] : [];
      prev[resKey].push(row);
      return prev;
    }, {});
  }

  var reSplitAlphaNumeric = /([0-9]+)/gm; // Mixed sorting is slow, but very inclusive of many edge cases.
  // It handles numbers, mixed alphanumeric combinations, and even
  // null, undefined, and Infinity

  var alphanumeric = function alphanumeric(rowA, rowB, columnId) {
    var _getRowValuesByColumn = getRowValuesByColumnID(rowA, rowB, columnId),
        a = _getRowValuesByColumn[0],
        b = _getRowValuesByColumn[1]; // Force to strings (or "" for unsupported types)


    a = toString(a);
    b = toString(b); // Split on number groups, but keep the delimiter
    // Then remove falsey split values

    a = a.split(reSplitAlphaNumeric).filter(Boolean);
    b = b.split(reSplitAlphaNumeric).filter(Boolean); // While

    while (a.length && b.length) {
      var aa = a.shift();
      var bb = b.shift();
      var an = parseInt(aa, 10);
      var bn = parseInt(bb, 10);
      var combo = [an, bn].sort(); // Both are string

      if (isNaN(combo[0])) {
        if (aa > bb) {
          return 1;
        }

        if (bb > aa) {
          return -1;
        }

        continue;
      } // One is a string, one is a number


      if (isNaN(combo[1])) {
        return isNaN(an) ? -1 : 1;
      } // Both are numbers


      if (an > bn) {
        return 1;
      }

      if (bn > an) {
        return -1;
      }
    }

    return a.length - b.length;
  };
  function datetime(rowA, rowB, columnId) {
    var _getRowValuesByColumn2 = getRowValuesByColumnID(rowA, rowB, columnId),
        a = _getRowValuesByColumn2[0],
        b = _getRowValuesByColumn2[1];

    a = a.getTime();
    b = b.getTime();
    return compareBasic(a, b);
  }
  function basic(rowA, rowB, columnId) {
    var _getRowValuesByColumn3 = getRowValuesByColumnID(rowA, rowB, columnId),
        a = _getRowValuesByColumn3[0],
        b = _getRowValuesByColumn3[1];

    return compareBasic(a, b);
  }
  function string(rowA, rowB, columnId) {
    var _getRowValuesByColumn4 = getRowValuesByColumnID(rowA, rowB, columnId),
        a = _getRowValuesByColumn4[0],
        b = _getRowValuesByColumn4[1];

    a = a.split('').filter(Boolean);
    b = b.split('').filter(Boolean);

    while (a.length && b.length) {
      var aa = a.shift();
      var bb = b.shift();
      var alower = aa.toLowerCase();
      var blower = bb.toLowerCase(); // Case insensitive comparison until characters match

      if (alower > blower) {
        return 1;
      }

      if (blower > alower) {
        return -1;
      } // If lowercase characters are identical


      if (aa > bb) {
        return 1;
      }

      if (bb > aa) {
        return -1;
      }

      continue;
    }

    return a.length - b.length;
  }
  function number(rowA, rowB, columnId) {
    var _getRowValuesByColumn5 = getRowValuesByColumnID(rowA, rowB, columnId),
        a = _getRowValuesByColumn5[0],
        b = _getRowValuesByColumn5[1];

    var replaceNonNumeric = /[^0-9.]/gi;
    a = Number(String(a).replace(replaceNonNumeric, ''));
    b = Number(String(b).replace(replaceNonNumeric, ''));
    return compareBasic(a, b);
  } // Utils

  function compareBasic(a, b) {
    return a === b ? 0 : a > b ? 1 : -1;
  }

  function getRowValuesByColumnID(row1, row2, columnId) {
    return [row1.values[columnId], row2.values[columnId]];
  }

  function toString(a) {
    if (typeof a === 'number') {
      if (isNaN(a) || a === Infinity || a === -Infinity) {
        return '';
      }

      return String(a);
    }

    if (typeof a === 'string') {
      return a;
    }

    return '';
  }

  var sortTypes = /*#__PURE__*/Object.freeze({
    __proto__: null,
    alphanumeric: alphanumeric,
    datetime: datetime,
    basic: basic,
    string: string,
    number: number
  });

  actions.resetSortBy = 'resetSortBy';
  actions.setSortBy = 'setSortBy';
  actions.toggleSortBy = 'toggleSortBy';
  actions.clearSortBy = 'clearSortBy';
  defaultColumn.sortType = 'alphanumeric';
  defaultColumn.sortDescFirst = false;
  var useSortBy = function useSortBy(hooks) {
    hooks.getSortByToggleProps = [defaultGetSortByToggleProps];
    hooks.stateReducers.push(reducer$5);
    hooks.useInstance.push(useInstance$5);
  };
  useSortBy.pluginName = 'useSortBy';

  var defaultGetSortByToggleProps = function defaultGetSortByToggleProps(props, _ref) {
    var instance = _ref.instance,
        column = _ref.column;
    var _instance$isMultiSort = instance.isMultiSortEvent,
        isMultiSortEvent = _instance$isMultiSort === void 0 ? function (e) {
      return e.shiftKey;
    } : _instance$isMultiSort;
    return [props, {
      onClick: column.canSort ? function (e) {
        e.persist();
        column.toggleSortBy(undefined, !instance.disableMultiSort && isMultiSortEvent(e));
      } : undefined,
      style: {
        cursor: column.canSort ? 'pointer' : undefined
      },
      title: column.canSort ? 'Toggle SortBy' : undefined
    }];
  }; // Reducer


  function reducer$5(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        sortBy: []
      }, state);
    }

    if (action.type === actions.resetSortBy) {
      return _extends({}, state, {
        sortBy: instance.initialState.sortBy || []
      });
    }

    if (action.type === actions.clearSortBy) {
      var sortBy = state.sortBy;
      var newSortBy = sortBy.filter(function (d) {
        return d.id !== action.columnId;
      });
      return _extends({}, state, {
        sortBy: newSortBy
      });
    }

    if (action.type === actions.setSortBy) {
      var _sortBy = action.sortBy;
      return _extends({}, state, {
        sortBy: _sortBy
      });
    }

    if (action.type === actions.toggleSortBy) {
      var columnId = action.columnId,
          desc = action.desc,
          multi = action.multi;
      var allColumns = instance.allColumns,
          disableMultiSort = instance.disableMultiSort,
          disableSortRemove = instance.disableSortRemove,
          disableMultiRemove = instance.disableMultiRemove,
          _instance$maxMultiSor = instance.maxMultiSortColCount,
          maxMultiSortColCount = _instance$maxMultiSor === void 0 ? Number.MAX_SAFE_INTEGER : _instance$maxMultiSor;
      var _sortBy2 = state.sortBy; // Find the column for this columnId

      var column = allColumns.find(function (d) {
        return d.id === columnId;
      });
      var sortDescFirst = column.sortDescFirst; // Find any existing sortBy for this column

      var existingSortBy = _sortBy2.find(function (d) {
        return d.id === columnId;
      });

      var existingIndex = _sortBy2.findIndex(function (d) {
        return d.id === columnId;
      });

      var hasDescDefined = typeof desc !== 'undefined' && desc !== null;
      var _newSortBy = []; // What should we do with this sort action?

      var sortAction;

      if (!disableMultiSort && multi) {
        if (existingSortBy) {
          sortAction = 'toggle';
        } else {
          sortAction = 'add';
        }
      } else {
        // Normal mode
        if (existingIndex !== _sortBy2.length - 1 || _sortBy2.length !== 1) {
          sortAction = 'replace';
        } else if (existingSortBy) {
          sortAction = 'toggle';
        } else {
          sortAction = 'replace';
        }
      } // Handle toggle states that will remove the sortBy


      if (sortAction === 'toggle' && // Must be toggling
      !disableSortRemove && // If disableSortRemove, disable in general
      !hasDescDefined && ( // Must not be setting desc
      multi ? !disableMultiRemove : true) && ( // If multi, don't allow if disableMultiRemove
      existingSortBy && // Finally, detect if it should indeed be removed
      existingSortBy.desc && !sortDescFirst || !existingSortBy.desc && sortDescFirst)) {
        sortAction = 'remove';
      }

      if (sortAction === 'replace') {
        _newSortBy = [{
          id: columnId,
          desc: hasDescDefined ? desc : sortDescFirst
        }];
      } else if (sortAction === 'add') {
        _newSortBy = [].concat(_sortBy2, [{
          id: columnId,
          desc: hasDescDefined ? desc : sortDescFirst
        }]); // Take latest n columns

        _newSortBy.splice(0, _newSortBy.length - maxMultiSortColCount);
      } else if (sortAction === 'toggle') {
        // This flips (or sets) the
        _newSortBy = _sortBy2.map(function (d) {
          if (d.id === columnId) {
            return _extends({}, d, {
              desc: hasDescDefined ? desc : !existingSortBy.desc
            });
          }

          return d;
        });
      } else if (sortAction === 'remove') {
        _newSortBy = _sortBy2.filter(function (d) {
          return d.id !== columnId;
        });
      }

      return _extends({}, state, {
        sortBy: _newSortBy
      });
    }
  }

  function useInstance$5(instance) {
    var data = instance.data,
        rows = instance.rows,
        flatRows = instance.flatRows,
        allColumns = instance.allColumns,
        _instance$orderByFn = instance.orderByFn,
        orderByFn = _instance$orderByFn === void 0 ? defaultOrderByFn : _instance$orderByFn,
        userSortTypes = instance.sortTypes,
        manualSortBy = instance.manualSortBy,
        defaultCanSort = instance.defaultCanSort,
        disableSortBy = instance.disableSortBy,
        flatHeaders = instance.flatHeaders,
        sortBy = instance.state.sortBy,
        dispatch = instance.dispatch,
        plugins = instance.plugins,
        getHooks = instance.getHooks,
        _instance$autoResetSo = instance.autoResetSortBy,
        autoResetSortBy = _instance$autoResetSo === void 0 ? true : _instance$autoResetSo;
    ensurePluginOrder(plugins, ['useFilters', 'useGlobalFilter', 'useGroupBy', 'usePivotColumns'], 'useSortBy');
    var setSortBy = React.useCallback(function (sortBy) {
      dispatch({
        type: actions.setSortBy,
        sortBy: sortBy
      });
    }, [dispatch]); // Updates sorting based on a columnId, desc flag and multi flag

    var toggleSortBy = React.useCallback(function (columnId, desc, multi) {
      dispatch({
        type: actions.toggleSortBy,
        columnId: columnId,
        desc: desc,
        multi: multi
      });
    }, [dispatch]); // use reference to avoid memory leak in #1608

    var getInstance = useGetLatest(instance); // Add the getSortByToggleProps method to columns and headers

    flatHeaders.forEach(function (column) {
      var accessor = column.accessor,
          defaultColumnCanSort = column.canSort,
          columnDisableSortBy = column.disableSortBy,
          id = column.id;
      var canSort = accessor ? getFirstDefined(columnDisableSortBy === true ? false : undefined, disableSortBy === true ? false : undefined, true) : getFirstDefined(defaultCanSort, defaultColumnCanSort, false);
      column.canSort = canSort;

      if (column.canSort) {
        column.toggleSortBy = function (desc, multi) {
          return toggleSortBy(column.id, desc, multi);
        };

        column.clearSortBy = function () {
          dispatch({
            type: actions.clearSortBy,
            columnId: column.id
          });
        };
      }

      column.getSortByToggleProps = makePropGetter(getHooks().getSortByToggleProps, {
        instance: getInstance(),
        column: column
      });
      var columnSort = sortBy.find(function (d) {
        return d.id === id;
      });
      column.isSorted = !!columnSort;
      column.sortedIndex = sortBy.findIndex(function (d) {
        return d.id === id;
      });
      column.isSortedDesc = column.isSorted ? columnSort.desc : undefined;
    });

    var _React$useMemo = React.useMemo(function () {
      if (manualSortBy || !sortBy.length) {
        return [rows, flatRows];
      }

      var sortedFlatRows = []; // Filter out sortBys that correspond to non existing columns

      var availableSortBy = sortBy.filter(function (sort) {
        return allColumns.find(function (col) {
          return col.id === sort.id;
        });
      });

      var sortData = function sortData(rows) {
        // Use the orderByFn to compose multiple sortBy's together.
        // This will also perform a stable sorting using the row index
        // if needed.
        var sortedData = orderByFn(rows, availableSortBy.map(function (sort) {
          // Support custom sorting methods for each column
          var column = allColumns.find(function (d) {
            return d.id === sort.id;
          });

          if (!column) {
            throw new Error("React-Table: Could not find a column with id: " + sort.id + " while sorting");
          }

          var sortType = column.sortType; // Look up sortBy functions in this order:
          // column function
          // column string lookup on user sortType
          // column string lookup on built-in sortType
          // default function
          // default string lookup on user sortType
          // default string lookup on built-in sortType

          var sortMethod = isFunction(sortType) || (userSortTypes || {})[sortType] || sortTypes[sortType];

          if (!sortMethod) {
            throw new Error("React-Table: Could not find a valid sortType of '" + sortType + "' for column '" + sort.id + "'.");
          } // Return the correct sortFn.
          // This function should always return in ascending order


          return function (a, b) {
            return sortMethod(a, b, sort.id, sort.desc);
          };
        }), // Map the directions
        availableSortBy.map(function (sort) {
          // Detect and use the sortInverted option
          var column = allColumns.find(function (d) {
            return d.id === sort.id;
          });

          if (column && column.sortInverted) {
            return sort.desc;
          }

          return !sort.desc;
        })); // If there are sub-rows, sort them

        sortedData.forEach(function (row) {
          sortedFlatRows.push(row);

          if (!row.subRows || row.subRows.length === 0) {
            return;
          }

          row.subRows = sortData(row.subRows);
        });
        return sortedData;
      };

      return [sortData(rows), sortedFlatRows];
    }, [manualSortBy, sortBy, rows, flatRows, allColumns, orderByFn, userSortTypes]),
        sortedRows = _React$useMemo[0],
        sortedFlatRows = _React$useMemo[1];

    var getAutoResetSortBy = useGetLatest(autoResetSortBy);
    useMountedLayoutEffect(function () {
      if (getAutoResetSortBy()) {
        dispatch({
          type: actions.resetSortBy
        });
      }
    }, [manualSortBy ? null : data]);
    Object.assign(instance, {
      preSortedRows: rows,
      preSortedFlatRows: flatRows,
      sortedRows: sortedRows,
      sortedFlatRows: sortedFlatRows,
      rows: sortedRows,
      flatRows: sortedFlatRows,
      setSortBy: setSortBy,
      toggleSortBy: toggleSortBy
    });
  }

  function defaultOrderByFn(arr, funcs, dirs) {
    return [].concat(arr).sort(function (rowA, rowB) {
      for (var i = 0; i < funcs.length; i += 1) {
        var sortFn = funcs[i];
        var desc = dirs[i] === false || dirs[i] === 'desc';
        var sortInt = sortFn(rowA, rowB);

        if (sortInt !== 0) {
          return desc ? -sortInt : sortInt;
        }
      }

      return dirs[0] ? rowA.index - rowB.index : rowB.index - rowA.index;
    });
  }

  var pluginName = 'usePagination'; // Actions

  actions.resetPage = 'resetPage';
  actions.gotoPage = 'gotoPage';
  actions.setPageSize = 'setPageSize';
  var usePagination = function usePagination(hooks) {
    hooks.stateReducers.push(reducer$6);
    hooks.useInstance.push(useInstance$6);
  };
  usePagination.pluginName = pluginName;

  function reducer$6(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        pageSize: 10,
        pageIndex: 0
      }, state);
    }

    if (action.type === actions.resetPage) {
      return _extends({}, state, {
        pageIndex: instance.initialState.pageIndex || 0
      });
    }

    if (action.type === actions.gotoPage) {
      var pageCount = instance.pageCount,
          page = instance.page;
      var newPageIndex = functionalUpdate(action.pageIndex, state.pageIndex);
      var canNavigate = false;

      if (newPageIndex > state.pageIndex) {
        // next page
        canNavigate = pageCount === -1 ? page.length >= state.pageSize : newPageIndex < pageCount;
      } else if (newPageIndex < state.pageIndex) {
        // prev page
        canNavigate = newPageIndex > -1;
      }

      if (!canNavigate) {
        return state;
      }

      return _extends({}, state, {
        pageIndex: newPageIndex
      });
    }

    if (action.type === actions.setPageSize) {
      var pageSize = action.pageSize;
      var topRowIndex = state.pageSize * state.pageIndex;
      var pageIndex = Math.floor(topRowIndex / pageSize);
      return _extends({}, state, {
        pageIndex: pageIndex,
        pageSize: pageSize
      });
    }
  }

  function useInstance$6(instance) {
    var rows = instance.rows,
        _instance$autoResetPa = instance.autoResetPage,
        autoResetPage = _instance$autoResetPa === void 0 ? true : _instance$autoResetPa,
        _instance$manualExpan = instance.manualExpandedKey,
        manualExpandedKey = _instance$manualExpan === void 0 ? 'expanded' : _instance$manualExpan,
        plugins = instance.plugins,
        userPageCount = instance.pageCount,
        _instance$paginateExp = instance.paginateExpandedRows,
        paginateExpandedRows = _instance$paginateExp === void 0 ? true : _instance$paginateExp,
        _instance$expandSubRo = instance.expandSubRows,
        expandSubRows = _instance$expandSubRo === void 0 ? true : _instance$expandSubRo,
        _instance$state = instance.state,
        pageSize = _instance$state.pageSize,
        pageIndex = _instance$state.pageIndex,
        expanded = _instance$state.expanded,
        globalFilter = _instance$state.globalFilter,
        filters = _instance$state.filters,
        groupBy = _instance$state.groupBy,
        sortBy = _instance$state.sortBy,
        dispatch = instance.dispatch,
        data = instance.data,
        manualPagination = instance.manualPagination;
    ensurePluginOrder(plugins, ['useGlobalFilter', 'useFilters', 'useGroupBy', 'useSortBy', 'useExpanded'], 'usePagination');
    var getAutoResetPage = useGetLatest(autoResetPage);
    useMountedLayoutEffect(function () {
      if (getAutoResetPage()) {
        dispatch({
          type: actions.resetPage
        });
      }
    }, [dispatch, manualPagination ? null : data, globalFilter, filters, groupBy, sortBy]);
    var pageCount = manualPagination ? userPageCount : Math.ceil(rows.length / pageSize);
    var pageOptions = React.useMemo(function () {
      return pageCount > 0 ? [].concat(new Array(pageCount)).fill(null).map(function (d, i) {
        return i;
      }) : [];
    }, [pageCount]);
    var page = React.useMemo(function () {
      var page;

      if (manualPagination) {
        page = rows;
      } else {
        var pageStart = pageSize * pageIndex;
        var pageEnd = pageStart + pageSize;
        page = rows.slice(pageStart, pageEnd);
      }

      if (paginateExpandedRows) {
        return page;
      }

      return expandRows(page, {
        manualExpandedKey: manualExpandedKey,
        expanded: expanded,
        expandSubRows: expandSubRows
      });
    }, [expandSubRows, expanded, manualExpandedKey, manualPagination, pageIndex, pageSize, paginateExpandedRows, rows]);
    var canPreviousPage = pageIndex > 0;
    var canNextPage = pageCount === -1 ? page.length >= pageSize : pageIndex < pageCount - 1;
    var gotoPage = React.useCallback(function (pageIndex) {
      dispatch({
        type: actions.gotoPage,
        pageIndex: pageIndex
      });
    }, [dispatch]);
    var previousPage = React.useCallback(function () {
      return gotoPage(function (old) {
        return old - 1;
      });
    }, [gotoPage]);
    var nextPage = React.useCallback(function () {
      return gotoPage(function (old) {
        return old + 1;
      });
    }, [gotoPage]);
    var setPageSize = React.useCallback(function (pageSize) {
      dispatch({
        type: actions.setPageSize,
        pageSize: pageSize
      });
    }, [dispatch]);
    Object.assign(instance, {
      pageOptions: pageOptions,
      pageCount: pageCount,
      page: page,
      canPreviousPage: canPreviousPage,
      canNextPage: canNextPage,
      gotoPage: gotoPage,
      previousPage: previousPage,
      nextPage: nextPage,
      setPageSize: setPageSize
    });
  }

  actions.resetPivot = 'resetPivot';
  actions.togglePivot = 'togglePivot';
  var _UNSTABLE_usePivotColumns = function _UNSTABLE_usePivotColumns(hooks) {
    hooks.getPivotToggleProps = [defaultGetPivotToggleProps];
    hooks.stateReducers.push(reducer$7);
    hooks.useInstanceAfterData.push(useInstanceAfterData);
    hooks.allColumns.push(allColumns);
    hooks.accessValue.push(accessValue);
    hooks.materializedColumns.push(materializedColumns);
    hooks.materializedColumnsDeps.push(materializedColumnsDeps);
    hooks.visibleColumns.push(visibleColumns$1);
    hooks.visibleColumnsDeps.push(visibleColumnsDeps);
    hooks.useInstance.push(useInstance$7);
    hooks.prepareRow.push(prepareRow$2);
  };
  _UNSTABLE_usePivotColumns.pluginName = 'usePivotColumns';
  var defaultPivotColumns = [];

  var defaultGetPivotToggleProps = function defaultGetPivotToggleProps(props, _ref) {
    var header = _ref.header;
    return [props, {
      onClick: header.canPivot ? function (e) {
        e.persist();
        header.togglePivot();
      } : undefined,
      style: {
        cursor: header.canPivot ? 'pointer' : undefined
      },
      title: 'Toggle Pivot'
    }];
  }; // Reducer


  function reducer$7(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        pivotColumns: defaultPivotColumns
      }, state);
    }

    if (action.type === actions.resetPivot) {
      return _extends({}, state, {
        pivotColumns: instance.initialState.pivotColumns || defaultPivotColumns
      });
    }

    if (action.type === actions.togglePivot) {
      var columnId = action.columnId,
          setPivot = action.value;
      var resolvedPivot = typeof setPivot !== 'undefined' ? setPivot : !state.pivotColumns.includes(columnId);

      if (resolvedPivot) {
        return _extends({}, state, {
          pivotColumns: [].concat(state.pivotColumns, [columnId])
        });
      }

      return _extends({}, state, {
        pivotColumns: state.pivotColumns.filter(function (d) {
          return d !== columnId;
        })
      });
    }
  }

  function useInstanceAfterData(instance) {
    instance.allColumns.forEach(function (column) {
      column.isPivotSource = instance.state.pivotColumns.includes(column.id);
    });
  }

  function allColumns(columns, _ref2) {
    var instance = _ref2.instance;
    columns.forEach(function (column) {
      column.isPivotSource = instance.state.pivotColumns.includes(column.id);
      column.uniqueValues = new Set();
    });
    return columns;
  }

  function accessValue(value, _ref3) {
    var column = _ref3.column;

    if (column.uniqueValues && typeof value !== 'undefined') {
      column.uniqueValues.add(value);
    }

    return value;
  }

  function materializedColumns(materialized, _ref4) {
    var instance = _ref4.instance;
    var allColumns = instance.allColumns,
        state = instance.state;

    if (!state.pivotColumns.length || !state.groupBy || !state.groupBy.length) {
      return materialized;
    }

    var pivotColumns = state.pivotColumns.map(function (id) {
      return allColumns.find(function (d) {
        return d.id === id;
      });
    }).filter(Boolean);
    var sourceColumns = allColumns.filter(function (d) {
      return !d.isPivotSource && !state.groupBy.includes(d.id) && !state.pivotColumns.includes(d.id);
    });

    var buildPivotColumns = function buildPivotColumns(depth, parent, pivotFilters) {
      if (depth === void 0) {
        depth = 0;
      }

      if (pivotFilters === void 0) {
        pivotFilters = [];
      }

      var pivotColumn = pivotColumns[depth];

      if (!pivotColumn) {
        return sourceColumns.map(function (sourceColumn) {
          // TODO: We could offer support here for renesting pivoted
          // columns inside copies of their header groups. For now,
          // that seems like it would be (1) overkill on nesting, considering
          // you already get nesting for every pivot level and (2)
          // really hard. :)
          return _extends({}, sourceColumn, {
            canPivot: false,
            isPivoted: true,
            parent: parent,
            depth: depth,
            id: "" + (parent ? parent.id + "." + sourceColumn.id : sourceColumn.id),
            accessor: function accessor(originalRow, i, row) {
              if (pivotFilters.every(function (filter) {
                return filter(row);
              })) {
                return row.values[sourceColumn.id];
              }
            }
          });
        });
      }

      var uniqueValues = Array.from(pivotColumn.uniqueValues).sort();
      return uniqueValues.map(function (uniqueValue) {
        var columnGroup = _extends({}, pivotColumn, {
          Header: pivotColumn.PivotHeader || typeof pivotColumn.header === 'string' ? pivotColumn.Header + ": " + uniqueValue : uniqueValue,
          isPivotGroup: true,
          parent: parent,
          depth: depth,
          id: parent ? parent.id + "." + pivotColumn.id + "." + uniqueValue : pivotColumn.id + "." + uniqueValue,
          pivotValue: uniqueValue
        });

        columnGroup.columns = buildPivotColumns(depth + 1, columnGroup, [].concat(pivotFilters, [function (row) {
          return row.values[pivotColumn.id] === uniqueValue;
        }]));
        return columnGroup;
      });
    };

    var newMaterialized = flattenColumns(buildPivotColumns());
    return [].concat(materialized, newMaterialized);
  }

  function materializedColumnsDeps(deps, _ref5) {
    var _ref5$instance$state = _ref5.instance.state,
        pivotColumns = _ref5$instance$state.pivotColumns,
        groupBy = _ref5$instance$state.groupBy;
    return [].concat(deps, [pivotColumns, groupBy]);
  }

  function visibleColumns$1(visibleColumns, _ref6) {
    var state = _ref6.instance.state;
    visibleColumns = visibleColumns.filter(function (d) {
      return !d.isPivotSource;
    });

    if (state.pivotColumns.length && state.groupBy && state.groupBy.length) {
      visibleColumns = visibleColumns.filter(function (column) {
        return column.isGrouped || column.isPivoted;
      });
    }

    return visibleColumns;
  }

  function visibleColumnsDeps(deps, _ref7) {
    var instance = _ref7.instance;
    return [].concat(deps, [instance.state.pivotColumns, instance.state.groupBy]);
  }

  function useInstance$7(instance) {
    var columns = instance.columns,
        allColumns = instance.allColumns,
        flatHeaders = instance.flatHeaders,
        getHooks = instance.getHooks,
        plugins = instance.plugins,
        dispatch = instance.dispatch,
        _instance$autoResetPi = instance.autoResetPivot,
        autoResetPivot = _instance$autoResetPi === void 0 ? true : _instance$autoResetPi,
        manaulPivot = instance.manaulPivot,
        disablePivot = instance.disablePivot,
        defaultCanPivot = instance.defaultCanPivot;
    ensurePluginOrder(plugins, ['useGroupBy'], 'usePivotColumns');
    var getInstance = useGetLatest(instance);
    allColumns.forEach(function (column) {
      var accessor = column.accessor,
          defaultColumnPivot = column.defaultPivot,
          columnDisablePivot = column.disablePivot;
      column.canPivot = accessor ? getFirstDefined(column.canPivot, columnDisablePivot === true ? false : undefined, disablePivot === true ? false : undefined, true) : getFirstDefined(column.canPivot, defaultColumnPivot, defaultCanPivot, false);

      if (column.canPivot) {
        column.togglePivot = function () {
          return instance.togglePivot(column.id);
        };
      }

      column.Aggregated = column.Aggregated || column.Cell;
    });

    var togglePivot = function togglePivot(columnId, value) {
      dispatch({
        type: actions.togglePivot,
        columnId: columnId,
        value: value
      });
    };

    flatHeaders.forEach(function (header) {
      header.getPivotToggleProps = makePropGetter(getHooks().getPivotToggleProps, {
        instance: getInstance(),
        header: header
      });
    });
    var getAutoResetPivot = useGetLatest(autoResetPivot);
    useMountedLayoutEffect(function () {
      if (getAutoResetPivot()) {
        dispatch({
          type: actions.resetPivot
        });
      }
    }, [dispatch, manaulPivot ? null : columns]);
    Object.assign(instance, {
      togglePivot: togglePivot
    });
  }

  function prepareRow$2(row) {
    row.allCells.forEach(function (cell) {
      // Grouped cells are in the pivotColumns and the pivot cell for the row
      cell.isPivoted = cell.column.isPivoted;
    });
  }

  var pluginName$1 = 'useRowSelect'; // Actions

  actions.resetSelectedRows = 'resetSelectedRows';
  actions.toggleAllRowsSelected = 'toggleAllRowsSelected';
  actions.toggleRowSelected = 'toggleRowSelected';
  actions.toggleAllPageRowsSelected = 'toggleAllPageRowsSelected';
  var useRowSelect = function useRowSelect(hooks) {
    hooks.getToggleRowSelectedProps = [defaultGetToggleRowSelectedProps];
    hooks.getToggleAllRowsSelectedProps = [defaultGetToggleAllRowsSelectedProps];
    hooks.getToggleAllPageRowsSelectedProps = [defaultGetToggleAllPageRowsSelectedProps];
    hooks.stateReducers.push(reducer$8);
    hooks.useInstance.push(useInstance$8);
    hooks.prepareRow.push(prepareRow$3);
  };
  useRowSelect.pluginName = pluginName$1;

  var defaultGetToggleRowSelectedProps = function defaultGetToggleRowSelectedProps(props, _ref) {
    var instance = _ref.instance,
        row = _ref.row;
    var _instance$manualRowSe = instance.manualRowSelectedKey,
        manualRowSelectedKey = _instance$manualRowSe === void 0 ? 'isSelected' : _instance$manualRowSe;
    var checked = false;

    if (row.original && row.original[manualRowSelectedKey]) {
      checked = true;
    } else {
      checked = row.isSelected;
    }

    return [props, {
      onChange: function onChange(e) {
        row.toggleRowSelected(e.target.checked);
      },
      style: {
        cursor: 'pointer'
      },
      checked: checked,
      title: 'Toggle Row Selected',
      indeterminate: row.isSomeSelected
    }];
  };

  var defaultGetToggleAllRowsSelectedProps = function defaultGetToggleAllRowsSelectedProps(props, _ref2) {
    var instance = _ref2.instance;
    return [props, {
      onChange: function onChange(e) {
        instance.toggleAllRowsSelected(e.target.checked);
      },
      style: {
        cursor: 'pointer'
      },
      checked: instance.isAllRowsSelected,
      title: 'Toggle All Rows Selected',
      indeterminate: Boolean(!instance.isAllRowsSelected && Object.keys(instance.state.selectedRowIds).length)
    }];
  };

  var defaultGetToggleAllPageRowsSelectedProps = function defaultGetToggleAllPageRowsSelectedProps(props, _ref3) {
    var instance = _ref3.instance;
    return [props, {
      onChange: function onChange(e) {
        instance.toggleAllPageRowsSelected(e.target.checked);
      },
      style: {
        cursor: 'pointer'
      },
      checked: instance.isAllPageRowsSelected,
      title: 'Toggle All Current Page Rows Selected',
      indeterminate: Boolean(!instance.isAllPageRowsSelected && instance.page.some(function (_ref4) {
        var id = _ref4.id;
        return instance.state.selectedRowIds[id];
      }))
    }];
  }; // eslint-disable-next-line max-params


  function reducer$8(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        selectedRowIds: {}
      }, state);
    }

    if (action.type === actions.resetSelectedRows) {
      return _extends({}, state, {
        selectedRowIds: instance.initialState.selectedRowIds || {}
      });
    }

    if (action.type === actions.toggleAllRowsSelected) {
      var setSelected = action.value;
      var isAllRowsSelected = instance.isAllRowsSelected,
          rowsById = instance.rowsById,
          _instance$nonGroupedR = instance.nonGroupedRowsById,
          nonGroupedRowsById = _instance$nonGroupedR === void 0 ? rowsById : _instance$nonGroupedR;
      var selectAll = typeof setSelected !== 'undefined' ? setSelected : !isAllRowsSelected; // Only remove/add the rows that are visible on the screen
      //  Leave all the other rows that are selected alone.

      var selectedRowIds = Object.assign({}, state.selectedRowIds);

      if (selectAll) {
        Object.keys(nonGroupedRowsById).forEach(function (rowId) {
          selectedRowIds[rowId] = true;
        });
      } else {
        Object.keys(nonGroupedRowsById).forEach(function (rowId) {
          delete selectedRowIds[rowId];
        });
      }

      return _extends({}, state, {
        selectedRowIds: selectedRowIds
      });
    }

    if (action.type === actions.toggleRowSelected) {
      var id = action.id,
          _setSelected = action.value;
      var _rowsById = instance.rowsById,
          _instance$selectSubRo = instance.selectSubRows,
          selectSubRows = _instance$selectSubRo === void 0 ? true : _instance$selectSubRo,
          getSubRows = instance.getSubRows;
      var isSelected = state.selectedRowIds[id];
      var shouldExist = typeof _setSelected !== 'undefined' ? _setSelected : !isSelected;

      if (isSelected === shouldExist) {
        return state;
      }

      var newSelectedRowIds = _extends({}, state.selectedRowIds);

      var handleRowById = function handleRowById(id) {
        var row = _rowsById[id];

        if (row) {
          if (!row.isGrouped) {
            if (shouldExist) {
              newSelectedRowIds[id] = true;
            } else {
              delete newSelectedRowIds[id];
            }
          }

          if (selectSubRows && getSubRows(row)) {
            return getSubRows(row).forEach(function (row) {
              return handleRowById(row.id);
            });
          }
        }
      };

      handleRowById(id);
      return _extends({}, state, {
        selectedRowIds: newSelectedRowIds
      });
    }

    if (action.type === actions.toggleAllPageRowsSelected) {
      var _setSelected2 = action.value;

      var page = instance.page,
          _rowsById2 = instance.rowsById,
          _instance$selectSubRo2 = instance.selectSubRows,
          _selectSubRows = _instance$selectSubRo2 === void 0 ? true : _instance$selectSubRo2,
          isAllPageRowsSelected = instance.isAllPageRowsSelected,
          _getSubRows = instance.getSubRows;

      var _selectAll = typeof _setSelected2 !== 'undefined' ? _setSelected2 : !isAllPageRowsSelected;

      var _newSelectedRowIds = _extends({}, state.selectedRowIds);

      var _handleRowById = function _handleRowById(id) {
        var row = _rowsById2[id];

        if (!row.isGrouped) {
          if (_selectAll) {
            _newSelectedRowIds[id] = true;
          } else {
            delete _newSelectedRowIds[id];
          }
        }

        if (_selectSubRows && _getSubRows(row)) {
          return _getSubRows(row).forEach(function (row) {
            return _handleRowById(row.id);
          });
        }
      };

      page.forEach(function (row) {
        return _handleRowById(row.id);
      });
      return _extends({}, state, {
        selectedRowIds: _newSelectedRowIds
      });
    }

    return state;
  }

  function useInstance$8(instance) {
    var data = instance.data,
        rows = instance.rows,
        getHooks = instance.getHooks,
        plugins = instance.plugins,
        rowsById = instance.rowsById,
        _instance$nonGroupedR2 = instance.nonGroupedRowsById,
        nonGroupedRowsById = _instance$nonGroupedR2 === void 0 ? rowsById : _instance$nonGroupedR2,
        _instance$autoResetSe = instance.autoResetSelectedRows,
        autoResetSelectedRows = _instance$autoResetSe === void 0 ? true : _instance$autoResetSe,
        selectedRowIds = instance.state.selectedRowIds,
        _instance$selectSubRo3 = instance.selectSubRows,
        selectSubRows = _instance$selectSubRo3 === void 0 ? true : _instance$selectSubRo3,
        dispatch = instance.dispatch,
        page = instance.page,
        getSubRows = instance.getSubRows;
    ensurePluginOrder(plugins, ['useFilters', 'useGroupBy', 'useSortBy', 'useExpanded', 'usePagination'], 'useRowSelect');
    var selectedFlatRows = React.useMemo(function () {
      var selectedFlatRows = [];
      rows.forEach(function (row) {
        var isSelected = selectSubRows ? getRowIsSelected(row, selectedRowIds, getSubRows) : !!selectedRowIds[row.id];
        row.isSelected = !!isSelected;
        row.isSomeSelected = isSelected === null;

        if (isSelected) {
          selectedFlatRows.push(row);
        }
      });
      return selectedFlatRows;
    }, [rows, selectSubRows, selectedRowIds, getSubRows]);
    var isAllRowsSelected = Boolean(Object.keys(nonGroupedRowsById).length && Object.keys(selectedRowIds).length);
    var isAllPageRowsSelected = isAllRowsSelected;

    if (isAllRowsSelected) {
      if (Object.keys(nonGroupedRowsById).some(function (id) {
        return !selectedRowIds[id];
      })) {
        isAllRowsSelected = false;
      }
    }

    if (!isAllRowsSelected) {
      if (page && page.length && page.some(function (_ref5) {
        var id = _ref5.id;
        return !selectedRowIds[id];
      })) {
        isAllPageRowsSelected = false;
      }
    }

    var getAutoResetSelectedRows = useGetLatest(autoResetSelectedRows);
    useMountedLayoutEffect(function () {
      if (getAutoResetSelectedRows()) {
        dispatch({
          type: actions.resetSelectedRows
        });
      }
    }, [dispatch, data]);
    var toggleAllRowsSelected = React.useCallback(function (value) {
      return dispatch({
        type: actions.toggleAllRowsSelected,
        value: value
      });
    }, [dispatch]);
    var toggleAllPageRowsSelected = React.useCallback(function (value) {
      return dispatch({
        type: actions.toggleAllPageRowsSelected,
        value: value
      });
    }, [dispatch]);
    var toggleRowSelected = React.useCallback(function (id, value) {
      return dispatch({
        type: actions.toggleRowSelected,
        id: id,
        value: value
      });
    }, [dispatch]);
    var getInstance = useGetLatest(instance);
    var getToggleAllRowsSelectedProps = makePropGetter(getHooks().getToggleAllRowsSelectedProps, {
      instance: getInstance()
    });
    var getToggleAllPageRowsSelectedProps = makePropGetter(getHooks().getToggleAllPageRowsSelectedProps, {
      instance: getInstance()
    });
    Object.assign(instance, {
      selectedFlatRows: selectedFlatRows,
      isAllRowsSelected: isAllRowsSelected,
      isAllPageRowsSelected: isAllPageRowsSelected,
      toggleRowSelected: toggleRowSelected,
      toggleAllRowsSelected: toggleAllRowsSelected,
      getToggleAllRowsSelectedProps: getToggleAllRowsSelectedProps,
      getToggleAllPageRowsSelectedProps: getToggleAllPageRowsSelectedProps,
      toggleAllPageRowsSelected: toggleAllPageRowsSelected
    });
  }

  function prepareRow$3(row, _ref6) {
    var instance = _ref6.instance;

    row.toggleRowSelected = function (set) {
      return instance.toggleRowSelected(row.id, set);
    };

    row.getToggleRowSelectedProps = makePropGetter(instance.getHooks().getToggleRowSelectedProps, {
      instance: instance,
      row: row
    });
  }

  function getRowIsSelected(row, selectedRowIds, getSubRows) {
    if (selectedRowIds[row.id]) {
      return true;
    }

    var subRows = getSubRows(row);

    if (subRows && subRows.length) {
      var allChildrenSelected = true;
      var someSelected = false;
      subRows.forEach(function (subRow) {
        // Bail out early if we know both of these
        if (someSelected && !allChildrenSelected) {
          return;
        }

        if (getRowIsSelected(subRow, selectedRowIds, getSubRows)) {
          someSelected = true;
        } else {
          allChildrenSelected = false;
        }
      });
      return allChildrenSelected ? true : someSelected ? null : false;
    }

    return false;
  }

  var defaultInitialRowStateAccessor = function defaultInitialRowStateAccessor(row) {
    return {};
  };

  var defaultInitialCellStateAccessor = function defaultInitialCellStateAccessor(cell) {
    return {};
  }; // Actions


  actions.setRowState = 'setRowState';
  actions.setCellState = 'setCellState';
  actions.resetRowState = 'resetRowState';
  var useRowState = function useRowState(hooks) {
    hooks.stateReducers.push(reducer$9);
    hooks.useInstance.push(useInstance$9);
    hooks.prepareRow.push(prepareRow$4);
  };
  useRowState.pluginName = 'useRowState';

  function reducer$9(state, action, previousState, instance) {
    var _instance$initialRowS = instance.initialRowStateAccessor,
        initialRowStateAccessor = _instance$initialRowS === void 0 ? defaultInitialRowStateAccessor : _instance$initialRowS,
        _instance$initialCell = instance.initialCellStateAccessor,
        initialCellStateAccessor = _instance$initialCell === void 0 ? defaultInitialCellStateAccessor : _instance$initialCell,
        rowsById = instance.rowsById;

    if (action.type === actions.init) {
      return _extends({
        rowState: {}
      }, state);
    }

    if (action.type === actions.resetRowState) {
      return _extends({}, state, {
        rowState: instance.initialState.rowState || {}
      });
    }

    if (action.type === actions.setRowState) {
      var _extends2;

      var rowId = action.rowId,
          value = action.value;
      var oldRowState = typeof state.rowState[rowId] !== 'undefined' ? state.rowState[rowId] : initialRowStateAccessor(rowsById[rowId]);
      return _extends({}, state, {
        rowState: _extends({}, state.rowState, (_extends2 = {}, _extends2[rowId] = functionalUpdate(value, oldRowState), _extends2))
      });
    }

    if (action.type === actions.setCellState) {
      var _oldRowState$cellStat, _rowsById$_rowId, _rowsById$_rowId$cell, _extends3, _extends4;

      var _rowId = action.rowId,
          columnId = action.columnId,
          _value = action.value;

      var _oldRowState = typeof state.rowState[_rowId] !== 'undefined' ? state.rowState[_rowId] : initialRowStateAccessor(rowsById[_rowId]);

      var oldCellState = typeof (_oldRowState == null ? void 0 : (_oldRowState$cellStat = _oldRowState.cellState) == null ? void 0 : _oldRowState$cellStat[columnId]) !== 'undefined' ? _oldRowState.cellState[columnId] : initialCellStateAccessor((_rowsById$_rowId = rowsById[_rowId]) == null ? void 0 : (_rowsById$_rowId$cell = _rowsById$_rowId.cells) == null ? void 0 : _rowsById$_rowId$cell.find(function (cell) {
        return cell.column.id === columnId;
      }));
      return _extends({}, state, {
        rowState: _extends({}, state.rowState, (_extends4 = {}, _extends4[_rowId] = _extends({}, _oldRowState, {
          cellState: _extends({}, _oldRowState.cellState || {}, (_extends3 = {}, _extends3[columnId] = functionalUpdate(_value, oldCellState), _extends3))
        }), _extends4))
      });
    }
  }

  function useInstance$9(instance) {
    var _instance$autoResetRo = instance.autoResetRowState,
        autoResetRowState = _instance$autoResetRo === void 0 ? true : _instance$autoResetRo,
        data = instance.data,
        dispatch = instance.dispatch;
    var setRowState = React.useCallback(function (rowId, value) {
      return dispatch({
        type: actions.setRowState,
        rowId: rowId,
        value: value
      });
    }, [dispatch]);
    var setCellState = React.useCallback(function (rowId, columnId, value) {
      return dispatch({
        type: actions.setCellState,
        rowId: rowId,
        columnId: columnId,
        value: value
      });
    }, [dispatch]);
    var getAutoResetRowState = useGetLatest(autoResetRowState);
    useMountedLayoutEffect(function () {
      if (getAutoResetRowState()) {
        dispatch({
          type: actions.resetRowState
        });
      }
    }, [data]);
    Object.assign(instance, {
      setRowState: setRowState,
      setCellState: setCellState
    });
  }

  function prepareRow$4(row, _ref) {
    var instance = _ref.instance;
    var _instance$initialRowS2 = instance.initialRowStateAccessor,
        initialRowStateAccessor = _instance$initialRowS2 === void 0 ? defaultInitialRowStateAccessor : _instance$initialRowS2,
        _instance$initialCell2 = instance.initialCellStateAccessor,
        initialCellStateAccessor = _instance$initialCell2 === void 0 ? defaultInitialCellStateAccessor : _instance$initialCell2,
        rowState = instance.state.rowState;

    if (row) {
      row.state = typeof rowState[row.id] !== 'undefined' ? rowState[row.id] : initialRowStateAccessor(row);

      row.setState = function (updater) {
        return instance.setRowState(row.id, updater);
      };

      row.cells.forEach(function (cell) {
        if (!row.state.cellState) {
          row.state.cellState = {};
        }

        cell.state = typeof row.state.cellState[cell.column.id] !== 'undefined' ? row.state.cellState[cell.column.id] : initialCellStateAccessor(cell);

        cell.setState = function (updater) {
          return instance.setCellState(row.id, cell.column.id, updater);
        };
      });
    }
  }

  actions.resetColumnOrder = 'resetColumnOrder';
  actions.setColumnOrder = 'setColumnOrder';
  var useColumnOrder = function useColumnOrder(hooks) {
    hooks.stateReducers.push(reducer$a);
    hooks.visibleColumnsDeps.push(function (deps, _ref) {
      var instance = _ref.instance;
      return [].concat(deps, [instance.state.columnOrder]);
    });
    hooks.visibleColumns.push(visibleColumns$2);
    hooks.useInstance.push(useInstance$a);
  };
  useColumnOrder.pluginName = 'useColumnOrder';

  function reducer$a(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        columnOrder: []
      }, state);
    }

    if (action.type === actions.resetColumnOrder) {
      return _extends({}, state, {
        columnOrder: instance.initialState.columnOrder || []
      });
    }

    if (action.type === actions.setColumnOrder) {
      return _extends({}, state, {
        columnOrder: functionalUpdate(action.columnOrder, state.columnOrder)
      });
    }
  }

  function visibleColumns$2(columns, _ref2) {
    var columnOrder = _ref2.instance.state.columnOrder;

    // If there is no order, return the normal columns
    if (!columnOrder || !columnOrder.length) {
      return columns;
    }

    var columnOrderCopy = [].concat(columnOrder); // If there is an order, make a copy of the columns

    var columnsCopy = [].concat(columns); // And make a new ordered array of the columns

    var columnsInOrder = []; // Loop over the columns and place them in order into the new array

    var _loop = function _loop() {
      var targetColumnId = columnOrderCopy.shift();
      var foundIndex = columnsCopy.findIndex(function (d) {
        return d.id === targetColumnId;
      });

      if (foundIndex > -1) {
        columnsInOrder.push(columnsCopy.splice(foundIndex, 1)[0]);
      }
    };

    while (columnsCopy.length && columnOrderCopy.length) {
      _loop();
    } // If there are any columns left, add them to the end


    return [].concat(columnsInOrder, columnsCopy);
  }

  function useInstance$a(instance) {
    var dispatch = instance.dispatch;
    instance.setColumnOrder = React.useCallback(function (columnOrder) {
      return dispatch({
        type: actions.setColumnOrder,
        columnOrder: columnOrder
      });
    }, [dispatch]);
  }

  defaultColumn.canResize = true; // Actions

  actions.columnStartResizing = 'columnStartResizing';
  actions.columnResizing = 'columnResizing';
  actions.columnDoneResizing = 'columnDoneResizing';
  actions.resetResize = 'resetResize';
  var useResizeColumns = function useResizeColumns(hooks) {
    hooks.getResizerProps = [defaultGetResizerProps];
    hooks.getHeaderProps.push({
      style: {
        position: 'relative'
      }
    });
    hooks.stateReducers.push(reducer$b);
    hooks.useInstance.push(useInstance$b);
    hooks.useInstanceBeforeDimensions.push(useInstanceBeforeDimensions$1);
  };

  var defaultGetResizerProps = function defaultGetResizerProps(props, _ref) {
    var instance = _ref.instance,
        header = _ref.header;
    var dispatch = instance.dispatch;

    var onResizeStart = function onResizeStart(e, header) {
      var isTouchEvent = false;

      if (e.type === 'touchstart') {
        // lets not respond to multiple touches (e.g. 2 or 3 fingers)
        if (e.touches && e.touches.length > 1) {
          return;
        }

        isTouchEvent = true;
      }

      var headersToResize = getLeafHeaders(header);
      var headerIdWidths = headersToResize.map(function (d) {
        return [d.id, d.totalWidth];
      });
      var clientX = isTouchEvent ? Math.round(e.touches[0].clientX) : e.clientX;
      var raf;
      var mostRecentClientX;

      var dispatchEnd = function dispatchEnd() {
        window.cancelAnimationFrame(raf);
        raf = null;
        dispatch({
          type: actions.columnDoneResizing
        });
      };

      var dispatchMove = function dispatchMove() {
        window.cancelAnimationFrame(raf);
        raf = null;
        dispatch({
          type: actions.columnResizing,
          clientX: mostRecentClientX
        });
      };

      var scheduleDispatchMoveOnNextAnimationFrame = function scheduleDispatchMoveOnNextAnimationFrame(clientXPos) {
        mostRecentClientX = clientXPos;

        if (!raf) {
          raf = window.requestAnimationFrame(dispatchMove);
        }
      };

      var handlersAndEvents = {
        mouse: {
          moveEvent: 'mousemove',
          moveHandler: function moveHandler(e) {
            return scheduleDispatchMoveOnNextAnimationFrame(e.clientX);
          },
          upEvent: 'mouseup',
          upHandler: function upHandler(e) {
            document.removeEventListener('mousemove', handlersAndEvents.mouse.moveHandler);
            document.removeEventListener('mouseup', handlersAndEvents.mouse.upHandler);
            dispatchEnd();
          }
        },
        touch: {
          moveEvent: 'touchmove',
          moveHandler: function moveHandler(e) {
            if (e.cancelable) {
              e.preventDefault();
              e.stopPropagation();
            }

            scheduleDispatchMoveOnNextAnimationFrame(e.touches[0].clientX);
            return false;
          },
          upEvent: 'touchend',
          upHandler: function upHandler(e) {
            document.removeEventListener(handlersAndEvents.touch.moveEvent, handlersAndEvents.touch.moveHandler);
            document.removeEventListener(handlersAndEvents.touch.upEvent, handlersAndEvents.touch.moveHandler);
            dispatchEnd();
          }
        }
      };
      var events = isTouchEvent ? handlersAndEvents.touch : handlersAndEvents.mouse;
      var passiveIfSupported = passiveEventSupported() ? {
        passive: false
      } : false;
      document.addEventListener(events.moveEvent, events.moveHandler, passiveIfSupported);
      document.addEventListener(events.upEvent, events.upHandler, passiveIfSupported);
      dispatch({
        type: actions.columnStartResizing,
        columnId: header.id,
        columnWidth: header.totalWidth,
        headerIdWidths: headerIdWidths,
        clientX: clientX
      });
    };

    return [props, {
      onMouseDown: function onMouseDown(e) {
        return e.persist() || onResizeStart(e, header);
      },
      onTouchStart: function onTouchStart(e) {
        return e.persist() || onResizeStart(e, header);
      },
      style: {
        cursor: 'col-resize'
      },
      draggable: false,
      role: 'separator'
    }];
  };

  useResizeColumns.pluginName = 'useResizeColumns';

  function reducer$b(state, action) {
    if (action.type === actions.init) {
      return _extends({
        columnResizing: {
          columnWidths: {}
        }
      }, state);
    }

    if (action.type === actions.resetResize) {
      return _extends({}, state, {
        columnResizing: {
          columnWidths: {}
        }
      });
    }

    if (action.type === actions.columnStartResizing) {
      var clientX = action.clientX,
          columnId = action.columnId,
          columnWidth = action.columnWidth,
          headerIdWidths = action.headerIdWidths;
      return _extends({}, state, {
        columnResizing: _extends({}, state.columnResizing, {
          startX: clientX,
          headerIdWidths: headerIdWidths,
          columnWidth: columnWidth,
          isResizingColumn: columnId
        })
      });
    }

    if (action.type === actions.columnResizing) {
      var _clientX = action.clientX;

      var _state$columnResizing = state.columnResizing,
          startX = _state$columnResizing.startX,
          _columnWidth = _state$columnResizing.columnWidth,
          _state$columnResizing2 = _state$columnResizing.headerIdWidths,
          _headerIdWidths = _state$columnResizing2 === void 0 ? [] : _state$columnResizing2;

      var deltaX = _clientX - startX;
      var percentageDeltaX = deltaX / _columnWidth;
      var newColumnWidths = {};

      _headerIdWidths.forEach(function (_ref2) {
        var headerId = _ref2[0],
            headerWidth = _ref2[1];
        newColumnWidths[headerId] = Math.max(headerWidth + headerWidth * percentageDeltaX, 0);
      });

      return _extends({}, state, {
        columnResizing: _extends({}, state.columnResizing, {
          columnWidths: _extends({}, state.columnResizing.columnWidths, {}, newColumnWidths)
        })
      });
    }

    if (action.type === actions.columnDoneResizing) {
      return _extends({}, state, {
        columnResizing: _extends({}, state.columnResizing, {
          startX: null,
          isResizingColumn: null
        })
      });
    }
  }

  var useInstanceBeforeDimensions$1 = function useInstanceBeforeDimensions(instance) {
    var flatHeaders = instance.flatHeaders,
        disableResizing = instance.disableResizing,
        getHooks = instance.getHooks,
        columnResizing = instance.state.columnResizing;
    var getInstance = useGetLatest(instance);
    flatHeaders.forEach(function (header) {
      var canResize = getFirstDefined(header.disableResizing === true ? false : undefined, disableResizing === true ? false : undefined, true);
      header.canResize = canResize;
      header.width = columnResizing.columnWidths[header.id] || header.originalWidth || header.width;
      header.isResizing = columnResizing.isResizingColumn === header.id;

      if (canResize) {
        header.getResizerProps = makePropGetter(getHooks().getResizerProps, {
          instance: getInstance(),
          header: header
        });
      }
    });
  };

  function useInstance$b(instance) {
    var plugins = instance.plugins,
        dispatch = instance.dispatch,
        _instance$autoResetRe = instance.autoResetResize,
        autoResetResize = _instance$autoResetRe === void 0 ? true : _instance$autoResetRe,
        columns = instance.columns;
    ensurePluginOrder(plugins, ['useAbsoluteLayout'], 'useResizeColumns');
    var getAutoResetResize = useGetLatest(autoResetResize);
    useMountedLayoutEffect(function () {
      if (getAutoResetResize()) {
        dispatch({
          type: actions.resetResize
        });
      }
    }, [columns]);
    var resetResizing = React.useCallback(function () {
      return dispatch({
        type: actions.resetResize
      });
    }, [dispatch]);
    Object.assign(instance, {
      resetResizing: resetResizing
    });
  }

  function getLeafHeaders(header) {
    var leafHeaders = [];

    var recurseHeader = function recurseHeader(header) {
      if (header.columns && header.columns.length) {
        header.columns.map(recurseHeader);
      }

      leafHeaders.push(header);
    };

    recurseHeader(header);
    return leafHeaders;
  }

  var cellStyles = {
    position: 'absolute',
    top: 0
  };
  var useAbsoluteLayout = function useAbsoluteLayout(hooks) {
    hooks.getTableBodyProps.push(getRowStyles);
    hooks.getRowProps.push(getRowStyles);
    hooks.getHeaderGroupProps.push(getRowStyles);
    hooks.getFooterGroupProps.push(getRowStyles);
    hooks.getHeaderProps.push(function (props, _ref) {
      var column = _ref.column;
      return [props, {
        style: _extends({}, cellStyles, {
          left: column.totalLeft + "px",
          width: column.totalWidth + "px"
        })
      }];
    });
    hooks.getCellProps.push(function (props, _ref2) {
      var cell = _ref2.cell;
      return [props, {
        style: _extends({}, cellStyles, {
          left: cell.column.totalLeft + "px",
          width: cell.column.totalWidth + "px"
        })
      }];
    });
    hooks.getFooterProps.push(function (props, _ref3) {
      var column = _ref3.column;
      return [props, {
        style: _extends({}, cellStyles, {
          left: column.totalLeft + "px",
          width: column.totalWidth + "px"
        })
      }];
    });
  };
  useAbsoluteLayout.pluginName = 'useAbsoluteLayout';

  var getRowStyles = function getRowStyles(props, _ref4) {
    var instance = _ref4.instance;
    return [props, {
      style: {
        position: 'relative',
        width: instance.totalColumnsWidth + "px"
      }
    }];
  };

  var cellStyles$1 = {
    display: 'inline-block',
    boxSizing: 'border-box'
  };

  var getRowStyles$1 = function getRowStyles(props, _ref) {
    var instance = _ref.instance;
    return [props, {
      style: {
        display: 'flex',
        width: instance.totalColumnsWidth + "px"
      }
    }];
  };

  var useBlockLayout = function useBlockLayout(hooks) {
    hooks.getRowProps.push(getRowStyles$1);
    hooks.getHeaderGroupProps.push(getRowStyles$1);
    hooks.getFooterGroupProps.push(getRowStyles$1);
    hooks.getHeaderProps.push(function (props, _ref2) {
      var column = _ref2.column;
      return [props, {
        style: _extends({}, cellStyles$1, {
          width: column.totalWidth + "px"
        })
      }];
    });
    hooks.getCellProps.push(function (props, _ref3) {
      var cell = _ref3.cell;
      return [props, {
        style: _extends({}, cellStyles$1, {
          width: cell.column.totalWidth + "px"
        })
      }];
    });
    hooks.getFooterProps.push(function (props, _ref4) {
      var column = _ref4.column;
      return [props, {
        style: _extends({}, cellStyles$1, {
          width: column.totalWidth + "px"
        })
      }];
    });
  };
  useBlockLayout.pluginName = 'useBlockLayout';

  function useFlexLayout(hooks) {
    hooks.getTableProps.push(getTableProps);
    hooks.getRowProps.push(getRowStyles$2);
    hooks.getHeaderGroupProps.push(getRowStyles$2);
    hooks.getFooterGroupProps.push(getRowStyles$2);
    hooks.getHeaderProps.push(getHeaderProps);
    hooks.getCellProps.push(getCellProps);
    hooks.getFooterProps.push(getFooterProps);
  }
  useFlexLayout.pluginName = 'useFlexLayout';

  var getTableProps = function getTableProps(props, _ref) {
    var instance = _ref.instance;
    return [props, {
      style: {
        minWidth: instance.totalColumnsMinWidth + "px"
      }
    }];
  };

  var getRowStyles$2 = function getRowStyles(props, _ref2) {
    var instance = _ref2.instance;
    return [props, {
      style: {
        display: 'flex',
        flex: '1 0 auto',
        minWidth: instance.totalColumnsMinWidth + "px"
      }
    }];
  };

  var getHeaderProps = function getHeaderProps(props, _ref3) {
    var column = _ref3.column;
    return [props, {
      style: {
        boxSizing: 'border-box',
        flex: column.totalFlexWidth ? column.totalFlexWidth + " 0 auto" : undefined,
        minWidth: column.totalMinWidth + "px",
        width: column.totalWidth + "px"
      }
    }];
  };

  var getCellProps = function getCellProps(props, _ref4) {
    var cell = _ref4.cell;
    return [props, {
      style: {
        boxSizing: 'border-box',
        flex: cell.column.totalFlexWidth + " 0 auto",
        minWidth: cell.column.totalMinWidth + "px",
        width: cell.column.totalWidth + "px"
      }
    }];
  };

  var getFooterProps = function getFooterProps(props, _ref5) {
    var column = _ref5.column;
    return [props, {
      style: {
        boxSizing: 'border-box',
        flex: column.totalFlexWidth ? column.totalFlexWidth + " 0 auto" : undefined,
        minWidth: column.totalMinWidth + "px",
        width: column.totalWidth + "px"
      }
    }];
  };

  actions.columnStartResizing = 'columnStartResizing';
  actions.columnResizing = 'columnResizing';
  actions.columnDoneResizing = 'columnDoneResizing';
  actions.resetResize = 'resetResize';
  function useGridLayout(hooks) {
    hooks.stateReducers.push(reducer$c);
    hooks.getTableProps.push(getTableProps$1);
    hooks.getHeaderProps.push(getHeaderProps$1);
    hooks.getRowProps.push(getRowProps);
  }
  useGridLayout.pluginName = 'useGridLayout';

  var getTableProps$1 = function getTableProps(props, _ref) {
    var instance = _ref.instance;
    var gridTemplateColumns = instance.visibleColumns.map(function (column) {
      var _instance$state$colum;

      if (instance.state.gridLayout.columnWidths[column.id]) return instance.state.gridLayout.columnWidths[column.id] + "px"; // When resizing, lock the width of all unset columns
      // instead of using user-provided width or defaultColumn width,
      // which could potentially be 'auto' or 'fr' units that don't scale linearly

      if ((_instance$state$colum = instance.state.columnResizing) == null ? void 0 : _instance$state$colum.isResizingColumn) return instance.state.gridLayout.startWidths[column.id] + "px";
      if (typeof column.width === 'number') return column.width + "px";
      return column.width;
    });
    return [props, {
      style: {
        display: "grid",
        gridTemplateColumns: gridTemplateColumns.join(" ")
      }
    }];
  };

  var getHeaderProps$1 = function getHeaderProps(props, _ref2) {
    var column = _ref2.column;
    return [props, {
      id: "header-cell-" + column.id,
      style: {
        position: "sticky",
        //enables a scroll wrapper to be placed around the table and have sticky headers
        gridColumn: "span " + column.totalVisibleHeaderCount
      }
    }];
  };

  var getRowProps = function getRowProps(props, _ref3) {
    var row = _ref3.row;

    if (row.isExpanded) {
      return [props, {
        style: {
          gridColumn: "1 / " + (row.cells.length + 1)
        }
      }];
    }

    return [props, {}];
  };

  function reducer$c(state, action, previousState, instance) {
    if (action.type === actions.init) {
      return _extends({
        gridLayout: {
          columnWidths: {}
        }
      }, state);
    }

    if (action.type === actions.resetResize) {
      return _extends({}, state, {
        gridLayout: {
          columnWidths: {}
        }
      });
    }

    if (action.type === actions.columnStartResizing) {
      var columnId = action.columnId,
          headerIdWidths = action.headerIdWidths;
      var columnWidth = getElementWidth(columnId);

      if (columnWidth !== undefined) {
        var startWidths = instance.visibleColumns.reduce(function (acc, column) {
          var _extends2;

          return _extends({}, acc, (_extends2 = {}, _extends2[column.id] = getElementWidth(column.id), _extends2));
        }, {});
        var minWidths = instance.visibleColumns.reduce(function (acc, column) {
          var _extends3;

          return _extends({}, acc, (_extends3 = {}, _extends3[column.id] = column.minWidth, _extends3));
        }, {});
        var maxWidths = instance.visibleColumns.reduce(function (acc, column) {
          var _extends4;

          return _extends({}, acc, (_extends4 = {}, _extends4[column.id] = column.maxWidth, _extends4));
        }, {});
        var headerIdGridWidths = headerIdWidths.map(function (_ref4) {
          var headerId = _ref4[0];
          return [headerId, getElementWidth(headerId)];
        });
        return _extends({}, state, {
          gridLayout: _extends({}, state.gridLayout, {
            startWidths: startWidths,
            minWidths: minWidths,
            maxWidths: maxWidths,
            headerIdGridWidths: headerIdGridWidths,
            columnWidth: columnWidth
          })
        });
      } else {
        return state;
      }
    }

    if (action.type === actions.columnResizing) {
      var clientX = action.clientX;
      var startX = state.columnResizing.startX;

      var _state$gridLayout = state.gridLayout,
          _columnWidth = _state$gridLayout.columnWidth,
          _minWidths = _state$gridLayout.minWidths,
          _maxWidths = _state$gridLayout.maxWidths,
          _state$gridLayout$hea = _state$gridLayout.headerIdGridWidths,
          _headerIdGridWidths = _state$gridLayout$hea === void 0 ? [] : _state$gridLayout$hea;

      var deltaX = clientX - startX;
      var percentageDeltaX = deltaX / _columnWidth;
      var newColumnWidths = {};

      _headerIdGridWidths.forEach(function (_ref5) {
        var headerId = _ref5[0],
            headerWidth = _ref5[1];
        newColumnWidths[headerId] = Math.min(Math.max(_minWidths[headerId], headerWidth + headerWidth * percentageDeltaX), _maxWidths[headerId]);
      });

      return _extends({}, state, {
        gridLayout: _extends({}, state.gridLayout, {
          columnWidths: _extends({}, state.gridLayout.columnWidths, {}, newColumnWidths)
        })
      });
    }

    if (action.type === actions.columnDoneResizing) {
      return _extends({}, state, {
        gridLayout: _extends({}, state.gridLayout, {
          startWidths: {},
          minWidths: {},
          maxWidths: {}
        })
      });
    }
  }

  function getElementWidth(columnId) {
    var _document$getElementB;

    var width = (_document$getElementB = document.getElementById("header-cell-" + columnId)) == null ? void 0 : _document$getElementB.offsetWidth;

    if (width !== undefined) {
      return width;
    }
  }

  exports._UNSTABLE_usePivotColumns = _UNSTABLE_usePivotColumns;
  exports.actions = actions;
  exports.defaultColumn = defaultColumn;
  exports.defaultGroupByFn = defaultGroupByFn;
  exports.defaultOrderByFn = defaultOrderByFn;
  exports.defaultRenderer = defaultRenderer;
  exports.emptyRenderer = emptyRenderer;
  exports.ensurePluginOrder = ensurePluginOrder;
  exports.flexRender = flexRender;
  exports.functionalUpdate = functionalUpdate;
  exports.loopHooks = loopHooks;
  exports.makePropGetter = makePropGetter;
  exports.makeRenderer = makeRenderer;
  exports.reduceHooks = reduceHooks;
  exports.safeUseLayoutEffect = safeUseLayoutEffect;
  exports.useAbsoluteLayout = useAbsoluteLayout;
  exports.useAsyncDebounce = useAsyncDebounce;
  exports.useBlockLayout = useBlockLayout;
  exports.useColumnOrder = useColumnOrder;
  exports.useExpanded = useExpanded;
  exports.useFilters = useFilters;
  exports.useFlexLayout = useFlexLayout;
  exports.useGetLatest = useGetLatest;
  exports.useGlobalFilter = useGlobalFilter;
  exports.useGridLayout = useGridLayout;
  exports.useGroupBy = useGroupBy;
  exports.useMountedLayoutEffect = useMountedLayoutEffect;
  exports.usePagination = usePagination;
  exports.useResizeColumns = useResizeColumns;
  exports.useRowSelect = useRowSelect;
  exports.useRowState = useRowState;
  exports.useSortBy = useSortBy;
  exports.useTable = useTable;

  Object.defineProperty(exports, '__esModule', { value: true });

})));
//# sourceMappingURL=react-table.development.js.map


/***/ }),

/***/ "./node_modules/react-table/index.js":
/*!*******************************************!*\
  !*** ./node_modules/react-table/index.js ***!
  \*******************************************/
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

if (false) {} else {
  module.exports = __webpack_require__(/*! ./dist/react-table.development.js */ "./node_modules/react-table/dist/react-table.development.js")
}


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

"use strict";
module.exports = window["React"];

/***/ }),

/***/ "moment":
/*!*************************!*\
  !*** external "moment" ***!
  \*************************/
/***/ (function(module) {

"use strict";
module.exports = window["moment"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/extends.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/extends.js ***!
  \************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _extends; }
/* harmony export */ });
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!***************************************************************!*\
  !*** ./includes/blocks/src/course-list/editor-course-list.js ***!
  \***************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/course-list/editor.scss");
/* harmony import */ var _index_course_list_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./index-course-list.js */ "./includes/blocks/src/course-list/index-course-list.js");
/* harmony import */ var apexcharts__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! apexcharts */ "./node_modules/apexcharts/dist/apexcharts.common.js");
/* harmony import */ var apexcharts__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(apexcharts__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var react_csv__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react-csv */ "./node_modules/react-csv/index.js");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_9__);
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */








let globalHooks = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_8__.createHooks)();

const icon = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("svg", {
  version: "1.0",
  xmlns: "http://www.w3.org/2000/svg",
  width: "24.000000pt",
  height: "24.000000pt",
  viewBox: "0 0 24.000000 24.000000",
  preserveAspectRatio: "xMidYMid meet"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M11.9,8.7h4.8c0.3,0,0.6-0.3,0.6-0.7c0-0.4-0.3-0.7-0.6-0.7h-4.8c-0.3,0-0.6,0.3-0.6,0.7C11.4,8.3,11.6,8.7,11.9,8.7z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M16.7,12.4h-4.8c-0.3,0-0.6,0.3-0.6,0.7s0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7S17,12.4,16.7,12.4z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M16.5,17.5h-4.8c-0.3,0-0.6,0.3-0.6,0.7c0,0.4,0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7C17.1,17.5,16.7,17.5,16.5,17.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M7.5,6.2C7.4,6.1,7.2,6.1,7,6.2C6.8,6.3,6.7,6.5,6.7,6.7v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 C9.9,8.4,10,8.2,10,8.1c0-0.2-0.1-0.3-0.2-0.4L7.5,6.2z M8.6,8.1L7.7,8.6v-1L8.6,8.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M7.5,11.2c-0.1-0.1-0.3-0.1-0.5,0c-0.2,0.1-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,11.2z M8.6,13.1l-0.9,0.5v-1L8.6,13.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M7.5,16.2c-0.1-0.1-0.3-0.1-0.5,0s-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,16.2z M8.6,18.1l-0.9,0.5v-1L8.6,18.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M2.9,21.6c0,1.1,0.9,2,1.9,2h14.4c1.1,0,1.9-0.9,1.9-2V3.7c0-1.1-0.9-2-1.9-2h-2.6l-0.1-0.1c-0.7-0.8-1.7-1.2-2.7-1.2h-3.7 c-1,0-1.9,0.4-2.6,1.2l0,0.1H4.9c-1.1,0-2,0.9-2,2V21.6z M7.8,3.2c0.4-1,1.4-1.7,2.3-1.7h3.7c1,0,2,0.7,2.3,1.7l0.1,0.2H7.7L7.8,3.2 z M4.1,3.7c0-0.5,0.4-0.9,0.8-0.9h2L6.8,3C6.7,3.3,6.6,3.7,6.6,4c0,0.4,0.2,0.6,0.6,0.6h9.7c0.3,0,0.5-0.3,0.5-0.6s0-0.6-0.1-0.9 l-0.1-0.2h2c0.5,0,0.8,0.4,0.8,0.9v17.9c0,0.5-0.4,0.9-0.8,0.9H4.9c-0.5,0-0.8-0.4-0.8-0.9V3.7z"
}));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('wisdm-learndash-reports/course-list', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Course List', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('A table containing a list of the courses', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-course-list',
  icon,
  attributes: {
    blockContent: {
      type: 'html',
      default: ''
    }
  },
  /**
       * edit function
       * 
       * Makes the markup for the editor interface.
       * 
       * @param {object} ObjectArgs {
       *      className - Automatic CSS class. Based on the block name: gutenberg-block-samples-block-simple
       * }
       * 
       * @return {JSX object} ECMAScript JSX Markup for the editor 
       */
  edit(props) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_index_course_list_js__WEBPACK_IMPORTED_MODULE_5__["default"], null));
  },
  /**
   * save function
   * 
   * Makes the markup that will be rendered on the site page
   * 
   * @return {JSX object} ECMAScript JSX Markup for the site
   */
  save() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps.save(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", {
      class: "wisdm-learndash-reports-course-list"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-course-list.js.map
define(["require","exports","jquery"],function(i,c,n){"use strict";var e;Object.defineProperty(c,"__esModule",{value:!0}),(e=n).fn.loadingSpinner=function(i){var c;return"off"==i?c=function(i){i.removeClass("ui-loading").find(".ui-loading-container").remove()}:"on"!=i&&null!=i||(c=function(i){"static"===i.css("position")&&i.css("position","relative"),i.addClass("ui-loading"),0==i.find(".ui-loading-container").length&&i.append(e('\n<div class="ui-loading-container">\n    <div class="ui-loading-spinner">\n        <div class="circle circle-1"></div>\n        <div class="circle circle-2"></div>\n        <div class="circle circle-3"></div>\n        <div class="circle circle-4"></div>\n        <div class="circle circle-5"></div>\n        <div class="circle circle-6"></div>\n        <div class="circle circle-7"></div>\n        <div class="circle circle-8"></div>\n        <div class="circle circle-9"></div>\n        <div class="circle circle-10"></div>\n        <div class="circle circle-11"></div>\n        <div class="circle circle-12"></div>\n    </div>\n</div>'))}),this.each(function(){c(e(this))})}});
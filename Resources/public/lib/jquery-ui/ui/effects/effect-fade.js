!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery","../version","../effect"],e):e(jQuery)}(function(n){"use strict";return n.effects.define("fade","toggle",function(e,t){var i="show"===e.mode;n(this).css("opacity",i?0:1).animate({opacity:i?1:0},{queue:!1,duration:e.duration,easing:e.easing,complete:t})})});
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery","./version"],e):e(jQuery)}(function(e){"use strict";return e.ui.safeActiveElement=function(n){var t;try{t=n.activeElement}catch(e){t=n.body}return t=(t=t||n.body).nodeName?t:n.body}});
define(["jquery","select2"],function(e){"use strict";function n(t){return t.id?e('<span><span class="fa fa-'+t.id+'"></span> '+t.text+"</span>"):t.text}return{init:function(t){t.select2({templateResult:n,templateSelection:n})}}});
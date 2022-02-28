define(["jquery","ekyna-modal","ekyna-table","select2"],function(i,l,c){"use strict";return i.fn.entityWidget=function(){return this.each(function(){var e=i(this),t=e.find("button.new-resource"),n=e.find("button.list-resource"),o=e.find("select");1===t.length&&t.bind("click",function(){var e=new l;e.load({url:t.data("path")}),i(e).on("ekyna.modal.response",function(e){if("json"===e.contentType){e.preventDefault();var t=e.content,n=i("<option />");if(n.prop("value",t.id),n.prop("selected",!0),void 0!==t.choice_label)n.html(t.choice_label);else if(void 0!==t.text)n.html(t.text);else{if(void 0===t.title)throw"Unexpected resource data.";n.html(t.title)}o.find("option").prop("selected",!1),o.append(n).trigger("change"),e.modal.close()}})}),1===n.length&&n.bind("click",function(){var t=new l;t.load({url:n.data("path")}),i(t).on("ekyna.modal.content",function(e){if("table"!==e.contentType)throw"Expected modal content type = 'table'.";c.create(e.content,{ajax:!0,onSelection:function(e){o.prop("multiple")&&o.find("option").prop("selected",!1),i(e).each(function(e,t){var n=o.find("option[value="+t.id+"]");1===n.length?n.prop("selected",!0):((n=i("<option />")).prop("value",t.id),n.prop("selected",!0),void 0!==t.choice_label?n.html(t.choice_label):void 0!==t.text?n.html(t.text):void 0!==t.title?n.html(t.title):n.html("Entity #"+t.id),o.append(n))}),o.trigger("change"),t.getDialog().close()}})})})}),this},{init:function(e){e.entityWidget()}}});
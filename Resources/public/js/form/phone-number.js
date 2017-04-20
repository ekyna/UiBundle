define(["jquery","ekyna-flags","ekyna-string"],function(a,b){"use strict";b.load();var c=a.getJSON("/js/countries.json?locale="+a("html").attr("lang")),d=function(b){this.$form=a(b),this.$country=this.$form.find("#"+this.$form.attr("id")+"_country"),this.$number=this.$form.find("#"+this.$form.attr("id")+"_number"),this.$dropdown=this.$form.find(".dropdown"),this.$flag=this.$dropdown.find("button > .country-flag"),this.$dial=this.$dropdown.find("button > .country-dial"),this.$list=null,this.$current=null,this.$watch=null,this.spellString="",this.spellTimeout=null};return d.prototype.init=function(){var b=this;c.then(function(a){b.buildList(a)}),this.onListClick=a.proxy(this.listClickHandler,this),this.onListKeydown=a.proxy(this.listKeydownHandler,this),this.onSpellTimeout=a.proxy(function(){this.spellString="",this.spellTimeout=null},this)},d.prototype.buildList=function(b){var c=document.createElement("div"),d=document.createElement("ul");this.$dropdown.find("> .dropdown-menu").empty().append(c),c.appendChild(d),a.each(b,function(a,b){var c=document.createElement("li"),e=document.createElement("span"),f=document.createElement("span"),g=document.createElement("span");c.setAttribute("data-name",b.name.removeDiatrics().toLowerCase().replace(/[^a-z]+/," ")),c.setAttribute("data-code",a),c.setAttribute("data-dial",b.dial),c.setAttribute("data-fixed",b.fixed),c.setAttribute("data-mobile",b.mobile),e.classList.add("country-flag",a.toLowerCase()),c.appendChild(e),f.classList.add("country-name"),f.innerText=b.name,c.appendChild(f),0<b.dial&&(g.classList.add("country-dial"),g.innerText="+"+b.dial,c.appendChild(g)),d.appendChild(c)}),this.$list=a(c),this.selectCountry(this.$dropdown.find('li[data-code="'+this.$country.val()+'"]')),this.$dropdown.on("show.bs.dropdown",a.proxy(this.enableListHandlers,this)),this.$dropdown.on("hide.bs.dropdown",a.proxy(this.disableListHandlers,this)),this.$dropdown.on("shown.bs.dropdown",a.proxy(this.scrollToSelected,this)),this.$country.on("change",a.proxy(this.countryChangeHandler,this)),this.$form.data("watch")&&(this.$watch=a("#"+this.$form.data("watch")),this.$watch.length&&(this.$watch.on("change",a.proxy(this.watchChangeHandler,this)),this.watchChangeHandler()))},d.prototype.enableListHandlers=function(){this.$dropdown.on("click","li",this.onListClick),this.$dropdown.on("keydown",this.onListKeydown)},d.prototype.disableListHandlers=function(){this.$dropdown.off("click","li",this.onListClick),this.$dropdown.off("keydown",this.onListKeydown)},d.prototype.countryChangeHandler=function(){this.$country.val()&&this.selectCountry(this.$dropdown.find('li[data-code="'+this.$country.val()+'"]'))},d.prototype.watchChangeHandler=function(){this.$number.val()||this.selectCountry(this.$dropdown.find('li[data-code="'+this.$watch.val()+'"]'))},d.prototype.listClickHandler=function(b){this.selectCountry(a(b.currentTarget))},d.prototype.listKeydownHandler=function(a){return 38===a.which?void this.selectPrevious():40===a.which?void this.selectNext():void((65<=a.which&&a.which<=90||97<=a.which&&a.which<=122)&&this.spellSelect(String.fromCharCode(a.which)))},d.prototype.selectPrevious=function(){if(0===this.$current.length)return void this.selectCountry(this.$dropdown.find("li:first-child"));var a=this.$current.prev();return a.length?void this.selectCountry(a):void this.selectCountry(this.$dropdown.find("li:last-child"))},d.prototype.selectNext=function(){if(0===this.$current.length)return void this.selectCountry(this.$dropdown.find("li:first-child"));var a=this.$current.next();return a.length?void this.selectCountry(a):void this.selectCountry(this.$dropdown.find("li:first-child"))},d.prototype.spellSelect=function(b){this.spellTimeout&&clearTimeout(this.spellTimeout),this.spellString+=b;var c,d=new RegExp("^"+this.spellString,"i");this.$dropdown.find("li").each(function(b,e){if(d.test(e.getAttribute("data-name")))return c=a(e),!1}),this.spellTimeout=setTimeout(this.onSpellTimeout,500),c&&this.selectCountry(c)},d.prototype.selectCountry=function(a){if(0!==a.length&&this.$current!==a){this.$current&&(this.$current.removeClass("active"),this.$flag.removeClass(String(this.$current.data("code")).toLowerCase()),this.$dial.empty(),this.$current=null),this.$current=a,this.$current.addClass("active");var b=String(this.$current.data("code"));b!==this.$country.val()&&this.$country.val(b),this.$flag.addClass(b.toLowerCase()),this.$dial.text("+"+this.$current.data("dial")),this.$number.removeAttr("placeholder");var c=this.$current.data(this.$form.data("type"));c&&this.$number.attr("placeholder",c),this.scrollToSelected()}},d.prototype.scrollToSelected=function(){this.$current&&this.$current.length&&this.$list.scrollTop(this.$current.position().top+this.$list.scrollTop())},a.fn.phoneNumberWidget=function(){return this.each(function(){new d(this).init()})},{init:function(a){a.phoneNumberWidget()}}});
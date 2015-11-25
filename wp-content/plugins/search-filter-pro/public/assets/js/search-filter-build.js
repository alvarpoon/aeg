(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

var fields = require('./includes/fields');
var pagination = require('./includes/pagination');
var state = require('./includes/state');
var plugin = require('./includes/plugin');

(function ( $ ) {
	
	"use strict";

	$(function () {
		 
		String.prototype.replaceAll = function(str1, str2, ignore) 
		{
			return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
		}
		
		if (!Object.keys) {
		  Object.keys = (function () {
			'use strict';
			var hasOwnProperty = Object.prototype.hasOwnProperty,
				hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
				dontEnums = [
				  'toString',
				  'toLocaleString',
				  'valueOf',
				  'hasOwnProperty',
				  'isPrototypeOf',
				  'propertyIsEnumerable',
				  'constructor'
				],
				dontEnumsLength = dontEnums.length;

			return function (obj) {
			  if (typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)) {
				throw new TypeError('Object.keys called on non-object');
			  }

			  var result = [], prop, i;

			  for (prop in obj) {
				if (hasOwnProperty.call(obj, prop)) {
				  result.push(prop);
				}
			  }

			  if (hasDontEnumBug) {
				for (i = 0; i < dontEnumsLength; i++) {
				  if (hasOwnProperty.call(obj, dontEnums[i])) {
					result.push(dontEnums[i]);
				  }
				}
			  }
			  return result;
			};
		  }());
		}
		
		/* Search & Filter jQuery Plugin */
		jQuery.fn.searchAndFilter = plugin;
		
		/* init */
		$(".searchandfilter").searchAndFilter();
		
		/* external controls */
		$(document).on("click", ".search-filter-reset", function(e){
			
			e.preventDefault();
			
			var searchFormID = typeof($(this).attr("data-search-form-id"))!="undefined" ? $(this).attr("data-search-form-id") : "";
			
			state.getSearchForm(searchFormID).reset();
			
			//var $linked = $("#search-filter-form-"+searchFormID).searchFilterForm({action: "reset"});
			
			return false;
			
		});
		
	});	
	
	$.easing.jswing=$.easing.swing;$.extend($.easing,{def:"easeOutQuad",swing:function(e,t,n,r,i){return $.easing[$.easing.def](e,t,n,r,i)},easeInQuad:function(e,t,n,r,i){return r*(t/=i)*t+n},easeOutQuad:function(e,t,n,r,i){return-r*(t/=i)*(t-2)+n},easeInOutQuad:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t+n;return-r/2*(--t*(t-2)-1)+n},easeInCubic:function(e,t,n,r,i){return r*(t/=i)*t*t+n},easeOutCubic:function(e,t,n,r,i){return r*((t=t/i-1)*t*t+1)+n},easeInOutCubic:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t+n;return r/2*((t-=2)*t*t+2)+n},easeInQuart:function(e,t,n,r,i){return r*(t/=i)*t*t*t+n},easeOutQuart:function(e,t,n,r,i){return-r*((t=t/i-1)*t*t*t-1)+n},easeInOutQuart:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t*t+n;return-r/2*((t-=2)*t*t*t-2)+n},easeInQuint:function(e,t,n,r,i){return r*(t/=i)*t*t*t*t+n},easeOutQuint:function(e,t,n,r,i){return r*((t=t/i-1)*t*t*t*t+1)+n},easeInOutQuint:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t*t*t+n;return r/2*((t-=2)*t*t*t*t+2)+n},easeInSine:function(e,t,n,r,i){return-r*Math.cos(t/i*(Math.PI/2))+r+n},easeOutSine:function(e,t,n,r,i){return r*Math.sin(t/i*(Math.PI/2))+n},easeInOutSine:function(e,t,n,r,i){return-r/2*(Math.cos(Math.PI*t/i)-1)+n},easeInExpo:function(e,t,n,r,i){return t==0?n:r*Math.pow(2,10*(t/i-1))+n},easeOutExpo:function(e,t,n,r,i){return t==i?n+r:r*(-Math.pow(2,-10*t/i)+1)+n},easeInOutExpo:function(e,t,n,r,i){if(t==0)return n;if(t==i)return n+r;if((t/=i/2)<1)return r/2*Math.pow(2,10*(t-1))+n;return r/2*(-Math.pow(2,-10*--t)+2)+n},easeInCirc:function(e,t,n,r,i){return-r*(Math.sqrt(1-(t/=i)*t)-1)+n},easeOutCirc:function(e,t,n,r,i){return r*Math.sqrt(1-(t=t/i-1)*t)+n},easeInOutCirc:function(e,t,n,r,i){if((t/=i/2)<1)return-r/2*(Math.sqrt(1-t*t)-1)+n;return r/2*(Math.sqrt(1-(t-=2)*t)+1)+n},easeInElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i)==1)return n+r;if(!o)o=i*.3;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);return-(u*Math.pow(2,10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o))+n},easeOutElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i)==1)return n+r;if(!o)o=i*.3;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);return u*Math.pow(2,-10*t)*Math.sin((t*i-s)*2*Math.PI/o)+r+n},easeInOutElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i/2)==2)return n+r;if(!o)o=i*.3*1.5;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);if(t<1)return-.5*u*Math.pow(2,10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o)+n;return u*Math.pow(2,-10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o)*.5+r+n},easeInBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;return r*(t/=i)*t*((s+1)*t-s)+n},easeOutBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;return r*((t=t/i-1)*t*((s+1)*t+s)+1)+n},easeInOutBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;if((t/=i/2)<1)return r/2*t*t*(((s*=1.525)+1)*t-s)+n;return r/2*((t-=2)*t*(((s*=1.525)+1)*t+s)+2)+n},easeInBounce:function(e,t,n,r,i){return r-$.easing.easeOutBounce(e,i-t,0,r,i)+n},easeOutBounce:function(e,t,n,r,i){if((t/=i)<1/2.75){return r*7.5625*t*t+n}else if(t<2/2.75){return r*(7.5625*(t-=1.5/2.75)*t+.75)+n}else if(t<2.5/2.75){return r*(7.5625*(t-=2.25/2.75)*t+.9375)+n}else{return r*(7.5625*(t-=2.625/2.75)*t+.984375)+n}},easeInOutBounce:function(e,t,n,r,i){if(t<i/2)return $.easing.easeInBounce(e,t*2,0,r,i)*.5+n;return $.easing.easeOutBounce(e,t*2-i,0,r,i)*.5+r*.5+n}})	
			
}(jQuery));

/* Chosen v1.1.0 | (c) 2011-2013 by Harvest | MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md */
!function(){var a,AbstractChosen,Chosen,SelectParser,b,c={}.hasOwnProperty,d=function(a,b){function d(){this.constructor=a}for(var e in b)c.call(b,e)&&(a[e]=b[e]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};SelectParser=function(){function SelectParser(){this.options_index=0,this.parsed=[]}return SelectParser.prototype.add_node=function(a){return"OPTGROUP"===a.nodeName.toUpperCase()?this.add_group(a):this.add_option(a)},SelectParser.prototype.add_group=function(a){var b,c,d,e,f,g;for(b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:this.escapeExpression(a.label),children:0,disabled:a.disabled}),f=a.childNodes,g=[],d=0,e=f.length;e>d;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},SelectParser.prototype.add_option=function(a,b,c){return"OPTION"===a.nodeName.toUpperCase()?(""!==a.text?(null!=b&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1):void 0},SelectParser.prototype.escapeExpression=function(a){var b,c;return null==a||a===!1?"":/[\&\<\>\"\'\`]/.test(a)?(b={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},c=/&(?!\w+;)|[\<\>\"\'\`]/g,a.replace(c,function(a){return b[a]||"&amp;"})):a},SelectParser}(),SelectParser.select_to_array=function(a){var b,c,d,e,f;for(c=new SelectParser,f=a.childNodes,d=0,e=f.length;e>d;d++)b=f[d],c.add_node(b);return c.parsed},AbstractChosen=function(){function AbstractChosen(a,b){this.form_field=a,this.options=null!=b?b:{},AbstractChosen.browser_is_supported()&&(this.is_multiple=this.form_field.multiple,this.set_default_text(),this.set_default_values(),this.setup(),this.set_up_html(),this.register_observers())}return AbstractChosen.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.allow_single_deselect=null!=this.options.allow_single_deselect&&null!=this.form_field.options[0]&&""===this.form_field.options[0].text?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.disable_search=this.options.disable_search||!1,this.enable_split_word_search=null!=this.options.enable_split_word_search?this.options.enable_split_word_search:!0,this.group_search=null!=this.options.group_search?this.options.group_search:!0,this.search_contains=this.options.search_contains||!1,this.single_backstroke_delete=null!=this.options.single_backstroke_delete?this.options.single_backstroke_delete:!0,this.max_selected_options=this.options.max_selected_options||1/0,this.inherit_select_classes=this.options.inherit_select_classes||!1,this.display_selected_options=null!=this.options.display_selected_options?this.options.display_selected_options:!0,this.display_disabled_options=null!=this.options.display_disabled_options?this.options.display_disabled_options:!0},AbstractChosen.prototype.set_default_text=function(){return this.default_text=this.form_field.getAttribute("data-placeholder")?this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.options.placeholder_text_multiple||this.options.placeholder_text||AbstractChosen.default_multiple_text:this.options.placeholder_text_single||this.options.placeholder_text||AbstractChosen.default_single_text,this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||AbstractChosen.default_no_result_text},AbstractChosen.prototype.mouse_enter=function(){return this.mouse_on_container=!0},AbstractChosen.prototype.mouse_leave=function(){return this.mouse_on_container=!1},AbstractChosen.prototype.input_focus=function(){var a=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return a.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()},AbstractChosen.prototype.input_blur=function(){var a=this;return this.mouse_on_container?void 0:(this.active_field=!1,setTimeout(function(){return a.blur_test()},100))},AbstractChosen.prototype.results_option_build=function(a){var b,c,d,e,f;for(b="",f=this.results_data,d=0,e=f.length;e>d;d++)c=f[d],b+=c.group?this.result_add_group(c):this.result_add_option(c),(null!=a?a.first:void 0)&&(c.selected&&this.is_multiple?this.choice_build(c):c.selected&&!this.is_multiple&&this.single_set_selected_text(c.text));return b},AbstractChosen.prototype.result_add_option=function(a){var b,c;return a.search_match?this.include_option_in_results(a)?(b=[],a.disabled||a.selected&&this.is_multiple||b.push("active-result"),!a.disabled||a.selected&&this.is_multiple||b.push("disabled-result"),a.selected&&b.push("result-selected"),null!=a.group_array_index&&b.push("group-option"),""!==a.classes&&b.push(a.classes),c=document.createElement("li"),c.className=b.join(" "),c.style.cssText=a.style,c.setAttribute("data-option-array-index",a.array_index),c.innerHTML=a.search_text,this.outerHTML(c)):"":""},AbstractChosen.prototype.result_add_group=function(a){var b;return a.search_match||a.group_match?a.active_options>0?(b=document.createElement("li"),b.className="group-result",b.innerHTML=a.search_text,this.outerHTML(b)):"":""},AbstractChosen.prototype.results_update_field=function(){return this.set_default_text(),this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.results_build(),this.results_showing?this.winnow_results():void 0},AbstractChosen.prototype.reset_single_select_options=function(){var a,b,c,d,e;for(d=this.results_data,e=[],b=0,c=d.length;c>b;b++)a=d[b],a.selected?e.push(a.selected=!1):e.push(void 0);return e},AbstractChosen.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},AbstractChosen.prototype.results_search=function(){return this.results_showing?this.winnow_results():this.results_show()},AbstractChosen.prototype.winnow_results=function(){var a,b,c,d,e,f,g,h,i,j,k,l,m;for(this.no_results_clear(),e=0,g=this.get_search_text(),a=g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),d=this.search_contains?"":"^",c=new RegExp(d+a,"i"),j=new RegExp(a,"i"),m=this.results_data,k=0,l=m.length;l>k;k++)b=m[k],b.search_match=!1,f=null,this.include_option_in_results(b)&&(b.group&&(b.group_match=!1,b.active_options=0),null!=b.group_array_index&&this.results_data[b.group_array_index]&&(f=this.results_data[b.group_array_index],0===f.active_options&&f.search_match&&(e+=1),f.active_options+=1),(!b.group||this.group_search)&&(b.search_text=b.group?b.label:b.html,b.search_match=this.search_string_match(b.search_text,c),b.search_match&&!b.group&&(e+=1),b.search_match?(g.length&&(h=b.search_text.search(j),i=b.search_text.substr(0,h+g.length)+"</em>"+b.search_text.substr(h+g.length),b.search_text=i.substr(0,h)+"<em>"+i.substr(h)),null!=f&&(f.group_match=!0)):null!=b.group_array_index&&this.results_data[b.group_array_index].search_match&&(b.search_match=!0)));return this.result_clear_highlight(),1>e&&g.length?(this.update_results_content(""),this.no_results(g)):(this.update_results_content(this.results_option_build()),this.winnow_results_set_highlight())},AbstractChosen.prototype.search_string_match=function(a,b){var c,d,e,f;if(b.test(a))return!0;if(this.enable_split_word_search&&(a.indexOf(" ")>=0||0===a.indexOf("["))&&(d=a.replace(/\[|\]/g,"").split(" "),d.length))for(e=0,f=d.length;f>e;e++)if(c=d[e],b.test(c))return!0},AbstractChosen.prototype.choices_count=function(){var a,b,c,d;if(null!=this.selected_option_count)return this.selected_option_count;for(this.selected_option_count=0,d=this.form_field.options,b=0,c=d.length;c>b;b++)a=d[b],a.selected&&(this.selected_option_count+=1);return this.selected_option_count},AbstractChosen.prototype.choices_click=function(a){return a.preventDefault(),this.results_showing||this.is_disabled?void 0:this.results_show()},AbstractChosen.prototype.keyup_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:if(a.preventDefault(),this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},AbstractChosen.prototype.clipboard_event_checker=function(){var a=this;return setTimeout(function(){return a.results_search()},50)},AbstractChosen.prototype.container_width=function(){return null!=this.options.width?this.options.width:""+this.form_field.offsetWidth+"px"},AbstractChosen.prototype.include_option_in_results=function(a){return this.is_multiple&&!this.display_selected_options&&a.selected?!1:!this.display_disabled_options&&a.disabled?!1:a.empty?!1:!0},AbstractChosen.prototype.search_results_touchstart=function(a){return this.touch_started=!0,this.search_results_mouseover(a)},AbstractChosen.prototype.search_results_touchmove=function(a){return this.touch_started=!1,this.search_results_mouseout(a)},AbstractChosen.prototype.search_results_touchend=function(a){return this.touch_started?this.search_results_mouseup(a):void 0},AbstractChosen.prototype.outerHTML=function(a){var b;return a.outerHTML?a.outerHTML:(b=document.createElement("div"),b.appendChild(a),b.innerHTML)},AbstractChosen.browser_is_supported=function(){return"Microsoft Internet Explorer"===window.navigator.appName?document.documentMode>=8:/iP(od|hone)/i.test(window.navigator.userAgent)?!1:/Android/i.test(window.navigator.userAgent)&&/Mobile/i.test(window.navigator.userAgent)?!1:!0},AbstractChosen.default_multiple_text="Select Some Options",AbstractChosen.default_single_text="Select an Option",AbstractChosen.default_no_result_text="No results match",AbstractChosen}(),a=jQuery,a.fn.extend({chosen:function(b){return AbstractChosen.browser_is_supported()?this.each(function(){var c,d;c=a(this),d=c.data("chosen"),"destroy"===b&&d?d.destroy():d||c.data("chosen",new Chosen(this,b))}):this}}),Chosen=function(c){function Chosen(){return b=Chosen.__super__.constructor.apply(this,arguments)}return d(Chosen,c),Chosen.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.current_selectedIndex=this.form_field.selectedIndex,this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")},Chosen.prototype.set_up_html=function(){var b,c;return b=["chosen-container"],b.push("chosen-container-"+(this.is_multiple?"multi":"single")),this.inherit_select_classes&&this.form_field.className&&b.push(this.form_field.className),this.is_rtl&&b.push("chosen-rtl"),c={"class":b.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title},this.form_field.id.length&&(c.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen"),this.container=a("<div />",c),this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),this.form_field_jq.hide().after(this.container),this.dropdown=this.container.find("div.chosen-drop").first(),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chosen-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chosen-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chosen-search").first(),this.selected_item=this.container.find(".chosen-single").first()),this.results_build(),this.set_tab_index(),this.set_label_behavior(),this.form_field_jq.trigger("chosen:ready",{chosen:this})},Chosen.prototype.register_observers=function(){var a=this;return this.container.bind("mousedown.chosen",function(b){a.container_mousedown(b)}),this.container.bind("mouseup.chosen",function(b){a.container_mouseup(b)}),this.container.bind("mouseenter.chosen",function(b){a.mouse_enter(b)}),this.container.bind("mouseleave.chosen",function(b){a.mouse_leave(b)}),this.search_results.bind("mouseup.chosen",function(b){a.search_results_mouseup(b)}),this.search_results.bind("mouseover.chosen",function(b){a.search_results_mouseover(b)}),this.search_results.bind("mouseout.chosen",function(b){a.search_results_mouseout(b)}),this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(b){a.search_results_mousewheel(b)}),this.search_results.bind("touchstart.chosen",function(b){a.search_results_touchstart(b)}),this.search_results.bind("touchmove.chosen",function(b){a.search_results_touchmove(b)}),this.search_results.bind("touchend.chosen",function(b){a.search_results_touchend(b)}),this.form_field_jq.bind("chosen:updated.chosen",function(b){a.results_update_field(b)}),this.form_field_jq.bind("chosen:activate.chosen",function(b){a.activate_field(b)}),this.form_field_jq.bind("chosen:open.chosen",function(b){a.container_mousedown(b)}),this.form_field_jq.bind("chosen:close.chosen",function(b){a.input_blur(b)}),this.search_field.bind("blur.chosen",function(b){a.input_blur(b)}),this.search_field.bind("keyup.chosen",function(b){a.keyup_checker(b)}),this.search_field.bind("keydown.chosen",function(b){a.keydown_checker(b)}),this.search_field.bind("focus.chosen",function(b){a.input_focus(b)}),this.search_field.bind("cut.chosen",function(b){a.clipboard_event_checker(b)}),this.search_field.bind("paste.chosen",function(b){a.clipboard_event_checker(b)}),this.is_multiple?this.search_choices.bind("click.chosen",function(b){a.choices_click(b)}):this.container.bind("click.chosen",function(a){a.preventDefault()})},Chosen.prototype.destroy=function(){return a(this.container[0].ownerDocument).unbind("click.chosen",this.click_test_action),this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex),this.container.remove(),this.form_field_jq.removeData("chosen"),this.form_field_jq.show()},Chosen.prototype.search_field_disabled=function(){return this.is_disabled=this.form_field_jq[0].disabled,this.is_disabled?(this.container.addClass("chosen-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action),this.close_field()):(this.container.removeClass("chosen-disabled"),this.search_field[0].disabled=!1,this.is_multiple?void 0:this.selected_item.bind("focus.chosen",this.activate_action))},Chosen.prototype.container_mousedown=function(b){return this.is_disabled||(b&&"mousedown"===b.type&&!this.results_showing&&b.preventDefault(),null!=b&&a(b.target).hasClass("search-choice-close"))?void 0:(this.active_field?this.is_multiple||!b||a(b.target)[0]!==this.selected_item[0]&&!a(b.target).parents("a.chosen-single").length||(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(this.container[0].ownerDocument).bind("click.chosen",this.click_test_action),this.results_show()),this.activate_field())},Chosen.prototype.container_mouseup=function(a){return"ABBR"!==a.target.nodeName||this.is_disabled?void 0:this.results_reset(a)},Chosen.prototype.search_results_mousewheel=function(a){var b;return a.originalEvent&&(b=-a.originalEvent.wheelDelta||a.originalEvent.detail),null!=b?(a.preventDefault(),"DOMMouseScroll"===a.type&&(b=40*b),this.search_results.scrollTop(b+this.search_results.scrollTop())):void 0},Chosen.prototype.blur_test=function(){return!this.active_field&&this.container.hasClass("chosen-container-active")?this.close_field():void 0},Chosen.prototype.close_field=function(){return a(this.container[0].ownerDocument).unbind("click.chosen",this.click_test_action),this.active_field=!1,this.results_hide(),this.container.removeClass("chosen-container-active"),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},Chosen.prototype.activate_field=function(){return this.container.addClass("chosen-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},Chosen.prototype.test_active_click=function(b){var c;return c=a(b.target).closest(".chosen-container"),c.length&&this.container[0]===c[0]?this.active_field=!0:this.close_field()},Chosen.prototype.results_build=function(){return this.parsing=!0,this.selected_option_count=null,this.results_data=SelectParser.select_to_array(this.form_field),this.is_multiple?this.search_choices.find("li.search-choice").remove():this.is_multiple||(this.single_set_selected_text(),this.disable_search||this.form_field.options.length<=this.disable_search_threshold?(this.search_field[0].readOnly=!0,this.container.addClass("chosen-container-single-nosearch")):(this.search_field[0].readOnly=!1,this.container.removeClass("chosen-container-single-nosearch"))),this.update_results_content(this.results_option_build({first:!0})),this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.parsing=!1},Chosen.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){if(this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight(),b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(f>c)return this.search_results.scrollTop(c)}},Chosen.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},Chosen.prototype.results_show=function(){return this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.container.addClass("chosen-with-drop"),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results(),this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this}))},Chosen.prototype.update_results_content=function(a){return this.search_results.html(a)},Chosen.prototype.results_hide=function(){return this.results_showing&&(this.result_clear_highlight(),this.container.removeClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})),this.results_showing=!1},Chosen.prototype.set_tab_index=function(){var a;return this.form_field.tabIndex?(a=this.form_field.tabIndex,this.form_field.tabIndex=-1,this.search_field[0].tabIndex=a):void 0},Chosen.prototype.set_label_behavior=function(){var b=this;return this.form_field_label=this.form_field_jq.parents("label"),!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=a("label[for='"+this.form_field.id+"']")),this.form_field_label.length>0?this.form_field_label.bind("click.chosen",function(a){return b.is_multiple?b.container_mousedown(a):b.activate_field()}):void 0},Chosen.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices_count()<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},Chosen.prototype.search_results_mouseup=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c.length?(this.result_highlight=c,this.result_select(b),this.search_field.focus()):void 0},Chosen.prototype.search_results_mouseover=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c?this.result_do_highlight(c):void 0},Chosen.prototype.search_results_mouseout=function(b){return a(b.target).hasClass("active-result")?this.result_clear_highlight():void 0},Chosen.prototype.choice_build=function(b){var c,d,e=this;return c=a("<li />",{"class":"search-choice"}).html("<span>"+b.html+"</span>"),b.disabled?c.addClass("search-choice-disabled"):(d=a("<a />",{"class":"search-choice-close","data-option-array-index":b.array_index}),d.bind("click.chosen",function(a){return e.choice_destroy_link_click(a)}),c.append(d)),this.search_container.before(c)},Chosen.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),b.stopPropagation(),this.is_disabled?void 0:this.choice_destroy(a(b.target))},Chosen.prototype.choice_destroy=function(a){return this.result_deselect(a[0].getAttribute("data-option-array-index"))?(this.show_search_field_default(),this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide(),a.parents("li").first().remove(),this.search_field_scale()):void 0},Chosen.prototype.results_reset=function(){return this.reset_single_select_options(),this.form_field.options[0].selected=!0,this.single_set_selected_text(),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change"),this.active_field?this.results_hide():void 0},Chosen.prototype.results_reset_cleanup=function(){return this.current_selectedIndex=this.form_field.selectedIndex,this.selected_item.find("abbr").remove()},Chosen.prototype.result_select=function(a){var b,c;return this.result_highlight?(b=this.result_highlight,this.result_clear_highlight(),this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.is_multiple?b.removeClass("active-result"):this.reset_single_select_options(),c=this.results_data[b[0].getAttribute("data-option-array-index")],c.selected=!0,this.form_field.options[c.options_index].selected=!0,this.selected_option_count=null,this.is_multiple?this.choice_build(c):this.single_set_selected_text(c.text),(a.metaKey||a.ctrlKey)&&this.is_multiple||this.results_hide(),this.search_field.val(""),(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[c.options_index].value}),this.current_selectedIndex=this.form_field.selectedIndex,this.search_field_scale())):void 0},Chosen.prototype.single_set_selected_text=function(a){return null==a&&(a=this.default_text),a===this.default_text?this.selected_item.addClass("chosen-default"):(this.single_deselect_control_build(),this.selected_item.removeClass("chosen-default")),this.selected_item.find("span").text(a)},Chosen.prototype.result_deselect=function(a){var b;return b=this.results_data[a],this.form_field.options[b.options_index].disabled?!1:(b.selected=!1,this.form_field.options[b.options_index].selected=!1,this.selected_option_count=null,this.result_clear_highlight(),this.results_showing&&this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[b.options_index].value}),this.search_field_scale(),!0)},Chosen.prototype.single_deselect_control_build=function(){return this.allow_single_deselect?(this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'),this.selected_item.addClass("chosen-single-with-deselect")):void 0},Chosen.prototype.get_search_text=function(){return this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html()},Chosen.prototype.winnow_results_set_highlight=function(){var a,b;return b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first(),null!=a?this.result_do_highlight(a):void 0},Chosen.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c),this.form_field_jq.trigger("chosen:no_results",{chosen:this})},Chosen.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},Chosen.prototype.keydown_arrow=function(){var a;return this.results_showing&&this.result_highlight?(a=this.result_highlight.nextAll("li.active-result").first())?this.result_do_highlight(a):void 0:this.results_show()},Chosen.prototype.keyup_arrow=function(){var a;return this.results_showing||this.is_multiple?this.result_highlight?(a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices_count()>0&&this.results_hide(),this.result_clear_highlight())):void 0:this.results_show()},Chosen.prototype.keydown_backstroke=function(){var a;return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(a=this.search_container.siblings("li.search-choice").last(),a.length&&!a.hasClass("search-choice-disabled")?(this.pending_backstroke=a,this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")):void 0)},Chosen.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},Chosen.prototype.keydown_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),8!==b&&this.pending_backstroke&&this.clear_backstroke(),b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:a.preventDefault(),this.keydown_arrow()}},Chosen.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){for(d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"],i=0,j=g.length;j>i;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return b=a("<div />",{style:f}),b.text(this.search_field.val()),a("body").append(b),h=b.width()+25,b.remove(),c=this.container.outerWidth(),h>c-10&&(h=c-10),this.search_field.css({width:h+"px"})}},Chosen}(AbstractChosen)}.call(this);

/* noui slider */
(function(f){if(f.zepto&&!f.fn.removeData)throw new ReferenceError("Zepto is loaded without the data module.");f.fn.noUiSlider=function(C,D){function s(a,b){return 100*b/(a[1]-a[0])}function E(a,b){return b*(a[1]-a[0])/100+a[0]}function t(a){return a instanceof f||f.zepto&&f.zepto.isZ(a)}function n(a){return!isNaN(parseFloat(a))&&isFinite(a)}function r(a,b){f.isArray(a)||(a=[a]);f.each(a,function(){"function"===typeof this&&this.call(b)})}function F(a,b){return function(){var c=[null,null];c[b]=f(this).val();
a.val(c,!0)}}function G(a,b){a=a.toFixed(b.decimals);0===parseFloat(a)&&(a=a.replace("-0","0"));return a.replace(".",b.serialization.mark)}function u(a){return parseFloat(a.toFixed(7))}function p(a,b,c,d){var e=d.target;a=a.replace(/\s/g,h+" ")+h;b.on(a,function(a){var b=e.attr("disabled");if(e.hasClass("noUi-state-tap")||void 0!==b&&null!==b)return!1;var g;a.preventDefault();var b=0===a.type.indexOf("touch"),h=0===a.type.indexOf("mouse"),l=0===a.type.indexOf("pointer"),v,H=a;0===a.type.indexOf("MSPointer")&&
(l=!0);a.originalEvent&&(a=a.originalEvent);b&&(g=a.changedTouches[0].pageX,v=a.changedTouches[0].pageY);if(h||l)l||void 0!==window.pageXOffset||(window.pageXOffset=document.documentElement.scrollLeft,window.pageYOffset=document.documentElement.scrollTop),g=a.clientX+window.pageXOffset,v=a.clientY+window.pageYOffset;g=f.extend(H,{pointX:g,pointY:v,cursor:h});c(g,d,e.data("base").data("options"))})}function I(a){var b=this.target;if(void 0===a)return this.element.data("value");!0===a?a=this.element.data("value"):
this.element.data("value",a);void 0!==a&&f.each(this.elements,function(){if("function"===typeof this)this.call(b,a);else this[0][this[1]](a)})}function J(a,b,c){if(t(b)){var d=[],e=a.data("target");a.data("options").direction&&(c=c?0:1);b.each(function(){f(this).on("change"+h,F(e,c));d.push([f(this),"val"])});return d}"string"===typeof b&&(b=[f('<input type="hidden" name="'+b+'">').appendTo(a).addClass(g[3]).change(function(a){a.stopPropagation()}),"val"]);return[b]}function K(a,b,c){var d=[];f.each(c.to[b],
function(e){d=d.concat(J(a,c.to[b][e],b))});return{element:a,elements:d,target:a.data("target"),val:I}}function L(a,b){var c=a.data("target");c.hasClass(g[14])||(b||(c.addClass(g[15]),setTimeout(function(){c.removeClass(g[15])},450)),c.addClass(g[14]),r(a.data("options").h,c))}function w(a,b){var c=a.data("options");b=u(b);a.data("target").removeClass(g[14]);a.css(c.style,b+"%").data("pct",b);a.is(":first-child")&&a.toggleClass(g[13],50<b);c.direction&&(b=100-b);a.data("store").val(G(E(c.range,b),
c))}function x(a,b){var c=a.data("base"),d=c.data("options"),c=c.data("handles"),e=0,k=100;if(!n(b))return!1;if(d.step){var m=d.step;b=Math.round(b/m)*m}1<c.length&&(a[0]!==c[0][0]?e=u(c[0].data("pct")+d.margin):k=u(c[1].data("pct")-d.margin));b=Math.min(Math.max(b,e),0>k?100:k);if(b===a.data("pct"))return[e?e:!1,100===k?!1:k];w(a,b);return!0}function A(a,b,c,d){a.addClass(g[5]);setTimeout(function(){a.removeClass(g[5])},300);x(b,c);r(d,a.data("target"));a.data("target").change()}function M(a,b,c){var d=
b.a,e=a[b.d]-b.start[b.d],e=100*e/b.size;if(1===d.length){if(a=x(d[0],b.c[0]+e),!0!==a){0<=f.inArray(d[0].data("pct"),a)&&L(b.b,!c.margin);return}}else{var k,m;c.step&&(a=c.step,e=Math.round(e/a)*a);a=k=b.c[0]+e;e=m=b.c[1]+e;0>a?(e+=-1*a,a=0):100<e&&(a-=e-100,e=100);if(0>k&&!a&&!d[0].data("pct")||100===e&&100<m&&100===d[1].data("pct"))return;w(d[0],a);w(d[1],e)}r(c.slide,b.target)}function N(a,b,c){1===b.a.length&&b.a[0].data("grab").removeClass(g[4]);a.cursor&&y.css("cursor","").off(h);z.off(h);
b.target.removeClass(g[14]+" "+g[20]).change();r(c.set,b.target)}function B(a,b,c){1===b.a.length&&b.a[0].data("grab").addClass(g[4]);a.stopPropagation();p(q.move,z,M,{start:a,b:b.b,target:b.target,a:b.a,c:[b.a[0].data("pct"),b.a[b.a.length-1].data("pct")],d:c.orientation?"pointY":"pointX",size:c.orientation?b.b.height():b.b.width()});p(q.end,z,N,{target:b.target,a:b.a});a.cursor&&(y.css("cursor",f(a.target).css("cursor")),1<b.a.length&&b.target.addClass(g[20]),y.on("selectstart"+h,function(){return!1}))}
function O(a,b,c){b=b.b;var d,e;a.stopPropagation();c.orientation?(a=a.pointY,e=b.height()):(a=a.pointX,e=b.width());d=b.data("handles");var k=a,m=c.style;1===d.length?d=d[0]:(m=d[0].offset()[m]+d[1].offset()[m],d=d[k<m/2?0:1]);a=100*(a-b.offset()[c.style])/e;A(b,d,a,[c.slide,c.set])}function P(a,b,c){var d=b.b.data("handles"),e;e=c.orientation?a.pointY:a.pointX;a=(e=e<b.b.offset()[c.style])?0:100;e=e?0:d.length-1;A(b.b,d[e],a,[c.slide,c.set])}function Q(a,b){function c(a){if(2!==a.length)return!1;
a=[parseFloat(a[0]),parseFloat(a[1])];return!n(a[0])||!n(a[1])||a[1]<a[0]?!1:a}var d={f:function(a,b){switch(a){case 1:case 0.1:case 0.01:case 0.001:case 1E-4:case 1E-5:a=a.toString().split(".");b.decimals="1"===a[0]?0:a[1].length;break;case void 0:b.decimals=2;break;default:return!1}return!0},e:function(a,b,c){if(!a)return b[c].mark=".",!0;switch(a){case ".":case ",":return!0;default:return!1}},g:function(a,b,c){function d(a){return t(a)||"string"===typeof a||"function"===typeof a||!1===a||t(a[0])&&
"function"===typeof a[0][a[1]]}function g(a){var b=[[],[]];d(a)?b[0].push(a):f.each(a,function(a,e){1<a||(d(e)?b[a].push(e):b[a]=b[a].concat(e))});return b}if(a){var l,h;a=g(a);b.direction&&a[1].length&&a.reverse();for(l=0;l<b.handles;l++)for(h=0;h<a[l].length;h++){if(!d(a[l][h]))return!1;a[l][h]||a[l].splice(h,1)}b[c].to=a}else b[c].to=[[],[]];return!0}};f.each({handles:{r:!0,t:function(a){a=parseInt(a,10);return 1===a||2===a}},range:{r:!0,t:function(a,b,d){b[d]=c(a);return b[d]&&b[d][0]!==b[d][1]}},
start:{r:!0,t:function(a,b,d){if(1===b.handles)return f.isArray(a)&&(a=a[0]),a=parseFloat(a),b.start=[a],n(a);b[d]=c(a);return!!b[d]}},connect:{r:!0,t:function(a,b,c){if("lower"===a)b[c]=1;else if("upper"===a)b[c]=2;else if(!0===a)b[c]=3;else if(!1===a)b[c]=0;else return!1;return!0}},orientation:{t:function(a,b,c){switch(a){case "horizontal":b[c]=0;break;case "vertical":b[c]=1;break;default:return!1}return!0}},margin:{r:!0,t:function(a,b,c){a=parseFloat(a);b[c]=s(b.range,a);return n(a)}},direction:{r:!0,
t:function(a,b,c){switch(a){case "ltr":b[c]=0;break;case "rtl":b[c]=1;b.connect=[0,2,1,3][b.connect];break;default:return!1}return!0}},behaviour:{r:!0,t:function(a,b,c){b[c]={tap:a!==(a=a.replace("tap","")),extend:a!==(a=a.replace("extend","")),drag:a!==(a=a.replace("drag","")),fixed:a!==(a=a.replace("fixed",""))};return!a.replace("none","").replace(/\-/g,"")}},serialization:{r:!0,t:function(a,b,c){return d.g(a.to,b,c)&&d.f(a.resolution,b)&&d.e(a.mark,b,c)}},slide:{t:function(a){return f.isFunction(a)}},
set:{t:function(a){return f.isFunction(a)}},block:{t:function(a){return f.isFunction(a)}},step:{t:function(a,b,c){a=parseFloat(a);b[c]=s(b.range,a);return n(a)}}},function(c,d){var f=a[c],g=void 0!==f;if(d.r&&!g||g&&!d.t(f,a,c))throw console&&console.log&&console.group&&(console.group("Invalid noUiSlider initialisation:"),console.log("Option:\t",c),console.log("Value:\t",f),console.log("Slider(s):\t",b),console.groupEnd()),new RangeError("noUiSlider");})}function R(a){this.data("options",f.extend(!0,
{},a));a=f.extend({handles:2,margin:0,connect:!1,direction:"ltr",behaviour:"tap",orientation:"horizontal"},a);a.serialization=a.serialization||{};Q(a,this);a.style=a.orientation?"top":"left";return this.each(function(){var b=f(this),c,d=[],e,k=f("<div/>").appendTo(b);if(b.data("base"))throw Error("Slider was already initialized.");b.data("base",k).addClass([g[6],g[16+a.direction],g[10+a.orientation]].join(" "));for(c=0;c<a.handles;c++)e=f("<div><div/></div>").appendTo(k),e.addClass(g[1]),e.children().addClass([g[2],
g[2]+g[7+a.direction+(a.direction?-1*c:c)]].join(" ")),e.data({base:k,target:b,options:a,grab:e.children(),pct:-1}).attr("data-style",a.style),e.data({store:K(e,c,a.serialization)}),d.push(e);switch(a.connect){case 1:b.addClass(g[9]);d[0].addClass(g[12]);break;case 3:d[1].addClass(g[12]);case 2:d[0].addClass(g[9]);case 0:b.addClass(g[12])}k.addClass(g[0]).data({target:b,options:a,handles:d});b.val(a.start);if(!a.behaviour.fixed)for(c=0;c<d.length;c++)p(q.start,d[c].children(),B,{b:k,target:b,a:[d[c]]});
a.behaviour.tap&&p(q.start,k,O,{b:k,target:b});a.behaviour.extend&&(b.addClass(g[19]),a.behaviour.tap&&p(q.start,b,P,{b:k,target:b}));a.behaviour.drag&&(c=k.find("."+g[9]).addClass(g[18]),a.behaviour.fixed&&(c=c.add(k.children().not(c).data("grab"))),p(q.start,c,B,{b:k,target:b,a:d}))})}function S(){var a=f(this).data("base"),b=[];f.each(a.data("handles"),function(){b.push(f(this).data("store").val())});return 1===b.length?b[0]:a.data("options").direction?b.reverse():b}function T(a,b){f.isArray(a)||
(a=[a]);return this.each(function(){var c=f(this).data("base"),d,e=Array.prototype.slice.call(c.data("handles"),0),g=c.data("options");1<e.length&&(e[2]=e[0]);g.direction&&a.reverse();for(c=0;c<e.length;c++)if(d=a[c%2],null!==d&&void 0!==d){"string"===f.type(d)&&(d=d.replace(",","."));var h=g.range;d=parseFloat(d);d=s(h,0>h[0]?d+Math.abs(h[0]):d-h[0]);g.direction&&(d=100-d);!0!==x(e[c],d)&&e[c].data("store").val(!0);!0===b&&r(g.set,f(this))}})}function U(a){var b=[[a,""]];f.each(a.data("base").data("handles"),
function(){b=b.concat(f(this).data("store").elements)});f.each(b,function(){1<this.length&&this[0].off(h)});a.removeClass(g.join(" "));a.empty().removeData("base options")}function V(a){return this.each(function(){var b=f(this).val()||!1,c=f(this).data("options"),d=f.extend({},c,a);!1!==b&&U(f(this));a&&(f(this).noUiSlider(d),!1!==b&&d.start===c.start&&f(this).val(b))})}var z=f(document),y=f("body"),h=".nui",W=f.fn.val,g="noUi-base noUi-origin noUi-handle noUi-input noUi-active noUi-state-tap noUi-target -lower -upper noUi-connect noUi-horizontal noUi-vertical noUi-background noUi-stacking noUi-block noUi-state-blocked noUi-ltr noUi-rtl noUi-dragable noUi-extended noUi-state-drag".split(" "),
q=window.navigator.pointerEnabled?{start:"pointerdown",move:"pointermove",end:"pointerup"}:window.navigator.msPointerEnabled?{start:"MSPointerDown",move:"MSPointerMove",end:"MSPointerUp"}:{start:"mousedown touchstart",move:"mousemove touchmove",end:"mouseup touchend"};f.fn.val=function(){return this.hasClass(g[6])?arguments.length?T.apply(this,arguments):S.apply(this):W.apply(this,arguments)};return(D?V:R).call(this,C)}})(window.jQuery||window.Zepto);

},{"./includes/fields":2,"./includes/pagination":3,"./includes/plugin":4,"./includes/state":6}],2:[function(require,module,exports){

var fields = {
	
	functions: {}
	
};

module.exports = fields;
},{}],3:[function(require,module,exports){

//var state = require('./includes/state');

var pagination = {
	
	setupLegacy: function(){
		
		
	},
	
	setupLegacy: function(){
		
		/*if(typeof(self.ajax_links_selector)!="undefined")
		{
			var $ajax_links_object = jQuery(self.ajax_links_selector);
			
			if($ajax_links_object.length>0)
			{
				$ajax_links_object.on('click', function(e) {
					
					e.preventDefault();
					
					var link = jQuery(this).attr('href');
					self.ajax_action = "pagination";
					
					self.fetchLegacyAjaxResults(link);
					return false;
				});
			}
		}*/
	}
};

module.exports = pagination;
},{}],4:[function(require,module,exports){

var $ = (window.jQuery);
var state = require('./state');
var process_form = require('./process_form');

module.exports = function(options)
{
	var defaults = {
		startOpened: false,
		action: ""
	};
	
	var opts = jQuery.extend(defaults, options);
	
	//loop through each item matched
	this.each(function()
	{
		
		var $this = $(this);
		var self = this;
		this.sfid = $this.attr("data-sf-form-id");
		
		state.addSearchForm(this.sfid, this);
		
		this.$fields = $this.find("> ul > li"); //a reference to each fields parent LI
		
		process_form.init();
		process_form.enableInputs(self);
		
		this.extra_query_params = {};
		
		this.template_is_loaded = $this.attr("data-template-loaded");
		this.is_ajax = $this.attr("data-ajax");
		
		this.$ajax_results_container = jQuery($this.attr("data-ajax-target"));
		this.results_url = $this.attr("data-results-url");
		this.debug_mode = $this.attr("data-debug-mode");
		this.update_ajax_url = $this.attr("data-update-ajax-url");
		this.auto_count_refresh_mode = $this.attr("data-auto-count-refresh-mode");
		this.only_results_ajax = $this.attr("data-only-results-ajax"); //if we are not on the results page, redirect rather than try to load via ajax
		this.scroll_to_pos = $this.attr("data-scroll-to-pos");
		this.custom_scroll_to = $this.attr("data-custom-scroll-to");
		this.scroll_on_action = $this.attr("data-scroll-on-action");
		this.lang_code = $this.attr("data-lang-code");
		this.ajax_url = $this.attr('data-ajax-url');
		this.ajax_form_url = $this.attr('data-ajax-form-url');
		this.is_rtl = $this.attr('data-is-rtl');
		this.ajax_action = "";
		this.last_submit_query_params = "";
		
		this.ajax_target_attr = $this.attr("data-ajax-target");
		this.use_history_api = $this.attr("data-use-history-api");
		
		this.last_ajax_request = null;
		
		if(typeof(this.use_history_api)=="undefined")
		{
			this.use_history_api = "";
		}

		if(typeof(this.ajax_target_attr)=="undefined")
		{
			this.ajax_target_attr = "";
		}
		
		if(typeof(this.ajax_url)=="undefined")
		{
			this.ajax_url = "";
		}
		
		if(typeof(this.ajax_form_url)=="undefined")
		{
			this.ajax_form_url = "";
		}
		
		if(typeof(this.results_url)=="undefined")
		{
			this.results_url = "";
		}
		
		if(typeof(this.scroll_to_pos)=="undefined")
		{
			this.scroll_to_pos = "";
		}
		
		if(typeof(this.scroll_on_action)=="undefined")
		{
			this.scroll_on_action = "";
		}
		if(typeof(this.custom_scroll_to)=="undefined")
		{
			this.custom_scroll_to = "";
		}
		this.$custom_scroll_to = jQuery(this.custom_scroll_to);
		
		if(typeof(this.update_ajax_url)=="undefined")
		{
			this.update_ajax_url = "";
		}
		
		if(typeof(this.debug_mode)=="undefined")
		{
			this.debug_mode = "";
		}
		
		if(typeof(this.ajax_target_object)=="undefined")
		{
			this.ajax_target_object = "";
		}
		
		if(typeof(this.template_is_loaded)=="undefined")
		{
			this.template_is_loaded = "0";
		}
		
		if(typeof(this.auto_count_refresh_mode)=="undefined")
		{
			this.auto_count_refresh_mode = "0";
		}
		
		this.ajax_links_selector = $this.attr("data-ajax-links-selector");
		
		
		this.auto_update = $this.attr("data-auto-update");
		this.inputTimer = 0;
		
		
		/* functions */
		
		this.reset = function()
		{
			this.resetForm();
			return true;
		}
		
		this.inputUpdate = function(delayDuration)
		{
			if(typeof(delayDuration)=="undefined")
			{
				var delayDuration = 300;
			}
			
			self.resetTimer(delayDuration);
		}
		
		this.dateInputType = function()
		{
			var $thise = $(this);
			
			if((self.auto_update==1)||(self.auto_count_refresh_mode==1))
			{
				var $tf_date_pickers = $this.find(".sf-datepicker");
				var no_date_pickers = $tf_date_pickers.length;
				
				if(no_date_pickers>1)
				{					
					//then it is a date range, so make sure both fields are filled before updating
					var dp_counter = 0;
					var dp_empty_field_count = 0;
					$tf_date_pickers.each(function(){
					
						if($(this).val()=="")
						{
							dp_empty_field_count++;
						}
						
						dp_counter++;
					});
					
					if(dp_empty_field_count==0)
					{
						self.inputUpdate(1200);
					}
				}
				else
				{
					self.inputUpdate(1200);
				}
			}
		}
		
		this.scrollToPos = function()
		{
			var offset = 0;
			var canScroll = true;
			
			if(self.is_ajax==1)
			{
				if(self.scroll_to_pos=="window")
				{
					offset = 0;
					
				}
				else if(self.scroll_to_pos=="form")
				{
					offset = $this.offset().top;
				}
				else if(self.scroll_to_pos=="results")
				{
					if(self.$ajax_results_container.length>0)
					{
						offset = self.$ajax_results_container.offset().top;
					}
				}
				else if(self.scroll_to_pos=="custom")
				{
					//custom_scroll_to
					if(self.$custom_scroll_to.length>0)
					{
						offset = self.$custom_scroll_to.offset().top;
					}
				}
				else
				{
					canScroll = false;
				}
				
				if(canScroll)
				{
					$("html, body").animate({
						  scrollTop: offset
					}, "normal", "easeOutQuad" );					
				}
			}
			
		}
		
		this.initAutoUpdateEvents = function(){
			
			/* auto update */
			if((self.auto_update==1)||(self.auto_count_refresh_mode==1))
			{
				$this.on('change', 'input[type=radio], input[type=checkbox], select', function(e)
				{
					self.inputUpdate(200);
				});
				$this.on('change', '.meta-slider', function(e)
				{
					self.inputUpdate(200);
				});
				$this.on('input', 'input[type=number]', function(e)
				{
					self.inputUpdate(800);
				});
				
				
				var $textInput = $this.find('input[type=text]:not(.sf-datepicker)');
				var lastValue = $textInput.val();
				
				$this.on('input', 'input[type=text]:not(.sf-datepicker)', function()
				{
					if(lastValue!=$textInput.val())
					{
						self.inputUpdate(1200);
					}
					
					lastValue = $textInput.val();
				});
				
				$this.on('input', 'input.sf-datepicker', self.dateInputType);
				
			}
		};
		
		//this.initAutoUpdateEvents();
		
		
		this.resetTimer = function(delayDuration)
		{
			clearTimeout(self.inputTimer);
			self.inputTimer = setTimeout(self.formUpdated, delayDuration);
			
		};
		
		this.addDatePickers = function()
		{
			var $date_picker = $this.find(".sf-datepicker");
			
			if($date_picker.length>0)
			{
				$date_picker.each(function(){
				
					var $this = $(this);
					var dateFormat = "";
					var dateDropdownYear = false;
					var dateDropdownMonth = false;
					
					var $closest_date_wrap = $this.closest(".sf_date_field");
					if($closest_date_wrap.length>0)
					{
						dateFormat = $closest_date_wrap.attr("data-date-format");
						
						if($closest_date_wrap.attr("data-date-use-year-dropdown")==1)
						{
							dateDropdownYear = true;
						}
						if($closest_date_wrap.attr("data-date-use-month-dropdown")==1)
						{
							dateDropdownMonth = true;
						}
					}
					
					var datePickerOptions = {
						inline: true,
						showOtherMonths: true,
						onSelect: self.dateSelect,
						dateFormat: dateFormat,
						
						changeMonth: dateDropdownMonth,
						changeYear: dateDropdownYear
					};
					
					if(self.is_rtl==1)
					{
						datePickerOptions.direction = "rtl";
					}
					
					$this.datepicker(datePickerOptions);
					
					if(self.lang_code!="")
					{
						$.datepicker.setDefaults(
						  $.extend(
							{'dateFormat':dateFormat},
							$.datepicker.regional[ self.lang_code]
						  )
						);
						
					}
					else
					{
						$.datepicker.setDefaults(
						  $.extend(
							{'dateFormat':dateFormat},
							$.datepicker.regional["en"]
						  )
						);
					
					}
					
				});
				
				if($('.ll-skin-melon').length==0)
				{
					$date_picker.datepicker('widget').wrap('<div class="ll-skin-melon searchandfilter-date-picker"/>');
				}
				
			}
		};
		
		this.dateSelect = function()
		{
			var $thise = $(this);
			
			if((self.auto_update==1)||(self.auto_count_refresh_mode==1))
			{
				var $tf_date_pickers = $this.find(".sf-datepicker");
				var no_date_pickers = $tf_date_pickers.length;
				
				if(no_date_pickers>1)
				{					
					//then it is a date range, so make sure both fields are filled before updating
					var dp_counter = 0;
					var dp_empty_field_count = 0;
					$tf_date_pickers.each(function(){
					
						if($(this).val()=="")
						{
							dp_empty_field_count++;
						}
						
						dp_counter++;
					});
					
					if(dp_empty_field_count==0)
					{
						self.inputUpdate(1);
					}
				}
				else
				{
					self.inputUpdate(1);
				}
			}
		};
		
		this.addRangeSliders = function()
		{
			var $meta_range = $this.find(".meta-range");
			
			if($meta_range.length>0)
			{		
				$meta_range.each(function(){
					
					var $this = $(this);
					var min = $this.attr("data-min");
					var max = $this.attr("data-max");
					var smin = $this.attr("data-start-min");
					var smax = $this.attr("data-start-max");
					var step = $this.attr("data-step");
					var $start_val = $this.find('.range-min');
					var $end_val = $this.find('.range-max');
					
					var noUIOptions = {
						range: [min,max],
						start: [smin,smax],
						handles: 2,
						connect: true,
						step: step,
						serialization: {
							 to: [ $start_val, $end_val],
							 resolution: 1
						},
						behaviour: 'extend-tap'
					};
					
					if(self.is_rtl==1)
					{
						noUIOptions.direction = "rtl";
					}
					
					$(this).find(".meta-slider").noUiSlider(noUIOptions);
					
				});
			}
		};
		
		this.init = function(keep_pagination)
		{
			
			if(typeof(keep_pagination)=="undefined")
			{
				var keep_pagination = false;
			}
			
			this.initAutoUpdateEvents();
			
			this.addDatePickers();
			this.addRangeSliders();
			
			//init combo boxes
			var $chosen = $this.find("select[data-combobox=1]");
			
			if($chosen.length>0)
			{
				if (typeof $chosen.chosen != "undefined")
				{
					// safe to use the function
					//search_contains
					if(self.is_rtl==1)
					{
						$chosen.addClass("chosen-rtl");
					}
					
					$chosen.chosen({
						
						search_contains: true
						
					});
				}
			}
			
			
			
			//if ajax is enabled init the pagination
			if(self.is_ajax==1)
			{
				self.setupAjaxPagination();
			}
			
			$this.submit(this.submitForm);
			
			self.initWooCommerceControls(); //woocommerce orderby
			
			if(keep_pagination==false)
			{
				self.last_submit_query_params = self.getUrlParams(false);
			}
			
		}
		
		
		/*if(this.debug_mode=="1")
		{//error logging
			
			if(self.is_ajax==1)
			{
				if(self.display_results_as=="shortcode")
				{
					if(self.$ajax_results_container.length==0)
					{
						console.log("Search & Filter | Form ID: "+self.sfid+": cannot find the results container on this page - ensure you use the shortcode on this page or provide a URL where it can be found (Results URL)");
					}
					if(self.results_url=="")
					{
						console.log("Search & Filter | Form ID: "+self.sfid+": No Results URL has been defined - ensure that you enter this in order to use the Search Form on any page)");
					}
					//check if results URL is on same domain for potential cross domain errors
				}
				else
				{
					if(self.$ajax_results_container.length==0)
					{
						console.log("Search & Filter | Form ID: "+self.sfid+": cannot find the results container on this page - ensure you use are using the right content selector");
					}
				}
			}
			else
			{
				
			}
			
		}*/
		

		this.stripQueryStringAndHashFromPath = function(url) {
			return url.split("?")[0].split("#")[0];
		}
		
		this.gup = function( name, url ) {
			if (!url) url = location.href
			name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
			var regexS = "[\\?&]"+name+"=([^&#]*)";
			var regex = new RegExp( regexS );
			var results = regex.exec( url );
			return results == null ? null : results[1];
		};
		
		
		this.getUrlParams = function(keep_pagination)
		{
			if(typeof(keep_pagination)=="undefined")
			{
				var keep_pagination = true;
			}
			
			var url_params_str = "";
			
			// get all params from fields
			var url_params_array = process_form.getUrlParams(self);
			
			var length = Object.keys(url_params_array).length;
			var count = 0;
			
			if(length>0)
			{
				for (var k in url_params_array) {
					if (url_params_array.hasOwnProperty(k)) {
						
						url_params_str += k+"="+url_params_array[k];
						
						if(count<length-1)
						{
							url_params_str += "&";
						}
						
						count++;
					}
				}
			}
			
			var query_params = "";
			
			//form params as url query string
			var form_params = url_params_str.replaceAll("%2B", "+").replaceAll("%2C", ",")
			
			//get url params from the form itself (what the user has selected)
			query_params = self.joinUrlParam(query_params, form_params);
			
			//add pagination
			if(keep_pagination==true)
			{
				var pageNumber = self.$ajax_results_container.attr("data-paged");
				
				if(typeof(pageNumber)=="undefined")
				{
					pageNumber = 1;
				}
				
				if(pageNumber>1)
				{
					query_params = self.joinUrlParam(query_params, "sf_paged="+pageNumber);
				}
			}
			
			//add sfid
			//query_params = self.joinUrlParam(query_params, "sfid="+self.sfid);
			
			// loop through any extra params (from ext plugins) and add to the url (ie woocommerce `orderby`)
			var extra_query_param = "";
			var length = Object.keys(self.extra_query_params).length;
			var count = 0;
			
			if(length>0)
			{

				for (var k in self.extra_query_params) {
					if (self.extra_query_params.hasOwnProperty(k)) {

						if(self.extra_query_params[k]!="")
						{
							extra_query_param = k+"="+self.extra_query_params[k];
							query_params = self.joinUrlParam(query_params, extra_query_param);
						}
					}
				}
			}
			
			
			return query_params;
		}
		
		this.addUrlParam = function(url, string)
		{
			var add_params = "";
			
			if(url!="")
			{
				if(url.indexOf("?") != -1)
				{
					add_params += "&";
				}
				else
				{
					//url = this.trailingSlashIt(url);
					add_params += "?";
				}
			}
			
			if(string!="")
			{
			
				return url + add_params + string;
			}
			else
			{
				return url;
			}
		};
		
		this.joinUrlParam = function(params, string)
		{
			var add_params = "";
			
			if(params!="")
			{
				add_params += "&";
			}
						
			if(string!="")
			{
			
				return params + add_params + string;
			}
			else
			{
				return params;
			}
		};
		
		this.setAjaxResultsURLs = function(query_params)
		{
			if(typeof(self.ajax_results_conf)=="undefined")
			{
				self.ajax_results_conf = new Array();
			}
			
			self.ajax_results_conf['processing_url'] = "";
			self.ajax_results_conf['results_url'] = "";
			self.ajax_results_conf['data_type'] = "";
			
			if(self.ajax_url!="")
			{//then we want to do a request to the ajax endpoint
				self.ajax_results_conf['results_url'] = self.addUrlParam(self.results_url, query_params);
				
				//add lang code to ajax api request, lang code should already be in there for other requests (ie, supplied in the Results URL)
				
				if(self.lang_code!="")
				{
					//so add it
					query_params = self.joinUrlParam(query_params, "lang="+self.lang_code);
				}
				
				self.ajax_results_conf['processing_url'] = self.addUrlParam(self.ajax_url, query_params);
				
				self.ajax_results_conf['data_type'] = 'json';
				
			}
			else
			{//otherwise we want to pull the results directly from the results page
				self.ajax_results_conf['results_url'] = self.addUrlParam(self.results_url, query_params);
				self.ajax_results_conf['processing_url'] = self.ajax_results_conf['results_url'];
				self.ajax_results_conf['data_type'] = 'html';
			}
		};
		
		this.fetchAjaxResults = function()
		{
			//trigger start event
			var event_data = {
				sfid: self.sfid,
				targetSelector: self.ajax_target_attr
			};
			
			$this.trigger("sf:ajaxstart", [ event_data ]);
			
			$this.addClass("search-filter-disabled");
			process_form.disableInputs(self);
			
			//fade out results
			self.$ajax_results_container.animate({ opacity: 0.5 }, "fast"); //loading
				
			if(self.ajax_action=="pagination")
			{
				query_params = self.last_submit_query_params;
				
				//now add the new pagination
				var pageNumber = self.$ajax_results_container.attr("data-paged");
				
				if(typeof(pageNumber)=="undefined")
				{
					pageNumber = 1;
				}
				
				if(pageNumber>1)
				{
					query_params = self.joinUrlParam(query_params, "sf_paged="+pageNumber);
				}
			}
			else if(self.ajax_action=="submit")
			{
				var query_params = self.getUrlParams();
				self.last_submit_query_params = self.getUrlParams(false); //grab a copy of hte URL params without pagination already added
			}
			
			var ajax_processing_url = "";
			var ajax_results_url = "";
			var data_type = "";
		
			self.setAjaxResultsURLs(query_params);
			ajax_processing_url = self.ajax_results_conf['processing_url'];
			ajax_results_url = self.ajax_results_conf['results_url'];
			data_type = self.ajax_results_conf['data_type'];
			
			
			//abort any previous ajax requests
			if(self.last_ajax_request)
			{
				self.last_ajax_request.abort();
			}
			
			
			self.last_ajax_request = $.get(ajax_processing_url, function(data, status, request)
			{
				self.last_ajax_request = null;
				
				/* scroll */
				self.scrollResults();
				
				//updates the resutls & form html
				self.updateResults(data, data_type);
				
				//setup pagination
				self.setupAjaxPagination();
				
				/* update URL */
				self.updateUrlHistory(ajax_results_url);
				
				
				/* user def */
				self.initWooCommerceControls(); //woocommerce orderby
			
				
			}, data_type).fail(function(jqXHR, textStatus, errorThrown)
			{
				var data = {};
				data.sfid = self.sfid;
				data.targetSelector = self.ajax_target_attr;
				data.ajaxURL = ajax_processing_url;
				data.jqXHR = jqXHR;
				data.textStatus = textStatus;
				data.errorThrown = errorThrown;
				$this.trigger("sf:ajaxerror", [ data ]);
				/*console.log("AJAX FAIL");
				console.log(e);
				console.log(x);*/
				
			}).always(function()
			{
				self.$ajax_results_container.stop(true,true).animate({ opacity: 1}, "fast"); //finished loading
				var data = {};
				data.sfid = self.sfid;
				data.targetSelector = self.ajax_target_attr;						
				$this.trigger("sf:ajaxfinish", [ data ]);
				
				$this.removeClass("search-filter-disabled");
				process_form.enableInputs(self);
			});
		};
		
		this.fetchAjaxForm = function()
		{
			//trigger start event
			/*var event_data = {
				sfid: self.sfid,
				targetSelector: self.ajax_target_attr
			};*/
			
			//$this.trigger("sf:ajaxstart", [ event_data ]);
			
			$this.addClass("search-filter-disabled");
			process_form.disableInputs(self);
			
			var query_params = self.getUrlParams();
			
			if(self.lang_code!="")
			{
				//so add it
				query_params = self.joinUrlParam(query_params, "lang="+self.lang_code);
			}
			
			var ajax_processing_url = self.addUrlParam(self.ajax_form_url, query_params);
			var data_type = "json";
						
			
			//abort any previous ajax requests
			/*if(self.last_ajax_request)
			{
				self.last_ajax_request.abort();
			}*/
			
			
			//self.last_ajax_request = 
			
			$.get(ajax_processing_url, function(data, status, request)
			{
				//self.last_ajax_request = null;
				
				//updates the resutls & form html
				self.updateForm(data, data_type);
				
				
			}, data_type).fail(function(jqXHR, textStatus, errorThrown)
			{
				var data = {};
				data.sfid = self.sfid;
				data.targetSelector = self.ajax_target_attr;		
				data.ajaxURL = ajax_processing_url;		
				data.jqXHR = jqXHR;		
				data.textStatus = textStatus;		
				data.errorThrown = errorThrown;		
				$this.trigger("sf:ajaxerror", [ data ]);
				
			}).always(function()
			{
				var data = {};
				data.sfid = self.sfid;
				data.targetSelector = self.ajax_target_attr;						
				//$this.trigger("sf:ajaxfinish", [ data ]);
				
				$this.removeClass("search-filter-disabled");
				process_form.enableInputs(self);
			});
		};
		
		this.copyListItemsContents = function($list_from, $list_to)
		{
			var li_contents_array = new Array();
			
			$list_from.find("> ul > li").each(function(i){
				
				li_contents_array.push($(this).html());
				
			});
			
			var li_it = 0;
			$list_to.find("> ul > li").each(function(i){
				
				$(this).html(li_contents_array[li_it])
				
				li_it++;
			});			
		}
		
		this.updateForm = function(data, data_type)
		{
			var self = this;
			
			if(data_type=="json")
			{//then we did a request to the ajax endpoint, so expect an object back
				
				if(typeof(data['form'])!=="undefined")
				{
					//remove all events from S&F form
					$this.off();
					
					//refresh the form (auto count)
					self.copyListItemsContents($(data['form']), $this);
					
					//re init S&F class on the form
					//$this.searchAndFilter();
					
					//if ajax is enabled init the pagination
					
					this.init(true);
					
					if(self.is_ajax==1)
					{
						self.setupAjaxPagination();
					}
					
					
					
				}
			}
			
			
		}
		this.updateResults = function(data, data_type)
		{
			var self = this;
			
			if(data_type=="json")
			{//then we did a request to the ajax endpoint, so expect an object back
				//grab the results and load in
				self.$ajax_results_container.html(data['results']);
				
				if(typeof(data['form'])!=="undefined")
				{
					//remove all events from S&F form
					$this.off();
					
					//remove pagination
					self.removeAjaxPagination();
					
					//refresh the form (auto count)
					self.copyListItemsContents($(data['form']), $this);
					
					//re init S&F class on the form
					$this.searchAndFilter();
				}
				else
				{
					//$this.find("input").removeAttr("disabled");
				}
			}
			else if(data_type=="html")
			{//we are expecting the html of the results page back, so extract the html we need
				
				var $data_obj = $(data);
				
				self.$ajax_results_container.html($data_obj.find(self.ajax_target_attr).html());
				
				var $new_search_form = $data_obj.find(".searchandfilter[data-sf-form-id="+self.sfid+"]");
				
				if($new_search_form.length==1)
				{//then replace the search form with the new one
					
					//remove all events from S&F form
					$this.off();
					
					//remove pagination
					self.removeAjaxPagination();
					
					//refresh the form (auto count)
					self.copyListItemsContents($new_search_form, $this);
					
					//re init S&F class on the form
					$this.searchAndFilter();
				}
				else
				{
					//$this.find("input").removeAttr("disabled");
				}
			}
			
		}
		
		this.removeWooCommerceControls = function(){
			var $woo_orderby = $('.woocommerce-ordering .orderby');
			var $woo_orderby_form = $('.woocommerce-ordering');
			
			$woo_orderby_form.off();
			$woo_orderby.off();
		};
		
		this.initWooCommerceControls = function(){
			
			self.removeWooCommerceControls();
			
			var $woo_orderby = $('.woocommerce-ordering .orderby');
			var $woo_orderby_form = $('.woocommerce-ordering');
			
			var order_val = "";
			if($woo_orderby.length>0)
			{
				order_val = $woo_orderby.val();
			}
			else
			{
				order_val = self.getQueryParamFromURL("orderby", window.location.href);
			}
			
			if(order_val=="menu_order")
			{
				order_val = "";				
			}
			
			if((order_val!="")&&(!!order_val))
			{
				self.extra_query_params.orderby = order_val;
			}
			
			
			$woo_orderby_form.on('submit', function(e)
			{
				e.preventDefault();
				//var form = e.target;
				return false;
			});
			
			$woo_orderby.on("change", function(e)
			{
				e.preventDefault();
				
				var val = $(this).val();
				if(val=="menu_order")
				{
					val = "";
				}
				
				self.extra_query_params.orderby = val;
								
				$this.submit();
				
				return false;
			});
			
		}
		
		this.scrollResults = function()
		{
			var self = this;
			
			if((self.scroll_on_action==self.ajax_action)||(self.scroll_on_action=="all"))
			{
				self.scrollToPos(); //scroll the window if it has been set
				//self.ajax_action = "";
			}
		}
		this.updateUrlHistory = function(ajax_results_url)
		{
			var self = this;
			
			var use_history_api = 0;
			if (window.history && window.history.pushState)
			{
				use_history_api = $this.attr("data-use-history-api");
			}
			
			if((self.update_ajax_url==1)&&(use_history_api==1))
			{
				//now check if the browser supports history state push :)
				if (window.history && window.history.pushState)
				{
					history.pushState(null, null, ajax_results_url);
				}
			}
		}
		this.removeAjaxPagination = function()
		{
			var self = this;
			
			if(typeof(self.ajax_links_selector)!="undefined")
			{
				var $ajax_links_object = jQuery(self.ajax_links_selector);
				
				if($ajax_links_object.length>0)
				{
					$ajax_links_object.off();
				}
			}
		}
		
		this.canFetchAjaxResults = function()
		{
			var self = this;
			var fetch_ajax_results = false;
			
			if(self.is_ajax==1)
			{//then we will ajax submit the form
				
				//and if we can find the results container
				if(self.$ajax_results_container.length==1)
				{
					fetch_ajax_results = true;
				}
				
				if(self.only_results_ajax==1)
				{//if a user has chosen to only allow ajax on results pages (default behaviour)
					
					
					var results_url = self.results_url;
					var current_url = window.location.href;
					
					if(current_url.indexOf(results_url) > -1)
					{//this means the current URL contains the results url, which means we can do ajax
						fetch_ajax_results = true;
					}
					else
					{
						fetch_ajax_results = false;
					}
				}
			}
			
			return fetch_ajax_results;
		}
		
		this.setupAjaxPagination = function()
		{
			if(typeof(self.ajax_links_selector)=="undefined")
			{
				return;
			}
			
			var $ajax_links_object = jQuery(self.ajax_links_selector);
			
			if($ajax_links_object.length>0)
			{
				$ajax_links_object.off('click');
				$ajax_links_object.on('click', function(e) {
					
					if(self.canFetchAjaxResults())
					{
						e.preventDefault();
						
						var link = jQuery(this).attr('href');
						self.ajax_action = "pagination";
						
						var pageNumber = self.getPagedFromURL(link);
						
						self.$ajax_results_container.attr("data-paged", pageNumber);
												
						self.fetchAjaxResults();
						
						return false;
					}
				});
			}
		};
		
		this.getPagedFromURL = function(URL){
			
			var pagedVal = 1;
			//first test to see if we have "/page/4/" in the URL
			var tpVal = self.getQueryParamFromURL("sf_paged", URL);
			if((typeof(tpVal)=="string")||(typeof(tpVal)=="number"))
			{
				pagedVal = tpVal;
			}
			
			return pagedVal;
		};
		
		this.getQueryParamFromURL = function(name, URL){
			
			var qstring = "?"+URL.split('?')[1];
			if(typeof(qstring)!="undefined")
			{
				var val = decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(qstring)||[,""])[1].replace(/\+/g, '%20'))||null;
				return val;
			}
			return "";
		};
		
		
		
		this.formUpdated = function(e){
			
			//e.preventDefault();
			
			if(self.auto_update==1)
			{
				self.submitForm();
			}
			else if((self.auto_update==0)&&(self.auto_count_refresh_mode==1))
			{
				self.formUpdatedFetchAjax();
			}
						
			return false;
		};
		
		this.formUpdatedFetchAjax = function(){
			
			//loop through all the fields and build the URL
			self.fetchAjaxForm();
			
			
			return false;
		};
		
		this.submitForm = function(e){
			
			//loop through all the fields and build the URL
			
			self.$ajax_results_container.attr("data-paged", 1); //init paged
			
			if(self.canFetchAjaxResults())
			{//then we will ajax submit the form
				self.ajax_action = "submit"; //so we know it wasn't pagination
				
				
				self.fetchAjaxResults();
			}
			else
			{//then we will simply redirect to the Results URL
				var query_params = self.getUrlParams();
				results_url = self.addUrlParam(self.results_url, query_params);
				
				window.location.href = results_url;
			}
			
			return false;
		};
		
		
		
		this.resetForm = function(e)
		{
			//unset all fields
			
			//unset select
			self.$fields.each(function(){
				
				var $field = $(this);
				$field.find("select:not([multiple='multiple']) > option:first-child").prop("selected", true);
				$field.find("select[multiple='multiple'] > option").prop("selected", false);
				
				$field.find("input[type='checkbox']").prop("checked", false);
				$field.find("li:first-child input[type='radio']").prop("checked", true);
				$field.find("input[type='text']").val("");
				
				
				//number range - 2 number input fields
				$field.find("input[type='number']").each(function(index){
					
					var $thisInput = $(this);
					
					if($thisInput.parent().hasClass("meta-range"))
					{
							if(index==0)
							{
								$thisInput.val($thisInput.attr("min"));
							}
							else if(index==1)
							{
								$thisInput.val($thisInput.attr("max"));
							}
					}
						
				});
								
				//number slider - noUiSlider
				$field.find(".meta-slider").each(function(index){
					
					var $thisSlider = $(this);
					var $sliderEl = $thisSlider.closest(".meta-range");
					var minVal = $sliderEl.attr("data-min");
					var maxVal = $sliderEl.attr("data-max");
					$thisSlider.val([minVal, maxVal]);
					
				});
				
				//need to see if any are combobox and act accordingly
				$field.find("select").trigger("chosen:updated"); //for chosen only
				
			});
			
			self.submitForm();
		};
		
		this.init();
		
		var event_data = {};
		event_data.sfid = self.sfid;
		event_data.targetSelector = self.ajax_target_attr;	
		$this.trigger("sf:init", [ event_data ]);
		
	});
};
},{"./process_form":5,"./state":6}],5:[function(require,module,exports){

var $ = (window.jQuery);

module.exports = {
	
	init: function(){
		
		//this.$fields = $fields;
		
		this.clearUrlComponents();
	},
	
	getUrlParams: function($form){
		
		this.buildUrlComponents($form, true);
		return this.url_params;
			
	},
	clearUrlComponents: function(){
		this.url_components = "";
		this.url_params = {};
	},
	disableInputs: function($form){
		var self = this;
		
		$form.$fields.each(function(){
			
			var $inputs = $(this).find("input, select, .meta-slider");
			$inputs.attr("disabled", "disabled");
			$inputs.attr("disabled", true);
			$inputs.prop("disabled", true);
			$inputs.trigger("chosen:updated");
			
		});
		
		
	},
	enableInputs: function($form){
		var self = this;
		
		$form.$fields.each(function(){
			
			var $inputs = $(this).find("input, select, .meta-slider");
			$inputs.prop("disabled", true);
			$inputs.removeAttr("disabled");
			$inputs.trigger("chosen:updated");			
		});
		
		
	},
	buildUrlComponents: function($form, clear_components){
		
		var self = this;
		
		if(typeof(clear_components)!="undefined")
		{
			if(clear_components==true)
			{
				this.clearUrlComponents();
			}
		}
		
		$form.$fields.each(function(){
			
			var fieldName = $(this).attr("data-sf-field-name");
			var fieldType = $(this).attr("data-sf-field-type");
			
			if(fieldType=="search")
			{
				self.processSearchField($(this));
			}
			else if((fieldType=="tag")||(fieldType=="category")||(fieldType=="taxonomy"))
			{
				self.processTaxonomy($(this));
			}
			else if(fieldType=="sort_order")
			{
				self.processSortOrderField($(this));
			}
			else if(fieldType=="author")
			{
				self.processAuthor($(this));
			}
			else if(fieldType=="post_type")
			{
				self.processPostType($(this));
			}
			else if(fieldType=="post_date")
			{
				self.processPostDate($(this));
			}
			else if(fieldType=="post_meta")
			{
				self.processPostMeta($(this));
				
			}
			else
			{
				//console.log(self.$fields);
			}
			
		});
		
	},
	processSearchField: function($container)
	{
		var self = this;
		
		var $field = $container.find("input[name='_sf_search']");
		
		if($field.length>0)
		{
			var fieldName = $field.attr("name");
			var fieldVal = $field.val();
			
			if(fieldVal!="")
			{
				self.url_components += "&_sf_s="+encodeURIComponent(fieldVal);
				self.url_params['_sf_s'] = encodeURIComponent(fieldVal);
			}
		}
	},
	processSortOrderField: function($container)
	{
		var self = this;

		var $field = $container.find("input[name='_sf_search']");
		
		$field = $container.find("select");
		
		if($field.length>0)
		{
			var fieldName = $field.attr("name").replace('[]', '');
			
			if($field.val()!=0)
			{
				var fieldVal = $field.val();
				
				if(fieldVal!="")
				{
					self.url_components += "&sort_order="+fieldVal;
					self.url_params['sort_order'] = fieldVal;
				}
			}
		}
	},
	getSelectVal: function($field){
		
		var fieldVal = "";
		
		if($field.val()!=0)
		{
			fieldVal = $field.val();
		}
		
		if(fieldVal==null)
		{
			fieldVal = "";
		}
		
		return fieldVal;
	},
	getMetaSelectVal: function($field){
		
		var fieldVal = "";
		
		fieldVal = $field.val();
						
		if(fieldVal==null)
		{
			fieldVal = "";
		}
		
		return fieldVal;
	},
	getMultiSelectVal: function($field, operator){
		
		var delim = "+";
		if(operator=="or")
		{
			delim = ",";
		}
		
		if(typeof($field.val())=="object")
		{
			if($field.val()!=null)
			{
				return $field.val().join(delim);
			}
		}
		
	},
	getMetaMultiSelectVal: function($field, operator){
		
		var delim = "-+-";
		if(operator=="or")
		{
			delim = "-,-";
		}
		
		if(typeof($field.val())=="object")
		{
			if($field.val()!=null)
			{
				return $field.val().join(delim);
			}
		}
		
	},
	getCheckboxVal: function($field, operator){
		
		
		var fieldVal = $field.map(function(){
			if($(this).prop("checked")==true)
			{
				return $(this).val();
			}
		}).get();
		
		var delim = "+";
		if(operator=="or")
		{
			delim = ",";
		}
		
		return fieldVal.join(delim);
	},
	getMetaCheckboxVal: function($field, operator){
		
		
		var fieldVal = $field.map(function(){
			if($(this).prop("checked")==true)
			{
				return $(this).val();
			}
		}).get();
		
		var delim = "-+-";
		if(operator=="or")
		{
			delim = "-,-";
		}
		
		return fieldVal.join(delim);
	},
	getRadioVal: function($field){
							
		var fieldVal = $field.map(function()
		{
			if($(this).prop("checked")==true)
			{
				return $(this).val();
			}
			
		}).get();
		
		
		if(fieldVal[0]!=0)
		{
			return fieldVal[0];
		}
	},
	getMetaRadioVal: function($field){
							
		var fieldVal = $field.map(function()
		{
			if($(this).prop("checked")==true)
			{
				return $(this).val();
			}
			
		}).get();
		
		return fieldVal[0];
	},
	processAuthor: function($container)
	{
		var self = this;
		
		var fieldType = $container.attr("data-sf-field-type");
		var inputType = $container.attr("data-sf-field-input-type");
		
		var $field;
		var fieldName = "";
		var fieldVal = "";
		
		if(inputType=="select")
		{
			$field = $container.find("select");
			fieldName = $field.attr("name").replace('[]', '');
			
			fieldVal = self.getSelectVal($field); 
		}
		else if(inputType=="multiselect")
		{
			$field = $container.find("select");
			fieldName = $field.attr("name").replace('[]', '');
			var operator = $field.attr("data-operator");
			
			fieldVal = self.getMultiSelectVal($field, "or");
			
		}
		else if(inputType=="checkbox")
		{
			$field = $container.find("ul > li input:checkbox");
			
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
										
				var operator = $container.find("> ul").attr("data-operator");
				fieldVal = self.getCheckboxVal($field, "or");
			}
			
		}
		else if(inputType=="radio")
		{
			$field = $container.find("ul > li input:radio");
			
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
				
				fieldVal = self.getRadioVal($field);
			}
		}
		
		if(typeof(fieldVal)!="undefined")
		{
			if(fieldVal!="")
			{
				var fieldSlug = "";
				
				if(fieldName=="_sf_author")
				{
					fieldSlug = "authors";
				}
				else if(fieldName=="_sf_post_type")
				{
					fieldSlug = "post_types";
				}
				else
				{
				
				}
				
				if(fieldSlug!="")
				{
					self.url_components += "&"+fieldSlug+"="+fieldVal;
					self.url_params[fieldSlug] = fieldVal;
				}
			}
		}
		
	},
	processPostType : function($this){
		
		this.processAuthor($this);
		
	},
	processPostMeta: function($container)
	{
		var self = this;
		
		var fieldType = $container.attr("data-sf-field-type");
		var inputType = $container.attr("data-sf-field-input-type");
		var metaType = $container.attr("data-sf-meta-type");
		
		var fieldVal = "";
		var $field;
		var fieldName = "";
		
		
		
		if(metaType=="number")
		{
			if((inputType=="range-slider")||(inputType=="range-number"))
			{
				$field = $container.find(".meta-range input");
				
				var values = [];
				$field.each(function(){
					
					values.push($(this).val());
				
				});
				
				fieldVal = values.join("+");
				
			}
			else if(inputType=="range-radio")
			{
				$field = $container.find("ul > li input:radio");
				
				if($field.length>0)
				{
					fieldVal = self.getRadioVal($field);
				}
			}
			else if(inputType=="range-select")
			{
				$field = $container.find("select");
				
				if($field.length>0)
				{
					fieldVal = self.getSelectVal($field);
				}
			}
			else if(inputType=="range-checkbox")
			{
				$field = $container.find("ul > li input:checkbox");
				
				if($field.length>0)
				{
					fieldVal = self.getCheckboxVal($field, "and");
				}
			}
			
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
			}
		}
		else if(metaType=="choice")
		{
			if(inputType=="select")
			{
				$field = $container.find("select");
				
				fieldVal = self.getMetaSelectVal($field); 
			}
			else if(inputType=="multiselect")
			{
				$field = $container.find("select");
				var operator = $field.attr("data-operator");
				
				fieldVal = self.getMetaMultiSelectVal($field, operator);
			}
			else if(inputType=="checkbox")
			{
				$field = $container.find("ul > li input:checkbox");
				
				if($field.length>0)
				{
					var operator = $container.find("> ul").attr("data-operator");
					fieldVal = self.getMetaCheckboxVal($field, operator);
				}
			}
			else if(inputType=="radio")
			{
				$field = $container.find("ul > li input:radio");
				
				if($field.length>0)
				{
					fieldVal = self.getMetaRadioVal($field);
				}
			}
			
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
				
				//for those who insist on using & ampersands in the name of the custom field (!)
				fieldName = (fieldName);
			}
			
		}
		else if(metaType=="date")
		{
			self.processPostDate($container);
		}
		
		if(typeof(fieldVal)!="undefined")
		{
			if(fieldVal!="")
			{
				self.url_components += "&"+encodeURIComponent(fieldName)+"="+fieldVal;
				self.url_params[fieldName] = fieldVal;
			}
		}
	},
	processPostDate: function($container)
	{
		var self = this;
		
		var fieldType = $container.attr("data-sf-field-type");
		var inputType = $container.attr("data-sf-field-input-type");
		
		var $field;
		var fieldName = "";
		var fieldVal = "";
		
		$field = $container.find("ul > li input:text");
		fieldName = $field.attr("name").replace('[]', '');
		
		var dates = [];
		$field.each(function(){
			
			dates.push($(this).val());
		
		});
		
		if($field.length==2)
		{
			if((dates[0]!="")||(dates[1]!=""))
			{
				fieldVal = dates.join("+");
				fieldVal = fieldVal.replace(/\//g,'');
			}
		}
		else if($field.length==1)
		{
			if(dates[0]!="")
			{
				fieldVal = dates.join("+");
				fieldVal = fieldVal.replace(/\//g,'');
			}
		}
		
		if(typeof(fieldVal)!="undefined")
		{
			if(fieldVal!="")
			{
				var fieldSlug = "";
				
				if(fieldName=="_sf_post_date")
				{
					fieldSlug = "post_date";
				}
				else
				{
					fieldSlug = fieldName;
				}
				
				if(fieldSlug!="")
				{
					self.url_components += "&"+fieldSlug+"="+fieldVal;
					self.url_params[fieldSlug] = fieldVal;
				}
			}
		}
		
	},
	processTaxonomy: function($container)
	{
		//if()					
		//var fieldName = $(this).attr("data-sf-field-name");
		var self = this;
	
		var fieldType = $container.attr("data-sf-field-type");
		var inputType = $container.attr("data-sf-field-input-type");
		
		var $field;
		var fieldName = "";
		var fieldVal = "";
		
		if(inputType=="select")
		{
			$field = $container.find("select");
			fieldName = $field.attr("name").replace('[]', '');
			
			fieldVal = self.getSelectVal($field); 
		}
		else if(inputType=="multiselect")
		{
			$field = $container.find("select");
			fieldName = $field.attr("name").replace('[]', '');
			var operator = $field.attr("data-operator");
			
			fieldVal = self.getMultiSelectVal($field, operator);
		}
		else if(inputType=="checkbox")
		{
			$field = $container.find("ul > li input:checkbox");
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
										
				var operator = $container.find("> ul").attr("data-operator");
				fieldVal = self.getCheckboxVal($field, operator);
			}
		}
		else if(inputType=="radio")
		{
			$field = $container.find("ul > li input:radio");
			if($field.length>0)
			{
				fieldName = $field.attr("name").replace('[]', '');
				
				fieldVal = self.getRadioVal($field);
			}
		}
		
		if(typeof(fieldVal)!="undefined")
		{
			if(fieldVal!="")
			{
				self.url_components += "&"+fieldName+"="+fieldVal;
				self.url_params[fieldName] = fieldVal;
			}
		}
	}
};
},{}],6:[function(require,module,exports){

module.exports = {
	
	searchForms: {},
	
	init: function(){
		
		
	},
	addSearchForm: function(id, object){
		
		this.searchForms[id] = object;
	},
	getSearchForm: function(id)
	{
		return this.searchForms[id];	
	}
	
};
},{}]},{},[1]);

/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.core.js";var __meta__={id:"bottomnavigation",name:"BottomNavigation",category:"web",description:"The BottomNavigation widget is a navigation element that allows movement between primary destinations in an app.",depends:["core"]};!function(e,t){var i=window.kendo,n=i.ui,s=n.Widget,a=e.extend,o=i.template,l=i.keys,d=e.isPlainObject,r=e.isEmptyObject,m=".kendoBottomNavigation",c="k-bottom-nav-",f=".",u="select",p=function(e){return"string"==typeof e},v={widget:"k-bottom-nav",item:"k-bottom-nav-item",navIcon:"k-bottom-nav-item-icon",icon:"k-icon",text:"k-bottom-nav-item-text",itemFlow:{vertical:"k-bottom-nav-item-flow-vertical",horizontal:"k-bottom-nav-item-flow-horizontal"},selected:"k-selected",disabled:"k-disabled",border:"k-bottom-nav-border",shadow:"k-bottom-nav-shadow",focus:"k-focus"},h={item:o("<span class='"+v.item+"'></span>"),anchor:o("<a class='"+v.item+"' href='#:url#'></a>"),text:o("<span class='"+v.text+"'>#=text#</span>"),icon:o("<span class='"+v.navIcon+"#if(icon){# k-icon k-i-#:icon# #}#'></span>")},g=s.extend({init:function(t,i){var n=this;i=i||{},s.fn.init.call(n,t,i),n.element=e(t),n._updateCssClasses(),n._items(),n._bindEvents()},options:{name:"BottomNavigation",positionMode:"fixed",items:[],themeColor:"primary",itemFlow:"vertical",fill:"flat",shadow:!1,border:!0,template:null},events:[u],destroy:function(){this.element.off(m),s.fn.destroy.call(this)},_tabindex:function(e){var t=this.element,n="tabindex",s=t.attr("data-"+i.ns+n),a=e.attr(n)||t.attr(n)||s;s||(t.removeAttr(n),t.attr("data-"+i.ns+n,a)),e.attr(n,isNaN(a)?0:a)},_updateCssClasses:function(){var e=this,t=e.options,n=v;e.element.removeClass((function(t,i){0===i.indexOf("k-")&&e.element.removeClass(i)})),e.element.addClass(n.widget),e.element.addClass(i.getValidCssClass(c,"themeColor",t.themeColor)),e.element.addClass(i.getValidCssClass(c,"fill",t.fill)),e.element.addClass(i.getValidCssClass("k-pos-","positionMode",t.positionMode)),e.element.toggleClass(n.border,t.border),e.element.toggleClass(n.shadow,t.shadow),e._itemFlow(t.itemFlow)},_itemFlow:function(e){var t=v.itemFlow;this._toggleClassGroup(this.element,e,t)},_toggleClassGroup:function(e,t,i){if(p(i[t])){for(var n in i)e.removeClass(i[n]);e.addClass(i[t])}},_items:function(){for(var e,t=this,i=t.options.items,n=0;n<i.length;n++)e=t._renderItem(i[n]),t.element.append(e)},_renderItem:function(t){var n,s,l=t.template||this.options.template,d=t.url&&p(t.url);return(n=e(d?o(h.anchor)(t):o(h.item)(t))).toggleClass(v.selected,!0===t.selected).toggleClass(v.disabled,!1===t.enabled).addClass(t.cssClass).attr(a({},t.attributes,{"aria-disabled":!1===t.enabled})).data(t.data),this._tabindex(n),n.attr("role")||d||n.attr("role","link"),l?(n.append(o(l)(t)),n):(t=a({},{icon:""},t),s=e(h.icon(t)).addClass(t.iconClass),n.append(s),t.text&&(t.text=!1===t.encoded?t.text:i.htmlEncode(t.text),n.append(e(h.text(t)))),n)},_bindEvents:function(){var e=this,t=e._click.bind(e),i=e._keydown.bind(e);e.element.on("click"+m,f+v.item,t).on("keydown"+m,f+v.item,i)},_click:function(t){var i=e(t.target).closest(f+v.item);i.is(f+v.disabled)?t.preventDefault():this._triggerSelect(i,t)},_triggerSelect:function(e,t){e.is(f+v.disabled)||this.trigger(u,{originalEvent:t,item:e,data:e.data()})||this.select(e)},_keydown:function(t){var i=e(t.target),n=t.keyCode;n!==l.ENTER&&n!==l.SPACEBAR||this._isItem(i)&&(this._triggerSelect(i,t),n===l.SPACEBAR&&t.preventDefault())},_isItem:function(t){return(t=e(t)).is(f+v.item)&&!!this.element.find(t).length},items:function(){return this.element.children()},select:function(t,i){var n=this.items().filter(f+v.selected);if(!t)return n;i=!1!==i,this._isItem(t)&&(n.removeClass(v.selected),e(t).toggleClass(v.selected,i))},enable:function(t,i){i=!1===i,t&&this._isItem(t)&&(e(t).toggleClass(v.disabled,i),e(t).attr("aria-disabled",i))},item:function(e){return isNaN(e)?null:this.items().eq(e)},itemById:function(e){return this.element.find("#"+e)},add:function(t,i){var n=this,s="append",a=n.element;i&&n._isItem(i)&&(s="before",a=e(i)),t&&d(t)&&!r(t)&&a[s](n._renderItem(t))},remove:function(e){e&&this._isItem(e)&&e.remove()},showText:function(e){e=!1!==e,this.items().find(f+v.text).toggle(e)},setOptions:function(e){var t=this;s.fn.setOptions.call(this,e),t._updateCssClasses(),("items"in e||"template"in e)&&(t.element.empty(),t._items())}});n.plugin(g)}(window.kendo.jQuery);
//# sourceMappingURL=kendo.bottomnavigation.js.map

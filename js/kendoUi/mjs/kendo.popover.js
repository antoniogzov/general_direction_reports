/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.tooltip.js";var __meta__={id:"popover",name:"Popover",category:"web",description:"The Popover widget displays a popup with additional information for an element.",depends:["tooltip"],features:[{id:"popover-fx",name:"Animation",description:"Support for animation",depends:["fx"]}]};!function(t,o){var e=window.kendo,n=e.ui.Popup,i=e.ui.TooltipBase,s=t.extend,a=t(document),r=".kendoPopover",p="show",c="hide",l={top:"bottom",bottom:"top",left:"right",right:"left",center:"center"},d={bottom:{origin:"bottom center",position:"top center"},top:{origin:"top center",position:"bottom center"},left:{origin:"center left",position:"center right",collision:"fit flip"},right:{origin:"center right",position:"center left",collision:"fit flip"},center:{position:"center center",origin:"center center"}},u={bottom:"n",top:"s",left:"e",right:"w",center:"n"},f={mouseenter:"mouseleave",focus:"blur",focusin:"focusout"},h={horizontal:{offset:"top",size:"outerHeight"},vertical:{offset:"left",size:"outerWidth"}},m=i.extend({init:function(o,n){var s,a=this;i.fn.init.call(a,o,n),s=a.options.position.match(/left|right/)?"horizontal":"vertical",a.dimensions=h[s],a._saveTitle=t.noop,a._documentKeyDownHandler=a._documentKeyDown.bind(a),a._actionsHandler=a._actionsClick.bind(a),a.options.toggleOnClick&&a._isShownOnClick()&&a.element.on((e.support.touch?e.support.mousedown:a.options.showOn)+r,a.options.filter,a._showAction.bind(a)),a._isShownOnClick()||a.element.on(f[a.options.showOn],a.options.filter,a._dismissAction.bind(a))},options:{name:"Popover",filter:"",actions:[],actionsLayout:"center",position:"bottom",showOn:"mouseenter",toggleOnClick:!1,width:null,height:null,animation:{open:{effects:"fade:in",duration:0},close:{duration:40,hide:!0}}},events:[p,c],_addAria:function(){var t,o=this,n=o.options;o._isShownOnClick()&&o.wrapper.find("a,input,select,textarea,button").length?(o.wrapper.attr("role","dialog"),o._isDialog=!0,n.header&&(t=e.guid(),o.wrapper.attr("aria-labelledby",t).find(".k-popover-header").attr("id",t)),n.body&&(t=e.guid(),o.wrapper.attr("aria-describedby",t).find(".k-popover-body").attr("id",t))):o.wrapper.attr("role","tooltip")},_appendContent:function(t){var o=this,n=o.options,i=o.wrapper.find(".k-popover-inner"),s=o.options.template;i.length?i.children().remove():o.wrapper.children(":not(.k-popover-callout)").remove(),i=i.length?i:o.wrapper,s?i.append(e.template(s)({target:t})):i.append(e.template('#if (header) {# <div class="k-popover-header">#=header#</div> #}#<div class="k-popover-body">#=body#</div>#if (actions){ #<div class="k-popover-actions k-actions k-hstack k-justify-content-#=positioning#">#=actions#</div>#}#')({header:e.template(n.header||"")({target:t}),body:e.template(n.body||"")({target:t}),actions:o._buildActions(n.actions),positioning:n.actionsLayout}))},_actionsClick:function(o){var n=this,i=n.options.actions,s=t(o.currentTarget),a=i[parseInt(s.attr(e.attr("index")),10)];a.click&&a.click.call(n,{sender:n,target:s})},_attachActions:function(){this.wrapper.on("click"+r,".k-popover-actions .k-button",this._actionsHandler)},_dettachActions:function(){var t=this;t.wrapper&&t.wrapper.off("click"+r,t._actionsHandler)},_buildActions:function(t){if(t.length){for(var o,n="",i=0;i<t.length;i++)(o=t[i]).text&&o.iconClass?n+=e.template('<button #=index# class="k-button k-button-md k-rounded-md k-button-solid k-button-solid-base"><span class="k-button-icon #=iconClass#"></span><span class="k-button-text">#=text#</span></button>')({text:o.text,index:e.attr("index")+"="+i,iconClass:o.iconClass}):o.iconClass&&!o.text?n+=e.template('<button #=index# class="k-button k-button-md k-rounded-md k-button-solid k-button-solid-base k-icon-button"><span class="k-button-icon #=iconClass#"></span></button>')({index:e.attr("index")+"="+i,iconClass:o.iconClass}):n+=e.template('<button #=index# class="k-button k-button-md k-rounded-md k-button-flat k-button-flat-primary"><span class="k-button-text">#=text#</span></button>')({text:o.text,index:e.attr("index")+"="+i});return n}},_documentKeyDown:function(t){if(t.keyCode===e.keys.ESC&&(this._shown=!1,this._dismissAction()),t.keyCode===e.keys.TAB){var o=this.wrapper.find(":kendoFocusable"),n=o.first(),i=o.last();t.shiftKey?document.activeElement===n[0]&&(i.trigger("focus"),t.preventDefault()):document.activeElement===i[0]&&(n.trigger("focus"),t.preventDefault())}},_initPopup:function(){var o=this,i=o.options,l=t(e.template('<div role="tooltip" class="k-popover k-widget">#if (callout){ #<div class="k-popover-callout k-callout-#=dir#"></div><div class="k-popover-inner"></div>#}#</div>')({callout:i.callout&&"center"!==i.position,dir:u[i.position]}));o.wrapper=l,o.popup=new n(l,s({activate:function(){o._offset(o.options.position,o.options.offset,28),o._positionCallout(),o._attachActions(),a.on("keydown"+r,o._documentKeyDownHandler),o._isDialog?o.wrapper.find(":kendoFocusable").first().trigger("focus"):o._addDescribedBy(),o.trigger(p),o.popup._hovered=undefined},close:function(t){o.options.toggleOnClick&&o._shown?t.preventDefault():o.trigger(c)},copyAnchorStyles:!1,animation:i.animation},d[i.position])),l.css({width:i.width,height:i.height}),o._isShownOnMouseEnter()&&l.on("mouseleave"+r,o._dismissAction.bind(o)),o.arrow=l.find(".k-popover-callout")},_dismissAction:function(){var t=this;clearTimeout(t.timeout),t.timeout=setTimeout((function(){t.popup&&!t.popup._hovered&&t.popup.close()}),t.options.hideAfter)},_showAction:function(){var t=this;t._shown=!t._shown,t._shown||t.popup.close()},_show:function(t){var o=this,e=o.target();o.popup||o._initPopup(),e&&e[0]!=t[0]&&(o.popup.close(),o.popup.element.kendoStop(!0,!0)),e&&e[0]==t[0]||(o._appendContent(t),o._addAria(),o.popup.options.anchor=t),o.popup.one("deactivate",(function(){a.off("keydown"+r,o._documentKeyDownHandler),o._isDialog||(o._removeDescribedBy(o.target()),this.element.removeAttr("id")),o._dettachActions()})),o._openPopup()},_positionCallout:function(){var t=this,o=t.options.position,e=t.popup,n=u[e.flipped?l[o]:o];t.arrow.removeClass("k-callout-s k-callout-w k-callout-e k-callout-n").addClass("k-callout-"+n)},destroy:function(){this.element.off(r),this._dettachActions(),clearTimeout(this.timeout),a.off("keydown"+r,this._documentKeyDownHandler),i.fn.destroy.call(this)}});e.ui.plugin(m)}(window.kendo.jQuery);
//# sourceMappingURL=kendo.popover.js.map
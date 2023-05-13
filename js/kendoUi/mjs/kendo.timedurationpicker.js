/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.timeselector.js";var __meta__={id:"timedurationpicker",name:"TimeDurationPicker",category:"web",description:"The TimeDurationPicker widget allows the end user to select a time range stored in milliseconds.",docsCategory:"editors",depends:["timeselector"]};!function(e,t){var a=window.kendo,s=a.ui,n=s.Widget,i=a.html,r=e.map,o=e.extend,l="open",d="close",u="change",_="k-focus",c="aria-expanded",p="mouseenter"+(S=".kendoTimeDurationPicker")+" mouseleave"+S,m="k-disabled",h="aria-hidden",f="aria-disabled",v="aria-readonly",g="disabled",k="readonly",b="k-invalid",I="_",y=a.caret,P=a.keys,S=".kendoTimeDurationPicker";i=a.html;var w={hours:{name:"hours",format:"## hours",divider:36e5,min:0,max:23,step:1},minutes:{name:"minutes",format:"## mins",divider:6e4,min:0,max:59,step:1},seconds:{name:"seconds",format:"## sec",divider:1e3,min:0,max:59,step:1},milliseconds:{name:"milliseconds",format:"### msec",min:0,max:999,step:1},days:{format:"## days",name:"days",divider:864e5,min:0,max:365,step:1}};var x=n.extend({init:function(s,i){var l,d=this;i=i||{},n.fn.init.call(d,s,i),d._columns=(l=i.columns,r(l,(function(e){var t=o(!0,{},w[(e="string"==typeof e?{name:e}:e).name],e);return t.selectorFormat="{0:"+"0".repeat(t.format.match(/#/g).length)+"}",t}))),d.options.columns=d._columns,d.options.readonly=i.readonly!==t?i.readonly:Boolean(d.element.attr("readonly")),d.options.enable=i.enable!==t?i.enable:!Boolean(d.element.is("[disabled]")||e(s).parents("fieldset").is(":disabled")),d._wrapper(),d._button(),d._applyCssClasses(),d._input(),d._popupView(),d._buildMask(),d._validation(),d._editable({readonly:d.options.readonly,disable:!d.options.enable}),d._ariaLabel(d._timeSelector._listsContainer),d.options.value&&(d.value(i.value),d._old=d._maskedInput.val()),d._canUpdateLast=!0,a.notify(d)},options:{name:"TimeDurationPicker",columns:[],separator:",",shortcuts:[],value:null,size:"medium",fillMode:"solid",rounded:"medium",messages:{set:"Set",cancel:"Cancel",days:"Days",hours:"Hours",minutes:"Minutes",milliseconds:"Milliseconds",seconds:"Seconds"}},events:[l,d,u],destroy:function(){var e=this;e.wrapper.off(S),e._maskedInput.off(S),e._timeSelector&&(e._timeSelector.destroy(),e._timeSelector=null)},value:function(e){var a=this;if(e===t)return a._value;e="number"==typeof e?parseInt(e):null,a._value=e,a.element.val(a._value),a._timeSelector._value=a._value,a._updateValueFromTimeSelector()},_toggleHover:function(t){e(t.currentTarget).toggleClass("k-hover","mouseenter"===t.type)},_editable:function(e){var t=this,a=e.disable,s=e.readonly,n=t._maskedInput.add(t.element).off(S),i=t.wrapper.off(S);s||a?(i.addClass(a?m:"").removeClass(a?"":m),n.attr(g,a).attr(k,s).attr(f,a).attr(v,s)):(i.removeClass(m).on(p,t._toggleHover),n&&n.length&&(n.removeAttr(g),n.removeAttr(k)),n.attr(f,!1).attr(v,!1),t._attachHandlers())},readonly:function(e){this._editable({readonly:e===t||e,disable:!1})},enable:function(e){this._editable({readonly:!1,disable:!(e=e===t||e)})},_popupView:function(){var e=this,t=e.options;e._timeSelector||(e._timeSelector=new a.ui.TimeSelector(e._maskedInput,{id:e.element.attr("id")+"_timeSelector",anchor:e.wrapper,columns:t.columns,shortcuts:t.shortcuts,value:t.value,size:t.size,fillMode:t.fillMode,rounded:t.rounded,messages:t.messages,focus:function(){e._maskedInput.trigger("focus"),e._selectNearestSection(e._lastCaret||0)},close:function(t){e.trigger(d)?t.preventDefault():(e._maskedInput.attr(c,!1),e._timeSelector._listsContainer.attr(h,!0))},open:function(t){e.trigger(l)?t.preventDefault():(e._maskedInput.attr(c,!0),e._timeSelector._listsContainer.attr(h,!1),e._lastCaret=y(e._maskedInput)[0],e._lastCaret===e._maskedInput.val().length&&(e._lastCaret=0))},change:function(t){e._value=t,e._updateValueFromTimeSelector(),e._removeInvalidState(),e.trigger(u)}}))},_button:function(){var t,a=this,s=a.element,n=a.options;(t=s.next("button.k-input-button"))[0]||(t=e(i.renderButton('<button unselectable="on" tabindex="-1" class="k-input-button" aria-label="select"></button>',{icon:"clock-arrow-rotate",size:n.size,fillMode:n.fillMode,shape:"none",rounded:"none"})).insertAfter(s)),a._button=t.attr({role:"button"})},_buildMask:function(){var e,t,a=this.options.columns,s="";this._emtpyMask="",this._maskParts=[];for(var n=0;n<a.length;n++)t={},e=a[n].format,n&&(s=this.options.separator),s+=e.replace(/#/g,"_"),this._emtpyMask+=s,t.end=this._emtpyMask.length-1,t.mask=e.replace(/#/g,"_"),t.symbolsLength=t.mask.match(/_/g).length,t.start=this._emtpyMask.indexOf(t.mask),t.numberEnd=this._emtpyMask.lastIndexOf(I)+1,t.numberStart=this._emtpyMask.indexOf(I,t.start),this._maskParts.push(t);this._old=this._emtpyMask,this._maskedInput.val(this._emtpyMask)},_input:function(){var t=this;t._maskedInput=e("<input />").attr(a.attr("validate"),!1).attr({role:"combobox","aria-expanded":!1,"aria-controls":t.element.attr("id")+"_timeSelector",autocomplete:"off"}).addClass("k-input-inner").insertBefore(t.element),t.element.hide()},_wrapper:function(){var e,t=this.element;(e=t.parents(".k-timedurationpicker"))[0]||(e=t.wrap("<span></span>").parent()),this.wrapper=e.addClass("k-timedurationpicker k-input")},_attachHandlers:function(){var e=this;e._maskedInput.on("keydown"+S,e._keydown.bind(e)).on("input"+S,e._inputHandler.bind(e)).on("focus"+S,e._focus.bind(e)).on("focusout"+S,e._focusout.bind(e)).on("paste"+S,e._paste.bind(e)).on("mousedown"+S,e._mouseDown.bind(e)).on("mouseup"+S,e._mouseUp.bind(e)),e.wrapper.on("click"+S,".k-input-button",e._click.bind(e))},_mouseDown:function(){this._mouseOnInput=!0},_mouseUp:function(){var e=y(this._maskedInput[0]),t=this._maskedInput[0].value;e[0]===e[1]&&this._selectNearestSection(e[1]==t.length?t.length-1:e[1])},_triggerChange:function(){(this._lastValue==t&&this._value||this._lastValue!=this._value)&&(this._lastValue=this._value,this.trigger(u))},_focusout:function(){this._updateValueFromInput(),this._triggerChange(),this._canUpdateLast=!0,this.wrapper.removeClass(_)},_focus:function(){var e=this;e._mouseOnInput||setTimeout((function(){e._selectNearestSection(0)}),10),e._mouseOnInput=!1,e.wrapper.addClass(_)},_selectNearestSection:function(e){for(var t,a=0;a<this._maskParts.length;a++)e>=(t=this._maskParts[a]).start&&e<=t.end&&y(this._maskedInput[0],t.numberStart,t.numberEnd)},_getPartValue:function(e,t){var a=this.options.separator,s=e.indexOf(a,t),n=e.lastIndexOf(a,t);return e.substring(n<0?0:n+1,s<0?e.length:s)},_getEmptyPart:function(e){return this._getPartValue(this._emtpyMask,e)},_fillParts:function(e){var t,a=this._emtpyMask.split(this.options.separator);e+="";for(var s=0;s<a.length;s++)(t=a[s].match(/_/g).length)>e.length?a[s]=a[s].replace(I.repeat(t),"0".repeat(t-e.length)+e):(a[s]=a[s].replace(I.repeat(t),e.substring(0,t)),e=e.substring(t,t.length));return a.join(this.options.separator)},_clearParts:function(e,t,a){for(var s,n=e.split(this.options.separator),i=0,r=0;r<n.length;r++)s=i+n[r].length,(i<=t&&s>=t||i<=a&&s>=a)&&(n[r]=this._getEmptyPart(i)),i+=n[r].length+1;return n.join(this.options.separator)},_updatePart:function(e,t){var s=this._getEmptyPart(this._old.indexOf(e)),n=e.substring(s.indexOf(I),s.lastIndexOf(I)+1),i="{0:"+"0".repeat(n.length)+"}";return n.indexOf(I)>=0||"0"!==e[e.search(/[0-9]/)]||t.length>1?e.replace(n,a.format(i,parseInt(t))):e.replace(n,a.format(i,parseInt(n+t)))},_replacePart:function(e,t,a){return e.replace(this._getPartValue(e,a),t)},_click:function(){this._timeSelector&&this._timeSelector.toggle()},_switchPart:function(e){var a,s=this.options.separator,n=e.caret!=t?e.caret:y(this._maskedInput[0])[0],i=this._maskedInput.val();e.next?(a=i.indexOf(s,n))<0?a=i.length-1:a++:(a=i.lastIndexOf(s,n))<0?a=0:a--,this._selectNearestSection(a)},_keydown:function(e){var t,a,s,n,i,r=e.keyCode,o=this.options.separator;if(this._backward=r===P.BACKSPACE,r==P.LEFT||r==P.RIGHT){if(e.preventDefault(),t=y(this._maskedInput[0])[0],n=(a=this._maskedInput.val()).indexOf(o,t),i=a.lastIndexOf(o,t),n<0&&r==P.RIGHT||i<0&&r==P.LEFT)return;s=r==P.LEFT?i-1:n+1,this._selectNearestSection(s),r==P.LEFT&&(this._canUpdateLast=!0)}else this._timeSelector.popup.visible()?this._timeSelector._scrollerKeyDownHandler(e):r==P.UP||r==P.DOWN?(e.preventDefault(),e.altKey&&this._timeSelector.toggle()):r==P.ENTER&&(this._updateValueFromInput(),this._triggerChange())},_updateValueFromTimeSelector:function(){for(var e,t,a,s=this,n=function(e){var t,a=Math.floor(e/864e5);t=e%864e5;var s=Math.floor(t/36e5);t=e%36e5;var n=Math.floor(t/6e4);return t=e%6e4,{days:a,hours:s,minutes:n,seconds:Math.floor(t/1e3),milliseconds:t=e%1e3}}(s._value),i="",r=this._columns[o],o=0;o<this._columns.length;o++)t=n[(r=this._columns[o]).name]+"",e=this._maskParts[o],o&&(i+=s.options.separator),n[r.name]?(a=e.symbolsLength-t.length,i+=e.mask.replace(I.repeat(e.symbolsLength),"0".repeat(a<0?0:a)+t)):i+=e.mask;s._maskedInput.val(i),s._old=i},_validation:function(){var t=this.element;this._validationIcon=e("<span class='k-input-validation-icon k-icon k-i-warning k-hidden'></span>").insertAfter(t)},_addInvalidState:function(){this.wrapper.addClass(b),this._validationIcon.removeClass("k-hidden")},_removeInvalidState:function(){this.wrapper.removeClass(b),this._validationIcon.addClass("k-hidden")},_updateValueFromInput:function(){for(var e,t,s,n,i=this._maskedInput.val(),r={},o=!0,l=0;l<this._columns.length;l++){if(e=this._columns[l],t=this._maskParts[l],s=i.substring(t.numberStart,t.numberEnd).replace(/_/g,""),!((s=a.parseInt(s||0))>=e.min&&s<=e.max)){o=!1;break}r[e.name]=s}o?(this._value=864e5*((n=r).days||0)+36e5*(n.hours||0)+6e4*(n.minutes||0)+1e3*(n.seconds||0)+(n.milliseconds||0),this.element.val(this._value||""),this._timeSelector._value=this._value,this._removeInvalidState()):this._addInvalidState()},_paste:function(e){var t;this._isPasted=!0,this._pasted=(e.clipboardData||e.originalEvent.clipboardData).getData("Text");var a=y(this._maskedInput);this._maskedInput.val().length!=a[1]||a[0]?(t=this._getEmptyPart(a[0]),this._pasted=this._pasted.substring(0,t.match(/_/g).length)):this._replaceEntire=!0},_inputHandler:function(){if(a._activeElement()===this._maskedInput[0]){var e,t,s,n,i,r,o,l,d,u,_,c=this,p=c._old,m=c.options.separator,h=c._maskedInput[0].value,f=y(c._maskedInput)[0],v=p.length-h.length;if(e=c._isPasted?c._pasted:h.substring(f-1,f),t=/\d/.test(e),c._isPasted&&c._replaceEntire&&t)return c._old=c._fillParts(parseInt(e)),c._maskedInput.val(c._old),c._selectNearestSection(0),void(c._isPasted=c._replaceEntire=!1);if(c._isPasted&&(v=0),c._isPasted=!1,e===m&&!c._backward)return c._maskedInput.val(c._old),void c._switchPart({caret:f,next:!0});if(!h&&c._backward)return c._old=c._emtpyMask,c._maskedInput.val(c._old),void c._selectNearestSection(f);if(!t&&!c._backward)return c._maskedInput.val(c._old),void c._selectNearestSection(f);if(!v||v>0){if(n=h.substring(f,h.length),d=(i=h.substring(0,f))+(r=c._emtpyMask.substring(i.length,p.indexOf(n)))+n,u=c._getPartValue(c._old,f),_=c._old.indexOf(m,c._old.indexOf(u)+1),c._backward){if(r.split(m).length>1)c._old=this._clearParts(d,f,f+r.length);else{if((o=this._getEmptyPart(f))==u)return c._maskedInput.val(c._old),void c._switchPart({caret:f});c._old=c._replacePart(d,o,f)}return c._maskedInput.val(c._old),void c._selectNearestSection(f)}if(_<0&&-1===u.indexOf(I)&&!c._canUpdateLast)return c._maskedInput.val(c._old),void c._selectNearestSection(f);t&&(l="0"===(s=c._updatePart(u,e))[s.search(/[0-9]/)],d=c._replacePart(d,s,f),c._maskedInput.val(d),_>0&&!l?c._selectNearestSection(_+1,!0):c._selectNearestSection(f),c._canUpdateLast=!(_<0&&!l)),c._old=d}else v<0&&(c._maskedInput.val(c._old),c._selectNearestSection(c._old.length-1))}}});a.cssProperties.registerPrefix("TimeDurationPicker","k-input-"),a.cssProperties.registerValues("TimeDurationPicker",[{prop:"rounded",values:a.cssProperties.roundedValues.concat([["full","full"]])}]),s.plugin(x)}(window.kendo.jQuery);
//# sourceMappingURL=kendo.timedurationpicker.js.map

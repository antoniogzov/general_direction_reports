/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.datepicker.js";import"./kendo.timepicker.js";import"./kendo.html.button.js";import"./kendo.label.js";var __meta__={id:"datetimepicker",name:"DateTimePicker",category:"web",description:"The DateTimePicker allows the end user to select a value from a calendar or a time drop-down list.",depends:["datepicker","timepicker","label"]};!function(e,t){var i=window.kendo,a=i.TimeView,n=i.html,o=i.parseDate,l=i.support,s=i._activeElement,r=i._extractFormat,u=i.calendar,d=u.isInRange,p=u.restrictValue,m=u.isEqualDatePart,c=a.getMilliseconds,_=i.ui,f=_.Widget,g="open",v="close",h="change",b=".kendoDateTimePicker",w="click"+b,k=l.mouseAndTouchPresent?i.applyEventMap("up",b.slice(1)):w,V="disabled",y="readonly",T="k-focus",x="k-disabled",D="mousedown"+b,I="month",C="aria-activedescendant",P="aria-expanded",A="aria-hidden",S="aria-disabled",F="aria-readonly",M=Date,R=new M(1800,0,1),z=new M(2099,11,31),H={view:"date"},O={view:"time"},L=e.extend,E="k-selected",B=f.extend({init:function(t,a){var n,l=this;(a=a||{}).componentType=a.componentType||"classic",f.fn.init.call(l,t,a),t=l.element,(a=l.options).disableDates=i.calendar.disabled(a.disableDates),a.min=o(t.attr("min"))||o(a.min),a.max=o(t.attr("max"))||o(a.max),+a.max==+z&&+a.min==+R||(this._specifiedRange=!0),Y(a),l._initialOptions=L({},a),l._wrapper(),a.singlePopup&&l._popup(),l._views(),l._icons(),l._reset(),l._template();try{t[0].setAttribute("type","text")}catch(e){t[0].type="text"}t.addClass("k-input-inner").attr({role:"combobox","aria-expanded":!1,"aria-haspopup":"grid","aria-controls":l.dateView._dateViewID+" "+l.timeView._timeViewID,autocomplete:"off"}),l._midnight=l._calculateMidnight(a.min,a.max),t.is("[disabled]")||e(l.element).parents("fieldset").is(":disabled")?l.enable(!1):l.readonly(t.is("[readonly]")),n=o(a.value||l.element.val(),a.parseFormats,a.culture),l._createDateInput(a),l._old=l._update(n||l.element.val()),l._oldText=t.val(),l._applyCssClasses(),a.label&&l._label(),i.notify(l)},options:{name:"DateTimePicker",value:null,format:"",timeFormat:"",culture:"",parseFormats:[],dates:[],disableDates:null,min:new M(R),max:new M(z),interval:30,height:200,footer:"",start:I,depth:I,animation:{},month:{},ARIATemplate:"Current focused #=data.valueType# is #=data.text#",dateButtonText:"Open the date view",timeButtonText:"Open the time view",dateInput:!1,weekNumber:!1,messages:{set:"Set",cancel:"Cancel",hour:"hour",minute:"minute",second:"second",millisecond:"millisecond",now:"Now",date:"Date",time:"Time",today:"Today",weekColumnHeader:""},componentType:"classic",size:"medium",fillMode:"solid",rounded:"medium",label:null},events:[g,v,h],componentTypes:{classic:{singlePopup:!1,timeView:{list:"list"}},modern:{singlePopup:!0,timeView:{list:"scroll"}}},setOptions:function(e){var t,a,n,l=this,s=l._value;f.fn.setOptions.call(l,e),(e=l.options).min=t=o(e.min),e.max=a=o(e.max),Y(e),l._midnight=l._calculateMidnight(e.min,e.max),n=e.value||l._value||l.dateView._current,t&&!m(t,n)&&(t=new M(R)),a&&!m(a,n)&&(a=new M(z)),l._dateIcon.off(b),l._dateIcon.remove(),l._timeIcon.off(b),l._timeIcon.remove(),l.dateView.setOptions(e),l.timeView.setOptions(L({},e,{format:e.timeFormat,min:t,max:a})),l._icons(),l._editable(e),l._createDateInput(e),l._dateInput||l.element.val(i.toString(s,e.format,e.culture)),s&&l._updateARIA(s),e.label&&l._inputLabel?l.label.setOptions(e.label):!1===e.label?(l.label._unwrapFloating(),l._inputLabel.remove(),delete l._inputLabel):e.label&&l._label()},_editable:function(e){var t=this,a=t.element.off(b),n=t._dateIcon.off(b),o=t._timeIcon.off(b),l=t.wrapper.off(b),s=e.readonly,r=e.disable;s||r?(l.addClass(r?x:"").removeClass(r?"":x),a.attr(V,r).attr(y,s).attr(S,r).attr(F,s)):(l.removeClass(x).on("mouseenter.kendoDateTimePicker mouseleave.kendoDateTimePicker",t._toggleHover),a&&a.length&&(a[0].removeAttribute(V),a[0].removeAttribute(y,!1),a[0].removeAttribute(S,!1),a[0].removeAttribute(F,!1)),a.on("keydown"+b,t._keydown.bind(t)).on("focus"+b,(function(){t.wrapper.addClass(T)})).on("focusout"+b,(function(){t.wrapper.removeClass(T),a.val()!==t._oldText&&(t._change(a.val()),a.val()||t.dateView.current(i.calendar.getToday())),t.options.singlePopup||(t.close("date"),t.close("time"))})),n.on(D,N).on(k,(function(e){t.toggle("date"),t._focusElement(e.type)})),o.on(D,N).on(k,(function(e){t.toggle("time"),t._focusElement(e.type)})))},_label:function(){var t=this,a=t.options,n=e.isPlainObject(a.label)?a.label:{content:a.label};t.label=new i.ui.Label(null,e.extend({},n,{widget:t})),t._inputLabel=t.label.element},_focusElement:function(e){var t=this.element;l.touch&&(!l.mouseAndTouchPresent||(e||"").match(/touch/i))||t[0]===s()||t.trigger("focus")},readonly:function(e){this._editable({readonly:e===t||e,disable:!1}),this.label&&this.label.floatingLabel&&this.label.floatingLabel.readonly(e===t||e)},enable:function(e){this._editable({readonly:!1,disable:!(e=e===t||e)}),this.label&&this.label.floatingLabel&&this.label.floatingLabel.enable(e=e===t||e)},destroy:function(){var e=this;f.fn.destroy.call(e),e.dateView.destroy(),e.timeView.destroy(),e.label&&e.label.destroy(),e.options.singlePopup&&(e.popup.element.off(b),e.popup.destroy()),e.element.off(b),e._dateIcon.off(b),e._timeIcon.off(b),e.wrapper.off(b),e._form&&e._form.off("reset",e._resetHandler)},close:function(e){var t=this;t.options.singlePopup?t.popup.close():("time"!==e&&(e="date"),t[e+"View"].close()),setTimeout((function(){t.element.removeAttr("aria-activedescendant")}))},open:function(e){var t,i=this;i.options.singlePopup?(i.dateView._calendar(),i.timeView.ul.find("li").length<1&&i.timeView.bind(),t=i.popup._hovered,i.popup._hovered=!0,i.popup.open(),"time"===e?i._switchToTimeView():i._switchToDateView(),this._dateIcon.toggle("time"!==e),this._timeIcon.toggle("time"===e),setTimeout((function(){i.popup._hovered=t}),1)):("time"!==e&&(e="date"),this[e+"View"].open())},min:function(e){return this._option("min",e)},max:function(e){return this._option("max",e)},toggle:function(e){if(this.options.singlePopup)this.popup.visible()?this.close():this.open(e);else{var t="timeView";"time"!==e?e="date":t="dateView",this[e+"View"].toggle(),this[t].close()}},value:function(e){var i=this;if(e===t)return i._value;i._old=i._update(e),null===i._old&&(i._dateInput?i._dateInput.value(i._old):i.element.val("")),i._oldText=i.element.val(),i.label&&i.label.floatingLabel&&i.label.floatingLabel.refresh()},_change:function(e){var t,i=this,a=i.element.val();e=i._update(e);var n=(t=+i._old!=+e)&&!i._typing,o=a!==i.element.val();(n||o)&&i.element.trigger(h),t&&(i._old=e,i._oldText=i.element.val(),i.trigger(h)),i._typing=!1},_option:function(e,i){var a,n,l=this,s=l.options,r=l.timeView,u=r.options,d=l._value||l._old;if(i===t)return s[e];if(i=o(i,s.parseFormats,s.culture),u.maxSet=!1,i){if(s.min.getTime()===s.max.getTime()&&(u.dates=[]),s[e]=new M(i.getTime()),l.dateView[e](i),l._midnight=l._calculateMidnight(s.min,s.max),d&&(a=m(s.min,d),n=m(s.max,d)),a||n){if(u[e]=i,a&&!n&&(u.max=j(s.interval)),n){if(l._midnight)return void r.dataBind([z]);a||(u.min=R,u.maxSet=!0)}}else u.max=z,u.min=R;r.bind()}},_toggleHover:function(t){e(t.currentTarget).toggleClass("k-hover","mouseenter"===t.type)},_update:function(t){var a,n,l,s,r,u=this,c=u.options,_=c.min,f=c.max,g=c.dates,v=u.timeView,b=u._value,w=o(t,c.parseFormats,c.culture),k=null===w&&null===b||w instanceof Date&&b instanceof Date;return c.disableDates&&c.disableDates(w)&&(w=null,u._old||u.element.val()||(t=null)),+w==+b&&k?((r=i.toString(w,c.format,c.culture))!==t&&(u.element.val(null===w?t:r),t instanceof String&&u.element.trigger(h)),w):(null!==w&&m(w,_)?w=p(w,_,f):d(w,_,f)||(w=null),u._value=w,v.value(w),u.dateView.value(w),w&&(l=u._old,(n=v.options).maxSet=!1,g[0]&&(g=e.grep(g,(function(e){return m(w,e)})))[0]&&(v.dataBind(g),s=!0),s||(m(w,_)&&(n.min=_,n.max=j(c.interval),a=!0),m(w,f)&&(u._midnight?(v.dataBind([z]),s=!0):(n.max=f,n.maxSet=!0,a||(n.min=R),a=!0))),!s&&(!l&&a||l&&!m(l,w))&&(a||(n.max=z,n.min=R),v.bind())),u._dateInput?w&&u._dateInput.value(w):u.element.val(i.toString(w||t,c.format,c.culture)),u._updateARIA(w),w)},_keydown:function(e){var t=this,a=t.dateView,n=t.timeView,o=t.element.val(),l=t.options.singlePopup?t.popup.visible():a.popup.visible(),s=t._dateInput&&e.stopImmediatePropagation;e.altKey&&e.keyCode===i.keys.DOWN?t.toggle(l?"time":"date"):l?(a.move(e),t._updateARIA(a._current),e.keyCode===i.keys.ENTER&&t.toggle("time")):!t.options.singlePopup&&n.popup.visible()?n.move(e):e.keyCode===i.keys.ENTER&&o!==t._oldText?t._change(o):(t._typing=!0,s=!1),s&&e.stopImmediatePropagation()},_views:function(){var e,t,n,l,s,r,u,d,p,m=this,c=m.element,_=m.options,f=c.attr("id");_.singlePopup&&(_.dateDiv=m.popup.element.find(".k-datetime-calendar-wrap"),u=m.popup.element.find(".k-datetime-time-wrap"),_.omitPopup=d=!0,p=_.timeView),m.dateView=e=new i.DateView(L({},_,{id:f,anchor:m.wrapper,change:function(){var e=m._applyDateValue();_.singlePopup?(m.timeView._currentlySelected||(m.timeView._currentlySelected=new Date),m.timeView._currentlySelected.setFullYear(e.getFullYear()),m.timeView._currentlySelected.setMonth(e.getMonth()),m.timeView._currentlySelected.setDate(e.getDate()),m._switchToTimeView(),m._toggleIcons()):(m._change(e),m.close("date"))},close:function(e){m.trigger(v,H)?e.preventDefault():(c.attr(P,!1),n.attr(A,!0))},open:function(e){m.trigger(g,H)?e.preventDefault():(c.val()!==m._oldText&&(r=o(c.val(),_.parseFormats,_.culture),m.dateView[r?"current":"value"](r)),n.attr(A,!1),c.attr(P,!0),m._updateARIA(r))}})),n=e.div,s=_.min.getTime(),m.timeView=t=new a({id:f,value:_.value,size:_.size,anchor:m.wrapper,animation:_.animation,format:_.timeFormat,culture:_.culture,height:"modern"===_.componentType?null:_.height,interval:_.interval,min:"modern"===_.componentType?_.min:new M(R),max:"modern"===_.componentType?_.max:new M(z),dates:s===_.max.getTime()?[new Date(s)]:[],parseFormats:_.parseFormats,validateDate:!0,change:function(t,a){t=m._applyTimeValue(t),a?(m._timeSelected=!0,m._change(t)):(c.val(i.toString(t,_.format,_.culture)),e.value(t),m._updateARIA(t))},close:function(e){m.trigger(v,O)?e.preventDefault():(l.attr(A,!0),c.attr(P,!1))},open:function(e){"modern"!==m.options.componentType?t._adjustListWidth():m.timeView._updateTitle(),m.trigger(g,O)?e.preventDefault():(c.val()!==m._oldText&&(r=o(c.val(),_.parseFormats,_.culture),m.timeView.value(r)),l.attr(A,!1),c.attr(P,!0),t.options.active(t.current()))},active:function(e){c&&c.length&&c[0].removeAttribute(C),e&&c.attr(C,t._optionID)},popup:_.popup,useValueToRender:!0,specifiedRange:m._specifiedRange,omitPopup:d,timeDiv:u,timeView:p,messages:m.options.messages}),l=t.ul},_applyDateValue:function(){var e,t,a=this,n=a.options,o=a.dateView.calendar.value(),l=+o,s=+n.min,r=+n.max;return l!==s&&l!==r||(e=l===s?s:r,(e=new M(a._value||e)).setFullYear(o.getFullYear(),o.getMonth(),o.getDate()),d(e,s,r)&&(o=e)),a._value&&(t=i.date.setHours(new Date(o),a._value),d(t,s,r)&&(o=t)),o},_applyTimeValue:function(e){var t=this.timeView,i=this.options;return(e=t._parse(e))<i.min?(e=new M(+i.min),t.options.min=e):e>i.max&&(e=new M(+i.max),t.options.max=e),e},_icons:function(){var t=this,i=t.element,a=t.options;t.wrapper.find("button.k-input-button")[0]||(t._dateIcon=e(n.renderButton('<button unselectable="on" tabindex="-1" class="k-input-button" aria-label="'+a.dateButtonText+'"></button>',{icon:"calendar",size:a.size,fillMode:a.fillMode,shape:"none",rounded:"none"})).insertAfter(i),t._timeIcon=e(n.renderButton('<button unselectable="on" tabindex="-1" class="k-input-button" aria-label="'+a.timeButtonText+'"></button>',{icon:"clock",size:a.size,fillMode:a.fillMode,shape:"none",rounded:"none"})).insertAfter(i)),a.singlePopup&&t._timeIcon.hide()},_wrapper:function(){var e,t=this.element;(e=t.parents(".k-datetimepicker"))[0]||(e=t.wrap("<span/>").parent()),e[0].style.cssText=t[0].style.cssText,t.css({width:"100%",height:t[0].style.height}),this.wrapper=e.addClass("k-datetimepicker k-input").addClass(t[0].className).removeClass("input-validation-error")},_reset:function(){var t=this,a=t.element,n=a.attr("form"),o=n?e("#"+n):a.closest("form"),l=t.options,s=l.disableDates,r=l.parseFormats.length?l.parseFormats:null,u=t._initialOptions.value,d=a[0].defaultValue;u&&s&&s(u)&&(u=null),d&&i.parseDate(d,r,l.culture)||!u||a.attr("value",i.toString(u,l.format,l.culture)),o[0]&&(t._resetHandler=function(){t.value(u||a[0].defaultValue),t.max(t._initialOptions.max),t.min(t._initialOptions.min)},t._form=o.on("reset",t._resetHandler))},_template:function(){this._ariaTemplate=i.template(this.options.ARIATemplate).bind(this)},_createDateInput:function(e){this._dateInput&&(this._dateInput.destroy(),this._dateInput=null),e.dateInput&&(this._dateInput=new _.DateInput(this.element,{culture:e.culture,format:e.format,size:e.size,fillMode:e.fillMode,rounded:e.rounded,min:e.min,max:e.max,interval:e.interval}))},_calculateMidnight:function(e,t){return c(e)+c(t)===0},_updateARIA:function(e){var t=this,i=t.dateView.calendar;t.element&&t.element.length&&t.element[0].removeAttribute(C),i&&t.element.attr(C,i._updateAria(t._ariaTemplate,e))},_popup:function(){var t=this,a=t.options,n=e("<div></div>").attr(A,"true").addClass("k-datetime-container k-group k-reset").appendTo(document.body);n.append(i.template('<div class="k-date-tab k-datetime-wrap"><div class="k-datetime-buttongroup"><div class="k-button-group k-button-group-stretched"><button class="k-button #=buttonSize# k-rounded-md k-button-solid k-button-solid-base k-selected k-group-start"><span class="k-button-text">#=messages.date#</span></button><button class="k-button #=buttonSize# k-rounded-md k-button-solid k-button-solid-base k-group-end"><span class="k-button-text">#=messages.time#</span></button></div></div><div class="k-datetime-selector"><div class="k-datetime-calendar-wrap"></div><div class="k-datetime-time-wrap"></div></div><div class="k-datetime-footer k-action-buttons"><button class="k-button #=buttonSize# k-rounded-md k-button-solid k-button-solid-base k-time-cancel" title="Cancel" aria-label="Cancel"><span class="k-button-text">#=messages.cancel#</span></button><button class="k-time-accept k-button #=buttonSize# k-rounded-md k-button-solid k-button-solid-primary" title="Set" aria-label="Set"><span class="k-button-text">#=messages.set#</span></button></div></div>')(L({},t.options,{buttonSize:i.getValidCssClass("k-button-","size",t.options.size)}))),t.popup=new _.Popup(n,L(a.popup,a,{name:"Popup",isRtl:i.support.isRtl(t.wrapper),anchor:t.wrapper,activate:function(){t.options.timeView&&"scroll"===t.options.timeView.list&&(t.timeView.addTranslate(),t.timeView.applyValue(t._value),t.timeView._updateRanges())},open:function(e){t.trigger(g,{view:this.element.find(".k-date-tab").length?"date":"time",sender:t})?e.preventDefault():(this.element.attr(A,!1),t.element.attr(P,!0)),t.timeView._updateTitle()},close:function(e){t.trigger(v,{view:this.element.find(".k-date-tab").length?"date":"time",sender:t})?e.preventDefault():(t.element.attr(P,!1),this.element.attr(A,!0))}})),n.on(w+b,".k-datetime-buttongroup .k-button",t._groupChangeClick.bind(t)),n.on(w+b,".k-datetime-footer button.k-time-cancel",t._cancelClickHandler.bind(t)),n.on(w+b,".k-datetime-footer button.k-time-accept",t._setClickHandler.bind(t))},_groupChangeClick:function(t){N(t),e(t.currentTarget).index()?this._switchToTimeView():this._switchToDateView(),this._toggleIcons()},_switchToDateView:function(){this.popup.element.find(".k-group-start, .k-group-end").removeClass(E).eq(0).addClass(E),this.popup.element.find(".k-datetime-wrap").removeClass("k-time-tab").addClass("k-date-tab")},_switchToTimeView:function(){this.timeView.addTranslate(),this.timeView.applyValue(this._value),this.timeView._updateRanges(),this.popup.element.find(".k-group-start, .k-group-end").removeClass(E).eq(1).addClass(E),this.popup.element.find(".k-datetime-wrap").removeClass("k-date-tab").addClass("k-time-tab")},_toggleIcons:function(){this._dateIcon.toggle(),this._timeIcon.toggle()},_cancelClickHandler:function(e){N(e),this._value&&(this.value(this._value),this.dateView.value(this._value)),this.popup.close()},_setClickHandler:function(e){N(e);var t,i=this._applyDateValue();i=i||new Date,t=this.timeView._currentlySelected||i,this.timeView._updateCurrentlySelected(),i.setHours(t.getHours()),i.setMinutes(t.getMinutes()),i.setSeconds(t.getSeconds()),i=this._applyTimeValue(i),this._change(i),this.popup.close()}});function j(e){var t=new Date(2100,0,1);return t.setMinutes(-e),t}function N(e){e.preventDefault()}function Y(t){var a,n=i.getCulture(t.culture).calendars.standard.patterns,o=!t.parseFormats.length;t.format=r(t.format||n.g),t.timeFormat=a=r(t.timeFormat||n.t),i.DateView.normalize(t),o&&t.parseFormats.unshift("yyyy-MM-ddTHH:mm:ss"),-1===e.inArray(a,t.parseFormats)&&t.parseFormats.push(a)}i.cssProperties.registerPrefix("DateTimePicker","k-input-"),i.cssProperties.registerValues("DateTimePicker",[{prop:"rounded",values:i.cssProperties.roundedValues.concat([["full","full"]])}]),_.plugin(B)}(window.kendo.jQuery);
//# sourceMappingURL=kendo.datetimepicker.js.map

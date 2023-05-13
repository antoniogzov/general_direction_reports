/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.autocomplete.js";import"./kendo.datepicker.js";import"./kendo.numerictextbox.js";import"./kendo.combobox.js";import"./kendo.dropdownlist.js";var __meta__={id:"filtercell",name:"Row filter",category:"framework",depends:["autocomplete"],advanced:!0};!function(e,t){var r=window.kendo,a=r.ui,o=r.data.DataSource,l=a.Widget,i="change",n="boolean",s="enums",u="string",p="Is equal to",d="Is not equal to",c=["isnull","isnotnull","isempty","isnotempty","isnullorempty","isnotnullorempty"];function f(t){var r="string"==typeof t?t:t.operator;return e.inArray(r,c)>-1}function g(t,r){var a=[];if(e.isPlainObject(t))if(t.hasOwnProperty("filters"))a=t.filters;else if(t.field==r)return t;Array.isArray(t)&&(a=t);for(var o=0;o<a.length;o++){var l=g(a[o],r);if(l)return l}}function v(t,r){t.filters&&(t.filters=e.grep(t.filters,(function(e){return v(e,r),e.filters?e.filters.length:e.field!=r})))}var m=l.extend({init:function(a,o){a=e(a).addClass("k-filtercell");var p,d,c,g,v=this.wrapper=e("<span/>").appendTo(a),m=this,h=o,b=m.operators=o.operators||{},_=m.input=e("<input/>").attr(r.attr("bind"),"value: value").appendTo(v),w=o?o.suggestDataSource:null;w&&(o=e.extend({},o,{suggestDataSource:{}})),l.fn.init.call(m,a[0],o),w&&(m.options.suggestDataSource=w),o=m.options,p=m.dataSource=o.dataSource,m.model=p.reader.model,g=o.type=u;var y=(r.getter("reader.model.fields",!0)(p)||{})[o.field];if(y&&y.type&&(g=o.type=y.type),o.values&&(o.type=g=s),b=b[g]||o.operators[g],!h.operator)for(c in b){o.operator=c;break}if(m._parse=function(e){return null!=e?e+"":e},m.model&&m.model.fields){var S=m.model.fields[o.field];S&&S.parse&&(m._parse=S.parse.bind(S))}m.defaultOperator=o.operator,m.viewModel=d=r.observable({operator:o.operator,value:null,operatorVisible:function(){var e=this.get("value");return null!==e&&e!==t&&"undefined"!=e||f(this.get("operator"))&&m.dataSource.filter()&&!m._clearInProgress}}),m._prevOperator=o.operator,d.bind(i,m.updateDsFilter.bind(m)),g==u&&m.initSuggestDataSource(o),null!==o.inputWidth&&(_.addClass("k-sized-input"),_.width(o.inputWidth)),_.attr("aria-label",m._getColumnTitle()),_.attr("title",m._getColumnTitle()),m._setInputType(o,g),g!=n&&!1!==o.showOperators?m._createOperatorDropDown(b):(e('<div unselectable="on" />').css("display","none").text("eq").appendTo(v),v.addClass("k-operator-hidden")),m._createClearIcon(),r.bind(this.wrapper,d),g==u&&(o.template||m.setAutoCompleteSource()),g==s&&m.setComboBoxSource(m.options.values),m._refreshUI(),m._refreshHandler=m._refreshUI.bind(m),m.dataSource.bind(i,m._refreshHandler)},_setInputType:function(t,a){var o=this,l=o.input;if("function"==typeof t.template)t.template.call(o.viewModel,{element:o.input,dataSource:o.suggestDataSource}),o._angularItems("compile");else if(a==u)l.attr(r.attr("role"),"autocomplete").attr(r.attr("text-field"),t.dataTextField||t.field).attr(r.attr("filter"),t.suggestionOperator).attr(r.attr("delay"),t.delay).attr(r.attr("min-length"),t.minLength).attr(r.attr("value-primitive"),!0);else if("date"==a)l.attr(r.attr("role"),"datepicker").attr("id",r.guid());else if(a==n){l.remove();var i=e("<input type='radio'/>"),p=o.wrapper,d=r.guid(),c=e("<label/>").text(t.messages.isTrue).append(i);i.attr(r.attr("bind"),"checked:value").attr("name",d).val("true");var f=c.clone().text(t.messages.isFalse);i.clone().val("false").appendTo(f),p.append([c,f])}else"number"==a?l.attr(r.attr("role"),"numerictextbox").attr("title",o._getColumnTitle()):a==s&&l.attr(r.attr("role"),"combobox").attr(r.attr("text-field"),"text").attr(r.attr("suggest"),!0).attr(r.attr("filter"),"contains").attr(r.attr("value-field"),"value").attr(r.attr("value-primitive"),!0)},_getColumnTitle:function(){var e=this.options.column;return e?e.title||e.field:""},_createOperatorDropDown:function(t){var a=[],o=this.viewModel;for(var l in t)a.push({text:t[l],value:l});var i=e('<input class="k-dropdown-operator" '+r.attr("bind")+'="value: operator"/>').appendTo(this.wrapper);i.attr("aria-label",this._getColumnTitle()),this.operatorDropDown=i.kendoDropDownList({dataSource:a,dataTextField:"text",dataValueField:"value",open:function(){this.popup.element.width(150)},valuePrimitive:!0}).data("kendoDropDownList"),o.bind("change",(function(){var e=t[o.operator];i.attr("aria-label",e)})),this.operatorDropDown.wrapper.attr("aria-label",this._getColumnTitle()).find(".k-i-arrow-s").removeClass("k-i-arrow-s").addClass("k-i-filter")},initSuggestDataSource:function(e){var a,l,i,n=e.suggestDataSource;n instanceof o||(!e.customDataSource&&n&&(n.group=t,n.filter=t),n=this.suggestDataSource=o.create(n)),e.customDataSource||(n._pageSize=t,n.reader.data=(a=n.reader.data,l=this.options.field,i=r.getter(l,!0),function(e){for(var t=a(e),r=[],o=0,l={};o<t.length;){var n=t[o++],s=i(n);l.hasOwnProperty(s)||(r.push(n),l[s]=!0)}return r})),this.suggestDataSource=n},setAutoCompleteSource:function(){var e=this.input.data("kendoAutoComplete");e&&e.setDataSource(this.suggestDataSource)},setComboBoxSource:function(e){var t=o.create({data:e}),r=this.input.data("kendoComboBox");r&&!this.options.template&&r.setDataSource(t)},_refreshUI:function(){var t=this,r=g(t.dataSource.filter(),this.options.field)||{},a=t.viewModel;t.manuallyUpdatingVM=!0,r=e.extend(!0,{},r),t.options.type==n&&a.value!==r.value&&t.wrapper.find(":radio").prop("checked",!1),r.operator&&a.set("operator",r.operator),a.set("value",r.value),e.isEmptyObject(r)&&a.trigger(i,{field:"operatorVisible"}),t.manuallyUpdatingVM=!1},_applyFilter:function(e){e.filters.length?this.dataSource.filter(e):this.dataSource.filter({})},updateDsFilter:function(r){var a,o=this,l=o.viewModel;if("operator"==r.field&&l.value===t&&!f(l)&&f(o._prevOperator))return v(a=o.dataSource.filter()||{filters:[],logic:"and"},o.options.field),o._prevOperator=l.operator,void o._applyFilter(a);if(!(o.manuallyUpdatingVM||"operator"==r.field&&l.value===t&&!f(l)||"operator"==r.field&&o._clearInProgress&&null!==l.value)){var n=e.extend({},o.viewModel.toJSON(),{field:o.options.field});o._prevOperator=n.operator;var s={logic:"and",filters:[]},u=!1;if((n.value!==t&&null!==n.value||f(n)&&!this._clearInProgress)&&(s.filters.push(n),u=o.trigger(i,{filter:s,field:o.options.field})),(o._clearInProgress||null===n.value)&&(u=o.trigger(i,{filter:null,field:o.options.field})),!u){var p=o._merge(s);o._applyFilter(p)}}},_merge:function(t){var r,a,o,l=this,i=t.logic||"and",n=t.filters,s=l.dataSource.filter()||{filters:[],logic:"and"};for(v(s,l.options.field),a=0,o=n.length;a<o;a++)(r=n[a]).value=l._parse(r.value);return n=e.grep(n,(function(e){return""!==e.value&&null!==e.value||f(e)})),n.length&&(s.filters.length?(t.filters=n,"and"!==s.logic&&(s.filters=[{logic:s.logic,filters:s.filters}],s.logic="and"),n.length>1?s.filters.push(t):s.filters.push(n[0])):(s.filters=n,s.logic=i)),s},_createClearIcon:function(){var t=this;e("<button type='button' class='k-button k-button-md k-rounded-md k-button-solid k-button-solid-base k-icon-button' title = '"+t.options.messages.clear+"'/>").attr("aria-label",t.options.messages.clear).attr(r.attr("bind"),"visible:operatorVisible").html("<span class='k-button-icon k-icon k-i-filter-clear'></span>").on("click",t.clearFilter.bind(t)).appendTo(t.wrapper)},clearFilter:function(){this._clearInProgress=!0,f(this.viewModel.operator)&&this.viewModel.set("operator",this.defaultOperator),this.viewModel.set("value",null),this._clearInProgress=!1},_angularItems:function(e){var t=this.wrapper.closest("td").get(),r=this.options.column;this.angular(e,(function(){return{elements:t,data:[{column:r}]}}))},destroy:function(){var e=this;e.filterModel=null,e.operatorDropDown=null,e._angularItems("cleanup"),e._refreshHandler&&(e.dataSource.bind(i,e._refreshHandler),e._refreshHandler=null),r.unbind(e.element),l.fn.destroy.call(e),r.destroy(e.element)},events:[i],options:{name:"FilterCell",delay:200,minLength:1,inputWidth:null,values:t,customDataSource:!1,field:"",dataTextField:"",type:"string",suggestDataSource:null,suggestionOperator:"startswith",operator:"eq",showOperators:!0,template:null,messages:{isTrue:"is true",isFalse:"is false",filter:"Filter",clear:"Clear",operator:"Operator"},operators:{string:{eq:p,neq:d,startswith:"Starts with",contains:"Contains",doesnotcontain:"Does not contain",endswith:"Ends with",isnull:"Is null",isnotnull:"Is not null",isempty:"Is empty",isnotempty:"Is not empty",isnullorempty:"Has no value",isnotnullorempty:"Has value"},number:{eq:p,neq:d,gte:"Is greater than or equal to",gt:"Is greater than",lte:"Is less than or equal to",lt:"Is less than",isnull:"Is null",isnotnull:"Is not null"},date:{eq:p,neq:d,gte:"Is after or equal to",gt:"Is after",lte:"Is before or equal to",lt:"Is before",isnull:"Is null",isnotnull:"Is not null"},enums:{eq:p,neq:d,isnull:"Is null",isnotnull:"Is not null"}}}});a.plugin(m)}(window.kendo.jQuery);
//# sourceMappingURL=kendo.filtercell.js.map

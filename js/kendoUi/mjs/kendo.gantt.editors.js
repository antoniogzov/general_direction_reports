/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.data.js";import"./kendo.popup.js";import"./kendo.window.js";import"./kendo.gantt.data.js";import"./kendo.grid.js";import"./kendo.datetimepicker.js";import"./kendo.numerictextbox.js";import"./kendo.textbox.js";import"./kendo.form.js";var __meta__={id:"gantt.editors",name:"GanttEditors",category:"web",description:"The Gantt component editors.",depends:["data","popup","window","gantt.data","grid","datetimepicker","numerictextbox","textbox","form"],hidden:!0};!function(t,e){var i=window.kendo,n=i.ui,o=i.support.browser,s=i.Observable,a=n.Widget,r=i._outerWidth,d=t.extend({F10:121},i.keys),l=t.extend,p=t.isPlainObject,c=".kendoGantt",u="click",f="width",h=".",m={down:{origin:"bottom left",position:"top left"},up:{origin:"top left",position:"bottom left"}},g=i.template('<div class="#=styles.popupWrapper#"><ul class="#=styles.popupList#" role="listbox" aria-label="Add dropdown">#for(var i = 0, l = actions.length; i < l; i++){#<li tabindex="0" class="#=styles.item#" data-action="#=actions[i].data#" role="option"><span class="k-link k-menu-link"><span class="k-menu-link-text">#=actions[i].text#</span></span></li>#}#</ul></div>'),v={buttonDelete:"k-gantt-delete",buttonCancel:"k-gantt-cancel",buttonSave:"k-gantt-update",focused:"k-focus",gridContent:"k-grid-content",hovered:"k-hover",item:"k-item k-menu-item",popupWrapper:"k-popup k-menu-popup",popupList:"k-menu-group k-menu-group-md k-reset",popup:{form:"k-popup-edit-form",editForm:"k-gantt-edit-form",formContainer:"k-edit-form-container",resourcesFormContainer:"k-resources-form-container",message:"k-popup-message",buttonsContainer:"k-edit-buttons",button:"k-button",buttonDefaults:"k-button-md k-rounded-md k-button-solid k-button-solid-base",editField:"k-edit-field",editLabel:"k-edit-label",resourcesField:"k-gantt-resources"},primary:"k-button-solid-primary",toolbar:{appendButton:"k-gantt-create"}},b=s.extend({init:function(t,e){s.fn.init.call(this),this.element=t,this.options=l(!0,{},this.options,e),this._popup()},options:{direction:"down"},destroy:function(){clearTimeout(this._focusTimeout),this.popup.destroy(),this.element.off(c),this.list.off(c),this.unbind()},_adjustListWidth:function(){var t,e,i=this.list,n=i[0].style.width,s=this.element,a=r(i);!i.data(f)&&n||(e=(t=window.getComputedStyle?window.getComputedStyle(s[0],null):0)?parseFloat(t.width):r(s),t&&(o.mozilla||o.msie)&&(e+=parseFloat(t.paddingLeft)+parseFloat(t.paddingRight)+parseFloat(t.borderLeftWidth)+parseFloat(t.borderRightWidth)),a>(n="border-box"!==i.css("box-sizing")?e-(r(i)-i.width()):e)&&(n=a),i.css({fontFamily:s.css("font-family"),width:n}).data(f,n))},_current:function(t){var e=this.list.find(h+v.focused),i=e[t]();i.length&&(e.removeClass(v.focused).removeAttr("id"),i.addClass(v.focused).trigger("focus"))},_popup:function(){var e=this,n="li.k-item",o=this.options.messages.actions;this.list=t(g({styles:v,actions:[{data:"add",text:o.addChild},{data:"insert-before",text:o.insertBefore},{data:"insert-after",text:o.insertAfter}]})),this.element.append(this.list),this.popup=new i.ui.Popup(this.list,l({anchor:this.element,open:function(){e._adjustListWidth()},animation:this.options.animation},m[this.options.direction])),this.element.on(u+c,(function(n){var o=t(this).attr(i.attr("action"));n.preventDefault(),o?e.trigger("command",{type:o}):(e.popup.open(),e.list.find("li:first").addClass(v.focused).trigger("focus"))})),this.list.find(n).hover((function(){t(this).addClass(v.hovered)}),(function(){t(this).removeClass(v.hovered)})).end().on(u+c,n,(function(){e.trigger("command",{type:t(this).attr(i.attr("action"))}),e.popup.close()})),this.popup.bind("close",(function(){e.list.find(n).removeClass(v.focused),e.element.parents("["+i.attr("role")+'="gantt"]').find(h+v.gridContent+" > table").first().trigger("focus")})),this.list.find("ul").on("keydown"+c,(function(t){switch(t.keyCode){case d.UP:t.preventDefault(),e._current("prev");break;case d.DOWN:t.preventDefault(),e._current("next");break;case d.ENTER:e.list.find(h+v.focused).click();break;case d.ESC:t.preventDefault(),e.popup.close()}}))}}),k={desktop:{dateRange:function(e,n){var o={name:n.field,title:n.title},s=n.model.fields[n.field].validation;s&&p(s)&&s.message&&(o[i.attr("dateCompare-msg")]=s.message),t('<input type="text" required id="'+n.field+'"'+i.attr("type")+'="date" '+i.attr("role")+'="datetimepicker" '+i.attr("bind")+'="value:'+n.field+'" '+i.attr("validate")+"='true' />").attr(o).appendTo(e),t("<span "+i.attr("for")+'="'+n.field+'" class="k-invalid-msg"/>').hide().appendTo(e)},resources:function(e,i){t('<div class="'+v.popup.resourcesField+'" style="display:none"></div><a href="#" class="'+i.styles.button+" "+i.styles.buttonDefaults+'"><span class="k-button-text">'+i.messages.assignButton+"</span></a>").on("click",i.click).appendTo(e)},readonly:function(t,e){var i=e.field,n="";null!==e.model.get(i)&&(n=e.model.get(i)),t.append("<span>"+n+"</span>")}}},y=i.Observable.extend({init:function(t,e){i.Observable.fn.init.call(this),this.element=t,this.options=l(!0,{},this.options,e),this.createButton=this.options.createButton},fields:function(e,i,n){var o,s=this,a=this.options,r=a.messages.editor,d=a.resources;return a.editable.template?o=t.map(i.fields,(function(t,e){return{field:e}})):(o=[{field:"title",title:r.title},{field:"start",title:r.start,editor:e.dateRange},{field:"end",title:r.end,editor:e.dateRange},{field:"percentComplete",title:r.percentComplete,format:"p0"}],n&&o.splice.apply(o,[1,0].concat([{field:"plannedStart",title:r.plannedStart,editor:e.dateRange},{field:"plannedEnd",title:r.plannedEnd,editor:e.dateRange}])),i.get(d.field)&&o.push({field:d.field,title:r.resources,messages:r,editor:e.resources,click:function(t){t.preventDefault(),d.editor(s.container.find(h+v.popup.resourcesField),i)},styles:v.popup}),o=o.map((function(t){return!i.editable||i.editable(t.field)?t:l(!0,t,{editor:e.readonly})}))),o.map((function(t){return l(!0,t,{label:t.title})}))},_buildEditTemplate:function(t){var e=this.options.editable.template,n=l({},i.Template,this.options.templateSettings),o="";return e?("string"==typeof e&&(e=i.unescape(e)),o+=i.template(e,n)(t)):(this.renderForm=!0,o+='<div class="k-gantt-form"></div>'),o}}),w=y.extend({destroy:function(){this.close(),this.unbind()},close:function(){var t=this,e=function(){t.editable&&(t.container.data("kendoWindow").destroy(),t.editable=null,t.container=null),t.popup&&(t.popup.destroy(),t.popup=null)};t.editable&&t.container.is(":visible")?(t.trigger("close",{window:t.container}),t.container.data("kendoWindow").bind("deactivate",e).close()):e()},editTask:function(t,e){this.editable=this._createPopupEditor(t,e)},showDialog:function(e){for(var n=e.buttons,o=v.popup,s=i.format('<div class="{0}"><div class="{1}"><p class="{2}">{3}</p><div class="{4}">',o.form,o.formContainer,o.message,e.text,o.buttonsContainer),a=0,r=n.length;a<r;a++)s+=this.createButton(n[a]);s+="</div></div></div>";var d=this.element;this.popup&&this.popup.destroy();var l=this.popup=t(s).appendTo(d).eq(0).on("click",h+o.button,(function(e){e.preventDefault(),l.close();var i=t(e.currentTarget).index();n[i].click()})).kendoWindow({modal:!0,autoFocus:!1,resizable:!1,draggable:!1,title:e.title,visible:!1,deactivate:function(){this.destroy(),d.trigger("focus")}}).getKendoWindow();l.center().open(),l.element.find(".k-button-solid-primary").trigger("focus")},_createPopupEditor:function(e,n){var o,s=this,a=this.options.messages,r=v.popup,d=i.format('<div {0}="{1}" class="{2} {3}"><div class="{4}">',i.attr("uid"),e.uid,r.form,r.editForm,r.formContainer),p=this.fields(k.desktop,e,n);d+=this._buildEditTemplate(e),d+='<div class="'+r.buttonsContainer+'">',d+=this.createButton({name:"update",text:a.save,className:v.primary}),d+=this.createButton({name:"cancel",text:a.cancel}),!1!==s.options.editable.destroy&&(d+=this.createButton({name:"delete",text:a.destroy})),d+="</div></div></div>";var f=this.container=t(d).appendTo(this.element).eq(0).kendoWindow(l({modal:!0,resizable:!1,draggable:!0,title:a.editor.editorTitle,visible:!1,close:function(t){t.userTriggered&&s.trigger("cancel",{container:f,model:e})&&t.preventDefault()}},{}));return this.renderForm?(this.form=f.find(".k-gantt-form").kendoForm({items:p,formData:e,buttonsTemplate:"",validatable:{validateOnBlur:!0}}).data("kendoForm"),o=this.form.editable):o=f.kendoEditable({model:e,clearContainer:!1,validateOnBlur:!0,target:s.options.target}).data("kendoEditable"),i.cycleForm(f),this.trigger("edit",{container:f,model:e})?s.trigger("cancel",{container:f,model:e}):(f.data("kendoWindow").center().open(),f.on(u+c,h+v.buttonCancel,(function(t){t.preventDefault(),t.stopPropagation(),s.trigger("cancel",{container:f,model:e})})),f.on(u+c,h+v.buttonSave,(function(t){t.preventDefault(),t.stopPropagation();for(var i,o=s.fields(k.desktop,e,n),a={},r=0,d=o.length;r<d;r++)a[i=o[r].field]=e.get(i);s.trigger("save",{container:f,model:e,updateInfo:a})})),f.on(u+c,h+v.buttonDelete,(function(t){t.preventDefault(),t.stopPropagation(),s.trigger("remove",{container:f,model:e})}))),o}}),x=a.extend({init:function(t,e){a.fn.init.call(this,t,e),this.wrapper=this.element,this.model=this.options.model,this.resourcesField=this.options.resourcesField,this.createButton=this.options.createButton,this._initContainer(),this._attachHandlers()},events:["save"],close:function(){this.window.bind("deactivate",this.destroy.bind(this)).close()},destroy:function(){this._dettachHandlers(),this.grid.destroy(),this.grid=null,this.window.destroy(),this.window=null,a.fn.destroy.call(this),i.destroy(this.wrapper),this.element=this.wrapper=null},open:function(){this.window.center().open()},_attachHandlers:function(){var e=this.grid,n=this._cancelProxy=this._cancel.bind(this);this.container.on(u+c,h+v.buttonCancel,this._cancelProxy),this._saveProxy=this._save.bind(this),this.container.on(u+c,h+v.buttonSave,this._saveProxy),this.window.bind("close",(function(t){t.userTriggered&&n(t)})),e.wrapper.on(u+c,"input[type='checkbox']",(function(){var n=t(this),o=t(n).closest("tr"),s=e.dataSource.getByUid(o.attr(i.attr("uid"))),a=t(n).is(":checked")?1:"";s.set("value",a)}))},_cancel:function(t){t.preventDefault(),this.close()},_createButtons:function(){for(var t=this.options.buttons,e='<div class="'+v.popup.buttonsContainer+'">',i=0,n=t.length;i<n;i++)e+=this.createButton(t[i]);e+="</div>",this.container.append(e)},_dettachHandlers:function(){this._cancelProxy=null,this._saveProxy=null,this.container.off(c),this.grid.wrapper.off()},_initContainer:function(){var e=this,n=v.popup,o=i.format('<div class="{0} {1}"><div class="{2} {3}"></div></div>"',n.form,n.editForm,n.formContainer,n.resourcesFormContainer);o=t(o),this.container=o.find(h+n.resourcesFormContainer),this.window=o.kendoWindow({modal:!0,resizable:!1,draggable:!0,visible:!1,title:this.options.messages.resourcesEditorTitle,open:function(){e.grid.resize(!0)}}).data("kendoWindow"),this._resourceGrid(),this._createButtons()},_resourceGrid:function(){var e=this.options.messages,n=t('<div id="resources-grid"/>').appendTo(this.container);this.grid=new i.ui.Grid(n,{columns:[{field:"name",title:e.resourcesHeader,template:"<label><input type='checkbox' value='#=name#'# if (value > 0 && value !== null) {#checked='checked'# } #/>#=name#</labe>"},{field:"value",title:e.unitsHeader,template:function(t){var e=t.format,n=null!==t.value?t.value:"";return e?i.toString(n,e):n}}],height:280,sortable:!0,editable:!0,filterable:!0,dataSource:{data:this.options.data,schema:{model:{id:"id",fields:{id:{from:"id"},name:{from:"name",type:"string",editable:!1},value:{from:"value",type:"number",validation:this.options.unitsValidation},format:{from:"format",type:"string"}}}}},save:function(t){var e=!!t.values.value;t.container.parent().find("input[type='checkbox']").prop("checked",e)}})},_save:function(t){t.preventDefault(),this._updateModel(),this.wrapper.is(h+v.popup.resourcesField)||this.trigger("save",{container:this.wrapper,model:this.model}),this.close()},_updateModel:function(){for(var t,e=[],i=this.grid.dataSource.data(),n=0,o=i.length;n<o;n++)null!==(t=i[n].get("value"))&&t>0&&e.push(i[n]);this.model[this.resourcesField]=e}});i.gantt={TaskDropDown:b,Editor:y,PopupEditor:w,ResourceEditor:x}}(window.kendo.jQuery);
//# sourceMappingURL=kendo.gantt.editors.js.map

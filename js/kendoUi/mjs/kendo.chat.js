/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.core.js";import"./kendo.draganddrop.js";!function(t,e){var s=window.kendo,n=s.ui.Widget,i=t.extend,o=".kendoChat",a=s.keys,r=n.extend({init:function(t,e){n.fn.init.call(this,t,e),this._wrapper(),this._attachEvents(),this._typing=!1},events:["focusToolbar","sendMessage","toggleToolbar","typingEnd","typingStart"],options:{messages:{placeholder:"Type a message...",toggleButton:"Toggle toolbar",sendButton:"Send message"}},destroy:function(){n.fn.destroy.call(this),this.input&&(this.input.off(o),this.input.remove(),this.input=null),this.element.off(o),this.element.empty()},_wrapper:function(){var e=r.styles,n=this.options,i=n.messages,o="inputId_"+s.guid();t("<label>").addClass(e.hidden).html(i.placeholder).attr("for",o).appendTo(this.element),this.inputWrapper=this.element.addClass(e.inputWrapper).appendTo(this.element),this.input=t("<input type='text'>").addClass(e.input).attr("id",o).attr("placeholder",i.placeholder).appendTo(this.inputWrapper),this.inputSuffix=t("<span></span>").addClass(e.inputSuffix).appendTo(this.inputWrapper),n.toolbar&&n.toolbar.toggleable&&n.toolbar.buttons&&n.toolbar.buttons.length&&t("<button>").addClass(e.button).addClass(e.buttonFlat).addClass(e.iconButton).addClass(e.buttonToggle).attr({type:"button",title:i.toggleButton,"aria-label":i.toggleButton,"aria-controls":n.toolbarId}).append(t('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g>   <path d="M128,240c0-26.4-21.6-48-48-48s-48,21.6-48,48s21.6,48,48,48S128,266.4,128,240z"/>   <path d="M192,240c0,26.4,21.6,48,48,48c26.4,0,48-21.6,48-48s-21.6-48-48-48C213.6,192,192,213.6,192,240z"/>   <path d="M352,240c0,26.4,21.6,48,48,48c26.4,0,48-21.6,48-48s-21.6-48-48-48C373.6,192,352,213.6,352,240z"/></g></svg>')).appendTo(this.inputSuffix),t("<button>").addClass(e.button).addClass(e.buttonFlat).addClass(e.iconButton).addClass(e.buttonSend).append(t('<svg version="1.1" ixmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve"><path d="M0,14.3c-0.1,0.6,0.3,0.8,0.8,0.6l14.8-6.5c0.5-0.2,0.5-0.6,0-0.8L0.8,1.1C0.3,0.9-0.1,1.1,0,1.7l0.7,4.2C0.8,6.5,1.4,7,1.9,7.1l8.8,0.8c0.6,0.1,0.6,0.1,0,0.2L1.9,8.9C1.4,9,0.8,9.5,0.7,10.1L0,14.3z"/></svg>')).appendTo(this.inputSuffix).attr("title",i.sendButton).attr("aria-label",i.sendButton)},_attachEvents:function(){var t=r.styles;this.input.on("keydown"+o,this._keydown.bind(this)).on("input"+o,this._input.bind(this)).on("focusout"+o,this._inputFocusout.bind(this)),this.element.on("click"+o,"."+t.buttonSend,this._buttonClick.bind(this)),this.element.on("click"+o,"."+t.buttonToggle,this._toggleToolbar.bind(this))},_input:function(){var t=this.input.val().length>0;this._triggerTyping(t)},_keydown:function(t){switch(t.keyCode){case a.ENTER:t.preventDefault(),this._sendMessage();break;case a.F10:t.preventDefault(),this.trigger("focusToolbar")}},_buttonClick:function(t){t.preventDefault(),this._sendMessage()},_sendMessage:function(){var t=this.input.val();if(t.length){this._triggerTyping(!1);var e={text:t};this.trigger("sendMessage",e),this.input.val("")}},_inputFocusout:function(){this._triggerTyping(!1)},_triggerTyping:function(t){t?this._typing||(this.trigger("typingStart",{}),this._typing=!0):this._typing&&(this.trigger("typingEnd",{}),this._typing=!1)},_toggleToolbar:function(t){this.trigger("toggleToolbar",{originalEvent:t})}});i(!0,r,{styles:{input:"k-input-inner",inputWrapper:"k-textbox k-input k-input-lg k-input-solid",button:"k-button",buttonFlat:"k-button-lg k-button-flat k-button-flat-base",iconButton:"k-icon-button",buttonIcon:"k-button-icon",buttonSend:"k-button-send",buttonToggle:"k-button-toggle",iconAdd:"k-icon k-i-add",hidden:"k-hidden",inputSuffix:"k-input-suffix"}}),i(s,{chat:{ChatMessageBox:r}})}(window.kendo.jQuery),function(t,e){var s=window.kendo,n=s.ui.Widget,i=t.extend,o=".kendoChat",a="kButtonName",r="chatToolbarScrollLeft",l="chatToolbarScrollRight",c=":visible",d="tabindex",u={effects:"expand:vertical",duration:200},h={expand:{show:!0},collapse:{hide:!0}},p=n.extend({init:function(t,e){var s=(e=i({},e,{name:"ChatToolbar"})).toolbar,a=s.buttons&&s.buttons.length;n.fn.init.call(this,t,e),a&&this._createButtonList(),a&&s.scrollable&&this.buttonsWidth()>this.element.width()&&this._initScrolling(),this._setupAnimation(),a&&s.toggleable&&this.toggle(!0),this.element.on("click"+o,this._onClick.bind(this)).on("keydown"+o,this._onKeydown.bind(this))},events:["click"],destroy:function(){n.fn.destroy.call(this),this.element.off(o),this.element.empty()},_createButtonList:function(){for(var e=p.styles,s=this.options.toolbar.buttons,n=t("<div class='"+e.buttonList+"'></div>"),i=0;i<s.length;i++){var o=this._createButton(s[i]);n.append(o)}n.appendTo(this.element),this.buttonList=n,this.buttons().first().removeAttr(d)},_createButton:function(e){var s,n=p.styles,i=t("<button>");return"string"==typeof e&&(e={name:e}),s=t.extend({},e.attr||{},{title:e.text||e.name,"aria-label":e.text||e.name,type:"button",tabindex:-1}),i.attr(s).addClass(e.name).data(a,e.name).addClass(n.button).addClass(n.buttonDefaults),e.iconClass&&(i.addClass(n.iconButton),i.prepend("<span class='k-button-icon "+e.iconClass+"'></span>")),i},_onClick:function(e){var s=p.styles,n=t(e.target).closest("."+s.button);n.is("."+s.scrollButton)&&!this._scrolling&&this._scroll(n.data(a)),n.data(a)&&(this.buttons().attr(d,-1),n.removeAttr(d),this.trigger("click",{button:n[0],name:n.data(a),originalEvent:e}))},_onKeydown:function(t){var e=t.keyCode,n=s.keys;switch(e){case n.LEFT:this._focusButton(-1);break;case n.RIGHT:this._focusButton(1)}},_focusButton:function(t){var e=this.buttons(),s=e.not("[tabindex=-1]"),n=e[s.index()+t];n&&(s.attr(d,-1),n.removeAttribute(d),n.focus())},_initScrolling:function(){var t=p.styles;this.scrollButtonLeft=this._createButton({name:r,iconClass:t.scrollButtonLeftIcon,attr:{class:t.scrollButton+" "+t.scrollButtonLeft}}),this.scrollButtonRight=this._createButton({name:l,iconClass:t.scrollButtonRightIcon,attr:{class:t.scrollButton+" "+t.scrollButtonRight}}),this.element.prepend(this.scrollButtonLeft),this.element.append(this.scrollButtonRight),this._refreshScrollButtons(),this.element.on("keydown"+o,this._refreshScrollButtons.bind(this))},_scroll:function(t){var e=this,n=e.buttonWidth(),i=this.maxScrollSize(),o=t===r?-1*n:n,a=this.currentScrollLeft()+o;a=Math.min(Math.max(a,0),i),t!==r&&t!==l||(s.scrollLeft(e.buttonList,a),e._refreshScrollButtons(a))},_refreshScrollButtons:function(t){var e=this.maxScrollSize(),s=undefined===t||isNaN(parseInt(t,10))?this.currentScrollLeft():t;(this.scrollButtonLeft||this.scrollButtonRight)&&(this.scrollButtonLeft.toggle(0!==s),this.scrollButtonRight.toggle(s!==e))},_setupAnimation:function(){var t=this.options.toolbar.animation,e=i({},u),s=i({reverse:!0,hide:!0},u);t=!1===t?i(!0,{},h):i(!0,{expand:e,collapse:s},t),this.options.toolbar.animation=t},_animationComplete:function(){this._refreshScrollButtons()},_animationCompleteExpand:function(){this._animationComplete(),this.buttons().not("[tabindex=-1]").trigger("focus")},currentScrollLeft:function(){return Math.round(s.scrollLeft(this.buttonList))},maxScrollSize:function(){return Math.round(this.buttonList[0].scrollWidth-this.buttonList[0].clientWidth)},buttons:function(){var t=p.styles;return this.buttonList?this.buttonList.children("."+t.button):null},buttonWidth:function(){return Math.round(this.buttons().last().outerWidth(!0))},buttonsWidth:function(){var t=0;return this.buttons()&&(t=this.buttonWidth()*this.buttons().length),t},toggle:function(t){var e=this.options.toolbar.animation;t&&(e=i(!0,{},h)),e.expand.complete=this._animationCompleteExpand.bind(this),e.collapse.complete=this._animationComplete.bind(this),this.element.is(c)?this.element.kendoStop().kendoAnimate(e.collapse):this.element.kendoStop().kendoAnimate(e.expand)},focus:function(){this.element.is(c)?this.buttons().not("[tabindex=-1]").trigger("focus"):this.toggle()}});i(!0,p,{styles:{button:"k-button",buttonDefaults:"k-button-md k-rounded-md k-button-solid k-button-solid-base",buttonList:"k-button-list",scrollButton:"k-scroll-button",scrollButtonLeft:"k-scroll-button-left",scrollButtonRight:"k-scroll-button-right",scrollButtonLeftIcon:"k-icon k-i-arrow-chevron-left",scrollButtonRightIcon:"k-icon k-i-arrow-chevron-right",iconButton:"k-icon-button"}}),i(s.chat,{ChatToolBar:p})}(window.kendo.jQuery),function(t,e){var s=window.kendo,n=s.ui.Widget,i=t.extend,o=".",a=".kendoChat",r=s.template('<div #:text# class="#=styles.messageGroup# #= url ? "" : styles.noAvatar #"><p class="#=styles.author#">#:text#</p># if (url) { #<img src="#=url#" alt="#:text#" class="#=styles.avatar#"># } #</div>'),l=s.template('<div me class="#=styles.messageGroup# #=styles.self# #= url ? "" : styles.noAvatar #"># if (url) { #<img src="#=url#" alt="#:text#" class="#=styles.avatar#"># } #</div>'),c=s.template('<div class="#=styles.message#"><time class="#=styles.messageTime#">#= kendo.toString(kendo.parseDate(timestamp), "HH:mm:ss") #</time><div class="#=styles.bubble#">#:text#</div></div>'),d=s.template('<div class="#=styles.messageListContent# #=styles.typingIndicatorBubble#"><p class="#=styles.author#">#:text#</p><div class="#=styles.message#"><div class="#=styles.bubble#"><div class="#=styles.typingIndicator#"><span></span><span></span><span></span></div></div></div></div>'),u=s.template('<div class="#=styles.suggestedActions#"># for (var i = 0; i < suggestedActions.length; i++) { #<span role="button" tabindex="0" class="#=styles.suggestedAction#" data-value="#:suggestedActions[i].value#">#:suggestedActions[i].title#</span># } #</div>'),h=s.template('<div class="#=styles.card# #=styles.cardRich#"># if (typeof images !== "undefined" && images.length > 0) { #<img src="#:images[0].url#" alt="#:images[0].alt#" class="#=styles.cardImage#" /># } #<div class="#=styles.cardBody#"># if (typeof title !== "undefined") { #<h5 class="#=styles.cardTitle#">#:title#</h5># } ## if (typeof subtitle !== "undefined") { #<h6 class="#=styles.cardSubtitle#">#:subtitle#</h6># } ## if (typeof text !== "undefined") { #<p>#:text#</p># } #</div># if (typeof buttons !== "undefined" && buttons.length > 0) { #<div class="#=styles.cardActions# #=styles.cardActionsVertical#"># for (var i = 0; i < buttons.length; i++) { #<span class="#=styles.cardAction#"><span class="#=styles.button# #=styles.buttonPrimary#" data-value="#:buttons[i].value#">#:buttons[i].title#</span></span># } #</div># } #</div>');i(s.chat,{Templates:{},Components:{}}),s.chat.registerTemplate=function(t,e){s.chat.Templates[t]=s.template(e)},s.chat.getTemplate=function(t){return s.chat.Templates[t]||c},s.chat.registerTemplate("text",c),s.chat.registerTemplate("message",c),s.chat.registerTemplate("typing",d),s.chat.registerTemplate("suggestedAction",u),s.chat.registerTemplate("heroCard",h),s.chat.registerTemplate("application/vnd.microsoft.card.hero",h),s.chat.registerComponent=function(t,e){s.chat.Components[t]=e},s.chat.getComponent=function(t){return s.chat.Components[t]||null};var p=s.chat.Component=s.Class.extend({init:function(e,s){this.element=t("<div></div>"),this.options=e,this.view=s},destroy:function(){s.destroy(this.element)}}),g=p.extend({init:function(t,e){p.fn.init.call(this,t,e),this.element.kendoCalendar({change:function(){e.trigger("suggestedAction",{text:s.toString(this.value(),"d"),type:"message"})}})},destroy:function(){}});s.chat.registerComponent("calendar",g);var b={wrapper:"k-widget k-chat",messageList:"k-avatars",messageListContent:"k-message-list-content",messageTime:"k-message-time",messageGroup:"k-message-group",message:"k-message",only:"k-only",first:"k-first",middle:"k-middle",last:"k-last",author:"k-author",avatar:"k-avatar",noAvatar:"k-no-avatar",self:"k-alt",button:"k-button",buttonDefaults:"k-button-md k-rounded-md k-button-solid k-button-solid-base",iconButton:"k-icon-button",buttonIcon:"k-button-icon",buttonPrimary:"k-button-md k-rounded-md k-button-flat k-button-flat-primary",scrollButtonIcon:"k-icon",scrollButtonIconLeft:"k-i-arrow-chevron-left",scrollButtonIconRight:"k-i-arrow-chevron-right",typingIndicator:"k-typing-indicator",typingIndicatorBubble:"k-typing-indicator-bubble",bubble:"k-bubble",suggestedActions:"k-quick-replies",suggestedAction:"k-quick-reply",cardWrapper:"k-card-container",cardDeckScrollWrap:"k-card-deck-scrollwrap",cardDeck:"k-card-deck",cardList:"k-card-list",card:"k-card",cardRich:"k-card-type-rich",cardBody:"k-card-body",cardImage:"k-card-image",cardTitle:"k-card-title",cardSubtitle:"k-card-subtitle",cardActions:"k-card-actions",cardActionsVertical:"k-card-actions-vertical",cardAction:"k-card-action",selected:"k-selected"},m=s.chat.ChatView=n.extend({init:function(t,e){n.fn.init.call(this,t,e),this._list(),this._lastSender=null,this.typingParticipants=[],this._attachEvents(),this._scrollable()},events:[],options:{messages:{isTyping:" is typing.",areTyping:" are typing.",and:" and "}},destroy:function(){n.fn.destroy.call(this),this._scrollDraggable&&this._scrollDraggable.destroy(),this.element.empty(),this.element.off(a),this.list=null,this._lastSender=null},_list:function(){var e=m.styles;this.element.addClass(e.messageList).attr("role","log").attr("aria-label",this.options.messages.messageListLabel),this.list=t("<div>").addClass(e.messageListContent).appendTo(this.element)},_attachEvents:function(){var t=m.styles;this.element.on("click"+a,this._listClick.bind(this)).on("click"+a,o+t.message,this._messageClick.bind(this)).on("click"+a,o+t.suggestedAction,this._suggestedActionClick.bind(this)).on("click"+a,o+t.cardAction+" "+o+t.button,this._cardActionClick.bind(this)),this.element.on("keydown"+a,o+t.suggestedAction,this._suggestedActionKeydown.bind(this))},_scrollable:function(){var t=m.styles;this.element.on("click"+a,o+t.cardDeckScrollWrap+" "+o+t.button,this._scrollButtonClick.bind(this))},_scrollButtonClick:function(e){var n=m.styles,i=t(e.currentTarget),a=0!==i.find(o+n.scrollButtonIconLeft).length,r=i.siblings(o+n.cardDeck),l=r.find(o+n.card).last().outerWidth(!0);a?s.scrollLeft(r,s.scrollLeft(r)-l):s.scrollLeft(r,s.scrollLeft(r)+l)},getTemplate:function(t){return s.chat.getTemplate(t)},getComponent:function(t){return s.chat.getComponent(t)},renderMessage:function(t,e){t.timestamp||(t.timestamp=new Date),t.text||(t.text="");var s=this._renderTemplate(t.type,t);this._renderBubble(t.type,s,e),"typing"==t.type?this.typingParticipants.length>0&&this._removeTypingParticipant(e):this._lastSender=e.id},renderSuggestedActions:function(t){this._removeSuggestedActions();var e=this._renderTemplate("suggestedAction",{suggestedActions:t});this.list.append(e),this._scrollToBottom()},renderAttachments:function(t){var e=this._renderAttachmentWrapper(t.attachmentLayout),s="carousel"===t.attachmentLayout?e.find(o+m.styles.cardDeck):e,n=t.attachments;if(n.length){for(var i=0;i<n.length;i++){var a=this._renderTemplate(n[i].contentType,n[i].content);s.append(a)}this._removeSuggestedActions(),this._removeTypingIndicator(),this.list.append(e),this._lastSender=null}},renderComponent:function(t){var e=new(this.getComponent(t))({},this);this.list.append(e.element),this._scrollToBottom()},_renderAttachmentWrapper:function(e){var s=m.styles,n=t("<div>");if("carousel"===e){n.addClass(s.cardDeckScrollWrap);var i=this._renderScrollButton(s.scrollButtonIconLeft);n.append(i),n.append(t("<div>").addClass(s.cardDeck));var o=this._renderScrollButton(s.scrollButtonIconRight);n.append(o)}else n.addClass(s.cardList);return n},_renderScrollButton:function(e){var s=m.styles;return t("<button>").addClass(s.button).addClass(s.buttonDefaults).addClass(s.iconButton).append(t("<span>").addClass(s.buttonIcon).addClass(s.scrollButtonIcon).addClass(e))},_removeSuggestedActions:function(){this.list.find(o+m.styles.suggestedActions).remove()},_listClick:function(e){var s=m.styles,n=t(e.target);n.hasClass(s.message)||n.parents(o+s.message).length||this._clearSelection()},_messageClick:function(e){this._clearSelection(),t(e.currentTarget).addClass(m.styles.selected)},_suggestedActionClick:function(e){var s=t(e.target).data("value")||"";this.trigger("actionClick",{text:s}),this._removeSuggestedActions()},_suggestedActionKeydown:function(t){t.keyCode!==s.keys.SPACEBAR&&t.keyCode!==s.keys.ENTER||this._suggestedActionClick(t)},_cardActionClick:function(e){var s=t(e.target).data("value")||"";this.trigger("actionClick",{text:s})},_renderBubble:function(t,e,s){this._removeSuggestedActions(),this._removeTypingIndicator();var n=this._getMessageGroup(s,t);this._appendToGroup(n,e,t),this._scrollToBottom()},_renderTemplate:function(e,s){var n,o=this.getComponent(e);if(o){n=new o(s,this).element}else{var a=this.getTemplate(e),r=i(!0,{},s,{styles:m.styles});n=t(a(r))}return n},_getMessageGroup:function(e,s){var n,i=m.styles,a=this._getMessageGroupTemplate(e,s),r="typing"==s?this.element:this.list;return e.id===this._lastSender&&null!==this._lastSender&&"typing"!==s&&(n=this.list.find(o+i.messageGroup).last()).length?n:t(a({text:e.name,url:e.iconUrl,styles:i})).appendTo(r)},_getMessageGroupTemplate:function(t,e){var s=t.id===this.options.user.id?l:r;return"typing"==e&&(s=d),s},_appendToGroup:function(t,e,s){var n=m.styles,i=t.find(o+n.message),a=i.length;this.element.find(o+n.typingIndicator).length&&"typing"==s||(e.addClass(0===a?n.only:n.last),i.filter(o+n.only).removeClass(n.only).addClass(n.first),i.filter(o+n.last).removeClass(n.last).addClass(n.middle),t.append(e))},_renderTypingIndicator:function(e){var s,n=this.element.find(o+b.typingIndicatorBubble);this._addTypingParticipant(e),n.length?(s=this._composeTypingParticipantsText(this.typingParticipants),n.find(o+b.author).first().text(s)):t(d({text:e.name+this.options.messages.isTyping,styles:b})).appendTo(this.element),this._scrollToBottom()},_addTypingParticipant:function(t){for(var e=!1,s=0;s<this.typingParticipants.length;s+=1)if(this.typingParticipants[s].id==t.id){e=!0;break}e||this.typingParticipants.push(t)},_removeTypingParticipant:function(t){var e,s=this.element.find(o+b.typingIndicatorBubble);if(s.length){for(var n=0;n<this.typingParticipants.length;n+=1)this.typingParticipants[n].id==t.id&&this.typingParticipants.splice(n,1);""===(e=this._composeTypingParticipantsText(this.typingParticipants))?s.remove():s.find(o+b.author).first().text(e)}},_composeTypingParticipantsText:function(t){var e=this.options.messages,s=1==t.length?e.isTyping:e.areTyping,n="";return 0===t.length?n:n=this.typingParticipants.map((function(t){return t.name})).join(", ").replace(/,(?!.*,)/gim,e.and.trimRight())+s},_removeTypingIndicator:function(){var t=this.element.find(o+b.typingIndicatorBubble);t.length&&(this.typingParticipants=[],t.remove())},_clearSelection:function(){var t=m.styles.selected;this.element.find(o+t).removeClass(t)},_scrollToBottom:function(){this.element.scrollTop(this.element.prop("scrollHeight"))}});i(!0,m,{styles:b})}(window.kendo.jQuery);var __meta__={id:"chat",name:"Chat",category:"web",description:"The Chat component.",depends:["core","draganddrop"]};!function(t,e){var s=window.kendo,n=s.ui.Widget,i=t.extend,o=".",a={wrapper:"k-widget k-chat",canvas:"k-chat-canvas",viewWrapper:"k-message-list",messageBoxWrapper:"k-message-box",toolbarBoxWrapper:"k-toolbar-box"},r=n.extend({init:function(t,e,i){n.fn.init.call(this,t,e),i&&(this._events=i),this._user(),this._wrapper(),this._view(),e&&e.toolbar&&e.toolbar.buttons&&this._toolbar(),this._messageBox(),s.notify(this)},events:["typingStart","typingEnd","post","sendMessage","actionClick","toolClick"],options:{user:{name:"User",iconUrl:""},name:"Chat",messages:{messageListLabel:"Message list",placeholder:"Type a message...",toggleButton:"Toggle toolbar",sendButton:"Send message"},toolbar:!1},setOptions:function(e){this._setEvents(e),t.extend(!0,this.options,e),this.toolbar&&"toolbar"in e&&(this.toolbar.destroy(),this.toolbar=null),this.messageBox&&(this.messageBox.unbind(),this.messageBox.destroy(),this.messageBox=null),this._messageBox(),"toolbar"in e&&(this._resetToolbarButtons(e),this._toolbar())},_resetToolbarButtons:function(t){var e=this.wrapper.find(o+a.toolbarBoxWrapper);e.is(":visible")||e.show(),t.toolbar&&"object"==typeof t.toolbar&&"buttons"in t.toolbar&&(this.options.toolbar.buttons=t.toolbar.buttons)},destroy:function(){this.view&&(this.view.unbind(),this.view.destroy(),this.view=null),this.messageBox&&(this.messageBox.unbind(),this.messageBox.destroy(),this.messageBox=null),this.toolbar&&(this.toolbar.destroy(),this.toolbar=null),n.fn.destroy.call(this)},_user:function(){this.options.user.id=s.guid()},getUser:function(){return i(!0,{},this.options.user)},_wrapper:function(){var t=r.styles,e=this.options,s=e.height,n=e.width,i="<div class='"+t.viewWrapper+"'></div><span class='"+t.messageBoxWrapper+"'></span><div class='"+t.toolbarBoxWrapper+"' role='toolbar' style='display:none;'></div>";this.wrapper=this.element.addClass(t.wrapper).append(i),e.toolbar&&e.toolbar.buttons&&e.toolbar.buttons.length&&this.wrapper.find(o+t.toolbarBoxWrapper).show(),s&&this.wrapper.height(s),n&&this.wrapper.css("max-width",n)},_view:function(){var t=this,e=r.styles,n=i(!0,{},this.options),a=this.wrapper.find(o+e.viewWrapper+"");this.view=new s.chat.ChatView(a,n),this.view.bind("actionClick",(function(e){t.trigger("actionClick",e),t.postMessage(e.text)}))},_messageBox:function(){var t=this,e=r.styles,n=i(!0,{},this.options),a=this.wrapper.find(o+e.messageBoxWrapper+"");this.messageBox=new s.chat.ChatMessageBox(a,n),this.messageBox.bind("typingStart",(function(e){t.trigger("typingStart",e)})).bind("typingEnd",(function(e){t.trigger("typingEnd",e)})).bind("sendMessage",(function(e){t.trigger("sendMessage",e),t.postMessage(e.text)})).bind("toggleToolbar",(function(){t.toggleToolbar()})).bind("focusToolbar",(function(){t.toolbar&&t.toolbar.focus()}))},_toolbar:function(){var t=this,n=r.styles,a=i(!0,{},t.options),l=t.wrapper.find(o+n.toolbarBoxWrapper+"");t.options.toolbarId=s.guid(),l.attr("id",t.options.toolbarId),a.toolbar.scrollable===e&&(this.options.toolbar.scrollable=a.toolbar.scrollable=!0),a.toolbar.toggleable===e&&(this.options.toolbar.toggleable=a.toolbar.toggleable=!1),t.toolbar=new s.chat.ChatToolBar(l,a),t.toolbar.bind("click",(function(e){t.trigger("toolClick",{sender:t,name:e.name,button:e.button,messageBox:t.messageBox.input[0],originalEvent:e.originalEvent})}))},postMessage:function(t){var e=i(!0,{},{text:t,type:"message",timestamp:new Date,from:this.getUser()});this.trigger("post",e),this.renderMessage(e,e.from)},renderMessage:function(t,e){this.view.renderMessage(t,e)},renderSuggestedActions:function(t){this.view.renderSuggestedActions(t)},renderAttachments:function(t,e){this.view.renderAttachments(t,e)},toggleToolbar:function(t){this.toolbar.toggle(t)},renderUserTypingIndicator:function(t){this.view._renderTypingIndicator(t)},clearUserTypingIndicator:function(t){this.view._removeTypingParticipant(t)},removeTypingIndicator:function(){this.view._removeTypingIndicator()}});s.ui.plugin(r),i(!0,r,{styles:a})}(window.kendo.jQuery);
//# sourceMappingURL=kendo.chat.js.map
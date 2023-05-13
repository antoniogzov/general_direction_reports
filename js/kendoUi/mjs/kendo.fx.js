/**
 * Kendo UI v2022.3.1109 (http://www.telerik.com/kendo-ui)
 * Copyright 2022 Progress Software Corporation and/or one of its subsidiaries or affiliates. All rights reserved.
 *
 * Kendo UI commercial licenses may be obtained at
 * http://www.telerik.com/purchase/license-agreement/kendo-ui-complete
 * If you do not own a commercial license, this file shall be governed by the trial license terms.
 */
import"./kendo.core.js";var __meta__={id:"fx",name:"Effects",category:"framework",description:"Required for animation effects in all Kendo UI widgets.",depends:["core"]};!function(t,e){var i=window.kendo,r=i.effects,n=t.each,s=t.extend,o=i.support,a=o.browser,c=o.transforms,l=o.transitions,d={scale:0,scalex:0,scaley:0,scale3d:0},u={translate:0,translatex:0,translatey:0,translate3d:0},f=void 0!==document.documentElement.style.zoom&&!c,p=/matrix3?d?\s*\(.*,\s*([\d\.\-]+)\w*?,\s*([\d\.\-]+)\w*?,\s*([\d\.\-]+)\w*?,\s*([\d\.\-]+)\w*?/i,h=/^(-?[\d\.\-]+)?[\w\s]*,?\s*(-?[\d\.\-]+)?[\w\s]*/i,v=/translatex?$/i,m=/(zoom|fade|expand)(\w+)/,x=/(zoom|fade|expand)/,_=/[xy]$/i,g=["perspective","rotate","rotatex","rotatey","rotatez","rotate3d","scale","scalex","scaley","scalez","scale3d","skew","skewx","skewy","translate","translatex","translatey","translatez","translate3d","matrix","matrix3d"],y=["rotate","scale","scalex","scaley","skew","skewx","skewy","translate","translatex","translatey","matrix"],b={rotate:"deg",scale:"",skew:"px",translate:"px"},k=c.css,w=Math.round,C="px",P="auto",T="width",N="height",z="hidden",E="origin",R="abortId",H="overflow",D="translate",O="position",F="completeCallback",I=k+"transition",S=k+"transform",q=k+"backface-visibility",A=k+"perspective",V="1500px",M="perspective(1500px)",$={left:{reverse:"right",property:"left",transition:"translatex",vertical:!1,modifier:-1},right:{reverse:"left",property:"left",transition:"translatex",vertical:!1,modifier:1},down:{reverse:"up",property:"top",transition:"translatey",vertical:!0,modifier:1},up:{reverse:"down",property:"top",transition:"translatey",vertical:!0,modifier:-1},top:{reverse:"bottom"},bottom:{reverse:"top"},in:{reverse:"out",modifier:-1},out:{reverse:"in",modifier:1},vertical:{reverse:"vertical"},horizontal:{reverse:"horizontal"}};if(i.directions=$,s(t.fn,{kendoStop:function(t,e){return l?r.stopQueue(this,t||!1,e||!1):this.stop(t,e)}}),c&&!l){n(y,(function(e,i){t.fn[i]=function(e){if(void 0===e)return B(this,i);var r=t(this)[0],n=i+"("+e+b[i.replace(_,"")]+")";return-1==r.style.cssText.indexOf(S)?t(this).css(S,n):r.style.cssText=r.style.cssText.replace(new RegExp(i+"\\(.*?\\)","i"),n),this},t.fx.step[i]=function(e){t(e.elem)[i](e.now)}}));var L=t.fx.prototype.cur;t.fx.prototype.cur=function(){return-1!=y.indexOf(this.prop)?parseFloat(t(this.elem)[this.prop]()):L.apply(this,arguments)}}function j(t){return parseInt(t,10)}function Q(t,e){return j(t.css(e))}function W(t){for(var e in t)-1!=g.indexOf(e)&&-1==y.indexOf(e)&&delete t[e];return t}function U(t,e){var i,r,n,s,a=[],l={};for(r in e)i=r.toLowerCase(),s=c&&-1!=g.indexOf(i),!o.hasHW3D&&s&&-1==y.indexOf(i)?delete e[r]:(n=e[r],s?a.push(r+"("+n+")"):l[r]=n);return a.length&&(l[S]=a.join(" ")),l}function B(t,e){if(c){var i=t.css(S);if("none"==i)return"scale"==e?1:0;var r=i.match(new RegExp(e+"\\s*\\(([\\d\\w\\.]+)")),n=0;return r?n=j(r[1]):(r=i.match(p)||[0,0,0,0,0],e=e.toLowerCase(),v.test(e)?n=parseFloat(r[3]/r[2]):"translatey"==e?n=parseFloat(r[4]/r[2]):"scale"==e?n=parseFloat(r[2]):"rotate"==e&&(n=parseFloat(Math.atan2(r[2],r[1])))),n}return parseFloat(t.css(e))}i.toggleClass=function(t,e,i,r){return e&&(e=e.split(" "),l&&(i=s({exclusive:"all",duration:400,ease:"ease-out"},i),t.css(I,i.exclusive+" "+i.duration+"ms "+i.ease),setTimeout((function(){t.css(I,"").css(N)}),i.duration)),n(e,(function(e,i){t.toggleClass(i,r)}))),t},i.parseEffects=function(t,e){var i={};return"string"==typeof t?n(t.split(" "),(function(t,r){var n=!x.test(r),s=r.replace(m,(function(t,e,i){return e+":"+i.toLowerCase()})).split(":"),o=s[1],a={};s.length>1&&(a.direction=e&&n?$[o].reverse:o),i[s[0]]=a})):n(t,(function(t){var r=this.direction;r&&e&&!x.test(t)&&(this.direction=$[r].reverse),i[t]=this})),i},l&&s(r,{transition:function(e,i,r){var n,o,a=0,c=e.data("keys")||[];r=s({duration:200,ease:"ease-out",complete:null,exclusive:"all"},r);var d=!1,u=function(){d||(d=!0,o&&(clearTimeout(o),o=null),e.removeData(R).dequeue().css(I,"").css(I),r.complete.call(e))};r.duration=t.fx&&t.fx.speeds[r.duration]||r.duration,n=U(0,i),t.merge(c,function(t){var e=[];for(var i in t)e.push(i);return e}(n)),t.hasOwnProperty("uniqueSort")?e.data("keys",t.uniqueSort(c)).height():e.data("keys",t.unique(c)).height(),e.css(I,r.exclusive+" "+r.duration+"ms "+r.ease).css(I),e.css(n).css(S),l.event&&(e.one(l.event,u),0!==r.duration&&(a=500)),o=setTimeout(u,r.duration+a),e.data(R,o),e.data(F,u)},stopQueue:function(t,e,r){var n,s=t.data("keys"),o=!r&&s,a=t.data(F);return o&&(n=i.getComputedStyles(t[0],s)),a&&a(),o&&t.css(n),t.removeData("keys").stop(e)}});var K=i.Class.extend({init:function(t,e){var i=this;i.element=t,i.effects=[],i.options=e,i.restore=[]},run:function(e){var i,n,o,a,d,u,f=this,p=e.length,h=f.element,v=f.options,m=t.Deferred(),x={},_={};for(f.effects=e,m.done(f.complete.bind(f)),h.data("animating",!0),n=0;n<p;n++)for((i=e[n]).setReverse(v.reverse),i.setOptions(v),f.addRestoreProperties(i.restore),i.prepare(x,_),o=0,u=(d=i.children()).length;o<u;o++)d[o].duration(v.duration).run();for(var g in v.effects)s(_,v.effects[g].properties);for(h.is(":visible")||s(x,{display:h.data("olddisplay")||"block"}),c&&!v.reset&&(a=h.data("targetTransform"))&&(x=s(a,x)),x=U(0,x),c&&!l&&(x=W(x)),h.css(x).css(S),n=0;n<p;n++)e[n].setup();return v.init&&v.init(),h.data("targetTransform",_),r.animate(h,_,s({},v,{complete:m.resolve})),m.promise()},stop:function(){t(this.element).kendoStop(!0,!0)},addRestoreProperties:function(t){for(var e,i=this.element,r=0,n=t.length;r<n;r++)e=t[r],this.restore.push(e),i.data(e)||i.data(e,i.css(e))},restoreCallback:function(){for(var t=this.element,e=0,i=this.restore.length;e<i;e++){var r=this.restore[e];t.css(r,t.data(r))}},complete:function(){var t=this,e=0,i=t.element,r=t.options,n=t.effects,s=n.length;for(i.removeData("animating").dequeue(),r.hide&&i.data("olddisplay",i.css("display")).hide(),this.restoreCallback(),f&&!c&&setTimeout(this.restoreCallback.bind(this),0);e<s;e++)n[e].teardown();r.completeCallback&&r.completeCallback(i)}});r.promise=function(t,e){var n,s,o=[],a=new K(t,e),c=i.parseEffects(e.effects);for(var l in e.effects=c,c)(n=r[J(l)])&&(s=new n(t,c[l].direction),o.push(s));o[0]?a.run(o):(t.is(":visible")||t.css({display:t.data("olddisplay")||"block"}).css("display"),e.init&&e.init(),t.dequeue(),a.complete())},s(r,{animate:function(i,o,f){var p=!1!==f.transition;delete f.transition,l&&"transition"in r&&p?r.transition(i,o,f):c?i.animate(W(o),{queue:!1,show:!1,hide:!1,duration:f.duration,complete:f.complete}):i.each((function(){var i=t(this),r={};n(g,(function(t,n){var a,l=o?o[n]+" ":null;if(l){var f=o;if(n in d&&o[n]!==e)a=l.match(h),c&&s(f,{scale:+a[0]});else if(n in u&&o[n]!==e){var p=i.css(O),v="absolute"==p||"fixed"==p;i.data(D)||(v?i.data(D,{top:Q(i,"top")||0,left:Q(i,"left")||0,bottom:Q(i,"bottom"),right:Q(i,"right")}):i.data(D,{top:Q(i,"marginTop")||0,left:Q(i,"marginLeft")||0}));var m=i.data(D);if(a=l.match(h)){var x="translatey"==n?0:+a[1],_="translatey"==n?+a[1]:+a[2];v?(isNaN(m.right)?isNaN(x)||s(f,{left:m.left+x}):isNaN(x)||s(f,{right:m.right-x}),isNaN(m.bottom)?isNaN(_)||s(f,{top:m.top+_}):isNaN(_)||s(f,{bottom:m.bottom-_})):(isNaN(x)||s(f,{marginLeft:m.left+x}),isNaN(_)||s(f,{marginTop:m.top+_}))}}!c&&"scale"!=n&&n in f&&delete f[n],f&&s(r,f)}})),a.msie&&delete r.scale,i.animate(r,{queue:!1,show:!1,hide:!1,duration:f.duration,complete:f.complete})}))}}),r.animatedPromise=r.promise;var G=i.Class.extend({init:function(t,e){var i=this;i.element=t,i._direction=e,i.options={},i._additionalEffects=[],i.restore||(i.restore=[])},reverse:function(){return this._reverse=!0,this.run()},play:function(){return this._reverse=!1,this.run()},add:function(t){return this._additionalEffects.push(t),this},direction:function(t){return this._direction=t,this},duration:function(t){return this._duration=t,this},compositeRun:function(){var t=this,e=new K(t.element,{reverse:t._reverse,duration:t._duration}),i=t._additionalEffects.concat([t]);return e.run(i)},run:function(){if(this._additionalEffects&&this._additionalEffects[0])return this.compositeRun();var e,i,n=this,o=n.element,a=0,d=n.restore,u=d.length,f=t.Deferred(),p={},h={},v=n.children(),m=v.length;for(f.done(n._complete.bind(n)),o.data("animating",!0),a=0;a<u;a++)e=d[a],o.data(e)||o.data(e,o.css(e));for(a=0;a<m;a++)v[a].duration(n._duration).run();return n.prepare(p,h),o.is(":visible")||s(p,{display:o.data("olddisplay")||"block"}),c&&(i=o.data("targetTransform"))&&(p=s(i,p)),p=U(0,p),c&&!l&&(p=W(p)),o.css(p).css(S),n.setup(),o.data("targetTransform",h),r.animate(o,h,{duration:n._duration,complete:f.resolve}),f.promise()},stop:function(){var e=0,i=this.children(),r=i.length;for(e=0;e<r;e++)i[e].stop();return t(this.element).kendoStop(!0,!0),this},restoreCallback:function(){for(var t=this.element,e=0,i=this.restore.length;e<i;e++){var r=this.restore[e];t.css(r,t.data(r))}},_complete:function(){var t=this,e=t.element;e.removeData("animating").dequeue(),t.restoreCallback(),t.shouldHide()&&e.data("olddisplay",e.css("display")).hide(),f&&!c&&setTimeout(t.restoreCallback.bind(t),0),t.teardown()},setOptions:function(t){s(!0,this.options,t)},children:function(){return[]},shouldHide:t.noop,setup:t.noop,prepare:t.noop,teardown:t.noop,directions:[],setReverse:function(t){return this._reverse=t,this}});function J(t){return t.charAt(0).toUpperCase()+t.substring(1)}function X(t,e){var i=G.extend(e),s=i.prototype.directions;r[J(t)]=i,r.Element.prototype[t]=function(t,e,r,n){return new i(this.element,t,e,r,n)},n(s,(function(e,n){r.Element.prototype[t+J(n)]=function(t,e,r){return new i(this.element,n,t,e,r)}}))}var Y=["left","right","up","down"],Z=["in","out"];function tt(t,e,i,r){X(t,{directions:Z,startValue:function(t){return this._startValue=t,this},endValue:function(t){return this._endValue=t,this},shouldHide:function(){return this._shouldHide},prepare:function(t,n){var s,o,a="out"===this._direction,c=this.element.data(e);s=!(isNaN(c)||c==i)?c:void 0!==this._startValue?this._startValue:a?i:r,o=void 0!==this._endValue?this._endValue:a?r:i,this._reverse?(t[e]=o,n[e]=s):(t[e]=s,n[e]=o),this._shouldHide=n[e]===r}})}X("slideIn",{directions:Y,divisor:function(t){return this.options.divisor=t,this},prepare:function(t,e){var r,n=this,s=n.element,o=i._outerWidth,a=i._outerHeight,l=$[n._direction],d=-l.modifier*(l.vertical?a(s):o(s))/(n.options&&n.options.divisor||1)+C;n._reverse&&(r=t,t=e,e=r),c?(t[l.transition]=d,e[l.transition]="0px"):(t[l.property]=d,e[l.property]="0px")}}),X("tile",{directions:Y,init:function(t,e,i){G.prototype.init.call(this,t,e),this.options={previous:i}},previousDivisor:function(t){return this.options.previousDivisor=t,this},children:function(){var t=this,e=t._reverse,r=t.options.previous,n=t.options.previousDivisor||1,s=t._direction,o=[i.fx(t.element).slideIn(s).setReverse(e)];return r&&o.push(i.fx(r).slideIn($[s].reverse).divisor(n).setReverse(!e)),o}}),tt("fade","opacity",1,0),tt("zoom","scale",1,.01),X("slideMargin",{prepare:function(t,e){var i,r=this,n=r.element,s=r.options,o=n.data(E),a=s.offset,c=r._reverse;c||null!==o||n.data(E,parseFloat(n.css("margin-"+s.axis))),i=n.data(E)||0,e["margin-"+s.axis]=c?i:i+a}}),X("slideTo",{prepare:function(t,e){var i=this,r=i.element,n=i.options.offset.split(","),s=i._reverse;c?(e.translatex=s?0:n[0],e.translatey=s?0:n[1]):(e.left=s?0:n[0],e.top=s?0:n[1]),r.css("left")}}),X("expand",{directions:["horizontal","vertical"],restore:[H],prepare:function(t,i){var r=this,n=r.element,s=r.options,o=r._reverse,a="vertical"===r._direction?N:T,c=n[0].style[a],l=n.data(a),d=parseFloat(l||c),u=w(n.css(a,P)[a]());t.overflow=z,d=s&&s.reset?u||d:d||u,i[a]=(o?0:d)+C,t[a]=(o?d:0)+C,l===e&&n.data(a,c)},shouldHide:function(){return this._reverse},teardown:function(){var t=this.element,e="vertical"===this._direction?N:T,i=t.data(e);i!=P&&""!==i||setTimeout((function(){t.css(e,P).css(e)}),0)}});var et={position:"absolute",marginLeft:0,marginTop:0,scale:1};X("transfer",{init:function(t,e){this.element=t,this.options={target:e},this.restore=[]},setup:function(){this.element.appendTo(document.body)},prepare:function(t,e){var i=this,n=i.element,o=r.box(n),a=r.box(i.options.target),c=B(n,"scale"),l=r.fillScale(a,o),d=r.transformOrigin(a,o);s(t,et),e.scale=1,n.css(S,"scale(1)").css(S),n.css(S,"scale("+c+")"),t.top=o.top,t.left=o.left,t.transformOrigin=d.x+C+" "+d.y+C,i._reverse?t.scale=l:e.scale=l}});var it={top:"rect(auto auto $size auto)",bottom:"rect($size auto auto auto)",left:"rect(auto $size auto auto)",right:"rect(auto auto auto $size)"},rt={top:{start:"rotatex(0deg)",end:"rotatex(180deg)"},bottom:{start:"rotatex(-180deg)",end:"rotatex(0deg)"},left:{start:"rotatey(0deg)",end:"rotatey(-180deg)"},right:{start:"rotatey(180deg)",end:"rotatey(0deg)"}};function nt(t,e){var r=t[i.directions[e].vertical?N:T]()/2+"px";return it[e].replace("$size",r)}X("turningPage",{directions:Y,init:function(t,e,i){G.prototype.init.call(this,t,e),this._container=i},prepare:function(t,e){var r=this,n=r._reverse,s=n?$[r._direction].reverse:r._direction,o=rt[s];t.zIndex=1,r._clipInHalf&&(t.clip=nt(r._container,i.directions[s].reverse)),t[q]=z,e[S]=M+(n?o.start:o.end),t[S]=M+(n?o.end:o.start)},setup:function(){this._container.append(this.element)},face:function(t){return this._face=t,this},shouldHide:function(){var t=this._reverse,e=this._face;return t&&!e||!t&&e},clipInHalf:function(t){return this._clipInHalf=t,this},temporary:function(){return this.element.addClass("temp-page"),this}}),X("staticPage",{directions:Y,init:function(t,e,i){G.prototype.init.call(this,t,e),this._container=i},restore:["clip"],prepare:function(t,e){var i=this,r=i._reverse?$[i._direction].reverse:i._direction;t.clip=nt(i._container,r),t.opacity=.999,e.opacity=1},shouldHide:function(){var t=this._reverse,e=this._face;return t&&!e||!t&&e},face:function(t){return this._face=t,this}}),X("pageturn",{directions:["horizontal","vertical"],init:function(t,e,i,r){G.prototype.init.call(this,t,e),this.options={},this.options.face=i,this.options.back=r},children:function(){var t,e=this,r=e.options,n="horizontal"===e._direction?"left":"top",s=i.directions[n].reverse,o=e._reverse,a=r.face.clone(!0).removeAttr("id"),c=r.back.clone(!0).removeAttr("id"),l=e.element;return o&&(t=n,n=s,s=t),[i.fx(r.face).staticPage(n,l).face(!0).setReverse(o),i.fx(r.back).staticPage(s,l).setReverse(o),i.fx(a).turningPage(n,l).face(!0).clipInHalf(!0).temporary().setReverse(o),i.fx(c).turningPage(s,l).clipInHalf(!0).temporary().setReverse(o)]},prepare:function(t,e){t[A]=V,t.transformStyle="preserve-3d",t.opacity=.999,e.opacity=1},teardown:function(){this.element.find(".temp-page").remove()}}),X("flip",{directions:["horizontal","vertical"],init:function(t,e,i,r){G.prototype.init.call(this,t,e),this.options={},this.options.face=i,this.options.back=r},children:function(){var t,e=this,r=e.options,n="horizontal"===e._direction?"left":"top",s=i.directions[n].reverse,o=e._reverse,a=e.element;return o&&(t=n,n=s,s=t),[i.fx(r.face).turningPage(n,a).face(!0).setReverse(o),i.fx(r.back).turningPage(s,a).setReverse(o)]},prepare:function(t){t[A]=V,t.transformStyle="preserve-3d"}});var st=!o.mobileOS.android;X("replace",{_before:t.noop,_after:t.noop,init:function(e,i,r){G.prototype.init.call(this,e),this._previous=t(i),this._transitionClass=r},duration:function(){throw new Error("The replace effect does not support duration setting; the effect duration may be customized through the transition class rule")},beforeTransition:function(t){return this._before=t,this},afterTransition:function(t){return this._after=t,this},_both:function(){return t().add(this._element).add(this._previous)},_containerClass:function(){var t=this._direction,e="k-fx k-fx-start k-fx-"+this._transitionClass;return t&&(e+=" k-fx-"+t),this._reverse&&(e+=" k-fx-reverse"),e},complete:function(e){if(!(!this.deferred||e&&t(e.target).is(".km-touch-scrollbar, .km-actionsheet-wrapper"))){var i=this.container;i.removeClass("k-fx-end").removeClass(this._containerClass()).off(l.event,this.completeProxy),this._previous.hide().removeClass("k-fx-current"),this.element.removeClass("k-fx-next"),st&&i.css(H,""),this.isAbsolute||this._both().css(O,""),this.deferred.resolve(),delete this.deferred}},run:function(){if(this._additionalEffects&&this._additionalEffects[0])return this.compositeRun();var e=this,r=e.element,n=e._previous,s=r.parents().filter(n.parents()).first(),o=e._both(),a=t.Deferred(),c=r.css(O);return s.length||(s=r.parent()),this.container=s,this.deferred=a,this.isAbsolute="absolute"==c,this.isAbsolute||o.css(O,"absolute"),st&&(s.css(H),s.css(H,"hidden")),l?(r.addClass("k-fx-hidden"),s.addClass(this._containerClass()),this.completeProxy=this.complete.bind(this),s.on(l.event,this.completeProxy),i.animationFrame((function(){r.removeClass("k-fx-hidden").addClass("k-fx-next"),n.css("display","").addClass("k-fx-current"),e._before(n,r),i.animationFrame((function(){s.removeClass("k-fx-start").addClass("k-fx-end"),e._after(n,r)}))}))):this.complete(),a.promise()},stop:function(){this.complete()}});var ot=i.Class.extend({init:function(){var t=this;t._tickProxy=t._tick.bind(t),t._started=!1},tick:t.noop,done:t.noop,onEnd:t.noop,onCancel:t.noop,start:function(){this.enabled()&&(this.done()?this.onEnd():(this._started=!0,i.animationFrame(this._tickProxy)))},enabled:function(){return!0},cancel:function(){this._started=!1,this.onCancel()},_tick:function(){var t=this;t._started&&(t.tick(),t.done()?(t._started=!1,t.onEnd()):i.animationFrame(t._tickProxy))}}),at=ot.extend({init:function(t){s(this,t),ot.fn.init.call(this)},done:function(){return this.timePassed()>=this.duration},timePassed:function(){return Math.min(this.duration,new Date-this.startDate)},moveTo:function(t){var e=this,i=e.movable;e.initial=i[e.axis],e.delta=t.location-e.initial,e.duration="number"==typeof t.duration?t.duration:300,e.tick=e._easeProxy(t.ease),e.startDate=new Date,e.start()},_easeProxy:function(t){var e=this;return function(){e.movable.moveAxis(e.axis,t(e.timePassed(),e.initial,e.delta,e.duration))}}});s(at,{easeOutExpo:function(t,e,i,r){return t==r?e+i:i*(1-Math.pow(2,-10*t/r))+e},easeOutBack:function(t,e,i,r,n){return i*((t=t/r-1)*t*(((n=1.70158)+1)*t+n)+1)+e}}),r.Animation=ot,r.Transition=at,r.createEffect=X,r.box=function(e){var r=(e=t(e)).offset();return r.width=i._outerWidth(e),r.height=i._outerHeight(e),r},r.transformOrigin=function(t,e){var i=(t.left-e.left)*e.width/(e.width-t.width),r=(t.top-e.top)*e.height/(e.height-t.height);return{x:isNaN(i)?0:i,y:isNaN(r)?0:r}},r.fillScale=function(t,e){return Math.min(t.width/e.width,t.height/e.height)},r.fitScale=function(t,e){return Math.max(t.width/e.width,t.height/e.height)}}(window.kendo.jQuery);
//# sourceMappingURL=kendo.fx.js.map

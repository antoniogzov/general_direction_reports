(function(h){"function"===typeof define&&define.amd?define(["jquery","datatables.net","datatables.net-buttons"],function(f){return h(f,window,document)}):"object"===typeof exports?module.exports=function(f,j,v,q){f||(f=window);if(!j||!j.fn.dataTable)j=require("datatables.net")(f,j).$;j.fn.dataTable.Buttons||require("datatables.net-buttons")(f,j);return h(j,f,f.document,v,q)}:h(jQuery,window,document)})(function(h,f,j,v,q,r){function w(a){for(var c="";0<=a;)c=String.fromCharCode(a%26+65)+c,a=Math.floor(a/
26)-1;return c}function A(a,c){x===r&&(x=-1===z.serializeToString((new f.DOMParser).parseFromString(B["xl/worksheets/sheet1.xml"],"text/xml")).indexOf("xmlns:r"));h.each(c,function(c,b){if(h.isPlainObject(b)){var e=a.folder(c);A(e,b)}else{if(x){var e=b.childNodes[0],g,k,i=[];for(g=e.attributes.length-1;0<=g;g--){k=e.attributes[g].nodeName;var f=e.attributes[g].nodeValue;-1!==k.indexOf(":")&&(i.push({name:k,value:f}),e.removeAttribute(k))}g=0;for(k=i.length;g<k;g++)f=b.createAttribute(i[g].name.replace(":",
"_dt_b_namespace_token_")),f.value=i[g].value,e.setAttributeNode(f)}e=z.serializeToString(b);x&&(-1===e.indexOf("<?xml")&&(e='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'+e),e=e.replace(/_dt_b_namespace_token_/g,":"),e=e.replace(/xmlns:NS[\d]+="" NS[\d]+:/g,""));e=e.replace(/<([^<>]*?) xmlns=""([^<>]*?)>/g,"<$1 $2>");a.file(c,e)}})}function o(a,c,d){var b=a.createElement(c);d&&(d.attr&&h(b).attr(d.attr),d.children&&h.each(d.children,function(a,c){b.appendChild(c)}),null!==d.text&&d.text!==
r&&b.appendChild(a.createTextNode(d.text)));return b}function L(a,c){var d=a.header[c].length,b;a.footer&&a.footer[c].length>d&&(d=a.footer[c].length);for(var e=0,g=a.body.length;e<g;e++)if(b=a.body[e][c],b=null!==b&&b!==r?b.toString():"",-1!==b.indexOf("\n")?(b=b.split("\n"),b.sort(function(a,b){return b.length-a.length}),b=b[0].length):b=b.length,b>d&&(d=b),40<d)return 54;d*=1.35;return 6<d?d:6}var p=h.fn.dataTable;p.Buttons.pdfMake=function(a){if(!a)return q||f.pdfMake;q=a};p.Buttons.jszip=function(a){if(!a)return v||
f.JSZip;v=a};var u;var l="undefined"!==typeof self&&self||"undefined"!==typeof f&&f||this.content;if("undefined"===typeof l||"undefined"!==typeof navigator&&/MSIE [1-9]\./.test(navigator.userAgent))u=void 0;else{var y=l.document.createElementNS("http://www.w3.org/1999/xhtml","a"),M="download"in y,N=/constructor/i.test(l.HTMLElement)||l.safari,C=/CriOS\/[\d]+/.test(navigator.userAgent),O=function(a){(l.setImmediate||l.setTimeout)(function(){throw a;},0)},D=function(a){setTimeout(function(){"string"===
typeof a?(l.URL||l.webkitURL||l).revokeObjectURL(a):a.remove()},4E4)},E=function(a){return/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type)?new Blob([String.fromCharCode(65279),a],{type:a.type}):a},F=function(a,c,d){d||(a=E(a));var b=this,d="application/octet-stream"===a.type,e,g=function(){for(var a=["writestart","progress","write","writeend"],a=[].concat(a),c=a.length;c--;){var d=b["on"+a[c]];if("function"===typeof d)try{d.call(b,b)}catch(e){O(e)}}};b.readyState=
b.INIT;if(M)e=(l.URL||l.webkitURL||l).createObjectURL(a),setTimeout(function(){y.href=e;y.download=c;var a=new MouseEvent("click");y.dispatchEvent(a);g();D(e);b.readyState=b.DONE});else if((C||d&&N)&&l.FileReader){var k=new FileReader;k.onloadend=function(){var a=C?k.result:k.result.replace(/^data:[^;]*;/,"data:attachment/file;");l.open(a,"_blank")||(l.location.href=a);b.readyState=b.DONE;g()};k.readAsDataURL(a);b.readyState=b.INIT}else e||(e=(l.URL||l.webkitURL||l).createObjectURL(a)),d?l.location.href=
e:l.open(e,"_blank")||(l.location.href=e),b.readyState=b.DONE,g(),D(e)},n=F.prototype;"undefined"!==typeof navigator&&navigator.msSaveOrOpenBlob?u=function(a,c,d){c=c||a.name||"download";d||(a=E(a));return navigator.msSaveOrOpenBlob(a,c)}:(n.abort=function(){},n.readyState=n.INIT=0,n.WRITING=1,n.DONE=2,n.error=n.onwritestart=n.onprogress=n.onwrite=n.onabort=n.onerror=n.onwriteend=null,u=function(a,c,d){return new F(a,c||a.name||"download",d)})}p.fileSave=u;var G=function(a){var c="Sheet1";a.sheetName&&
(c=a.sheetName.replace(/[\[\]\*\/\\\?\:]/g,""));return c},H=function(a){return a.newline?a.newline:navigator.userAgent.match(/Windows/)?"\r\n":"\n"},I=function(a,c){for(var d=H(c),b=a.buttons.exportData(c.exportOptions),e=c.fieldBoundary,g=c.fieldSeparator,k=RegExp(e,"g"),i=c.escapeChar!==r?c.escapeChar:"\\",h=function(a){for(var b="",c=0,d=a.length;c<d;c++)0<c&&(b+=g),b+=e?e+(""+a[c]).replace(k,i+e)+e:a[c];return b},f=c.header?h(b.header)+d:"",l=c.footer&&b.footer?d+h(b.footer):"",j=[],m=0,o=b.body.length;m<
o;m++)j.push(h(b.body[m]));return{str:f+j.join(d)+l,rows:j.length}},J=function(){if(!(-1!==navigator.userAgent.indexOf("Safari")&&-1===navigator.userAgent.indexOf("Chrome")&&-1===navigator.userAgent.indexOf("Opera")))return!1;var a=navigator.userAgent.match(/AppleWebKit\/(\d+\.\d+)/);return a&&1<a.length&&603.1>1*a[1]?!0:!1};try{var z=new XMLSerializer,x}catch(P){}var B={"_rels/.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
"xl/_rels/workbook.xml.rels":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',"[Content_Types].xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
"xl/workbook.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets><definedNames/></workbook>',
"xl/worksheets/sheet1.xml":'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/><mergeCells count="0"/></worksheet>',"xl/styles.xml":'<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><numFmts count="6"><numFmt numFmtId="164" formatCode="#,##0.00_- [$$-45C]"/><numFmt numFmtId="165" formatCode="&quot;£&quot;#,##0.00"/><numFmt numFmtId="166" formatCode="[$€-2] #,##0.00"/><numFmt numFmtId="167" formatCode="0.0%"/><numFmt numFmtId="168" formatCode="#,##0;(#,##0)"/><numFmt numFmtId="169" formatCode="#,##0.00;(#,##0.00)"/></numFmts><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="68"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf><xf numFmtId="9"   fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="164" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="165" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="166" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="167" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="168" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="169" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="3" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="4" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="1" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="2" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="14" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>'},
K=[{match:/^\-?\d+\.\d%$/,style:60,fmt:function(a){return a/100}},{match:/^\-?\d+\.?\d*%$/,style:56,fmt:function(a){return a/100}},{match:/^\-?\$[\d,]+.?\d*$/,style:57},{match:/^\-?£[\d,]+.?\d*$/,style:58},{match:/^\-?€[\d,]+.?\d*$/,style:59},{match:/^\-?\d+$/,style:65},{match:/^\-?\d+\.\d{2}$/,style:66},{match:/^\([\d,]+\)$/,style:61,fmt:function(a){return-1*a.replace(/[\(\)]/g,"")}},{match:/^\([\d,]+\.\d{2}\)$/,style:62,fmt:function(a){return-1*a.replace(/[\(\)]/g,"")}},{match:/^\-?[\d,]+$/,style:63},
{match:/^\-?[\d,]+\.\d{2}$/,style:64},{match:/^[\d]{4}\-[\d]{2}\-[\d]{2}$/,style:67,fmt:function(a){return Math.round(25569+Date.parse(a)/864E5)}}];p.ext.buttons.copyHtml5={className:"buttons-copy buttons-html5",text:function(a){return a.i18n("buttons.copy","Copy")},action:function(a,c,d,b){this.processing(!0);var e=this,a=I(c,b),g=c.buttons.exportInfo(b),k=H(b),i=a.str,d=h("<div/>").css({height:1,width:1,overflow:"hidden",position:"fixed",top:0,left:0});g.title&&(i=g.title+k+k+i);g.messageTop&&(i=
g.messageTop+k+k+i);g.messageBottom&&(i=i+k+k+g.messageBottom);b.customize&&(i=b.customize(i,b,c));b=h("<textarea readonly/>").val(i).appendTo(d);if(j.queryCommandSupported("copy")){d.appendTo(c.table().container());b[0].focus();b[0].select();try{var f=j.execCommand("copy");d.remove();if(f){c.buttons.info(c.i18n("buttons.copyTitle","Copy to clipboard"),c.i18n("buttons.copySuccess",{1:"Copied one row to clipboard",_:"Copied %d rows to clipboard"},a.rows),2E3);this.processing(!1);return}}catch(l){}}f=
h("<span>"+c.i18n("buttons.copyKeys","Press <i>ctrl</i> or <i>⌘</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.")+"</span>").append(d);c.buttons.info(c.i18n("buttons.copyTitle","Copy to clipboard"),f,0);b[0].focus();b[0].select();var o=h(f).closest(".dt-button-info"),n=function(){o.off("click.buttons-copy");h(j).off(".buttons-copy");c.buttons.info(!1)};o.on("click.buttons-copy",n);h(j).on("keydown.buttons-copy",function(a){27===
a.keyCode&&(n(),e.processing(!1))}).on("copy.buttons-copy cut.buttons-copy",function(){n();e.processing(!1)})},exportOptions:{},fieldSeparator:"\t",fieldBoundary:"",header:!0,footer:!1,title:"*",messageTop:"*",messageBottom:"*"};p.ext.buttons.csvHtml5={bom:!1,className:"buttons-csv buttons-html5",available:function(){return f.FileReader!==r&&f.Blob},text:function(a){return a.i18n("buttons.csv","CSV")},action:function(a,c,d,b){this.processing(!0);var a=I(c,b).str,d=c.buttons.exportInfo(b),e=b.charset;
b.customize&&(a=b.customize(a,b,c));!1!==e?(e||(e=j.characterSet||j.charset),e&&(e=";charset="+e)):e="";b.bom&&(a=String.fromCharCode(65279)+a);u(new Blob([a],{type:"text/csv"+e}),d.filename,!0);this.processing(!1)},filename:"*",extension:".csv",exportOptions:{},fieldSeparator:",",fieldBoundary:'"',escapeChar:'"',charset:null,header:!0,footer:!1};p.ext.buttons.excelHtml5={className:"buttons-excel buttons-html5",available:function(){return f.FileReader!==r&&(v||f.JSZip)!==r&&!J()&&z},text:function(a){return a.i18n("buttons.excel",
"Excel")},action:function(a,c,d,b){this.processing(!0);var e=this,g=0,k,a=function(a){return h.parseXML(B[a])},i=a("xl/worksheets/sheet1.xml"),l=i.getElementsByTagName("sheetData")[0],a={_rels:{".rels":a("_rels/.rels")},xl:{_rels:{"workbook.xml.rels":a("xl/_rels/workbook.xml.rels")},"workbook.xml":a("xl/workbook.xml"),"styles.xml":a("xl/styles.xml"),worksheets:{"sheet1.xml":i}},"[Content_Types].xml":a("[Content_Types].xml")},j=c.buttons.exportData(b.exportOptions),n,p,m=function(a){n=g+1;p=o(i,"row",
{attr:{r:n}});for(var c=0,d=a.length;c<d;c++){var e=w(c)+""+n,f=null;if(null===a[c]||a[c]===r||""===a[c])if(!0===b.createEmptyCells)a[c]="";else continue;var h=a[c];a[c]="function"===typeof a[c].trim?a[c].trim():a[c];for(var j=0,k=K.length;j<k;j++){var m=K[j];if(a[c].match&&!a[c].match(/^0\d+/)&&a[c].match(m.match)){f=a[c].replace(/[^\d\.\-]/g,"");m.fmt&&(f=m.fmt(f));f=o(i,"c",{attr:{r:e,s:m.style},children:[o(i,"v",{text:f})]});break}}f||("number"===typeof a[c]||a[c].match&&a[c].match(/^-?\d+(\.\d+)?$/)&&
!a[c].match(/^0\d+/)?f=o(i,"c",{attr:{t:"n",r:e},children:[o(i,"v",{text:a[c]})]}):(h=!h.replace?h:h.replace(/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g,""),f=o(i,"c",{attr:{t:"inlineStr",r:e},children:{row:o(i,"is",{children:{row:o(i,"t",{text:h,attr:{"xml:space":"preserve"}})}})}})));p.appendChild(f)}l.appendChild(p);g++};b.customizeData&&b.customizeData(j);var s=function(a,c){var b=h("mergeCells",i);b[0].appendChild(o(i,"mergeCell",{attr:{ref:"A"+a+":"+w(c)+a}}));b.attr("count",parseFloat(b.attr("count"))+
1);h("row:eq("+(a-1)+") c",i).attr("s","51")},t=c.buttons.exportInfo(b);t.title&&(m([t.title],g),s(g,j.header.length-1));t.messageTop&&(m([t.messageTop],g),s(g,j.header.length-1));b.header&&(m(j.header,g),h("row:last c",i).attr("s","2"));d=g;k=0;for(var q=j.body.length;k<q;k++)m(j.body[k],g);k=g;b.footer&&j.footer&&(m(j.footer,g),h("row:last c",i).attr("s","2"));t.messageBottom&&(m([t.messageBottom],g),s(g,j.header.length-1));m=o(i,"cols");h("worksheet",i).prepend(m);s=0;for(q=j.header.length;s<q;s++)m.appendChild(o(i,
"col",{attr:{min:s+1,max:s+1,width:L(j,s),customWidth:1}}));m=a.xl["workbook.xml"];h("sheets sheet",m).attr("name",G(b));b.autoFilter&&(h("mergeCells",i).before(o(i,"autoFilter",{attr:{ref:"A"+d+":"+w(j.header.length-1)+k}})),h("definedNames",m).append(o(m,"definedName",{attr:{name:"_xlnm._FilterDatabase",localSheetId:"0",hidden:1},text:G(b)+"!$A$"+d+":"+w(j.header.length-1)+k})));b.customize&&b.customize(a,b,c);0===h("mergeCells",i).children().length&&h("mergeCells",i).remove();c=new (v||f.JSZip);
d={type:"blob",mimeType:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"};A(c,a);c.generateAsync?c.generateAsync(d).then(function(a){u(a,t.filename);e.processing(false)}):(u(c.generate(d),t.filename),this.processing(!1))},filename:"*",extension:".xlsx",exportOptions:{},header:!0,footer:!1,title:"*",messageTop:"*",messageBottom:"*",createEmptyCells:!1,autoFilter:!1,sheetName:""};p.ext.buttons.pdfHtml5={className:"buttons-pdf buttons-html5",available:function(){return f.FileReader!==
r&&(q||f.pdfMake)},text:function(a){return a.i18n("buttons.pdf","PDF")},action:function(a,c,d,b){this.processing(!0);var d=c.buttons.exportData(b.exportOptions),a=c.buttons.exportInfo(b),e=[];b.header&&e.push(h.map(d.header,function(a){return{text:"string"===typeof a?a:a+"",style:"tableHeader"}}));for(var g=0,j=d.body.length;g<j;g++)e.push(h.map(d.body[g],function(a){if(null===a||a===r)a="";return{text:"string"===typeof a?a:a+"",style:g%2?"tableBodyEven":"tableBodyOdd"}}));b.footer&&d.footer&&e.push(h.map(d.footer,
function(a){return{text:"string"===typeof a?a:a+"",style:"tableFooter"}}));d={pageSize:b.pageSize,pageOrientation:b.orientation,content:[{table:{headerRows:1,body:e},layout:"noBorders"}],styles:{tableHeader:{bold:!0,fontSize:11,color:"white",fillColor:"#2d4154",alignment:"center"},tableBodyEven:{},tableBodyOdd:{fillColor:"#f3f3f3"},tableFooter:{bold:!0,fontSize:11,color:"white",fillColor:"#2d4154"},title:{alignment:"center",fontSize:15},message:{}},defaultStyle:{fontSize:10}};a.messageTop&&d.content.unshift({text:a.messageTop,
style:"message",margin:[0,0,0,12]});a.messageBottom&&d.content.push({text:a.messageBottom,style:"message",margin:[0,0,0,12]});a.title&&d.content.unshift({text:a.title,style:"title",margin:[0,0,0,12]});b.customize&&b.customize(d,b,c);c=(q||f.pdfMake).createPdf(d);"open"===b.download&&!J()?c.open():c.download(a.filename);this.processing(!1)},title:"*",filename:"*",extension:".pdf",exportOptions:{},orientation:"portrait",pageSize:"A4",header:!0,footer:!1,messageTop:"*",messageBottom:"*",customize:null,
download:"download"};return p.Buttons});

/**handles:event-tickets-meta,gform_datepicker_init,gform_json,wp-embed**/
var tribe_event_tickets_plus=tribe_event_tickets_plus||{};tribe_event_tickets_plus.meta=tribe_event_tickets_plus.meta||{},tribe_event_tickets_plus.meta.event=tribe_event_tickets_plus.meta.event||{},function(t,e,i,n){"use strict";n.init=function(){i(".tribe-list").on("click",".attendee-meta.toggle",function(){i(this).toggleClass("on").siblings(".attendee-meta-row").slideToggle()}),this.$ticket_form=i(".tribe-events-tickets").closest(".cart"),this.$ticket_form.on("change",".quantity input, .quantity select",this.event.quantity_changed).on("keyup",".quantity input",this.event.quantity_changed).on("submit",this.event.handle_submission),this.$ticket_form.find('.quantity input:not([type="hidden"]), .quantity select').each(function(){n.set_quantity(i(this))}),i(".tribe-event-tickets-plus-meta-fields").on("keydown",".tribe-tickets-meta-number input",this.event.limit_number_field_typing)},n.render_fields=function(t,e){var s=i(".tribe-event-tickets-plus-meta").filter('[data-ticket-id="'+t+'"]'),a=s.find(".tribe-event-tickets-plus-meta-fields-tpl"),r=s.find(".tribe-event-tickets-plus-meta-fields"),l=a.html();if(n.has_meta_fields(t)){var c=r.find(".tribe-event-tickets-plus-meta-attendee").length,d=0;if(c>e)return d=c-e,void r.find(".tribe-event-tickets-plus-meta-attendee:nth-last-child(-n+"+d+")").remove();d=e-c;for(var u=0;u<d;u++){var o=l;o=l.replace(/tribe-tickets-meta\[\]/g,"tribe-tickets-meta["+t+"]["+(c+u+1)+"]"),o=o.replace(/tribe-tickets-meta_([a-z0-9\-]+)_/g,"tribe-tickets-meta_$1_"+(c+u+1)+"_"),r.append(o)}}},n.set_quantity=function(t){var s=parseInt(t.val(),10),a=parseInt(t.closest("td").data("product-id"),10),r=i(e.getElementById("tribe-event-tickets-plus-meta-fields-tpl-"+a)).html();s&&n.has_meta_fields(a)?t.closest("table").find('.tribe-event-tickets-plus-meta[data-ticket-id="'+a+'"]').show():t.closest("table").find('.tribe-event-tickets-plus-meta[data-ticket-id="'+a+'"]').hide(),n.render_fields(a,s)},n.has_meta_fields=function(t){var n=i(e.getElementById("tribe-event-tickets-plus-meta-fields-tpl-"+t)).html();return!!i(n).find(".tribe-tickets-meta").length},n.validate_submission=function(){var t=!0,e=i(".tribe-tickets-meta-required");return e.each(function(){var e=i(this),n="";n=e.is(".tribe-tickets-meta-radio")||e.is(".tribe-tickets-meta-checkbox")?e.find("input:checked").length?"checked":"":e.find("input, select, textarea").val(),0===n.length&&(t=!1)}),t},n.event.quantity_changed=function(){n.set_quantity(i(this))},n.event.limit_number_field_typing=function(t){i.inArray(t.keyCode,[46,8,9,27,13,110])!==-1||65===t.keyCode&&(t.ctrlKey===!0||t.metaKey===!0)||67===t.keyCode&&t.ctrlKey===!0||86===t.keyCode&&t.ctrlKey===!0||88===t.keyCode&&t.ctrlKey===!0||t.keyCode>=35&&t.keyCode<=40||(t.shiftKey||t.keyCode<48||t.keyCode>57)&&(t.keyCode<96||t.keyCode>105)&&t.preventDefault()},n.event.handle_submission=function(t){if(!n.validate_submission()){t.preventDefault();var e=i(this).closest("form");return e.addClass("tribe-event-tickets-plus-meta-missing-required"),void i("html, body").animate({scrollTop:e.offset().top},300)}},i(function(){n.init()})}(window,document,jQuery,tribe_event_tickets_plus.meta);
function gformInitDatepicker(){jQuery(".datepicker").each(function(){var a=jQuery(this),e=this.id,t={yearRange:"-100:+20",showOn:"focus",dateFormat:"mm/dd/yy",changeMonth:!0,changeYear:!0,suppressDatePicker:!1,onClose:function(){a.focus();var e=this;this.suppressDatePicker=!0,setTimeout(function(){e.suppressDatePicker=!1},200)},beforeShow:function(a,e){return!this.suppressDatePicker}};a.hasClass("dmy")?t.dateFormat="dd/mm/yy":a.hasClass("dmy_dash")?t.dateFormat="dd-mm-yy":a.hasClass("dmy_dot")?t.dateFormat="dd.mm.yy":a.hasClass("ymd_slash")?t.dateFormat="yy/mm/dd":a.hasClass("ymd_dash")?t.dateFormat="yy-mm-dd":a.hasClass("ymd_dot")&&(t.dateFormat="yy.mm.dd"),a.hasClass("datepicker_with_icon")&&(t.showOn="both",t.buttonImage=jQuery("#gforms_calendar_icon_"+e).val(),t.buttonImageOnly=!0),e=e.split("_"),t=gform.applyFilters("gform_datepicker_options_pre_init",t,e[1],e[2]),a.datepicker(t)})}jQuery(document).ready(gformInitDatepicker);
!function($){"use strict";var escape=/["\\\x00-\x1f\x7f-\x9f]/g,meta={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},hasOwn=Object.prototype.hasOwnProperty;$.toJSON="object"==typeof JSON&&JSON.stringify?JSON.stringify:function(t){if(null===t)return"null";var e,r,n,o,i=$.type(t);if("undefined"!==i){if("number"===i||"boolean"===i)return String(t);if("string"===i)return $.quoteString(t);if("function"==typeof t.toJSON)return $.toJSON(t.toJSON());if("date"===i){var f=t.getUTCMonth()+1,u=t.getUTCDate(),s=t.getUTCFullYear(),a=t.getUTCHours(),l=t.getUTCMinutes(),c=t.getUTCSeconds(),p=t.getUTCMilliseconds();return 10>f&&(f="0"+f),10>u&&(u="0"+u),10>a&&(a="0"+a),10>l&&(l="0"+l),10>c&&(c="0"+c),100>p&&(p="0"+p),10>p&&(p="0"+p),'"'+s+"-"+f+"-"+u+"T"+a+":"+l+":"+c+"."+p+'Z"'}if(e=[],$.isArray(t)){for(r=0;r<t.length;r++)e.push($.toJSON(t[r])||"null");return"["+e.join(",")+"]"}if("object"==typeof t){for(r in t)if(hasOwn.call(t,r)){if(i=typeof r,"number"===i)n='"'+r+'"';else{if("string"!==i)continue;n=$.quoteString(r)}i=typeof t[r],"function"!==i&&"undefined"!==i&&(o=$.toJSON(t[r]),e.push(n+":"+o))}return"{"+e.join(",")+"}"}}},$.evalJSON="object"==typeof JSON&&JSON.parse?JSON.parse:function(str){return eval("("+str+")")},$.secureEvalJSON="object"==typeof JSON&&JSON.parse?JSON.parse:function(str){var filtered=str.replace(/\\["\\\/bfnrtu]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"");if(/^[\],:{}\s]*$/.test(filtered))return eval("("+str+")");throw new SyntaxError("Error parsing JSON, source is not valid.")},$.quoteString=function(t){return t.match(escape)?'"'+t.replace(escape,function(t){var e=meta[t];return"string"==typeof e?e:(e=t.charCodeAt(),"\\u00"+Math.floor(e/16).toString(16)+(e%16).toString(16))})+'"':'"'+t+'"'}}(jQuery);
!function(e,t){"use strict";function r(){if(!s){s=!0;var e,r,a,n,o=-1!==navigator.appVersion.indexOf("MSIE 10"),i=!!navigator.userAgent.match(/Trident.*rv:11\./),c=t.querySelectorAll("iframe.wp-embedded-content");for(r=0;r<c.length;r++)a=c[r],a.getAttribute("data-secret")||(n=Math.random().toString(36).substr(2,10),a.src+="#?secret="+n,a.setAttribute("data-secret",n)),(o||i)&&(e=a.cloneNode(!0),e.removeAttribute("security"),a.parentNode.replaceChild(e,a))}}var a=!1,s=!1;t.querySelector&&e.addEventListener&&(a=!0),e.wp=e.wp||{},e.wp.receiveEmbedMessage||(e.wp.receiveEmbedMessage=function(r){var a=r.data;if((a.secret||a.message||a.value)&&!/[^a-zA-Z0-9]/.test(a.secret)){var s,n,o,i,c,d=t.querySelectorAll('iframe[data-secret="'+a.secret+'"]'),l=t.querySelectorAll('blockquote[data-secret="'+a.secret+'"]');for(s=0;s<l.length;s++)l[s].style.display="none";for(s=0;s<d.length;s++)n=d[s],r.source===n.contentWindow&&(n.removeAttribute("style"),"height"===a.message&&(o=parseInt(a.value,10),o>1e3?o=1e3:~~o<200&&(o=200),n.height=o),"link"===a.message&&(i=t.createElement("a"),c=t.createElement("a"),i.href=n.getAttribute("src"),c.href=a.value,c.host===i.host&&t.activeElement===n&&(e.top.location.href=a.value)))}},a&&(e.addEventListener("message",e.wp.receiveEmbedMessage,!1),t.addEventListener("DOMContentLoaded",r,!1),e.addEventListener("load",r,!1)))}(window,document);
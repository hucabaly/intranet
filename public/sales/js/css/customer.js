function setHeightBody(t,o){$(t).css("min-height",$(window).height()-$(".welcome-footer").outerHeight()-o)}function goto_make(t){var o=$("#make_name").val();""==o?$("#modal-confirm-name").modal("show"):($.cookie("makeName",o,{expires:7}),location.href=t)}function hideModalConfirmMake(){$("#modal-confirm-make").hide()}function goToFinish(){location.href="/css/cancel"}function checkPoint(t){var o=$(t).val();if(""===o){var a=$(t).attr("data-questionid"),e=$(".rateit[data-questionid="+a+"]"),r=e.rateit("value");r<3&&r>0?($(t).css("border","1px solid red"),$(t).attr("placeholder","＃コメントがあればご記入ください。")):r>=3&&($(t).css("border","1px solid #d2d6de"),$(t).removeAttr("placeholder"))}else $(t).css("border","1px solid #d2d6de"),$(t).removeAttr("placeholder")}function totalMark(t){var o=$(t).rateit("value"),a=$(t).attr("data-questionid"),e=$(".comment-question[data-questionid='"+a+"']");if(o<3&&o>0){var r=e.val();""===$.trim(r)&&(e.css("border","1px solid red"),e.attr("placeholder","＃コメントがあればご記入ください。"),e.focus())}else o>=3&&(e.css("border","1px solid #d2d6de"),e.removeAttr("placeholder"));"tongquat"===t.id&&$(t).css("border","1px solid #d2d6de"),$(".total-point").html(getTotalPoint())}function getTotalPoint(){var t=0,o=0;$(".rateit").each(function(){var a=parseInt($(this).rateit("value"));"tongquat"!==$(this).attr("id")&&a>0&&(t++,o+=a)});var a=parseInt($("#tongquat").rateit("value"));return o=0===t?20*a:4*a+o/(5*t)*80,o.toFixed(2)}function confirm(t){var o=!1,a=!1,e="",r="";$("#modal-confirm").modal("hide"),$(".comment-question").css("border-color","#d2d6de"),$("#tongquat").css("border","none"),$("#comment-tongquat").css("border-color","#d2d6de");var t=$.parseJSON(t),i=parseInt($("#tongquat").rateit("value"));0==i&&(o=!0,e+="<p>"+t.totalMarkValidateRequired+"</p>",$("#tongquat").css("border","1px solid red"));var d=[];if($(".rateit").each(function(){var t=parseInt($(this).rateit("value"));t>0&&t<3&&""==$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val()&&d.push($(this).attr("data-questionid"))}),d.length>0){for(var n=0;n<d.length;n++)$(".comment-question[data-questionid='"+d[n]+"']").css("border","1px solid red");r+="<p>"+t.questionCommentRequired+"</p>",a=!0}return o?($("#modal-alert .modal-body").html(e),$("#modal-alert").modal("show"),!1):a?($("#modal-confirm .modal-body").html(r+"<p>現在の点数は "+$(".total-point").html()+" 点です。アンケート結果を送信しますか。</p>"),$("#modal-confirm .modal-footer .cancel").html("アンケートに戻る"),$("#modal-confirm .modal-footer .submit").html("そのまま送信する"),$("#modal-confirm").modal("show"),!1):($("#modal-confirm .modal-body").html("現在の点数は "+$(".total-point").html()+" 点です。アンケート結果を送信しますか。"),$("#modal-confirm .modal-footer .cancel").html("キャンセル"),$("#modal-confirm .modal-footer .submit").html("OK"),void $("#modal-confirm").modal("show"))}function submit(t,o){var a=$(".make-name").text(),e="test",r=getTotalPoint(),i=$("#proposed").val(),d=[];$(".rateit").each(function(){var t=parseInt($(this).rateit("value")),o=$(this).attr("data-questionid"),a=t,e=$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();d.push([o,a,e])}),$(".apply-click-modal").show(),$.ajax({url:baseUrl+"/css/saveResult",type:"post",data:{_token:t,arrayQuestion:d,make_name:a,make_email:e,totalMark:r,proposed:i,cssId:o}}).done(function(t){$(".comment-question").val(""),$(".proposed").val(""),location.href=baseUrl+"css/success"}).fail(function(){alert("Ajax failed to fetch data")})}$("#frm_welcome").submit(function(t){var o=$.trim($("#make_name").val());""===o&&($("#modal-confirm-name").modal("show"),t.preventDefault())});
function goto_make(t){var a=$("#make_name").val();""==a?$("#modal-confirm-name").modal("show"):($.cookie("makeName",a,{expires:7}),location.href=t)}function hideModalConfirmMake(){$("#modal-confirm-make").hide()}function goToFinish(){location.href="/css/cancel"}function totalMark(t){var a=$(t).rateit("value"),o=$(t).attr("data-questionid"),e=$(".comment-question[data-questionid='"+o+"']");if(a<3){var i=e.val();""===$.trim(i)&&(e.css("border","1px solid red"),e.attr("placeholder","＃コメントがあればご記入ください"))}else e.css("border","1px solid #d2d6de"),e.removeAttr("placeholder");"tongquat"===t.id&&$(t).css("border","1px solid #d2d6de"),$("#total-point").html(getTotalPoint()),$(".point-fixed").html(getTotalPoint())}function getTotalPoint(){var t=0,a=0;$(".rateit").each(function(){var o=parseInt($(this).rateit("value"));"tongquat"!==$(this).attr("id")&&o>0&&(t++,a+=o)});var o=parseInt($("#tongquat").rateit("value"));return a=0===t?20*o:4*o+a/(5*t)*80,a.toFixed(2)}function confirm(t){var a=!1,o="";$("#modal-confirm").modal("hide"),$(".comment-question").css("border-color","#d2d6de"),$("#tongquat").css("border","none"),$("#comment-tongquat").css("border-color","#d2d6de");var t=$.parseJSON(t),e=parseInt($("#tongquat").rateit("value"));0==e&&(a=!0,o+="<p>"+t.totalMarkValidateRequired+"</p>",$("#tongquat").css("border","1px solid red"));var i=[];if($(".rateit").each(function(){var t=parseInt($(this).rateit("value"));t>0&&t<3&&""==$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val()&&i.push($(this).attr("data-questionid"))}),i.length>0){for(var n=0;n<i.length;n++)$(".comment-question[data-questionid='"+i[n]+"']").css("border","1px solid red");o+="<p>"+t.questionCommentRequired+"</p>",a=!0}return a?($("#modal-alert .modal-body").html(o),$("#modal-alert").modal("show"),!1):($("#modal-confirm .modal-body").html("現在の点数は "+$("#total-point").html()+" 点です。アンケート結果を送信しますか。"),void $("#modal-confirm").modal("show"))}function submit(t,a){var o=$(".make-name").text(),e="test",i=getTotalPoint(),n=$("#proposed").val(),r=[];$(".rateit").each(function(){var t=parseInt($(this).rateit("value")),a=$(this).attr("data-questionid"),o=t,e=$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();r.push([a,o,e])}),$(".apply-click-modal").show(),$.ajax({url:baseUrl+"/css/saveResult",type:"post",data:{_token:t,arrayQuestion:r,make_name:o,make_email:e,totalMark:i,proposed:n,cssId:a}}).done(function(t){location.href=baseUrl+"css/success/"+a}).fail(function(){alert("Ajax failed to fetch data")})}$(document).ready(function(){$("#make_name").focusout(function(){$(".project-info .make-name").text($(this).val())})}),$(window).scroll(function(){$(".visible-check").visible()?$(".point-fixed").hide():$(".point-fixed").show()});
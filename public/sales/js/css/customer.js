function setHeightBody(t,o){$(t).css("min-height",$(window).height()-$(".welcome-footer").outerHeight()-o)}function goto_make(t){var o=$("#make_name").val();""==o?$("#modal-confirm-name").modal("show"):($.cookie("makeName",o,{expires:7}),location.href=t)}function hideModalConfirmMake(){$("#modal-confirm-make").hide()}function goToFinish(){location.href="/css/cancel"}function totalMark(t){var o=$(t).rateit("value"),e=$(t).attr("data-questionid"),a=$(".comment-question[data-questionid='"+e+"']");if(o<3&&o>0){var i=a.val();""===$.trim(i)&&(a.css("border","1px solid red"),a.attr("placeholder","＃コメントがあればご記入ください。"))}else o>=3&&(a.css("border","1px solid #d2d6de"),a.removeAttr("placeholder"));"tongquat"===t.id&&$(t).css("border","1px solid #d2d6de"),$(".total-point").html(getTotalPoint())}function getTotalPoint(){var t=0,o=0;$(".rateit").each(function(){var e=parseInt($(this).rateit("value"));"tongquat"!==$(this).attr("id")&&e>0&&(t++,o+=e)});var e=parseInt($("#tongquat").rateit("value"));return o=0===t?20*e:4*e+o/(5*t)*80,o.toFixed(2)}function fixPointContainer(){var t=$(window).width(),o=$("#make-header").width();if($(".visible-check").visible())$(".total-point-container ").css("position","inherit");else{$(".total-point-container ").css("position","fixed");var e=(t-o)/2;$(".total-point-container ").css("right",e)}}function confirm(t){var o=!1,e="";$("#modal-confirm").modal("hide"),$(".comment-question").css("border-color","#d2d6de"),$("#tongquat").css("border","none"),$("#comment-tongquat").css("border-color","#d2d6de");var t=$.parseJSON(t),a=parseInt($("#tongquat").rateit("value"));0==a&&(o=!0,e+="<p>"+t.totalMarkValidateRequired+"</p>",$("#tongquat").css("border","1px solid red"));var i=[];if($(".rateit").each(function(){var t=parseInt($(this).rateit("value"));t>0&&t<3&&""==$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val()&&i.push($(this).attr("data-questionid"))}),i.length>0){for(var n=0;n<i.length;n++)$(".comment-question[data-questionid='"+i[n]+"']").css("border","1px solid red");e+="<p>"+t.questionCommentRequired+"</p>",o=!0}return o?($("#modal-alert .modal-body").html(e),$("#modal-alert").modal("show"),!1):($("#modal-confirm .modal-body").html("現在の点数は "+$(".total-point").html()+" 点です。アンケート結果を送信しますか。"),void $("#modal-confirm").modal("show"))}function submit(t,o){var e=$(".make-name").text(),a="test",i=getTotalPoint(),n=$("#proposed").val(),r=[];$(".rateit").each(function(){var t=parseInt($(this).rateit("value")),o=$(this).attr("data-questionid"),e=t,a=$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();r.push([o,e,a])}),$(".apply-click-modal").show(),$.ajax({url:baseUrl+"/css/saveResult",type:"post",data:{_token:t,arrayQuestion:r,make_name:e,make_email:a,totalMark:i,proposed:n,cssId:o}}).done(function(t){location.href=baseUrl+"css/success"}).fail(function(){alert("Ajax failed to fetch data")})}$(document).ready(function(){$("#make_name").focusout(function(){$(".project-info .make-name").text($(this).val())})}),$(window).scroll(function(){fixPointContainer()}),$(window).resize(function(){fixPointContainer()});
function showCalendar(e){var t=$(e).attr("target");$("#"+t).focus()}function set_team_to_css(){var e="",t="";teamArray=[],$("input[class=team-tree-checkbox]:checked").each(function(){var a=$(this).attr("data-id"),i=$(this).parent().parent().find("span").text();e+='<input class="team_id" type="hidden" name="teams['+a+']" value="'+i+'" />',""==t?t=i:t+=", "+i,teamArray.push([a,i])}),$(".set_team").html(t),$(".set_team").parent().find(".team_id").remove(),$(".set_team").parent().append(e),""==t?$("#team_id_check").val(""):($("#team_id_check").val("1"),$("#team_id_check-error").remove()),$("#teamsModal").modal("hide")}function set_teams_popup(){$(".team-tree-checkbox").iCheck("uncheck");for(var e=0;e<teamArray.length;e++)$(".team-tree-checkbox[data-id="+teamArray[e][0]+"]").iCheck("check")}function totalMark(e){var t=$(e).rateit("value"),a=$(e).attr("data-questionid");if(t<3){var i=$(".comment-question[data-questionid='"+a+"']").val();""==$.trim(i)&&$(".comment-question[data-questionid='"+a+"']").css("border","1px solid red")}else $(".comment-question[data-questionid='"+a+"']").css("border","1px solid #d2d6de");$(".diem").html(getTotalPoint()),$(".diem-fixed").html("Tổng điểm: "+getTotalPoint())}function getTotalPoint(){var e=0,t=0;$(".rateit").each(function(){var a=parseInt($(this).rateit("value"));"tongquat"!=$(this).attr("id")&&a>0&&(e++,t+=a)});var a=parseInt($("#tongquat").rateit("value"));return t=0==e?20*a:4*a+t/(5*e)*80,t.toFixed(2)}function confirm(e){var t=!1,a="";$("#modal-confirm").modal("hide"),$(".comment-question").css("border-color","#d2d6de"),$("#tongquat").css("border","none"),$("#comment-tongquat").css("border-color","#d2d6de");var e=$.parseJSON(e),i=$.trim($("#make_name").val()),n=$.trim($("#make_email").val());""==i&&($("#make_name").css("border","1px solid red"),a+="<div>"+e.nameRequired+"</div>",t=!0,$("#make_name").focus()),""==n?($("#make_email").css("border","1px solid red"),a+="<div>"+e.emailRequired+"</div>",t=!0,""!=i&&$("#make_email").focus()):isValidEmailAddress(n)||($("#make_email").css("border","1px solid red"),a+="<div>"+e.emailAddress+"</div>",t=!0,""!=i&&$("#make_email").focus());var r=parseInt($("#tongquat").rateit("value"));0==r&&(t=!0,a+="<div>"+e.totalMarkValidateRequired+"</div>",$("#tongquat").css("border","1px solid red"));var d=[];if($(".rateit").each(function(){var e=parseInt($(this).rateit("value"));e>0&&e<3&&""==$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val()&&d.push($(this).attr("data-questionid"))}),d.length>0){for(var o=0;o<d.length;o++)$(".comment-question[data-questionid='"+d[o]+"']").css("border","1px solid red");a+="<div>"+e.questionCommentRequired+"</div>",t=!0}return t?($("#modal-alert .modal-body").html(a),$("#modal-alert").modal("show"),!1):($("#modal-confirm .modal-body").html("Số điểm hiện tại của CSS là "+$(".diem").html()+" Điểm. Bạn có chắc muốn hoàn thành CSS tại đây không?"),void $("#modal-confirm").modal("show"))}function submit(e,t){var a=$("#make_name").val(),i=$("#make_email").val(),n=getTotalPoint(),r=$("#proposed").val(),d=[];$(".rateit").each(function(){var e=parseInt($(this).rateit("value")),t=$(this).attr("data-questionid"),a=e,i=$(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();d.push([t,a,i])}),$(".apply-click-modal").show(),$.ajax({url:baseUrl+"/css/saveResult",type:"post",data:{_token:e,arrayQuestion:d,make_name:a,make_email:i,totalMark:n,proposed:r,cssId:t}}).done(function(e){location.href=baseUrl+"/css/success/"+t}).fail(function(){alert("Ajax failed to fetch data")})}function isValidEmailAddress(e){var t=/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;return t.test(e)}function copyToClipboard(e){var t=$("<input>");$("body").append(t),t.val($(e).attr("data-href")).select(),document.execCommand("copy"),t.remove(),$("#modal-clipboard").modal("show")}$("input").iCheck({checkboxClass:"icheckbox_minimal-blue",radioClass:"iradio_minimal-blue"});var teamArray=[];$(document).ready(function(){$(".project_type input[type=radio]:first-child").prop("checked",!0),$(".team-tree a").removeAttr("href"),$(".team-tree a").attr("onclick","change_bgcolor_element(this)"),$("#start_date").on("changeDate",function(){$(this).datepicker("hide"),$("#end_date").focus(),$("#start_date").css("color","#555").css("font-size","14px"),$("#start_date-error").remove()}),$("#end_date").on("changeDate",function(){$(this).datepicker("hide"),$("#end_date").css("color","#555").css("font-size","14px"),$("#end_date-error").remove()})}),$(document).ready(function(){navigator.userAgent.toLowerCase().indexOf("firefox")>-1&&$(".date").css("top","-1px"),jQuery.validator.addMethod("greaterThan",function(e,t,a){return/Invalid|NaN/.test(new Date(e))?isNaN(e)&&isNaN($(a).val())||Number(e)>Number($(a).val()):new Date(e)>new Date($(a).val())},"Must be greater than {0}."),$("#frm_create_css").validate({rules:{japanese_name:"required",team_id_check:"required",company_name:"required",customer_name:"required",project_name:"required",pm_name:"required",brse_name:"required",start_date:"required",end_date:{required:!0,greaterThan:"#start_date"}},messages:{japanese_name:"Vui lòng điền tên tiếng Nhật của bạn",team_id_check:"Vui lòng chọn team cho dự án",company_name:"Vui lòng nhập tên công ty",customer_name:"Vui lòng nhập tên khách hàng",project_name:"Vui lòng nhập tên dự án",pm_name:"Vui lòng nhập tên PM",brse_name:"Vui lòng nhập tên BrSE",start_date:"Vui lòng nhập ngày bắt đầu dự án",end_date:{required:"Vui lòng nhập ngày kết thúc dự án",greaterThan:"Ngày kết thúc phải lớn hơn ngày bắt đầu"}}})}),$(window).scroll(function(){$(".visible-check").visible()?$(".diem-fixed").hide():$(".diem-fixed").show()});
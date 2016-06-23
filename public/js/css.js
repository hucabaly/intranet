/**
 * file javascript dùng trong các trang CSS
 */


/** 
 * trang tạo CSS
 * mảng lưu các team đã được set (khi ấn OK trên popup danh sách team)
 * dùng khi thay đổi danh sách team trên popup nhưng đóng popup
 * mà không ấn OK thì khi mở lại vẫn lưu các team cũ
 * @type Array
 */
$('input').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
}); 

var teamArray = []; 

/** 
 * Sự kiện ấn vào img calendar thi show calendar
 * @param element x
*/
function showCalendar(x) {
    var target = $(x).attr("target");
    $("#" + target).focus();
}





/**
 * Sự kiện khi click OK trên popup team
 * Thêm các team được chọn vào form tạo CSS
 * @returns void
 */
function set_team_to_css() {
    var elements = "";
    var str = "";
    teamArray = [];
    //get team checked
    $('input[class=team-tree-checkbox]:checked').each(function(){
        var team_id = $(this).attr("data-id");
        var team_name = $(this).parent().parent().find('span').text();
        elements += '<input class="team_id" type="hidden" name="teams[' + team_id + ']" value="' + team_name + '" />';
        if (str == "") {
            str = team_name;
        } else {
            str += ', ' + team_name;
        }
        teamArray.push([team_id, team_name]);
    });
    
    $(".set_team").html(str);
    $(".set_team").parent().find('.team_id').remove();
    $(".set_team").parent().append(elements);
    if (str == "") {
        $('#team_id_check').val("")
    } else {
        $('#team_id_check').val("1")
        $("#team_id_check-error").remove();
    }
    
    //close popup
    $('#teamsModal').modal('hide');
}

/**
 * Khi ấn nút Set team trên form tạo CSS
 * Set các team đã được chọn từ trước vào cột phải của popup team
 * @returns void
 */
function set_teams_popup() {
    $('.team-tree-checkbox').iCheck('uncheck');
    for (var i = 0; i < teamArray.length; i++) {
        $('.team-tree-checkbox[data-id='+teamArray[i][0]+']').iCheck('check');
    }
}

$(document).ready(function () {
    $(".project_type input[type=radio]:first-child").prop('checked', true);
    $(".team-tree a").removeAttr("href");
    $(".team-tree a").attr("onclick", "change_bgcolor_element(this)");

    //hide calendar sau khi select
    $('#start_date').on('changeDate', function (ev) {
        $(this).datepicker('hide');
        $('#end_date').focus();
        $('#start_date').css('color','#555').css('font-size','14px');
        $('#start_date-error').remove();
    });
    
    $('#end_date').on('changeDate', function (ev) {
        $(this).datepicker('hide');
        $('#end_date').css('color','#555').css('font-size','14px');;
    });
});

$(document).ready(function () {
    /** Check is firefox */
    if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
        $(".date").css('top','-1px');
    }
    /** End check is firefox */
    
    jQuery.validator.addMethod("greaterThan",
        function (value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val())
                    || (Number(value) > Number($(params).val()));
        }, 'Must be greater than {0}.'
    );

    $("#frm_create_css").validate({
        rules: {
            japanese_name: "required",
            team_id_check: "required",
            company_name: "required",
            customer_name: "required",
            project_name: "required",
            pm_name: "required",
            brse_name: "required",
            start_date: "required",
            end_date: {
                required: true,
                greaterThan: "#start_date",
            },
        },
        messages: {
            japanese_name: "Vui lòng điền tên tiếng Nhật của bạn",
            team_id_check: "Vui lòng chọn team cho dự án",
            company_name: "Vui lòng nhập tên công ty",
            customer_name: "Vui lòng nhập tên khách hàng",
            project_name: "Vui lòng nhập tên dự án",
            pm_name: "Vui lòng nhập tên PM",
            brse_name: "Vui lòng nhập tên BrSE",
            start_date: "Vui lòng nhập ngày bắt đầu dự án",
            end_date: {
                required: 'Vui lòng nhập ngày kết thúc dự án',
                greaterThan: 'Ngày kết thúc phải lớn hơn ngày bắt đầu',
            }
        }
    });
});

/**
 * Trang lam danh gia
 * Khi khach hang danh danh gia mot tieu chi
 * Tinh tong diem realtime
 * @returns void
 */
function totalMark(elem) {
    var point = $(elem).rateit('value');
    var dataQuestionid = $(elem).attr('data-questionid');
    if(point < 3){
        $(".comment-question[data-questionid='"+dataQuestionid+"']").css("border","1px solid red");
    }else{
        $(".comment-question[data-questionid='"+dataQuestionid+"']").css("border","1px solid #d2d6de");
    }
    
    $(".diem").html(getTotalPoint());
    $(".diem-fixed").html('Tổng điểm: ' + getTotalPoint());
}

function getTotalPoint(){
    var tongSoCauDanhGia = 0;
    var total = 0;
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        if($(this).attr("id") != "tongquat"){
            if(danhGia > 0){
                tongSoCauDanhGia++;
                total += danhGia;
            }
        }
    });
    
    var diemTongQuat = parseInt($("#tongquat").rateit('value'));
    if(tongSoCauDanhGia == 0){
        total = diemTongQuat * 20;
    }else{
        total = diemTongQuat * 4 + total/(tongSoCauDanhGia * 5) * 80;
    }
    
    return total.toFixed(2);
}

/**
 * show or hide total point at bottom right make css page
 */
$(window).scroll(function(){
    if($('.visible-check').visible()){
        $(".diem-fixed").hide();
    } else {
        $(".diem-fixed").show();
    }
});

/**
 * Function confirm CSS
 * @returns void
 */
function confirm(arrayValidate){
    var invalid = false;
    var strInvalid = "";
    $('#modal-confirm').modal('hide');
    $(".comment-question").css("border-color","#d2d6de");
    $("#tongquat").css("border","none");
    $("#comment-tongquat").css("border-color","#d2d6de");
    
    var arrayValidate = $.parseJSON(arrayValidate);
    var makeName = $.trim($("#make_name").val());
    var makeEmail = $.trim($("#make_email").val());
    if(makeName == ""){
        $("#make_name").css("border","1px solid red");
        strInvalid += '<div>'+arrayValidate['nameRequired']+'</div>';
        invalid = true;
        $("#make_name").focus();
    }
    
    if(makeEmail == ""){
        $("#make_email").css("border","1px solid red");
        strInvalid += '<div>'+arrayValidate['emailRequired']+'</div>';
        invalid = true;
        if(makeName != ""){
            $("#make_email").focus();
        }
    }else if( !isValidEmailAddress( makeEmail ) ){
        $("#make_email").css("border","1px solid red");
        strInvalid += '<div>'+arrayValidate['emailAddress']+'</div>';
        invalid = true;
        if(makeName != ""){
            $("#make_email").focus();
        }
    }
    
    var diemTongQuat = parseInt($("#tongquat").rateit('value'));
    if(diemTongQuat == 0){
        invalid = true;
        strInvalid += '<div>'+arrayValidate['totalMarkValidateRequired']+'</div>';
        $("#tongquat").css("border","1px solid red");
    }else if(diemTongQuat < 3){
        var comment_tong = $.trim($("#comment-tongquat").val());
        if(comment_tong == ""){
            strInvalid += '<div>'+arrayValidate['questionCommentRequired']+'</div>';
            $("#comment-tongquat").css("border","1px solid red");
            invalid = true;
        }
        
    }
    
    var arrValidate = [];
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        if($(this).attr("id") != "tongquat"){
            if(danhGia > 0 && danhGia < 3){
                if($(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val() == ""){
                    arrValidate.push($(this).attr("data-questionid"));
                }
            }
        }
    }); 
    
    if(arrValidate.length > 0) {
        for(var i=0; i<arrValidate.length; i++){
            $(".comment-question[data-questionid='"+arrValidate[i]+"']").css("border","1px solid red");
        }
        if(diemTongQuat > 2){
            strInvalid += '<div>'+arrayValidate['questionCommentRequired']+'</div>';
        }else {
            var comment_tong = $.trim($("#comment-tongquat").val());
            if(comment_tong != ""){
                strInvalid += '<div>'+arrayValidate['questionCommentRequired']+'</div>';
            }
        }
        invalid = true;
    }
    
    if(invalid){
        $('#modal-alert .modal-body').html(strInvalid);
        $('#modal-alert').modal('show');
        return false;
    }
    
    $('#modal-confirm .modal-body').html("Số điểm hiện tại của CSS là "+$(".diem").html()+" Điểm. Bạn có chắc muốn hoàn thành CSS tại đây không?");
    $('#modal-confirm').modal('show');
}

/**
 * Validate then insert CSS result into database
 * @param string token
 * @param int cssId
 * @param json arrayValidate
 * @returns void
 */
function submit(token, cssId){ 
    var make_name = $("#make_name").val();
    var make_email = $("#make_email").val();
    var totalMark = getTotalPoint();
    var proposed = $("#proposed").val();
    var arrayQuestion = [];
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        var questionId = $(this).attr("data-questionid");
        var diem = danhGia;
        var comment = $(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();
        arrayQuestion.push([questionId,diem,comment]);
    });
    
    $(".apply-click-modal").show();
    $.ajax({
        url: baseUrl + '/css/saveResult',
        type: 'post',
        data: {
            _token: token, 
            arrayQuestion: arrayQuestion, 
            make_name: make_name, 
            make_email: make_email, 
            totalMark: totalMark,
            proposed: proposed,
            cssId: cssId
        },
    })
    .done(function (data) { 
        location.href = baseUrl + "/css/success/"+cssId;
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

/**
 * Function validate email address
 * @param string emailAddress
 * @returns boolean
 */
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

function exportExcel(projectName){
    $(".make-css").table2excel({
        exclude: ".noExl",
        name: "Worksheet Name",
        filename: projectName //do not include extension
    });
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).attr('data-href')).select();
  document.execCommand("copy");
  $temp.remove();
  
  $("#modal-clipboard").modal('show');
}
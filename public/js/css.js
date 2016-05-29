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
 * hàm thực hiện việc add team từ cột trái sang cột phải trong popup team
 **/
function add_team() {
    if ($(".team-tree ul a[set=true]").length > 0) {
        var team_id = $(".team-tree ul a[set=true]").attr("data-id");
        var chosen = false;

        $(".modal-body ul.right-list li").each(function (index) {

            if ($(this).attr("id") == team_id) {
                alert("Da add roi");
                chosen = true;
                return false;
            }
        });
        if (chosen == false) {
            var element_add = $(".team-tree ul a[set=true]").parent().parent()[0].outerHTML;
            //$(".team-tree ul a[set=true]")

            $(".modal-body ul.right-list").append(element_add);
            $(".modal-body ul li a[set=true]").attr("set", "false");
            $(".modal-body ul li").css("background-color", "");
        }
    }
}

/** 
 * Hàm thực hiện việc remove team khỏi cột phải trong popup team
 * @returns void
 **/

function remove_team() {
    if ($(".modal-body ul.right-list li a[set=true]").length > 0) {
        $(".modal-body ul.right-list li a[set=true]").parent().parent().remove();
    }
}

/**
 * Thay đổi background color của team được click và set attribue set=true
 * @param element x
 * @returns void
 */
function change_bgcolor_element(x) {
    $(".team-tree a").attr("set", "false");
    $(".team-tree ul li").css("background-color", "");
    $(x).attr("set", "true");
    $(x).parent().parent().css("background-color", "rgb(33, 42, 109)");

    $(".right-list a").attr("set", "false");
    $(".right-list li").css("background-color", "").attr("set", "false");
    $(x).attr("set", "true");
    $(x).parent().parent().css("background-color", "rgb(33, 42, 109)");
    
    console.log(abc(x,parseInt($(x).attr("level"))));
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

    $(".modal-body ul.right-list li a").each(function (index) {
        var team_id = $(this).attr("data-id");
        var team_name = $(this).html();
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
    $(".right-list").html("");
    for (var i = 0; i < teamArray.length; i++) {
        $(".right-list").append('<li set="false"><label class="team-item"><a data-id="' + teamArray[i][0] + '" onclick="change(this)">' + teamArray[i][1] + '</a></label></li>');
    }
}

$(document).ready(function () {
    $(".project_type input[type=radio]:first-child").prop('checked', true);
    $(".team-tree a").removeAttr("href");
    $(".team-tree li ul a").attr("onclick", "change_bgcolor_element(this)");

    //hide calendar sau khi select
    $('#start_date').on('changeDate', function (ev) {
        $(this).datepicker('hide');
        $('#end_date').focus();
    });

    $('#end_date').on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
});

$(document).ready(function () {
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
function totalMark() {
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
    
    total = total.toFixed(2);
    $(".diem").html(total);
    $(".diem-fixed").html(total);
    
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
function confirm(){
    $('#modal-confirm .modal-body').html("Số điểm hiện tại của CSS là "+$(".diem").html()+" Điểm. Bạn có chắc muốn hoàn thành CSS tại đây không?");
    $('#modal-confirm').modal('show');
}

/**
 * Function validate va insert CSS vao database
 * @param string token
 * @param int cssId
 * @param json arrayValidate
 * @returns void
 */
function submit(token, cssId, arrayValidate){ 
    $('#modal-confirm').modal('hide');
    $(".comment-question").css("border","none");
    
    var arrayValidate = $.parseJSON(arrayValidate);
    var makeName = $.trim($("#make_name").val());
    var makeEmail = $.trim($("#make_email").val());
    if(makeName == "" || makeEmail == ""){
        if(makeName == ""){
            $('#modal-alert .modal-body').html(arrayValidate['nameRequired']);
            $("#make_name").focus();
            $("#make_name").css("border","1px solid red");
        }else{
            $('#modal-alert .modal-body').html(arrayValidate['emailRequired']);
            $("#make_email").focus();
            $("#make_email").css("border","1px solid red");
        }
        $('#modal-alert').modal('show');
        return false;
    }
    
    if( !isValidEmailAddress( makeEmail ) ){
        $('#modal-alert .modal-body').html(arrayValidate['emailAddress']);
        $("#make_email").focus();
        $("#make_email").css("border","1px solid red");
        $('#modal-alert').modal('show');
        return false;
    }
    
    var diemTongQuat = parseInt($("#tongquat").rateit('value'));
    if(diemTongQuat == 0){
        $('#modal-alert .modal-body').html(arrayValidate['totalMarkValidateRequired']);
        $('#modal-alert').modal('show');
        return false;
    }else if(diemTongQuat < 3){
        var comment_tong = $.trim($("#comment-tongquat").val());
        if(comment_tong == ""){
            $('#modal-alert .modal-body').html(arrayValidate['questionCommentRequired']);
            $('#modal-alert').modal('show');
            return false;
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
            //$(".comment-question[data-questionid='"+arrValidate[i]+"']").parent().parent().find("td:nth-child(2)").css("border-right","1px solid red");
            //$(".comment-question[data-questionid='"+arrValidate[i]+"']").parent().parent().prev().find("td:last-child").css("border-bottom","1px solid red");
        }
        $('#modal-alert .modal-body').html(arrayValidate['questionCommentRequired']);
        $('#modal-alert').modal('show');
        return false;
    }
    
    var make_name = $("#make_name").val();
    var make_email = $("#make_email").val();
    var totalMark = parseInt($("#tongquat").rateit('value'));
    var proposed = $("#proposed").val();
    var arrayQuestion = [];
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        if($(this).attr("id") != "tongquat"){
            var questionId = $(this).attr("data-questionid");
            var diem = danhGia;
            var comment = $(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();
            arrayQuestion.push([questionId,diem,comment]);
        }
    });
    
    
    $.ajax({
        url: '/css/saveResult',
        type: 'post',
        data: {
            _token: token, 
            arrayQuestion: arrayQuestion, 
            make_name: make_name, 
            make_email: make_email, 
            totalMark: totalMark,
            comment : $.trim($("#comment-tongquat").val()),
            proposed: proposed,
            cssId: cssId
        },
    })
    .done(function (data) {
        console.log(data);
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
};



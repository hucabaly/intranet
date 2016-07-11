/**
 * 
 * iCheck load
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
    $('#start_date').on('changeDate', function () {
        $(this).datepicker('hide');
        $('#end_date').focus();
        $('#start_date').css('color','#555').css('font-size','14px');
        $('#start_date-error').remove();
    });
    
    $('#end_date').on('changeDate', function () {
        $(this).datepicker('hide');
        $('#end_date').css('color','#555').css('font-size','14px');
        $('#end_date-error').remove();
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

//# sourceMappingURL=create.js.map

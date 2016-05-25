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


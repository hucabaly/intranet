$('input').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});   

$("#dateRanger").dateRangeSlider({
    range: {min: new Date(2016, 0, 1)}, //use minimum range
    bounds: {
           min: new Date(2016, 0, 1),
           max: new Date(2017, 11, 31, 12, 59, 59)
            },
    defaultValues: {
           min: new Date(2016, 1, 10),
           max: new Date(2017, 4, 22)
            }

});

$(document).ready(function(){
 /** iCheck event theotieuchi la cau hoi */  
    // Make "Item" checked if checkAll are checked
    $('.checkItemQuestion').on('ifChecked', function (event) {
        var parent_id = $(this).attr('data-id');
        $('.checkItemQuestion[parent-id='+parent_id+']').iCheck('check');
        triggeredByChild = false;
    });
    
    // Make "Item" unchecked if checkAll are unchecked
    $('.checkItemQuestion').on('ifUnchecked', function (event) {
        if (!triggeredByChild) {
            var parent_id = $(this).attr('data-id');
            $('.checkItemQuestion[parent-id='+parent_id+']').iCheck('uncheck');
        }
        triggeredByChild = true;
    });
    
    // Remove the checked state from "All" if any checkbox is unchecked
    $('.checkItemQuestion').on('ifUnchecked', function (event) {
        triggeredByChild = true;
        var parent_id = $(this).attr('parent-id');
        $('.checkItemQuestionx[data-id='+parent_id+']').iCheck('uncheck');
    });

    // Make "All" checked if all checkboxes are checked
    $('.checkItemQuestion').on('ifChecked', function (event) {
        var parent_id = $(this).attr('parent-id');
        if ($('.checkItemQuestion[parent-id='+parent_id+']').filter(':checked').length == $('.checkItemQuestion[parent-id='+parent_id+']').length) {
            $('.checkItemQuestion[data-id='+parent_id+']').iCheck('check');
        }
    });
    
/** iCheck event team tree */   
    /* Make team child checked if team parent are checked */
    $('.team-tree-checkbox').on('ifChecked', function (event) {
        var parent_id = $(this).attr('data-id');
        $('.team-tree-checkbox[parent-id='+parent_id+']').iCheck('check');
        triggeredByChild = false;
    });
    
    /* Make team child unchecked if team parent are unchecked */
    $('.team-tree-checkbox').on('ifUnchecked', function (event) {
        if (!triggeredByChild) {
            var parent_id = $(this).attr('data-id');
            $('.team-tree-checkbox[parent-id='+parent_id+']').iCheck('uncheck');
        }
        triggeredByChild = false;
    });
    
    // Remove the checked state from "All" if any checkbox is unchecked
    $('.team-tree-checkbox').on('ifUnchecked', function (event) {
        triggeredByChild = true;
        var parent_id = $(this).attr('parent-id');
        $('.team-tree-checkbox[data-id='+parent_id+']').iCheck('uncheck');
    });

    // Make "All" checked if all checkboxes are checked
    $('.team-tree-checkbox').on('ifChecked', function (event) {
        var parent_id = $(this).attr('parent-id');
        if ($('.team-tree-checkbox[parent-id='+parent_id+']').filter(':checked').length == $('.team-tree-checkbox[parent-id='+parent_id+']').length) {
            $('.team-tree-checkbox[data-id='+parent_id+']').iCheck('check');
        }
    });
});

/**
* click filter
*/
function filterAnalyze(token){
    //get project type checked
    var projectTypeIds = "";
    $('input[type=checkbox][name=project_type]:checked').each(function(){
        if(projectTypeIds == ""){
            projectTypeIds = $(this).val();
        } else{
            projectTypeIds += "," + $(this).val();
        }
    });
    
    //get team checked
    var teamIds = "";
    $('input[class=team-tree-checkbox]:checked').each(function(){
        if(teamIds == ""){
            teamIds = $(this).attr("data-id");
        } else{
            teamIds += "," + $(this).attr("data-id");
        }
    });
    
    var startDate = $('.ui-rangeSlider-leftLabel .ui-rangeSlider-label-value').text();
    var endDate = $('.ui-rangeSlider-rightLabel .ui-rangeSlider-label-value').text();

    $.ajax({
        url: '/css/filterAnalyze',
        type: 'post',
        data: {
            _token: token, 
            startDate: startDate,
            endDate: endDate,
            projectTypeIds: projectTypeIds,
            teamIds: teamIds,
        },
    })
    .done(function (data) { 
        $("div.theotieuchi").html(data);
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });   
        /** iCheck event theotieuchi (ngoai tru theo cau hoi) */
        // Make "Item" checked if checkAll are checked
        $('#checkAll').on('ifChecked', function (event) {
            $('.checkItem').iCheck('check');
            triggeredByChild = false;
        });

        // Make "Item" unchecked if checkAll are unchecked
        $('#checkAll').on('ifUnchecked', function (event) {
            if (!triggeredByChild) {
                $('.checkItem').iCheck('uncheck');
            }
            triggeredByChild = false;
        });

        // Remove the checked state from "All" if any checkbox is unchecked
        $('.checkItem').on('ifUnchecked', function (event) {
            triggeredByChild = true;
            $('#checkAll').iCheck('uncheck');
        });

        // Make "All" checked if all checkboxes are checked
        $('.checkItem').on('ifChecked', function (event) {
            if ($('.checkItem').filter(':checked').length == $('.checkItem').length) {
                $('#checkAll').iCheck('check');
            }
        });
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

function apply(token){
    var projectTypeIds = "";
    $('input[class=checkItem]:checked').each(function(){
        if(projectTypeIds == ""){
            projectTypeIds = $(this).attr("data-id");
        } else{
            projectTypeIds += "," + $(this).attr("data-id");
        }
    });
    
    //get team checked
    var teamIds = "";
    $('input[class=team-tree-checkbox]:checked').each(function(){
        if(teamIds == ""){
            teamIds = $(this).attr("data-id");
        } else{
            teamIds += "," + $(this).attr("data-id");
        }
    });
    var startDate = $('.ui-rangeSlider-leftLabel .ui-rangeSlider-label-value').text();
    var endDate = $('.ui-rangeSlider-rightLabel .ui-rangeSlider-label-value').text();
    
    $.ajax({
        url: '/css/applyAnalyze',
        type: 'post',
        data: {
            _token: token, 
            startDate: startDate,
            endDate: endDate,
            projectTypeIds: projectTypeIds,
            teamIds: teamIds,
        },
    })
    .done(function (data) { 
        $(".ketquaapply").show();
        var countResult = data["cssResult"].length; 
        var html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResult"][i]["stt"]+"</td>";
            html += "<td>"+data["cssResult"][i]["project_name"]+"</td>";
            html += "<td>"+data["cssResult"][i]["teamName"]+"</td>";
            html += "<td>"+data["cssResult"][i]["pmName"]+"</td>";
            html += "<td>"+data["cssResult"][i]["css_created_at"]+"</td>";
            html += "<td>"+data["cssResult"][i]["created_at"]+"</td>";
            html += "<td>"+data["cssResult"][i]["point"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachduan tbody").html(html);
        
        $('#chartAll').highcharts({
            title: {
                text: 'Điểm CSS',

            },

            xAxis: {
                categories: data["dateToHighchart"]
            },
            yAxis: {
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '',
                valueDecimals: 2
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Điểm CSS',
                data: data["pointToHighchart"]
            }]
        });
        
        $('#chartFilter').highcharts({
            title: {
                text: 'Điểm CSS',

            },

            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: data["pointCompareChart"]
        });
        
        //danh sach cau hoi duoi 3 sao
        var countResult = data["duoi3Sao"].length; 
        html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["duoi3Sao"][i]["stt"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["tenDuAn"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["tenCauHoi"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["soSao"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["comment"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["ngayLamCss"]+"</td>";
            html += "<td>"+data["duoi3Sao"][i]["diemCss"]+"</td>";
            html += "</tr>";   
        }
        $("#duoi3sao tbody").html(html);
        
        //danh sach de xuat
        var countResult = data["danhSachDeXuat"].length; 
        html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["danhSachDeXuat"][i]["stt"]+"</td>";
            html += "<td>"+data["danhSachDeXuat"][i]["tenDuAn"]+"</td>";
            html += "<td>"+data["danhSachDeXuat"][i]["commentKhachHang"]+"</td>";
            html += "<td>"+data["danhSachDeXuat"][i]["ngayLamCss"]+"</td>";
            html += "<td>"+data["danhSachDeXuat"][i]["diemCss"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachdexuat tbody").html(html);
        
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}
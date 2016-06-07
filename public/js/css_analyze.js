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
    
    jQuery(document).on("ifChanged","#tcProjectType", function () {
        //show table project type
        $('#tcProjectType').on('ifChecked', function (event) {
            $('.tbl-criteria').hide(); 
            $('table[data-id=tcProjectType]').show();
        });
        
        //show table team
        $('#tcTeam').on('ifChecked', function (event) {
            $('.tbl-criteria').hide();
            $('table[data-id=tcTeam]').show();
        });
        
        //show table pm
        $('#tcPm').on('ifChecked', function (event) {
            $('.tbl-criteria').hide();
            $('table[data-id=tcPm]').show();
        });
        
        //show table brse
        $('#tcBrse').on('ifChecked', function (event) {
            $('.tbl-criteria').hide();
            $('table[data-id=tcBrse]').show();
        });
                
        //show table customer
        $('#tcCustomer').on('ifChecked', function (event) {
            $('.tbl-criteria').hide();
            $('table[data-id=tcCustomer]').show();
        });    
        
        //show table sale
        $('#tcSale').on('ifChecked', function (event) {
            $('.tbl-criteria').hide();
            $('table[data-id=tcSale]').show();
        }); 
    });
});



/**
* click filter
*/
function filterAnalyze(token){
    //hidden and clear empty apply old result
    $(".ketquaapply").hide();
    $("#danhsachduan tbody").html('');
    $("#duoi3sao tbody").html('');
    
    //get criteria type
    var criteriaType = $("input[name=tieuchi]:checked").attr("id");
    
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
    
    //check if don't any check project type or team
    
    if(projectTypeIds == ""){
        if(teamIds == ""){
            $('#modal-warning').modal('show');
            $('#modal-warning .modal-body').html("Chưa chọn loại dự án và team");
        }else{
            $('#modal-warning').modal('show');
            $('#modal-warning .modal-body').html("Chưa chọn loại dự án");
        }
        return false;
    }else{
        if(teamIds == ""){
            $('#modal-warning').modal('show');
            $('#modal-warning .modal-body').html("Chưa chọn team");
            return false;
        }
    }

    
    
    var startDate = $('.ui-rangeSlider-leftLabel .ui-rangeSlider-label-value').text();
    var endDate = $('.ui-rangeSlider-rightLabel .ui-rangeSlider-label-value').text();

    $.ajax({
        url: '/css/filter_analyze',
        type: 'post',
        data: {
            _token: token, 
            startDate: startDate,
            endDate: endDate,
            projectTypeIds: projectTypeIds,
            teamIds: teamIds,
            criteriaType: criteriaType,
        },
    })
    .done(function (data) { 
        $("div.theotieuchi").html(data);
        $(".tbl-criteria").hide();
        $("table[data-id="+criteriaType+"]").show();
        
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
    //get criteria type and id
    var criteriaType = $("input[name=tieuchi]:checked").attr("id");
    var criteriaIds = "";
    
    var classCriteriaCheck = getCriteriaChecked();
    $('input[class='+classCriteriaCheck+']:checked').each(function(){
        if(criteriaIds == ""){
            criteriaIds = $(this).attr("data-id");
        } else{
            criteriaIds += "," + $(this).attr("data-id");
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
    
    //get project type checked
    var projectTypeIds = "";
    $('input[name=project_type]:checked').each(function(){
        if(projectTypeIds == ""){
            projectTypeIds = $(this).val();
        } else{
            projectTypeIds += "," + $(this).val();
        }
    });
    
    var startDate = $('.ui-rangeSlider-leftLabel .ui-rangeSlider-label-value').text();
    var endDate = $('.ui-rangeSlider-rightLabel .ui-rangeSlider-label-value').text();
    
    $.ajax({
        url: '/css/apply_analyze',
        type: 'post',
        data: {
            _token: token, 
            startDate: startDate,
            endDate: endDate,
            criteriaIds: criteriaIds,
            teamIds: teamIds,
            projectTypeIds: projectTypeIds,
            criteriaType: criteriaType,
        },
    })
    .done(function (data) {  console.log(data); 
        $("#startDate_val").val(startDate);
        $("#endDate_val").val(endDate);
        $("#criteriaIds_val").val(criteriaIds);
        $("#teamIds_val").val(teamIds);
        $("#projectTypeIds_val").val(projectTypeIds);
        $("#criteriaType_val").val(criteriaType);
        
        $(".ketquaapply").show();
        $('html, body').animate({
            scrollTop: $(".ketquaapply").offset().top
        }, 100);
        var countResult = data["cssResultPaginate"]["cssResultdata"].length; 
        var html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["stt"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["project_name"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["teamName"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["pmName"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["css_created_at"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["created_at"]+"</td>";
            html += "<td>"+data["cssResultPaginate"]["cssResultdata"][i]["point"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachduan tbody").html(html);
        $("#danhsachduan").parent().find(".pagination").html(data["cssResultPaginate"]["paginationRender"]);
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
        var countResult = data["lessThreeStar"].length; 
        html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["lessThreeStar"][i]["no"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["projectName"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["questionName"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["stars"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["comment"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["makeDateCss"]+"</td>";
            html += "<td>"+data["lessThreeStar"][i]["cssPoint"]+"</td>";
            html += "</tr>";   
        }
        $("#duoi3sao tbody").html(html);
        
        //danh sach de xuat
        var countResult = data["proposes"].length; 
        html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["proposes"][i]["no"]+"</td>";
            html += "<td>"+data["proposes"][i]["projectName"]+"</td>";
            html += "<td>"+data["proposes"][i]["customerComment"]+"</td>";
            html += "<td>"+data["proposes"][i]["makeDateCss"]+"</td>";
            html += "<td>"+data["proposes"][i]["cssPoint"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachdexuat tbody").html(html);
        
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

function getCriteriaChecked(){
    var criteriaType = $("input[name=tieuchi]:checked").attr("id");
    if(criteriaType == "tcProjectType"){
        return "checkProjectTypeItem";
    }else if(criteriaType == "tcTeam"){
        return "checkTeamItem";
    }else if(criteriaType == "tcPm"){
        return "checkPmItem";
    }else if(criteriaType == "tcBrse"){
        return "checkBrseItem";
    }else if(criteriaType == "tcCustomer"){
        return "checkCustomerItem";
    }else if(criteriaType == "tcSale"){
        return "checkSaleItem";
    }else if(criteriaType == "tcQuestion"){
        return "checkQuestionItem";
    }
}

function showAnalyzeListProject(curpage,token){
    var startDate = $("#startDate_val").val();
    var endDate = $("#endDate_val").val();
    var criteriaIds = $("#criteriaIds_val").val();
    var teamIds = $("#teamIds_val").val();
    var projectTypeIds = $("#projectTypeIds_val").val();
    var criteriaType = "";
     
    switch($("#criteriaType_val").val()){
        case 'tcProjectType':
            criteriaType = "projectType";
            break;
        case 'tcTeam':
            criteriaType = "team";
            break;
        case 'tcPm':
            criteriaType = "pm";
            break;
        case 'tcBrse':
            criteriaType = "brse";
            break;
        case 'tcCustomer':
            criteriaType = "customer";
            break;
        case 'tcSale':
            criteriaType = "sale";
            break;
    }
    $.ajax({
        url: baseUrl + 'css/show_analyze_list_project/'+criteriaIds+'/'+teamIds+'/'+ projectTypeIds+'/'+startDate+'/'+endDate+'/'+criteriaType+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) {  console.log(data); 
        var countResult = data["cssResultdata"].length; 
        var html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResultdata"][i]["stt"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["project_name"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["teamName"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["pmName"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["css_created_at"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["created_at"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["point"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachduan tbody").html(html);
        $("#danhsachduan").parent().find(".pagination").html(data["paginationRender"]);
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}
    
    


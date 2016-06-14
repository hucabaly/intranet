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
    
    $(".apply-click-modal").show();
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
        $(".apply-click-modal").hide();
        $("div.theotieuchi").html(data);
        $(".tbl-criteria").hide();
        $(".no-result").hide();
        $("table[data-id="+criteriaType+"]").show();
        $(".no-result-"+criteriaType).show();
        $(document).trigger('icheck');
        $("#startDate_val").val(startDate);
        $("#endDate_val").val(endDate);
        $("#teamIds_val").val(teamIds);
        $("#projectTypeIds_val").val(projectTypeIds);
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

/**
 * apply filter
 * @param string token
 */
function apply(token){
    //get criteria type and id
    var criteriaType = $("input[name=tieuchi]:checked").attr("id");
    var criteriaIds = "";
    
    var classCriteriaCheck = getCriteriaChecked();
    $('input[class='+classCriteriaCheck+']:checked').each(function(){
        if(criteriaIds == ""){
            if(classCriteriaCheck == "checkQuestionItem"){
                if($(this).attr("data-questionid")){
                    criteriaIds = $(this).attr("data-questionid");
                }
            }else{
                criteriaIds = $(this).attr("data-id");
            }
        } else{
            if(classCriteriaCheck == "checkQuestionItem"){
                if($(this).attr("data-questionid")){
                    criteriaIds += "," + $(this).attr("data-questionid");
                }
            }else{
                criteriaIds += "," + $(this).attr("data-id");
            }
            
        }
    });
    
    if(criteriaIds == ""){
        $('#modal-warning').modal('show');
        $('#modal-warning .modal-body').html("Phải chọn ít nhất một dòng ở bảng tiêu chí.");
        return false;
    }
    
    var teamIds = $("#teamIds_val").val();
    var projectTypeIds = $("#projectTypeIds_val").val();
    var startDate = $("#startDate_val").val();
    var endDate = $("#endDate_val").val();
    $(".apply-click-modal").show();
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
    .done(function (data) {  
        $(".apply-click-modal").hide();
        $(".ketquaapply").show();
        if(criteriaType == "tcQuestion"){
            $(".box-select-question").show();
            $(".box-select-question #question-choose").html(data["htmlQuestionList"]);
        }else{
            $(".box-select-question").hide();
            $(".box-select-question #question-choose").html('');
        }
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
                layout: 'horizontal',
                align: 'right',
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
                categories: []
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
                layout: 'horizontal',
                align: 'right',
                borderWidth: 0
            },
            series: data["pointCompareChart"]
        });
        
        if(criteriaType != "tcQuestion"){
            //danh sach cau hoi duoi 3 sao
            var countResult = data["lessThreeStar"]["cssResultdata"].length; 
            html = "";
            if(countResult > 0){
                for(var i=0; i<countResult; i++){
                    html += "<tr>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["no"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["projectName"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["questionName"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["stars"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["comment"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["makeDateCss"]+"</td>";
                    html += "<td>"+data["lessThreeStar"]["cssResultdata"][i]["cssPoint"]+"</td>";
                    html += "</tr>";   
                }
                $("#duoi3sao tbody").html(html);
                $("#duoi3sao").parent().find(".pagination").html(data["lessThreeStar"]["paginationRender"]);

                //danh sach de xuat
                var countResult = data["proposes"]["cssResultdata"].length; 
                html = "";
                for(var i=0; i<countResult; i++){
                    html += "<tr>";
                    html += "<td>"+data["proposes"]["cssResultdata"][i]["no"]+"</td>";
                    html += "<td>"+data["proposes"]["cssResultdata"][i]["projectName"]+"</td>";
                    html += "<td>"+data["proposes"]["cssResultdata"][i]["customerComment"]+"</td>";
                    html += "<td>"+data["proposes"]["cssResultdata"][i]["makeDateCss"]+"</td>";
                    html += "<td>"+data["proposes"]["cssResultdata"][i]["cssPoint"]+"</td>";
                    html += "</tr>";   
                }
                $("#danhsachdexuat tbody").html(html);
                $("#danhsachdexuat").parent().find(".pagination").html(data["proposes"]["paginationRender"]);
            }else{
                $("#duoi3sao tbody").html(noResult);
                $("#danhsachdexuat tbody").html(noResult);
            }
        }else{
            $("#duoi3sao tbody").html('');
            $("#duoi3sao").parent().find(".pagination").html('');
            $("#danhsachdexuat tbody").html('');
            $("#danhsachdexuat").parent().find(".pagination").html('');
        }
        
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

/**
 * get checkbox in tables filter
 */
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

strLoading = '<tr class="loading"><td colspan="7"><img class="loading-img" src="'+baseUrl+'img/loading.gif" /></td></tr>';
noResult = '<tr><td colspan="7" style="text-align:center;">Không có kết quả nào được tìm thấy</td></tr>';

/**
 * Show analyze project list paginate
 * @param int curpage
 * @param string token
 */
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
        case 'tcQuestion':
            criteriaType = "question";
            break;
    }
    
    $("#danhsachduan tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/show_analyze_list_project/'+criteriaIds+'/'+teamIds+'/'+ projectTypeIds+'/'+startDate+'/'+endDate+'/'+criteriaType+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
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

/**
 * Show less 3* list
 * @param int curpage
 * @param string token
 */
function getListLessThreeStar(curpage,token,cssresultids){
    $("#duoi3sao tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/get_list_less_three_star/'+cssresultids+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        var countResult = data["cssResultdata"].length; 
        html = "";
        if(countResult > 0){
            for(var i=0; i<countResult; i++){
                html += "<tr>";
                html += "<td>"+data["cssResultdata"][i]["no"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["projectName"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["questionName"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["stars"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["comment"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["makeDateCss"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["cssPoint"]+"</td>";
                html += "</tr>";   
            }
            $("#duoi3sao tbody").html(html);
            $("#duoi3sao").parent().find(".pagination").html(data["paginationRender"]);
        }else{
            $("#duoi3sao tbody").html(noResult);
        }
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

/**
 * Show proposes list
 * @param int curpage
 * @param string token
 */
function getProposes(curpage,token,cssresultids){
    $("#danhsachdexuat tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/get_proposes/'+cssresultids+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        //danh sach de xuat
        var countResult = data["cssResultdata"].length; 
        html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResultdata"][i]["no"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["projectName"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["customerComment"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["makeDateCss"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["cssPoint"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachdexuat tbody").html(html);
        $("#danhsachdexuat").parent().find(".pagination").html(data["paginationRender"]);
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

$(document).on('icheck', function(){
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    }); 

    var arrCetirea = ['ProjectType','Team','Pm','Brse','Customer','Sale'];
    for(var i=0; i<arrCetirea.length; i++){ 
        // Make "Item" checked if checkAll are checked
        $('#check'+arrCetirea[i]).on('ifChecked', function (event) {
            var id = '.' + $(this).attr("id") + "Item"; 
            $(id).iCheck('check');
            triggeredByChild = false;
        });

        // Make "Item" unchecked if checkAll are unchecked
        $('#check'+arrCetirea[i]).on('ifUnchecked', function (event) {
            var id = '.' + $(this).attr("id") + "Item"; 
            if (!triggeredByChild) {
                $(id).iCheck('uncheck');
            }
            triggeredByChild = false;
        });

        // Remove the checked state from "All" if any checkbox is unchecked
        $('.check'+arrCetirea[i]+'Item').on('ifUnchecked', function (event) {
            triggeredByChild = true;
            var id = '#' + $(this).attr("class"); 
            id = id.replace("Item",""); 
            $(id).iCheck('uncheck');
        });

        // Make "All" checked if all checkboxes are checked
        $('.check'+arrCetirea[i]+'Item').on('ifChecked', function (event) {
            var id = '#' + $(this).attr("class"); 
            id = id.replace("Item","");
            if ($('.' + $(this).attr("class")).filter(':checked').length == $('.' + $(this).attr("class")).length) {
                $(id).iCheck('check');
            }
        });
    }
    
    /** iCheck event theotieuchi la cau hoi */  
    // Make "Item" checked if checkAll are checked
    $('.checkQuestionItem').on('ifChecked', function (event) {
        var parent_id = $(this).attr('data-id');
        $('.checkQuestionItem[parent-id='+parent_id+']').iCheck('check');
        triggeredByChild = false;
    });
    
    // Make "Item" unchecked if checkAll are unchecked
    $('.checkQuestionItem').on('ifUnchecked', function (event) {
        if (!triggeredByChild) {
            var parent_id = $(this).attr('data-id');
            $('.checkQuestionItem[parent-id='+parent_id+']').iCheck('uncheck');
        }
        triggeredByChild = false;
    });
    
    //Remove the checked state from "All" if any checkbox is unchecked
    $('.checkQuestionItem').on('ifUnchecked', function (event) {
        triggeredByChild = true;
        var parent_id = $(this).attr('parent-id');
        $('.checkQuestionItem[data-id='+parent_id+']').iCheck('uncheck');
    });

    // Make "All" checked if all checkboxes are checked
    $('.checkQuestionItem').on('ifChecked', function (event) {
        var parent_id = $(this).attr('parent-id');
        if ($('.checkQuestionItem[parent-id='+parent_id+']').filter(':checked').length == $('.checkQuestionItem[parent-id='+parent_id+']').length) {
            $('.checkQuestionItem[data-id='+parent_id+']').iCheck('check');
        }
    });
    
    //show table project type
    $('#tcProjectType').on('ifChecked', function (event) {
        $('.tbl-criteria').hide(); 
        $('table[data-id=tcProjectType]').show();
        $('.no-result').hide();
        $('.no-result-tcProjectType').show();
    });

    //show table team
    $('#tcTeam').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcTeam]').show();
        $('.no-result').hide();
        $('.no-result-tcTeam').show();
    });

    //show table pm
    $('#tcPm').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcPm]').show();
        $('.no-result').hide();
        $('.no-result-tcPm').show();
    });

    //show table brse
    $('#tcBrse').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcBrse]').show();
        $('.no-result').hide();
        $('.no-result-tcBrse').show();
    });

    //show table customer
    $('#tcCustomer').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcCustomer]').show();
        $('.no-result').hide();
        $('.no-result-tcCustomer').show();
    });    

    //show table sale
    $('#tcSale').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcSale]').show();
        $('.no-result').hide();
        $('.no-result-tcSale').show();
    }); 

    //show table question
    $('#tcQuestion').on('ifChecked', function (event) {
        $('.tbl-criteria').hide();
        $('table[data-id=tcQuestion]').show();
        $('.no-result').hide();
        $('.no-result-tcQuestion').show();
    }); 
}).trigger('icheck'); // trigger it for page load
    
$(document).ready(function(){
   $(".box-select-question #question-choose").change(function(){
       $(".box-select-question #question-choose option[value=0]").remove();
       var questionId = $(this).val(); 
       if(questionId == 0){
           $("#duoi3sao tbody").html('');
           $("#duoi3sao").parent().find(".pagination").html('');
           $("#danhsachdexuat tbody").html('');
           $("#danhsachdexuat").parent().find(".pagination").html('');
       }else{
           var curpage = 1;
           var cssresultids = $("#question-choose option:selected").data("cssresult");
           var token = $("#question-choose option:selected").data("token");
           
           getListLessThreeStarByQuestion(questionId,curpage,token,cssresultids);
           getProposesQuestion(questionId,curpage,token,cssresultids);
       }
   }); 
});

/**
 * Get less 3* list by question
 * @param int questionId
 * @param int curpage
 * @param string token
 * @param string cssresultids
 */
function getListLessThreeStarByQuestion(questionId,curpage,token,cssresultids){
    $("#duoi3sao tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/get_list_less_three_star_question/'+questionId+'/'+cssresultids+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        var countResult = data["cssResultdata"].length; 
        html = "";
        if(countResult > 0){
            for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResultdata"][i]["no"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["projectName"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["questionName"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["stars"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["comment"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["makeDateCss"]+"</td>";
            html += "<td>"+data["cssResultdata"][i]["cssPoint"]+"</td>";
            html += "</tr>";   
            }
            $("#duoi3sao tbody").html(html);
            $("#duoi3sao").parent().find(".pagination").html(data["paginationRender"]);
        }else {
            $("#duoi3sao tbody").html(noResult);
        }
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

/**
 * Show proposes list by question
 * @param int questionId
 * @param int curpage
 * @param string token
 * @param string cssresultids
 */
function getProposesQuestion(questionId,curpage,token,cssresultids){
    $("#danhsachdexuat tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/get_proposes_question/'+questionId+'/'+cssresultids+'/'+curpage,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        //danh sach de xuat
        var countResult = data["cssResultdata"].length; 
        html = "";
        if(countResult > 0){
            for(var i=0; i<countResult; i++){
                html += "<tr>";
                html += "<td>"+data["cssResultdata"][i]["no"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["projectName"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["customerComment"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["makeDateCss"]+"</td>";
                html += "<td>"+data["cssResultdata"][i]["cssPoint"]+"</td>";
                html += "</tr>";   
            }
            $("#danhsachdexuat tbody").html(html);
            $("#danhsachdexuat").parent().find(".pagination").html(data["paginationRender"]);
        }else{
            $("#danhsachdexuat tbody").html(noResult);
        }
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}
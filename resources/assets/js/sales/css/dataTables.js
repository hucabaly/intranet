/**
 * Sort data by comlumns in Project list table
 * @param html element elem
 * @param string token
 */
function sortProject(elem,token){
    var startDate = $("#startDate_val").val();
    var endDate = $("#endDate_val").val();
    var criteriaIds = $("#criteriaIds_val").val();
    var teamIds = $("#teamIds_val").val();
    var projectTypeIds = $("#projectTypeIds_val").val();
    var criteriaType = getCriteriaType($("#criteriaType_val").val());
    var sortType = getSortProjectType($(elem).attr('data-sort-type'));
    var curpage = 1;
    var ariaType = $(elem).attr('aria-type');
    $("#danhsachduan tbody").html(strLoading);
    $.ajax({
        url: baseUrl + 'css/show_analyze_list_project/'+criteriaIds+'/'+teamIds+'/'+ projectTypeIds+'/'+startDate+'/'+endDate+'/'+criteriaType+'/'+curpage+'/'+sortType+'/'+ariaType,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        $(elem).parent().find('th:not(:first)').removeClass('sorting_asc').removeClass('sorting_desc').addClass('sorting');
        $(elem).parent().find('th:not(:first)').attr('aria-type','asc');
        if(ariaType == 'asc'){
            $(elem).attr('aria-type','desc');
            $(elem).removeClass('sorting').removeClass('sorting_desc').addClass('sorting_asc');
        }else if(ariaType == 'desc'){
            $(elem).attr('aria-type','asc');
            $(elem).removeClass('sorting').removeClass('sorting_asc').addClass('sorting_desc');
        }
        var countResult = data["cssResultdata"]["data"].length; 
        var html = "";
        for(var i=0; i<countResult; i++){
            html += "<tr>";
            html += "<td>"+data["cssResultdata"]["data"][i]["id"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["project_name"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["teamName"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["pmName"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["css_end_date"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["css_result_created_at"]+"</td>";
            html += "<td>"+data["cssResultdata"]["data"][i]["point"]+"</td>";
            html += "</tr>";   
        }
        $("#danhsachduan tbody").html(html);
        $("#danhsachduan").parent().find(".pagination").html(data["paginationRender"]);
    })
}

/**
 * Sort data by comlumns in less 3 star list table
 * @param html element elem
 * @param string token
 */
function sortLess3Star(elem,token){
    var cssresultids = $('#cssResultIds').val();
    var sortType = getSortProjectType($(elem).attr('data-sort-type'));
    var curpage = 1;
    var ariaType = $(elem).attr('aria-type');
    $("#duoi3sao tbody").html(strLoading);
    var dataType = $(elem).attr('data-type'); 
    if(dataType === "question"){
        questionId = $(".box-select-question #question-choose").val();
        url = baseUrl + 'css/get_list_less_three_star_question/'+questionId+'/'+cssresultids+'/'+curpage+'/'+sortType+'/'+ariaType;
    }else if(dataType === 'all'){
        url = baseUrl + 'css/get_list_less_three_star/'+cssresultids+'/'+curpage+'/'+sortType+'/'+ariaType;
    }
    $.ajax({
        url: url,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        $(elem).parent().find('th:not(:first)').removeClass('sorting_asc').removeClass('sorting_desc').addClass('sorting');
        $(elem).parent().find('th:not(:first)').attr('aria-type','asc');
        if(ariaType == 'asc'){
            $(elem).attr('aria-type','desc');
            $(elem).removeClass('sorting').removeClass('sorting_desc').addClass('sorting_asc');
        }else if(ariaType == 'desc'){
            $(elem).attr('aria-type','asc');
            $(elem).removeClass('sorting').removeClass('sorting_asc').addClass('sorting_desc');
        }
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
            $("#duoi3sao").parent().find(".pagination").html('');
        }
    })
}

/**
 * Sort data by comlumns in Proposed list table
 * @param html element elem
 * @param string token
 */
function sortProposed(elem,token){
    var cssresultids = $('#cssResultIds').val();
    var sortType = getSortProjectType($(elem).attr('data-sort-type'));
    var curpage = 1;
    var ariaType = $(elem).attr('aria-type');
    $("#danhsachdexuat tbody").html(strLoading);
    var dataType = $(elem).attr('data-type'); 
    if(dataType === "question"){
        questionId = $(".box-select-question #question-choose").val();
        url = baseUrl + 'css/get_proposes_question/'+questionId+'/'+cssresultids+'/'+curpage+'/'+sortType+'/'+ariaType;
    }else if(dataType === 'all'){
        url = baseUrl + 'css/get_proposes/'+cssresultids+'/'+curpage+'/'+sortType+'/'+ariaType;
    }
    $.ajax({
        url: url,
        type: 'post',
        data: {
            _token: token, 
        },
    })
    .done(function (data) { 
        $(elem).parent().find('th:not(:first)').removeClass('sorting_asc').removeClass('sorting_desc').addClass('sorting');
        $(elem).parent().find('th:not(:first)').attr('aria-type','asc');
        if(ariaType == 'asc'){
            $(elem).attr('aria-type','desc');
            $(elem).removeClass('sorting').removeClass('sorting_desc').addClass('sorting_asc');
        }else if(ariaType == 'desc'){
            $(elem).attr('aria-type','asc');
            $(elem).removeClass('sorting').removeClass('sorting_asc').addClass('sorting_desc');
        }
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
            $("#danhsachdexuat").parent().find(".pagination").html('');
        }
    })
}

function getSortProjectType(sortType){
    switch(sortType){
        case 'projectName':
            return 'css.project_name';
            break;
        case 'team':
            return 'css.project_name';
            break;
        case 'pm':
            return 'css.pm_name';
            break;
        case 'projectDate':
            return 'css.end_date';
            break;    
        case 'makeDate':
            return 'result_make';
            break;
        case 'projectPoint':
            return 'result_point';
            break;
        case 'questionName':
            return 'question_name';
            break;
        case 'questionPoint':
            return 'point';
            break;
        case 'customerComment':
            return 'comment';
            break;
        case 'proposed':
            return 'proposed';
            break;
        case 'resultId':
            return 'id';
            break;
    }
}
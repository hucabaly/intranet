/**
 * Sort by project name in Project list table
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
            html += "<td>"+data["cssResultdata"]["data"][i]["stt"]+"</td>";
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
        $(document).ready(function() {
            $('#danhsachduan').dataTable();
          });
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
    }
}
@extends('layouts.default')

@section('content')

<div class="container content-container" style="background-color: #fff;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h3>Cập nhật CSS</h3>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                       <form id="frm_create_css" method="post" action="/css/save"  >
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <input type="hidden" name="create_or_update" value="update">
                          <input type="hidden" name="css_id" value="{{$css->id}}">
                          <table class="table table-create-css">

                           <tr>
                               <td class="title">Họ và tên người gửi (Sales)</td>
                               <td>
                                   <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                                   <input type="text" class="form-control width-300" id="user_name" name="user_name" value="{{$user->name}}" disabled="disabled">
                               </td>
                               <td style="width:50px"></td>
                               <td class="title2">Tên dự án</td>
                               <td><input type="text" class="form-control width-300" id="project_name" name="project_name" value="{{$css->project_name}}"  ></td>

                           </tr>
                           <tr>
                               <td class="title">Họ và tên người gửi (Tiếng Nhật)</td>
                               <td><input type="text" class="form-control width-300" id="japanese_name" name="japanese_name" value="{{$user->japanese_name}}" <?php if($user->japanese_name != ""){ echo 'disabled="disabled"'; } ?> ></td>
                               <td></td>
                               <td class="title2">Các team liên quan</td>
                               <td>
                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">Set team</button>
                                   <label class="set_team">
                                     {{$str_teams_set_name}}
                                   </label>
                                   <input id="team_id_check" name="team_id_check" type="text" value="1" style="visibility: hidden;">
                                   @foreach($teams_set as $team)
                                      <input class="team_id" type="hidden" name="teams[{{$team->id}}]" value="{{$team->name}}" />
                                   @endforeach
                               </td>

                           </tr>
                           <tr>
                               <td class="title">Tên công ty khách hàng</td>
                               <td><input type="text" class="form-control width-300" id="company_name" name="company_name" value="{{$css->company_name}}" ></td>
                               <td ></td>
                               <td class="title2">Người phụ trách dự án (PM)</td>
                               <td><input type="text" class="form-control width-300" id="pm_name" name="pm_name" value="{{$css->pm_name}}" ></td>

                           </tr>
                           <tr>
                               <td class="title">Khách hàng</td>
                               <td><input type="text" class="form-control width-300" id="customer_name" name="customer_name" value="{{$css->customer_name}}" ></td>
                               <td ></td>
                               <td class="title2">BrSE của dự án</td>
                               <td><input type="text" class="form-control width-300" id="brse_name" name="brse_name" value="{{$css->brse_name}}" ></td>

                           </tr>
                           <tr>
                               <td class="title">Project type</td>
                               <td class="project_type">
                                   @foreach($projects as $pj)
                                   <label class="radio-inline">
                                      <input type="radio" name="project_type_id" value="{{$pj->id}}">{{$pj->name}}
                                  </label>
                                  @endforeach 
                              </td>
                              <td ></td>
                              <td class="title2">Thơì gian dự án</td>
                              <td class="container-date ">
                               <div class="input-group-addon calendar-button" target="start_date" onclick="showCalendar(this);">
                                   <i class="fa fa-calendar">
                                   </i>
                               </div>
                               <input type='text' class="form-control date start-date" id="start_date" name="start_date" data-provide="datepicker" placeholder="DD/MM/YYYY" value="{{$css->start_date}}" />
                               &nbsp; ~ &nbsp; &nbsp;
                               <div class="input-group-addon calendar-button" target="end_date"  onclick="showCalendar(this);">
                                   <i class="fa fa-calendar">
                                   </i>
                               </div>
                               <input type='text' class="form-control date end-date" id="end_date" name="end_date" data-provide="datepicker" placeholder="DD/MM/YYYY" value="{{$css->end_date}}"  />
                           </td>

                       </tr>
                       <tr>
                           <td colspan="5" class="container-button-css" style="text-align: center;">
                               <button class="btn btn-primary" type="submit" >Cập nhật CSS</button>
                           </td>
                       </tr>
                   </table>
               </form>  
           </div>
       </div>
   </div>

</div>
</div>
</div>

<div class="modal fade" id="teamsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <form id="frm-add-teams">
                    <div class="remove-team"><img src="{{ URL::asset('img/remove_icon.png') }}" onclick="remove_team();"></div>
                    <div class="container-teams">
                        <ul class="list-teams left-list">
                            @foreach($teams as $team)
                            <li id="{{$team->id}}" onclick="set_true(this);">{{$team->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn-add-team" type="button" onclick="add_team();">Add >></button>
                    <div class="container-teams">
                        <ul class="list-teams right-list">
                            @foreach($teams_set as $team)
                            <li id="{{$team->id}}" onclick="set_true(this);">{{$team->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary create-css" onclick="set_team();">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Styles -->
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" />
<link href="{{ asset('css/css-screen.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    var teamArray = []; // bien luu team set tren popup
    <?php foreach($teams_set as $team): ?>
      teamArray.push([<?php echo $team->id ?>, '<?php echo $team->name ?>']); 
    <?php endforeach; ?>
    
    
    /** 
        click img calendar to show calendar
        **/
        function showCalendar(x){
            var target = $(x).attr("target");
            $("#"+target).focus();
        }

        /** end showCalendar() **/

    /** 
        in popup set team
        add team from left column to right column
        **/
        function add_team(){
            if($(".modal-body ul.left-list li[set=true]").length > 0){
                var team_id = $(".modal-body ul.left-list li[set=true]").attr("id");
                var chosen = false;

                $( ".modal-body ul.right-list li" ).each(function( index ) {

                    if($(this).attr("id") == team_id){
                        alert("Da add roi");
                        chosen = true;
                        return false;
                    }
                });
                if(chosen == false){
                    var element_add = $(".modal-body ul.left-list li[set=true]")[0].outerHTML;
                    

                    $(".modal-body ul.right-list").append(element_add);
                    $(".modal-body ul.list-teams li").css("background-color","").attr("set","false");
                }
            }       

        }

        /** end add_team() **/

    /** 
        in popup set team
        remove team from right column
        **/

        function remove_team(){
            if($(".modal-body ul.right-list li[set=true]").length > 0){
                
                $(".modal-body ul.right-list li[set=true]").remove();
            }


        }

        /** end remove_team() **/


    /** 
        in popup team
        when click (choose) a team -> change bgcolor and set attribute set=true
        **/
        function set_true(x){
            $(x).parent().find("li").css("background-color","").attr("set","false");
            $(x).css("background-color","rgb(33, 42, 109)").attr("set","true");
        }

        /** end set_true **/

        function set_team(){
           var elements = "";
           var str = "";

           teamArray = [];
           if($( ".modal-body ul.right-list li" ).length > 0){
              $( ".modal-body ul.right-list li" ).each(function( index ) {
                var team_id = $(this).attr("id");
                var team_name = $(this).html();
                
                elements += '<input class="team_id" type="hidden" name="teams['+team_id+']" value="'+team_name+'" />';    
                if(str == ""){
                     str = team_name;
                 }else{
                     str += ', ' + team_name; 
                 }

                teamArray.push([team_id,team_name]);

              });
           }
           
           //get ra text hien ten cac team da add
           $(".set_team").html(str);

           //xoa cac team_id cu, sau do append team moi
           $(".set_team").parent().find(".team_id").remove();
           $(".set_team").parent().append(elements);

           //#team_id_check de validate, "" -> chua chon team va nguoc lai
           if(str == ""){
                $('#team_id_check').val("")
           }else{
                $('#team_id_check').val("1")
           }
           
        //close popup
        $('#teamsModal').modal('hide');
    }

    /*
       set team vao cot right cua popup team
    */

    function set_teams_popup(){
        $(".right-list").html("");
        for(var i=0; i<teamArray.length;i++){
              $(".right-list").append('<li id="'+teamArray[i][0]+'" onclick="set_true(this);">'+teamArray[i][1]+'</li>');
        }
    }

    $(document).ready(function(){
        $(".project_type input[type=radio]:first-child").prop('checked',true);
    });
    

    $(document).ready(function() {

         
         $("#frm_create_css").validate({
           rules: {
                
                team_id_check: "required",
               company_name: "required",
               customer_name: "required",
               project_name: "required",
               pm_name: "required",
               brse_name: "required",
               start_date: "required",
               end_date: "required",
           },
           messages: {
               team_id_check: "Vui lòng chọn team cho dự án",
               company_name: "Vui lòng nhập tên công ty",
               customer_name: "Vui lòng nhập tên khách hàng",
               project_name: "Vui lòng nhập tên dự án",
               pm_name: "Vui lòng nhập tên PM",
               brse_name: "Vui lòng nhập tên BrSE",
               start_date: "Vui lòng nhập ngày bắt đầu dự án",
               end_date: "Vui lòng nhập ngày kết thúc dự án",
           }
        });
    });

  

    
</script>
@endsection
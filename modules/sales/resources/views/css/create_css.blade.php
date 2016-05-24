@extends('layouts.default')
<?php
use Rikkei\Team\View\TeamList;
use Rikkei\Core\View\Form;
?>

@section('content')

<div class="container content-container box box-primary" style="background-color: #fff;">
    <div class="row">
        <div class="col-md-12">
            <div class="box-header with-border">
                <h3 class="box-title">Tạo CSS gửi cho khách hàng</h3>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                       <form id="frm_create_css" method="post" action="/css/save"  >
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <table class="table table-create-css">
                          <input type="hidden" name="create_or_update" value="create">
                           <tr>
                               <td class="title"><label>Họ và tên người gửi (Sales) </label></td>
                               <td>
                                   <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                                   <input type="text" class="form-control width-300" id="user_name" name="user_name" value="{{$user->name}}" disabled="disabled">
                               </td>
                               <td style="width:50px"></td>
                               <td class="title2"><label class="project_name">Tên dự án</label> <span class="required">*</label></td>
                               <td><input type="text" class="form-control width-300" id="project_name" name="project_name" tabindex=4 ></td>

                           </tr>
                           <tr>
                               <td class="title"><label>Họ và tên người gửi (Tiếng Nhật)</label></td>
                               <td><input type="text" class="form-control width-300" id="japanese_name" name="japanese_name" value="{{$user->japanese_name}}" <?php if($user->japanese_name != ""){ echo 'disabled="disabled"'; } ?> ></td>
                               <td></td>
                               <td class="title2"><label>Các team liên quan</label> <span class="required">*</span></td>
                               <td>
                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">Set team</button>
                                   <label class="set_team"></label>
                                   <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden;">
                               </td>

                           </tr>
                           <tr>
                               <td class="title"><label>Tên công ty khách hàng</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="company_name" name="company_name" tabindex=2 ></td>
                               <td ></td>
                               <td class="title2"><label>Người phụ trách dự án (PM)</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="pm_name" name="pm_name" tabindex=5></td>

                           </tr>
                           <tr>
                               <td class="title"><label>Khách hàng</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="customer_name" name="customer_name" tabindex=3 ></td>
                               <td ></td>
                               <td class="title2"><label>BrSE của dự án</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="brse_name" name="brse_name" tabindex=6 ></td>

                           </tr>
                           <tr>
                               <td class="title"><label>Project type</label> <span class="required">*</span></td>
                               <td class="project_type">
                                   @foreach($projects as $pj)
                                   <label class="radio-inline">
                                      <input type="radio" name="project_type_id" value="{{$pj->id}}">{{$pj->name}}
                                  </label>
                                  @endforeach 
                              </td>
                              <td ></td>
                              <td class="title2"><label>Thơì gian dự án</label> <span class="required">*</span></td>
                              <td class="container-date ">
                               <div class="input-group-addon calendar-button" target="start_date" onclick="showCalendar(this);">
                                   <i class="fa fa-calendar">
                                   </i>
                               </div>
                               <input type='text' class="form-control date start-date" id="start_date" name="start_date" data-provide="datepicker" placeholder="DD/MM/YYYY" tabindex=7 />
                               &nbsp; ~ &nbsp; &nbsp;
                               <div class="input-group-addon calendar-button" target="end_date"  onclick="showCalendar(this);">
                                   <i class="fa fa-calendar">
                                   </i>
                               </div>
                               <input type='text' class="form-control date end-date" id="end_date" name="end_date" data-provide="datepicker" placeholder="DD/MM/YYYY" tabindex=8  />
                           </td>

                       </tr>
                       <tr>
                           <td colspan="5" class="container-button-css" style="text-align: center;">
                               <button class="btn btn-primary" type="submit" >Tạo css</button>
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
                    <div class="container-teams left-list">
                        {!! TeamList::getTreeHtml(Form::getData('id')) !!}
                    </div>
                    <button class="btn-add-team" type="button" onclick="add_team();">Add >></button>
                    <div class="container-teams">
                        <ul class="list-teams right-list">

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
  
  /** 
  click img calendar to show calendar
  **/
  function showCalendar(x){
      var target = $(x).attr("target");
      $("#"+target).focus();
  }

  /** end showCalendar() **/

  /** 
    popup set team
    add team from left column to right column
  **/
  function add_team(){
    if($(".team-tree ul a[set=true]").length > 0){
        var team_id = $(".team-tree ul a[set=true]").attr("data-id");
        var chosen = false;

        $( ".modal-body ul.right-list li" ).each(function( index ) {

            if($(this).attr("id") == team_id){
                alert("Da add roi");
                chosen = true;
                return false;
            }
        });
        if(chosen == false){
            var element_add = $(".team-tree ul a[set=true]").parent().parent()[0].outerHTML;
            //$(".team-tree ul a[set=true]")

            $(".modal-body ul.right-list").append(element_add);
            $(".modal-body ul li a[set=true]").attr("set","false");
            $(".modal-body ul li").css("background-color","");
        }
    }
  }

  /** end add_team() **/

  /** 
    in popup set team
    remove team from right column
  **/

  function remove_team(){
      if($(".modal-body ul.right-list li a[set=true]").length > 0){
        $(".modal-body ul.right-list li a[set=true]").parent().parent().remove();
      }
  }

  /** end remove_team() **/

  /** 
  in popup team
  when click (choose) a team -> change bgcolor and set attribute set=true
  **/
  function set_true(x){
      $(".team-tree a").attr("set","false");
      $(".team-tree ul li").css("background-color","").attr("set","false");
      $(x).attr("set","true");
      $(x).parent().parent().css("background-color","rgb(33, 42, 109)");

      $(".right-list a").attr("set","false");
      $(".right-list li").css("background-color","").attr("set","false");
      $(x).attr("set","true");
      $(x).parent().parent().css("background-color","rgb(33, 42, 109)");
  }

  /** end set_true **/

  function set_team(){
     var elements = "";
     var str = "";
     teamArray = [];

     $( ".modal-body ul.right-list li a" ).each(function( index ) {
        var team_id = $(this).attr("data-id");
        var team_name = $(this).html();
        elements += '<input class="team_id" type="hidden" name="teams['+team_id+']" value="'+team_name+'" />';    
        if (str == "") {
             str = team_name;
        } else{
             str += ', ' + team_name; 
        }

        teamArray.push([team_id, team_name]);
     });

     $(".set_team").html(str);
     $(".set_team").parent().append(elements);
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
        $(".right-list").append('<li set="false"><label class="team-item"><a data-id="'+teamArray[i][0]+'" onclick="set_true(this)">'+teamArray[i][1]+'</a></label></li>');
      }
  }

  $(document).ready(function(){
      $(".project_type input[type=radio]:first-child").prop('checked',true);
      $(".team-tree a").removeAttr("href");
      $(".team-tree li ul a").attr("onclick","set_true(this)");

      //hide calendar sau khi select
      $('#start_date').on('changeDate', function(ev){
          $(this).datepicker('hide');
          $('#end_date').focus();
      });

      $('#end_date').on('changeDate', function(ev){
          $(this).datepicker('hide');
      });

      //
      $('input[type=radio][name=project_type_id]').change(function() {
        if (this.value == '1') {
            $(".project_name").text('Tên OSDC');
        }
        else if (this.value == '2') {
            $(".project_name").text('Tên dự án');
        }
      });
  });
  
  $(document).ready(function() {    
    jQuery.validator.addMethod("greaterThan", 
      function(value, element, params) {
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }

        return isNaN(value) && isNaN($(params).val()) 
              || (Number(value) > Number($(params).val())); 
      },'Must be greater than {0}.');

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
</script>
@endsection
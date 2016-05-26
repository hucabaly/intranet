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
                <h3 class="box-title">{{ trans('sales::view.Create CSS title') }}</h3>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                       <form id="frm_create_css" method="post" action="/css/save"  >
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <table class="table table-create-css">
                          <input type="hidden" name="create_or_update" value="create">
                           <tr>
                               <td class="title"><label>{{ trans('sales::view.Sale name') }} </label></td>
                               <td>
                                   <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                                   <input type="text" class="form-control width-300" id="user_name" name="user_name" value="{{$user->name}}" disabled="disabled">
                               </td>
                               <td style="width:50px"></td>
                               <td class="title2"><label class="project_name">{{ trans('sales::view.Project base name') }}</label> <span class="required">*</label></td>
                               <td><input type="text" class="form-control width-300" id="project_name" name="project_name" tabindex=4 ></td>

                           </tr>
                           <tr>
                               <td class="title"><label>{{ trans('sales::view.Sale name jp') }}</label></td>
                               <td><input type="text" class="form-control width-300" id="japanese_name" name="japanese_name" value="{{$user->japanese_name}}" <?php if($user->japanese_name != ""){ echo 'disabled="disabled"'; } ?> ></td>
                               <td></td>
                               <td class="title2"><label>{{ trans('sales::view.Team relate') }}</label> <span class="required">*</span></td>
                               <td>
                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">{{ trans('sales::view.Set team') }}</button>
                                   <label class="set_team"></label>
                                   <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden;">
                               </td>

                           </tr>
                           <tr>
                               <td class="title"><label>{{ trans('sales::view.Customer company') }}</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="company_name" name="company_name" tabindex=2 ></td>
                               <td ></td>
                               <td class="title2"><label>{{ trans('sales::view.PM name') }}</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="pm_name" name="pm_name" tabindex=5></td>

                           </tr>
                           <tr>
                               <td class="title"><label>{{ trans('sales::view.Customer name') }}</label> <span class="required">*</span></td>
                               <td class="customer_name_td">
                                   <input type="text" class="form-control width-300" id="customer_name" name="customer_name" tabindex=3 >
                                   <label class="sama_label">様</label>
                               </td>
                               <td ></td>
                               <td class="title2"><label>{{ trans('sales::view.BrSE name') }}</label> <span class="required">*</span></td>
                               <td><input type="text" class="form-control width-300" id="brse_name" name="brse_name" tabindex=6 ></td>

                           </tr>
                           <tr>
                               <td class="title"><label>{{ trans('sales::view.Project type') }}</label> <span class="required">*</span></td>
                               <td class="project_type">
                                   @foreach($projects as $pj)
                                   <label class="radio-inline">
                                      <input type="radio" name="project_type_id" value="{{$pj->id}}">{{$pj->name}}
                                  </label>
                                  @endforeach 
                              </td>
                              <td ></td>
                              <td class="title2"><label>{{ trans('sales::view.Project date') }}</label> <span class="required">*</span></td>
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
                               <button class="btn btn-primary" type="submit" >{{ trans('sales::view.Create CSS') }}</button>
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
                    <button class="btn-add-team" type="button" onclick="add_team();">{{ trans('sales::view.Add >>') }}</button>
                    <div class="container-teams">
                        <ul class="list-teams right-list">

                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary create-css" onclick="set_team_to_css();">{{ trans('sales::view.OK') }}</button>
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
<script src="{{ asset('js/css.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[type=radio][name=project_type_id]').change(function () {
            if (this.value == '1') {
                $(".project_name").text('<?php echo trans('sales::view.Project OSDC name') ?>');
            } else if (this.value == '2') {
                $(".project_name").text('<?php echo trans('sales::view.Project base name') ?>');
            }
        });
    });
</script>
@endsection
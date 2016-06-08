@extends('layouts.default')
<?php
use Rikkei\Team\View\TeamList;
use Rikkei\Core\View\Form;
?>

@section('content')

<div class="container content-container box box-primary css-update-page" style="background-color: #fff;">
    <div class="row">
        <div class="col-md-12">
            <div class="box-header with-border">
                <h3>Cập nhật CSS</h3>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                       <form id="frm_create_css" method="post" action="/css/save"  >
                          <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                          <input type="hidden" name="create_or_update" value="update">
                          <input type="hidden" name="css_id" value="{{$css->id}}">
                          <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_name">{{ trans('sales::view.Sale name') }}</label>
                                        <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
                                        <input type="text" class="form-control" id="user_name" name="user_name" value="{{$user->name}}" disabled="disabled">
                                    </div>
                                    <div class="form-group">
                                        <label for="form_name">{{ trans('sales::view.Sale name jp') }}</label>
                                        <input type="text" class="form-control" id="japanese_name" name="japanese_name" value="{{$user->japanese_name}}" <?php if($user->japanese_name != ""){ echo 'disabled="disabled"'; } ?> >
                                    </div>
                                    <div class="form-group">
                                        <label for="form_name">{{ trans('sales::view.Customer company') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" tabindex=2  value="{{$css->company_name}}" >
                                    </div>
                                    <div class="form-group" style="position: relative;">
                                        <label for="form_name">{{ trans('sales::view.Customer name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" tabindex=3 value="{{$css->customer_name}}" >
                                        <label class="sama_label">様</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="form_name">{{ trans('sales::view.Project type') }} <span class="required">*</span></label>
                                        <div>
                                        @foreach($projects as $pj)
                                            <label class="radio-inline">
                                                @if($pj->id == $css->project_type_id)
                                                    <input type="radio" checked="checked" name="project_type_id" value="{{$pj->id}}">&nbsp;{{$pj->name}}
                                                @else
                                                    <input type="radio" name="project_type_id" value="{{$pj->id}}">&nbsp;{{$pj->name}}
                                                @endif
                                                
                                            </label>
                                        @endforeach 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Project base name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="project_name" name="project_name" tabindex=4 value="{{$css->project_name}}"  >
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Team relate') }} <span class="required">*</span></label>
                                        <div>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">{{ trans('sales::view.Set team') }}</button>
                                            <input id="team_id_check" name="team_id_check" type="text" value="1" style="visibility: hidden; position: absolute;">
                                            <label class="set_team">{{$str_teams_set_name}}</label>
                                            @foreach($teams_set as $team)
                                                <input class="team_id" type="hidden" name="teams[{{$team->id}}]" value="{{$team->name}}" />
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.PM name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="pm_name" name="pm_name" tabindex=5 value="{{$css->pm_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.BrSE name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="brse_name" name="brse_name" tabindex=6 value="{{$css->brse_name}}" >
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Project date') }} <span class="required">*</span></label>
                                        <div >
                                            <div style="position: relative; display: inline;">
                                                <div class="input-group-addon calendar-button" target="start_date" onclick="showCalendar(this);">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type='text' class="form-control date start-date" id="start_date" name="start_date" data-provide="datepicker" placeholder="DD/MM/YYYY" tabindex=7 value="{{date('m/d/Y',strtotime($css->start_date))}}" />
                                                &nbsp; ~ &nbsp; &nbsp;
                                            </div>
                                            <div style="position: relative; display: inline;">
                                                <div class="input-group-addon calendar-button" target="end_date"  onclick="showCalendar(this);">
                                                    <i class="fa fa-calendar">
                                                    </i>
                                                </div>
                                                <input type='text' class="form-control date end-date" id="end_date" name="end_date" data-provide="datepicker" placeholder="DD/MM/YYYY" tabindex=8 value="{{date('m/d/Y',strtotime($css->end_date))}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding:20px; text-align: center;"><button class="btn btn-primary" type="submit" >{{ trans('sales::view.Update CSS') }}</button></div>
                            </div>
                          
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
                <ul class="list-team-tree">{!! $htmlTeam !!}</ul>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="set_team_to_css();">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Styles -->
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" />
<link href="{{ asset('css/css-screen.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('adminlte/plugins/iCheck/minimal/_all.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('adminlte/plugins/iCheck/icheck.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/css.js') }}"></script>
<script type="text/javascript">
    var teamArray = []; 
    <?php foreach($teams_set as $team): ?>
      teamArray.push([<?php echo $team->id ?>, '<?php echo $team->name ?>']); 
    <?php endforeach; ?>
</script>
@endsection
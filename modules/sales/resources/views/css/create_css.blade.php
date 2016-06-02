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
                            <input type="hidden" name="create_or_update" value="create">
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
                                        <input type="text" class="form-control" id="company_name" name="company_name" tabindex=2 >
                                    </div>
                                    <div class="form-group" style="position: relative;">
                                        <label for="form_name">{{ trans('sales::view.Customer name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" tabindex=3 >
                                        <label class="sama_label">様</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="form_name">{{ trans('sales::view.Project type') }} <span class="required">*</span></label>
                                        <div>
                                        @foreach($projects as $pj)
                                            <label class="radio-inline">
                                                <input type="radio" checked="checked" name="project_type_id" value="{{$pj->id}}">{{$pj->name}}
                                            </label>
                                        @endforeach 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Project base name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="project_name" name="project_name" tabindex=4 >
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Team relate') }} <span class="required">*</span></label>
                                        <div>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">{{ trans('sales::view.Set team') }}</button>
                                            <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden;">
                                        </div>
                                        <label class="set_team"></label>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.PM name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="pm_name" name="pm_name" tabindex=5>
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.BrSE name') }} <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="brse_name" name="brse_name" tabindex=6 >
                                    </div>
                                    <div class="form-group">
                                        <label for="form_lastname">{{ trans('sales::view.Project date') }} <span class="required">*</span></label>
                                        <div style="position: relative;">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding:20px; text-align: center;"><button class="btn btn-primary" type="submit" >{{ trans('sales::view.Create CSS') }}</button></div>
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
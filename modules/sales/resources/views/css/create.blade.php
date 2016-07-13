@extends('layouts.default')

@section('content')

<div class="container box box-primary css-create-page">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('sales::view.Create CSS title') }}</h3>
    </div>
            
    <div class="span12">
       <form id="frm_create_css" method="post" action="/css/save"  >
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <input type="hidden" name="create_or_update" value="create">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" id="employee_id" name="employee_id" value="{{$employee->id}}">
                        <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{$employee->name}}" disabled="disabled" placeholder="{{ trans('sales::view.Create.Sale name') }}">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="japanese_name" name="japanese_name" value="{{$employee->japanese_name}}" tabindex=1 maxlength="100" placeholder="{{ trans('sales::view.Create.Sale name jp') }}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="company_name" name="company_name" tabindex=2 maxlength="200" placeholder="{{ trans('sales::view.Customer company name') }}"  >
                        <label class="sama_label">様</label>
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="customer_name" name="customer_name" tabindex=3  maxlength="100" placeholder="{{ trans('sales::view.Customer name') }}" >
                        <label class="sama_label">様</label>
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group">
                        <label for="project_type_id">{{ trans('sales::view.Project type') }} <span class="required">*</span></label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" checked="checked" name="project_type_id" value="1">&nbsp;{{trans('sales::view.Osdc')}}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" checked="checked" name="project_type_id" value="2">&nbsp;{{trans('sales::view.Project base')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="project_name"> </label>
                        <input type="text" class="form-control" id="project_name" name="project_name" tabindex=4  maxlength="200" placeholder="{{ trans('sales::view.Project base name') }}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">{{ trans('sales::view.Create.Set team relate') }}</button>
                            &nbsp;<span class="required">(*)</span>
                            <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden; position: absolute;">
                            <label class="set_team"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="pm_name" name="pm_name" tabindex=5  maxlength="100" placeholder="{{ trans('sales::view.PM name') }} " >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="brse_name" name="brse_name" tabindex=6  maxlength="100" placeholder="{{ trans('sales::view.BrSE name') }}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group">
                        <label for="start_date">{{ trans('sales::view.Project date') }} <span class="required">*</span></label>
                        <div >
                            <div class="container-date">
                                <div class="input-group-addon calendar-button" target="start_date" onclick="showCalendar(this);">
                                    <i class="fa fa-calendar">
                                    </i>
                                </div>
                                <input type='text' class="form-control date start-date" id="start_date" name="start_date" data-provide="datepicker" placeholder="MM/DD/YYYY" tabindex=7 />
                                &nbsp; ~ &nbsp; &nbsp;
                            </div>
                            <div class="container-date">
                                <div class="input-group-addon calendar-button" target="end_date"  onclick="showCalendar(this);">
                                    <i class="fa fa-calendar">
                                    </i>
                                </div>
                                <input type='text' class="form-control date end-date" id="end_date" name="end_date" data-provide="datepicker" placeholder="MM/DD/YYYY" tabindex=8  />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="padding:20px; text-align: center;"><button class="btn btn-primary" type="submit" >{{ trans('sales::view.Create CSS') }}</button></div>
            </div>
        </form>  
    </div>
</div>

<div class="modal fade" id="teamsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">{{ trans('sales::view.Set relation teams') }}</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="list-team-tree">{!! $htmlTeam !!}</ul>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="set_team_to_css();">{{ trans('sales::view.OK') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Styles -->
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" />
<link href="{{ asset('sales/css/sales.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('adminlte/plugins/iCheck/minimal/_all.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('lib/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/iCheck/icheck.js') }}"></script>
<script src="{{ asset('sales/js/css/create.js') }}"></script>
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
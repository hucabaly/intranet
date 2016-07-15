@extends('layouts.default')

@section('title')
    @if($save === 'create')
        {{ trans('sales::view.Create CSS title') }}
    @else
        {{ trans('sales::view.Update CSS title') }}
    @endif
@endsection

@section('content')

<div class="container box box-primary css-create-page">
    <div class="box-header with-border">
        <h3 class="box-title">
            @if($save === 'create')
                {{ trans('sales::view.Create CSS title') }}
            @else
                {{ trans('sales::view.Update CSS title') }}
            @endif
        </h3>
    </div>
            
    <div class="css-create-body">
       <form id="frm_create_css" method="post" action="/css/save"  >
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            @if($save === 'create')
            <input type="hidden" name="create_or_update" value="create">
            @else
            <input type="hidden" name="create_or_update" value="update">
            <input type="hidden" name="css_id" value="{{$css->id}}">
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group position-relative">
                        <input type="hidden" id="employee_id" name="employee_id" value="{{$employee->id}}">
                        <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{$employee->name}}" disabled="disabled" placeholder="{{ trans('sales::view.Create.Sale name') }}">
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="japanese_name" name="japanese_name" value="{{$employee->japanese_name}}" tabindex=1 maxlength="100" placeholder="{{ trans('sales::view.Create.Sale name jp') }}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="company_name" name="company_name" tabindex=2 maxlength="200" placeholder="{{ trans('sales::view.Customer company name') }}" value="{{$css->company_name}}" >
                        <label class="sama_label">様</label>
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="customer_name" name="customer_name" tabindex=3  maxlength="100" placeholder="{{ trans('sales::view.Customer name') }}" value="{{$css->customer_name}}" >
                        <label class="sama_label">様</label>
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <label for="project_type_id">{{ trans('sales::view.Project type') }} <span class="required position-inherit">(*)</span></label>
                        <div class="margin-top-10">
                            @foreach($projectType as $item)
                            <label class="radio-inline">
                                @if($item->id === $css->project_type_id)
                                    <input type="radio" checked="checked" name="project_type_id" value="{{$item->id}}">&nbsp;{{$item->name}}
                                @else
                                    <input type="radio" name="project_type_id" value="{{$item->id}}">&nbsp;{{$item->name}}
                                @endif    
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group position-relative">
                        <label for="project_name"> </label>
                        <input type="text" class="form-control" id="project_name" name="project_name" tabindex=4  maxlength="200" placeholder="{{ trans('sales::view.Project base name') }}" value="{{$css->project_name}}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#teamsModal" data-whatever="@mdo" onclick="set_teams_popup();">{{ trans('sales::view.Create.Set team relate') }}</button>
                            &nbsp;<span class="required position-inherit">(*)</span>
                            @if($save === 'create')
                                <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden; position: absolute;">
                            @else
                                <input id="team_id_check" name="team_id_check" type="text" value="1" style="visibility: hidden; position: absolute;">
                            @endif
                            <input id="team_id_check" name="team_id_check" type="text" value="" style="visibility: hidden; position: absolute;">
                            <label class="set_team">
                                @if($save === 'update')
                                    {{$strTeamsNameSet}}
                                @endif
                            </label>
                            @if($save === 'update')
                                @foreach($teamsSet as $team)
                                    <input class="team_id" type="hidden" name="teams[{{$team->id}}]" value="{{$team->name}}" />
                                @endforeach
                            @endif
                            
                        </div>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="pm_name" name="pm_name" tabindex=5  maxlength="100" placeholder="{{ trans('sales::view.PM name') }} " value="{{$css->pm_name}}" >
                        &nbsp;<span class="required">(*)</span>
                    </div>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" id="brse_name" name="brse_name" tabindex=6  maxlength="100" placeholder="{{ trans('sales::view.BrSE name') }}" value="{{$css->brse_name}}" >
                        &nbsp;<span class="required">(*)</span>
                    </div> 
                    <div class="form-group position-relative">
                        <label class="col-xs-12 padding-0" for="start_date">{{ trans('sales::view.Project date') }} <span class="required position-inherit position-reset">(*)</span></label>
                        <div class="col-xs-12 col-sm-6 padding-0" >
                            <div class="input-group margin-top-10"> 
                                <span class="input-group-btn"> 
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> 
                                </span> 
                                <input type='text' class="form-control date start-date" id="start_date" name="start_date" data-provide="datepicker" placeholder="MM/DD/YYYY" tabindex=7 value="{{$css->start_date}}" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 padding-0" >
                            <div class="input-group margin-top-10"> 
                                <span class="input-group-btn"> 
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>                                </i></button> 
                                </span> 
                                <input type='text' class="form-control date end-date" id="end_date" name="end_date" data-provide="datepicker" placeholder="MM/DD/YYYY" tabindex=8 value="{{$css->end_date}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-align-center " >
                    <button class="btn btn-primary btn-create" type="submit" >
                        @if($save === 'create')
                            {{ trans('sales::view.Create CSS') }}
                        @else
                            {{ trans('sales::view.Update CSS') }}
                        @endif
                    </button>
                </div>
            </div>
        </form>  
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
<script type="text/javascript">
    <?php if($save === 'update'){ ?>
        var teamArray = []; 
        <?php foreach($teamsSet as $team): ?>
          teamArray.push([<?php echo $team->id ?>, '<?php echo $team->name ?>']); 
        <?php endforeach; ?>
    <?php } ?>
    
</script>
@endsection
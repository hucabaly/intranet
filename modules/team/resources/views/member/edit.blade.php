@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Team\View\TeamList;
use Rikkei\Team\Model\Position;
?>

@section('title')
{{ trans('team::view.Profile of :employeeName', ['employeeName' => Form::getData('name')]) }}
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row member-profile">
    <form action="{{ route('team::team.member.save') }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ Form::getData('id') }}" />
        <div class=" col-md-12 box-action">
            <input type="submit" class="btn-edit" name="submit" value="{{ trans('team::view.Update information') }}" />
        </div>
        <div class="col-md-5">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Personal Information') }}</h2>
                </div>
                <div class="box-body">
                    <div class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('team::view.Employee code') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control" placeholder="{{ trans('team::view.Employee code') }}" value="{{ Form::getData('employee_card_id') }}" disabled/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">Team</h2>
                </div>
                <div class="box-body">
                    <div class="form-horizontal form-label-left">
                        <div class="form-group group-team-position">
                            <div class="input-team-position input-team">
                                <label class="control-label">Team</label>
                                <div class="input-box">
                                    <select name="team[0][team]" class="form-control">
                                        @foreach(TeamList::toOption(null, false, false) as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="input-team-position input-position">
                                <label class=" control-label">{{ trans('team::view.Position') }}</label>
                                <div class="input-box">
                                    <select name="team[0][position]" class="form-control">
                                        @foreach(Position::toOption() as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end edit memeber left col -->

        <div class="col-md-7">

        </div> <!-- end edit memeber right col -->
    </form>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script>
    jQuery(document).ready(function ($) {
        var messages = {
            'item[name]': '<?php echo trans('core::view.Please enter') . ' ' . trans('team::view.role name') ; ?>'
        }
        $('#form-role-edit').validate({
            messages: messages
        });
    });
</script>
@endsection
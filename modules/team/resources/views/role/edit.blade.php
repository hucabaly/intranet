@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
?>

@section('title')
{{ trans('team::view.Edit role: :roleName', ['roleName' => Form::getData('name')]) }}
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="box box-info">
    <form class="form-horizontal" action="{{ route('team::setting.role.save') }}" method="post" id="form-role-edit">
        {!! csrf_field() !!}
        @if(Form::getData('id'))
            <input type="hidden" name="id" value="{{ Form::getData('id') }}" />
        @endif
        <div class="box-header with-border">
            <div class="box-action">
                <input type="submit" class="btn-add" name="submit" value="{{ trans('team::view.Save') }}" />
                <input type="submit" class="btn-edit" name="submit_continue" value="{{ trans('team::view.Save And Continue') }}" />
                <a href="{{ app('request')->fullUrl() }}" class="btn-move">Reset</a>
                @if(Form::getData('id'))
                    <input type="submit" class="btn-delete delete-confirm" name="delete" value="{{ trans('team::view.Remove') }}" />
                @endif
            </div>
        </div>
        <div class="box-body">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#item-general">{{ trans('team::view.General') }}</a></li>
                @if(Form::getData('id'))
                    <li><a data-toggle="tab" href="#item-rules">{{ trans('team::view.Rules') }}</a></li>
                    <li ><a data-toggle="tab" href="#item-users">{{ trans('team::view.Users') }}</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="item-general" class="tab-pane active">
                    @include('team::role.edit.general')
                </div>
                @if(Form::getData('id'))
                    <div id="item-rules" class="tab-pane">
                        @include('team::role.edit.rules')
                    </div>
                    <div id="item-users" class="tab-pane">
                        @include('team::role.edit.users')
                    </div>
                @endif
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" class="btn-add" name="submit" value="{{ trans('team::view.Save') }}" />
            <input type="submit" class="btn-edit" name="submit_continue" value="{{ trans('team::view.Save And Continue') }}" />
            <a href="{{ app('request')->fullUrl() }}" class="btn-move">Reset</a>
            @if(Form::getData('id'))
                <input type="submit" class="btn-delete delete-confirm" name="delete" value="{{ trans('team::view.Remove') }}" />
            @endif
        </div>
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
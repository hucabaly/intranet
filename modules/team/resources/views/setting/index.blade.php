@extends('layouts.default')
<?php

use Rikkei\Team\View\TeamList;
use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Position;

$positionAll = Position::getAll();
?>

@section('title')
Team Setting
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row">
    <!-- team manage -->
    <div class="col-sm-6 team-wrapper hight-same">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('team::view.List team') }}</h3>
            </div>
            <div class="row team-list-action box-body">
                <div class="col-sm-9 team-list">
                    {!! TeamList::getTreeHtml(Form::getData('id')) !!}
                </div>
                <div class="col-sm-3 team-action">
                    <p><button type="button" class="btn-add btn-action" data-target="#team-add-form" data-toggle="modal">
                            <span>{{ trans('team::view.Add') }}</span>
                        </button></p>
                    <p><button type="button" class="btn-edit btn-action" data-target="#team-edit-form" data-toggle="modal"<?php
                        if(!Form::getData('id')): ?> disabled<?php endif; ?>>
                            <span>{{ trans('team::view.Edit') }}</span>
                        </button></p>
                    @if(Form::getData('id'))
                        <form class="form" method="post" action="{{ URL::route('team::setting.team.delete') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="{{ Form::getData('id') }}" />
                    @endif
                        <p><button type="submit" class="btn-delete btn-action delete-confirm"<?php
                                if(!Form::getData('id')): ?> disabled<?php endif; ?>>
                                <span>{{ trans('team::view.Remove') }}</span>
                            </button></p>
                    @if(Form::getData('id'))
                        </form>
                    @endif
                    @if(Form::getData('id'))
                        <form class="form" method="post" action="{{ URL::route('team::setting.team.move') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ Form::getData('id') }}" id="item-team-id" />
                    @endif
                        <p>
                            <input type="submit" name="move_up" value="{{ trans('team::view.Move up') }}" 
                                class="btn-move btn-action"<?php if(!Form::getData('id')): ?> disabled<?php endif; ?> />
                        </p>
                        <p>
                            <input type="submit" name="move_down" value="{{ trans('team::view.Move down') }}"
                                class="btn-move btn-action"<?php if(!Form::getData('id')): ?> disabled<?php endif; ?> />
                        </p>
                    @if(Form::getData('id'))
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div> <!-- end team manage -->
    
    <!-- team position manage -->
    <div class="col-sm-6 team-position-wrapper hight-same">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('team::view.Position of team') }}</h3>
            </div>
            <div class="row team-list-action box-body">
                <div class="col-sm-9 position-list">
                    @if (! count($positionAll))
                        <p class="alert alert-warning">{{ trans('team::view.Not found position') }}</p>
                    @else
                        <table class="table table-bordered">
                            <tbody>
                                @foreach ($positionAll as $positionItem)
                                <tr><td>
                                    <a href="{{ URL::route('team::setting.team.position.view', ['id' => $positionItem->id]) }}"<?php
                                    if ($positionItem->id == Form::getData('position.id')): ?> class="active"<?php endif; ?>>{{ $positionItem->name }}</a>
                                </td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="col-sm-3 team-action">
                    <p><button type="button" class="btn-add btn-action" data-target="#position-add-form" data-toggle="modal">
                            <span>{{ trans('team::view.Add') }}</span>
                        </button></p>
                    <p><button type="button" class="btn-edit btn-action" data-target="#position-edit-form" data-toggle="modal"<?php
                        if(!Form::getData('position.id')): ?> disabled<?php endif; ?>>
                            <span>{{ trans('team::view.Edit') }}</span>
                        </button></p>
                    @if(Form::getData('position.id'))
                        <form class="form" method="post" action="{{ URL::route('team::setting.team.position.delete') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="{{ Form::getData('position.id') }}" />
                    @endif
                        <p><button type="submit" class="btn-delete btn-action delete-confirm"<?php
                                if(!Form::getData('position.id')): ?> disabled<?php endif; ?> 
                                data-noti="{{ trans('team::view.Are you sure delete postion team?') }}">
                                <span>{{ trans('team::view.Remove') }}</span>
                            </button></p>
                    @if(Form::getData('position.id'))
                        </form>
                    @endif
                    @if(Form::getData('position.id'))
                        <form class="form" method="post" action="{{ URL::route('team::setting.team.position.move') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ Form::getData('position.id') }}" />
                    @endif
                        <p>
                            <input type="submit" name="move_up" value="{{ trans('team::view.Move up') }}" 
                                class="btn-move btn-action"<?php if(!Form::getData('position.id')): ?> disabled<?php endif; ?> />
                        </p>
                        <p>
                            <input type="submit" name="move_down" value="{{ trans('team::view.Move down') }}"
                                class="btn-move btn-action"<?php if(!Form::getData('position.id')): ?> disabled<?php endif; ?> />
                        </p>
                    @if(Form::getData('position.id'))
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div> <!-- end team position manage -->
    
    <div class="col-sm-12 team-rule-wrapper">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h2 class="box-title">{{ trans('team::view.Permission function of') }} <b>{{ Form::getData('name') }}</b></h2>
            </div>
            <div class="box-body">
                @if (! Form::getData('id'))
                    <p class="alert alert-warning">{{ trans('team::view.Please choose team to set permission function') }}</p>
                @elseif (! Form::getData('is_function'))
                    <p class="alert alert-warning">{{ trans('team::view.Team is not function') }}</p>
                @elseif ($permissionAs)
                    <p class="alert alert-warning">{{ trans('team::view.Team permisstion as team') }} 
                        <a href="{{ Url::route('team::setting.team.view', ['id' => $permissionAs->id]) }}">{{ $permissionAs->name }}</a></p>
                @elseif (! isset($positions) || ! count($positions))
                    <p class="alert alert-warning">{{ trans('team::view.Not found position to set permission function') }}</p>
                @else
                    @include('team::setting.include.rule')
                @endif
            </div>
    </div>
</div>

<!-- modal add/edit team position-->
@if(Form::getData('position.id'))
<!-- modal edit team -->
<div class="modal fade" id="position-edit-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.team.position.save') }}" id="form-position-edit">
                {!! csrf_field() !!}
                <input type="hidden" name="position[id]" value="{{ Form::getData('position.id') }}" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('team::view.Edit team position') }}</h4>
                </div>
                @include('team::setting.include.position_edit')
            </form>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="position-add-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.team.position.save') }}" id="form-position-add">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('team::view.Create team position') }}</h4>
                </div>
                @include('team::setting.include.position_edit')
            </form>
        </div>
    </div>
</div>
<!-- //end modal add/edit position -->

<!-- modal add/edit team -->
@if(Form::getData('id'))
<!-- modal edit team -->
<div class="modal fade" id="team-edit-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-team" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.team.save') }}" id="form-team-edit">
                {!! csrf_field() !!}
                <input type="hidden" name="item[id]" value="{{ Form::getData('id') }}" id="item-team-id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('team::view.Edit team') }}</h4>
                </div>
                @include('team::setting.include.team_edit')
            </form>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="team-add-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-team" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.team.save') }}" id="form-team-add">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('team::view.Create team') }}</h4>
                </div>
                @include('team::setting.include.team_edit')
            </form>
        </div>
    </div>
</div>
<!-- //end modal add/edit team -->
@endsection


@section('script')
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.match.height.addtional.js') }}"></script>
<script src="{{ URL::asset('team/js/script.js') }}"></script>
<script>
    jQuery(document).ready(function ($) {
        var messages = {
            'item[name]': '<?php echo trans('core::view.Please enter') . ' ' . trans('team::view.team name') ; ?>',
            'position[name]': '<?php echo trans('core::view.Please enter') . ' ' . trans('team::view.position name') ; ?>',
        }
        $('#form-team-add').validate({
            messages: messages
        });
        $('#form-team-edit').validate({
            messages: messages
        });
        
        $('#form-position-add').validate({
            messages: messages
        });
        $('#form-position-edit').validate({
            messages: messages
        });
        $('.hight-same').matchHeight({
            child: '.box'
        });
    });
</script>
@endsection
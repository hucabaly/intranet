@extends('layouts.default')
<?php

use Rikkei\Team\View\TeamList;
use Rikkei\Core\View\Form;
?>

@section('title')
Team Setting
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-6 team-wrapper">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{ Lang::get('team::setting.List team') }}</h3>
            </div>
            <div class="row team-list-action box-body">
                <div class="col-sm-9 team-list">
                    {!! TeamList::getTreeHtml(Form::getData('id')) !!}
                </div>
                <div class="col-sm-3 team-action">
                    <p><button type="button" class="btn-add btn-action" data-target="#team-add-form" data-toggle="modal">
                            <span>{{ Lang::get('team::setting.Add') }}</span>
                        </button></p>
                    <p><button type="button" class="btn-edit btn-action" data-target="#team-edit-form" data-toggle="modal"<?php
                        if(!Form::getData('id')): ?> disabled<?php endif; ?>>
                            <span>{{ Lang::get('team::setting.Edit') }}</span>
                        </button></p>
                    @if(Form::getData('id'))
                        <form class="form" method="post" action="{{ URL::route('team::setting.team.delete') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="{{ Form::getData('id') }}" />
                    @endif
                        <p><button type="submit" class="btn-delete btn-action delete-confirm"<?php
                                if(!Form::getData('id')): ?> disabled<?php endif; ?>>
                                <span>{{ Lang::get('team::setting.Remove') }}</span>
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
                            <input type="submit" name="move_up" value="{{ Lang::get('team::setting.Move up') }}" 
                                class="btn-move btn-action"<?php if(!Form::getData('id')): ?> disabled<?php endif; ?> />
                        </p>
                        <p>
                            <input type="submit" name="move_down" value="{{ Lang::get('team::setting.Move down') }}"
                                class="btn-move btn-action"<?php if(!Form::getData('id')): ?> disabled<?php endif; ?> />
                        </p>
                    @if(Form::getData('id'))
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal add/edit team -->
@if(Form::getData('id'))
<!-- modal edit team -->
<div class="modal fade" id="team-edit-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-team" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.team.save') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="item[id]" value="{{ Form::getData('id') }}" id="item-team-id" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ Lang::get('team::setting.Edit team') }}</h4>
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
            <form class="form" method="post" action="{{ URL::route('team::setting.team.save') }}">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ Lang::get('team::setting.Create team') }}</h4>
                </div>
                @include('team::setting.include.team_edit')
            </form>
        </div>
    </div>
</div>
<!-- //end modal add/edit team -->
@endsection


@section('script')
<script src="{{ URL::asset('team/js/script.js') }}"></script>
@endsection
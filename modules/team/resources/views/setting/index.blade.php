@extends('layouts.default')
<?php
use Rikkei\Team\View\TeamList;
?>

@section('title')
Team Setting
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-sm-6">
        <h3>{{ Lang::get('team::setting.List team') }}</h3>
        <div class="row team-list-action">
            <div class="col-sm-6 team-list">
                {!! TeamList::getTreeHtml() !!}
            </div>
            <div class="col-sm-6 team-action">
                <p><button type="button" class="btn btn-default" data-toggle="modal" data-target="#team-edit-form">
                        <span>{{ Lang::get('team::setting.Add') }}</span>
                    </button></p>
                <p><button type="button" class="btn btn-default">
                        <span>{{ Lang::get('team::setting.Edit') }}</span>
                    </button></p>
                <p><button type="button" class="btn btn-default">
                        <span>{{ Lang::get('team::setting.Remove') }}</span>
                    </button></p>
                <p><button type="button" class="btn btn-default">
                        <span>{{ Lang::get('team::setting.Move up') }}</span>
                    </button></p>
                <p><button type="button" class="btn btn-default">
                        <span>{{ Lang::get('team::setting.Move down') }}</span>
                    </button></p>
            </div>
        </div>
    </div>
</div>
@include('team::setting.include.team_edit')
@endsection


@section('script')
<script src="{{ URL::asset('team/js/script.js') }}"></script>
@endsection
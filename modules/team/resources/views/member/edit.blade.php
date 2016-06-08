@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Team\View\TeamList;
use Rikkei\Team\Model\Position;

$postionsOption = Position::toOption();
$teamsOption = TeamList::toOption(null, false, false);
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
                    @include('team::member.edit.base')
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">Team</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.team')
                </div>
            </div>
            
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Role Special') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.role')
                </div>
            </div>
            
        </div> <!-- end edit memeber left col -->

        <div class="col-md-7">

        </div> <!-- end edit memeber right col -->
    </form>
</div>
<?php
//remove flash session
Form::forget();
?>
@endsection

@section('script')
<script src="{{ URL::asset('team/js/script.js') }}"></script>
<script>
    
</script>
@endsection
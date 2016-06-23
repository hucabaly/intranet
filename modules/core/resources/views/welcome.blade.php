@extends('layouts.login')
@section('title')
Login
@endsection

@section('content')
<div class="login-wrapper">
    <h1 class="login-title">{{ trans('core::view.Welcome to Rikkeisoft Intranet !!!') }}</h1><!-- /.login-logo -->
    <div class="login-action">
        <p>
            <a class="btn btn-primary btn-lg login-button" href="{{ url('auth/connect', ['google']) }}" 
               role="button">{{ trans('core::view.Login with Rikkeisoft Account') }}</a>
        </p>
    </div><!-- /.login-box-action -->
</div><!-- /.login-wrapper -->
@endsection
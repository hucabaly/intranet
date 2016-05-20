@extends('layouts.guest')

@section('title')
Login -
@endsection

@section('content')
<div class="login-wrapper">
    <h1 class="login-title">
        <a href="{{ URL::to('/') }}">{{ trans('core::view.Welcome to Rikkeisoft Intranet !!!') }}</a>
    </h1><!-- /.login-logo -->
    <div class="login-action">
        <p>
            <a class="btn btn-primary btn-lg login-button" href="{{ url('auth/connect', ['google']) }}" role="button">{{ trans('core::view.Login with Rikkeisoft Account') }}</a>
        </p>
        <div class="language-change">
            <a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'vi']) }}" role="button">{{ Config::get('app.locales.vi') }}</a>
            <a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'en']) }}" role="button">{{ Config::get('app.locales.en') }}</a>
            <a class="btn btn-primary btn-md" href="{{ URL::route('core::change-locale', ['locale' => 'ko']) }}" role="button">Korean</a>
        </div>
    </div><!-- /.login-box-action -->
</div><!-- /.login-wrapper -->
@endsection
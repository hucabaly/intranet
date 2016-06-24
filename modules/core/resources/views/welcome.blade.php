@extends('layouts.login')
@section('title')
Login
@endsection

@section('content')
<div class="login-wrapper">
    <h1 class="login-title">
        <img src="{{ URL::asset('img/logo_login.png') }}" />
    </h1><!-- /.login-logo -->
    <div class="login-action">
        <p>
            <a class="btn login-button" href="{{ url('auth/connect', ['google']) }}" 
               role="button">
                <img src="{{ URL::asset('img/login-button.png') }}" />
            </a>
        </p>
    </div><!-- /.login-box-action -->
</div><!-- /.login-wrapper -->
@endsection

@section('script')
<script src="{{ URL::asset('adminlte/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.backstretch.min.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        $.backstretch('{{ URL::asset('img/login-background.png') }}');
    });
    
</script>
@endsection
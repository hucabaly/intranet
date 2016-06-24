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
            <a class="login-button" href="{{ url('auth/connect', ['google']) }}" 
               role="button">
<!--                <span class="login-btn-item login-btn-head"><img src="{{ URL::asset('img/favicon-r.png') }}" /></span>-->
                <span class="login-btn-item login-btn-content">LOGIN WITH   RIKKEISOFT ACCOUNT</span>
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
        
        /**
         * fix position for login block - margin height
         */
        function fixPositionLoginBlock()
        {
            windowHeight = $(window).height();
            loginHeight = $('.login-wrapper').height();
            if (windowHeight < 2 * loginHeight) {
                $('.login-wrapper').css('margin-top', '0px');
                return;
            }
            placeHeight = windowHeight - loginHeight;
            $('.login-wrapper').css('margin-top', placeHeight / 3 + 'px');
        }
        
        fixPositionLoginBlock();
        $(window).resize(function (event) {
            fixPositionLoginBlock();
        })
    });
    
</script>
@endsection
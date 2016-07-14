@extends('layouts.guest')

@section('content')
<div class="success-body">
    <h1 class="success-title">
        <img src="{{ URL::asset('common/images/logo-rikkei.png') }}" />
    </h1><!-- /.login-logo -->
    <div class="success-action">
        <p>
            <span class="success-item success-content">ご協力、ありがとうございました。</span>
        </p>
    </div><!-- /.login-box-action -->
</div><!-- /.login-wrapper -->
@endsection

<!-- Styles -->
@section('css')
<link href="{{ asset('sales/css/css_customer.css') }}" rel="stylesheet" type="text/css" >
@endsection

@section('script')
<script src="{{ URL::asset('lib/js/jquery.backstretch.min.js') }}"></script>
<script src="{{ asset('sales/js/css/customer.js') }}"></script>
<script src="{{ asset('sales/js/css/success.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        $.backstretch('{{ URL::asset('common/images/login-background.png') }}');
        
        /**
         * fix position for login block - margin height
         */
        function fixPositionLoginBlock()
        {
            windowHeight = $(window).height();
            loginHeight = $('.login-wrapper').height();
            placeHeight = windowHeight / 2 - loginHeight / 2;
            $('.login-wrapper').css('margin-top', placeHeight + 'px');
        }
        
        fixPositionLoginBlock();
        $(window).resize(function (event) {
            fixPositionLoginBlock();
        });
    });
    
</script>
@endsection
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            @yield('title')
            Rikkeisoft お客様アンケート
        </title>
        <script>
            var baseUrl = '{{ url('/') }}/';
        </script>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" href="{{ URL::asset('img/favicon.ico') }}" type="image/x-icon">
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ URL::asset('img/favicon.ico') }}">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/bootstrap/css/bootstrap.min.css') }}" />
        <!-- common style -->
        <link rel="stylesheet" href="{{ URL::asset('common/css/style.css') }}" />

        @yield('css')
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition guest">
        <div class="jumbotron">
            <div class="container">
                @include('messages.success')
                @include('messages.errors')
                <section class="content">
                    @yield('content')
                </section><!-- /.content -->
            </div>
            <div class="welcome-footer col-md-12">
                <div class="row">
                    <div class="col-md-6 policy-mobile-container"><p class="float-right policy"><a href="http://rikkeisoft.com/privacypolicy/" target="_blank"><span class="policy-link">プライバシーポリシー</span></a> &nbsp; | &nbsp; Version 1.0.0</p></div>
                    <div class="col-md-6"><p class="float-left copyright">Copyright © 2016 <span>Rikkeisoft</span>. All rights reserved.</p></div>
                    <div class="col-md-6 policy-container"><p class="float-right policy"><a href="http://rikkeisoft.com/privacypolicy/" target="_blank"><span class="policy-link">プライバシーポリシー</span></a> &nbsp; | &nbsp; Version 1.0.0</p></div>
                </div>
            </div>
        </div>
        <!-- jQuery 2.2.0 -->
        <script src="{{ URL::asset('adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <!-- jQuery UI -->
        <script src="{{ URL::asset('adminlte/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ URL::asset('adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ URL::asset('adminlte/plugins/fastclick/fastclick.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::asset('adminlte/dist/js/app.min.js') }}"></script>
        <!-- Slimscroll -->
        <script src="{{ URL::asset('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        
        <script src="{{ URL::asset('common/js/script.js') }}"></script>
        <!-- Add custom script follow page -->
        @yield('script')
        @yield('scriptCode')
    </body>
</html>

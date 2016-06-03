<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            @yield('title')
            Rikkei Intranet
        </title>
        <script>
            var baseUrl = '{{ url('/') }}/';
            var currentUrl = '{{ app('request')->url() }}/';
        </script>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/bootstrap/css/bootstrap.min.css') }}" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/dist/css/AdminLTE.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('adminlte/dist/css/skins/_all-skins.min.css') }}" />
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
    <body class="hold-transition skin-blue layout-top-nav">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="{{ URL::to('/') }}" class="navbar-brand"><b><img src="{{ asset('/common/images/intranet_logo.png') }}" /></b></a>
                            <button type="button" class="navbar-toggle mmenu-toggle">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        @include("include.menu_main")
                        @include("include.menu_right")
                    </div><!-- /.container-fluid -->
                </nav>
            </header>
            <!-- Full Width Column -->
            <div class="content-wrapper">
                <div class="container-fluid">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            @yield('title')
                        </h1>
                        <!-- Breadcrumb -->
                            @include('include.breadcrumb')
                        <!-- end Breadcrumb -->
                        <div class="clearfix"></div>
                    </section>
                    
                    @include('messages.success')
                    @include('messages.errors')

                    <!-- Main content -->
                    <section class="content">
                        <div class="content-container">
                            @yield('content')
                        </div>
                    </section><!-- /.content -->
                </div><!-- /.container -->
            </div><!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="container-fluid">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> 1.0.0
                    </div>
                    <strong>Copyright &copy; 2016 <a href="http://rikkeisoft.com/">RikkeiSoft</a>.</strong> All rights reserved.
                </div><!-- /.container -->
            </footer>
        </div><!-- ./wrapper -->
        
        <!-- modal delete cofirm -->
        <div class="modal fade modal-danger" id="modal-delete-confirm" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ Lang::get('core::view.Confirm') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-default">{{ Lang::get('core::view.Are you sure delete item(s)?') }}</p>
                        <p class="text-change"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline btn-close" data-dismiss="modal">{{ Lang::get('core::view.Close') }}</button>
                        <button type="button" class="btn btn-outline btn-ok">{{ Lang::get('core::view.OK') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div> <!-- modal delete cofirm -->
        
        <!-- menu mobile -->
        <div class="menu-left-wraper">
            <nav id="mmenu-left" class="left">
                <div class="mmenu-close mmenu-toggle">
                    <a href="#"><span></span></a>
                </div>
                <ul class="collapse-wrapper collapse-mmenu">
                    {!! Rikkei\Core\View\Menu::get() !!}
                </ul>
            </nav>
        </div> <!-- end menu mobile -->

        <!-- jQuery 2.2.0 -->
        <script src="{{ URL::asset('adminlte/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
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

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            @if(isset($title) && $title)
            {{ $title }}
            -
            @endif
            Admin Rikkei Intranet
        </title>
        <script>
            var baseUrl = '{{ url(' / ') }}/',
            ckeditorBaseUrl = baseUrl + 'public/media/ckeditor/';
        </script>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="{{ URL::asset('adminlte/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('adminlte/font-awesome-4.6.1/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('adminlte/ionicons-2.0.1/css/ionicons.min.css') }}" />

        <!-- table boostrap -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('adminlte/dist/css/AdminLTE.min.css') }}" />

        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/dist/css/skins/_all-skins.min.css') }}" />

        <!-- iCheck -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/iCheck/flat/blue.css') }}">
        <!-- Morris chart -->
        <?php /* link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/morris/morris.css') }}" */ ?>
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
        <!-- Date Picker -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/css/bootstrap-datetimepicker.css') }}">

        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('adminlte/css/styles.css') }}">
        <!-- Add custom css follow page -->
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
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ URL::to('/') }}" class="navbar-brand"><b>Intranet</b></a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
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
                <div class="container">
                    @include('messages.success')
                    @include('messages.errors')
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        @if(isset($title) && $title)
                            <h1>{{ $title }}</h1>
                        @endif
                        
                        <!-- Breadcrumb -->
                            @include('include.breadcrumb')
                        <!-- end Breadcrumb -->
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        @yield('content')
                    </section><!-- /.content -->
                </div><!-- /.container -->
            </div><!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> 1.0.0
                    </div>
                    <strong>Copyright &copy; 2016 <a href="http://rikkeisoft.com/">RikkeiSoft</a>.</strong> All rights reserved.
                </div><!-- /.container -->
            </footer>
        </div><!-- ./wrapper -->


        <!-- jQuery 2.1.4 -->
        <script src="{{ URL::asset('adminlte/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ URL::asset('adminlte/js/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
                    $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.5 -->
        <script src="{{ URL::asset('adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Morris.js charts -->
        <script src="{{ URL::asset('adminlte/js/raphael-min.js') }}"></script>

        <?php /* script src="{{ URL::asset('adminlte/plugins/morris/morris.min.js') }}"></script */ ?>
        <!-- Sparkline -->
        <script src="{{ URL::asset('adminlte/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- jvectormap -->
        <script src="{{ URL::asset('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ URL::asset('adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ URL::asset('adminlte/plugins/knob/jquery.knob.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ URL::asset('adminlte/js/moment.min.js') }}"></script>
        <script src="{{ URL::asset('adminlte/js/bootstrap-datetimepicker.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{ URL::asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
        <!-- DataTables -->
        <script src="{{ URL::asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <!-- Slimscroll -->
        <script src="{{ URL::asset('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ URL::asset('adminlte/plugins/fastclick/fastclick.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::asset('adminlte/dist/js/app.min.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <?php /* script src="{{ URL::asset('adminlte/dist/js/pages/dashboard.js') }}"></script */ ?>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ URL::asset('adminlte/dist/js/demo.js') }}"></script>
        <script src="{{ URL::asset('adminlte/ckeditor-4.5.8/ckeditor.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('adminlte/ckfinder/ckfinder.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('adminlte/js/app.js') }}"></script>
        <!-- Add custom script follow page -->
        @yield('script')
        @yield('scriptCode')
    </body>
</html>

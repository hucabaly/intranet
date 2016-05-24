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
            var baseUrl = '{{ url(' / ') }}/';
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
    <body class="hold-transition guest">
        <div class="jumbotron">
            <div class="container">
                @include('messages.success')
                @include('messages.errors')
                <section class="content">
                    @yield('content')
                </section><!-- /.content -->
            </div>
        </div>
        <!-- Add custom script follow page -->
        @yield('script')
        @yield('scriptCode')
    </body>
</html>
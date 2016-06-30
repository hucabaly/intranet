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
        </script>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" href="{{ URL::asset('common/images/favicon.ico') }}" type="image/x-icon">
        <link rel="Shortcut Icon" type="image/x-icon" href="{{ URL::asset('common/images/favicon.ico') }}">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ URL::asset('adminlte/bootstrap/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('common/css/login.css') }}" />
        @yield('css')
    </head>
    <body class="hold-transition guest">
        <div class="jumbotron">
            <div class="container-fluid">
                @include('messages.success')
                @include('messages.errors')
                <section class="content">
                    @yield('content')
                </section><!-- /.content -->
            </div>
        </div>
        @yield('script')
    </body>
</html>

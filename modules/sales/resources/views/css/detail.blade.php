@extends('layouts.default')

@section('content')
<div class="container box box-primary" style="background-color: #fff;min-height: 400px;">
    <div class="row">
        <div class="make-css ">
            <div class="row">
                <div class="col-md-12">
                    <section><a href="{{url('/css/export_excel/'.$cssResult->id)}}" target="_blank">Export excel</a></section>
                    <section id="header-makecss">
                        <div id="chu-trai"><h2>お客様アンケート</h2></div>
                        <div id="logo-rikkei"></div>
                    </section>
                    <section>
                        @include('sales::css.include.project_detail')
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Styles -->
@section('css')
<link href="{{ asset('sales/css/css-screen.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('sales/css/rateit.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('lib/js/jquery.rateit.js') }}"></script>
<script src="{{ asset('lib/js/jquery.visible.js') }}"></script>
<script src="{{ asset('sales/js/css.js') }}"></script>
<script type="text/javascript">
    function goto_make() {
        $(".welcome").hide();
        $(".make-css").show();
    }
    
</script>
@endsection
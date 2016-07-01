@extends('layouts.guest')

@section('content')
<div class="container box box-primary" style="background-color: #fff;min-height: 400px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 welcome" style="display:block;" >
            <div class="box-header with-textalign-center color-green">
                <h2>{{ trans('sales::view.Welcome title') }}</h2>
            </div>
            <div class="container-fluid with-textalign-center">
                <div class="row-fluid">

                    <div class="span12">
                        <div >
                            <p class="welcome-line">{{trans('sales::view.Welcome line 1')}}</p>
                            <p class="welcome-line">{{trans('sales::view.Welcome line 2')}}</p>
                            <p class="welcome-line">{{trans('sales::view.Welcome line 3')}}</p>
                            <div style="clear:both;"></div>
                            <button type="button" class="btn btn-primary btn-next" onclick="goto_make();">{{ trans('sales::view.Next') }}</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="make-css " style="display:none;">
            <div class="row">
                <div class="col-md-12">
                    <section id="header-makecss">
                        <div id="chu-trai"><h2>お客様アンケート</h2></div>
                        <div id="logo-rikkei"><img src="{{ URL::asset('img/logo') }}"></div>
                    </section>
                    <section>
                        
                    @include('sales::css.include.project_make')
                    
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
    <?php if(Auth::check()): ?>
        $('#modal-confirm-make').show();
    <?php endif; ?>
        
    function hideModalConfirmMake(){
        $('#modal-confirm-make').hide();
    }
    
    function goToFinish(){
        location.href = "/css/cancel";
    }
</script>
@endsection
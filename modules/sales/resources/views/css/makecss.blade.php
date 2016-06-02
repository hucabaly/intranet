@extends('layouts.default')

@section('content')
<div class="container box box-primary" style="background-color: #fff;min-height: 400px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 welcome" style="display:block;" >
            <div class="box-header with-border">
                <h1 >{{ trans('sales::view.Welcome title') }}</h1>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                        <div >
                            <p><?php echo trans('sales::view.Hello',["customer_name" => $css->customer_name, "company_name" => $css->company_name]) ?></p>
                            <p>{{ trans('sales::view.Thank you message') }}</p>
                            <p><?php echo trans('sales::view.Please message',["project_name" => $css->project_name]) ?></p>
                            <p class="kinh-thu"><?php echo trans('sales::view.Respect',["user_name" => $user->name]) ?></p>
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
<link href="{{ asset('css/css-screen.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/rateit.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('js/jquery.rateit.js') }}"></script>
<script src="{{ asset('js/jquery.visible.js') }}"></script>
<script src="{{ asset('js/css.js') }}"></script>
<script type="text/javascript">
    function goto_make() {
        $(".welcome").hide();
        $(".make-css").show();
    }
    <?php if(Auth::check()): ?>
        //$(document).ready(function(){
            $('#modal-confirm-make').show();
        //});
        
    <?php endif; ?>
        
    function hideModalConfirmMake(){
        $('#modal-confirm-make').hide();
    }
    
    function goToFinish(){
        location.href = "/css/cancel";
    }
</script>
@endsection
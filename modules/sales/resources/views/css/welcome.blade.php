@extends('layouts.guest')

@section('content')

<div class="row">
    <div class="col-md-12 welcome-body" style="display:block;" >
        <div class="logo-rikkei">
            <img src="{{ URL::asset('common/images/logo-rikkei.png') }}">
        </div>
        <div class="box-header welcome-header">
            <h2 class="welcome-title <?php if($css->project_type_id === 1){ echo 'color-blue'; } ?>">{{ trans('sales::view.Welcome title') }}</h2>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div >
                        <p class="welcome-line">{{trans('sales::view.Welcome line 1')}}</p>
                        <p class="welcome-line">{{trans('sales::view.Welcome line 2')}}</p>
                        <p class="welcome-line">{{trans('sales::view.Welcome line 3')}}</p>
                    </div>
                </div>
            </div>
            <div class="row-fluid ">
                <form method="post" action="{{ url('/css/welcome/'.$token.'/'.$id)}}"  >
                    <input type="hidden" name="token" value="{{$token}}" />
                    <input type="hidden" name="id" value="{{$id}}" />
                    <div class="css-make-info">
                        <div>
                            <div class="company-name-title">{{ trans('sales::view.Customer company name jp')}}</div>
                            <div class="company-name inline-block">{{ $css->company_name}} 様</div>
                        </div>
                        <div>
                            <div class="project-name-title">{{ trans('sales::view.Project name jp')}}</div>
                            <div class="project-name inline-block">{{ $css->project_name}}</div>
                        </div>
                        <div>
                            <div class="customer-name-title">{{ trans('sales::view.Make name jp')}}</div>
                            <div class="inline-block">
                                <div class="input-group goto-make-parent">

                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="text" class="form-control" id="make_name" name="make_name" maxlength="100" />
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default btn-to-make <?php if($css->project_type_id === 1){ echo 'bg-color-blue'; } ?>" name="submit"><img src="{{ URL::asset('sales/images/splash.png') }}" /></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
</div>
<div class="modal modal-warning" id="modal-confirm-name">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span></button>
                <h4 class="modal-title">{{ trans('sales::view.Warning') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ trans('sales::message.Name validate required') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ trans('sales::view.Close jp') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

<!-- Styles -->
@section('css')
<link href="{{ asset('sales/css/css_customer.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('lib/js/jquery.visible.js') }}"></script>
<script src="{{ asset('sales/js/css/customer.js') }}"></script>
<script>
    <?php if($nameRequired == 1): ?>
        $('#modal-confirm-name').modal('show');
    <?php elseif($nameRequired == -1): ?>
        $('#modal-confirm-name .modal-body').html('{{trans("sales::message.Check max length name")}}');
        $('#modal-confirm-name').modal('show');
        $('#make_name').val('{{$makeName}}');
    <?php endif; ?>
    //Fix footer bottom
    setHeightBody('.welcome-body', 90);
    $(window).resize(function(){
        setHeightBody('.welcome-body', 90);
    });
</script>
@endsection
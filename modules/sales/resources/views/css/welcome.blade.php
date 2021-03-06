@extends('layouts.guest')

@section('content')

<div class="row">
    <div class="col-md-12 welcome-body" style="display:block;" >
        <div class="logo-rikkei">
            <img src="{{ URL::asset('common/images/logo-rikkei.png') }}">
        </div>
        <div class="box-header welcome-header">
            <h2 class="welcome-title">{{ trans('sales::view.Welcome title') }}</h2>
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
                <form method="post" action="{{ url('/css/save_name/')}}"  >
                    <input type="hidden" name="token" value="{{$token}}" />
                    <input type="hidden" name="id" value="{{$id}}" />
                    <div class="css-make-info">
                        <div>
                            <div class="company-name-title">{{ trans('sales::view.Customer company name jp')}}</div>
                            <div class="company-name inline-block">{{ $css->company_name}}</div>
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
                                        <input type="text" class="form-control" id="make_name" name="make_name" />
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default" type="button">Go!</button>
                                        </span>

                                    <!--<img class="btn-make-css" src="{{ URL::asset('sales/images/btn-make-css.jpg') }}" onclick="goto_make('{{ $hrefMake }}');" />-->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="welcome-footer col-md-12">
        <div class="row">
            <div class="col-md-6"><p class="float-left copyright">Copyright © 2016 <span>Rikkeisoft</span>. All rights reserved.</p></div>
            <div class="col-md-6"><p class="float-right policy"><a href="http://rikkeisoft.com/privacypolicy/" target="_blank"><span class="policy-link">プライバシーポリシー</span></a> &nbsp; | &nbsp; Version 1.0.0</p></div>
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
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ trans('sales::view.Close') }}</button>
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
@endsection
@extends('layouts.default')

@section('content')
<div class="row analyze-body">
    <div class="col-xs-12">
        <div class="box box-solid">
            <div class="box-group" id="accordion">
              <div class="panel box box-primary">
                  <div id="collapseOne" class="panel-collapse collapse in">
                      <div class="box-body">
                          <div class="row">
                            <div class="col-md-3">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 1")}}</h3>
                                </div>
                                <div class="row" id="dateRanger"></div>
                                <div class="row line-15">
                                    <label class="title-label">{{trans('sales::view.Project type')}}</label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="project_type" value="1">&nbsp;&nbsp;{{ trans('sales::view.Osdc')}}
                                        </div>
                                    </label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="project_type" value="2">&nbsp;&nbsp;{{trans('sales::view.Project base')}}
                                        </div>
                                    </label>
                                </div>
                                <div class="row line-15">
                                    <label class="title-label">{{trans('sales::view.Team')}}</label>
                                    <ul class="list-team-tree">
                                        {!! $htmlTeam !!}
                                    </ul>
                                </div>
                                
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-filter" onclick="filterAnalyze('{{ Session::token() }}');">{{trans('sales::view.Button filter text')}}</button>
                            </div>
                            <div class="col-md-8">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 2")}}</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="border-right: 1px solid #f4f4f4;">
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcProjectType"  checked>
                                            <label class="label-normal" for="tcProjectType">{{trans("sales::view.By project type")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcTeam" >
                                            <label class="label-normal" for="tcTeam">{{trans("sales::view.By team")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcPm" >
                                            <label class="label-normal" for="tcPm">{{trans("sales::view.By pm")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcBrse" >
                                            <label class="label-normal" for="tcBrse">{{trans("sales::view.By brse")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcCustomer" 
                                            <label class="label-normal" for="tcCustomer">{{trans("sales::view.By customer")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcSale" >
                                            <label class="label-normal" for="tcSale">{{trans("sales::view.By sale")}}</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcQuestion" >
                                            <label class="label-normal" for="tcQuestion">{{trans("sales::view.By question")}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div  class="theotieuchi">@include('sales::css.include.table_theotieuchi')</div>
                                    </div>
                                </div>
                                
                            </div>
                          </div>
                          <div class="row" style="text-align:center;">
                              <button class="btn btn-danger btn-apply" onclick="apply('{{ Session::token() }}');">Apply</button>
                          </div>
                          <div class="ketquaapply">
                          <div class="row">
                              <div class="col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Result")}}</h3>
                                </div>
                                <div class="row">
                                <!----------- Bảng danh sách du an ------------------->
                                    <div class="col-md-5">
                                        <h4 class="small-title">{{trans('sales::view.Project list')}}</h4>
                                        <table class="table  table-hover dataTable ketqua" id='danhsachduan'>
                                            <thead>
                                                <tr>
                                                    <th>{{trans('sales::view.No.')}}</th>
                                                    <th>{{trans('sales::view.Project name')}}</th>
                                                    <th>{{trans('sales::view.Team')}}</th>
                                                    <th>{{trans('sales::view.PM')}}</th>
                                                    <th>{{trans('sales::view.Project date finish')}}</th>
                                                    <th>{{trans('sales::view.Make date css')}}</th>
                                                    <th>{{trans('sales::view.Css point')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="pagination"></ul>
                                        </div>
                                    </div>
                                <!----------- Phan hien 2 bieu do ------------------->    
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="small-title">{{trans('sales::view.Chart title by all')}}</h4>
                                                <div id="chartAll" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="small-title">{{trans('sales::view.Chart title by filter')}}</h4>
                                                <div id="chartFilter" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        
                      <div class="row">
                              <div class="col-md-12">
                                <div class="box-header with-border box-select-question">
                                    <div class="form-group">
                                        <label for="sel1">{{trans("sales::view.Question choose")}}</label>
                                        <select class="form-control" id="question-choose"></select>
                                    </div>
                                </div>
                                  
                                <div class="row">
                                 <!----------- Bảng danh sách dưới 3 sao ------------------->   
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.List 3 * below')}}</h4>
                                        <table class="table  table-hover dataTable duoi3sao" id='duoi3sao'>
                                            <thead>
                                                <tr>
                                                    <th>{{trans('sales::view.No.')}}</th>
                                                    <th>{{trans('sales::view.Project name')}}</th>
                                                    <th>{{trans('sales::view.Criteria name')}}</th>
                                                    <th>{{trans('sales::view.Point *')}}</th>
                                                    <th>{{trans('sales::view.Customer comment')}}</th>
                                                    <th>{{trans('sales::view.Make date css')}}</th>
                                                    <th>{{trans('sales::view.Css point')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="pagination"></ul>
                                        </div>
                                    </div>
                                 <!----------- Bảng danh sách de xuat cua khach hang ------------------->
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.Customer proposed')}}</h4>
                                        <table class="table  table-hover dataTable ketqua" id="danhsachdexuat">
                                            <thead>
                                                <tr>
                                                    <th>{{trans('sales::view.No.')}}</th>
                                                    <th>{{trans('sales::view.Project name')}}</th>
                                                    <th>{{trans('sales::view.Customer comment')}}</th>
                                                    <th>{{trans('sales::view.Make date css')}}</th>
                                                    <th>{{trans('sales::view.Css point')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="pagination"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <!-- /.box-body -->
    </div>
</div>    
<div class="modal modal-warning" id="modal-warning" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ trans('sales::view.Notification') }}</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ trans('sales::view.Close') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
    <input type="hidden" id="startDate_val" value="" />
    <input type="hidden" id="endDate_val" value="" />
    <input type="hidden" id="criteriaIds_val" value="" />
    <input type="hidden" id="teamIds_val" value="" />
    <input type="hidden" id="projectTypeIds_val" value="" />
    <input type="hidden" id="criteriaType_val" value="" />
    <div class="modal apply-click-modal"><img class="loading-img" src="{{ asset('img/loading.gif') }}" /></div>
    
@endsection

<!-- Styles -->
@section('css')
<link href="{{ asset('css/css-screen.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('plugins/rangeSlider/css/iThing.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('plugins/rangeSlider/demo/rangeSlider.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('adminlte/plugins/iCheck/minimal/_all.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('plugins/rangeSlider/lib/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('plugins/rangeSlider/jQAllRangeSliders-min.js') }}"></script>
<script src="{{ asset('plugins/rangeSlider/demo/sliderDemo.js') }}"></script>
<script src="{{ asset('plugins/rangeSlider/demo/dateSliderDemo.js') }}"></script>
<script src="{{ asset('adminlte/plugins/iCheck/icheck.js') }}"></script>
<script src="{{ asset('plugins/hightcharts/hightcharts.js') }}"></script>
<script src="{{ asset('plugins/hightcharts/exporting.js') }}"></script>
<script src="{{ asset('js/css_analyze.js') }}"></script>
@endsection
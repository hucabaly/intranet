@extends('layouts.default')

@section('content')
<div class="row analyze-body">
    <div class="col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="box-group" id="accordion">
              <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
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
                                    @foreach($projectTypes as $type)
                                        <label class="icheckbox-container label-normal">
                                            <div class="icheckbox">
                                                <input type="checkbox" name="project_type" value="{{$type->id}}">&nbsp;&nbsp;{{$type->name}}
                                            </div>
                                        </label>
                                    @endforeach
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
                                            <input type="radio" name="tieuchi" id="tcProjectType" onclick="chonTieuChi(this);" checked>
                                            <label class="label-normal" for="tcProjectType">Theo loại dự án</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcTeam" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcTeam">Theo team</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcPm" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcPm">Theo PM</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcBrse" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcBrse">Theo BrSE</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcCustomer" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcCustomer">Theo khách hàng</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcSale" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcSale">Theo nhân viên sale</label>
                                        </div>
                                        <div class="iradio-container">
                                            <input type="radio" name="tieuchi" id="tcQuestion" onclick="chonTieuChi(this);">
                                            <label class="label-normal" for="tcQuestion">Theo Câu hỏi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div  class="theotieuchi">@include('sales::css.include.table_theotieuchi')</div>
                                        <div class="theotieuchicauhoi">@include('sales::css.include.table_theotieuchicauhoi')</div>
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
                                        <table class="table table-bordered table-hover dataTable ketqua" id='danhsachduan'>
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
                                        <select class="form-control" id="question-choose">
                                            <option value='0'>{{trans('sales::view.Please choose question')}}</option>
                                            <option class="parent" disabled="disabled">OSDC</option>
                                            <option class="parent" disabled="disabled">-- Kỹ năng, năng lực</option>
                                            <option>---- Năng lực của nhân viên công ty cung cấp cho các bạn phù hợp chứ?</option>
                                            <option>---- Kỹ năng về kỹ thuật của nhân viên công ty cung cấp cho các bạn phù hợp chứ (khả năng phân tích yêu cầu, thiết kế, coding,..)</option>
                                            <option class="parent" disabled="disabled">OSDC</option>
                                            <option class="parent" disabled="disabled">-- Kỹ năng, năng lực</option>
                                            <option>---- Năng lực của nhân viên công ty cung cấp cho các bạn phù hợp chứ?</option>
                                            <option>---- Kỹ năng về kỹ thuật của nhân viên công ty cung cấp cho các bạn phù hợp chứ (khả năng phân tích yêu cầu, thiết kế, coding,..)</option>
                                        </select>
                                    </div>
                                </div>
                                  
                            
                                <div class="row">
                                 <!----------- Bảng danh sách dưới 3 sao ------------------->   
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.List 3 * below')}}</h4>
                                        <table class="table table-bordered table-hover dataTable duoi3sao" id='duoi3sao'>
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
                                    </div>
                                 <!----------- Bảng danh sách de xuat cua khach hang ------------------->
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.Customer proposed')}}</h4>
                                        <table class="table table-bordered table-hover dataTable ketqua" id="danhsachdexuat">
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
        <!-- /.box -->
    </div>
</div>    
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
<script>
$(document).ready(function(){
    //tieu chi theo loai du an
    $("#tcProjectType").on('ifChanged', function(event){
        $('#tcProjectType').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.Project type") ?>');
        });
    });
    
    //tieu chi theo team
    $("#tcTeam").on('ifChanged', function(event){
        $('#tcTeam').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.Team") ?>');
        });
    });
    
    //tieu chi theo pm
    $("#tcPm").on('ifChanged', function(event){
        $('#tcPm').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.PM") ?>');
        });
    });
    
    //tieu chi theo BrSE
    $("#tcBrse").on('ifChanged', function(event){
        $('#tcBrse').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.BrSE") ?>');
        });
    });
    
    //tieu chi theo Customer
    $("#tcCustomer").on('ifChanged', function(event){
        $('#tcCustomer').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.Customer") ?>');
        });
    });
    
    //tieu chi theo Sale
    $("#tcSale").on('ifChanged', function(event){
        $('#tcSale').on('ifChecked', function (event) {
            $('.hienthi-theotieuchi').text('<?php echo trans("sales::view.Sale name") ?>');
        });
    });

    //tieu chi theo cau hoi CSS
    $("#tcQuestion").on('ifChanged', function(event){
        $('#tcQuestion').on('ifUnchecked', function (event) {
            $('.box-select-question').hide();
            $('div.theotieuchi').show();
            $('div.theotieuchicauhoi').hide();
        });
        $('#tcQuestion').on('ifChecked', function (event) {
            $('.box-select-question').show();
            $('div.theotieuchi').hide();
            $('div.theotieuchicauhoi').show();
        });
    });
});

</script>
@endsection
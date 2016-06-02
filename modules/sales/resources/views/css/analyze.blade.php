@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="box-group" id="accordion">
              <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
              <div class="panel box box-primary">
                  <div id="collapseOne" class="panel-collapse collapse in">
                      <div class="box-body">
                          <div class="row">
                            <div class="col-md-5">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 1")}}</h3>
                                </div>
                                <div class="row" id="dateRanger"></div>
                                <div class="row line-15">
                                    <label class="title-label">{{trans('sales::view.Project type')}}</label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="project_type[1]">&nbsp;&nbsp;Project base
                                        </div>
                                    </label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="project_type[2]">&nbsp;&nbsp;OSDC
                                        </div>
                                    </label>
                                </div>
                                <div class="row line-15">
                                    <label class="title-label">{{trans('sales::view.Team')}}</label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="team[1]">&nbsp;&nbsp;Web
                                        </div>
                                    </label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="team[2]">&nbsp;&nbsp;IOS
                                        </div>
                                    </label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="team[3]">&nbsp;&nbsp;Adroid
                                        </div>
                                    </label>
                                    <label class="icheckbox-container label-normal">
                                        <div class="icheckbox">
                                            <input type="checkbox" name="team[4]">&nbsp;&nbsp;Finance
                                        </div>
                                    </label>
                                </div>
                                <div class="row">
                                    <button class="btn btn-primary btn-filter">{{trans('sales::view.Button filter text')}}</button>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 2")}}</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-3" style="border-right: 1px solid #f4f4f4;">
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
                                    <div class="col-md-9">
                                        @include('sales::css.include.tables_theotieuchi')
                                   </div>
                                </div>
                                
                            </div>
                          </div>
                          <div class="row" style="text-align:center;">
                              <button class="btn btn-danger btn-apply">Apply</button>
                          </div>
                          <div class="row">
                              <div class="col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Result")}}</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4 class="small-title">{{trans('sales::view.Project list')}}</h4>
                                        <table class="table table-bordered table-hover dataTable ketqua">
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
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.List 3 * below')}}</h4>
                                        <table class="table table-bordered table-hover dataTable ketqua">
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
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>Quyetnd</td>
                                                    <td>3/5/2016</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="small-title">{{trans('sales::view.Customer proposed')}}</h4>
                                        <table class="table table-bordered table-hover dataTable ketqua">
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
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>ABC</td>
                                                    <td>Android</td>
                                                    <td>1/6/2016</td>
                                                    <td>96.00</td>
                                                </tr>
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
$(function () {
    $('#chartAll').highcharts({

        title: {
            text: '{{trans("sales::view.Css point")}}'
        },

        xAxis: {
            tickInterval: 1
        },

        yAxis: {
            type: 'logarithmic',
            minorTickInterval: 0.1
        },

        tooltip: {
            headerFormat: '',
            pointFormat: 'Point: {point.y}'
        },

        series: [{
            name: 'Android',
            data: [1, 2, 6, 8, 16, 5, 7, 1, 22],
            pointStart: 1
        }]
    });
});   
$(function () {
    $('#chartFilter').highcharts({
        title: {
            text: '{{trans("sales::view.Css point")}}',
            
        },
        
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Android',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'Web',
            data: [0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }]
    });
});

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
            $('table.tieuchi').show();
            $('table.tieuchi-theocauhoi').hide();
        });
        $('#tcQuestion').on('ifChecked', function (event) {
            $('.box-select-question').show();
            $('table.tieuchi').hide();
            $('table.tieuchi-theocauhoi').show();
        });
    });
});

</script>
@endsection
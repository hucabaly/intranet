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
                            <div class="col-md-5">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 1")}}</h3>
                                </div>
                                <div class="row" id="dateRanger"></div>
                                <div class="row">
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
                                <div class="row">
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
                            </div>
                            <div class="col-md-7">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{trans("sales::view.Analyze step 2")}}</h3>
                                </div>
                                <div class="col-md-3" style="border-right: 1px solid #f4f4f4;">
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi1" checked>
                                        <label class="label-normal" for="tieuchi1">Theo loại dự án</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi2" >
                                        <label class="label-normal" for="tieuchi2">Theo team</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi3" >
                                        <label class="label-normal" for="tieuchi3">Theo PM</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi4" >
                                        <label class="label-normal" for="tieuchi4">Theo BrSE</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi5" >
                                        <label class="label-normal" for="tieuchi5">Theo khách hàng</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi6" >
                                        <label class="label-normal" for="tieuchi6">Theo nhân viên sale</label>
                                    </div>
                                    <div class="iradio-container">
                                        <input type="radio" name="tieuchi" id="tieuchi7" >
                                        <label class="label-normal" for="tieuchi7">Theo nhân viên sale</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                
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
<link href="{{ asset('rangeSlider/css/iThing.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('rangeSlider/demo/rangeSlider.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('adminlte/plugins/iCheck/minimal/_all.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('rangeSlider/lib/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('rangeSlider/jQAllRangeSliders-min.js') }}"></script>
<script src="{{ asset('rangeSlider/demo/sliderDemo.js') }}"></script>
<script src="{{ asset('rangeSlider/demo/dateSliderDemo.js') }}"></script>
<script src="{{ asset('rangeSlider/demo/demo.js') }}"></script>
<script src="{{ asset('adminlte/plugins/iCheck/icheck.js') }}"></script>
<script>
$('input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });    
</script>
@endsection
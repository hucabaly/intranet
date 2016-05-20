@extends('layouts.default')

@section('content')

<div class="container content-container" style="background-color: #fff;">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h3>Url của CSS</h3>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">

                    <div class="span12">
                        <p>Link đường dẫn URL đến trang CSS bạn cần gửi cho khách hàng ở bên dưới, bạn chỉ cần copy URL này và gửi qua mail cho khách hàng.</p>
                        <p>Bạn có thể xem trang preview lời chào mừng và chi tiết bài CSS ở bên dưới, đây chính xác là những gì khách hàng có thể nhìn thấy được, nếu chưa ok, bạn có thể bấm nút back để quay lại màn hình đăng ký thông tin cho CSS</p>
                        <div class="url_make">
                            <h2>{{ url('/') }}/css/make/{{$css->token}}/{{$css->id}}</h2>
                            <button type="button" class="btn btn-primary" onclick="location.href='/css/update/{{$css->id}}'">Back</button>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
      <hr class="hr">
  </div>

  <div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h3>Preview</h3>
            <h4>Trang chào mừng</h4>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12 welcome">
                    <h1 class="welcome-title">Welcome to CSS page of Rikkeisoft</h1>
                    <div >
                        <p>Xin chào quý khách {{$css->customer_name}} thuộc công ty {{$css->company_name}}.</p>
                        <p>Cảm ơn quý khách đã luôn đồng hành cùng Rikkeisoft thời gian qua.</p>
                        <p>Xin quý khách vui lòng dành chút thời gian để làm phiếu khảo sát ý kiến khách hàng về dự án {{$css->project_name}}.</p>
                        <p class="kinh-thu">Kính thư: nhân viên {{$user->name}}</p>
                        <div style="clear:both;"></div>
                        <button type="button" class="btn btn-primary btn-next" onclick="goto_make();">Next</button>
                    </div>      

                </div>
            </div>
        </div>

    </div>
  </div>
    <div class="row">
      <hr class="hr">
    </div>
    <div class="make-css container">
        <div class="page-header">
            <h3>Preview</h3>
            <h4>Trang làm CSS</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section id="header-makecss">
                    <div id="chu-trai"><h2>お客様アンケート</h2></div>
                    <div id="logo-rikkei"><img src="{{ URL::asset('img/logo') }}"></div>
                </section>
                <section>
                    @include('sales::css.include.project_ODSC')
                </section>
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

<script type="text/javascript">
    $(function () { $('#rateit_star').rateit({min: 1, max: 10, step: 2}); });
</script>
- See more at: http://hocphp.info/danh-gia-dang-ngoi-sao-voi-jquery-rateit/#sthash.e8vhG2FN.dpuf
@endsection
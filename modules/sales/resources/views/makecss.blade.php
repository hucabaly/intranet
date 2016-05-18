@extends('layouts.default')
<style>
.page-header{text-align: center;}
.container-fluid .row-fluid p{font-size:15px;}
.container-fluid .row-fluid p.kinh-thu{float:right;}
@media (min-width: 992px){
	.col-md-offset-1 {
	    margin-left: 11.333333%;
	    width: 75.333333%;
	}
}
.container-fluid .row-fluid button.btn-next{float: right; background-color: #690F8E; padding: 3px 28px;}

</style>
@section('content')
<div class="container" style="background-color: #fff;min-height: 400px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 welcome">
            <div class="page-header">
			  	<h1>Welcome to CSS page of Rikkeisoft</h1>
			</div>
			<div class="container-fluid">
				<div class="row-fluid">
				    
				    <div class="span12">
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
        <div class="make-css" style="display: none;">
        	man hinh lam CSS
        </div>
    </div>
</div>
@endsection
<!-- Script -->
@section('script')
<script type="text/javascript">
	function goto_make(){
		$(".welcome").hide();
		$(".make-css").show();
	}

</script>
@endsection
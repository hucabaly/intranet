@extends('layouts.default')
<style>
.box-header{text-align: center;margin-bottom: 20px;}
.container-fluid .row-fluid p{font-size:15px;}
.container-fluid .row-fluid p.kinh-thu{float:right;}
@media (min-width: 992px){
	.col-md-offset-1 {
	    margin-left: 11.333333%;
	    width: 75.333333%;
	}
}
.container-fluid .row-fluid button.btn-next{float: right; background-color: #690F8E; padding: 3px 28px;}

.make-css{padding: 20px;}
#header-makecss {margin-top: 20px;}
#header-makecss #chu-trai{float:left;width: 300px;background-color:rgb(24, 52, 93);text-align: center;width: 500px;}
#header-makecss #chu-trai h2{color:#fff; margin:5px 0; font-size: 25px;font-weight: bold;}
#header-makecss #logo-rikkei{float: right;margin-top: -20px;}

.bang1 tr:first-child td,.bang2 tr:first-child {color:#fff;  background-color: rgb(24, 52, 93);}

.bang1 input[type=text],.bang2 input[type=text] {outline: none;border: none;width: 100%}
.bang1 .tongdiem{text-align: center;}
.table td{font-size: 13px;}
</style>
@section('content')
<div class="container box box-primary" style="background-color: #fff;min-height: 400px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 welcome">
            <div class="box-header with-border">
			  	<h1 >Welcome to CSS page of Rikkeisoft</h1>
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
        <div class="make-css container" style="display: none;">
        	<div class="row">
		        <div class="col-md-12">
		            <section id="header-makecss">
		                <div id="chu-trai"><h2>お客様アンケート</h2></div>
		                <div id="logo-rikkei"><img src="{{ URL::asset('img/logo') }}"></div>
		            </section>
		            <section>
		            	<table class="table table-bordered bang1">
		            		  <tr><td colspan="5" class="top">プロジェクト情報</td></tr>
						      <tr>
						        <td class="title">Tên project</td>
						        <td><input type="text" id="pj_name" name="pj_name"></td>
						        <td class="title2">Thời gian dự án</td>
						        <td><input type="text" id="pj_time" name="pj_time"></td>
						        <td class="tongdiem">Tổng số điểm</td>
						      </tr>
						      <tr>
						        <td class="title">Sales bên rikkei</td>
						        <td><input type="text" id="rikkei_name" name="rikkei_name"></td>
						        <td class="title2">Người đại diện bên phía khách hàng</td>
						        <td><input type="text" id="cus_name" name="cus_name"></td>
						        <td rowspan="3"></td>
						      </tr>
						      <tr>
						        <td class="title">Tên PM</td>
						        <td><input type="text" id="pm_name" name="pm_name"></td>
						        <td class="title2">Người thực hiện CSS này</td>
						        <td><input type="text" id="mk_name" name="mk_name"></td>
						        
						      </tr>
						      <tr>
						        <td class="title">Tên BrSE</td>
						        <td><input type="text" id="brse_name" name="brse_name"></td>
						        <td class="title2">Địa chỉ mail của người thực hiện CSS</td>
						        <td><input type="text" id="mk_email" name="mk_email"></td>
						        
						      </tr>
						 </table>

						 <table class="table table-bordered bang2">
		            		  
						      <tr>
						        <td>質問</td>
						        <td>内容詳細</td>
						        <td>回答</td>
						        <td>コメント</td>
						        
						      </tr>
						      <tr>
						        <td class="title" colspan="2">Sales bên rikkei</td>
						        <td><input type="text" id="rikkei_name" name="rikkei_name"></td>
						        <td class="title2">Người đại diện bên phía khách hàng</td>
						       
						      </tr>
						      <tr>
						        <td class="title" colspan="2">Tên PM</td>
						        <td><input type="text" id="pm_name" name="pm_name"></td>
						        <td class="title2">Người thực hiện CSS này</td>
						        
						        
						      </tr>
						      <tr>
						        <td class="title" colspan="2">Tên BrSE</td>
						        <td><input type="text" id="brse_name" name="brse_name"></td>
						        <td class="title2">Địa chỉ mail của người thực hiện CSS</td>
						        
						        
						      </tr>
						 </table>
		            </section>
		        </div>
		    </div>
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
@extends('layouts.guest')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        {{trans("sales::view.success title")}}
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                      {{trans("sales::view.success notification",["project_name"=>$css->project_name])}}
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
</div>    
@endsection
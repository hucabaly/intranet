@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('sales::view.Css result list of')}}<strong>{{$css->project_name}}</strong></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                @if(count($cssResults))
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.STT') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Make name') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Make email') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.CSS mark') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Make date css') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.View css detail') }}</th>
                                            
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cssResults as $item)
                                        <tr role="row" class="odd">
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->stt }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->email }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ number_format($item->mark,2) }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->make_date }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" class="with-textalign-center" ><a href="/css/detail/{{$item->id}}">{{ trans('sales::view.View') }}</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <h2>{{ trans('sales::view.No result not found')}}</h2>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                    <?php echo $cssResults->render(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->


                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
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
@endsection
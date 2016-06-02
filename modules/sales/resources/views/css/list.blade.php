@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('sales::view.Css list') }}</h3>
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
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row">
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.STT') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project type') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project base name') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Team relate') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Sale name 2') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.PM') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.BrSE') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project date') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Customer company') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Customer name') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Create date css') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Count make css') }}</th>
                                            <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" ></th>
                                            
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($css as $item)
                                        <tr role="row" class="odd">
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->stt }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->project_type_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->project_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->teams_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->sale_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->pm_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->brse_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->start_date }} - {{ $item->end_date }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->company_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->customer_name }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->create_date }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->countCss }}</td>
                                            <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >
                                                @if($item->countCss > 0)
                                                    <a href="/css/view/{{$item->id}}">{{ trans('sales::view.View css') }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                    <?php echo $css->render(); ?>
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
- See more at: http://hocphp.info/danh-gia-dang-ngoi-sao-voi-jquery-rateit/#sthash.e8vhG2FN.dpuf
@endsection
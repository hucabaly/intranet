@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ trans('sales::view.Css list') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @if(count($css) > 0)
                        <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.STT') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project type') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project base name') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Team relate') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Sale name 2') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.PM') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Project date') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Customer company') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Customer name') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Create date css') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Count make css') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.View css make list')}}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.Url css')}}</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    @foreach($css as $item)
                                    <tr role="row" class="odd">
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->stt }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->project_type_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->project_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->teamsName }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->sale_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->pm_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->start_date }} - {{ $item->end_date }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->company_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->customer_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->create_date }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" class="with-textalign-center" >{{ $item->countCss }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" class="with-textalign-center" >
                                            @if($item->countCss > 0)
                                                <a href="{{$item->hrefToView}}">{{ trans('sales::view.View') }}</a>
                                            @endif
                                        </td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >
                                            <a href="javascript:void(0)" data-href="{{$item->url}}" onclick="copyToClipboard(this);">{{ trans('sales::view.Copy to clipboard')}}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>    
                        @else
                        <h3>{{trans('sales::view.No result not found')}}</h3>
                        @endif
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
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<div class="modal modal-primary" id="modal-clipboard">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ trans('sales::view.Notification') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ trans('sales::view.Text notification copy clipboard')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ trans('sales::view.Close') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
<!-- Styles -->
@section('css')
<link href="{{ asset('sales/css/sales.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('sales/js/css/list.js') }}"></script>
@endsection
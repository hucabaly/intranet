@extends('layouts.default')
@section('title')
    {{ trans('sales::view.Css list') }}
@endsection
@section('content')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\View;

?>
<div class="row list-css-page">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">{{ trans('sales::view.Css list') }}</strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box-body">
                            @include('team::include.filter')
                            @include('team::include.pager')
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting {{ Config::getDirClass('id') }}" data-order="id" data-dir="{{ Config::getDirOrder('id') }}" >{{ trans('sales::view.Id') }}</th>
                                        <th class="sorting {{ Config::getDirClass('project_type_name') }}" data-order="project_type_name" data-dir="{{ Config::getDirOrder('project_type_name') }}" >{{ trans('sales::view.Project type') }}</th>
                                        <th class="sorting {{ Config::getDirClass('project_name') }}" data-order="project_name" data-dir="{{ Config::getDirOrder('project_name') }}" >{{ trans('sales::view.Project base name') }}</th>
                                        <th class="sorting {{ Config::getDirClass('teams.name') }}" data-order="teams.name" data-dir="{{ Config::getDirOrder('teams.name') }}" >{{ trans('sales::view.List.Team relate') }}</th>
                                        <th class="sorting {{ Config::getDirClass('sale_name') }}" data-order="sale_name" data-dir="{{ Config::getDirOrder('sale_name') }}" >{{ trans('sales::view.Sale name 2') }}</th>
                                        <th class="sorting {{ Config::getDirClass('pm_name') }}" data-order="pm_name" data-dir="{{ Config::getDirOrder('pm_name') }}" >{{ trans('sales::view.PM') }}</th>
                                        <th class="sorting {{ Config::getDirClass('start_date') }}" data-order="start_date" data-dir="{{ Config::getDirOrder('start_date') }}" >{{ trans('sales::view.Project date') }}</th>
                                        <th class="sorting {{ Config::getDirClass('company_name') }}" data-order="company_name" data-dir="{{ Config::getDirOrder('company_name') }}" >{{ trans('sales::view.Customer company') }}</th>
                                        <th class="sorting {{ Config::getDirClass('customer_name') }}" data-order="customer_name" data-dir="{{ Config::getDirOrder('customer_name') }}" >{{ trans('sales::view.Customer name') }}</th>
                                        <th class="sorting {{ Config::getDirClass('css.created_at') }}" data-order="css.created_at" data-dir="{{ Config::getDirOrder('css.created_at') }}" >{{ trans('sales::view.Create date css') }}</th>
                                        <th class="sorting {{ Config::getDirClass('countCss') }}" data-order="countCss" data-dir="{{ Config::getDirOrder('countCss') }}"  >{{ trans('sales::view.Count make css') }}</th>
                                        <th  rowspan="1" colspan="1" >{{ trans('sales::view.View css make list')}}</th>
                                        <th  rowspan="1" colspan="1" >{{ trans('sales::view.Url css')}}</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    <tr class="filter-input-grid">
                                        <td>&nbsp;</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[css_project_type.name]" value="{{ Form::getFilterData('css_project_type.name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[project_name]" value="{{ Form::getFilterData('project_name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[teams.name]" value="{{ Form::getFilterData('teams.name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[employees.name]" value="{{ Form::getFilterData('employees.name') }}"  />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[pm_name]" value="{{ Form::getFilterData('pm_name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[start_date]" value="{{ Form::getFilterData('start_date') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[company_name]" value="{{ Form::getFilterData('company_name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[customer_name]" value="{{ Form::getFilterData('customer_name') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class='form-control filter-grid' name="filter[css.created_at]" value="{{ Form::getFilterData('css.created_at') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @if(count($css) > 0)
                                    @foreach($css as $item)
                                    <tr role="row" class="odd">
                                        <td rowspan="1" colspan="1" >{{ $item->id }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->project_type_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->project_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->teamsName }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->sale_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->pm_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->start_date }} - {{ $item->end_date }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->company_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->customer_name }}</td>
                                        <td rowspan="1" colspan="1" >{{ $item->created_date }}</td>
                                        <td rowspan="1" colspan="1" class="with-textalign-center" >{{ $item->countCss }}</td>
                                        <td rowspan="1" colspan="1" class="with-textalign-center" >
                                            @if($item->countCss > 0)
                                                <a href="{{$item->hrefToView}}">{{ trans('sales::view.View') }}</a>
                                            @endif
                                        </td>
                                        <td  rowspan="1" colspan="1" >
                                            <a href="javascript:void(0)" data-href="{{$item->url}}" onclick="copyToClipboard(this);">{{ trans('sales::view.Copy to clipboard')}}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr><td colspan="13" class="text-align-center"><h2>{{trans('sales::view.No result not found')}}</h2></td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>    
                        
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
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
<link href="{{ asset('sales/css/sales.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('sales/js/css/list.js') }}"></script>
@endsection
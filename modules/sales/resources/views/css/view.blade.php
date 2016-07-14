@extends('layouts.default')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\View;

?>
@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ trans('sales::view.Css result list of')}}<strong>{{$css->project_name}}</strong></h3>
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
                            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting {{ Config::getDirClass('id') }}" data-order="id" data-dir="{{ Config::getDirOrder('id') }}"  >{{ trans('sales::view.Id') }}</th>
                                        <th class="sorting {{ Config::getDirClass('name') }}" data-order="name" data-dir="{{ Config::getDirOrder('name') }}"  >{{ trans('sales::view.Make name') }}</th>
                                        <th class="sorting {{ Config::getDirClass('sale_name') }}" data-order="sale_name" data-dir="{{ Config::getDirOrder('sale_name') }}"  >{{ trans('sales::view.Sale name 2') }}</th>
                                        <th class="sorting {{ Config::getDirClass('avg_point') }}" data-order="avg_point" data-dir="{{ Config::getDirOrder('avg_point') }}" >{{ trans('sales::view.CSS mark') }}</th>
                                        <th class="sorting {{ Config::getDirClass('created_at') }}" data-order="created_at" data-dir="{{ Config::getDirOrder('created_at') }}"  >{{ trans('sales::view.Make date css') }}</th>
                                        <th tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ trans('sales::view.View css detail') }}</th>

                                   </tr>
                                </thead>
                                <tbody>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" name="filter[css_result.name]" value="{{ Form::getFilterData('css_result.name') }}"  class="filter-grid" />
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" name="filter[employees.name]" value="{{ Form::getFilterData('employees.name') }}" class="filter-grid" />
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="text" name="filter[css_result.created_at]" value="{{ Form::getFilterData('css_result.created_at') }}"  class="filter-grid" />
                                            </div>
                                        </div>
                                    </td>
                                    @if(count($cssResults))
                                    @foreach($cssResults as $item)
                                    <tr role="row" class="odd">
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->id }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->sale_name }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ number_format($item->avg_point,2) }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >{{ $item->make_date }}</td>
                                        <td tabindex="0" aria-controls="example2" rowspan="1" colspan="1" ><a href="/css/detail/{{$item->id}}">{{ trans('sales::view.View') }}</a></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr><td colspan="13"><h2>{{ trans('sales::view.No result not found')}}</td></tr></h2>
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
                            <?php echo $cssResults->render(); ?>
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
@endsection
<!-- Styles -->
@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
<link href="{{ asset('sales/css/sales.css') }}" rel="stylesheet" type="text/css" >
@endsection
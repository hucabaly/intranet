@extends('layouts.default')

@section('title')
{{ trans('team::view.Acl') }} 
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <a href="{{ route('team::setting.acl.create')  }}" class="btn-add">{{ trans('team::view.Create new') }}</a>
        </p>
    </div>
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-body">
                @include('team::include.pager')
                <div class="filter-action">
                    <button class="btn btn-primary btn-reset-filter">
                        <span>{{ trans('team::view.Reset filter') }} <i class="fa fa-spin fa-refresh hidden"></i></span>
                    </button>
                    <button class="btn btn-primary btn-search-filter">
                        <span>{{ trans('team::view.Search') }} <i class="fa fa-spin fa-refresh hidden"></i></span>
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                    <thead>
                        <tr>
                            <th class="sorting {{ Config::getDirClass('id') }} col-id" onclick="window.location.href = '{{Config::getUrlOrder('id')}}';">Id</th>
                            <th class="sorting {{ Config::getDirClass('name') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('name')}}';">{{ trans('team::view.Name') }}</th>
                            <th class="sorting {{ Config::getDirClass('description') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('description')}}';">{{ trans('team::view.Description') }}</th>
                            <th class="sorting {{ Config::getDirClass('route') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('route')}}';">Route</th>
                            <th class="sorting {{ Config::getDirClass('parent_id') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('parent_id')}}';">{{ trans('team::view.Parent id') }}</th>
                            <th class="sorting {{ Config::getDirClass('sort_order') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('sort_order')}}';">{{ trans('team::view.Sort order') }}</th>
                            <th class="col-action">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="filter-input-grid">
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>{{ trans('team::view.From') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="filter[id][from]" value="{{ Form::getFilterData('id', 'from') }}" placeholder="{{ trans('team::view.From') }}" class="filter-grid" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>{{ trans('team::view.To') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="filter[id][to]" value="{{ Form::getFilterData('id', 'to') }}" placeholder="{{ trans('team::view.To') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[name]" value="{{ Form::getFilterData('name') }}" placeholder="{{ trans('team::view.Name') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[description]" value="{{ Form::getFilterData('description') }}" placeholder="{{ trans('team::view.Description') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[route]" value="{{ Form::getFilterData('route') }}" placeholder="Route" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>{{ trans('team::view.From') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="filter[parent_id][from]" value="{{ Form::getFilterData('parent_id', 'from') }}" placeholder="{{ trans('team::view.From') }}" class="filter-grid" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>{{ trans('team::view.To') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="filter[parent_id][to]" value="{{ Form::getFilterData('parent_id', 'to') }}" placeholder="{{ trans('team::view.To') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        @if(isset($collectionModel) && count($collectionModel))
                            @foreach($collectionModel as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->route }}</td>
                                    <td>{{ $item->parent_id }}</td>
                                    <td>{{ $item->sort_order }}</td>
                                    <td>
                                        <a href="{{ route('team::setting.acl.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.Edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

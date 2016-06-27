@extends('layouts.default')

@section('title')
Menu item
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;

$menuItemsTable = Rikkei\Core\Model\MenuItems::getTableName();
$menuGroupTable = \Rikkei\Core\Model\Menus::getTableName();
?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <a href="{{ route('core::setting.menu.item.create')  }}" class="btn-add">{{ trans('team::view.Create new') }}</a>
            <a href="{{ route('core::setting.menu.group.index')  }}" class="btn btn-primary btn-mar-left">{{ trans('core::view.Menu group manage') }}</a>
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
                            <th class="sorting {{ Config::getDirClass('nane_group') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('nane_group')}}';">Menu group</th>
                            <th class="sorting {{ Config::getDirClass('name_parent') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('name_parent')}}';">{{ trans('team::view.Parent') }}</th>
                            <th class="sorting {{ Config::getDirClass('url') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('url')}}';">Url</th>
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
                                        <input type="text" name="filter[{{ $menuItemsTable }}.id][from]" value="{{ Form::getFilterData("{$menuItemsTable}.id", 'from') }}" placeholder="{{ trans('team::view.From') }}" class="filter-grid" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>{{ trans('team::view.To') }}</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="filter[{{ $menuItemsTable }}.id][to]" value="{{ Form::getFilterData("{$menuItemsTable}.id", 'to') }}" placeholder="{{ trans('team::view.To') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $menuItemsTable }}.name]" value="{{ Form::getFilterData("{$menuItemsTable}.name") }}" placeholder="{{ trans('team::view.Name') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $menuGroupTable }}.name]" value="{{ Form::getFilterData("{$menuGroupTable}.name") }}" placeholder="Menu group" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[menu_item_parent.name]" value="{{ Form::getFilterData('menu_item_parent.name') }}" placeholder="{{ trans('team::view.Parent') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $menuItemsTable }}.url]" value="{{ Form::getFilterData("{$menuItemsTable}.url") }}" placeholder="Url" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        @if(isset($collectionModel) && count($collectionModel))
                            @foreach($collectionModel as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->nane_group }}</td>
                                    <td>{{ $item->name_parent }}</td>
                                    <td>{{ $item->url }}</td>
                                    <td>
                                        <a href="{{ route('core::setting.menu.item.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.Edit') }}</a>
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

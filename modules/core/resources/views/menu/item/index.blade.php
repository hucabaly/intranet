@extends('layouts.default')

@section('title')
Menu item
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
@endsection

@section('content')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\View;

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
                @include('team::include.filter')
                @include('team::include.pager')
            </div>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                    <thead>
                        <tr>
                            <th class="col-id">{{ trans('core::view.NO.') }}</th>
                            <th class="sorting {{ Config::getDirClass('name') }} col-name" data-order="name" data-dir="{{ Config::getDirOrder('name') }}">{{ trans('team::view.Name') }}</th>
                            <th class="sorting {{ Config::getDirClass('nane_group') }} col-name" data-order="nane_group" data-dir="{{ Config::getDirOrder('nane_group') }}">Menu group</th>
                            <th class="sorting {{ Config::getDirClass('name_parent') }} col-name" data-order="name_parent" data-dir="{{ Config::getDirOrder('name_parent') }}">Menu Parent</th>
                            <th class="sorting {{ Config::getDirClass('url') }} col-name" data-order="url" data-dir="{{ Config::getDirOrder('url') }}">Url</th>
                            <th class="col-action">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="filter-input-grid">
                            <td>&nbsp;</td>
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
                            <?php $i = View::getNoStartGrid($collectionModel); ?>
                            @foreach($collectionModel as $item)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->nane_group }}</td>
                                    <td>{{ $item->name_parent }}</td>
                                    <td>{{ $item->url }}</td>
                                    <td>
                                        <a href="{{ route('core::setting.menu.item.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.Edit') }}</a>
                                        <form action="{{ route('core::setting.menu.item.delete') }}" method="post" class="form-inline">
                                            {!! csrf_field() !!}
                                            {!! method_field('delete') !!}
                                            <input type="hidden" name="id" value="{{ $item->id }}" />
                                            <button href="" class="btn-delete delete-confirm" disabled>
                                                <span>{{ trans('core::view.Remove') }}</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

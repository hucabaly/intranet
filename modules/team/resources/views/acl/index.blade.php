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
use Rikkei\Team\Model\Action;

$actionTable = Action::getTableName();
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
                @include('team::include.filter')
                @include('team::include.pager')
            </div>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                    <thead>
                        <tr>
                            <th class="sorting {{ Config::getDirClass('id') }} col-id" width="30px" onclick="window.location.href = '{{Config::getUrlOrder('id')}}';">{{ trans('core::view.ID') }}</th>
                            <th class="sorting {{ Config::getDirClass('name') }} col-name" width="20px" onclick="window.location.href = '{{Config::getUrlOrder('name')}}';">Code</th>
                            <th class="sorting {{ Config::getDirClass('description') }} col-name" width="150px" onclick="window.location.href = '{{Config::getUrlOrder('description')}}';">{{ trans('team::view.Description') }}</th>
                            <th class="sorting {{ Config::getDirClass('route') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('route')}}';">Route</th>
                            <th class="sorting {{ Config::getDirClass('name_parent') }} col-name" width="150px" onclick="window.location.href = '{{Config::getUrlOrder('name_parent')}}';">{{ trans('team::view.Parent') }}</th>
                            <th class="sorting {{ Config::getDirClass('sort_order') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('sort_order')}}';">{{ trans('team::view.Sort order') }}</th>
                            <th class="col-action col-a2">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="filter-input-grid">
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $actionTable }}.id][from]" value="{{ Form::getFilterData("{$actionTable}.id", 'from') }}" placeholder="{{ trans('team::view.From') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $actionTable }}.name]" value="{{ Form::getFilterData("{$actionTable}.name") }}" placeholder="{{ trans('team::view.Name') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $actionTable }}.description]" value="{{ Form::getFilterData("{$actionTable}.description") }}" placeholder="{{ trans('team::view.Description') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[{{ $actionTable }}.route]" value="{{ Form::getFilterData("{$actionTable}.route") }}" placeholder="Route" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[action_parent.name]" value="{{ Form::getFilterData('action_parent.name') }}" placeholder="{{ trans('team::view.Parent id') }}" class="filter-grid" />
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
                                    <td>{{ $item->name_parent }}</td>
                                    <td>{{ $item->sort_order }}</td>
                                    <td>
                                        <a href="{{ route('team::setting.acl.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.Edit') }}</a>
                                        <form action="{{ route('team::setting.acl.delete') }}" method="post" class="form-inline">
                                            {!! csrf_field() !!}
                                            {!! method_field('delete') !!}
                                            <input type="hidden" name="id" value="{{ $item->id }}" />
                                            <button href="" class="btn-delete delete-confirm" disabled>
                                                <span>{{ trans('team::view.Remove') }}</span>
                                            </button>
                                        </form>
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

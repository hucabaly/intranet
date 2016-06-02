@extends('layouts.default')

@section('title')
Role Setting
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h2 class="box-title">{{ trans('team::view.Roles list') }}</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{ route('team::setting.role.create') }}" class="btn-add">{{ trans('team::view.Create new') }}</a>
                    </div>
                </div>
                @include('team::include.pager')
                </div>
                <div class="table-responsive">
                    <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                        <thead>
                            <tr>
                                <th class="sorting {{ Config::getDirClass('id') }} col-id" onclick="window.location.href='{{Config::getUrlOrder('id')}}';">Id</th>
                                <th class="sorting {{ Config::getDirClass('name') }} col-name" onclick="window.location.href='{{Config::getUrlOrder('name')}}';">{{ trans('team::view.Name') }}</th>
                                <th class="col-action">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php /*tr class="filter-input-grid">
                                <td>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>From</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="filter[id][from]" value="{{ Form::getData('filter.id.from') }}" placeholder="From" class="filter-grid" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>To</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="filter[id][to]" value="{{ Form::getData('filter.id.to') }}" placeholder="To" class="filter-grid" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-fluid">
                                        <div class="col-md-12">
                                            <input type="text" name="filter[name]" value="{{ Form::getData('filter.name') }}" placeholder="Name" class="filter-grid" />
                                        </div>
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                            </tr*/ ?>
                            @if(isset($collectionModel) && count($collectionModel))
                                @foreach($collectionModel as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href="{{ route('team::setting.role.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.Edit') }}</a>
                                            <span>|</span>
                                            <form method="post" action="{{ route('team::setting.role.delete') }}" class="form-inline">
                                                {!! csrf_field() !!}
                                                {!! method_field('delete') !!}
                                                <input type="hidden" name="id" value="{{ $item->id }}" />
                                                <button class="btn-delete delete-confirm" type="submit" data-noti="{{ trans('team::view.Are you sure delete this role and all link this role with employee?') }}">
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

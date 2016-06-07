@extends('layouts.default')

@section('title')
{{ trans('team::view.Employee List') }} 
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
                <h2 class="box-title">{{ trans('team::view.Member list') }}</h2>
            </div>
            <div class="box-body">
                @include('team::include.pager')
                <div class="filter-action">
                    <button class="btn-move btn-reset-filter">
                        <span>{{ trans('team::view.Reset filter') }} <i class="fa fa-spin fa-refresh hidden"></i></span>
                    </button>
                    <button class="btn-move btn-search-filter">
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
                            <th class="sorting {{ Config::getDirClass('email') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('email')}}';">Email</th>
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
                                        <input type="text" name="filter[email]" value="{{ Form::getFilterData('email') }}" placeholder="Email" class="filter-grid" />
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
                            <td>{{ $item->email }}</td>
                            <td>
                                <a href="{{ route('team::team.member.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.View profile') }}</a>
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

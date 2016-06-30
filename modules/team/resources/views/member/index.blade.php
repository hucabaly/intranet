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
            <div class="box-body">
                @include('team::include.filter')
                @include('team::include.pager')
            </div>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                    <thead>
                        <tr>
                            <th class="sorting {{ Config::getDirClass('id') }} col-id" onclick="window.location.href = '{{Config::getUrlOrder('id')}}';">{{ trans('core::view.ID') }}</th>
                            <th class="sorting {{ Config::getDirClass('employee_code') }} col-id" onclick="window.location.href = '{{Config::getUrlOrder('employee_code')}}';">Code</th>
                            <th class="sorting {{ Config::getDirClass('name') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('name')}}';">{{ trans('team::view.Name') }}</th>
                            <th class="sorting {{ Config::getDirClass('email') }} col-name" onclick="window.location.href = '{{Config::getUrlOrder('email')}}';">Email</th>
                            <th class="col-action">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="filter-input-grid">
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[id][from]" value="{{ Form::getFilterData('id', 'from') }}" placeholder="{{ trans('team::view.From') }}" class="filter-grid" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="text" name="filter[employee_code]" value="{{ Form::getFilterData('employee_code') }}" placeholder="Code" class="filter-grid" />
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
                            <td>{{ $item->employee_code }}</td>
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

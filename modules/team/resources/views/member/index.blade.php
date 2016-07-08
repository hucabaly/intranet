@extends('layouts.default')

@section('title')
{{ trans('team::view.Employee List') }} 
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datatables/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/select2/select2.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<?php

use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
use Rikkei\Core\View\View;
use Rikkei\Team\View\TeamList;

$teamsOptionAll = TeamList::toOption(null, false, false);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-body">
                <div class="team-select-box">
                    <label for="select-team-member">{{ trans('team::view.Choose team') }}</label>
                    <div class="input-box">
                        <select name="team_all" id="select-team-member"
                            class="form-control select-search input-select-team-member">
                            @if (count($teamsOptionAll))
                                @foreach($teamsOptionAll as $option)
                                    <option value="{{ URL::route('team::team.member.index', ['id' => $option['value']]) }}"<?php
                                        if ($option['value'] == 0): ?> selected<?php endif; 
                                    ?>>{{ $option['label'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @include('team::include.filter')
                @include('team::include.pager')
            </div>
            <div class="table-responsive">
                <table class="table table-striped dataTable table-bordered table-hover table-grid-data">
                    <thead>
                        <tr>
                            <th class="col-id">{{ trans('core::view.NO.') }}</th>
                            <th class="sorting {{ Config::getDirClass('employee_code') }} col-id" data-order="employee_code" data-dir="{{ Config::getDirOrder('employee_code') }}">Code</th>
                            <th class="sorting {{ Config::getDirClass('name') }} col-name" data-order="name" data-dir="{{ Config::getDirOrder('name') }}">{{ trans('team::view.Name') }}</th>
                            <th class="sorting {{ Config::getDirClass('email') }} col-name" data-order="email" data-dir="{{ Config::getDirOrder('email') }}">Email</th>
                            <th class="col-action">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="filter-input-grid">
                            <td>&nbsp;</td>
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
                            <?php $i = View::getNoStartGrid($collectionModel); ?>
                            @foreach($collectionModel as $item)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $item->employee_code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <a href="{{ route('team::team.member.edit', ['id' => $item->id ]) }}" class="btn-edit">{{ trans('team::view.View profile') }}</a>
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

@section('script')
<script src="{{ URL::asset('adminlte/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('team/js/script.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        selectSearchReload();
        
        $('.input-select-team-member').on('change', function(event) {
            value = $(this).val();
            window.location.href = value;
        });
    });
</script>
@endsection
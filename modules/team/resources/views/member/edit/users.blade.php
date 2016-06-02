<?php
use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;
?>

@include('team::include.pager')
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
            @if(isset($collectionModel) && count($collectionModel))
            @foreach($collectionModel as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    <a href="#" class="btn-edit">{{ trans('team::view.View') }}</a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
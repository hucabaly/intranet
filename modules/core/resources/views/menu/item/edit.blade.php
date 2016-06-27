@extends('layouts.default')

<?php
use Rikkei\Core\View\Form;
use Rikkei\Core\Model\Menus;
use Rikkei\Core\Model\MenuItems;
use Rikkei\Team\Model\Action;
use Rikkei\Core\View\View;

$activeOptions = MenuItems::toOptionState();
$menusOptions = Menus::toOption();
$menuItemOptions = MenuItems::toOption(Form::getData('menuitem.id'));
$actionsOptions = Action::toOption();
$routeListOption = View::routeListToOption();
?>

@section('title')
@if (! Form::getData('menuitem.id'))
    {{ trans('core::view.Create new') }}
@else
    Menu item: {{ Form::getData('menuitem.name') }}
@endif
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/select2/select2.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row menu-group">
    <form action="{{ route('core::setting.menu.item.save') }}" method="post" class="form-horizontal" id="form-edit-menu-item">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ Form::getData('menuitem.id') }}" />
        <div class=" col-md-12 box-action">
            <input type="submit" class="btn-edit" name="submit" value="{{ trans('team::view.Save') }}" />
            <a href="{{ route('core::setting.menu.item.index') }}" class="btn btn-primary">{{ trans('core::view.Back') }}</a>
            @if (Form::getData('menuitem.id'))
                <input type="submit" class="btn-delete btn-action delete-confirm" disabled name="submit_delete" 
                    value="{{ trans('team::view.Remove') }}" data-noti="{{ trans('core::view.Are you sure delete this menu and all children?') }}" />
            @endif
        </div>
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label required">{{ trans('team::view.Name') }}<em>*</em></label>
                        <div class="input-box col-md-9">
                            <input type="text" name="item[name]" class="form-control" placeholder="{{ trans('team::view.Name') }}" value="{{ Form::getData('menuitem.name') }}" />
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">{{ trans('core::view.State') }}</label>
                        <div class="input-box col-md-9">
                            <select class="form-control" name="item[state]">
                                @foreach ($activeOptions as $option)
                                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menuitem.state')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">Menu Group</label>
                        <div class="input-box col-md-9">
                            <select class="form-control" name="item[menu_id]">
                                @foreach ($menusOptions as $option)
                                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menuitem.menu_id')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">Menu Parent</label>
                        <div class="input-box col-md-9">
                            <select class="form-control select-search" name="item[parent_id]">
                                @foreach ($menuItemOptions as $option)
                                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menuitem.parent_id')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">Url</label>
                        <div class="input-box col-md-9">
                            <select name="item[url]" class="form-control">
                                @if ($routeListOption)
                                    @foreach ($routeListOption as $option)
                                        <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menuitem.url')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">Action</label>
                        <div class="input-box col-md-9">
                            <select class="select-search form-control" name="item[action_id]">
                                @foreach ($actionsOptions as $option)
                                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menuitem.action_id')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">{{ trans('team::view.Sort order') }}</label>
                        <div class="input-box col-md-9">
                            <input type="number" name="item[sort_order]" class="form-control" placeholder="{{ trans('team::view.Sort order') }}" value="{{ Form::getData('menuitem.sort_order') }}" />
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
</div>
<?php
//remove flash session
Form::forget();
?>
@endsection

@section('script')
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('adminlte/plugins/select2/select2.full.min.js') }}"></script>
<script>
    jQuery(document).ready(function ($) {
        var messages = {
            'item[name]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
              }
        }
        var rules = {
            'item[name]': {
                required: true,
                rangelength: [1, 50]
            }
        };
        $('#form-edit-menu-item').validate({
            rules: rules,
            messages: messages
        });
        selectSearchReload();
    });
</script>
@endsection
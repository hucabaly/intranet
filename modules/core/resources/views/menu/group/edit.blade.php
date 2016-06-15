@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Core\Model\Menus;

$actionOptions = Menus::toOptionState();
?>

@section('title')
@if (! Form::getData('menus.id'))
    {{ trans('core::view.Create new') }}
@else
    Menu group: {{ Form::getData('menus.name') }}
@endif
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row menu-group">
    <form action="{{ route('core::setting.menu.group.save') }}" method="post" class="form-horizontal" id="form-edit-menus">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ Form::getData('menus.id') }}" />
        <div class=" col-md-12 box-action">
            <input type="submit" class="btn-edit" name="submit" value="{{ trans('team::view.Save') }}" />
            <a href="{{ route('core::setting.menu.group.index') }}" class="btn btn-primary">{{ trans('core::view.Back') }}</a>
            <input type="submit" class="btn-delete btn-action<?php if (Form::getData('menus.id')): ?> delete-confirm<?php endif; ?>" 
                   disabled name="submit_delete" value="{{ trans('team::view.Remove') }}" />
        </div>
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">{{ trans('team::view.Name') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" name="item[name]" class="form-control" placeholder="{{ trans('team::view.Name') }}" value="{{ Form::getData('menus.name') }}" />
                        </div>
                    </div>
                    
                    <div class="form-group form-label-left">
                        <label class="col-md-3 control-label">{{ trans('core::view.State') }}</label>
                        <div class="input-box col-md-9">
                            <select class="select-search form-control" name="item[state]">
                                @foreach ($actionOptions as $option)
                                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('menus.state')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                            
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
<script>
    jQuery(document).ready(function ($) {
        var messages = {
            'item[name]': {
                required: '<?php echo trans('core::view.Please enter') . ' ' . trans('core::view.menu name') ; ?>',
                rangelength: '<?php echo trans('team::view.Menu name') . ' ' . trans('core::view.not be greater than :number characters', ['number' => 255]) ; ?>'
              }
        }
        var rules = {
            'item[name]': {
                required: true,
                rangelength: [1, 255]
            }
        };
        $('#form-edit-menus').validate({
            rules: rules,
            messages: messages
        });
    });
</script>
@endsection
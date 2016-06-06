@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
?>

@section('title')
{{ trans('team::view.Edit employee: :employeeName', ['employeeName' => Form::getData('name')]) }}
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="box box-info">
            <div class="box-header with-border">
                <h2 class="box-title">{{ trans('team::view.Personal Information') }}</h2>
            </div>
            <div class="box-body">
                <div class="form-horizontal form-label-left">
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label">{{ trans('team::view.') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" class="form-control" id="" placeholder="{{ trans('team::view.') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end edit memeber left col -->
    
    <div class="col-md-7">
        
    </div> <!-- end edit memeber right col -->
</div>
@endsection

@section('script')
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script>
    jQuery(document).ready(function ($) {
        var messages = {
            'item[name]': '<?php echo trans('core::view.Please enter') . ' ' . trans('team::view.role name') ; ?>'
        }
        $('#form-role-edit').validate({
            messages: messages
        });
    });
</script>
@endsection
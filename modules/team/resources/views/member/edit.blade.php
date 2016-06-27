@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Team\View\TeamList;
use Rikkei\Team\Model\Roles;

$postionsOption = Roles::toOptionPosition();
$teamsOption = TeamList::toOption(null, true, false);

?>

@section('title')
@if (Form::getData('employee.id'))
    {{ trans('team::view.Profile of :employeeName', ['employeeName' => Form::getData('employee.name')]) }}
@else
    {{ trans('team::view.Profile') }}
@endif
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/select2/select2.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('adminlte/plugins/datepicker/datepicker3.css') }}">    
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row member-profile">
    <form action="{{ route('team::team.member.save') }}" method="post" id="form-employee-info">
        {!! csrf_field() !!}
        @if (Form::getData('employee.id'))
            <input type="hidden" name="id" value="{{ Form::getData('employee.id') }}" />
        @endif
        <div class=" col-md-12 box-action">
            @if (Form::getData('employee.id'))
                <input type="submit" class="btn-edit" name="submit" value="{{ trans('team::view.Update information') }}" />
             @else
                <input type="submit" class="btn-add" name="submit" value="{{ trans('team::view.Register new member') }}" />
            @endif
        </div>
        <div class="col-md-5">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Personal Information') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.base')
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">Team</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.team')
                </div>
            </div>
            
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Role Special') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.role')
                </div>
            </div>
            
        </div> <!-- end edit memeber left col -->

        <div class="col-md-7">

        </div> <!-- end edit memeber right col -->
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
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{ URL::asset('adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('team/js/script.js') }}"></script>
<script>
    jQuery(document).ready(function($) {
        selectSearchReload();
        $(document).on('click', '.input-team-position.input-add-new button', function(event) {
            selectSearchReload();
        });
        var messages = {
            'employee[name]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
              },
            'employee[mobile_phone]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'employee[personal_email]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
                email: '<?php echo trans('core::view.Please enter a valid email address'); ?>'
            },
            'employee[join_date]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'employee[email]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
                email: '<?php echo trans('core::view.Please enter a valid email address'); ?>'
            },
            'employee[id_card_number]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'employee[employee_card_id]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                'number': '{{ trans('core::view.Please enter a valid number') }}',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 10]) ; ?>',
            },
            'employee[birthday]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
        }
        var rules = {
            'employee[name]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[mobile_phone]': {
                required: true,
                rangelength: [1, 20]
            },
            'employee[personal_email]': {
                required: true,
                email: true,
                rangelength: [1, 100]
            },
            'employee[join_date]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[birthday]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[email]': {
                required: true,
                email: true,
                rangelength: [1, 100]
            },
            'employee[id_card_number]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[employee_card_id]': {
                required: true,
                number: true,
                rangelength: [1, 10]
            }
        };
        
        $('#form-employee-info').validate({
            rules: rules,
            messages: messages,
            lang: 'vi'
        });
        
        //Date picker
        optionDatePicker = {
            autoclose: true,
            format: 'yyyy-mm-dd',
        }
        $('#employee-birthday').datepicker(optionDatePicker);
        $('#employee-joindate').datepicker(optionDatePicker);
        
        @if (! isset($recruitmentPresent) || ! $recruitmentPresent)
            $('#employee-phone').on('blur', function(event) {
                $('#employee-presenter').parents('.form-group').find('label i').removeClass('hidden');
                value = $(this).val();
                if (value) {
                    $.ajax({
                        url: '{{ URL::route('recruitment::get.applies.presenter') }}',
                        type: 'get',
                        data: {phone: value},
                        success: function(data) {
                            if (data) {
                                $('#employee-presenter').val(data);
                            }
                            $('#employee-presenter').parents('.form-group').find('label i').addClass('hidden');
                        }
                    });
                }
            });
        @endif
    });
</script>
@endsection
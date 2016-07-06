@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Team\View\TeamList;
use Rikkei\Team\Model\Roles;
use Rikkei\Team\Model\School;
use Rikkei\Core\View\View;

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
<link rel="stylesheet" href="{{ URL::asset('lib/css/jquery-ui.min.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('team/css/style.css') }}" />
@endsection

@section('content')
<div class="row member-profile">
    <form action="{{ route('team::team.member.save') }}" method="post" id="form-employee-info" enctype="multipart/form-data">
        {!! csrf_field() !!}
        @if (Form::getData('employee.id'))
            <input type="hidden" name="id" value="{{ Form::getData('employee.id') }}" />
        @endif
        @if (isset($isProfile) && $isProfile)
            <input type="hidden" name="is_profile" value="1" />
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
        
        <script>
            /**
             * employee skill data format json object
             */
            var employeeSkill = {
                schools: {}
            };
        </script>
        
        <div class="col-md-7">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Qualifications and Skills') }}</h2>
                </div>
                <div class="box-body">
                    <input type="hidden" name="employee_skill" value="" />
                    @include('team::member.edit.qualifications')
                </div>
            </div>
        </div> <!-- end edit memeber right col -->
    </form>
</div>

@include('team::member.edit.skill_modal')

<?php
//remove flash session
Form::forget();
?>
@endsection

@section('script')
<script src="{{ URL::asset('lib/js/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('adminlte/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('lib/js/moment.min.js') }}"></script>
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
            'employee[join_date]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'employee[email]': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
                email: '<?php echo trans('core::view.Please enter a valid email address'); ?>'
            },
            'employee[personal_email]': {
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
            'name': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'country': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'province': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'majors': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'start_at': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            }
        }
        var rules = {
            'employee[name]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[join_date]': {
                required: true,
                rangelength: [1, 255]
            },
            'employee[email]': {
                required: true,
                email: true,
                rangelength: [1, 100]
            },
            'employee[personal_email]': {
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
            },
            'name': {
                required: true,
                rangelength: [1, 255]
            },
            'country': {
                required: true,
                rangelength: [1, 255]
            },
            'province': {
                required: true,
                rangelength: [1, 255]
            },
            'majors': {
                required: true,
                rangelength: [1, 255]
            },
            'start_at': {
                required: true,
                rangelength: [1, 255]
            }
        };
        
        $('#form-employee-info').validate({
            rules: rules,
            messages: messages,
            lang: 'vi'
        });
        $('#employee-skill-school-form').validate({
            rules: rules,
            messages: messages
        });
        
        //Date picker
        optionDatePicker = {
            autoclose: true,
            format: 'yyyy-mm-dd',
        }
        $('#employee-birthday').datepicker(optionDatePicker);
        $('#employee-joindate').datepicker(optionDatePicker);
        
        $('#college-start').datepicker(optionDatePicker);
        $('#college-end').datepicker(optionDatePicker);
        
        
        @if (! isset($recruitmentPresent) || ! $recruitmentPresent)
            $('#employee-phone').on('blur', function(event) {
                value = $(this).val();
                if (value) {
                    $('#employee-presenter').parents('.form-group').find('label i').removeClass('hidden');
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
        
        /*
        * modal employee skill process
         */
        var autoComplete = {},
            imagePreviewImageDefault,
            employeeSkillNo = {};
        autoComplete.school = getArrayFormat({!! School::getAllFormatJson() !!});
        imagePreviewImageDefault = '{{ View::getLinkImage() }}';
        @if (isset($employeeSchools) && $employeeSchools)
            employeeSkillNo.schools = {{ count($employeeSchools) }};
        @else
            employeeSkillNo.schools = 0;
        @endif
        employeeSkillNo.schools++;
        
        //preview image
        <?php
        $typeAllow = implode('","', Config::get('services.file.image_allow'));
        $typeAllow = '"' . $typeAllow . '"';
        ?>
        $('.input-box-img-preview').previewImage({
            type: [{!! $typeAllow !!}],
            size: {{ Config::get('services.file.image_max') }},
            default_image: imagePreviewImageDefault,
            message_size: '{{ trans('core::message.File size is large') }}'
        });
        
        $().employeeSkillAction({
            'autoComplete' : autoComplete,
            'imagePreviewImageDefault': imagePreviewImageDefault,
            'employeeSkillNo': employeeSkillNo,
            'employeeSkill': employeeSkill
        });
        /* -----end modal employee skill process */
        
    });
</script>
@endsection
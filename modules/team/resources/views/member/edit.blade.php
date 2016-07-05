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
        function getDateFormat(date, format) {
            if (format == 'Y') {
                return date.getFullYear();
            }
            return '';
        }
        
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
        
        $('.employee-college-modal').on('shown.bs.modal', function (e) {
            $('.college-image-box .college-image').verticalCenter({
                parent: '.college-image-box .image-preview'
            });
        });
        
        $('.college-image-box').previewImage();
        
        /*
        * modal employee skill process
         */
        var autoComplete = {},
            groupChange = {},
            dataGroup,
            dataHrefModal,
            dataItemId,
            imagePreviewImageDefault,
            employeeSkillNo = {},
            tokenValue;
        autoComplete.school = {!! School::getAllFormatJson() !!};
        tokenValue = $('input[name=_token]').val();
        imagePreviewImageDefault = '{{ View::getLinkImage() }}';
        @if (isset($employeeSchools) && $employeeSchools)
            employeeSkillNo.schools = {{ count($employeeSchools) }};
        @else
            employeeSkillNo.schools = 0;
        @endif
        employeeSkillNo.schools++;
        console.log(autoComplete);
        console.log(employeeSkill);
        console.log(employeeSkillNo.schools);
        //click button to show modal
        $(document).on('click', '.employee-skill-box-wrapper [data-modal=true]', function(event) {
            dataItemId = $(this).parents('.esbw-item').data('id');
            if (!dataItemId || dataItemId == undefined) {
                dataItemId = 0;
            }
            dataGroup = $(this).parents('.employee-skill-box-wrapper').data('group');
            dataHrefModal = $(this).parents('.employee-skill-box-wrapper').data('href');
            dataIsChange = $(this).parents('.employee-skill-box-wrapper').data('change');
            $(dataHrefModal).modal('show');
            
            //process data when show modal
            $(dataHrefModal).on('shown.bs.modal', function (e) {
                $(this).find('input').removeAttr('disabled').val('');
                $(this).find('img.college-image-preview').attr('src', imagePreviewImageDefault);
                $(this).find('input').each(function (i,k) {
                    inputType = $(this).attr('type');
                    dataCol = $(this).data('col');
                    dataTbl = $(this).data('tbl');
                    if (! dataCol || ! dataTbl) {
                    } else {
                        valueId = employeeSkill[dataGroup][dataItemId][dataTbl].id;
                        value = employeeSkill[dataGroup][dataItemId][dataTbl][dataCol];
                        if (inputType == 'file') {
                            if ($(this).parents('.input-box-img-preview').length) {
                                $(this).parents('.input-box-img-preview')
                                    .find('img[data-tbl=' + dataTbl +'][data-col=' + dataCol +'_preview]')
                                    .attr('src', value);
                            }
                            if (valueId) {
                                $(this).attr('disabled', true).val('');
                            }
                        } else {
                            if (value && value != undefined) {
                                value = $.parseHTML(value)[0].nodeValue;
                            } else {
                                value = '';
                            }
                            if (
                                valueId && 
                                ! $(this).data('autocomplete') && 
                                $(this).attr('type') != 'hidden'
                            ) {
                                $(this).attr('disabled', true).val(value);
                            } else {
                                $(this).val(value);
                            }
                        }
                    }
                });
                $(this).attr('data-id', dataItemId);
            });
        });
        
        //process data when close modal
//        $('.employee-skill-modal').on('hide.bs.modal', function(e) {
//            $(this).find('input').removeAttr('disabled').val('');
//            $(this).find('img.college-image-preview').attr('src', imagePreviewImageDefault);
//        });
        
        //autocomplete field in modal
        $('.employee-skill-modal input[data-autocomplete=true]').each(function() {
            var dataTblAuto = $(this).data('tbl');
            $(this).autocomplete({
                minLength: 0,
                source: autoComplete[dataTblAuto],
                select: function( event, ui ) {
                    thisParent = $(this).parents('.employee-college-modal');
                    var uiItemSelected = ui.item;
                    thisParent.find('input[data-tbl=' + dataTblAuto + ']:not([data-autocomplete=true])').each(function (){
                        inputType = $(this).attr('type');
                        dataCol = $(this).data('col');
                        if (! dataCol) {
                        } else {
                            value = uiItemSelected[dataCol];
                            if (inputType == 'file') {
                                if ($(this).parents('.input-box-img-preview').length) {
                                    $(this).parents('.input-box-img-preview')
                                        .find('img[data-col=' + dataCol +'_preview]')
                                        .attr('src', value);
                                }
                                $(this).attr('disabled', true).val('');
                            } else {
                                if (value && value != undefined) {
                                    value = $.parseHTML(value)[0].nodeValue;
                                    $(this).attr('disabled', true).val(value);
                                } else {
                                    $(this).attr('disabled', true).val('');
                                }
                            }
                        }
                    });
                }
            }).focus(function(){
                $(this).autocomplete("search");
            });
        });
        
        //process data when key press autocomplete field
        $('.employee-skill-modal input[data-autocomplete=true]').on('keyup', function(e) {
            if (e.keyCode == 13) {
            } else {
                thisParent = $(this).parents('.employee-skill-modal');
                var dataTblAuto = $(this).data('tbl');
                thisParent.find('input[data-tbl=' + dataTblAuto + ']:not([data-autocomplete=true])').each(function (){
                    inputType = $(this).attr('type');
                    if (inputType == 'file') {
                        $(this).removeAttr('disabled');
                        return true;
                        if ($(this).parents('.input-box-img-preview').length) {
                            dataCol = $(this).data('col');
                            $(this).parents('.input-box-img-preview')
                                .find('img[data-col=' + dataCol +'_preview]')
                                .attr('src', imagePreviewImageDefault);
                        }
                        $(this).removeAttr('disabled').val('');
                    } else {
                        if ($(this).data('col') == 'id') {
                            $(this).removeAttr('disabled').val('');
                            return true;
                        }
                        $(this).removeAttr('disabled');
                        return true;
                        $(this).removeAttr('disabled').val('');
                    }
                });
            }
        });
        
        /**
        * update employee skill data
         */
        function updateEmployeeData(thisDomModal, imageReturnAjax) {
            if (! imageReturnAjax || imageReturnAjax == undefined) {
                imageReturnAjax = null;
            }
            
            var id = thisDomModal.attr('data-id');
            id = parseInt(id);
            var group = thisDomModal.data('group');
            if (! id) {
                id = employeeSkillNo[group];
                employeeSkillNo[group]++;
            }
            thisDomModal.find('input').each(function (i,k) {
                inputType = $(this).attr('type');
                dataCol = $(this).data('col');
                dataTbl = $(this).data('tbl');
                if (! dataCol || ! dataTbl) {
                } else {
                    valueInput = $(this).val();
                    if (inputType == 'file') {
                        if ($(this).parents('.input-box-img-preview').length) {
                            if (imageReturnAjax && imageReturnAjax.image && imageReturnAjax.image_path) {
                                valueInput = imageReturnAjax.image;
                                employeeSkill[group][id][dataTbl].image_path = imageReturnAjax.image_path;
                            } else {
                                valueInput = $(this).parents('.input-box-img-preview')
                                    .find('img[data-tbl=' + dataTbl +'][data-col=' + dataCol +'_preview]')
                                    .attr('src');
                                employeeSkill[group][id][dataTbl].image_path = '';
                            }
                        }
                    }
                    if (employeeSkill[group][id] == undefined) {
                        employeeSkill[group][id] = {};
                    }
                    if (employeeSkill[group][id][dataTbl] == undefined) {
                        employeeSkill[group][id][dataTbl] = {};
                    }
                    if (employeeSkill[group][id][dataTbl][dataCol] == undefined) {
                        employeeSkill[group][id][dataTbl][dataCol] = {};
                    }
                    employeeSkill[group][id][dataTbl][dataCol] = valueInput;
                }
            });
        }
        
        /**
        * upload skill html list
         */
        function updateHtmlSkillList(group)
        {
            skillListWrapper = $('.employee-skill-box-wrapper[data-group=' + group + '] .employee-skill-items');
            skillListItem0 = skillListWrapper.find('.esbw-item[data-id=0]');
            var htmlNew = '';
            $.each(employeeSkill[group], function(i, k){
                if (i == 0) {
                    return true;
                }
                skillItemNew = skillListItem0.clone();
                skillItemNew.attr('data-id', i);
                skillItemNew.removeClass('hidden');
                skillItemNew.find('[data-tbl][data-col]').each(function(){
                    dataTbl = $(this).data('tbl');
                    dataCol = $(this).data('col');
                    if ($(this).is('img')) {                        
                        skillItemNew.find('[data-tbl=' + dataTbl + '][data-col=' + dataCol + ']').attr('src', k[dataTbl][dataCol]);
                    } else {
                        valueInput = k[dataTbl][dataCol];
                        domInput = skillItemNew.find('[data-tbl=' + dataTbl + '][data-col=' + dataCol + ']');
                        dataDateFormat = domInput.data('date-format');
                        if (dataDateFormat) {
                            date = new Date(valueInput);
                            valueInput = getDateFormat(date, dataDateFormat);
                        }
                        domInput.html(valueInput);
                    }
                });
                htmlNew += skillItemNew[0].outerHTML;
            });
            skillListWrapper.children(':not(.esbw-item)').html(htmlNew);
        }
        
        //process data when modal action change
        $('.employee-skill-modal .btn-action').on('click', function(event) {
            var thisDomModal = $(this).parents('.employee-skill-modal');
            var dataGroupType = thisDomModal.data('group');
            
            //upload if available
            if (thisDomModal.find('input[type=file]').val()) {
                formDom = thisDomModal.find('form.skill-modal-form');
                formData = new FormData();
                formData.append('_token', tokenValue);
                formData.append('skill_type', dataGroupType);
                formData.append('file', formDom.find('input[type=file]')[0].files[0]);
                $.ajax({
                    url : formDom.attr('action'),
                    type : 'POST',
                    data : formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success : function(data) {
                        updateEmployeeData(thisDomModal, data);
                        updateHtmlSkillList(dataGroupType);
                    }
                });
            } else {
                updateEmployeeData(thisDomModal);
                updateHtmlSkillList(dataGroupType);
            }
        });
        
        // remove pop up in modal when scroll
        $('.employee-skill-modal').scroll(function(){
            $('input').blur();
        });
        
        //disable submti fomr modal
        $('.employee-skill-modal form').on('submit', function(event) {
            event.preventDefault();
        });
        
        /* -----end modal employee skill process */
        
    });
</script>
@endsection
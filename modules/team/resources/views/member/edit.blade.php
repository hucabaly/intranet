@extends('layouts.default')
<?php
use Rikkei\Core\View\Form;
use Rikkei\Team\View\TeamList;
use Rikkei\Team\Model\Roles;
use Rikkei\Team\Model\School;
use Rikkei\Core\View\View;
use Rikkei\Team\Model\Certificate;
use Rikkei\Team\Model\Skill;
use Rikkei\Team\Model\WorkExperience;
use Rikkei\Team\View\Permission;
use Rikkei\Team\View\General;

$postionsOption = Roles::toOptionPosition();
$teamsOption = TeamList::toOption(null, true, false);

$employeeSchools = $employeeLanguages = $employeeCetificates = null;
$employeePrograms = $employeeDatabases = $employeeOss = null;
$employeeWorkExperiences = $employeeProjectExperiences = null;

// skill data for employee
if (isset($employeeModelItem) && $employeeModelItem) {
    $employeeSchools = $employeeModelItem->getSchools();
    $employeeLanguages = $employeeModelItem->getLanguages();
    $employeeCetificates = $employeeModelItem->getCetificates();
    $employeePrograms = $employeeModelItem->getPrograms();
    $employeeDatabases = $employeeModelItem->getDatabases();
    $employeeOss = $employeeModelItem->getOss();
    $employeeWorkExperiences = $employeeModelItem->getWorkExperience();
    $employeeProjectExperiences = $employeeModelItem->getProjectExperience();
}

//skill data for flash create employee
$employeeSkillchangeFlashData = $employeeSkillFlashData = null;
$employeeSkillModelFlash = $employeeSkillGroupChange = null;
if (! Form::getData('employee.id') && Form::getData('employee_skill.data')) {
    $employeeSkillchangeFlashData = Form::getData('employee_skill_change.data');
    $employeeSkillFlashData = Form::getData('employee_skill.data');
    $employeeSkillModelFlash = General::getEmployeeSkllObject($employeeSkillFlashData, $employeeSkillchangeFlashData);
    if (isset($employeeSkillModelFlash['schools']) && $employeeSkillModelFlash['schools']) {
        $employeeSchools = $employeeSkillModelFlash['schools'];
    }
    if (isset($employeeSkillModelFlash['languages']) && $employeeSkillModelFlash['languages']) {
        $employeeLanguages = $employeeSkillModelFlash['languages'];
    }
    if (isset($employeeSkillModelFlash['cetificates']) && $employeeSkillModelFlash['cetificates']) {
        $employeeCetificates = $employeeSkillModelFlash['cetificates'];
    }
    if (isset($employeeSkillModelFlash['programs']) && $employeeSkillModelFlash['programs']) {
        $employeePrograms = $employeeSkillModelFlash['programs'];
    }
    if (isset($employeeSkillModelFlash['oss']) && $employeeSkillModelFlash['oss']) {
        $employeeOss = $employeeSkillModelFlash['oss'];
    }
    if (isset($employeeSkillModelFlash['databases']) && $employeeSkillModelFlash['databases']) {
        $employeeDatabases = $employeeSkillModelFlash['databases'];
    }
    if (isset($employeeSkillModelFlash['work_experiences']) && $employeeSkillModelFlash['work_experiences']) {
        $employeeWorkExperiences = $employeeSkillModelFlash['work_experiences'];
    }
    if (isset($employeeSkillModelFlash['project_experiences']) && $employeeSkillModelFlash['project_experiences']) {
        $employeeProjectExperiences = $employeeSkillModelFlash['project_experiences'];
    }
    parse_str($employeeSkillchangeFlashData, $employeeSkillGroupChange);
}
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
                    <input type="hidden" name="employee_team_change" 
                        value="<?php if (! Form::getData('employee.id') && Form::getData('employee_team')): ?>1<?php endif; ?>" />
                    @include('team::member.edit.team')
                </div>
            </div>
            
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Role Special') }}</h2>
                </div>
                <div class="box-body">
                    <input type="hidden" name="employee_role_change" 
                        value="<?php if (! Form::getData('employee.id') && Form::getData('employee_role')): ?>1<?php endif; ?>" />
                    @include('team::member.edit.role')
                </div>
            </div>
            
        </div> <!-- end edit memeber left col -->
        
        <div class="col-md-7">
            <script>
                /**
                 * employee skill data format json object
                 */
                var employeeSkill = {
                    schools: {},
                    languages: {},
                    cetificates: {},
                    programs: {},
                    databases: {},
                    oss: {},
                    work_experiences: {},
                    project_experiences: {},
                };
            </script>
            <input type="hidden" name="employee_skill" value="{!! $employeeSkillFlashData !!}" />
            <input type="hidden" name="employee_skill_change" value="{!! $employeeSkillchangeFlashData !!}" />
            <!-- box skills -->
            <div class="box box-info qualifications-skill-box">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Qualifications and Skills') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.qualifications')
                </div>
            </div> <!-- end box skills -->
            
            <!-- box work experience -->
            <div class="box box-info work-experience-box">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Work experience') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.work_experience')
                </div>
            </div> <!-- end box work experience -->
            
            <!-- box project experience -->
            <div class="box box-info project-experience-box">
                <div class="box-header with-border">
                    <h2 class="box-title">{{ trans('team::view.Project experience') }}</h2>
                </div>
                <div class="box-body">
                    @include('team::member.edit.project_experience')
                </div>
            </div> <!-- end box project experience -->
            
        </div> <!-- end edit memeber right col -->
    </form>
</div>
@if (Permission::getInstance()->isAllow('team::team.member.edit.skill'))
    @include('team::member.edit.skill_modal')
@endif

@if (Permission::getInstance()->isAllow('team::team.member.edit.exerience'))
    @include('team::member.edit.work_experience_modal')
    @include('team::member.edit.project_experience_modal')
@endif

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
            },
            'level': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
            },
            'experience': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                'number': '{{ trans('core::view.Please enter a valid number') }}',
            },
            'responsible': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
            },
            'position': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'company': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
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
            },
            'level': {
                required: true
            },
            'experience': {
                required: true,
                number: true
            },
            'responsible': {
                required: true,
            },
            'position': {
                required: true,
                rangelength: [1, 255]
            },
            'company': {
                required: true,
                rangelength: [1, 255]
            },
        };
        var rulesAddtion = {
            'end_at': {
                required: true,
            },
            'enviroment_language': {
                required: true,
                rangelength: [1, 255]
            },
            'enviroment_enviroment': {
                required: true,
                rangelength: [1, 255]
            },
            'enviroment_os': {
                required: true,
                rangelength: [1, 255]
            },
        };
        var messagesAddtion = {
            'end_at': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
            },
            'enviroment_language': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'enviroment_enviroment': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
            'enviroment_os': {
                required: '<?php echo trans('core::view.This field is required'); ?>',
                rangelength: '<?php echo trans('core::view.This field not be greater than :number characters', ['number' => 255]) ; ?>',
            },
        }
        
        $('#form-employee-info').validate({
            rules: rules,
            messages: messages,
            lang: 'vi'
        });
        $('#employee-skill-school-form').validate({
            rules: rules,
            messages: messages
        });
        $('#employee-skill-language-form').validate({
            rules: rules,
            messages: messages
        });
        
        $('#employee-skill-program-form').validate({
            rules: rules,
            messages: messages
        });
        $('#employee-skill-database-form').validate({
            rules: rules,
            messages: messages
        });
        $('#employee-skill-os-form').validate({
            rules: rules,
            messages: messages
        });
        $('#form-employee-work_experience').validate({
            rules: rules,
            messages: messages
        });
        $('#form-employee-project_experience').validate({
            rules: $.extend(rules, rulesAddtion),
            messages: $.extend(messages, messagesAddtion)
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
        
        $('.input-skill-modal.date-picker').datepicker(optionDatePicker);
        
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
            employeeSkillNo = {},
            labelFormat = {},
            urlLoadAutoComplete,
            groupChange = {};
        <?php /*autoComplete.school = getArrayFormat({!! School::getAllFormatJson() !!});
        autoComplete.language = getArrayFormat({!! Cetificate::getAllFormatJson(Cetificate::TYPE_LANGUAGE) !!});
        autoComplete.cetificate = getArrayFormat({!! Cetificate::getAllFormatJson(Cetificate::TYPE_CETIFICATE) !!});
        autoComplete.program = getArrayFormat({!! Skill::getAllFormatJson(Skill::TYPE_PROGRAM) !!});
        autoComplete.database = getArrayFormat({!! Skill::getAllFormatJson(Skill::TYPE_DATABASE) !!});
        autoComplete.os = getArrayFormat({!! Skill::getAllFormatJson(Skill::TYPE_OS) !!});
        autoComplete.work_experience = getArrayFormat({!! WorkExperience::getAllFormatJson() !!});
        */ ?>
        urlLoadAutoComplete = '{{ URL::route('core::ajax.skills.autocomplete') }}';
        imagePreviewImageDefault = '{{ View::getLinkImage() }}';
        
        @if ($employeeSchools)
            employeeSkillNo.schools = {{ count($employeeSchools) }};
        @else
            employeeSkillNo.schools = 0;
        @endif
        employeeSkillNo.schools++;
        
        @if ($employeeLanguages)
            employeeSkillNo.languages = {{ count($employeeLanguages) }};
        @else
            employeeSkillNo.languages = 0;
        @endif
        employeeSkillNo.languages++;
        
        @if ($employeeCetificates)
            employeeSkillNo.cetificates = {{ count($employeeCetificates) }};
        @else
            employeeSkillNo.cetificates = 0;
        @endif
        employeeSkillNo.cetificates++;
        
        @if ($employeePrograms)
            employeeSkillNo.programs = {{ count($employeePrograms) }};
        @else
            employeeSkillNo.programs = 0;
        @endif
        employeeSkillNo.programs++;
        
        @if ($employeeDatabases)
            employeeSkillNo.databases = {{ count($employeeDatabases) }};
        @else
            employeeSkillNo.databases = 0;
        @endif
        employeeSkillNo.databases++;
        
        @if ($employeeOss)
            employeeSkillNo.oss = {{ count($employeeOss) }};
        @else
            employeeSkillNo.oss = 0;
        @endif
        employeeSkillNo.oss++;
        
        @if ($employeeWorkExperiences)
            employeeSkillNo.work_experiences = {{ count($employeeWorkExperiences) }};
        @else
            employeeSkillNo.work_experiences = 0;
        @endif
        employeeSkillNo.work_experiences++;
        
        @if ($employeeProjectExperiences)
            employeeSkillNo.project_experiences = {{ count($employeeProjectExperiences) }};
        @else
            employeeSkillNo.project_experiences = 0;
        @endif
        employeeSkillNo.project_experiences++;
        
        labelFormat.level_language = {!! View::getLanguageLevelFormatJson() !!};
        labelFormat.level_normal = {!! View::getNormalLevelFormatJson() !!};
        
        @if ($employeeSkillGroupChange && count($employeeSkillGroupChange))
            @foreach ($employeeSkillGroupChange as $itemKey => $itemValue)
                groupChange.{{ $itemKey }} = {{ $itemValue }};
            @endforeach
        @endif
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
            'employeeSkill': employeeSkill,
            'messageError': {
                'same_schools': '{!! trans('team::view.Canot choose the same school') !!}',
                'same_languages': '{!! trans('team::view.Canot choose the same language') !!}',
                'same_cetificates': '{!! trans('team::view.Canot choose the same certificate') !!}',
                'same_programs': '{!! trans('team::view.Canot choose the same programming language') !!}',
                'same_databases': '{!! trans('team::view.Canot choose the same database') !!}',
                'same_oss': '{!! trans('team::view.Canot choose the same os') !!}'
            },
            'labelFormat': labelFormat,
            'urlLoadAutoComplete': urlLoadAutoComplete,
            'groupChange': groupChange
        });
        /* -----end modal employee skill process */
        
    });
</script>
@endsection
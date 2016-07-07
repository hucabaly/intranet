<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View as ViewHelper;
use Rikkei\Team\View\Permission;
use Rikkei\Core\Model\CoreModel;
?>
<?php
/**
 * function html render employee project
*/
function getHtmlEmployeeProjectExperience($employeeExperience = null, $i = 0) 
{ 
    if (! $employeeExperience) {
        $employeeExperience = new CoreModel();
    }
?>
    <div class="col-sm-12 employee-experience-item esbw-item<?php if(! $i): ?> hidden<?php endif; ?>" data-id="{{ $i }}">
        <div class="esi-image">
            <img src="{{ ViewHelper::getLinkImage($employeeExperience->image) }}" 
                class="img-responsive image-preview" data-tbl="project_experience" data-col="image" />
        </div>
        <div class="esi-content">
            <p>
                <a href="#" class="esi-title" data-tbl="project_experience" data-col="name"
                   data-modal="true">{{ $employeeExperience->name }}</a>
            </p>
            <p>
                <span>{{ trans('team::view.Period') }}</span>
                <span data-tbl="project_experience" data-col="start_at" data-date-format="M/Y">{{ ViewHelper::formatDateTime('Y-m-d', 'm/Y', $employeeExperience->start_at) }}</span>
                ~
                <span data-tbl="project_experience" data-col="end_at" data-date-format="M/Y">{{ ViewHelper::formatDateTime('Y-m-d', 'm/Y', $employeeExperience->end_at) }}</span>
                &nbsp;&nbsp;&nbsp;
                <span>{{ trans('team::view.Language') }}:<span> <span data-tbl="project_experience" data-col="enviroment_language">{{ $employeeExperience->getEnvironment('language') }}</span>
                &nbsp;&nbsp;&nbsp;
                <span>{{ trans('team::view.Environment') }}:<span> <span data-tbl="project_experience" data-col="enviroment_enviroment">{{ $employeeExperience->getEnvironment('enviroment') }}</span>
                &nbsp;&nbsp;&nbsp;
                <span>{{ trans('team::view.OS') }}:<span> <span data-tbl="project_experience" data-col="enviroment_os">{{ $employeeExperience->getEnvironment('os') }}</span>
            </p>
            <p>
                <span>{{ trans('team::view.Responsible') }}:<span> <span data-tbl="project_experience" data-col="responsible">{{ $employeeExperience->responsible }}</span>
            </p>
        </div>
        <script>
            employeeSkill.project_experiences[{{ $i }}] = {
                project_experience: {
                    id: '{{ $employeeExperience->id }}',
                    name: '{{ $employeeExperience->name }}',
                    enviroment_language: '{{ $employeeExperience->getEnvironment('language') }}',
                    enviroment_enviroment: '{{ $employeeExperience->getEnvironment('enviroment') }}',
                    enviroment_os: '{{ $employeeExperience->getEnvironment('os') }}',
                    start_at: '{{ $employeeExperience->start_at }}',
                    end_at: '{{ $employeeExperience->end_at }}',
                    image: '{{ ViewHelper::getLinkImage($employeeExperience->image) }}',
                    responsible: '{{ $employeeExperience->responsible }}',
                }
            };
        </script>
    </div>
<?php } //end function project html
?>
    
<!-- employee project experience -->
<div class="row skill-list-row">
    <div class="col-sm-12">
        <div class="row employee-skill-box-wrapper" data-btn-modal="true" 
            data-group="project_experiences" data-href="#employee-project_experience-form">
            <div class="col-sm-12 employee-skill-items item-full-col">
                <div class="row">
                    @if ($employeeProjectExperiences)
                        <?php $i = 0; ?>
                        @foreach ($employeeProjectExperiences as $employeeProjectExperience)
                            <?php $i++; ?>
                            <?php getHtmlEmployeeProjectExperience($employeeProjectExperience, $i); ?>
                        @endforeach
                    @endif
                </div>
                <?php getHtmlEmployeeProjectExperience(null, 0); ?>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn-add add-college" data-toggle="tooltip" 
                    data-placement="bottom" title="{{ trans('team::view.Add new project') }}"
                    data-modal="true">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- //end employee project experience -->

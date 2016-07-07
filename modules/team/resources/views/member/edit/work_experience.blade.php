<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View as ViewHelper;
use Rikkei\Team\View\Permission;
use Rikkei\Core\Model\CoreModel;
?>
<?php
/**
 * function html render employee work experience
*/
function getHtmlEmployeeWordExperience($employeeExperience = null, $i = 0) 
{ 
    if (! $employeeExperience) {
        $employeeExperience = new CoreModel();
    }
?>
    <div class="col-sm-6 employee-experience-item esbw-item<?php if(! $i): ?> hidden<?php endif; ?>" data-id="{{ $i }}">
        <div class="esi-image">
            <img src="{{ ViewHelper::getLinkImage($employeeExperience->image) }}" 
                class="img-responsive image-preview" data-tbl="work_experience" data-col="image" />
        </div>
        <div class="esi-content">
            <p>
                <a href="#" class="esi-title" data-tbl="work_experience" data-col="company"
                   data-modal="true">{{ $employeeExperience->company }}</a>
            </p>
            <p>
                <span data-tbl="work_experience" data-col="start_at" data-date-format="M/Y">{{ ViewHelper::formatDateTime('Y-m-d', 'm/Y', $employeeExperience->start_at) }}</span>
                ~
                <span data-tbl="work_experience" data-col="end_at" data-date-format="M/Y">{{ ViewHelper::formatDateTime('Y-m-d', 'm/Y', $employeeExperience->end_at) }}</span>
            </p>
            <p>
                <span>{{ trans('team::view.Positon') }}:<span> <span data-tbl="work_experience" data-col="position">{{ $employeeExperience->position }}</span>
            </p>
        </div>
        <script>
            employeeSkill.work_experiences[{{ $i }}] = {
                work_experience: {
                    id: '{{ $employeeExperience->id }}',
                    company: '{{ $employeeExperience->company }}',
                    position: '{{ $employeeExperience->position }}',
                    start_at: '{{ $employeeExperience->start_at }}',
                    end_at: '{{ $employeeExperience->end_at }}',
                    image: '{{ ViewHelper::getLinkImage($employeeExperience->image) }}',
                }
            };
        </script>
    </div>
<?php } //end function work experience html
?>
    
<!-- employee work experience -->
<div class="row skill-list-row">
    <div class="col-sm-12">
        <div class="row employee-skill-box-wrapper" data-btn-modal="true" 
            data-group="work_experiences" data-href="#employee-work_experience-form">
            <div class="col-sm-10 employee-skill-items">
                <div class="row">
                    @if ($employeeWorkExperiences)
                        <?php $i = 0; ?>
                        @foreach ($employeeWorkExperiences as $employeeWorkExperience)
                            <?php $i++; ?>
                            <?php getHtmlEmployeeWordExperience($employeeWorkExperience, $i); ?>
                        @endforeach
                    @endif
                </div>
                <?php getHtmlEmployeeWordExperience(null, 0); ?>
            </div>
            <div class="col-sm-2 add-skill-item">
                <button type="button" class="btn-add add-college" data-toggle="tooltip" 
                    data-placement="bottom" title="{{ trans('team::view.Add new company') }}"
                    data-modal="true">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- //end employee experience -->

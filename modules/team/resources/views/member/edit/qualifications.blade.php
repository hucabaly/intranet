<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View as ViewHelper;
use Rikkei\Team\View\Permission;

$genderOption = Employees::toOptionGender();
if (isset($isProfile) && $isProfile) {
    $employeePermission = Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit');
} else {
    $employeePermission = Permission::getInstance()->isScopeCompany();
}
?>
<?php
/**
 * function html render
*/
function getHtmlEmployeeSchool($employeeSchool = null, $i = 0) 
{ 
    if (! $employeeSchool) {
        $employeeSchool = new Rikkei\Core\Model\CoreModel();
    }
?>
    <div class="col-sm-6 employee-school-item esbw-item<?php if(! $i): ?> hidden<?php endif; ?>" data-id="{{ $i }}">
        <div class="esi-image">
            <img src="{{ ViewHelper::getLinkImage($employeeSchool->image) }}" 
                class="img-responsive image-preview" data-tbl="school" data-col="image" />
        </div>
        <div class="esi-content">
            <p>
                <a href="#" class="esi-title" data-tbl="school" data-col="name"
                   data-modal="true">{{ $employeeSchool->name }}</a>
            </p>
            <p>
                <span>{{ trans('team::view.Class of') }} <span data-tbl="employee_school" data-col="start_at" data-date-format="Y">{{ ViewHelper::formatDateTime('Y-m-d H:i:s', 'Y', $employeeSchool->start_at) }}</span>
                &nbsp;&nbsp;&nbsp;
                <span data-tbl="employee_school" data-col="majors">{{ $employeeSchool->majors }}</span>
                &nbsp;&nbsp;&nbsp;
                <span data-tbl="school" data-col="province">{{ $employeeSchool->province }}</span>, <span data-tbl="school" data-col="country">{{ $employeeSchool->country }}</span>
            </p>
        </div>
    </div>
    <script>
        employeeSkill.schools[{{ $i }}] = {
            school: {
                id: '{{ $employeeSchool->id }}',
                name: '{{ $employeeSchool->name }}',
                country: '{{ $employeeSchool->country }}',
                province: '{{ $employeeSchool->province }}',
                image: '{{ ViewHelper::getLinkImage($employeeSchool->image) }}',
            },
            employee_school: {
                majors: '{{ $employeeSchool->majors }}',
                start_at: '{{ $employeeSchool->start_at }}',
                end_at: '{{ $employeeSchool->end_at }}',
            }
        };
    </script>
<?php } //end function school html
?>
<div class="row">
    <div class="col-sm-12">
        <h5 class="skill-title">{{ trans('team::view.Content has studied') }}</h5>
    </div>
    <div class="col-sm-12">
        <div class="row employee-skill-box-wrapper" data-btn-modal="true" 
            data-group="schools" data-href="#employee-school-form" data-change="schools">
            <div class="col-sm-10 employee-skill-items">
                <div class="row">
                    @if (isset($employeeSchools) && $employeeSchools)
                        <?php $i = 0; ?>
                        @foreach ($employeeSchools as $employeeSchool)
                            <?php $i++; ?>
                            <?php getHtmlEmployeeSchool($employeeSchool, $i); ?>
                        @endforeach
                    @endif
                </div>
                <?php getHtmlEmployeeSchool(null, 0); ?>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn-add add-college" data-toggle="tooltip" 
                    data-placement="bottom" title="{{ trans('team::view.Add a college') }}"
                    data-modal="true">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>


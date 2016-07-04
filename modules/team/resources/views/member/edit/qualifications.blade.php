<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View;
use Rikkei\Team\View\Permission;

$genderOption = Employees::toOptionGender();
if (isset($isProfile) && $isProfile) {
    $employeePermission = Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit');
} else {
    $employeePermission = Permission::getInstance()->isScopeCompany();
}
?>

<div class="row">
    <div class="col-sm-12">
        <h5 class="skill-title">{{ trans('team::view.Content has studied') }}</h5>
    </div>
    <div class="col-sm-12">
        <div class="row">
            @if (isset($employeeSchools) && $employeeSchools)
                @foreach ($employeeSchools as $employeeSchool)
                    <div class="col-sm-5 employee-school-item">
                        <div class="esi-image">
                            @if ($employeeSchool->image)
                            <img src="{{ URL::asset($employeeSchool->image) }}" class="img-responsive" />
                            @else
                                <img src="{{ URL::asset('common/images/noimage.png') }}" class="img-responsive" />
                            @endif
                        </div>
                        <div class="esi-content">
                            <p>
                                <a href="#" class="esi-title">{{ $employeeSchool->name }}</a>
                            </p>
                            <p>
                                @if ($employeeSchool->start_at)
                                    <span>{{ trans('team::view.Class of') }} {{ View::formatDateTime('Y-m-d H:i:s', 'Y', $employeeSchool->start_at) }}</span>
                                    &nbsp;&nbsp;&nbsp;
                                @endif
                                @if ($employeeSchool->majors)
                                    <span>{{ $employeeSchool->majors }}</span>
                                    &nbsp;&nbsp;&nbsp;
                                @endif
                                @if ($employeeSchool->province || $employeeSchool->country)
                                    <span>{{ $employeeSchool->province }}, {{ $employeeSchool->country }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="col-sm-2">
                <button type="button" class="btn-add add-college" data-toggle="modal" 
                    data-placement="bottom" title="{{ trans('team::view.Add a college') }}"
                    data-target="#employee-college-form" data-tooltip='true'>
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@include('team::member.edit.skill_modal')

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

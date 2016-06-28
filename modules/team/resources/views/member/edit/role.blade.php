<?php

use Rikkei\Core\View\Form;

$rolesData = Rikkei\Team\Model\Roles::getAllRole();
$employeeRoleIds = [];
if (! Form::getData('employee.id') && Form::getData('employee_role')) {
    $employeeRoles = Form::getData('employee_role');
}
?>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <div class="col-md-12">
            <ul class="employee-roles">
                @if (isset($employeeRoles) && count($employeeRoles))
                    @foreach ($employeeRoles as $employeeRole)
                        <li>
                            <span>
                                @if (is_array($employeeRoles))
                                    {{ $employeeRole['role'] }}
                                @else
                                    {{ $employeeRole->role }}
                                @endif
                            </span>
                        </li>
                        <?php 
                            if (is_array($employeeRoles)) {
                                $employeeRoleIds[] = $employeeRole['role_id'];
                            } else {
                                $employeeRoleIds[] = $employeeRole->role_id;
                            }
                         ?>
                    @endforeach
                @endif
            </ul>
            <p>
                <button type="button" class="btn-add" data-target="#employee-role-form" data-toggle="modal">
                    <span>{{ trans('team::view.Change') }}</span>
                </button>
            </p>
        </div>
    </div>
</div>

<div class="modal fade" id="employee-role-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-role-employee" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('team::view.Change Role of employee') }}</h4>
            </div>
            <div class="modal-body">
                @if (isset($rolesData) && count($rolesData))
                    @foreach ($rolesData as $roleItem)
                        <div class="checkbox">
                            <label>
                                <input name="role[]" type="checkbox" value="{{ $roleItem->id }}"<?php
                                    if (in_array($roleItem->id, $employeeRoleIds)): ?> checked<?php endif; ?>>{{ $roleItem->role }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
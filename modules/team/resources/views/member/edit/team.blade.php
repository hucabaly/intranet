<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\View\Permission;


$employeePermissionTeam = Permission::getInstance()->isScopeCompany(null, 'team::team.member.edit.team.position');
if ($employeePermissionTeam || $employeeGreaterLeader) {
    $employeePermission = true;
} else {
    $employeePermission = false;
}

/**
 * html add team / position
 * 
 * @param array $teamsOption
 * @param array $postionsOption
 * @param int $index
 * @param int $teamId
 * @param int $positionId
 */
if (! function_exists('teamHtmladdTeamPostion')) {
    function teamHtmladdTeamPostion(
        $teamsOption, 
        $postionsOption, 
        $index = 0, 
        $teamId = 0, 
        $positionId = 0,
        $employeePermission = true
        )
    { ?>
        <div class="form-inline group-team-position form-inline-block form-group-sm-select2">
            <div class="input-team-position input-team form-group">
                <label class="control-label">Team</label>
                <select name="team[{{ $index }}][team]" class="form-control select-search"
                    <?php if (! $employeePermission): ?>disabled<?php endif; ?>>
                    @foreach($teamsOption as $option)
                        <option value="{{ $option['value'] }}"<?php
                            if ($option['value'] == $teamId): ?> selected<?php endif; 
                        ?>{{ $option['option'] }}>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-team-position input-position form-group">
                <label class=" control-label">{{ trans('team::view.Position') }}</label>
                <select name="team[{{ $index }}][position]" class="form-control select-search"
                    <?php if (! $employeePermission): ?>disabled<?php endif; ?>>
                    @foreach($postionsOption as $option)
                        <option value="{{ $option['value'] }}"<?php
                            if ($option['value'] == $positionId): ?> selected<?php endif; 
                        ?>>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            @if ($employeePermission)
                <button type="button" class="btn-delete input-team-position input-remove" data-toggle="tooltip" 
                        data-placement="top" title="{{ trans('team::view.Remove team') }}" data-noti="{{ trans('team::view.Employees must belong to at least one team') }}">
                    <i class="fa fa-minus"></i>
                </button>
            @endif
        </div>
<?php }
}
?>

<div class="form-label-left box-form-team-position">
    <?php $i = 1; ?>
    @if (isset($employeeTeamPositions) && $employeeTeamPositions)
        @foreach ($employeeTeamPositions as $employeeTeamPosition)
            <?php teamHtmladdTeamPostion(
                    $teamsOption, 
                    $postionsOption, 
                    $i, 
                    $employeeTeamPosition->team_id, 
                    $employeeTeamPosition->role_id,
                    $employeePermission
                ); ?>
            <?php $i++; ?>
        @endforeach
    @elseif (! Form::getData('employee.id') && Form::getData('employee_team'))
        @foreach (Form::getData('employee_team') as $employeeTeamPosition)
            <?php teamHtmladdTeamPostion(
                    $teamsOption, 
                    $postionsOption, 
                    $i,
                    $employeeTeamPosition['team'], 
                    $employeeTeamPosition['position']
                ); ?>
            <?php $i++; ?>
        @endforeach
    @endif
    
    @if ($employeePermission)
        <div class="group-team-position-orgin hidden">
            <?php teamHtmladdTeamPostion(
                    $teamsOption,
                    $postionsOption 
                ); ?>
        </div>
    @endif
</div>
@if ($employeePermission)
    <div class="form-horizontal">
        <div class="form-group">
            <div class="input-team-position input-add-new col-md-12">
                <button type="button" class="btn-add" title="{{ trans('team::view.Add team') }}" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
@endif
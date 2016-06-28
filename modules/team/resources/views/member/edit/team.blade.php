<?php

use Rikkei\Core\View\Form;

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
    function teamHtmladdTeamPostion($teamsOption, $postionsOption, $index = 0, $teamId = 0, $positionId = 0)
    { ?>
        <div class="form-inline group-team-position form-inline-block">
            <div class="input-team-position input-team form-group">
                <label class="control-label">Team</label>
                <select name="team[{{ $index }}][team]" class="form-control select-search">
                    @foreach($teamsOption as $option)
                        <option value="{{ $option['value'] }}"<?php
                            if ($option['value'] == $teamId): ?> selected<?php endif; 
                        ?>{{ $option['option'] }}>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-team-position input-position form-group">
                <label class=" control-label">{{ trans('team::view.Position') }}</label>
                <select name="team[{{ $index }}][position]" class="form-control">
                    @foreach($postionsOption as $option)
                        <option value="{{ $option['value'] }}"<?php
                            if ($option['value'] == $positionId): ?> selected<?php endif; 
                        ?>>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn-delete input-team-position input-remove" data-toggle="tooltip" 
                    data-placement="top" title="{{ trans('team::view.Remove team') }}" data-noti="{{ trans('team::view.Employees must belong to at least one team') }}">
                <i class="fa fa-minus"></i>
            </button>
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
                    $employeeTeamPosition->role_id
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
    
    <div class="group-team-position-orgin hidden">
        <?php teamHtmladdTeamPostion(
                $teamsOption,
                $postionsOption 
            ); ?>
    </div>
</div>
<div class="form-horizontal">
    <div class="form-group">
        <div class="input-team-position input-add-new col-md-12">
            <button type="button" class="btn-add" title="{{ trans('team::view.Add team') }}" data-toggle="tooltip" data-placement="top">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>
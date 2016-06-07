<?php
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
        <div class="form-group group-team-position" data-id='{{ $index }}'>
            <div class="input-team-position input-team col-md-5">
                <label class="control-label">Team</label>
                <div class="input-box">
                    <select name="team[{{ $index }}][team]" class="form-control">
                        @foreach($teamsOption as $option)
                            <option value="{{ $option['value'] }}"<?php
                                if ($option['value'] == $teamId): ?> selected<?php endif; 
                            ?>>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="input-team-position input-position col-md-5">
                <label class=" control-label">{{ trans('team::view.Position') }}</label>
                <div class="input-box">
                    <select name="team[{{ $index }}][position]" class="form-control">
                        @foreach($postionsOption as $option)
                            <option value="{{ $option['value'] }}"<?php
                                if ($option['value'] == $positionId): ?> selected<?php endif; 
                            ?>>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="input-team-position input-remove col-md-2">
                <button type="button" class="btn-delete" data-toggle="tooltip" data-placement="top" title="{{ trans('team::view.Remove team') }}">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="clearfix"></div>
        </div>
<?php }
}
?>

<div class="form-horizontal form-label-left box-form-team-position">
    @if (isset($employeeTeamPositions) && $employeeTeamPositions)
        <?php $i = 1; ?>
        @foreach ($employeeTeamPositions as $employeeTeamPosition)
            <?php teamHtmladdTeamPostion(
                    $teamsOption, 
                    $postionsOption, 
                    $i, 
                    $employeeTeamPosition->team_id, 
                    $employeeTeamPosition->position_id
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
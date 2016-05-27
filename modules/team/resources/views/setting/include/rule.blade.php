<?php

use Rikkei\Team\View\Acl;
use Rikkei\Core\View\Form;
use Rikkei\Team\Model\TeamRule;

$acl = Acl::getAclData();
$i = 0;
?>
<form action="{{ URL::route('team::setting.team.rule.save') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="team[id]" value="{{ Form::getData('id') }}" />
    <div class="actions">
        <button type="submit" class="btn-add btn-large">
            <span>{{ trans('team::view.Save') }}</span>
        </button>
    </div>
    
    <table class="table table-bordered table-striped table-team-rule">
        <thead>
            <tr>
                <th class="col-screen">{{ trans('team::view.Screen') }}</th>
                <th class="col-function">{{ trans('team::view.Function') }}</th>
                @foreach ($positions as $position)
                    <th class="col-team">{{ $position->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
              @if (! count($acl))
                <p class="alert alert-warning">{{ trans('team::view.Not found function') }}</p>
              @else
                @foreach ($acl as $aclKey => $aclValue)
                    <tr>
                        <td class="col-screen">{{ $aclValue['label'] }}</td>
                    </tr>
                    @if ($aclValue['child'] && count($aclValue['child']))
                        @foreach ($aclValue['child'] as $aclItemKey => $aclItem)
                            <?php $aclItemKey = $aclKey . ':' . $aclItemKey; ?>
                            <tr>
                                <td class="col-screen-empty">&nbsp;</td>
                                <td>{{ $aclItem['label'] }}</td>
                                @foreach ($positions as $position)
                                    <td class="col-team">
                                        <input type="hidden" name="rule[{{ $i }}][position_id]" value="{{ $position->id }}" />
                                        <input type="hidden" name="rule[{{ $i }}][rule]" value="{{ $aclItemKey }}" />
                                        <select name="rule[{{ $i }}][scope]">
                                            @foreach (TeamRule::toOption() as $option)
                                                <option value="{{ $option['value'] }}"<?php
                                                    if (Acl::findScope($teamRules, $aclItemKey, $position->id) == $option['value']) {
                                                        echo ' selected';
                                                    }
                                                ?>>{{ $option['label'] }}</option>
                                            @endforeach
                                        </select>
                                        <?php $i++; ?>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                @endforeach
              @endif
        </tbody>
    </table>
    
    <div class="actions">
        <button type="submit" class="btn-add btn-large">
            <span>{{ trans('team::view.Save') }}</span>
        </button>
    </div>
</form>
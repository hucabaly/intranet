<?php

use Rikkei\Team\View\Acl;
use Rikkei\Core\View\Form;
use Rikkei\Team\Model\TeamRule;

$acl = Acl::getAclData();
$i = 0;
$scopeIcon = TeamRule::scopeIconArray();
?>
<form action="{{ URL::route('team::setting.team.rule.save') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="team[id]" value="{{ Form::getData('id') }}" />
    
    <div class="rule-noti">
        {!! TeamRule::getScopeIconGuide() !!}
    </div>
    
    <div class="actions">
        <button type="submit" class="btn-add btn-large">
            <span>{{ trans('team::view.Save') }}</span>
        </button>
    </div>
    <div class="table-responsive">
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
                        <tr class="tr-col-screen">
                            <td class="col-screen">{{ $aclValue['label'] }}</td>
                            <td>&nbsp;</td>
                            @foreach ($positions as $position)
                                <td>&nbsp;</td>
                            @endforeach
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
                                            <div class="btn-group form-input-dropdown">
                                                <input type="hidden" name="rule[{{ $i }}][scope]" value="{{ Acl::findScope($teamRules, $aclItemKey, $position->id) }}" class="input" />
                                                <button type="button" class="btn btn-default dropdown-toggle input-show-data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>{!! $scopeIcon[Acl::findScope($teamRules, $aclItemKey, $position->id)] !!}</span>
                                                </button>
                                                <ul class="dropdown-menu input-menu">
                                                    @foreach (TeamRule::toOption() as $option)
                                                        <li>
                                                            <a href="#" data-value="{{ $option['value'] }}">{!! $option['label'] !!}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
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
    </div>
    
    <div class="actions">
        <button type="submit" class="btn-add btn-large">
            <span>{{ trans('team::view.Save') }}</span>
        </button>
    </div>
</form>


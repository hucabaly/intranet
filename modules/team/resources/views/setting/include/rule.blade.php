<?php

use Rikkei\Team\View\Acl;
use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Permissions;

$acl = Acl::getAclList();
$i = 0;
$scopeIcon = Permissions::scopeIconArray();
?>
<form action="{{ URL::route('team::setting.team.rule.save') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="team[id]" value="{{ Form::getData('id') }}" />
    
    <div class="rule-noti">
        {!! Permissions::getScopeIconGuide() !!}
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
                    @foreach ($rolesPosition as $role)
                        <th class="col-team">{{ $role->role }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                  @if (! count($acl))
                    <p class="alert alert-warning">{{ trans('team::view.Not found function') }}</p>
                  @else
                    @foreach ($acl as $aclKey => $aclValue)
                        <tr class="tr-col-screen">
                            <td class="col-screen">{{ trans('acl.' . $aclValue['description']) }}</td>
                            <td>&nbsp;</td>
                            @foreach ($rolesPosition as $role)
                                <td>&nbsp;</td>
                            @endforeach
                        </tr>
                        @if (isset($aclValue['child']) && count($aclValue['child']))
                            @foreach ($aclValue['child'] as $aclItemKey => $aclItem)
                                <tr>
                                    <td class="col-screen-empty">&nbsp;</td>
                                    <td>{{ trans('acl.' . $aclItem['description']) }}</td>
                                    @foreach ($rolesPosition as $role)
                                        <td class="col-team">
                                            <input type="hidden" name="permission[{{ $i }}][role_id]" value="{{ $role->id }}" />
                                            <input type="hidden" name="permission[{{ $i }}][action_id]" value="{{ $aclItemKey }}" />
                                            <div class="btn-group form-input-dropdown">
                                                <input type="hidden" name="permission[{{ $i }}][scope]" 
                                                    value="{{ Acl::findScope($teamPermissions, $aclItemKey, $role->id) }}" class="input" />
                                                <button type="button" class="btn btn-default dropdown-toggle input-show-data" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>{!! $scopeIcon[Acl::findScope($teamPermissions, $aclItemKey, $role->id)] !!}</span>
                                                </button>
                                                <ul class="dropdown-menu input-menu">
                                                    @foreach (Permissions::toOption() as $option)
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


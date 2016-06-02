<?php

use Rikkei\Team\View\Acl;
use Rikkei\Core\View\Form;
use Rikkei\Team\Model\TeamRule;

$acl = Acl::getAclData();
$i = 0;
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-rules">
        <thead>
            <tr>
                <th class="col-screen">{{ trans('team::view.Screen') }}</th>
                <th class="col-function">{{ trans('team::view.Function') }}</th>
                <th class="team">&nbsp;</th>
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
                                <td class="col-team">
                                    <input type="hidden" name="rule[{{ $i }}][rule]" value="{{ $aclItemKey }}" />
                                    <select name="rule[{{ $i }}][scope]">
                                        @foreach (TeamRule::toOption() as $option)
                                            <option value="{{ $option['value'] }}"<?php
                                                if (Acl::findScope($roleRule, $aclItemKey, '') == $option['value']) {
                                                    echo ' selected';
                                                }
                                            ?>>{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    @endif
                @endforeach
              @endif
        </tbody>
    </table>
</div>
<div class="box-footer">
    <input type="submit" class="btn-add" name="submit" value="{{ trans('team::view.Save') }}" />
    <input type="submit" class="btn-edit" name="submit_continue" value="{{ trans('team::view.Save And Continue') }}" />
    <a href="{{ app('request')->fullUrl() }}" class="btn-move">Reset</a>
    @if(Form::getData('id'))
        <input type="submit" class="btn-delete delete-confirm" name="delete" value="{{ trans('team::view.Remove') }}"
               data-noti="{{ trans('team::view.Are you sure delete this role and all link this role with employee?') }}" />
    @endif
</div>
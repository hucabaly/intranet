<?php

use Rikkei\Core\View\Form;
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View;

$genderOption = Employees::toOptionGender();
?>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Employee code') }}</label>
        <div class="input-box col-md-9">
            <input type="text" class="form-control" placeholder="{{ trans('team::view.Employee code') }}" value="{{ Form::getData('employee.employee_code') }}" disabled />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Employee card id') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" class="form-control" name="employee[employee_card_id]" placeholder="{{ trans('team::view.Employee card id') }}" value="{{ Form::getData('employee.employee_card_id') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Full name') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[name]" class="form-control" placeholder="{{ trans('team::view.Full name') }}" value="{{ Form::getData('employee.name') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Birthday') }}</label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[birthday]" id="employee-birthday" class="form-control date-picker" placeholder="yyyy-mm-dd" value="{{ Form::getData('employee.birthday') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Gender') }}</label>
        <div class="input-box col-md-9">
            <select name="employee[gender]" class="form-control">
                @foreach ($genderOption as $option)
                    <option value="{{ $option['value'] }}"<?php if ($option['value'] == Form::getData('employee.gender')): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Identity card number') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[id_card_number]" class="form-control" placeholder="{{ trans('team::view.Identity card number') }}" value="{{ Form::getData('employee.id_card_number') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Phone') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[mobile_phone]" class="form-control" placeholder="{{ trans('team::view.Phone') }}" value="{{ Form::getData('employee.mobile_phone') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">Email Rikkei <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[email]" class="form-control" placeholder="Email Rikkei" value="{{ Form::getData('employee.email') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Email another') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[personal_email]" class="form-control" placeholder="{{ trans('team::view.Email another') }}" value="{{ Form::getData('employee.personal_email') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Address') }}</label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[address]" class="form-control" placeholder="{{ trans('team::view.Address') }}" value="{{ Form::getData('employee.address') }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label required">{{ trans('team::view.Join date') }} <em>*</em></label>
        <div class="input-box col-md-9">
            <input type="text" name="employee[join_date]" id="employee-joindate" class="form-control date-picker" placeholder="yyyy-mm-dd" value="{{ View::getDate(Form::getData('employee.join_date')) }}" />
        </div>
    </div>
</div>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Presenter') }}</label>
        <div class="input-box col-md-9">
            <input type="text" name="recruitment[presenter]" class="form-control" placeholder="{{ trans('team::view.Presenter') }}" value="{{ Form::getData('employee.presenter') }}" />
        </div>
    </div>
</div>

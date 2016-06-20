<?php

use Rikkei\Core\View\Form;
?>

<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="col-md-3 control-label">{{ trans('team::view.Employee code') }}</label>
        <div class="input-box col-md-9">
            <input type="text" class="form-control" placeholder="{{ trans('team::view.Employee code') }}" value="{{ Form::getData('employee_code') }}" disabled />
        </div>
    </div>
</div>

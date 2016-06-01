<?php
use Rikkei\Core\View\Form;
?>

<div class="form-group">
    <label class="col-sm-2 control-label" for="item-name">{{ trans('team::view.Name') }}</label>
    <div class="col-sm-10">
        <input type="text" name="item[name]" id="item-name" class="form-control" placeholder="{{ trans('team::view.Name') }}" 
               value="{{ Form::getData('name') }}" required />
    </div>
</div>
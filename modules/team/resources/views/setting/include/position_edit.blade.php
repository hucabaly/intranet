<?php
use Rikkei\Core\View\Form;

if (Form::getData('position.id')) {
    $suffixId = '-edit';
} else {
    $suffixId = '';
}
?>
<div class="modal-body">
    <div class="form-group">
        <label for="position-name{{ $suffixId }}" class="form-label">{{ Lang::get('team::setting.Position name') }}</label>
        <div class="form-data">
        <input type="text" class="form-control" id="position-name{{ $suffixId }}" name="position[name]" 
            value="{{ Form::getData('position.name') }}" required />
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn-add">{{ Lang::get('team::setting.Save') }}</button>
</div>

<?php
//if is page edit team, remove data of add team
if(Form::getData('position.id')) {
    Form::forget('position');
}


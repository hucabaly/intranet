<?php
use Rikkei\Core\View\Form;

if (Form::getData('id')) {
    $suffixId = '-edit';
} else {
    $suffixId = '';
}
?>
<div class="modal-body">
    <div class="form-group">
        <label for="team-name{{ $suffixId }}" class="form-label">{{ Lang::get('team::setting.Team name') }}</label>
        <input type="text" class="form-control form-data" id="team-name{{ $suffixId }}" name="item[name]" value="{{ Form::getData('name') }}" />
    </div>

    <div class="form-group">
        <label>{{ Lang::get('team::setting.Functional unit') }}</label>
        <div class="form-group-sub">
            <div class="row">
                <div class="col-sm-5">
                    <input type="checkbox" name="item[is_function]" id="is-function{{ $suffixId }}" class="input-is-function" data-id="group-{{ Form::getData('id') }}"
                        value="1"<?php if (Form::getData('is_function') == 1): ?> checked<?php endif; ?> />
                    <label for="is-function{{ $suffixId }}">{{ Lang::get('team::setting.Is function unit') }}</label>
                </div>
                <div class="col-sm-7 team-group-function" data-id="group-{{ Form::getData('id') }}">
                    <p>
                        <input type="radio" name="permission_same" id="permission-type-new{{ $suffixId }}" value="0"<?php if (Form::getData('permission_as') == 0): ?> checked<?php endif; ?> />
                        <label for="permission-type-new{{ $suffixId }}">{{ Lang::get('team::setting.New') }}</label>
                    </p>
                    <p>
                        <input type="radio" name="permission_same" id="permission-type-same{{ $suffixId }}" value="1"<?php if (Form::getData('permission_as') != 0): ?> checked<?php endif; ?> />
                        <label for="permission-type-same{{ $suffixId }}">{{ Lang::get('team::setting.Permission following function unit') }}</label>
                        <select class="input-select" name="item[permission_as]">
                            @foreach(Rikkei\Team\View\TeamList::toOption(Form::getData('id'), true) as $option)
                            <option value="{{ $option['value'] }}"
                                <?php if (Form::getData('permission_as') == $option['value']): ?> selected<?php endif; ?>
                                    {{ $option['option'] }}>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="team-parent{{ $suffixId }}" class="form-label">{{ Lang::get('team::setting.Team parent') }}</label>
        <select class="input-select form-data" name="item[parent_id]" id="team-parent{{ $suffixId }}">
            @foreach(Rikkei\Team\View\TeamList::toOption(Form::getData('id')) as $option)
            <option value="{{ $option['value'] }}"<?php if (Form::getData('parent_id') == $option['value']): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-danger">{{ Lang::get('team::setting.Reset') }}</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('team::setting.Close') }}</button>
    <button type="submit" class="btn btn-primary">{{ Lang::get('team::setting.Save') }}</button>
</div>

<?php
//if is page edit team, remove data of add team
if(Form::getData('id')) {
    Form::forget();
}

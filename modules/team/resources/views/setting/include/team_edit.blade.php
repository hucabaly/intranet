<?php
use Rikkei\Core\View\Form;
?>
<div class="modal fade" id="team-edit-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="post" action="{{ URL::route('team::setting.saveTeam') }}">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ Lang::get('team::setting.Create team') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="team-name" class="form-label">{{ Lang::get('team::setting.Team name') }}</label>
                        <input type="text" class="form-control form-data" id="team-name" name="item[name]" value="{{ Form::getData('name') }}" />
                    </div>
                    
                    <div class="form-group">
                        <label>{{ Lang::get('team::setting.Functional unit') }}</label>
                        <div class="form-group-sub">
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="checkbox" name="item[is_function]" id="is-function" value="1"<?php if( Form::getData('is_function') == 1): ?> checked<?php endif; ?> />
                                    <label for="is-function">{{ Lang::get('team::setting.Is function unit') }}</label>
                                </div>
                                <div class="col-sm-7 team-group-function">
                                    <p>
                                        <input type="radio" name="permission_new" id="permission-type-new" value="1"<?php if( Form::getData('permission_new') == 1): ?> checked<?php endif; ?> />
                                        <label for="permission-type-new">{{ Lang::get('team::setting.New') }}</label>
                                    </p>
                                    <p>
                                        <input type="radio" name="permission_new" id="permission-type-same" value="0"<?php if( Form::getData('permission_new') == 0): ?> checked<?php endif; ?> />
                                        <label for="permission-type-same">{{ Lang::get('team::setting.Permission following function unit') }}</label>
                                        <select class="input-select" name="item[permission_as]">
                                                @foreach(Rikkei\Team\View\TeamList::toOption(Form::getData('id'), true) as $option)
                                                <option value="{{ $option['value'] }}"<?php if( Form::getData('permission_as') == $option['value']): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                                                @endforeach
                                        </select>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="team-parent" class="form-label">{{ Lang::get('team::setting.Team parent') }}</label>
                        <select class="input-select form-data" name="item[parent_id]">
                            <option value="">{{ Lang::get('team::setting.--Please choose--') }}</option>
                            @foreach(Rikkei\Team\View\TeamList::toOption(Form::getData('id')) as $option)
                                <option value="{{ $option['value'] }}"<?php if( Form::getData('parent_id') == $option['value']): ?> selected<?php endif; ?>>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger">{{ Lang::get('team::setting.Reset') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('team::setting.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ Lang::get('team::setting.Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- school -->
<div class="modal fade employee-college-modal employee-skill-modal" id="employee-college-form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('team::view.Infomation of College') }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal form-label-left" data-id="1">
                    <input type="hidden" name="college[0][id]" value="" class="college-id" />
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-name">{{ trans('team::view.Name') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" class="form-control college-name" placeholder="{{ trans('team::view.Name') }}" 
                                value="" name="college[0][name]" id="college-name"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-image">{{ trans('team::view.Image') }}</label>
                        <div class="input-box col-md-9 input-box-img-preview college-image-box">
                            <div class="image-preview">
                                <img src="{{ URL::asset('common/images/noimage.png') }}"
                                     id="college-image-preview" class="img-responsive college-image-preview" />
                            </div>
                            <div class="img-input">
                                <input type="file" class="form-control college-image" value="" 
                                    name="college[0][image]" id="college-image" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-country">{{ trans('team::view.Country') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" class="form-control college-country" placeholder="{{ trans('team::view.Country') }}" 
                                value="" name="college[0][country]" id="college-country" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-province">{{ trans('team::view.Province') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" class="form-control college-province" placeholder="{{ trans('team::view.Province') }}" 
                                value="" name="college[0][province]" id="college-province" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-majors">{{ trans('team::view.Majors') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" class="form-control college-majors" placeholder="{{ trans('team::view.Majors') }}" 
                                value="" name="employee_school[0][majors]" id="college-majors" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-start">{{ trans('team::view.Start at') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" id="college-start" 
                                class="form-control date-picker college-start" placeholder="yyyy-mm" 
                                value="" name="employee_school[0][start_at]" id="college-start"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="college-end">{{ trans('team::view.End at') }}</label>
                        <div class="input-box col-md-9">
                            <input type="text" id="college-end" 
                                class="form-control date-picker college-end" placeholder="yyyy-mm" 
                                value="" name="employee_school[0][end_at]" id="college-end"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-delete college-delete" data-dismiss="modal">
                    <span>{{ trans('team::view.Remove') }}</span>
                </button>
                <button type="button" class="btn-add college-add" data-dismiss="modal">
                    <span>{{ trans('team::view.Add') }}</span>
                </button>
                <button type="button" class="btn-edit college-edit hidden" data-dismiss="modal">
                    <span>{{ trans('team::view.Edit') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ------------end school -->
<?php

use Rikkei\Core\View\View;
?>

<!-- school -->
<div class="modal fade employee-college-modal employee-skill-modal" 
    id="employee-school-form" tabindex="-1" role="dialog" data-id="1"
    data-group="schools">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ URL::route('core::upload.skill') }}" method="post" 
                    enctype="multipart/form-data" class="skill-modal-form" id="employee-skill-school-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('team::view.Infomation of College') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal form-label-left">
                        <input type="hidden" name="college[0][id]" value="" class="college-id input-skill-modal" 
                            data-tbl="school" data-col="id" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-name">{{ trans('team::view.Name') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control college-name input-skill-modal" placeholder="{{ trans('team::view.Name') }}" 
                                    value="" name="name" id="college-name"
                                    data-tbl="school" data-col="name" data-autocomplete="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-image">{{ trans('team::view.Image') }}</label>
                            <div class="input-box col-md-9 input-box-img-preview college-image-box">
                                <div class="image-preview">
                                    <img src="{{ URL::asset('common/images/noimage.png') }}"
                                         id="college-image-preview" class="img-responsive college-image-preview skill-modal-image-preview" 
                                         data-tbl="school" data-col="image_preview"/>
                                </div>
                                <div class="img-input">
                                    <input type="file" class="form-control college-image skill-modal-image input-skill-modal" value="" 
                                        name="image" id="college-image" 
                                        data-tbl="school" data-col="image" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-country">{{ trans('team::view.Country') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control college-country input-skill-modal" placeholder="{{ trans('team::view.Country') }}" 
                                    value="" name="country" id="college-country" 
                                    data-tbl="school" data-col="country" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-province">{{ trans('team::view.Province') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control college-province input-skill-modal" placeholder="{{ trans('team::view.Province') }}" 
                                    value="" name="province" id="college-province" 
                                    data-tbl="school" data-col="province" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-majors">{{ trans('team::view.Majors') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control college-majors input-skill-modal" placeholder="{{ trans('team::view.Majors') }}" 
                                    value="" name="majors" id="college-majors" 
                                    data-tbl="employee_school" data-col="majors" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-start">{{ trans('team::view.Start at') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" id="college-start" 
                                    class="form-control date-picker college-start input-skill-modal" placeholder="yyyy-mm-dd" 
                                    value="" name="start_at" id="college-start"
                                    data-tbl="employee_school" data-col="start_at" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="college-end">{{ trans('team::view.End at') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" id="college-end" 
                                    class="form-control date-picker college-end input-skill-modal" placeholder="yyyy-mm-dd" 
                                    value="" name="end_at" id="college-end"
                                    data-tbl="employee_school" data-col="end_at" />
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-delete btn-action">
                        <span>{{ trans('team::view.Remove') }}</span>
                    </button>
                    <button type="submit" class="btn-add btn-action">
                        <span>{{ trans('team::view.Save') }}</span>
                    </button>
                    <button type="submit" class="btn-edit btn-action hidden">
                        <span>{{ trans('team::view.Save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ------------end school -->


<!-- languages -->
<div class="modal fade employee-skill-modal" 
    id="employee-language-form" role="dialog" data-id="1"
    data-group="languages">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ URL::route('core::upload.skill') }}" method="post" 
                    enctype="multipart/form-data" class="skill-modal-form" id="employee-skill-language-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('team::view.Infomation of language') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal form-label-left">
                        <input type="hidden" name="id" value="" class="input-skill-modal language-id" 
                            data-tbl="language" data-col="id" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="language-name">{{ trans('team::view.Name') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control language-name input-skill-modal" placeholder="{{ trans('team::view.Name') }}" 
                                    value="" name="name" id="language-name"
                                    data-tbl="language" data-col="name" data-autocomplete="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="language-image">{{ trans('team::view.Image') }}</label>
                            <div class="input-box col-md-9 input-box-img-preview">
                                <div class="image-preview">
                                    <img src="{{ URL::asset('common/images/noimage.png') }}"
                                         class="img-responsive college-image-preview skill-modal-image-preview" 
                                         data-tbl="language" data-col="image_preview"/>
                                </div>
                                <div class="img-input">
                                    <input type="file" class="form-control skill-modal-image input-skill-modal" value="" 
                                        name="image" id="language-image" 
                                        data-tbl="language" data-col="image" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-select2">
                            <label class="col-md-3 control-label" for="language-level">{{ trans('team::view.Level') }}</label>
                            <div class="input-box col-md-9">
                                <select name="level" id="language-level" class="form-control language-level input-skill-modal select-search has-search"
                                        value=""  data-tbl="employee_language" data-col="level">
                                    @foreach (View::toOptionLanguageLevel() as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="language-start">{{ trans('team::view.Start at') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" id="language-start" 
                                    class="form-control date-picker language-start input-skill-modal" placeholder="yyyy-mm-dd" 
                                    value="" name="start_at" id="language-start"
                                    data-tbl="employee_language" data-col="start_at" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="language-end">{{ trans('team::view.End at') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" id="language-end" 
                                    class="form-control date-picker language-end input-skill-modal" placeholder="yyyy-mm-dd" 
                                    value="" name="end_at" id="language-end"
                                    data-tbl="employee_language" data-col="end_at" />
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-delete btn-action">
                        <span>{{ trans('team::view.Remove') }}</span>
                    </button>
                    <button type="submit" class="btn-add btn-action">
                        <span>{{ trans('team::view.Save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ------------end languages -->

<!-- cetificate -->
<div class="modal fade employee-skill-modal" 
    id="employee-cetificate-form" role="dialog" data-id="1"
    data-group="cetificates">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ URL::route('core::upload.skill') }}" method="post" 
                    enctype="multipart/form-data" class="skill-modal-form" id="employee-skill-cetificate-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('team::view.Infomation of cetificates') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal form-label-left">
                        <input type="hidden" name="id" value="" class="input-skill-modal cetificate-id" 
                            data-tbl="cetificate" data-col="id" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cetificate-name">{{ trans('team::view.Name') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" class="form-control cetificate-name input-skill-modal" placeholder="{{ trans('team::view.Name') }}" 
                                    value="" name="name" id="cetificate-name"
                                    data-tbl="cetificate" data-col="name" data-autocomplete="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cetificate-image">{{ trans('team::view.Image') }}</label>
                            <div class="input-box col-md-9 input-box-img-preview">
                                <div class="image-preview">
                                    <img src="{{ URL::asset('common/images/noimage.png') }}"
                                         class="img-responsive college-image-preview skill-modal-image-preview" 
                                         data-tbl="cetificate" data-col="image_preview"/>
                                </div>
                                <div class="img-input">
                                    <input type="file" class="form-control skill-modal-image input-skill-modal" value="" 
                                        name="image" id="cetificate-image" 
                                        data-tbl="cetificate" data-col="image" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cetificate-start">{{ trans('team::view.Start at') }}</label>
                            <div class="input-box col-md-9">
                                <input type="text" id="cetificate-start" 
                                    class="form-control date-picker cetificate-start input-skill-modal" placeholder="yyyy-mm-dd" 
                                    value="" name="start_at" id="cetificate-start"
                                    data-tbl="employee_cetificate" data-col="start_at" />
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-delete btn-action">
                        <span>{{ trans('team::view.Remove') }}</span>
                    </button>
                    <button type="submit" class="btn-add btn-action">
                        <span>{{ trans('team::view.Save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ------------end cetificate -->
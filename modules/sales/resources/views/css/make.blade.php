@extends('layouts.guest')

@section('content')
<div class="make-css-page">
    <div class="row">
        <div class="col-md-12">
            <section id="make-header">
                <div class="logo-rikkei"><img src="{{ URL::asset('common/images/logo-rikkei.png') }}"></div>
                <h2 class="title <?php if($css->project_type_id === 2){ echo 'title-base'; }?>">お客様アンケート</h2>
                <div class="total-point-container <?php if($css->project_type_id === 2){ echo 'total-point-container-base'; }?>">
                    <div class="total-point-text">{{ trans('sales::view.Total point')}}</div>
                    <div class="total-point <?php if($css->project_type_id === 2){ echo 'total-point-base'; }?>" >00.00</div>
                </div>
                <div class="visible-check"></div>
            </section>
            <section>

                <!-- PROJECT INFORMATION -->
                <div class="row project-info">
                    <div class="col-xs-12 header <?php if($css->project_type_id === 2){ echo 'header-base'; }?>">{{ trans('sales::view.Project information') }}</div>
                    <div class="col-xs-12 col-sm-6 padding-left-right-5px">
                        <div class="row-info">{{ trans('sales::view.Project name jp') }} {{ $css->project_name }}</div>
                        <div class="row-info">{{ trans('sales::view.Sale name jp') }}{{ $employee->japanese_name }}</div>
                        <div class="row-info">{{ trans('sales::view.PM name jp') }}{{ $css->pm_name }}</div>
                        <div class="row-info">{{ trans('sales::view.BrSE name jp') }}{{ $css->brse_name }}</div>
                    </div>
                    <div class="col-xs-12 col-sm-6 padding-left-right-5px">
                        <div class="row-info">{{ trans('sales::view.Project date jp') }}{{ date("m/d/Y",strtotime($css->start_date)) }} - {{ date("m/d/Y",strtotime($css->end_date)) }}</div>
                        <div class="row-info">{{ trans('sales::view.Customer company name jp') }}{{ $css->company_name }} 様</div>
                        <div class="row-info">{{ trans('sales::view.Customer name jp') }}{{ $css->customer_name }} 様</div>
                        <div class="row-info">{{ trans('sales::view.Make name jp')}}<span class="make-name">{{ $makeName }} </span>様</div>
                    </div>
                </div>
                <!-- END PROJECT INFORMATION -->

                <!-- PROJECT DETAIL -->
                <div class="row project-detail">
                    <div class="col-xs-12 header <?php if($css->project_type_id === 2){ echo 'header-base'; }?>">
                        <div class="col-xs-12 col-sm-5">{{ trans('sales::view.Question') }}</div>
                        <div class="col-sm-2 rating-header">{{ trans('sales::view.Rating') }}</div>
                        <div class="col-sm-5 comment-header">{{ trans('sales::view.Comment') }}</div>
                        <div class="note-comment">{{ trans('sales::view.Note comment')}}</div>
                    </div>
                    @foreach($cssCate as $item)
                        @if($item['noCate'] == 1)
                        <div class="col-xs-12 root-cate root-cate-first <?php if($css->project_type_id === 2){ echo 'root-cate-base'; }?>">
                            {{$item["sort_order"] . ". " .$item['name']}}
                            <div class="col-sm-5 note-comment">{{ trans('sales::view.Note comment')}}</div>
                        </div>
                        @else
                        <div class="col-xs-12 root-cate <?php if($css->project_type_id === 2){ echo 'root-cate-base'; }?>">
                            {{$item["sort_order"] . ". " .$item['name']}}
                        </div>    
                        @endif
                        @if($item['show_pm_name'] === 1)
                        <div class="col-xs-12 show-name-in-cate">{{trans('sales::view.Show PM in category',['pm_name' => $css->pm_name])}}</div>
                        @endif
                        @if($item['show_brse_name'] === 1)
                        <div class="col-xs-12 show-name-in-cate">
                            @if($css->project_type_id === 1)
                                {{trans('sales::view.Show BrSE OSDC in category',['brse_name' => $css->brse_name])}}
                            @else
                                {{trans('sales::view.Show BrSE base in category',['brse_name' => $css->brse_name])}}
                            @endif
                        </div>
                        @endif
                        @if($item['cssCateChild'])
                            @foreach($item['cssCateChild'] as $itemChild)
                                <div class="col-xs-12 child-cate">{{$itemChild["sort_order"] . ". " .$itemChild['name']}}</div>
                                @if($itemChild['questionsChild'])
                                    @foreach($itemChild['questionsChild'] as $questionChild)
                                    <div class="row container-question">
                                        <div class="col-xs-12 col-sm-5 question"><span class="num-border">{{ $questionChild->sort_order }}</span> {{ $questionChild->content}}</div>
                                        <div class="col-xs-12 col-sm-2 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$questionChild->id}}" onclick="totalMark(this);" ontouchend="totalMark(this);" ></div></div>
                                        <div class="col-xs-12 col-sm-5 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$questionChild->id}}" ></textarea></div>
                                    </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @elseif($item['questions'])
                            @foreach($item['questions'] as $question)
                            <div class="row container-question">
                                <div class="col-xs-12 col-sm-5 question"><span class="num-border">{{ $question->sort_order }}</span> {{ $question->content}}</div>
                                <div class="col-xs-12 col-sm-2 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$question->id}}" onclick="totalMark(this);" ontouchend="totalMark(this);" ></div></div>
                                <div class="col-xs-12 col-sm-5 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$question->id}}" ></textarea></div>
                            </div>
                            @endforeach
                        @endif
                    @endforeach

                    <!-- Overview question -->
                    <div class="col-xs-12 root-cate <?php if($css->project_type_id === 2){ echo 'root-cate-base'; }?>">{{$noOverView . ". " .trans('sales::view.Overview') }}</div>
                    <div class="row container-question">
                        <div class="col-xs-12 col-sm-5 question">{{ $overviewQuestionContent }}</div>
                        <div class="col-xs-12 col-sm-2 rate"><div id="tongquat" class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$overviewQuestionId}}" onclick="totalMark(this);" ontouchend="totalMark(this);" ></div></div>
                        <div class="col-xs-12 col-sm-5 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$overviewQuestionId}}" id="comment-tongquat" ></textarea></div>
                    </div>
                    <!-- Proposed -->
                    <div class="row container-question">
                        <div class="col-xs-12 col-sm-5 question">
                            @if($css->project_type_id === 1)
                            <div>{{ trans('sales::view.Proposed line 1 OSDC') }}</div>
                            @else
                            <div>{{ trans('sales::view.Proposed line 1 base') }}</div>
                            @endif
                            <div>{{ trans('sales::view.Proposed line 2') }}</div>
                            <div>{{ trans('sales::view.Proposed line 3') }}</div>
                            <div>{{ trans('sales::view.Proposed line 4') }}</div>
                            <div>{{ trans('sales::view.Proposed line 5') }}</div>
                            <div>{{ trans('sales::view.Proposed line 6') }}</div>
                        </div>
                        <div class="col-xs-12 col-sm-7 comment proposed-comment"><textarea class="proposed form-control" id="proposed" maxlength="2000"></textarea></div>
                    </div>
                    <!-- Button submit -->
                    <div class="col-xs-12 container-submit"><button type="button" class="btn btn-primary <?php if($css->project_type_id === 2){ echo 'button-base'; }?>" onclick="confirm('{{$arrayValidate}}');">アンケートを送信する</button></div>
                </div>
                <!-- END PROJECT DETAIL -->
                
                <!-- MODALS -->
                <div class="modal modal-danger" id="modal-alert">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">{{ trans('sales::view.Error make css') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>One fine body…</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ trans('sales::view.Close jp')}}</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal modal-primary" id="modal-confirm">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">{{ trans('sales::view.Confirm make css') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>One fine body…</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">{{ trans('sales::view.Cancel make css') }}</button>
                                <button type="button" class="btn btn-outline" onclick="submit('{{ Session::token() }}',{{$css->id}});">{{ trans('sales::view.Submit make css') }}</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal modal-warning" id="modal-confirm-make">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"></span></button>
                                <h4 class="modal-title">{{ trans('sales::view.Warning') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('sales::message.Make confirm line 1') }}</p>
                                <p>{{ trans('sales::message.Make confirm line 2') }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" onclick="goToFinish();">{{ trans('sales::view.No') }}</button>
                                <button type="button" class="btn btn-outline" onclick="hideModalConfirmMake();">{{ trans('sales::view.Yes') }}</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal apply-click-modal"><img class="loading-img" src="{{ asset('sales/images/loading.gif') }}" /></div>

            </section>
        </div>
    </div>
</div>
@endsection

<!-- Styles -->
@section('css')
<link href="{{ asset('sales/css/css_customer.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('lib/rateit/rateit.css') }}" rel="stylesheet" type="text/css" >
@endsection

<!-- Script -->
@section('script')
<script src="{{ asset('lib/rateit/jquery.rateit.js') }}"></script>
<script src="{{ asset('lib/js/jquery.visible.js') }}"></script>
<script src="{{ asset('sales/js/css/customer.js') }}"></script>
<script type="text/javascript">
    <?php if(Auth::check()): ?>
        $('#modal-confirm-make').show();
    <?php endif; ?>
</script>
@endsection
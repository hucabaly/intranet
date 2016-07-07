@extends('layouts.guest')

@section('content')
<div class="make-css-page">
    <div class="row">
        <div class="col-md-12">
            <section id="make-header">
                <div class="logo-rikkei"><img src="{{ URL::asset('common/images/logo-rikkei.png') }}"></div>
                <h2 class="title">お客様アンケート</h2>
                <div class="total-point-container">
                    <div id="total-point-text">{{ trans('sales::view.Total point')}}</div>
                    <div id="total-point">00.00</div>
                </div>
                <div class="visible-check"></div>
            </section>
            <section>

                <!-- PROJECT INFORMATION -->
                <div class="row project-info">
                    <div class="col-xs-12 header">{{ trans('sales::view.Project information') }}</div>
                    <div class="col-xs-12 col-sm-6 padding-left-right-5px">
                        <div class="row-info">{{ trans('sales::view.Project name jp') }} {{ $css->project_name }}</div>
                        <div class="row-info">{{ trans('sales::view.Sale name jp') }}{{ $employee->japanese_name }}</div>
                        <div class="row-info">{{ trans('sales::view.PM name jp') }}{{ $css->pm_name }}</div>
                        <div class="row-info">{{ trans('sales::view.BrSE name jp') }}{{ $css->brse_name }}</div>
                    </div>
                    <div class="col-xs-12 col-sm-6 padding-left-right-5px">
                        <div class="row-info">{{ trans('sales::view.Project date jp') }}{{ date("m/d/Y",strtotime($css->start_date)) }} - {{ date("m/d/Y",strtotime($css->end_date)) }}</div>
                        <div class="row-info">{{ trans('sales::view.Customer company name jp') }}{{ $css->company_name }}</div>
                        <div class="row-info">{{ trans('sales::view.Customer name jp') }}{{ $css->customer_name }}</div>
                        <div class="row-info">{{ trans('sales::view.Make name jp')}}<span class="make-name">{{ $makeName }}</span></div>
                    </div>
                </div>
                <!-- END PROJECT INFORMATION -->

                <!-- PROJECT DETAIL -->
                <div class="row project-detail">
                    <div class="col-xs-12 header">
                        <div>{{ trans('sales::view.Question') }}</div>
                        <div class="note-comment">{{ trans('sales::view.Note comment')}}</div>
                    </div>
                    @foreach($cssCate as $item)
                        <div class="col-xs-12 root-cate">{{$item["sort_order"] . ". " .$item['name']}}</div>
                        @if($item['cssCateChild'])
                            @foreach($item['cssCateChild'] as $itemChild)
                                <div class="col-xs-12 child-cate">{{$itemChild["sort_order"] . ". " .$itemChild['name']}}</div>
                                @if($itemChild['questionsChild'])
                                    @foreach($itemChild['questionsChild'] as $questionChild)
                                    <div class="row container-question">
                                        <div class="col-xs-12 col-sm-4 question"><span class="num-border">{{ $questionChild->sort_order }}</span> {{ $questionChild->content}}</div>
                                        <div class="col-xs-12 col-sm-4 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$questionChild->id}}" onclick="totalMark(this);"></div></div>
                                        <div class="col-xs-12 col-sm-4 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$questionChild->id}}"  ></textarea></div>
                                    </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @elseif($item['questions'])
                            @foreach($item['questions'] as $question)
                            <div class="row container-question">
                                <div class="col-xs-12 col-sm-4 question"><span class="num-border">{{ $question->sort_order }}</span> {{ $question->content}}</div>
                                <div class="col-xs-12 col-sm-4 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$question->id}}" onclick="totalMark(this);"></div></div>
                                <div class="col-xs-12 col-sm-4 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$question->id}}"  ></textarea></div>
                            </div>
                            @endforeach
                        @endif
                    @endforeach

                    <!-- Overview question -->
                    <div class="col-xs-12 root-cate">{{$noOverView . ". " .trans('sales::view.Overview') }}</div>
                    <div class="row container-question">
                        <div class="col-xs-12 col-sm-4 question">{{ $overviewQuestionContent }}</div>
                        <div class="col-xs-12 col-sm-4 rate"><div id="tongquat" class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$overviewQuestionId}}" onclick="totalMark(this);"></div></div>
                        <div class="col-xs-12 col-sm-4 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$overviewQuestionId}}" id="comment-tongquat"  ></textarea></div>
                    </div>
                    <!-- Proposed -->
                    <div class="row container-question">
                        <div class="col-xs-12 question">{{ trans('sales::view.Proposed') }}</div>
                        <div class="col-xs-12 comment proposed-comment"><textarea class="proposed form-control" id="proposed" maxlength="2000"></textarea></div>
                    </div>
                    <!-- Button submit -->
                    <div class="col-xs-12 container-submit"><button type="button" class="btn btn-primary" onclick="confirm('{{$arrayValidate}}');">Submit</button></div>
                </div>
                <!-- END PROJECT DETAIL -->

                <div class="point-fixed">00.00</span></div>

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
                                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">Close</button>
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
<div class="welcome-footer col-md-12">
    <div class="row">
        <div class="col-md-6"><p class="float-left copyright">Copyright © 2016 <span>Rikkeisoft</span>. All rights reserved.</p></div>
        <div class="col-md-6"><p class="float-right policy"><a href="http://rikkeisoft.com/privacypolicy/" target="_blank"><span class="policy-link">プライバシーポリシー</span></a> &nbsp; | &nbsp; Version 1.0.0</p></div>
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
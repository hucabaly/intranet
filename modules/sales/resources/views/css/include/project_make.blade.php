<!-- PROJECT INFORMATION -->
<div class="row project-info">
    <div class="col-xs-12 header">{{ trans('sales::view.Project information') }}</div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Project name jp') }} <strong>{{ $css->project_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Sale name jp') }}<strong>{{ $employee->japanese_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.PM name jp') }}<strong>{{ $css->pm_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.BrSE name jp') }}<strong>{{ $css->brse_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Project date jp') }}<strong>{{ date("m/d/Y",strtotime($css->start_date)) }} - {{ date("m/d/Y",strtotime($css->end_date)) }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Customer company name jp') }}<strong>{{ $css->company_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Customer name jp') }}<strong>{{ $css->customer_name }}</strong></div>
    <div class="col-xs-12 row-info">{{ trans('sales::view.Make name jp')}}<strong><span class="make-name"></span></strong></div>
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
                        <div class="col-xs-12 question">{{$questionChild->sort_order . ". " .$questionChild->content}}</div>
                        <div class="col-xs-12 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$questionChild->id}}" onclick="totalMark(this);"></div></div>
                        <div class="col-xs-12 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$questionChild->id}}"  ></textarea></div>
                    @endforeach
                @endif
            @endforeach
        @elseif($item['questions'])
            @foreach($item['questions'] as $question)
                <div class="col-xs-12 question">{{$question->sort_order . ". " .$question->content}}</div>
                <div class="col-xs-12 rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$question->id}}" onclick="totalMark(this);"></div></div>
                <div class="col-xs-12 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$question->id}}"  ></textarea></div>
            @endforeach
        @endif
    @endforeach
    
    <!-- Overview question -->
    <div class="col-xs-12 root-cate">{{$noOverView . ". " .trans('sales::view.Overview') }}</div>
    <div class="col-xs-12 question">{{ $overviewQuestionContent }}</div>
    <div class="col-xs-12 rate"><div id="tongquat" class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$overviewQuestionId}}" onclick="totalMark(this);"></div></div>
    <div class="col-xs-12 comment"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$overviewQuestionId}}" id="comment-tongquat"  ></textarea></div>
    
    <!-- Proposed -->
    <div class="col-xs-12 question">{{ trans('sales::view.Proposed') }}</div>
    <div class="col-xs-12 comment proposed-comment"><textarea class="proposed form-control" id="proposed" maxlength="2000"></textarea></div>
    
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
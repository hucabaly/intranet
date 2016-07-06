<!--<div class="row project-info">
    <div class="col-xs-12 header">{{ trans('sales::view.Project information') }}</div>
    
</div>-->
<table class="table table-bordered bang1">
    <tr><td colspan="5" class="top"><label class="project-info-title">{{ trans('sales::view.Project information') }}</label></td></tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.Project name') }}<label</td>
        <td class="infomation">{{$css->project_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Period') }}</label></td>
        <td class="infomation">{{date("d/m/Y",strtotime($css->start_date))}} - {{date("d/m/Y",strtotime($css->end_date))}}</td>
        <td class="make_date"><label>{{ trans('sales::view.Make date') }} {{date("d/m/Y")}}</label></td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.Sale name') }}</label></td>
        <td class="infomation">{{$employee->japanese_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Customer name') }}</label></td>
        <td class="infomation">{{$css->customer_name}}</td>
        <td rowspan="3" class="{{ ($css->project_type_id == 1) ? 'diemso-osdc' : 'diemso-base' }}">
            <div>{{ trans('sales::view.Total point') }}</div>
            <div class="diem">00.00</div>
        </td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.PM name') }}</label></td>
        <td class="infomation">{{$css->pm_name}}</td>
        <td class="title2"></td>
        <td></td>

    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.BrSE name') }}</label></td>
        <td class="infomation">{{$css->brse_name}}</td>
        <td class="title2"></td>
        <td></td>

    </tr>
</table>
<div class="visible-check"></div>
<table class="table table-bordered bang2 {{($css->project_type_id == 1) ? 'table-osdc' : 'table-base' }}">
  <!-- header -->
  <tr class="header">
      <td><label>{{ trans('sales::view.No.') }}</label></td>
    <td><label>{{ trans('sales::view.Question') }}</label></td>
    <td class="reply"><label>{{ trans('sales::view.Rating') }}</label></td>
    <td class="comment"><label>{{ trans('sales::view.Comment') }}</label></td>
</tr>

@foreach($cssCate as $item)
    <tr class="mucto">
        <td class="title" colspan="3">{{$item["sort_order"] . ". " .$item['name']}}</td>
        <td class="title2"></td>
    </tr>
    @if($item['cssCateChild'])
        @foreach($item['cssCateChild'] as $itemChild)
            <tr class="mucbe">
                <td class="title" colspan="3">{{$itemChild["sort_order"] . ". " .$itemChild['name']}}</td>
                <td class="title2">
            </tr>
            @if($itemChild['questionsChild'])
                @foreach($itemChild['questionsChild'] as $questionChild)
                    <tr class="cau">
                        <td class="title" colspan="2">{{$questionChild->sort_order . ". " .$questionChild->content}}</td>
                        <td class="rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$questionChild->id}}" onclick="totalMark(this);"></div></td>
                        <td class="title2"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$questionChild->id}}"  ></textarea></td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    @elseif($item['questions'])
        @foreach($item['questions'] as $question)
            <tr class="cau">
                <td class="title" colspan="2">{{$question->sort_order . ". " .$question->content}}</td>
                <td class="rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-questionid="{{$question->id}}" onclick="totalMark(this);"></div></td>
                <td class="title2"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$question->id}}"  ></textarea></td>
            </tr>
        @endforeach
    @endif
    
@endforeach
    <tr class="mucto">
        <td class="title" colspan="4">{{$noOverView . ". " .trans('sales::view.Overview') }}</td>
    </tr>

    <!-- Overview Question -->
    <tr class="cau">
        <td class="title" colspan="2">{{ $overviewQuestionContent }}</td>
        
        <td class="rate"><div id="tongquat" class="rateit" data-rateit-step='1' data-questionid="{{$overviewQuestionId}}" data-rateit-resetable="false" onclick="totalMark(this);"></div></td>
        <td class="title2"><textarea class="comment-question form-control" rows="1" type="text" maxlength="1000" data-questionid="{{$overviewQuestionId}}" id="comment-tongquat"  ></textarea></td>
    </tr>

    <!-- Proposed -->
    <tr class="cau">
        <td class="title" >
            {{ trans('sales::view.Proposed') }}
        </td>
        
        <td class="title2" colspan="2"><textarea class="proposed form-control" id="proposed" maxlength="2000" placeholder="{{ trans('sales::view.Proposed placeholder') }}"></textarea></td>
        <td class="title2"></td>
    </tr>
</table>
<div class="container-submit"><button type="button" class="btn btn-primary" onclick="confirm('{{$arrayValidate}}');">Submit</button></div>
<div class="diem-fixed">Tổng điểm: 00.00</span></div>
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
                <p>{{ trans('sales::message.Make confirm') }}</p>
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
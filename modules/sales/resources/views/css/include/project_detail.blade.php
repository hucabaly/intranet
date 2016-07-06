<table class="table table-bordered bang1">
    <tr >
        <td colspan="5" class="top">
            <label class="project-info-title">{{ trans('sales::view.Project information') }}</label>
        </td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.Project name') }}<label</td>
        <td class="infomation">{{$css->project_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Period') }}</label></td>
        <td class="infomation">{{date("d/m/Y",strtotime($css->start_date))}} - {{date("d/m/Y",strtotime($css->end_date))}}</td>
        <td class="make_date infomation"><label>{{ trans('sales::view.Make date') }} {{date("d/m/Y",strtotime($cssResult->created_at))}}</label></td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.Sale name') }}</label></td>
        <td class="infomation">{{$employee->japanese_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Customer name') }}</label></td>
        <td class="infomation">{{$css->customer_name}}</td>
        <td rowspan="3" class="<?php echo ($css->project_type_id == 1) ? 'diemso-osdc' : 'diemso-base' ?>">
            <div>{{ trans('sales::view.Total point') }}</div>
            <div class="diem">{{number_format($cssResult->avg_point,2)}}</div>
        </td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.PM name') }}</label></td>
        <td class="infomation">{{$css->pm_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Make name') }}</label></td>
        <td class="infomation"><label>{{$cssResult->name}}</label></td>
    </tr>
    <tr>
        <td class="title"><label>{{ trans('sales::view.BrSE name') }}</label></td>
        <td class="infomation">{{$css->brse_name}}</td>
        <td class="title2"><label>{{ trans('sales::view.Make email') }}</label></td>
        <td class="infomation"><label>{{$cssResult->email}}</label></td>
    </tr>
</table>
<div class="visible-check"></div>
<table><tr><td></td></tr></table>
<table class="table table-bordered bang2 <?php echo ($css->project_type_id == 1) ? 'table-osdc' : 'table-base' ?>">
  <!-- header -->
  <tr class="header">
    <td colspan="2"><label>{{ trans('sales::view.Question') }}</label></td>
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
                        <td class="rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-rateit-readonly="true" data-questionid="{{$questionChild->id}}" data-rateit-value="{{$questionChild->point}}" ></div></td>
                        <td class="title2">{{$questionChild->comment}}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    @elseif($item['questions'])
        @foreach($item['questions'] as $question)
            <tr class="cau">
                <td class="title" colspan="2">{{$question->sort_order . ". " .$question->content}}</td>
                <td class="rate"><div class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-rateit-readonly="true" data-questionid="{{$question->id}}" data-rateit-value="{{$question->point}}"></div></td>
                <td class="title2">{{$question->comment}}</td>
            </tr>
        @endforeach
    @endif
    
@endforeach
    <!-- muc to -->
    <tr class="mucto">
        <td class="title" colspan="4">{{$noOverView . ". " .trans('sales::view.Overview') }}</td>
    </tr>

    <!-- Overview question -->
    <tr class="cau">
        <td class="title" colspan="2">{{ $overviewQuestionContent }}</td>
        <td class="rate"><div id="tongquat" class="rateit" data-rateit-step='1' data-rateit-resetable="false" data-rateit-readonly="true" data-rateit-value="{{$resultDetailRowOfOverview->point}}"></div></td>
        <td class="title2">{{$resultDetailRowOfOverview->comment}}</td>
    </tr>

    <!-- Proposed -->
    <tr class="cau">
        <td class="title" >{{ trans('sales::view.Proposed') }}</td>
        <td class="title2" colspan="2" style="vertical-align:top;">{{$cssResult->proposed}}</td>
        <td class="title2"></td>
    </tr>
</table>

<div class="diem-fixed">{{number_format($cssResult->avg_point,2)}}</div>



<table class="table table-bordered table-hover dataTable tieuchi" >
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th class="hienthi-theotieuchi">{{trans('sales::view.Project type')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($result) && count($result) > 0)
        @foreach($result as $item)
        <tr>
            <td>{{$item["stt"]}}</td>
            <td>{{$item["project_type_name"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='{{$item["project_type_id"]}}' class="checkItem">
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 

<!------ table project type -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcProjectType" >
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.Project type')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkProjectTypeAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($result) && count($result["projectType"]) > 0)
        @foreach($result["projectType"] as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["projectTypeName"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["projectTypeId"]}}' class="checkProjectTypeItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 
<!------ table team -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcTeam">
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.Team')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkTeamAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($result) && count($result["team"]) > 0)
        @foreach($result["team"] as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["teamName"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["teamId"]}}' class="checkTeamItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 

<!------ table PM -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcPm">
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.PM')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkPmAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($result) && count($result["pm"]) > 0)
        @foreach($result["pm"] as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["pmName"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["pmId"]}}' class="checkPmItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 
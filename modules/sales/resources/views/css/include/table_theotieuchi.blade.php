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
        @if(isset($projectType) && count($projectType) > 0)
        @foreach($projectType as $item)
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
        @if(isset($team) && count($team) > 0)
        @foreach($team as $item)
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
        @if(isset($pm) && count($pm) > 0)
        @foreach($pm as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["name"]}}' class="checkPmItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 

<!------ table BrSE -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcBrse">
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.BrSE name')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkBrseAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($brse) && count($brse) > 0)
        @foreach($brse as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["name"]}}' class="checkBrseItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 

<!------ table Customer -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcCustomer">
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.Customer name')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkCustomerAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($customer) && count($customer) > 0)
        @foreach($customer as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["name"]}}' class="checkCustomerItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 

<!------ table sale -------------->
<table class="table table-bordered table-hover dataTable tbl-criteria" data-id="tcSale">
    <thead>
        <tr>
            <th>{{trans('sales::view.No.')}}</th>
            <th>{{trans('sales::view.Sale name')}}</th>
            <th>{{trans('sales::view.Count css')}}</th>
            <th>{{trans('sales::view.Avg css')}}</th>
            <th>{{trans('sales::view.Max css')}}</th>
            <th>{{trans('sales::view.Min css')}}</th>
            <th>{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkSaleAll">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($sale) && count($sale) > 0)
        @foreach($sale as $item)
        <tr>
            <td>{{$item["no"]}}</td>
            <td>{{$item["name"]}}</td>
            <td>{{$item["countCss"]}}</td>
            <td>{{$item["avgPoint"]}}</td>
            <td>{{$item["maxPoint"]}}</td>
            <td>{{$item["minPoint"]}}</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["id"]}}' class="checkSaleItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 
<!------ table project type -------------->
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcProjectType" >
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Project type')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkProjectType">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($projectType) && count($projectType) > 0)
        @foreach($projectType as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["projectTypeName"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcTeam">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Team')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkTeam">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($team) && count($team) > 0)
        @foreach($team as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["teamName"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcPm">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.PM')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkPm">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($pm) && count($pm) > 0)
        @foreach($pm as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["name"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcBrse">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.BrSE name')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkBrse">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($brse) && count($brse) > 0)
        @foreach($brse as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["name"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcCustomer">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Customer name')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkCustomer">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($customer) && count($customer) > 0)
        @foreach($customer as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["name"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcSale">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Sale name')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-2">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkSale">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($sale) && count($sale) > 0)
        @foreach($sale as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-2">{{$item["name"]}}</td>
            <td class="col-xs-2">{{$item["countCss"]}}</td>
            <td class="col-xs-2">{{$item["avgPoint"]}}</td>
            <td class="col-xs-2">{{$item["maxPoint"]}}</td>
            <td class="col-xs-2">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
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

<!------ table questions -------------->
<table class="table table-hover dataTable tbl-criteria table-fixed" data-id="tcQuestion">
    <thead>
        <tr>
            <th class="col-xs-1">{{trans('sales::view.No.')}}</th>
            <th class="col-xs-6">{{trans('sales::view.Css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Count css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Avg css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Max css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Min css')}}</th>
            <th class="col-xs-1">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkQuestion">
                    </div>
                </label>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(isset($sale) && count($sale) > 0)
        @foreach($sale as $item)
        <tr>
            <td class="col-xs-1">{{$item["no"]}}</td>
            <td class="col-xs-6">{{$item["name"]}}</td>
            <td class="col-xs-1">{{$item["countCss"]}}</td>
            <td class="col-xs-1">{{$item["avgPoint"]}}</td>
            <td class="col-xs-1">{{$item["maxPoint"]}}</td>
            <td class="col-xs-1">{{$item["minPoint"]}}</td>
            <td class="col-xs-1">
                <label class="label-normal">
                    <div class="icheckbox">
                    @if($item["countCss"] > 0)
                        <input type="checkbox" data-id='{{$item["id"]}}' class="checkQuestionItem">
                    @endif
                    </div>
                </label>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table> 
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
        <tr>
            <td>1</td>
            <td>Web</td>
            <td>18</td>
            <td>99</td>
            <td>100</td>
            <td>98</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" class="checkItem">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Web</td>
            <td>18</td>
            <td>99</td>
            <td>100</td>
            <td>98</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" class="checkItem">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Web</td>
            <td>18</td>
            <td>99</td>
            <td>100</td>
            <td>98</td>
            <td>
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" class="checkItem">
                    </div>
                </label>
            </td>
        </tr>
    </tbody>
</table> 

<!-----------------------bang hien thi tieu chi theo cau hoi--------------------------------->
<table class="tieuchi-theocauhoi" cellspacing="0" cellpadding="0" border="0" width="100%">
 <tr>
  <td>
      <table class="table table-bordered table-hover dataTable"  width="100%" >
        <tr>
            <th class="No">{{trans('sales::view.No.')}}</th>
            <th class="question">{{trans('sales::view.Css')}}</th>
            <th class="point">{{trans('sales::view.Count css')}}</th>
            <th class="point">{{trans('sales::view.Avg point')}}</th>
            <th class="point">{{trans('sales::view.Max point')}}</th>
            <th class="point">{{trans('sales::view.Min point')}}</th>
            <th class="check">{{trans('sales::view.Check')}}
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" name="team[4]" id="checkAllQuestion">
                    </div>
                </label>
            </th>
        </tr>
   </table>
  </td>
 </tr>
<tr>
<td>
    <div style="width:100%; height:300px; overflow:auto; margin-top: -21px;">
     <table class="table table-bordered table-hover dataTable"  width="100%" >
       <tr>
            <td class="No"></td>
            <td colspan="5"><strong>OSDC</strong></td>
            <td class="check">
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='1' parent-id='0' class="checkItemQuestion">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td class="No"></td>
            <td colspan="5">-- <strong>Kỹ năng, năng lực</strong></td>
           <td class="check">
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='3' parent-id='1' class="checkItemQuestion">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td class="No">1</td>
            <td class="question">---- Năng lực của nhân viên công ty cung cấp cho các bạn phù hợp chứ?</td>
            <td class="point">12</td>
            <td class="point">99</td>
            <td class="point">100</td>
            <td class="point">98</td>
            <td class="check">
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='4' parent-id='3' class="checkItemQuestion">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td class="No">2</td>
            <td class="question">---- Kỹ năng về kỹ thuật của nhân viên công ty cung cấp cho các bạn phù hợp chứ (khả năng phân tích yêu cầu, thiết kế, coding,..)</td>
            <td class="point">12</td>
            <td class="point">99</td>
            <td class="point">100</td>
            <td class="point">98</td>
            <td class="check">
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='5' parent-id='3' class="checkItemQuestion">
                    </div>
                </label>
            </td>
        </tr>
        <tr>
            <td class="No">3</td>
            <td class="question">---- Kỹ năng về kỹ thuật của nhân viên công ty cung cấp cho các bạn phù hợp chứ (khả năng phân tích yêu cầu, thiết kế, coding,..)</td>
            <td class="point">12</td>
            <td class="point">99</td>
            <td class="point">100</td>
            <td class="point">98</td>
            <td class="check">
                <label class="label-normal">
                    <div class="icheckbox">
                        <input type="checkbox" data-id='6' parent-id='3' class="checkItemQuestion">
                    </div>
                </label>
            </td>
        </tr>
     </table>  
   </div>
  </td>
 </tr>
</table>

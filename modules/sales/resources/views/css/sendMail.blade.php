<?php
    if($css->project_type_id === 1){
        $project_type = trans('sales::view.Project OSDC name');
    }else {
        $project_type = trans('sales::view.Project base name');
    }    
?>
<p>{{trans('sales::view.Email hello',["sale" => $employee->name])}} </p>

<p>{{trans('sales::view.Email notifical') }} </p>
<p>{{trans('sales::view.Email company name',["company" => $css->company_name])}} </p>
<p>{{trans('sales::view.Email make name', ['make_name' => $cssResult['name']]) }} </p>
<p>{{trans('sales::view.Email make date',["make_date" => date('d/m/Y', strtotime($cssResult['created_at']))])}} </p>

<p>{{trans('sales::view.Email project name', ['project_type' => $project_type, 'project_name' => $css->project_name]) }} </p>
<p>{{trans('sales::view.Email project date',["project_date" => date('d/m/Y', strtotime($css->start_date)) . ' - ' . date('d/m/Y', strtotime($css->end_date))])}} </p>
<p>{{trans('sales::view.Email pm name', ['pm_name' => $css->pm_name]) }} </p>

<p>{!! trans('sales::view.Email point', ['point' => $cssResult['avg_point']]) !!} </p>
<p></p>
<p>{{trans('sales::view.Email text view detail CSS') }} </p>
<p><a href="{{$href}}" target="_blank">{{$href}}</a></p>

<p>{{trans('sales::view.Email respect')}}</p>
<p>{{trans('sales::view.Product team')}}</p>
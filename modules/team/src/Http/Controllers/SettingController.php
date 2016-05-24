<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;

class SettingController extends TeamBaseController
{
    /**
     * setting index
     * 
     * @return view
     */
    public function index()
    {
        Breadcrumb::add('Setting');
        Breadcrumb::add('Team');
        return view('team::setting.index');
    }
}

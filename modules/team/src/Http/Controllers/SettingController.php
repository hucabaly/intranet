<?php

namespace Rikkei\Team\Http\Controllers;

use Rikkei\Core\View\Breadcrumb;

class SettingController extends \Rikkei\Core\Http\Controllers\Controller
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

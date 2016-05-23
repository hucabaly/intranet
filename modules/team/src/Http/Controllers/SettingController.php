<?php

namespace Rikkei\Team\Http\Controllers;

class SettingController extends TeamBaseController
{
    /**
     * setting index
     * 
     * @return view
     */
    public function index()
    {
        return view('team::setting.index');
    }
}

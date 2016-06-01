<?php

namespace Rikkei\Team\Http\Controllers;

class PositionController extends TeamBaseController
{
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Setting');
        Breadcrumb::add('Role', URL::route('team::setting.role.index'));
    }
}


<?php

namespace Rikkei\Team\Http\Controllers;

use URL;
use Rikkei\Core\View\Breadcrumb;

/**
 * class Team module base
 */
class TeamBaseController extends \Rikkei\Core\Http\Controllers\Controller
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        Breadcrumb::add('Home', URL::to('/'), '<i class="fa fa-dashboard"></i>');
        $this->_construct();
    }
    
    /**
     * constructor callback
     * 
     * @return \Rikkei\Core\Http\Controllers\AuthController
     */
    protected function _construct()
    {
        return $this;
    }
}
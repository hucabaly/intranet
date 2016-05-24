<?php

namespace Rikkei\Team\Http\Controllers;

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
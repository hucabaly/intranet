<?php
namespace Rikkei\Team\View;

use Auth;
use Route;
use Config;
use Rikkei\Team\Model\Permissions;

/**
 * class permission
 * 
 * check permssion auth
 */
class Permission
{
    /**
     *  store this object
     * @var object
     */
    protected static $instance;
    
    /**
     * store user current logged
     * @var model
     */
    protected $employee;
    /**
     * store rules of current user
     * @var array
     */
    protected $rules;
    
    /**
     * contructor
     */
    public function __construct() 
    {
        $this->initEmployee();
        $this->initRules();
    }

    /**
     * init User loggined
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public function initEmployee()
    {
        if (! $this->employee) {
            $this->employee = Auth::user()->getEmployee();
        }
        return $this;
    }
    
    /**
     * init Rules
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public function initRules()
    {
        if ($this->isRoot()) {
            return $this;
        }
        if (! $this->rules) {
            $this->rules = $this->employee->getPermission();
            if (! $this->rules) {
                $this->rules = ['checked' => true];
            }
        }
        return $this;
    }
    
    /**
     * get scopes of teams in a route
     * 
     * @param int $teamId
     * @param string|int|null $routeOrActionId
     * @return int|array
     */
    public function getScopeCurrentOfTeam($teamId = null, $routeOrActionId = null)
    {
        if ($this->isRoot()) {
            return Permissions::SCOPE_COMPANY;
        }
        if (! $routeOrActionId) {
            $routeCurrent = Route::getCurrentRoute()->getName();
        } else {
            $routeCurrent = $routeOrActionId;
        }
        if (! $this->rules || ! isset($this->rules['team'])) {
            return Permissions::SCOPE_NONE;
        }
        $scopes = [];
        //if route current is number: check action id
        if (is_numeric($routeCurrent)) {
            $rulesTeam = $this->rules['team']['action'];
        } else { //if route current is string: check route name
            $rulesTeam = $this->rules['team']['route'];
        }
        foreach ($rulesTeam as $teamIdRule => $rules) {
            if ($teamId && $teamId != $teamIdRule) {
                continue;
            }
            foreach ($rules as $routePermission => $scope) {
                //check all permission
                if ($routePermission == '*') {
                    $routePermission = '.*';
                }
                $flagCheck = false; //search route action
                if (is_numeric($routeCurrent)) {
                    if ($routeCurrent == $routePermission) {
                        $flagCheck = true;
                    }
                } else {
                    if (preg_match('/' . $routePermission . '/', $routeCurrent)) {
                        $flagCheck = true;
                    }
                }
                if ($flagCheck) {
                    if ($teamId) {
                        return $scope;
                    }
                    $scopes[$teamIdRule] = $scope;
                }
            }
        }
        
        if (! $scopes) {
            return Permissions::SCOPE_NONE;
        }
        //get first element
        if (count($scopes) == 1) {
            return reset($scopes);
        }
        return $scopes;
    }

    /**
     * get scopes of roles in a route
     * 
     * @param string|int|null $routeOrActionId
     * @return int
     */
    public function getScopeCurrentOfRole($routeOrActionId = null)
    {
        if ($this->isRoot()) {
            return Permissions::SCOPE_COMPANY;
        }
        if (! $routeOrActionId) {
            $routeCurrent = Route::getCurrentRoute()->getName();
        } else {
            $routeCurrent = $routeOrActionId;
        }
        if (! $this->rules || ! isset($this->rules['role'])) {
            return Permissions::SCOPE_NONE;
        }
        $scopeResult = 0;
        //if route current is number: check action id
        if (is_numeric($routeCurrent)) {
            $rulesRole = $this->rules['role']['action'];
        } else { //if route current is string: check route name
            $rulesRole = $this->rules['role']['route'];
        }
        foreach ($rulesRole as $routePermission => $scope) {
            if ($routePermission == '*') {
                $routePermission = '.*';
            }
            $flagCheck = false; //search route action
            if (is_numeric($routeCurrent)) {
                if ($routeCurrent == $routePermission) {
                    $flagCheck = true;
                }
            } else {
                if (preg_match('/' . $routePermission . '/', $routeCurrent)) {
                    $flagCheck = true;
                }
            }
            if ($flagCheck) {
                return $scope;
            }
        }
        return Permissions::SCOPE_NONE;
    }
    
    /**
     * check allow access to route current
     * 
     * @param string|null $route route name
     * @return boolean
     */
    public function isAllow($route = null)
    {
        if ($this->isRoot()) {
            return true;
        }
        if ($this->isScopeNone(null, $route)) {
            return false;
        }
        return true;
    }
    
    /**
     * check is scope none
     * 
     * @param int $teamId
     * @param string|int|null $route route name
     * @return boolean
     */
    public function isScopeNone($teamId = null, $route = null)
    {
        if ($this->isRoot()) {
            return false;
        }
        if ($this->getScopeCurrentOfRole($route) != Permissions::SCOPE_NONE) {
            return false;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam != Permissions::SCOPE_NONE) {
                return false;
            }
            return true;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope != Permissions::SCOPE_NONE) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * check is scope self
     * 
     * @param int $teamId
     * @param string|null $route route name
     * @return boolean
     */
    public function isScopeSelf($teamId = null, $route = null)
    {
        if ($this->getScopeCurrentOfRole($route) == Permissions::SCOPE_SELF) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == Permissions::SCOPE_SELF) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == Permissions::SCOPE_SELF) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * check is scope team
     * 
     * @param int $teamId
     * @param string|null $route route name
     * @return boolean
     */
    public function isScopeTeam($teamId = null, $route = null)
    {
        if ($this->getScopeCurrentOfRole($route) == Permissions::SCOPE_TEAM) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == Permissions::SCOPE_TEAM) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == Permissions::SCOPE_TEAM) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * check is scope company
     *
     * @param int $teamId
     * @param string|null $route route name
     * @return boolean
     */
    public function isScopeCompany($teamId = null, $route = null)
    {
        if ($this->isRoot()) {
            return true;
        }
        if ($this->getScopeCurrentOfRole($route) == Permissions::SCOPE_COMPANY) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == Permissions::SCOPE_COMPANY) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == Permissions::SCOPE_COMPANY) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * get root account from file .env
     * 
     * @return string|null
     */
    public function getRootAccount()
    {
        return trim(Config('services.account_root'));
    }
    
    /**
     * check current user is root
     * 
     * @return boolean
     */
    public function isRoot()
    {
        if ($this->getRootAccount() == $this->employee->email) {
            return true;
        }
        return false;
    }
    
    /**
     * get employee current
     * 
     * @return null|model
     */
    public function getEmployee()
    {
        return $this->employee;
    }
    
    /**
     * get permission of employee current
     * 
     * @return array|null
     */
    public function getPermission()
    {
        return $this->rules;
    }
    /**
     * Singleton instance
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }
    
}

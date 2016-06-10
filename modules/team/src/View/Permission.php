<?php
namespace Rikkei\Team\View;

use Auth;
use Rikkei\Team\Model\TeamRule;
use Route;
use Config;
use Rikkei\Core\Model\User;

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
        return $this;
        if (! self::$rules) {
            self::$rules = self::$user->getAcl();
            if (! self::$rules) {
                self::$rules = ['checked' => true];
            }
        }
        return $this;
    }
    
    /**
     * get scopes of teams in a route
     * 
     * @param int $teamId
     * @param string|null $route route name
     * @return int|array
     */
    public function getScopeCurrentOfTeam($teamId = null, $route = null)
    {
        return $this;
        if (! $route) {
            $routeCurrent = Route::getCurrentRoute()->getName();
        } else {
            $routeCurrent = $route;
        }
        if (! self::$rules || ! isset(self::$rules['team'])) {
            return TeamRule::SCOPE_NONE;
        }
        $scopes = [];
        foreach (self::$rules['team'] as $teamIdRule => $rules) {
            if ($teamId && $teamId != $teamIdRule) {
                continue;
            }
            foreach ($rules as $routeAcl => $scope) {
                if ($routeAcl == '*') {
                    $routeAcl = '.*';
                }
                if (preg_match('/' . $routeAcl . '/', $routeCurrent)) {
                    if ($teamId) {
                        return $scope;
                    }
                    $scopes[$teamIdRule] = $scope;
                }
            }
        }
        if (! $scopes) {
            return TeamRule::SCOPE_NONE;
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
     * @param string|null $route route name
     * @return int
     */
    public function getScopeCurrentOfRole($route = null)
    {
        return $this;
        if (! $route) {
            $routeCurrent = Route::getCurrentRoute()->getName();
        } else {
            $routeCurrent = $route;
        }
        if (! self::$rules || ! isset(self::$rules['role'])) {
            return TeamRule::SCOPE_NONE;
        }
        $scopeResult = 0;
        foreach (self::$rules['role'] as $roleRuleId => $rules) {
            foreach ($rules as $routeAcl => $scope) {
                $scope = (int) $scope;
                if ($routeAcl == '*') {
                    $routeAcl = '.*';
                }
                if (preg_match('/' . $routeAcl . '/', $routeCurrent)) {
                    if ($scope == TeamRule::SCOPE_COMPANY) {
                        return TeamRule::SCOPE_COMPANY;
                    }
                    if ($scope > $scopeResult) {
                        $scopeResult = $scope;
                    }
                }
            }
        }
        return $scopeResult;
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
     * @param string|null $route route name
     * @return boolean
     */
    public function isScopeNone($teamId = null, $route = null)
    {
        if ($this->isRoot()) {
            return false;
        }
        if ($this->getScopeCurrentOfRole($route) != TeamRule::SCOPE_NONE) {
            return false;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam != TeamRule::SCOPE_NONE) {
                return false;
            }
            return true;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope != TeamRule::SCOPE_NONE) {
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
        if ($this->getScopeCurrentOfRole($route) == TeamRule::SCOPE_SELF) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == TeamRule::SCOPE_SELF) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == TeamRule::SCOPE_SELF) {
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
        if ($this->getScopeCurrentOfRole($route) == TeamRule::SCOPE_TEAM) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == TeamRule::SCOPE_TEAM) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == TeamRule::SCOPE_TEAM) {
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
        if ($this->getScopeCurrentOfRole($route) == TeamRule::SCOPE_COMPANY) {
            return true;
        }
        $scopeTeam = $this->getScopeCurrentOfTeam($teamId, $route);
        // scope team is int
        if (is_numeric($scopeTeam)) {
            if ($scopeTeam == TeamRule::SCOPE_COMPANY) {
                return true;
            }
            return false;
        }
        // scope team is array
        foreach ($scopeTeam as $scope) {
            if ($scope == TeamRule::SCOPE_COMPANY) {
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

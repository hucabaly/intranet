<?php
namespace Rikkei\Team\View;

use Rikkei\Team\Model\User;
use Auth;
use Rikkei\Team\Model\TeamRule;
use Session;
use Route;

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
    protected static $user;
    /**
     * store rules of current user
     * @var array
     */
    protected static $rules;
    
    /**
     * contructor
     */
    public function __construct() 
    {
        $this->initUser();
        $this->initRules();
    }

    /**
     * init User loggined
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public function initUser()
    {
        $id = Auth::user()->id;
        self::$user = User::find($id);
        return $this;
    }
    
    /**
     * init Rules
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public function initRules()
    {
        if (Session::has('permission.rules')) {
            self::$rules = Session::get('permission.rules');
            self::$rules = self::$rules[0];
            return $this;
        }
        self::$rules = self::$user->getAcl();
        if (! self::$rules) {
            self::$rules = ['checked' => true];
        }
        Session::push('permission.rules', self::$rules);
        return $this;
    }
    
    /**
     * get scopes current of teams 
     * 
     * @param int $teamId
     * @return int|array
     */
    public function getScopeCurrent($teamId = null)
    {
        $routeCurrent = Route::getCurrentRoute()->getName();
        if (! self::$rules || ! isset(self::$rules['team'])) {
            return TeamRule::SCOPE_NONE;
        }
        $scopes = [];
        foreach (self::$rules['team'] as $teamIdRule => $rules) {
            if ($teamId && $teamId != $teamIdRule) {
                continue;
            }
            foreach ($rules as $route => $scope) {
                if ($route == '*') {
                    $route = '.*';
                }
                if (preg_match('/' . $route . '/', $routeCurrent)) {
                    if ($teamId) {
                        return $scope;
                    }
                    $scopes[$teamIdRule] = $scope;
                }
            }
        }
        return $scopes;
    }
    
    /**
     * get scopes current of roles
     * 
     * @param int $teamId
     * @return int|array
     */
    public function getScopeCurrentRole()
    {
        $routeCurrent = Route::getCurrentRoute()->getName();
        if (! self::$rules || ! isset(self::$rules['role'])) {
            return TeamRule::SCOPE_NONE;
        }
        foreach (self::$rules['role'] as $roleRuleId => $rules) {
            foreach ($rules as $route => $scope) {
                if ($route == '*') {
                    $route = '.*';
                }
                if (preg_match('/' . $route . '/', $routeCurrent)) {
                    return $scope;
                }
            }
        }
        return TeamRule::SCOPE_NONE;
    }
    
    /**
     * check allow access to route current
     * 
     * @param int $teamId
     * @return boolean
     */
    public function isAllow($teamId = null)
    {
        //check scope role
        $scopeCurrentRole = $this->getScopeCurrentRole();
        if ($scopeCurrentRole !=TeamRule::SCOPE_NONE) {
            return true;
        }
        //check scope team
        $scopeCurrent = $this->getScopeCurrent($teamId);
        if (is_numeric($scopeCurrent)) { //exsits teamId
            if ($scopeCurrent == TeamRule::SCOPE_NONE) {
                return false;
            }
            return true;
        }
        foreach ($scopeCurrent as $teamId => $scope) {
            if ($scope != TeamRule::SCOPE_NONE) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * check is scope none of a team
     * 
     * @param int $teamId
     * @return boolean
     */
    public function isScopeNone($teamId)
    {
        if ($this->getScopeCurrent($teamId) == TeamRule::SCOPE_NONE) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope self
     * 
     * @param int $teamId
     * @return boolean
     */
    public function isScopeSelf($teamId)
    {
        if($this->getScopeCurrent($teamId) == TeamRule::SCOPE_SELF) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope team
     * 
     * @param int $teamId
     * @return boolean
     */
    public function isScopeTeam($teamId)
    {
        if($this->getScopeCurrent($teamId) == TeamRule::SCOPE_TEAM) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope company
     *
     * @param int $teamId
     * @return boolean
     */
    public function isScopeCompany($teamId)
    {
        if($this->getScopeCurrent($teamId) == TeamRule::SCOPE_COMPANY) {
            return true;
        }
        return false;
    }
    
    /**
     * flush session permission
     * 
     * @return \Rikkei\Team\View\Permission
     */
    public function flushPermission()
    {
        Session::forget('permission');
        return $this;
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

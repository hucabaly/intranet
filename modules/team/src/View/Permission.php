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
    
    public function __construct() {
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
        Session::push('permission.rules', self::$rules);
        return $this;
    }
    
    public function getScopeCurrent()
    {
        $routeCurrent = Route::getCurrentRoute()->getName();
        foreach (self::$rules as $route => $scope) {
            if (preg_match('/' . $route . '/', $routeCurrent)) {
                return $scope;
            }
        }
        return TeamRule::SCOPE_NONE;
    }
    
    public function isAllow()
    {
        if($this->isScopeNone()) {
            return false;
        }
        return true;
    }
    
    /**
     * check is scope none
     * 
     * @return boolean
     */
    public function isScopeNone()
    {
        if($this->getScopeCurrent() == TeamRule::SCOPE_NONE) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope self
     * 
     * @return boolean
     */
    public function isScopeSelf()
    {
        if($this->getScopeCurrent() == TeamRule::SCOPE_SELF) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope team
     * 
     * @return boolean
     */
    public function isScopeTeam()
    {
        if($this->getScopeCurrent() == TeamRule::SCOPE_TEAM) {
            return true;
        }
        return false;
    }
    
    /**
     * check is scope company
     * 
     * @return boolean
     */
    public function isScopeCompany()
    {
        if($this->getScopeCurrent() == TeamRule::SCOPE_COMPANY) {
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
        Session::flush('permission');
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

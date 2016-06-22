<?php
namespace Rikkei\Team\View;

use Rikkei\Team\Model\Permissions;
use Rikkei\Team\Model\Action;

class Acl
{
    /**
     *
     * @var array
     */
    protected static $scopeFind = [];
    
    protected static $actionList;
    
    /**
     * get Acl List Data
     * 
     * @return array
     */
    public static function getAclList()
    {
        if (! self::$actionList) {
            self::$actionList = Action::getListData();
        }
        return self::$actionList;
    }
    
    /**
     * get routes of key rule
     * 
     * @param string $key
     * @return array
     */
    public static function getRoutesNameFromKey($key)
    {
        $keyAclArray = explode(':', $key);
        $aclData = self::getAclData();
        foreach ($keyAclArray as $keyAcl) {
            if (isset($aclData[$keyAcl])) {
                $aclData = $aclData[$keyAcl];
            }
            if (isset($aclData['child'])) {
                $aclData = $aclData['child'];
            }
        }
        if (isset($aclData['routes'])) {
            if (! is_array($aclData['routes'])) {
                return [$aclData['routes']];
            }
            return $aclData['routes'];
        }
        return [];
    }
    
    /**
     * 
     * @param type $collection
     * @param type $rule
     * @param type $positionId
     * @return int|boolean
     */
    public static function findScope($collection, $actionId, $roleId)
    {
        if (isset(self::$scopeFind[$actionId . '-r-' . $roleId])) {
            return self::$scopeFind[$actionId . '-r-' . $roleId];
        }
        if (isset(self::$scopeFind['flag_checked'])) {
            return Permissions::SCOPE_NONE;
        }
        if (! $collection && ! count($collection)) {
            return Permissions::SCOPE_NONE;
        }
        foreach ($collection as $item) {
            if (! $item->scope) {
                $scope = Permissions::SCOPE_NONE;
            } else {
                $scope = (int) $item->scope;
            }
            self::$scopeFind[$item->action_id . '-r-' . $item->role_id] = $scope;
        }
        self::$scopeFind['flag_checked'] = true;
        if (isset(self::$scopeFind[$actionId . '-r-' . $roleId])) {
            return self::$scopeFind[$actionId . '-r-' . $roleId];
        }
        return Permissions::SCOPE_NONE;
    }
    
    /**
     * get all route acl
     * 
     * @return array
     */
    public static function getAclRoutes()
    {
        $routes = [];
        $aclData = self::getAclData();
        self::getAclRoutesRecursive($aclData, $routes);
        return $routes;
    }
     
    /**
     * get all route acl call recursive
     * 
     * @param array $aclData
     * @param array $routes
     * @return array
     */
    protected static function getAclRoutesRecursive($aclData, &$routes)
    {
        if (! $aclData || ! count($aclData)) {
            return;
        }
        foreach ($aclData as $aclValue) {
            if (isset($aclValue['routes'])) {
                if (is_array($aclValue['routes']) && count($aclValue['routes'])) {
                    foreach ($aclValue['routes'] as $route) {
                        $routes[] = $route;
                    }
                } else {
                    $routes[] = $aclValue['routes'];
                }
            }
            if (isset($aclData['child'])) {
                self::getAclRoutesRecursive($aclData['child'], $routes);
            }
        }
    }
    
    /**
     * get all key acl
     * 
     * @return array
     */
    public static function getAclKey()
    {
        $keys = [];
        $aclData = self::getAclData();
        foreach ($aclData as $key => $aclValue) {
            if (isset($aclValue['child'])) {
                foreach ($aclValue['child'] as $aclItemKey => $aclItem) {
                    $keys[] = $key . ':' . $aclItemKey;
                }
            }
        }
        return $keys;
    }
}

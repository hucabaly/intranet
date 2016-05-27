<?php
namespace Rikkei\Team\View;

class Acl
{
    /**
     *
     * @var array
     */
    protected static $scopeFind = [];
    
    protected static $config;
    /**
     * get Acl data
     * 
     * @return array
     */
    public static function getAclData()
    {
        if (! self::$config) {
            self::$config = \Illuminate\Support\Facades\Config::get('acl');
        }
        return self::$config;
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
    public static function findScope($collection, $rule, $positionId)
    {
        if (isset(self::$scopeFind[$rule . '-r-' . $positionId])) {
            return self::$scopeFind[$rule . '-r-' . $positionId];
        }
        if (isset(self::$scopeFind['flag_checked'])) {
            return false;
        }
        if (! $collection && ! count($collection)) {
            return false;
        }
        foreach ($collection as $item) {
            self::$scopeFind[$item->rule . '-r-' . $item->position_id] = $item->scope;
        }
        self::$scopeFind['flag_checked'] = true;
        if (isset(self::$scopeFind[$rule . '-r-' . $positionId])) { 
            return self::$scopeFind[$rule . '-r-' . $positionId];
        }
        return false;
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

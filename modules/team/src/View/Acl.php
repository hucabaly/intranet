<?php
namespace Rikkei\Team\View;

class Acl
{
    /**
     *
     * @var array
     */
    protected $scopeFind;
    /**
     * get Acl data
     * 
     * @return array
     */
    public static function getAclData()
    {
        return \Illuminate\Support\Facades\Config::get('acl');
    }
    
    /**
     * 
     * @param type $collection
     * @param type $rule
     * @param type $positionId
     * @return int|boolean
     */
    public static function findScope(&$collection, $rule, $positionId)
    {
        if (! $collection && ! count($collection)) {
            return false;
        }
        foreach ($collection as $key => &$item) {
            if ($item->rule == $rule && $item->position_id == $positionId) {
                $scope = $item->scope;
                $collection->forget($key); //remove item collection
                return $scope;
            }
        }
        return false;
    }
}

<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;

class Action extends CoreModel
{
    
    protected $table = 'actions';
    
    /**
     * get action list
     * 
     * @return array
     */
    public static function getListData()
    {
        $actionTree = [];
        //$actions = self::getTreeListRecursive(null, $actionTree);
        $actionsGroup = self::select('id', 'description')
            ->where('parent_id', null)
            ->orderBy('sort_order')
            ->get();
        if (! count($actionsGroup)) {
            return;
        }
        foreach ($actionsGroup as $actionGroup) {
            $actionTree[$actionGroup->id] = [
                'description' => $actionGroup->description,
            ];
            $actionsGroupChild = self::select('id', 'description')
                ->where('parent_id', $actionGroup->id)
                ->orderBy('sort_order')
                ->get();
            if (! count($actionsGroupChild)) {
                continue;
            }
            foreach ($actionsGroupChild as $actionGroupChild) {
                $actionTree[$actionGroup->id]['child'][$actionGroupChild->id] = [
                    'description' => $actionGroupChild->description,
                ];
            }
        }
        return $actionTree;
    }
    
    /**
     * get route to action ids
     * 
     * @param array $actionIds
     * @return array
     */
    public static function getRouteChildren($actionIds)
    {
        $result = [];
        if (! is_array($actionIds)) {
            $actionIds = array ($actionIds);
        }
        $routes = self::select('route', 'id', 'parent_id')
            ->orWhereIn('id', $actionIds)
            ->orWhereIn('parent_id', $actionIds)
            ->where('route' , '<>', null)
            ->where('route' , '<>', '')
            ->get();
        if (! count($routes)) {
            return $result;
        }
        foreach ($routes as $route) {
            if (! $route->route) {
                continue;
            }
            $result[$route->route] = [
                'id' => $route->id,
                'parent_id' => $route->parent_id,
            ];
        }
        return $result;
    }
}

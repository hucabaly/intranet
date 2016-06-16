<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use DB;

class Action extends CoreModel
{
    
    use SoftDeletes;
    
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
            if (isset($result[$route->route]) && $result[$route->route]) {
                continue;
            }
            $result[$route->route] = [
                'id' => $route->id,
                'parent_id' => $route->parent_id,
            ];
        }
        return $result;
    }
    
    /**
     * get collection to show grid data
     * 
     * @return collection model
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','parent_id','route', 'name', 'description', 'sort_order')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    /**
     * get collection to option
     * 
     * @param boolean $nullable
     * @return array
     */
    public static function toOption($nullable = true, $translate = true)
    {
        $options = [];
        if ($nullable) {
            $options[] = [
                'value' => '',
                'label' => ''
            ];
        }
        self::toOptionRecursive($options, null, 0, $translate);
        return $options;
    }
    
    /**
     * 
     * @param artay $options
     * @param int|null $parentId
     * @param int $level
     */
    public static function toOptionRecursive(&$options, $parentId = null, $level = 0, $translate = true)
    {
        //only get action level < 2
        if ($level >= 2 ) {
            return ;
        }
        $actions = self::select('id', 'description')
            ->where('description', '<>', null)
            ->where('description', '<>', '')
            ->where('parent_id', $parentId)
            ->get();
        if (! count($actions)) {
            return ;
        }
        $prefixLabel = '';
        for ($i = 0 ; $i < $level ; $i++) {
            $prefixLabel .= ' ---- ';
        }
        foreach ($actions as $action) {
            if ($translate && Lang::has('acl.' . $action->description)) {
                $options[] = [
                    'value' => $action->id,
                    'label' => $prefixLabel . Lang::get('acl.' . $action->description)
                ];
            } else {
                $options[] = [
                    'value' => $action->id,
                    'label' => $prefixLabel . $action->description
                ];
            }
            self::toOptionRecursive($options, $action->id, $level+1);
        }
    }
    
    /**
     * rewrite save
     * 
     * @param array $options
     */
    public function save(array $options = array()) {
        if ($this->route) {
            $actionRouteSame = self::select(DB::raw('COUNT(*) as count')) ->where('route', $this->route)->first();
            if ($actionRouteSame->count) {
                throw new Exception(Lang::get('team::view.Route data exists, please fill another route'));
            }
        }
        try {
            Employees::flushCache();
            return parent::save($options);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

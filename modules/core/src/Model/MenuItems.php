<?php

namespace Rikkei\Core\Model;

use DB;
use Rikkei\Team\View\Config;
use Lang;
use Exception;
use Rikkei\Core\View\CacheHelper;

class MenuItems extends CoreModel
{
    const STATE_ENABLE  = 1;
    const STATE_DISABLE = 0;
    
    public $timestamps = false;
    
    const KEY_CACHE = 'menu_items';


    /**
     * count child of menu item
     * 
     * @return count
     */
    public function hasChild()
    {
        $child = self::select(DB::raw('count(*) as count'))
            ->where('parent_id', $this->id)
            ->first();
        return $child->count;
    }
    
    /**
     * get collection to show grid data
     * 
     * @return collection model
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','name', 'parent_id', 'menu_id', 'action_id', 'url')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    /**
     * get option state
     * 
     * @return array
     */
    public static function toOptionState()
    {
        return [
            [
                'value' => self::STATE_DISABLE,
                'label' => Lang::get('core::view.Disable')
            ],
            [
                'value' => self::STATE_ENABLE,
                'label' => Lang::get('core::view.Active')
            ]
        ];
    }
    
    
    /**
     * get collection to option
     * 
     * @param null|int $skipId
     * @param boolean $nullable
     * @return array
     */
    public static function toOption($skipId = null, $nullable = true)
    {
        $options = [];
        if ($nullable) {
            $options[] = [
                'value' => '',
                'label' => ''
            ];
        }
        self::toOptionRecursive($options, null, $skipId, 0);
        return $options;
    }
    
    /**
     * collection to option recursive
     * 
     * @param artay $options
     * @param int|null $parentId
     * @param null|int $skipId
     * @param int $level
     */
    public static function toOptionRecursive(&$options, $parentId = null, $skipId = null, $level = 0)
    {
        $menuItem = self::select('id', 'name')
            ->where('parent_id', $parentId)
            ->where('id', '<>', $skipId)
            ->get();
        if (! count($menuItem)) {
            return ;
        }
        $prefixLabel = '';
        for ($i = 0 ; $i < $level ; $i++) {
            $prefixLabel .= ' ---- ';
        }
        foreach ($menuItem as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => $prefixLabel . $item->name
            ];
            self::toOptionRecursive($options, $item->id, $skipId, $level+1);
        }
    }
    
    /**
     * rewrite save
     * 
     * @param array $options
     */
    public function save(array $options = array()) {
        if ($this->parent_id) {
            $parentMenu = MenuItems::find($this->parent_id);
            $this->menu_id  = $parentMenu->menu_id;
        } else {
            $this->parent_id = null;
        }
        if (! $this->action_id) {
            $this->action_id = null;
        }
        try {
            $result = parent::save($options);
            CacheHelper::forget(self::KEY_CACHE);
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * rewrite delete model
     */
    public function delete() {
        try {
            $menuItemChildren = MenuItems::where('parent_id', $this->id)
                ->get();
            if (count($menuItemChildren)) {
                foreach ($menuItemChildren as $item) {
                    $item->delete();
                }
            }
            CacheHelper::forget(self::KEY_CACHE);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * get child of menu item
     * 
     * @param int $parentId
     * @param int $menuGroupId
     */
    public static function getChildMenuItems($parentId, $menuGroupId)
    {
        if ($menuItemsCache = CacheHelper::get(self::KEY_CACHE)) {
            return $menuItemsCache;
        }
        $menuItems = MenuItems::where('menu_id', $menuGroupId)
            ->where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->get();
        CacheHelper::put(self::KEY_CACHE, $menuItems);
        return $menuItems;
    }
}

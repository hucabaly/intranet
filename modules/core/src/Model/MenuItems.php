<?php

namespace Rikkei\Core\Model;

use DB;
use Rikkei\Team\View\Config;
use Lang;
use Exception;

class MenuItems extends CoreModel
{
    const STATE_ENABLE  = 1;
    const STATE_DISABLE = 0;

    public $timestamps = false;
    
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
        }
        if (! $this->action_id) {
            $this->action_id = null;
        }
        try {
            return parent::save($options);
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
            return parent::delete();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}

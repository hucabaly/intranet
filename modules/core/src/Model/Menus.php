<?php

namespace Rikkei\Core\Model;

use Rikkei\Team\View\Config;
use Lang;
use Exception;
use DB;
use Rikkei\Core\Model\MenuItems;
use Illuminate\Support\Facades\Cache;

/**
 * Menus object
 */
class Menus extends CoreModel
{
    const FLAG_DISABLE = 0;
    const FLAG_ACTIVE = 1;
    const FLAG_MAIN = 2;
    const FLAG_SETTING = 3;
    
    protected $table = 'menus';
    
    public $timestamps = false;
    
    /**
     * get menu default
     * 
     * @return model
     */
    public static function getMenuDefault()
    {
        $keyCache = self::getKeyCache();
        if (Cache::has($keyCache)) {
            return Cache::get($keyCache);
        }
        $menu = self::where('state', self::FLAG_MAIN)
            ->first();
        Cache::put($keyCache, $menu, self::$timeStoreCache);
        return $menu;
    }
    
    /**
     * get menu default
     * 
     * @return model
     */
    public static function getMenuSetting()
    {
        $keyCache = self::getKeyCache();
        if (Cache::has($keyCache)) {
            return Cache::get($keyCache);
        }
        $menu = self::where('state', self::FLAG_SETTING)
            ->first();
        Cache::put($keyCache, $menu, self::$timeStoreCache);
        return $menu;
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
                'value' => self::FLAG_DISABLE,
                'label' => Lang::get('core::view.Disable')
            ],
            [
                'value' => self::FLAG_ACTIVE,
                'label' => Lang::get('core::view.Active')
            ],
            [
                'value' => self::FLAG_MAIN,
                'label' => Lang::get('core::view.Main menu')
            ],
            [
                'value' => self::FLAG_SETTING,
                'label' => 'Setting menu'
            ],
        ];
    }
    
    /**
     * get collection to show grid data
     * 
     * @return collection model
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','name')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    /**
     * rewrite save
     * 
     * @param array $options
     */
    public function save(array $options = [])
    {
        DB::beginTransaction();
        try {
            // set state model to flag active
            if ($this->state == self::FLAG_MAIN) {
                self::where('state', self::FLAG_MAIN)
                    ->where('id', '<>', $this->id)
                    ->update([
                        'state' => self::FLAG_ACTIVE
                    ]);
            } elseif ($this->state == self::FLAG_SETTING) {
                self::where('state', self::FLAG_SETTING)
                    ->where('id', '<>', $this->id)
                    ->update([
                        'state' => self::FLAG_ACTIVE
                    ]);
            }
            self::flushCache();
            parent::save($options);
            Db::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * count menu item
     */
    public function countMenuItem()
    {
        $items = MenuItems::select(DB::raw('COUNT(*) AS count'))
            ->where('menu_id', $this->id)
            ->first();
        return $items->count;
    }
    
    /**
     * rewrite delete
     */
    public function delete() {
        $count = $this->countMenuItem();
        if ($count) {
            $message = Lang::get("core::view.This menu group has :number items, can't delete", ['number' => $count]);
            throw new Exception($message);
        }
        try {
            return parent::delete();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * to option array
     * 
     * @return array
     */
    public static function toOption($nullable = false)
    {
        $menus = self::select('id', 'name')
            ->where('state', '<>', self::FLAG_DISABLE)
            ->orderBy('name')
            ->get();
        $options = [];
        if ($nullable) {
            $options[] = [
                'value' => '',
                'label' => '',
            ];
        }
        if (count($menus)) {
            foreach ($menus as $menu) {
                $options[] = [
                    'value' => $menu->id,
                    'label' => $menu->name,
                ];
            }
        }
        return $options;
    }
}

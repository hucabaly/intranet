<?php

namespace Rikkei\Core\Model;

use Rikkei\Team\View\Config;
use Lang;
use Exception;
use DB;

/**
 * Menus object
 */
class Menus extends CoreModel
{
    const FLAG_DISABLE = 0;
    const FLAG_ACTIVE = 1;
    const FLAG_MAIN = 2;
    
    protected $table = 'menus';
    
    public $timestamps = false;
    
    /**
     * get menu default
     * 
     * @return model
     */
    public static function getMenuDefault()
    {
        return self::where('state', self::FLAG_MAIN)
            ->first();
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
            }
            parent::save($options);
            Db::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}

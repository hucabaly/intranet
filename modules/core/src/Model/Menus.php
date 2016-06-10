<?php

namespace Rikkei\Core\Model;

/**
 * Menus object
 */
class Menus extends CoreModel
{
    /**
     * menu name of intranet
     */
    const MENU_DEFAULT = 'Rikkei Intranet';


    protected $table = 'menus';
    
    public $timestamps = false;
    
    /**
     * get menu default
     * 
     * @return model
     */
    public static function getMenuDefault()
    {
        return self::where('name', self::MENU_DEFAULT)
            ->first();
    }
}

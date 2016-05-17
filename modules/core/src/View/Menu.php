<?php
namespace Rikkei\Core\View;

class Menu
{
    /**
     * active menu flag
     * @var string
     */
    protected static $active;
    
    /**
     * set active menu
     * 
     * @param string $menu
     */
    public static function setActive($menu)
    {
        self::$active = $menu;
    }
    
    /**
     * get active menu
     * 
     * @return string
     */
    public static function getActive()
    {
        return self::$active;
    }
    
    /**
     * check menu is active
     * 
     * @param string $menu
     * @return boolean
     */
    public static function isActive($menu)
    {
        if($menu == self::$active) {
            return true;
        }
        return false;
    }
}
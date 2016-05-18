<?php
/** 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
    
    /**
     * get menu html
     * 
     * @return string
     */
    public static function get()
    {
        $menu = config('menu');
        if(!$menu) {
            return;
        }
        return self::getChildMenu($menu);
    }
    
    /**
     * get html menu tree
     *  call recursive
     * 
     * @param array $menu
     * @return string
     */
    protected static function getChildMenu($menu)
    {
        $html = '';
        foreach ($menu as $key => $value) {
            if(!$value['active']) {
                continue;
            }
            $classLi = self::isActive($key) ? ' active' : '';
            $classA = '';
            $optionA = '';
            if (isset($value['child']) && count($value['child'])) {
                $classLi .= ' dropdown';
                $classA .= 'dropdown-toggle';
                $optionA .= ' data-toggle="dropdown"';
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classA = $classA ? " class=\"{$classA}\"" : '';
            $html .= "<li{$classLi}>";
            $html .= "<a href=\"{$value['path']}\"{$classA}{$optionA}>";
            $html .= $value['label'];
            $html .= '</a>';
            if (isset($value['child']) && count($value['child'])) {
                $html .= '<ul class="dropdown-menu" role="menu">';
                $html .= self::getChildMenu($value['child']);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}

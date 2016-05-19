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
        return self::getChildMenu($menu, 0);
    }
    
    /**
     * get html menu tree
     *  call recursive
     * 
     * @param array $menu
     * @return string
     */
    protected static function getChildMenu($menu, $level = 0)
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
                $htmlMenuChild = self::getChildMenu($value['child'], $level+1);
                if ($level > 0) {
                    $classLi .= ' dropdown-submenu';
                }
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classA = $classA ? " class=\"{$classA}\"" : '';
            if($value['path'] != '#') {
                $value['path'] = URL::to($value['path']);
            }
            
            $html .= "<li{$classLi}>";
            $html .= "<a href=\"{$value['path']}\"{$classA}{$optionA}>";
            $html .= $value['label'];
            $html .= '</a>';
            if (isset($value['child']) && count($value['child'])) {
                $html .= '<ul class="dropdown-menu" role="menu">';
                $html .= $htmlMenuChild;
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}

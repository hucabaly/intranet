<?php
/** 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Rikkei\Core\View;

use URL;
use Rikkei\Team\View\Permission;
use Rikkei\Core\Model\Menus;
use Rikkei\Core\Model\MenuItems;

class Menu
{
    /**
     * active menu flag
     * @var string
     */
    protected static $active;
    
    protected static $menuHtml;


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
     * @param int $menuId id of menus
     * @return string
     */
    public static function get($menuId = null)
    {
        if (self::$menuHtml !== null) {
            return self::$menuHtml;
        }
        if (! $menuId) {
            $menuId = Menus::getMenuDefault();
            if(! $menuId) {
                return;
            }
            $menuId = $menuId->id;
        }
        self::$menuHtml = self::getChildMenu($menuId, null, 0);
        return self::$menuHtml;
    }
    
    /**
     * get html menu tree
     *  call recursive
     * 
     * @param array $menu
     * @return string
     */
    protected static function getChildMenu($menuId, $parentId = null, $level = 0)
    {
        $html = '';
        $menuItems = MenuItems::where('menu_id', $menuId)
            ->where('parent_id', $parentId)
            ->get();
        if (! count($menuItems)) {
            return;
        }
        foreach ($menuItems as $item) {
            if ($item->state != MenuItems::STATE_ENABLE) {
                continue;
            }
            //check permission menu of current user logged
            if ($item->action_id) {
                if (! Permission::getInstance()->isAllow($item->action_id)  ) {
                    continue;
                }
            }
            $hasChild = $item->hasChild();
            $classLi = self::isActive($item->id) ? ' active' : '';
            $classA = '';
            $optionA = '';
            if ($hasChild) {
                $classLi .= ' dropdown';
                $classA .= 'dropdown-toggle';
                $optionA .= ' data-toggle="dropdown"';
                $htmlMenuChild = self::getChildMenu($menuId, $item->id, $level+1);
                if ($level > 0) {
                    $classLi .= ' dropdown-submenu';
                }
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classA = $classA ? " class=\"{$classA}\"" : '';
            $urlMenu = '#';
            if($item->url && $item->url != '#') {
                if (preg_match('/^http(s)?:\/\//', $item->url)) {
                    $urlMenu = $item->url;
                } else {
                    $urlMenu = URL::to($item->url);
                }
            }
            
            $html .= "<li{$classLi}>";
            $html .= "<a href=\"{$urlMenu}\"{$classA}{$optionA}>";
            $html .= $item->name;
            $html .= '</a>';
            if ($hasChild && e($htmlMenuChild)) {
                $html .= '<ul class="dropdown-menu" role="menu">';
                $html .= $htmlMenuChild;
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}

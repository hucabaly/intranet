<?php
namespace Rikkei\Core\View;

use URL;

class Breadcrumb
{
    /**
     * breadcrumb first
     * @var array
     */
    protected static $root = array();
    
    /**
     * breacdcrumb
     * @var array
     */
    protected static $path = array();

    public static function root()
    {
        self::$root['root'] = [
            'url' => URL::to('/'),
            'text' => 'Home',
            'pre_text' => '<i class="fa fa-dashboard"></i>',
        ];
        self::$path = array();
    }

    public static function add($key, $text, $url = null, $pre_text = null)
    {
        self::$path[$key] = [
            'url' => $url,
            'text' => $text,
            'pre_text' => $pre_text,
        ];
    }

    public static function get() 
    {
        if(!count(self::$path)) {
            return array();
        }
        if(!self::$root) {
            self::$root['root'] = [
                'url' => URL::to('/'),
                'text' => 'Home',
                'pre_text' => '<i class="fa fa-dashboard"></i>',
            ];
        }
        return array_merge(self::$root, self::$path);
    }
}

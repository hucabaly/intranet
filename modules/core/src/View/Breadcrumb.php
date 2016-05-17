<?php

namespace Rikkei\Core\View;

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
    
    /**
     * Set root node
     */
    public static function root($paths)
    {
        foreach ($paths as list($url, $text, $preText)) {
            self::$root[] = [
                'url' => $url,
                'text' => $text,
                'pre_text' => $preText,
            ];
        }

        self::$path = [];
    }

    /**
     * Add a node
     */
    public static function add($key, $text, $url = null, $pre_text = null)
    {
        self::$path[$key] = [
            'url' => $url,
            'text' => $text,
            'pre_text' => $pre_text,
        ];
    }

    /**
     * Get list nodes to render
     */
    public static function get() 
    {
        return array_merge(self::$root, self::$path);
    }
}

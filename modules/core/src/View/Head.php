<?php
namespace Rikkei\Core\View;

class Head
{
    /**
     * title page
     * @var string
     */
    protected static $title;
    
    /**
     * set title page
     * 
     * @param string $title
     */
    public static function setTitle($title)
    {
        self::$title = $title;
    }
    
    /**
     * get title page
     * 
     * @return string
     */
    public static function getTitle()
    {
        return self::$title;
    }
}

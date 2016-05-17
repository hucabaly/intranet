<?php

namespace Rikkei\Core\View;

class Breadcrumb
{
    /**
     * Set root node
     */
    public static function root();

    /**
     * Add a node
     */
    public static function add();

    /**
     * Get list nodes to render
     */
    public static function get();
}
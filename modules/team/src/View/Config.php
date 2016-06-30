<?php
namespace Rikkei\Team\View;

use Illuminate\Support\Facades\Input;
use Rikkei\Core\View\Form;

class Config
{
    /**
     * get direction class css for current order
     * 
     * @param type $orderKey
     * @return type
     */
    public static function getDirClass($orderKey)
    {
        if (! Form::getFilterPagerData('order') || Form::getFilterPagerData('order') != $orderKey) {
            return '';
        }
        if (Form::getFilterPagerData('dir') == 'asc') {
            return 'sorting_asc';
        }
        return 'sorting_desc';
    }
    
    /**
     * get url sort order
     * 
     * @param type $orderKey
     * @return type
     */
    public static function getDirOrder($orderKey)
    {
        if (! Form::getFilterPagerData('order') || 
            Form::getFilterPagerData('order') != $orderKey || 
            Form::getFilterPagerData('dir') == 'asc') {
            return 'desc';
        }
        return 'asc';
    }
    
    /**
     * get url sort order
     * 
     * @param type $orderKey
     * @return type
     */
    public static function getUrlOrder($orderKey)
    {
        $request = app('request');
        if (! $orderKey) {
            return $request->fullUrl();
        }
        $dir = null;
        if (! Input::get('order') || Input::get('order') != $orderKey || Input::get('dir') == 'asc') {
            $dir = 'desc';
        } else {
            $dir = 'asc';
        }
        $orderNew = [
            'order' => $orderKey,
            'dir' => $dir,
        ];
        $paramUrl = array_merge(Input::all(),$orderNew);
        return $request->fullUrlWithQuery($paramUrl);
    }
    
    /**
     * get pager option 
     * 
     * @return type
     */
    public static function getPagerData()
    {
        $pager = [
            'limit' => 10,
            'order' => 'id',
            'dir' => 'asc',
            'page' => 1
        ];
        if ($pagerFilter = Form::getFilterPagerData()) {
            $pager = array_merge($pager, (array) Form::getFilterPagerData());
        }
        return $pager;
    }
    
    /**
     * rebuild url with new params
     * 
     * @param array $paramsNew
     * @return string
     */
    public static function urlParams($paramsNew = array())
    {
        $request = app('request');
        if (!$paramsNew) {
            return $request->fullUrl();
        }
        $paramUrl = array_merge(Input::all(), $paramsNew);
        return $request->fullUrlWithQuery($paramUrl);
    }
    
    /**
     * limt Option
     * 
     * @return array
     */
    public static function toOptionLimit()
    {
        return [
            ['value'=>'10','label'=>'10'],
            ['value'=>'20','label'=>'20'],
            ['value'=>'50','label'=>'50']
        ];
    }
}


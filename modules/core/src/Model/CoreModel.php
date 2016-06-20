<?php

namespace Rikkei\Core\Model;

use Rikkei\Core\View\Form;
use Illuminate\Support\Facades\Cache;

class CoreModel extends \Illuminate\Database\Eloquent\Model
{
    
    protected static $timeStoreCache = 10080; //time store cache is 1 week
    /**
     * set data for a model
     * 
     * @param array $data
     * @return \Rikkei\Core\Model\CoreModel
     */
    public function setData(array $data = array())
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $this->{$key} = $value;
        }
        return $this;
    }
    
    /**
     * filter grid action
     * 
     * @param model $collection
     * @param array|null $except
     * @return model
     */
    public static function filterGrid(&$collection, $except = [])
    {
        $filter = Form::getFilterData();
        if ($filter && count($filter)) {
            foreach ($filter as $key => $value) {
                if (in_array($key, $except)) {
                    continue;
                }
                if (is_array($value)) {
                    if (isset($value['from']) && $value['from']) {
                        $collection = $collection->where($key, '>=', $value['from']);
                    }
                    if (isset($value['to']) && $value['to']) {
                        $collection = $collection->where($key, '<=', $value['to']);
                    }
                } else {
                    $collection = $collection->where($key, 'like', "%$value%");
                }
            }
        }
        return $collection;
    }
    
    /**
     * get table name of model
     * 
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    /**
     * flush cache store of model
     */
    public static function flushCache()
    {
        Cache::flush();
    }
    
    /**
     * get key follow function class called
     * 
     * @param array|null $suffixKey
     * @return string
     */
    public static function getKeyCache($suffixKey = [])
    {
        $dataCalled = debug_backtrace();
        if (isset($dataCalled[1])) {
            $dataCalled = $dataCalled[1];
        } else {
            $dataCalled = $dataCalled[0];
        }
        $key = '';
        if (isset($dataCalled['class'])) {
            $key .= $dataCalled['class'] . '-c-';
        }
        if (isset($dataCalled['function'])) {
            $key .= $dataCalled['function'] . '-f-';
        }
        if (isset($dataCalled['args']) && $dataCalled['args']) {
            foreach ($dataCalled['args'] as $args) {
                $key .= var_export($args, true) . '-';
            }
            $key .= 'ar-';
        }
        if ($suffixKey) {
            if (is_string($suffixKey)) {
                $key .= $suffixKey;
            } else if (is_array($suffixKey)) {
                foreach ($suffixKey as $i) {
                    if (is_array($i)) {
                        $i = implode('.', $i);
                    }
                    $key .= $i . '-';
                }
            }
            $key .= $i . '-p-';
        }
        return $key;
    }
}

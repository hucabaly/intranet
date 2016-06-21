<?php

namespace Rikkei\Core\View;

use Session;

class Form
{
    /**
     * set form data
     * 
     * @param array $data
     */
    public static function setData($data = null, $key = null)
    {
        if(!$data || is_string($data)) {
            self::setDataInput($data, $key);
        } elseif (is_array($data)) {
            self::setDataForm($data, $key);
        } elseif (is_object($data)) {
            self::setDataModel($data, $key);
        }
    }

    /**
     * get form data value
     * 
     * @param type $key
     * @return type
     */
    public static function getData($key = null)
    {
        if(!$key) {
            return Session::get('form_data');
        }
        if(Session::has('form_data.'.$key)) {
            return Session::get('form_data.'.$key);
        }
    }
    
    /**
     * pull form data value
     * 
     * @param type $key
     * @return type
     */
    public static function pullData($key = null)
    {
        if(!$key) {
            return Session::pull('form_data');
        }
        if(Session::has('form_data.'.$key)) {
            return Session::pull('form_data.'.$key);
        }
        return null;
    }
    
    /**
     * set form data
     *  data is request input
     * 
     * @param string $name
     */
    public static function setDataInput($name = null, $key = null)
    {
        if(!$name) {
            $data = (array)app('request')->all();
        } else {
            $data = (array)app('request')->input($name);
        }
        if ($key) {
            $data = [$key => $data];
        }
        self::setDataForm($data);
    }
    
    /**
     * set form data
     *  data is model
     * 
     * @param type $model
     * @param string key
     */
    public static function setDataModel($model, $key = null)
    {
        if ($model instanceof \Illuminate\Contracts\Support\Arrayable) {
            $model = $model->toArray();
        } else {
            $model = [];
        }
        if($key) {
            $model = [$key => $model];
        }
        self::setDataForm($model);
    }
    
    /**
     * remove all form data
     */
    public static function forget($key = null)
    {
        if ($key) {
            Session::forget('form_data.' . $key);
        } else {
            Session::forget('form_data');
        }
        
    }
    
    /**
     * set form data format array
     * 
     * @param array $data
     */
    protected static function setDataForm(array $data = array(), $key = null)
    {
        if (!$data || !count($data)) {
            return;
        }
        if ($key) {
            foreach ($data as $keyData => $value) {
                Session::flash('form_data.' . $key . '.' . $keyData, $value);
            }
        } else {
            foreach ($data as $keyData => $value) {
                Session::flash('form_data.' . $keyData, $value);
            }
        }
        
    }
    
    /**
     * get filter data follow current url
     * 
     * @param string $key
     * @param string $key2
     * @return string
     */
    public static function getFilterData($key = null, $key2 = null)
    {
        $url = app('request')->url() . '/';
        $url = md5($url);
        $data = Session::get('filter.' . $url);
        if (! isset($data[0])) {
            return null;
        }
        $data = $data[0];
        if (! $key) {
            return $data;
        }
        if (! isset($data[$key])) {
            return null;
        }
         $data = $data[$key];
         if (! $key2) {
             return $data;
         }
         if (! isset($data[$key2])) {
             return null;
         }
         return $data[$key2];
    }
    
    /**
     * remove filter data follow current url
     */
    public static function forgetFilter()
    {
        $url = app('request')->url();
        $url = md5($url);
        Session::forget('filter.' . $url);
    }
}

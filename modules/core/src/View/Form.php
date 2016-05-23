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
            self::setDataForm($data);
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
    protected static function setDataForm(array $data = array())
    {
        if (!$data || !count($data)) {
            return;
        }
        foreach ($data as $key => $value) {
            Session::flash('form_data.' . $key, $value);
        }
    }
}

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
    public static function setData($data = null)
    {
        if(!$data || is_string($data)) {
            self::setDataInput($data);
        } elseif (is_array($data)) {
            self::setDataForm($data);
        } elseif (is_object($data)) {
            self::setDataModel($data);
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
    }
    
    /**
     * set form data
     *  data is request input
     * 
     * @param string $name
     */
    public static function setDataInput($name = null)
    {
        if(!$name) {
            self::setDataForm((array)app('request')->all());
        } else {
            self::setDataForm((array)app('request')->input($name));
        }
    }
    
    /**
     * set form data
     *  data is model
     * 
     * @param type $model
     */
    public static function setDataModel($model)
    {
        if ($model instanceof \Illuminate\Contracts\Support\Arrayable) {
            $model = $model->toArray();
        } else {
            $model = [];
        }
        self::setDataForm($model);
    }
    
    public static function flush()
    {
        Session::flush('form_data');
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
            Session::flash('form_data.'.$key, $value);
        }
    }
}

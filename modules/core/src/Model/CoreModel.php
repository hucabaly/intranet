<?php

namespace Rikkei\Core\Model;

use Rikkei\Core\View\Form;

class CoreModel extends \Illuminate\Database\Eloquent\Model
{
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
}

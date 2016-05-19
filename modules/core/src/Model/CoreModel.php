<?php

namespace Rikkei\Core\Model;

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
}

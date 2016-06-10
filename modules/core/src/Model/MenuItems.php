<?php

namespace Rikkei\Core\Model;

use DB;

class MenuItems extends CoreModel
{
    const STATE_ENABLE  = 1;
    const STATE_DISABLE = 0;

    public $timestamps = false;
    
    public function hasChild()
    {
        $child = self::select(DB::raw('count(*) as count'))
            ->where('parent_id', $this->id)
            ->first();
        return $child->count;
    }
}

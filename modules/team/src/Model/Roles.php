<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;

class Roles extends CoreModel
{
    protected $table = 'roles';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
}

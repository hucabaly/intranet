<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;

class RoleRule extends CoreModel
{
    protected $table = 'role_rule';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'rule', 'scope'
    ];
}
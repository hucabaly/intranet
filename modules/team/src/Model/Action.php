<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends CoreModel
{
    
    use SoftDeletes;
    
    protected $table = 'actions';
    
}

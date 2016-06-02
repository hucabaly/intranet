<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;

class EmployeeRole extends CoreModel
{
    protected $table = 'employee_roles';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'role_id', 'start_at', 'end_at'
    ];
    
    /**
     * get collection to show grid
     * 
     * @return type
     */
    public static function getGridData($roleId = null)
    {
        $pager = Config::getPagerData();
        $collection = self::select('employees.id as id','employees.name as name', 'employees.email as email')
            ->leftJoin('employees', 'employee_roles.employee_id', '=', 'employees.id')
            ->orderBy($pager['order'], $pager['dir']);
        if ($roleId) {
            $collection = $collection->where('role_id', $roleId);
        }
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
}

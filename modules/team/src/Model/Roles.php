<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;

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
    
    /**
     * get collection to show grid
     * 
     * @return type
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','name')->orderBy($pager['order'], $pager['dir']);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    /**
     * rewrite save role
     * 
     * @param array $rules
     * @return boolean
     */
    public function save(array $rules = [], array $options = [])
    {
        if (! count($rules) ) {
            return parent::save($options);
        }
        foreach ($rules as &$item) {
            $item['role_id'] = $this->id;
        }
        DB::beginTransaction();
        try {
            $result = parent::save($options);
            RoleRule::where('role_id', $this->id)->delete();
            RoleRule::insert($rules);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return $result;
    }
}

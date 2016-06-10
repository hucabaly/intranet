<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;

class Roles extends CoreModel
{
    /**
     * flag postion, role
     */
    const FLAG_ROLE = 0;
    const FLAG_POSITION = 1;

    protected $table = 'roles';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'special_flg', 'sort_order'
    ];
    
    /**
     * get all role is position
     * 
     * @param string $dir
     * @return collection model
     */
    public static function getAllPosition($dir = 'asc')
    {
        return self::select('id', 'role')
            ->where('special_flg', self::FLAG_POSITION)
            ->orderBy('sort_order', $dir)
            ->get();
    }
    
    /**
     * get all role is position
     * 
     * @return collection model
     */
    public static function getAllRole()
    {
        return self::select('id', 'role')
            ->where('special_flg', self::FLAG_ROLE)
            ->orderBy('role')
            ->get();
    }
    
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
     * rewite delete role
     */
    public function delete()
    {
        DB::beginTransaction();
        try {
            EmployeeRole::where('role_id', $this->id)->delete();
            RoleRule::where('role_id', $this->id)->delete();
            $result = parent::delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return $result;
    }
    
    /**
     * save rule
     * 
     * @param array $rules
     * @return boolean
     */
    public function saveRule(array $rules = [], array $options = [])
    {
        if (! count($rules) ) {
            return true;
        }
        foreach ($rules as &$item) {
            $item['role_id'] = $this->id;
        }
        DB::beginTransaction();
        try {
            RoleRule::where('role_id', $this->id)->delete();
            RoleRule::insert($rules);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return true;
    }
}

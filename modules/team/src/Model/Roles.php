<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends CoreModel
{
    
    use SoftDeletes;
    
    /**
     * flag postion, role
     */
    const FLAG_ROLE = 0; //role another
    const FLAG_POSITION = 1; //role position of team

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
        return parent::delete();
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
    
    /**
     * move position roles
     * 
     * @param boolean $up
     */
    public function move($up = true)
    {
        $siblings = self::select('id', 'sort_order')
            ->where('special_flg', self::FLAG_POSITION)
            ->orderBy('sort_order')
            ->get();
        if (count($siblings) < 2) {
            return true;
        }
        $dataOrder = $siblings->toArray();
        $countDataOrder = count($dataOrder);
        if ($up) {
            if ($dataOrder[0]['id'] == $this->id) { //item move up is first
                return true;
            }
            for ($i = 1; $i < $countDataOrder; $i++) {
                $dataOrder[$i]['sort_order'] = $i;
                if ($dataOrder[$i]['id'] == $this->id) {
                    $dataOrder[$i]['sort_order'] = $i - 1;
                    $dataOrder[$i - 1]['sort_order'] = $i;
                    break;
                }
            }
        } else {
            if ($dataOrder[count($dataOrder) - 1]['id'] == $this->id) { //item move down is last
                return true;
            }
            for ($i = 0; $i < $countDataOrder - 1; $i++) {
                $dataOrder[$i]['sort_order'] = $i;
                if ($dataOrder[$i]['id'] == $this->id) {
                    $dataOrder[$i]['sort_order'] = $i + 1;
                    $dataOrder[$i + 1]['sort_order'] = $i;
                    $flagIndexToCurrent = true;
                    $i++;
                    break;
                }
            }
        }
        DB::beginTransaction();
        try {
            foreach ($dataOrder as $data) {
                DB::table($this->table)
                    ->where('id', $data['id'])
                    ->update([
                        'sort_order' => $data['sort_order']
                    ]);
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * check role item is position team
     * 
     * @return boolean
     */
    public function isPosition()
    {
        if ($this->special_flg == self::FLAG_POSITION) {
            return true;
        }
        return false;
    }
    
    /**
     * check role item is role speical
     * 
     * @return boolean
     */
    public function isRole()
    {
        if ($this->special_flg == self::FLAG_ROLE) {
            return true;
        }
        return false;
    }
}

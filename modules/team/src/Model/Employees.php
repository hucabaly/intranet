<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;
use DB;
use Exception;
use Lang;

class Employees extends CoreModel
{
    protected $table = 'employees';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'birthday',
        'nickname',
        'email',
        'employee_card_id',
        'join_date',
        'leave_date',
        'persional_email',
        'mobile_phone',
        'home_phone',
        'gender',
        'address',
         'home_town',
        'id_card_number',
        'id_card_place',
        'id_cart_date',
        'recruiment_apply_id',
    ];
    
    /**
     * get collection to show grid
     * 
     * @return type
     */
    public static function getGridData()
    {
        $pager = Config::getPagerData();
        $collection = self::select('id','name','email')
            ->orderBy($pager['order'], $pager['dir']);
        $collection = self::filterGrid($collection);
        $collection = $collection->paginate($pager['limit']);
        return $collection;
    }
    
    public function saveTeamPosition(array $teamPostions = [])
    {
        //check miss data
        foreach ($teamPostions as $teamPostion) {
            if (! isset($teamPostion['team']) || 
                ! isset($teamPostion['position']) ||
                ! $teamPostion['team'] ||
                ! $teamPostion['position']) {
                throw new Exception(Lang::get('team::view.Miss data team or position'));
            }
        }
        //check data team not same
        $lengthTeamPostionsSubmit = count($teamPostions);
        for ($i = 1 ; $i < $lengthTeamPostionsSubmit ;  $i++) {
            for ($j = $i + 1 ; $j <= $lengthTeamPostionsSubmit ; $j ++) {
                if ($teamPostions[$i]['team'] == $teamPostions[$j]['team']) {
                    throw new Exception(Lang::get('team::view.Team same data'));
                }
            }
        }
        DB::beginTransaction();
        try {
            TeamMembers::where('employee_id', $this->id)->delete();
            if (count($teamPostions)) {
                foreach ($teamPostions as $teamPostion) {
                    TeamMembers::create([
                        'team_id' => $teamPostion['team'],
                        'position_id' => $teamPostion['position'],
                        'employee_id' => $this->id
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * get team and position of employee
     * 
     * @return collection
     */
    public function getTeamPositons()
    {
        return TeamMembers::select('team_id', 'position_id')->where('employee_id', $this->id)->get();
    }
    
    /**
     * get roles of employee
     * 
     * @return collection
     */
    public function getRoles()
    {
        return EmployeeRole::select('role_id')->where('employee_id', $this->id)->get();
    }
    
    /**
     * convert collection model to array with key is name column
     * 
     * @param model $collection
     * @param string $collection
     * @return array
     */
    protected static function formatArray($collection, $key = null)
    {
        if (!$collection instanceof \Illuminate\Contracts\Support\Arrayable) {
            return [];
        }
        $collectionArray = $collection->toArray();
        if (! $key) {
            return $collectionArray;
        }
        $result = [];
        foreach ($collectionArray as $item) {
            $result[$item[$key]] = $item;
        }
        return $result;
    }
}

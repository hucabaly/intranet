<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use Rikkei\Team\View\Config;

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
    
    public function saveTeamPosition(array $teamPostions)
    {
        $teamPositionCurrent = TeamMembers::select('id', 'team_id', 'position_id')
            ->where('employee_id', $this->id)
            ->get();
        $teamPositionCurrent = self::formatArray($teamPositionCurrent, 'team_id');
        foreach ($teamPostions as $teamPostion) {
            
        }
        exit;
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

<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use DB;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rikkei\Core\View\CacheHelper;

class Team extends CoreModel
{
    
    use SoftDeletes;
    
    const MAX_LEADER = 1;
    
    const KEY_CACHE = 'team';
    
    protected $table = 'teams';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'sort_order', 'follow_team_id', 'is_function', 'type', 'description', 'leader_id', 'email'
    ];
    
    /**
     * move position team
     * 
     * @param boolean $up
     */
    public function move($up = true)
    {
        $siblings = Team::select('id', 'sort_order')
            ->where('parent_id', $this->parent_id)
            ->orderBy('sort_order')
            ->get();
        if (count($siblings) < 2) {
            return true;
        }
        $dataOrder = $siblings->toArray();
        $flagIndexToCurrent = false;
        $countDataOrder = count($dataOrder);
        if ($up) {
            if ($dataOrder[0]['id'] == $this->id) { //item move up is first
                return true;
            }
            for ($i = 1; $i < $countDataOrder; $i++) {
                if (!$flagIndexToCurrent) {
                    $dataOrder[$i]['sort_order'] = $i;
                    if ($dataOrder[$i]['id'] == $this->id) {
                        $dataOrder[$i]['sort_order'] = $i - 1;
                        $dataOrder[$i - 1]['sort_order'] = $i;
                        $flagIndexToCurrent = true;
                    }
                } else {
                    unset($dataOrder[$i]);
                }
            }
        } else {
            if ($dataOrder[count($dataOrder) - 1]['id'] == $this->id) { //item move down is last
                return true;
            }
            for ($i = 0; $i < $countDataOrder - 1; $i++) {
                if (!$flagIndexToCurrent) {
                    $dataOrder[$i]['sort_order'] = $i;
                    if ($dataOrder[$i]['id'] == $this->id) {
                        $dataOrder[$i]['sort_order'] = $i + 1;
                        $dataOrder[$i + 1]['sort_order'] = $i;
                        $flagIndexToCurrent = true;
                        $i++;
                    }
                } else {
                    unset($dataOrder[$i]);
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
     * delete team and all child
     * 
     * @throws \Rikkei\Team\Model\Exception
     */
    public function delete()
    {
        if ($length = $this->getNumberMember()) {
            throw new Exception(Lang::get("team::messages.Team :name has :number members, can't delete!",[
                'name' => $this->name,
                'number' => $length
            ]));
        }
        $children = Team::select('id')
            ->where('parent_id', $this->id)->get();
        DB::beginTransaction();
        try {
            //delete all children of team
            if (count($children)) {
                foreach ($children as $child) {
                    Team::find($child->id)->delete();
                }
            }
            
            // TO DO check table Relationship: team position, user, css, ...
            
            //delete team rule
            Permissions::where('team_id', $this->id)->delete();
            //set permission as of  teams follow this team to 0
            Team::where('follow_team_id', $this->id)->update([
                'follow_team_id' => null
            ]);
            parent::delete();
            CacheHelper::forget(self::KEY_CACHE);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * rewrite vave the team to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array()) {
        if (! $this->parent_id) {
            $this->parent_id = null;
        }
        // update model
        if ($this->id) {
            //delete team rule of this team
            if (! $this->is_function || $this->follow_team_id) {
                Permissions::where('team_id', $this->id)->delete();
                
                //flush cache
                $positions = Roles::getAllPosition();
                if (count($positions)) {
                    foreach ($positions as $position) {
                        CacheHelper::forget(
                        Employees::KEY_CACHE_PERMISSION_TEAM_ACTION,
                        $this->id . '_' . $position->id
                        );
                        CacheHelper::forget(
                            Employees::KEY_CACHE_PERMISSION_TEAM_ROUTE,
                            $this->id . '_' . $position->id
                        );
                    }
                }
            }
        }
        CacheHelper::forget(self::KEY_CACHE);
        return parent::save($options);
    }
    
    /**
     * get number children of a team
     * 
     * @return int
     */
    public function getNumberChildren()
    {
        $children = self::select(DB::raw('count(*) as count'))
            ->where('parent_id', $this->id)
            ->first();
        return $children->count;
    }
    
    /**
     * get number member of a team
     * 
     * @return int
     */
    public function getNumberMember()
    {
        $children = TeamMembers::select(DB::raw('count(*) as count'))
            ->where('team_id', $this->id)
            ->first();
        return $children->count;
    }
    
    /**
     * get team permission as
     * 
     * @return boolean|model
     */
    public function getTeamPermissionAs()
    {
        if (! $this->follow_team_id) {
            return null;
        }
        if ($teamAs = CacheHelper::get(self::KEY_CACHE, $this->id)) {
            return $teamAs;
        }
        $teamAs = Team::find($this->follow_team_id);
        if (! $teamAs) {
            return null;
        }
        CacheHelper::put(self::KEY_CACHE, $teamAs, $this->id);
        return $teamAs;
    }
    
    /**
     * get teams by team_id list
     * @param array $arrTeamIds
     * @return object list
     */
    public function getTeamsByTeamIds($arrTeamIds){
        return self::whereIn('id', $arrTeamIds)->get();
    }
    
    /**
     * Get Team with deleted_at != null by id
     * @param int $teamId
     * @return Team
     */
    public function getTeamWithTrashedById($teamId){
        return self::where('id',$teamId)
                ->withTrashed()
                ->first();
    }
    
    /**
     * Get Team with deleted_at != null by parent id
     * @param int $parentId
     * @return Team list
     */
    public function getTeamByParentId($parentId){
        return self::where('parent_id',$parentId)
                ->withTrashed()
                ->get();
    }
    
    /**
     * get leader of team
     * 
     * @return model|null
     */
    public function getLeader()
    {
        if (! $this->leader_id) {
            return null;
        }
        $leader = Employees::find($this->leader_id);
        if (! $leader) {
            return null;
        }
        return $leader;
    }
    
    /**
     * check team is function
     * 
     * @return boolean
     */
    public function isFunction()
    {
        if ($this->is_function) {
            return true;
        }
        return false;
    }
    
    /**
     * get children of team
     * 
     * @param int|null $teamParentId
     * @return model
     */
    public static function getTeamChildren($teamParentId = null)
    {
        if ($teams = CacheHelper::get(self::KEY_CACHE)) {
            return $teams;
        }
        $teams = Team::select('id', 'name', 'parent_id')
                ->where('parent_id', $teamParentId)
                ->orderBy('sort_order', 'asc')
                ->get();
        CacheHelper::put(self::KEY_CACHE, $teams);
        return $teams;
    }
    
    /**
     * get team path
     * 
     * @return array|null
     */
    public static function getTeamPath()
    {
        if ($teamPath = CacheHelper::get(self::KEY_CACHE)) {
            return $teamPath;
        }
        $teamAll = Team::select('id', 'parent_id')->get();
        if (! count($teamAll)) {
            return null;
        }
        $teamPaths = [];
        foreach ($teamAll as $team) {
            self::getTeamPathRecursive($teamPaths[$team->id], $team->parent_id);
            if (! isset($teamPaths[$team->id])) {
                $teamPaths[$team->id] = null;
            }
        }
        CacheHelper::put(self::KEY_CACHE, $teamPath);
        return $teamPaths;
    }
    
    /**
     * get team path recursive
     * 
     * @param array $teamPaths
     * @param null:int $parentId
     */
    protected static function getTeamPathRecursive(&$teamPaths = [], $parentId = null)
    {
        if (! $parentId) {
            return;
        }
        $teamParent = Team::find($parentId);
        if (! $teamParent) {
            return;
        }
        $teamPaths[] = (int) $teamParent->id;
        self::getTeamPathRecursive($teamPaths, $teamParent->parent_id);
    }
}

<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use DB;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends CoreModel
{
    
    use SoftDeletes;
    
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
            //delete team item
            self::flushCache();
            parent::delete();
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
        // update model
        if ($this->id) {
            //delete team rule of this team
            
            // TODO delete rule
            
//            if (! $this->is_function) {
//                TeamRule::where('team_id', $this->id)->delete();
//            } elseif ($this->permission_as) {
//                TeamRule::where('team_id', $this->id)->delete();
//            }
        }
        self::flushCache();
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
        $teamAs = Team::find($this->follow_team_id);
        if (! $teamAs) {
            return null;
        }
        return $teamAs;
    }
}

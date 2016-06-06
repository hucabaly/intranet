<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use DB;
use Exception;

class Team extends CoreModel
{
    protected $table = 'team';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'position', 'permission_as', 'is_function', 'path'
    ];
    
    /**
     * move position team
     * 
     * @param boolean $up
     */
    public function move($up = true)
    {
        $siblings = Team::select('id', 'position')
            ->where('parent_id', $this->parent_id)
            ->orderBy('position')
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
                    $dataOrder[$i]['position'] = $i;
                    if ($dataOrder[$i]['id'] == $this->id) {
                        $dataOrder[$i]['position'] = $i - 1;
                        $dataOrder[$i - 1]['position'] = $i;
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
                    $dataOrder[$i]['position'] = $i;
                    if ($dataOrder[$i]['id'] == $this->id) {
                        $dataOrder[$i]['position'] = $i + 1;
                        $dataOrder[$i + 1]['position'] = $i;
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
                        'position' => $data['position']
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
            throw new Exception("Team {$this->name} has {$length} members, can't delete!");
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
            TeamRule::where('team_id', $this->id)->delete();
            
            //delete team item
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
            if (! $this->is_function) {
                TeamRule::where('team_id', $this->id)->delete();
            } elseif ($this->permission_as) {
                TeamRule::where('team_id', $this->id)->delete();
            }
        }
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
        if (! $this->permission_as) {
            return null;
        }
        $teamAs = Team::find($this->permission_as);
        if (! $teamAs) {
            return null;
        }
        return $teamAs;
    }
}

<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use DB;

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
        $children = Team::select('id')
            ->where('parent_id', $this->id)->get();
        DB::beginTransaction();
        try {
            if (count($children)) {
                foreach ($children as $child) {
                    Team::find($child->id)->delete();
                }
            }
            
            // TO DO check table Relationship: team position, user, css, ...
            
            parent::delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * get positions of team
     * 
     * @return array model
     */
    public function getPosition()
    {
        return TeamPosition::select('id', 'name')
            ->where('team_id', $this->id)
            ->orderBy('level')
            ->get();
    }
}

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
        if(count($siblings) < 2) {
            return true;
        }
        $dataOrder = $siblings->toArray();
        $i = 0;
        $flagIndexToCurrent = false;
        if($up) {
            foreach ($dataOrder as $team) {
                if(!$flagIndexToCurrent) {
                    $dataOrder[$i]['position'] = $i;
                    if($team['id'] == $this->id) {
                        $dataOrder[$i]['position'] = $i - 1;
                        $dataOrder[$i - 1]['position'] = $i;
                        $flagIndexToCurrent = true;
                    }
                } else {
                    unset($dataOrder[$i]);
                }
                $i++;
            }
        } else {
            foreach ($dataOrder as $team) {
                if(!$flagIndexToCurrent) {
                    $dataOrder[$i]['position'] = $i;
                    if($team['id'] == $this->id) {
                        $dataOrder[$i]['position'] = $i + 1;
                        $dataOrder[$i + 1]['position'] = $i;
                        $flagIndexToCurrent = true;
                        $i++;
                    }
                } else {
                    unset($dataOrder[$i]);
                }
                $i++;
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
}

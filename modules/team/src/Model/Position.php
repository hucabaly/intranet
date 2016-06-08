<?php
namespace Rikkei\Team\Model;

use Rikkei\Core\Model\CoreModel;
use DB;
use Exception;
use Lang;

class Position extends CoreModel
{
    protected $table = 'position';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'level'
    ];
    
    /**
     * get all position
     * 
     * @return array model
     */
    public static function getAll()
    {
        return self::select('id', 'name')
            ->orderBy('level')
            ->get();
    }
    
    /**
     * delete position
     * 
     * @return boolean
     */
    public function delete()
    {
        if ($length = $this->getNumberMember()) {
            throw new Exception(Lang::get("team::messages.Position :name has :number members, can't delete!",[
                'name' => $this->name,
                'number' => $length
            ]));
        }
        DB::beginTransaction();
        try {
            TeamRule::where('position_id', $this->id)->delete();
            $result = parent::delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return $result;
    }
    
    /**
     * move position team
     * 
     * @param boolean $up
     */
    public function move($up = true)
    {
        $siblings = self::select('id', 'level')
            ->orderBy('level')
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
                $dataOrder[$i]['level'] = $i;
                if ($dataOrder[$i]['id'] == $this->id) {
                    $dataOrder[$i]['level'] = $i - 1;
                    $dataOrder[$i - 1]['level'] = $i;
                    break;
                }
            }
        } else {
            if ($dataOrder[count($dataOrder) - 1]['id'] == $this->id) { //item move down is last
                return true;
            }
            for ($i = 0; $i < $countDataOrder - 1; $i++) {
                $dataOrder[$i]['level'] = $i;
                if ($dataOrder[$i]['id'] == $this->id) {
                    $dataOrder[$i]['level'] = $i + 1;
                    $dataOrder[$i + 1]['level'] = $i;
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
                        'level' => $data['level']
                    ]);
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
    
    /**
     * get number member of a position
     * 
     * @return int
     */
    public function getNumberMember()
    {
        $children = TeamMembers::select(DB::raw('count(*) as count'))
            ->where('position_id', $this->id)
            ->first();
        return $children->count;
    }
    
    /**
     * to option array data
     * 
     * @return array
     */
    public static function toOption()
    {
        $data = self::select('id', 'name')->orderBy('level', 'desc')->get();
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'value' => $item->id,
                'label' => $item->name
            ];
        }
        return $result;
    }
}

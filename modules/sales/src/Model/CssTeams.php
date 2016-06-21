<?php

namespace Rikkei\Sales\Model;

use Rikkei\Core\Model\CoreModel;
use DB;

class CssTeams extends CoreModel
{
    protected $table = 'css_team';
    public $timestamps = false;
    /**
     * Get team_id list by css_id
     * @param int $cssId
     * @return object list
     */
    public static function getTeamIdsByCssId($cssId){
        
        return self::where('css_id', $cssId)->get();
    }
    
    /**
     * Insert into table css_team
     * @param int $cssId
     * @param array $arrTeamIds
     */
    public static function insertCssTeam($cssId, $arrTeamIds = []){ 
        DB::beginTransaction();
        try {
            self::where('css_id', $cssId)->delete();
            if (count($arrTeamIds)) {
                foreach ($arrTeamIds as $teamId => $teamName) {
                    $cssTeams = new CssTeams();
                    $cssTeams->team_id = $teamId;
                    $cssTeams->css_id = $cssId;
                    $cssTeams->save();
                }
            }
            self::flushCache();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }
}

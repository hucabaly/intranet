<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Rikkei\Team\Model\Team;
use DB;

class Css extends Model
{
    protected $table = 'css';
    
    /**
     * Get bai lam css theo mot nhom cac project_type_id
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds){
        if($teamIds == ""){
            $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" and created_at <= "'.$endDate.'" and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.'))'
                . 'order by created_at asc';
        }else{
            $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT id from css where id In (SELECT css_id from css_team where team_id IN ('.$teamIds.'))) '
                . 'order by created_at asc';
        }
        $result = DB::select($sql);
        
        return $result;
    }
    
    /**
     * Get bai lam css theo 1 project_type_id
     * @param string $projectTypeId
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByProjectTypeId($projectTypeId,$startDate, $endDate, $teamIds){
        if($teamIds == ""){
            $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id ='.$projectTypeId.')'
                . 'order by created_at asc';
        }else{
            $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id ='.$projectTypeId.')'
                . 'and css_id In (SELECT id from css where id In (SELECT css_id from css_team where team_id IN ('.$teamIds.'))) '
                . 'order by created_at asc';
        }
        $result = DB::select($sql);
        
        return $result;
    }
    
    /**
     * get name by id team
     * @param int $teamId
     * return string
     */
    public static function getTeamNameById($teamId){
        $team = Team::find($teamId);
        return $team->name;
    }
    
    /**
     * Lay ten cua loai du an theo id
     * @param int $project_type_id
     * return string
     */
    public static function getProjectTypeNameById($project_type_id){
        $project_type = DB::table("project_type")
                ->where("id",$project_type_id)
                ->first();
        return $project_type->name;
    }
    
    /**
     * lay css  theo loai du an va team du an
     * @param ing $project_type_id
     * @param string $team_ids
     * return object
     */
    public static function getCssByProjectTypeAndTeam($project_type_id,$team_ids){
        $result = DB::select('select * from css '
                . 'where project_type_id = '.$project_type_id.' and id In (SELECT css_id from css_team where team_id In ('.$team_ids.'))'
                . 'order by created_at asc');
        
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param string $cssResultIds
     * return object
     */
    public static function layDanhSachCauDuoi3Sao($cssResultIds){
        $result = DB::select('select * from css_result_detail where css_id IN ('.$cssResultIds.') and point between 1 and 2 ');
        
        return $result;
    }
    
    /**
     * @param int $cssResultId
     */
    public static function getCssResultById($cssResultId){
        $cssResult = DB::table("css_result")->where("id",$cssResultId)->first();
        
        return $cssResult;
    }
    
    /**
     * get cau hoi by id
     * @param int $questionId
     */
    public static function getQuestionById($questionId){
        $cssResult = DB::table("css_question")->where("id",$questionId)->first();
        
        return $cssResult;
    }
    
   public static function layDanhSachDeXuat($cssResultIds){
        $cssResult = DB::select("Select * from css_result where id in ($cssResultIds) and survey_comment <> ''");
        
        return $cssResult;
    }
}

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
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'order by created_at asc';
        }
        $result = DB::select($sql);
        return $result;
    }
    
    /**
     * Get bai lam css theo 1 project_type_id
     * @param int $projectTypeId
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
     * Get css result by team id
     * @param int $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultByTeamId($teamId,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id = ('.$teamId.')) '
                . 'order by created_at asc';
        
        $result = DB::select($sql);
        return $result;
    }
    
    /**
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultByPmName($pmName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'and css_id IN (SELECT id from css WHERE pm_name = "'.$pmName.'") '
                . 'order by created_at asc';
        
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
     * get css by project_type_id and list team ids
     * @param ing $project_type_id
     * @param string $team_ids
     * return list object
     */
    public static function getCssByProjectTypeAndTeam($project_type_id,$team_ids){
        $result = DB::select('select * from css '
                . 'where project_type_id = '.$project_type_id.' and id In (SELECT css_id from css_team where team_id In ('.$team_ids.'))'
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * get css by team_id and list project type ids
     * @param int $teamId
     * @param string $projectTypeIds
     * return list object
     */
    public static function getCssByTeamIdAndListProjectType($teamId,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id = '.$teamId.')'
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * get css by pm_name, team_id and list project type ids
     * @param string $pmName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return list object
     */
    public static function getCssByPmAndTeamIdsAndListProjectType($pmName, $teamIds,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where pm_name = "'.$pmName.'" AND project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id IN ('.$teamIds.'))'
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param string $cssResultIds
     * return object
     */
    public static function getListLessThreeStar($cssResultIds){
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
    
    /**
     * get list css's proposes
     * @param string $cssResultIds
     * @return type
     */
    public static function getProposes($cssResultIds){
        $cssResult = DB::select("Select * from css_result where id in ($cssResultIds) and survey_comment <> ''");
        return $cssResult;
    }
    
    /**
     * Get list pm 
     */
    public static function getListPm(){
        $sql = "select distinct(pm_name) from css ";
        $pm = DB::select($sql);
        return $pm;
    }
    
    /**
     * Get list css by list pm name, list team id, start date, end date and list project type id
     * @param string $listPmName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByListPm($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrPmName = explode(",",$listPmName);
        $cssResult = [];
        
        foreach($arrPmName as $k => $v){
            $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE pm_name = "'.$v.'") '
                    . 'order by created_at asc';
            foreach(DB::select($sql) as $item){
                $result[] = $item;
            }
        }
        
        return $result;
    }
}

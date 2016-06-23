<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Rikkei\Team\Model\Team;
use DB;

class Css extends Model
{
    protected $table = 'css';
    
    /**
     * Get Css by css_id and token
     * @param int $cssId
     * @param string $token
     * @return object
     */
    public function getCssByIdAndToken($cssId,$token){
        return self::where('id', $cssId)
                ->where('token', $token)
                ->first();
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
     * get css by brse_name, team_id and list project type ids
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return list object
     */
    public static function getCssByBrseAndTeamIdsAndListProjectType($brseName, $teamIds,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where brse_name = "'.$brseName.'" AND project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id IN ('.$teamIds.'))'
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * get css by customer_name, team_id and list project type ids
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return list object
     */
    public static function getCssByCustomerAndTeamIdsAndListProjectType($customerName, $teamIds,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where customer_name = "'.$customerName.'" AND project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * get css by customer_name, team_id and list project type ids
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return list object
     */
    public static function getCssBySaleAndTeamIdsAndListProjectType($employee_id, $teamIds,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where employee_id = "'.$employee_id.'" AND project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param string $cssResultIds
     * return object
     */
    public static function getListLessThreeStar($cssResultIds,$offset,$perPage){
        $result = DB::select('select * from css_result_detail '
                . 'where css_result_id IN ('.$cssResultIds.') and point between 1 and 2 '
                . 'limit ' . $offset . ',' . $perPage );
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param int $questionId
     * @param string $cssResultIds
     * @param int $offset
     * @param int $perPage
     * return object
     */
    public static function getListLessThreeStarByQuestionId($questionId,$cssResultIds,$offset,$perPage){
        $result = DB::select('select * from css_result_detail '
                . 'where css_result_id IN ('.$cssResultIds.') and point between 1 and 2 '
                    . 'and question_id = ' . $questionId . ' '
                . 'limit ' . $offset . ',' . $perPage );
        return $result;
    }
    
    /**
     * Get count less 3* list
     * @param string $cssResultIds
     * return int
     */
    public static function getCountListLessThreeStar($cssResultIds){
        $result = DB::select('select * from css_result_detail '
                . 'where css_result_id IN ('.$cssResultIds.') and point between 1 and 2 ' );
        return count($result);
    }
    
    /**
     * Get count less 3* list by question
     * @param int $questionId
     * @param string $cssResultIds
     * return int
     */
    public static function getCountListLessThreeStarByQuestion($questionId,$cssResultIds){
        $result = DB::select('select * from css_result_detail '
                . 'where css_result_id IN ('.$cssResultIds.') and point between 1 and 2 and question_id = ' .  $questionId);
        return count($result);
    }
    
    /**
     * get proposes list
     * @param string $cssResultIds
     * @return object list
     */
    public static function getProposes($cssResultIds,$offset,$perPage){
        $cssResult = DB::select("Select * from css_result "
                . "where id in ($cssResultIds) and proposed <> '' "
                . "limit " . $offset . "," . $perPage );
        return $cssResult;
    }
    
    /**
     * get proposes list by question
     * @param int $questionId
     * @param string $cssResultIds
     * @return object list
     */
    public static function getProposesByQuestion($questionId,$cssResultIds,$offset,$perPage){
        $cssResult = DB::select("Select * from css_result "
                . "where id in ($cssResultIds) "
                    . "and proposed <> '' "
                    . "and id in (Select css_result_id from css_result_detail where point between 1 and 2 and question_id = ".$questionId.")"
                . "limit " . $offset . "," . $perPage );
        return $cssResult;
    }
    
    /**
     * get count Proposes
     * @param string $cssResultIds
     * @return int
     */
    public static function getCountProposes($cssResultIds){
        $cssResult = DB::select("Select * from css_result where id in ($cssResultIds) and proposed <> ''");
        return count($cssResult);
    }
    
    /**
     * get count Proposes
     * @param int $questionId
     * @param string $cssResultIds
     * @return int
     */
    public static function getCountProposesByQuestion($questionId,$cssResultIds){
        $cssResult = DB::select("Select * from css_result where id in ($cssResultIds) and proposed <> '' and id in (Select css_id from css_result_detail where point between 1 and 2 and question_id = ".$questionId.")");
        return count($cssResult);
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
     * Get list brse 
     */
    public static function getListBrse(){
        $sql = "select distinct(brse_name) from css ";
        $brse = DB::select($sql);
        return $brse;
    }
    
    /**
     * Get list customer css 
     */
    public static function getListCustomer(){
        $sql = "select distinct(customer_name) from css ";
        $cus = DB::select($sql);
        return $cus;
    }
    
    /**
     * Get list sale css 
     */
    public static function getListSale(){
        $sql = "select distinct(employee_id) from css ";
        $sale = DB::select($sql);
        return $sale;
    }
    
    /**
     * 
     * @param ing $cssId
     * @param date $startDate
     * @param date $endDate
     */
    public static function getCssResultByCssId($cssId,$startDate,$endDate){
        return DB::table("css_result")
                ->where("css_id",$cssId)
                ->where("created_at", ">=", $startDate)
                ->where("created_at", "<=", $endDate)
                ->get();
    }
    
    /**
     * get Css list
     * @param int $perPage
     */
    public static function getCssList($perPage){
        return self::orderBy('id', 'desc')->paginate($perPage);
    }
}

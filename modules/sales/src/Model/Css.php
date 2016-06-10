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
        $sql = 'SELECT * FROM css_result '
                . 'WHERE created_at >= "'.$startDate.'" '
                    . 'AND created_at <= "'.$endDate.'" '
                    . 'AND css_id In (SELECT id FROM css WHERE project_type_id IN ('.$projectTypeIds.')) '
                    . 'AND css_id In (SELECT css_id FROM css_team WHERE team_id IN ('.$teamIds.')) '
                . 'ORDER BY created_at ASC '; 
        $result = DB::select($sql);
        return $result;
    }
    
    /**
     * Get bai lam css theo mot nhom cac project_type_id
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultPaginateByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds,$offset=0,$perPage){
        $sql = 'SELECT * FROM css_result '
                . 'WHERE created_at >= "'.$startDate.'" '
                    . 'AND created_at <= "'.$endDate.'" '
                    . 'AND css_id In (SELECT id FROM css WHERE project_type_id IN ('.$projectTypeIds.')) '
                    . 'AND css_id In (SELECT css_id FROM css_team WHERE team_id IN ('.$teamIds.')) '
                . 'ORDER BY created_at ASC '
                . 'LIMIT ' . $offset . ', ' . $perPage ; 
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
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id ='.$projectTypeId.')'
                . 'and css_id In (SELECT id from css where id In (SELECT css_id from css_team where team_id IN ('.$teamIds.'))) '
                . 'order by created_at asc';
        
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
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultByBrseName($brseName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'and css_id IN (SELECT id from css WHERE brse_name = "'.$brseName.'") '
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
    public static function getCssResultByCustomerName($customerName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'and css_id IN (SELECT id from css WHERE customer_name = "'.$customerName.'") '
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
    public static function getCssBySaleAndTeamIdsAndListProjectType($user_id, $teamIds,$projectTypeIds){
        $result = DB::select('select * from css '
                . 'where user_id = "'.$user_id.'" AND project_type_id IN ('.$projectTypeIds.') and id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'order by created_at asc');
        return $result;
    }
    
    /**
     * Get css result by user_id
     * @param string $pmName
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultBySale($sale,$teamIds,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'and css_id IN (SELECT id from css WHERE user_id = "'.$sale.'") '
                . 'order by created_at asc';
        
        $result = DB::select($sql);  
        return $result;
    }
    
    /**
     * Get css result by user_id
     * @param string $questionId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultByQuestionToChart($questionId,$teamIds,$startDate, $endDate, $projectTypeIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                . 'and created_at <= "'.$endDate.'" '
                . 'and css_id In (SELECT id from css where project_type_id IN ('.$projectTypeIds.'))'
                . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                . 'and id IN (SELECT css_id from css_result_detail WHERE question_id = "'.$questionId.'") '
                . 'order by created_at asc';
        
        $result = DB::select($sql);  
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param string $cssResultIds
     * return object
     */
    public static function getListLessThreeStar($cssResultIds,$offset,$perPage){
        $result = DB::select('select * from css_result_detail '
                . 'where css_id IN ('.$cssResultIds.') and point between 1 and 2 '
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
                . 'where css_id IN ('.$cssResultIds.') and point between 1 and 2 '
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
                . 'where css_id IN ('.$cssResultIds.') and point between 1 and 2 ' );
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
                . 'where css_id IN ('.$cssResultIds.') and point between 1 and 2 and question_id = ' .  $questionId);
        return count($result);
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
     * get proposes list
     * @param string $cssResultIds
     * @return object list
     */
    public static function getProposes($cssResultIds,$offset,$perPage){
        $cssResult = DB::select("Select * from css_result "
                . "where id in ($cssResultIds) and survey_comment <> '' "
                . "limit " . $offset . "," . $perPage );
        return $cssResult;
    }
    
    /**
     * get count Proposes
     * @param string $cssResultIds
     * @return int
     */
    public static function getCountProposes($cssResultIds){
        $cssResult = DB::select("Select * from css_result where id in ($cssResultIds) and survey_comment <> ''");
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
        $sql = "select distinct(user_id) from css ";
        $sale = DB::select($sql);
        return $sale;
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
        $str = "";
        foreach($arrPmName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE pm_name IN ('.$str.')) '
                    . 'order by created_at asc';
        return DB::select($sql);
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
    public static function getCssResultPaginateByListPm($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $arrPmName = explode(",",$listPmName);
        $str = "";
        foreach($arrPmName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id IN (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id IN (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE pm_name IN ('.$str.')) '
                    . 'order by created_at asc '
                    . 'limit ' . $offset . ',' . $perPage ;
            
        return DB::select($sql);
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByListBrse($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrBrseName = explode(",",$listBrseName);
        $str = "";
        foreach($arrBrseName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE brse_name IN ('.$str.')) '
                    . 'order by created_at asc';
        
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultPaginateByListBrse($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $arrBrseName = explode(",",$listBrseName);
        $str = "";
        foreach($arrBrseName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE brse_name IN ('.$str.')) '
                    . 'order by created_at asc '
                    . 'limit ' . $offset . ',' . $perPage ;
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByListCustomer($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrCustomerName = explode(",",$listCustomerName);
        $str = "";
        foreach($arrCustomerName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE customer_name IN ('.$str.')) '
                    . 'order by created_at asc';
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list sale(user_id), list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultByListSale($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE user_id IN ('.$saleIds.')) '
                    . 'order by created_at asc';
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list sale(user_id), list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultPaginateByListSale($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE user_id IN ('.$saleIds.')) '
                    . 'order by created_at asc '
                    . 'limit ' . $offset . ',' . $perPage ;
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object list
     */
    public static function getCssResultByListQuestion($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and id IN (SELECT css_id from css_result_detail where question_id IN ('.$questionIds.')) '
                    . 'order by created_at asc';
        
        return DB::select($sql);
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object list
     */
    public static function getCssResultPaginateByListQuestion($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and id IN (SELECT css_id from css_result_detail where question_id IN ('.$questionIds.')) '
                    . 'order by created_at asc '
                    . 'limit ' . $offset . ',' . $perPage ;
        
        return DB::select($sql);
    }
    
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public static function getCssResultPaginateByListCustomer($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $arrCustomerName = explode(",",$listCustomerName);
        $str = "";
        foreach($arrCustomerName as $k => $v){
            if($str == ""){
                $str .= "'".$v."'";
            }else{
                $str .= ",'".$v."'";
            }
        }
        
        $sql = 'select * from css_result '
                . 'where created_at >= "'.$startDate.'" '
                    . 'and created_at <= "'.$endDate.'" '
                    . 'and css_id In (SELECT id from css where project_type_id In ('.$projectTypeIds.')) '
                    . 'and css_id In (SELECT css_id from css_team where team_id IN ('.$teamIds.')) '
                    . 'and css_id IN (SELECT id from css WHERE customer_name IN ('.$str.')) '
                    . 'order by created_at asc '
                    . 'limit ' . $offset . ',' . $perPage ;
        return DB::select($sql);
    }
    
    public static function getCssResultByQuestion($questionId,$startDate,$endDate,$teamIds){
        $sql = "select * from css_result "
                . "where id IN (select css_id from css_result_detail where question_id = $questionId) "
                    . "AND css_id IN (select css_id from css_team where team_id IN ($teamIds)) "
                    . "AND created_at >= '$startDate'" 
                    . "and created_at <= '$endDate.'";
        return DB::select($sql);
    }
    
    /**
     * @param int $projectTypeIds
     */
    public static function getCategoryByProjectType($projectTypeId){
        return DB::table("css_category")->where('id',$projectTypeId)->first();
    }
    
}

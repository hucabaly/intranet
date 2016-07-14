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
     * @return Css
     */
    public function getCssByIdAndToken($cssId,$token){
        return self::where('id', $cssId)
                ->where('token', $token)
                ->first();
    }

    /**
     * get css by project_type_id and list team ids
     * @param ing $project_type_id
     * @param string $team_ids
     * return Css list
     */
    public function getCssByProjectTypeAndTeam($projectTypeId,$teamIds){
        $arrFilterTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrFilterTeam)
                ->where('project_type_id',$projectTypeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by project_type_id and list team ids and employee
     * @param ing $project_type_id
     * @param string $team_ids
     * return Css list
     */
    public function getCssByProjectTypeAndTeamAndEmployee($project_type_id,$team_ids,$employeeId){
        $result = DB::select('select * from css '
                . 'where project_type_id = '.$project_type_id.' '
                . ' and id In (SELECT css_id from css_team where team_id In ('.$team_ids.')) '
                . ' and employee_id = ' . $employeeId 
                . ' order by created_at asc');
        return $result;
    }
    
    /**
     * get css by project_type_id and list team ids and employee's team
     * @param int $projectTypeId
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public function getCssByProjectTypeAndTeamAndEmployeeTeam($projectTypeId,$teamIds,$arrEmployeeTeam){
        $arrFilterTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->whereIn('css_team.team_id',$arrFilterTeam)
                ->where('project_type_id',$projectTypeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by team_id and list project type ids
     * @param int $teamId
     * @param string $projectTypeIds
     * return Css list
     */
    public static function getCssByTeamIdAndListProjectType($teamId,$projectTypeIds){
        $arrProjectType = explode(',', $projectTypeIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->where('css_team.team_id',$teamId)
                ->whereIn('project_type_id',$arrProjectType)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by teamId and project type list and employee
     * @param ing $teamId
     * @param string $projectTypeIds
     * @param int $employeeId
     * return Css list
     */
    public function getCssByTeamIdAndListProjectTypeAndEmployee($teamId,$projectTypeIds,$employeeId){
        $arrProjectType = explode(',', $projectTypeIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->where('css_team.team_id',$teamId)
                ->whereIn('project_type_id',$arrProjectType)
                ->where('css.employee_id',$employeeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by project_type_id and list team ids and employee's team
     * @param int $teamId
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public function getCssByTeamIdAndListProjectTypeAndEmployeeTeam($teamId,$projectTypeIds,$arrEmployeeTeam){
        $arrProjectType = explode(',', $projectTypeIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css_team.team_id',$teamId)
                ->whereIn('project_type_id',$arrProjectType)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by pm_name, team_id and list project type ids
     * @param string $pmName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return Css list
     */
    public static function getCssByPmAndTeamIdsAndListProjectType($pmName, $teamIds,$projectTypeIds){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.pm_name',$pmName)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by PM, team list, project type list and employee
     * @param string $pmName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param array $employeeId
     * return Css list
     */
    public function getCssByPmAndTeamIdsAndListProjectTypeAndEmployee($pmName, $teamIds,$projectTypeIds,$employeeId){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.pm_name',$pmName)
                ->where('css.employee_id',$employeeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by PM, team list, project type list and employee's team
     * @param string $pmName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public function getCssByPmAndTeamIdsAndListProjectTypeAndEmployeeTeam($pmName, $teamIds,$projectTypeIds,$arrEmployeeTeam){
        $arrFilterTeam = explode(',', $teamIds);
        $arrFilterProjectType = explode(',', $projectTypeIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->whereIn('css_team.team_id',$arrFilterTeam)
                ->whereIn('css.project_type_id',$arrFilterProjectType)
                ->where('css.pm_name',$pmName)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by brse_name, team list and project type list
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return Css list
     */
    public static function getCssByBrseAndTeamIdsAndListProjectType($brseName, $teamIds,$projectTypeIds){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.brse_name',$brseName)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by brse_name, team list, project type list and employee
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param int $employeeId
     * return Css list
     */
    public static function getCssByBrseAndTeamIdsAndListProjectTypeAndEmployee($brseName, $teamIds,$projectTypeIds,$employeeId){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.brse_name',$brseName)
                ->where('css.employee_id',$employeeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by brse_name, team list, project type list and employee's team
     * @param string $brseName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public static function getCssByBrseAndTeamIdsAndListProjectTypeAndEmployeeTeam($brseName, $teamIds,$projectTypeIds,$arrEmployeeTeam){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.brse_name',$brseName)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by customer_name, team list and project type list
     * @param string $customerName
     * @param string $teamIds
     * @param string $projectTypeIds
     * return Css list
     */
    public static function getCssByCustomerAndTeamIdsAndListProjectType($customerName, $teamIds,$projectTypeIds){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.customer_name',$customerName)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * get css by customer_name, team list, project type list and employee
     * @param string $customerName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param int $employeeId
     * return Css list
     */
    public static function getCssByCustomerAndTeamIdsAndListProjectTypeAndEmployee($customerName, $teamIds,$projectTypeIds,$employeeId){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.customer_name',$customerName)
                ->where('css.employee_id',$employeeId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    
    /**
     * get css by customer_name, team list, project type list and employee
     * @param string $customerName
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public static function getCssByCustomerAndTeamIdsAndListProjectTypeAndEmployeeTeam($customerName, $teamIds,$projectTypeIds,$arrEmployeeTeam){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.customer_name',$customerName)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * Get CSS by sale, team list and project type list and employee's team
     * @param int $saleId
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * return Css list
     */
    public static function getCssBySaleAndTeamIdsAndListProjectTypeAndEmployeeTeam($saleId, $teamIds,$projectTypeIds,$arrEmployeeTeam){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.employee_id',$saleId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * Get CSS by sale, team list and project type list and employee's team
     * @param int $saleId
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param int $employeeId
     * return Css list
     */
    public static function getCssBySaleAndTeamIdsAndListProjectTypeAndEmployee($saleId, $teamIds,$projectTypeIds,$employeeId){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.employee_id',$employeeId)
                ->where('css.employee_id',$saleId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * Get CSS by sale, team list, project type list
     * @param int $saleId
     * @param string $teamIds
     * @param string $projectTypeIds
     * return Css list
     */
    public static function getCssBySaleAndTeamIdsAndListProjectType($saleId, $teamIds,$projectTypeIds){
        $arrProjectType = explode(',', $projectTypeIds);
        $arrTeam = explode(',', $teamIds);
        return self::join('css_team', 'css.id', '=', 'css_team.css_id')
                ->whereIn('css_team.team_id',$arrTeam)
                ->whereIn('css.project_type_id',$arrProjectType)
                ->where('css.employee_id',$saleId)
                ->groupBy('css.id')
                ->select('css.*')
                ->get();
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param string $cssResultIds
     * return object
     */
    public static function getListLessThreeStar($cssResultIds,$perPage,$orderBy,$ariaType){
        $arrResultId = explode(',', $cssResultIds);
        return DB::table('css_result')->join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->join('css_question', 'css_result_detail.question_id', '=', 'css_question.id')
                ->whereIn('css_result.id',$arrResultId)
                ->where('css_result_detail.point','>=',1)
                ->where('css_result_detail.point','<=',2)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('comment', 'ASC')
                ->select('css_result.*','css_question.content as question_name','css.project_name','css_result_detail.point as point','css_result_detail.comment as comment','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao
     * @param int $questionId
     * @param string $cssResultIds
     * @param int $offset
     * @param int $perPage
     * return object
     */
    public static function getListLessThreeStarByQuestionId($questionId,$cssResultIds,$perPage,$orderBy,$ariaType){
        $arrResultId = explode(',', $cssResultIds);
        return DB::table('css_result')->join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->join('css_question', 'css_result_detail.question_id', '=', 'css_question.id')
                ->whereIn('css_result.id',$arrResultId)
                ->where('css_result_detail.point','>=',1)
                ->where('css_result_detail.point','<=',2)
                ->where('css_question.id',$questionId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('comment', 'ASC')
                ->select('css_result.*','css_question.content as question_name','css.project_name','css_result_detail.point as point','css_result_detail.comment as comment','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
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
    public static function getProposes($cssResultIds,$perPage,$orderBy,$ariaType){
        $arrResultId = explode(',', $cssResultIds);
        return DB::table('css_result')
                ->join('css', 'css.id', '=', 'css_result.css_id')
                ->whereIn('css_result.id',$arrResultId)
                ->where('css_result.proposed','<>','')
                ->orderBy($orderBy,$ariaType)
                ->orderBy('proposed', 'desc')
                ->groupBy('css_result.id')
                ->select('css_result.*','css_result.proposed as proposed','css.project_name','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * get proposes list by question
     * @param int $questionId
     * @param string $cssResultIds
     * @return object list
     */
    public static function getProposesByQuestion($questionId,$cssResultIds,$perPage,$orderBy,$ariaType){
        $arrResultId = explode(',', $cssResultIds);
        return DB::table('css_result')
                ->join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css_result.id',$arrResultId)
                ->where('css_result.proposed','<>','')
                ->where('css_result_detail.question_id',$questionId)
                ->where('css_result_detail.point','>=',1)
                ->where('css_result_detail.point','<=',2)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('proposed', 'desc')
                ->select('css_result.*','css_result.proposed as proposed','css.project_name','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
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
        $arrResultId = explode(',', $cssResultIds);
        $cssResult =  DB::table('css_result')
                ->join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css_result.id',$arrResultId)
                ->where('css_result.proposed','<>','')
                ->where('css_result_detail.question_id',$questionId)
                ->where('css_result_detail.point','>=',1)
                ->where('css_result_detail.point','<=',2)
                ->select('css_result.id')
                ->get();
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
                ->join('css', 'css.id', '=', 'css_result.css_id')
                ->where("css_id",$cssId)
                ->where("end_date", ">=", $startDate)
                ->where("end_date", "<=", $endDate)
                ->select('css_result.*')
                ->get();
    }
    
    /**
     * Get all Css list
     * @param int $perPage
     * @return Css list
     */
    public function getCssList($order, $dir){
        return self::join('css_project_type', 'css_project_type.id', '=', 'css.project_type_id')
                ->join('css_result', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css.id', '=', 'css_team.css_id')
                ->join('teams','teams.id','=','css_team.team_id')
                ->join('employees','employees.id','=','css.employee_id')
                ->orderBy($order,$dir)
                ->orderBy('css.id','asc')
                ->groupBy('css.id')
                ->select('css.*','css_project_type.name as project_type_name','employees.name as sale_name',DB::raw('(select COUNT(css_result.id) from css_result where css_id = css.id) as countCss'));
    }
    
    /**
     * Get Css by employee
     * @param int $employeeId
     * @param int $perPage
     * @return Css list
     */
    public function getCssListByEmployee($employeeId,$order, $dir){
        return self::join('css_project_type', 'css_project_type.id', '=', 'css.project_type_id')
                ->join('css_result', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css.id', '=', 'css_team.css_id')
                ->join('teams','teams.id','=','css_team.team_id')
                ->join('employees','employees.id','=','css.employee_id')
                ->where('css.employee_id',$employeeId)
                ->orderBy($order,$dir)
                ->orderBy('css.id','asc')
                ->groupBy('css.id')
                ->select('css.*','css_project_type.name as project_type_name','employees.name as sale_name',DB::raw('(select COUNT(css_result.id) from css_result where css_id = css.id) as countCss'));
    }
    
    /**
     * Get Css by team list
     * @param array $arrTeamId
     * @param int $perPage
     * @return Css list
     */
    public function getCssListByCssIdAndArrTeamId($arrTeamId,$order, $dir){
        return self::join('css_project_type', 'css_project_type.id', '=', 'css.project_type_id')
                ->join('css_result', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css.id', '=', 'css_team.css_id')
                ->join('teams','teams.id','=','css_team.team_id')
                ->join('employees','employees.id','=','css.employee_id')
                ->whereIn('css_team.team_id',$arrTeamId)
                ->orderBy($order,$dir)
                ->orderBy('css.id','asc')
                ->groupBy('css.id')
                ->select('css.*','css_project_type.name as project_type_name','employees.name as sale_name',DB::raw('(select COUNT(css_result.id) from css_result where css_id = css.id) as countCss'));
    }
    
    /**
     * Get project make information
     * @param int $resultId
     */
    public function projectMakeInfo($resultId){
        return self::join('css_result', 'css.id', '=', 'css_result.css_id')
                ->join('employees', 'employees.id', '=', 'css.employee_id')
                ->where('css_result.id',$resultId)
                ->groupBy('css_result.id')
                ->select('css.*', 'employees.japanese_name', 'css_result.created_at as make_date', 'css_result.name as make_name', 'css_result.email as make_email', 'css_result.avg_point as point','css_result.proposed')
                ->first();
    }
}

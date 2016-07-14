<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Rikkei\Sales\Model\Css;

class CssResult extends Model
{
    protected $table = 'css_result';
    
    /**
     * Insert into table css_result
     * @param array $data
     */
    public function insertCssResult($data){
        try {
            $cssResult = new CssResult();
            $cssResult->css_id = $data["css_id"];
            $cssResult->name = $data["name"];
            $cssResult->email = $data["email"];
            $cssResult->proposed = $data["proposed"];
            $cssResult->avg_point = $data["avg_point"];
            $cssResult->save();        
            
            return $cssResult->id;
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    /**
     * Get css result count
     * @param type $cssId
     * @return count css result
     */
    public function getCountCssResultByCss($cssId){
        return self::where("css_id",$cssId)->count();
    }
    
    /**
     * When Css only have once Css result then use this to get Css result
     * @param int $cssId
     */
    public function getCssResultFirstByCss($cssId){
        return self::where('css_id', $cssId)->first();
    }
    
    /**
     * Get Css result list by Css
     * @param int $cssId
     * @param int $perPage
     * @return object list css result
     */
    public function getCssResulByCss($cssId, $order, $dir){
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('employees', 'employees.id', '=', 'css.employee_id')
                ->where("css_result.css_id",$cssId)
                ->orderBy($order, $dir)
                ->orderBy('id', 'desc')
                ->groupBy('css_result.id')
                ->select('css_result.*','employees.name as sale_name');
    }
    
    /**
     * Get max, min, avg point of overview question
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     */
    public function getQuestionOverviewInfoAnalyze($projectTypeId,$startDate, $endDate,$teamIds){
        return self::whereIn('css_id',function($query) use ($projectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->where('project_type_id', $projectTypeId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->get();
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByProjectTypeIdsAndEmployee($projectTypeIds,$startDate, $endDate, $teamIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->orderBy('css.end_date','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByProjectTypeIdsAndEmployeeTeam($projectTypeIds,$startDate, $endDate, $teamIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultPaginateByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy($orderBy,$ariaType)
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultPaginateByProjectTypeIdsAndEmployee($projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->orderBy($orderBy,$ariaType)
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get Css result by projects type 
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultPaginateByProjectTypeIdsAndEmployeeTeam($projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy($orderBy,$ariaType)
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get Css result by project type
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return CssResult list
     */
    public function getCssResultByProjectTypeId($projectTypeId,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->where('css.project_type_id',$projectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get Css result by project type
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return CssResult list
     */
    public function getCssResultByProjectTypeIdAndEmployee($projectTypeId,$startDate, $endDate, $teamIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->where('css.project_type_id',$projectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get Css result by project type
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return CssResult list
     */
    public function getCssResultByProjectTypeIdAndEmployeeTeam($projectTypeId,$startDate, $endDate, $teamIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->where('css.project_type_id',$projectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param int $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public function getCssResultByTeamId($teamId,$startDate, $endDate, $projectTypeIds){
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->where('css_team.team_id',$teamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param int $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByTeamIdAndEmployee($teamId,$startDate, $endDate, $projectTypeIds,$employeeId){
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->where('css_team.team_id',$teamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param int $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByTeamIdAndEmployeeTeam($teamId,$startDate, $endDate, $projectTypeIds,$arrEmployeeTeam){
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->where('css_team.team_id',$teamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultByPmName($pmName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.pm_name', $pmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByPmNameAndEmployee($pmName,$teamIds,$startDate, $endDate, $projectTypeIds,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->where('css.pm_name', $pmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByPmNameAndEmployeeTeam($pmName,$teamIds,$startDate, $endDate, $projectTypeIds,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.pm_name', $pmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultByBrseName($brseName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.brse_name', $brseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByBrseNameAndEmployee($brseName,$teamIds,$startDate, $endDate, $projectTypeIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->where('css.brse_name', $brseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by team id
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByBrseNameAndEmployeeTeam($brseName,$teamIds,$startDate, $endDate, $projectTypeIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.brse_name', $brseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by Customer
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public function getCssResultByCustomerName($customerName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.customer_name', $customerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by Customer
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByCustomerNameAndEmployee($customerName,$teamIds,$startDate, $endDate, $projectTypeIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id', $employeeId)
                ->where('css.customer_name', $customerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by Customer
     * @param string $pmName
     * @param string $teamId
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByCustomerNameAndEmployeeTeam($customerName,$teamIds,$startDate, $endDate, $projectTypeIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.customer_name', $customerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultByListPm($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list pm name, list team id, start date, end date and list project type id
     * @param string $listPmName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByListPmAndEmployee($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list pm name, list team id, start date, end date and list project type id
     * @param string $listPmName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByListPmAndEmployeeTeam($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultPaginateByListPm($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list pm name, list team id, start date, end date and list project type id
     * @param string $listPmName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return CssResult list
     */
    public function getCssResultPaginateByListPmAndEmployee($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list pm name, list team id, start date, end date and list project type id
     * @param string $listPmName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return CssResult list
     */
    public function getCssResultPaginateByListPmAndEmployeeTeam($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.pm_name', $arrPmName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
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
    public function getCssResultByListBrse($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByListBrseAndEmployee($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByListBrseAndEmployeeTeam($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultPaginateByListBrse($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultPaginateByListBrseAndEmployee($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list brse name, list team id, start date, end date and list project type id
     * @param string $listBrseName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultPaginateByListBrseAndEmployeeTeam($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.brse_name', $arrBrseName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
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
    public function getCssResultByListCustomer($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByListCustomerAndEmployee($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id',$employeeId)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByListCustomerAndEmployeeTeam($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultByListSale($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list sale(user_id), list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultByListSaleAndEmployee($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->where('css.employee_id', $employeeId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list sale(user_id), list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultByListSaleAndEmployeeTeam($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list sale(employee_id), list team id, start date, end date and list project type id
     * @param string $saleIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultPaginateByListSale($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list sale(employee_id), list team id, start date, end date and list project type id
     * @param string $saleIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultPaginateByListSaleAndEmployee($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->where('css.employee_id', $employeeId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list sale(employee_id), list team id, start date, end date and list project type id
     * @param string $saleIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultPaginateByListSaleAndEmployeeTeam($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.employee_id', $arrSaleId)
                ->whereIn('css_team.team_id', $arrEmployeeTeam)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
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
    public function getCssResultByListQuestion($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.point','>',0)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id and employee
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object list
     */
    public function getCssResultByListQuestionAndEmployee($questionIds,$projectTypeIds, $startDate, $endDate,$teamIds,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.point','>',0)
                ->where('css.employee_id',$employeeId)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id and employee's team
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object list
     */
    public function getCssResultByListQuestionAndEmployeeTeam($questionIds,$projectTypeIds, $startDate, $endDate,$teamIds,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.point','>',0)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
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
    public function getCssResultPaginateByListQuestion($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::leftJoin('css', 'css.id', '=', 'css_result.css_id')
                ->leftJoin('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->leftJoin('teams', 'teams.id', '=', 'css_team.team_id')
                ->leftJoin('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.point','>',0)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id and employee
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $employeeId
     * @return object list
     */
    public function getCssResultPaginateByListQuestionAndEmployee($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::leftJoin('css', 'css.id', '=', 'css_result.css_id')
                ->leftJoin('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->leftJoin('teams', 'teams.id', '=', 'css_team.team_id')
                ->leftJoin('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id',$employeeId)
                ->where('css_result_detail.point','>',0)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list question, list team id, start date, end date and list project type id and employee's team
     * @param string $questionIds
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return object list
     */
    public function getCssResultPaginateByListQuestionAndEmployeeTeam($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::leftJoin('css', 'css.id', '=', 'css_result.css_id')
                ->leftJoin('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->leftJoin('teams', 'teams.id', '=', 'css_team.team_id')
                ->leftJoin('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css_result_detail.point','>',0)
                ->whereIn('css_result_detail.question_id', $arrQuestionId)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $perPage
     * @param string orderBy
     * @param string ariaType
     * @return object
     */
    public function getCssResultPaginateByListCustomer($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $perPage
     * @param string orderBy
     * @param string ariaType
     * @param int $employeeId
     * @return object
     */
    public function getCssResultPaginateByListCustomerAndEmployee($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id','=',$employeeId)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get list css by list customer name, list team id, start date, end date and list project type id
     * @param string $listCustomerName
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @param int $perPage
     * @param string orderBy
     * @param string ariaType
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultPaginateByListCustomerAndEmployeeTeam($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage,$orderBy,$ariaType,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrCustomerName = explode(",", $listCustomerName);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css.customer_name', $arrCustomerName)
                ->orderBy($orderBy,$ariaType)
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName','css_result.avg_point as result_point','css_result.created_at as result_make')
                ->paginate($perPage);
    }
    
    /**
     * Get CSS result by question, team and date
     * @param int $questionId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @return type
     */
    public function getCssResultByQuestion($questionId,$startDate,$endDate,$teamIds){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get CSS result by question, date, team and employee
     * @param int $questionId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @param int $employeeId
     * @return type
     */
    public function getCssResultByQuestionAndEmployee($questionId,$startDate,$endDate,$teamIds,$employeeId){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id',$employeeId)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get CSS result by question, date, team and employee
     * @param int $questionId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @param array $arrEmployeeTeam
     * @return type
     */
    public function getCssResultByQuestionAndEmployeeTeam($questionId,$startDate,$endDate,$teamIds,$arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by employee_id
     * @param int $employee_id
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public function getCssResultBySale($employee_id,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id', $employee_id)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by employee_id
     * @param int $saleId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public function getCssResultBySaleAndEmployee($saleId,$teamIds,$startDate, $endDate, $projectTypeIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id', $saleId)
                ->where('css.employee_id', $employeeId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by employee_id
     * @param int $saleId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public function getCssResultBySaleAndEmployeeTeam($saleId,$teamIds,$startDate, $endDate, $projectTypeIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id', $saleId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by question
     * @param string $questionId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @return object
     */
    public static function getCssResultByQuestionToChart($questionId,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by question
     * @param string $questionId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param array $arrEmployeeTeam
     * @return object
     */
    public static function getCssResultByQuestionToChartAndEmployeeTeam($questionId,$teamIds,$startDate, $endDate, $projectTypeIds, $arrEmployeeTeam){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->whereIn('css_team.team_id',$arrEmployeeTeam)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
    
    /**
     * Get css result by question
     * @param string $questionId
     * @param string $teamIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $projectTypeIds
     * @param int $employeeId
     * @return object
     */
    public static function getCssResultByQuestionToChartAndEmployee($questionId,$teamIds,$startDate, $endDate, $projectTypeIds, $employeeId){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::join('css', 'css.id', '=', 'css_result.css_id')
                ->join('css_team', 'css_result.css_id', '=', 'css_team.css_id')
                ->join('teams', 'teams.id', '=', 'css_team.team_id')
                ->join('css_result_detail', 'css_result_detail.css_result_id', '=', 'css_result.id')
                ->whereIn('css.project_type_id',$arrProjectTypeId)
                ->whereIn('css_team.team_id',$arrTeamId)
                ->where('css.end_date','>=',$startDate)
                ->where('css.end_date','<=',$endDate)
                ->where('css.employee_id', $employeeId)
                ->where('css_result_detail.question_id', $questionId)
                ->orderBy('css.end_date','ASC')
                ->orderBy('css_result.id','ASC')
                ->groupBy('css_result.id')
                ->select('css_result.*','css.end_date','css.project_name','css.pm_name as pmName','teams.name as teamName')
                ->get();
    }
}

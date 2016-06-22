<?php

namespace Rikkei\Sales\Model;

use Illuminate\Database\Eloquent\Model;
use Rikkei\Sales\Model\Css;
use Rikkei\Sales\Model\CssTeams;
use Rikkei\Sales\Model\CssResultDetail;
use DB;

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
            $cssResult->comment_overview = $data["comment_overview"];
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
    public function getCssResulByCss($cssId, $perPage){
        return self::where("css_id",$cssId)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
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
     * Get bai lam css theo mot nhom cac project_type_id
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
                    ->get();
    }
    
    /**
     * Get bai lam css theo mot nhom cac project_type_id
     * @param string $projectTypeIds
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultPaginateByProjectTypeIds($projectTypeIds,$startDate, $endDate, $teamIds,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
                    ->paginate($perPage);
    }
    
    /**
     * Get bai lam css theo 1 project_type_id
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate 
     * @param string $teamIds
     * @return object
     */
    public function getCssResultByProjectTypeId($projectTypeId,$startDate, $endDate, $teamIds){
        $arrTeamId = explode(",", $teamIds);
        return self::whereIn('css_id',function($query) use ($projectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->where('project_type_id', $projectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($teamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->where('team_id', $teamId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($pmName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->where('pm_name', $pmName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($brseName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->where('brse_name', $brseName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultByCustomerName($customerName,$teamIds,$startDate, $endDate, $projectTypeIds){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($customerName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->where('customer_name', $customerName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrPmName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('pm_name', $arrPmName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultPaginateByListPm($listPmName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrPmName = explode(",", $listPmName);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrPmName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('pm_name', $arrPmName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrBrseName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('brse_name', $arrBrseName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultPaginateByListBrse($listBrseName,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrBrseName = explode(",", $listBrseName);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrBrseName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('brse_name', $arrBrseName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrCustomerName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('brse_name', $arrCustomerName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrSaleId){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('employee_id', $arrSaleId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultPaginateByListSale($saleIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrSaleId = explode(",", $saleIds);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrSaleId){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('employee_id', $arrSaleId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('id',function($query) use ($arrQuestionId){
                        $query->select('css_result_id')
                            ->from(with(new CssResultDetail)->getTable())
                            ->whereIn('question_id', $arrQuestionId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultPaginateByListQuestion($questionIds,$projectTypeIds,$startDate, $endDate, $teamIds,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrQuestionId = explode(",", $questionIds);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('id',function($query) use ($arrQuestionId){
                        $query->select('css_result_id')
                            ->from(with(new CssResultDetail)->getTable())
                            ->whereIn('question_id', $arrQuestionId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
    public function getCssResultPaginateByListCustomer($listCustomerName,$projectTypeIds,$startDate, $endDate, $teamIds,$offset,$perPage){
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $arrListCustomerName = explode(",", $listCustomerName);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($arrListCustomerName){
                        $query->select('css_id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('brse_name', $arrListCustomerName);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
                    ->paginate($perPage);
    }
    
    /**
     * 
     * @param int $questionId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @return type
     */
    public function getCssResultByQuestion($questionId,$startDate,$endDate,$teamIds){
        $arrTeamId = explode(",", $teamIds);
        return self::whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('id',function($query) use ($questionId){
                        $query->select('css_result_id')
                            ->from(with(new CssResultDetail)->getTable())
                            ->where('question_id', $questionId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
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
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                    ->whereIn('css_id',function($query) use ($employee_id){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->where('employee_id', $employee_id);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
                    ->get();
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
        $arrTeamId = explode(",", $teamIds);
        $arrProjectTypeId = explode(",", $projectTypeIds);
        return self::whereIn('css_id',function($query) use ($arrProjectTypeId){
                        $query->select('id')
                            ->from(with(new Css)->getTable())
                            ->whereIn('project_type_id', $arrProjectTypeId);
                        })
                    ->whereIn('css_id',function($query) use ($arrTeamId){
                        $query->select('css_id')
                            ->from(with(new CssTeams)->getTable())
                            ->whereIn('team_id', $arrTeamId);
                        })
                     ->whereIn('id',function($query) use ($questionId){
                        $query->select('css_result_id')
                            ->from(with(new CssResultDetail)->getTable())
                            ->where('question_id', $questionId);
                        })
                    ->where('created_at','>=',$startDate)
                    ->where('created_at','<=',$endDate)
                    ->orderBy('created_at','ASC')
                    ->get();
    }
    
}

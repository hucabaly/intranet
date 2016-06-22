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
     * Get Project type by id
     * @param int $project_type_id
     * return object
     */
    public static function getProjectTypeById($project_type_id){
        $project_type = DB::table("project_type")
                ->where("id",$project_type_id)
                ->first();
        return $project_type;
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
                    . "and id in (Select css_id from css_result_detail where point between 1 and 2 and question_id = ".$questionId.")"
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
     * @param int $projectTypeIds
     */
    public static function getCategoryByProjectType($projectTypeId){
        return DB::table("css_category")->where('id',$projectTypeId)->first();
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
     * 
     * @param int $cssResultId
     * @param int $questionId
     */
    public static function getCssResultDetail($cssResultId,$questionId){
        return DB::table('css_result_detail')
                        ->where("css_result_id",$cssResultId)
                        ->where("question_id",$questionId)
                        ->first();
    }
    
    /**
     * get Css list
     * @param int $perPage
     */
    public static function getCssList($perPage){
        return self::orderBy('id', 'desc')->paginate($perPage);
    }
    
    /**
     * Get Team by id
     * @param type $teamId
     * @return team object
     */
    public static function getTeamById($teamId){
        return Team::find($teamId);
    }
    
    /**
     * Insert data into table css_result
     * @param array $data
     * @return int
     */
    public static function insertCssResult($data){
        return DB::table('css_result')->insertGetId(
            array(
                'css_id' => $data["css_id"],
                'name' => $data["name"],
                'email' => $data["email"],
                'comment' => $data["comment"],
                'avg_point' => $data["avg_point"],
                'name' => $data["name"],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
                'survey_comment' => $data['survey_comment']
            )
        );
    }
    
    public static function insertCssResultDetail($data){
        DB::table('css_result_detail')->insert(
            array(
                'css_id' => $data['css_id'],
                'question_id' => $data['question_id'],
                'point' => $data['point'],
                'comment' => $data['comment'],
            )
        ); 
    }   
}

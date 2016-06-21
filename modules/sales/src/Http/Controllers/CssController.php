<?php

namespace Rikkei\Sales\Http\Controllers;

use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use DB;
use Rikkei\Core\Model\User;
use Rikkei\Sales\Model\ProjectType;
use Rikkei\Sales\Model\Css;
use Rikkei\Sales\Model\CssTeams;
use Rikkei\Sales\Model\CssQuestion;
use Rikkei\Sales\Model\CssCategory;
use Rikkei\Sales\Model\CssResult;
use Rikkei\Sales\Model\CssResultDetail;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\Employees;
use Lang;
use Mail;
use Session;
use Illuminate\Http\Request;

class CssController extends Controller {
    static $perPage = 5;
    static $perPageCss = 10;
    
    /**
     * Create Css page view
     */
    public function create() {
        $user = Auth::user(); 
        $employee = Employees::find($user->employee_id);
        $teams = Team::all();
        $htmlTeam = self::getTreeDataRecursive(null, 0, null);

        return view(
                'sales::css.create', 
                [
                    'employee'  => $employee,
                    'teams'     => $teams,
                    'htmlTeam'  => $htmlTeam,
                ]
        );
    }

    /**
     * Update Css page view
     * @param int $id
     */
    public function update($id) {
        $css = Css::where('id', $id)->first();

        //get employee create css       
        $employee = Employees::find($css->employee_id);
        
        //get team_id list by css_id
        $teams = Team::all();
        $arrTeamId = array();
        $teamIds = CssTeams::getTeamIdsByCssId($id);

        foreach ($teamIds as $team) {
            $arrTeamId[] = $team->team_id;
        }

        //get Css's team list
        $teamModel = new Team();
        $teamsSet = $teamModel->getTeamsByTeamIds($arrTeamId);

        //Get Team's name is set
        $strTeamsNameSet = [];
        foreach ($teamsSet as $team) {
            $strTeamsNameSet[] = $team->name;
        }
        $strTeamsNameSet = implode(', ', $strTeamsNameSet);
        
        $htmlTeam = self::getTreeDataRecursive(null, 0, null);
        return view(
            'sales::css.update', 
            [
                'css' => $css, 
                'employee' => $employee, 
                "teams" => $teams, 
                "teamsSet" => $teamsSet, 
                "strTeamsNameSet" => $strTeamsNameSet, 
                "htmlTeam"  => $htmlTeam,
            ]
        );
    }

    /**
     * Preview page
     * @param string $token
     * @param int $id
     * @return objects
     */
    public function preview($token, $id) {
        $cssModel = new Css();
        $css = $cssModel->getCssByIdAndToken($id,$token);

        if ($css) {
            $employee = Employees::find($css->employee_id);
            return view(
                'sales::css.preview', 
                [
                    'css' => $css,
                    "employee" => $employee
                ]
            );
        } else {
            return redirect("/");
        }
    }

    /**
     * Save Css (insert or update)
     */
    public function save(Request $request) { 
        $start_date = date('Y-m-d', strtotime($request->input('start_date')));
        $end_date = date('Y-m-d', strtotime($request->input('end_date')));

        if ($request->input("create_or_update") == 'create') {
            $css = new Css();
        } else {
            $cssId = $request->input("css_id");
            $css = Css::find($cssId);
        }
        
        $css->employee_id = $request->input("employee_id");
        $css->company_name = $request->input("company_name");
        $css->customer_name = $request->input("customer_name");
        $css->project_name = $request->input("project_name");
        $css->brse_name = $request->input("brse_name");
        $css->start_date = $start_date;
        $css->end_date = $end_date;
        $css->pm_name = $request->input("pm_name");
        $css->project_type_id = $request->input("project_type_id");
        
        $employee = Employees::find($css->employee_id);
        $employee->japanese_name = $request->input("japanese_name");
        
        if ($request->input("create_or_update") == 'create') {
            $css->token = md5(rand());
        }

        $css->save();
        $employee->save();
        
        //insert into table css_team
        $arrTeamIds = $request->input("teams"); 
        $cssTeamModel = new CssTeams();
        $cssTeamModel->insertCssTeam($css->id, $arrTeamIds);
        
        return redirect('/css/preview/' . $css->token . '/' . $css->id);
    }

    /**
     * Welcome and make Css page
     * @param string $token
     * @param int $id
     * @return objects
     */
    public function make($token, $id) {
        $cssQuestionModel = new CssQuestion();
        $cssCategoryModel = new CssCategory();
        $css = Css::where('id', $id)
                ->where('token', $token)
                ->first();

        if ($css) {
            $employee = Employees::find($css->employee_id);
            $rootCategory = $cssCategoryModel->getRootCategory($css->project_type_id);
            $cssCategory = $cssCategoryModel->getCategoryByParent($rootCategory->id);
            $cssCate = array();
            if ($cssCategory) {
                $NoOverView = 0;
                foreach ($cssCategory as $item) {
                    $NoOverView++;
                    $cssCategoryChild = $cssCategoryModel->getCategoryByParent($item->id);
                    $cssCateChild = array();
                    if ($cssCategoryChild) {
                        foreach ($cssCategoryChild as $item_child) {
                            $cssQuestionChild = $cssQuestionModel->getQuestionByCategory($item_child->id);
                            $cssCateChild[] = array(
                                "id" => $item_child->id,
                                "name" => $item_child->name,
                                "parent_id" => $item->id,
                                "sort_order" => $item_child->sort_order,
                                "questionsChild" => $cssQuestionChild,
                            );
                        }
                    }
                    
                    $cssQuestion = $cssQuestionModel->getQuestionByCategory($item->id);
                    $cssCate[] = array(
                        "id" => $item->id,
                        "name" => $item->name,
                        "sort_order" => self::romanic_number($item->sort_order,true),
                        "cssCateChild" => $cssCateChild,
                        "questions" => $cssQuestion,
                    );
                }
            }
            
            $overviewQuestion = $cssQuestionModel->getCommentOverview($rootCategory->id,1);
            
            $arrayValidate = array(
                "nameRequired" => Lang::get('sales::message.Name validate required'),
                "emailRequired" => Lang::get('sales::message.Email validate required'),
                "emailAddress" => Lang::get('sales::message.Email validate address'),
                "totalMarkValidateRequired" => Lang::get('sales::message.Total mark validate required'),
                "questionCommentRequired" => Lang::get('sales::message.Question comment required'),
                "proposedRequired"  => Lang::get('sales::message.Proposed required'),
            );
            
            return view(
                'sales::css.make', [
                    'css' => $css,
                    "employee" => $employee,
                    "cssCate" => $cssCate,
                    "arrayValidate" => json_encode($arrayValidate),
                    "noOverView" => self::romanic_number(++$NoOverView,true),
                    "overviewQuestionContent" => $overviewQuestion->content,
                ]
            );
        } else {
            return redirect("/");
        }
    }
    
    /**
     * Insert Css result into database
     * @return void
     */
    public function saveResult(Request $request){ 
        $arrayQuestion  = $request->input('arrayQuestion');
        $name           = $request->input('make_name');
        $email          = $request->input('make_email');
        $avgPoint       = $request->input('totalMark');
        $comment        = $request->input('comment');
        $survey_comment = $request->input('proposed');
        $cssId          = $request->input('cssId');
        
        $dataResult = [
            'css_id' => $cssId,
            'name' => $name,
            'email' => $email,
            'comment_overview' => $comment,
            'avg_point' => $avgPoint,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
            'proposed' => $survey_comment
        ];
        
        $cssResultModel = new CssResult();
        $cssResultId = $cssResultModel->insertCssResult($dataResult);
        
        $cssResultDetailModel = new CssResultDetail();
        $cssResultDetailModel->insertCssResultDetail($cssResultId,$arrayQuestion);
        
        $css = Css::find($cssId); 
        $employee = Employees::find($css->employee_id); 
        $email = $employee->email; 
        $data = array(
            'href' => url('/') . "/css/detail/" . $cssResultId,
            'project_name' => $css->project_name,
        );

        Mail::send('sales::css.sendMail', $data, function ($message) use($email) {
            $message->from('sales@rikkeisoft.com', 'Rikkeisoft');
            $message->to($email)->subject(Lang::get('sales::view.Subject email notification make css'));

        });

        
    }
    
    /**
     * View Css list 
     * @return void
     */
    public function grid(){
        $css = Css::getCssList(self::$perPageCss);
        
        if(count($css) > 0){
            $cssResultModel = new CssResult();
            $i = ($css->currentPage()-1) * $css->perPage() + 1;
            foreach($css as &$item){ 
                $item->stt = $i;
                $i++;
                $item->project_type_name = ($item->project_type_id == 1) ? 'OSDC' : 'Project base'; 
                $cssTeams = CssTeams::getCssTeamByCssId($item->id);

                $arr_team = array();
                foreach($cssTeams as $cssTeamChild){
                    $team = Team::find($cssTeamChild->team_id);
                    $arr_team[] = $team->name;
                }
                $item->teamsName = implode(", ", $arr_team);

                $employee = Employees::find($item->employee_id);
                $item->sale_name = $employee->name;
                $item->start_date = date('d/m/Y',strtotime($item->start_date));
                $item->end_date = date('d/m/Y',strtotime($item->end_date));
                $item->create_date = date('d/m/Y',strtotime($item->created_at));
                $item->url =  url('/') . '/css/make/'. $item->token . '/' . $item->id;
                // get count css result by cssId 
                $item->countCss = $cssResultModel->getCountCssResultByCss($item->id);
                if($item->countCss == 1){
                    $cssResultDetail = $cssResultModel->getCssResultFirstByCss($item->id);
                    $item->hrefToView = url('/') . '/css/detail/' . $cssResultDetail->id;
                }else if($item->countCss > 1){
                    $item->hrefToView = url('/') . '/css/view/' . $item->id;
                }
            }
        }
        
        return view(
            'sales::css.list', [
                'css' => $css,
            ]
        );
    }
    
    /**
     * View Css result by Css
     * @param int $cssId
     */
    public function view($cssId){
        $css = Css::find($cssId);
        if(count($css)){
            $cssResultModel = new CssResult();
            $cssResults = $cssResultModel->getCssResulByCss($cssId,self::$perPageCss);
            if(count($cssResults)){
                $i = ($cssResults->currentPage()-1) * $cssResults->perPage() + 1;
                foreach($cssResults as &$item){
                    $item->stt = $i;
                    $i++;
                    $item->make_date = date('d/m/Y',strtotime($item->created_at));
                    $item->mark = self::getPointCssResult($item->id);
                }
            }
        }
        
        return view(
            'sales::css.view', [
                'cssResults' => $cssResults,
                'css' => $css,
            ]
        );
    }
    
    /**
     * View Css result detail page
     * @param int $resultId
     * @return void
     */
    public function detail($resultId){
        $cssResult = CssResult::find($resultId);
        $css = Css::find($cssResult->css_id);
        $employee = Employees::find($css->employee_id);
        
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        $cssResultDetailModel = new CssResultDetail();
        
        $rootCategory = $cssCategoryModel->getRootCategory($css->project_type_id);
        $cssCategory = $cssCategoryModel->getCategoryByParent($rootCategory->id);
        foreach($cssCategory as $item){
            $cssCategoryChild = $cssCategoryModel->getCategoryByParent($item->id);
            $cssCateChild = array();
            if ($cssCategoryChild) {
                foreach ($cssCategoryChild as $itemChild) {
                    $cssQuestionChild = $cssQuestionModel->getQuestionByCategory($itemChild->id);
                    $questionsChild = array();
                    foreach($cssQuestionChild as &$question){
                        $resultDetailRow = $cssResultDetailModel->getResultDetailRow($resultId, $question->id);
                        $question->point = $resultDetailRow->point;
                        $question->comment = $resultDetailRow->comment;
                        if($resultDetailRow){
                            $questionsChild[] = $question;
                        }
                    }
                    $cssCateChild[] = array(
                        "id" => $itemChild->id,
                        "name" => $itemChild->name,
                        "parent_id" => $item->id,
                        "questionsChild" => $questionsChild,
                    );
                }
            }

            $cssQuestion = $cssQuestionModel->getQuestionByCategory($item->id);
            $questions = array();
            foreach($cssQuestion as $question){
                $resultDetailRow = $cssResultDetailModel->getResultDetailRow($resultId, $question->id);
                $question->point = $resultDetailRow->point;
                $question->comment = $resultDetailRow->comment;
                if($resultDetailRow){
                    $questions[] = $question;
                }
            }
            
            $cssCate[] = array(
                "id" => $item->id,
                "name" => $item->name,
                "cssCateChild" => $cssCateChild,
                "questions" => $questions,
            );
        }
        
        $cssResult->mark = self::getPointCssResult($resultId);
        return view(
            'sales::css.detail', [
                'css' => $css,
                "employee" => $employee,
                "cssCate" => $cssCate,
                "cssResult" => $cssResult,
            ]
        );
    }
    
    /**
     * 
     * @param int $cssId
     * @return void
     */
    public function success($cssId){
        $css = Css::find($cssId);
        return view(
            'sales::css.success', [
                "css" => $css
            ]
        ); 
    }
    
    /**
     * Trang huy yeu cau lam css
     * @return void
     */
    public function cancelMake(){
        return view(
            'sales::css.cancel', []
        );
    }
    
    /**
     * Trang phan tich css
     * @return void
     */
    public function analyze(){
        $htmlTeam = self::getTreeDataRecursive(null, 0, null);
        return view(
            'sales::css.analyze', [
                'htmlTeam'     => $htmlTeam,
            ]
        );
    }
    
    /**
     * trang phan tich css, thuc hien apply sau khi filter
     * @param string projectTypeIds
     * @param datetime $startDate
     * @param datetime $endDate
     * return json
     */
    public function applyAnalyze(Request $request){
        $projectTypeIds = $request->input("projectTypeIds"); 
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");
        $teamIds = $request->input("teamIds"); 
        $criteriaType = $request->input("criteriaType"); 
        $criteriaIds = $request->input("criteriaIds"); 
        
        //lay thong tin hien ket qua danh sach du an
        $data = [];
        switch ($criteriaType){
            case 'tcProjectType':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'projectType'); 
                break;
            case 'tcTeam':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'team');
                break;
            case 'tcPm':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'pm');
                break;
            case 'tcBrse':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'brse');
                break;
            case 'tcCustomer':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'customer');
                break;
            case 'tcSale':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'sale');
                break;
            case 'tcQuestion':
                $data = self::applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,'question');
                break;
        }
        
        return response()->json($data);
    }
    
    /**
     * @param string $criteriaIds
     * @param string $projectTypeIds
     * @param string $startDate
     * @param string $endDate
     * @param string $teamIds
     * @return array
     */
    protected function applyByFilter($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria){
        //lay ra cac ban ghi tu bang css_result theo loai du an, ngay lam du an, team set theo css
        if($criteria == 'projectType'){
            $cssResult = Css::getCssResultByProjectTypeIds($criteriaIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'team'){
            $cssResult = Css::getCssResultByProjectTypeIds($projectTypeIds, $startDate, $endDate,$criteriaIds);
        }else if($criteria == 'pm'){
            $cssResult = Css::getCssResultByListPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'brse'){
            $cssResult = Css::getCssResultByListBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'customer'){
            $cssResult = Css::getCssResultByListCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'sale'){
            $cssResult = Css::getCssResultByListSale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'question'){
            $cssResult = Css::getCssResultByListQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }
        
        //display chart all result
        $pointToHighchart = [];
        
        //cssResultIds list
        $cssResultIds = [];
        
        //Get data chart all result 
        foreach($cssResult as $itemResult){
            $cssResultIds[] = $itemResult->id;
            $pointToHighchart[] = (float)self::getPointCssResult($itemResult->id);
            $dateToHighchart[] = date('d/m/Y',strtotime($itemResult->created_at));
        }
        
        //Get data fill to table project list 
        $cssResultPaginate = self::showAnalyzeListProject($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria,1);
       
        //Get data fill to compare charts in analyze page
        $pointCompareChart = self::getCompareCharts($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria);
        
        //Get data fill to table criteria less 3 star
        $lessThreeStar = self::getListLessThreeStar(implode(",", $cssResultIds),1);
        
        //Get data fill to table customer's proposes
        $proposes = self::getProposes(implode(",", $cssResultIds),1);
        
        $htmlQuestionList = "<option value='0'>".Lang::get('sales::view.Please choose question')."</option>";
        if($criteria == 'question'){
            $arrProjectType = explode(",", $projectTypeIds);
            foreach($arrProjectType as $k=>$projectTypeId){
                $projectType = ($projectTypeId == 1) ? 'OSDC' : 'Project base';
                $htmlQuestionList .= "<option class=\"parent\" disabled=\"disabled\">$projectType</option>";
                $htmlQuestionList .= self::getHtmlQuestionList($projectTypeId,$startDate,$endDate,$teamIds,$criteriaIds,implode(",", $cssResultIds));
            }
            
        }
        
        $data = [
            "cssResult" => $cssResult,
            "cssResultPaginate" => $cssResultPaginate,
            "pointToHighchart" => $pointToHighchart,
            "dateToHighchart" => $dateToHighchart,
            "pointCompareChart" => $pointCompareChart,
            "lessThreeStar" =>$lessThreeStar,
            "proposes" => $proposes,
            "htmlQuestionList" => $htmlQuestionList,
        ];
        return $data;
    }
    
    /**
     * Get data fill to table project list in analyze page
     * @param string $criteriaIds
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param string $startDate
     * @param string $endDate
     * @param string $criteria
     * @param int $curPage
     * @return object list
     */
    public function showAnalyzeListProject($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria,$curPage){
        $offset = ($curPage-1) * self::$perPage; 
        
        if($criteria == 'projectType'){
            //all result to show charts
            $cssResult = Css::getCssResultByProjectTypeIds($criteriaIds, $startDate, $endDate,$teamIds);
            //result by pagination
            $cssResultPaginate = Css::getCssResultPaginateByProjectTypeIds($criteriaIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }else if($criteria == 'team'){
            $cssResult = Css::getCssResultByProjectTypeIds($projectTypeIds, $startDate, $endDate,$criteriaIds);
            $cssResultPaginate = Css::getCssResultPaginateByProjectTypeIds($projectTypeIds, $startDate, $endDate,$criteriaIds,$offset,self::$perPage);
        }else if($criteria == 'pm'){
            $cssResult = Css::getCssResultByListPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = Css::getCssResultPaginateByListPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }else if($criteria == 'brse'){
            $cssResult = Css::getCssResultByListBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = Css::getCssResultPaginateByListBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }else if($criteria == 'customer'){
            $cssResult = Css::getCssResultByListCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = Css::getCssResultPaginateByListCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }else if($criteria == 'sale'){
            $cssResult = Css::getCssResultByListSale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = Css::getCssResultPaginateByListSale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }else if($criteria == 'question'){
            $cssResult = Css::getCssResultByListQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = Css::getCssResultPaginateByListQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,$offset,self::$perPage);
        }
        
        foreach($cssResultPaginate as &$itemResultPaginate){
            $css = DB::table("css")->where("id",$itemResultPaginate->css_id)->first();
            
            //get team_id tu bang css_team
            $teamName = "";
            $team = DB::table("css_team")->where("css_id",$itemResultPaginate->css_id)->get();
            
            foreach($team as $teamId){
                if($teamName == ""){
                    $teamName = Css::getTeamNameById($teamId->team_id);
                }else{
                    $teamName .= ", " . Css::getTeamNameById($teamId->team_id);
                }
            }
            
            $itemResultPaginate->stt = ++$offset;
            $itemResultPaginate->project_name = $css->project_name;
            $itemResultPaginate->teamName = $teamName;
            $itemResultPaginate->pmName = $css->pm_name;
            $itemResultPaginate->css_created_at = date('d/m/Y',strtotime($css->created_at));
            $itemResultPaginate->created_at = date('d/m/Y',strtotime($itemResultPaginate->created_at));
            $itemResultPaginate->point = self::getPointCssResult($itemResultPaginate->id);
        }
        
        //Get html pagination render
        $totalPage = ceil(count($cssResult) / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.($curPage-1).',\''.Session::token().'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.$i.',\''.Session::token().'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.($curPage+1).',\''.Session::token().'\');" rel="next">»</a></li>';
            }
        }
        
        return $data = [
            "cssResultdata" => $cssResultPaginate,
            "paginationRender"  => $html,
        ];
    }
    
    /**
     * Get list less three star by cssResultIds
     * @param array $cssResultIds
     */
    protected function getListLessThreeStar($cssResultIds,$curPage){
        $offset = ($curPage-1) * self::$perPage;
        $lessThreeStar = Css::getListLessThreeStar($cssResultIds,$offset,self::$perPage);
        
        $result = [];
        foreach($lessThreeStar as $item){
            $cssResult = Css::getCssResultById($item->css_id);
            $cssPoint = self::getPointCssResult($item->css_id);
            $question = Css::getQuestionById($item->question_id);
            $css = Css::find($cssResult->css_id);
            
            $result[] = [
                "no"   => ++$offset,
                "projectName"   => $css->project_name,
                "questionName" => $question->content,
                "stars" => $item->point,
                "comment"   => $item->comment,
                "makeDateCss" => date('d/m/Y',strtotime($cssResult->created_at)),
                "cssPoint" => $cssPoint,
            ];
        }
        
        //Get html pagination render
        $count = Css::getCountListLessThreeStar($cssResultIds);
        $totalPage = ceil($count / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.$i.',\''.Session::token().'\',\''.$cssResultIds.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="next">»</a></li>';
            }
        }
        
        $data = [
            "cssResultdata" => $result,
            "paginationRender" => $html,
        ];
        
        return $data;
    }
    
    /**
     * get customer's proposes
     * @param array $cssResultIds
     * @param int $curPage
     */
    protected function getProposes($cssResultIds,$curPage){
        $offset = ($curPage-1) * self::$perPage;
        $proposes = Css::getProposes($cssResultIds,$offset,self::$perPage);
        $result =[];
        foreach($proposes as $propose){
            $css = Css::find($propose->css_id);
            
            $result[] = [
                "no"   => ++$offset,
                "cssPoint"   => self::getPointCssResult($propose->id),
                "projectName"   => $css->project_name,
                "customerComment" => $propose->survey_comment,
                "makeDateCss" => date('d/m/Y',strtotime($propose->created_at)),
            ];
        }
        //Get html pagination render
        $count = Css::getCountProposes($cssResultIds); 
        $totalPage = ceil($count / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.$i.',\''.Session::token().'\',\''.$cssResultIds.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="next">»</a></li>';
            }
        }
        
        $data = [
            "cssResultdata" => $result,
            "paginationRender" => $html,
        ];
        return $data;
    }
    
    /**
     * Get data fill to compare charts in analyze page
     * @param string $criteriaIds
     * @param string $teamIds
     * @param string $projectTypeIds
     * @param string $startDate
     * @param string $endDate
     * @param string $criteria
     * @return array list
     */
    public function getCompareCharts($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria){
        //lay diem theo tung loai du an de show tren bieu do phan loai theo tieu chi
        $criteriaIds = explode(",", $criteriaIds);
        
        $pointCompareChart = array();
        foreach($criteriaIds as $key => $criteriaId){
            if($criteria == 'projectType'){
                $name = Css::getProjectTypeNameById($criteriaId);
                $cssResultByCriteria = Css::getCssResultByProjectTypeId($criteriaId,$startDate,$endDate,$teamIds);
            }else if($criteria == 'team'){
                $team = Team::find($criteriaId);
                $name = $team->name;
                $cssResultByCriteria = Css::getCssResultByTeamId($criteriaId,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'pm'){
                $name = $criteriaId;
                $cssResultByCriteria = Css::getCssResultByPmName($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'brse'){
                $name = $criteriaId;
                $cssResultByCriteria = Css::getCssResultByBrseName($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'customer'){
                $name = $criteriaId;
                $cssResultByCriteria = Css::getCssResultByCustomerName($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'sale'){
                $name = $criteriaId;
                $cssResultByCriteria = Css::getCssResultBySale($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'question'){
                $question = Css::getQuestionById($criteriaId);
                $name = $question->content;
                $cssResultByCriteria = Css::getCssResultByQuestionToChart($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }
            
            $pointToHighchart = [];
            
            $pointToHighchart["data"] = [];
            if($criteria == 'question'){
                $pointToHighchart["name"] = self::getNumberQuestion($criteriaId);
                foreach($cssResultByCriteria as $itemCssResult){
                    $css_result_detail = Css::getCssResultDetail($itemCssResult->id,$criteriaId);
                    if($css_result_detail->point > 0){
                        $pointToHighchart["data"][] = $css_result_detail->point;
                    }else{
                        $pointToHighchart["data"][] = null;
                    }
                }
            }else{
                $pointToHighchart["name"] = $name;
                foreach($cssResultByCriteria as $item){
                    $pointToHighchart["data"][] = (float)self::getPointCssResult($item->id);
                }
            }
            $pointCompareChart[] = [
                "name" => $pointToHighchart["name"],
                "data" => $pointToHighchart["data"]
            ];
        }
        
        return $pointCompareChart;
    }


    /**
     * trang phan tich css, thuc hien B1. Filter
     * @param string startDate
     * @param string endDate
     * @param string projectTypeIds
     */
    public function filterAnalyze(Request $request){
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");
        $projectTypeIds = $request->input("projectTypeIds"); 
        $teamIds = $request->input("teamIds"); 
        
        $result["projectType"] = self::filterAnalyzeByProjectType($startDate, $endDate, $projectTypeIds,$teamIds);
        $result["team"] = self::filterAnalyzeByTeam($startDate, $endDate, $projectTypeIds,$teamIds);
        $result["pm"] = self::filterAnalyzeByPmOrBrseOrCustomerOrSale($startDate, $endDate, $projectTypeIds,$teamIds,'pm');
        $result["brse"] = self::filterAnalyzeByPmOrBrseOrCustomerOrSale($startDate, $endDate, $projectTypeIds,$teamIds,'brse');
        $result["customer"] = self::filterAnalyzeByPmOrBrseOrCustomerOrSale($startDate, $endDate, $projectTypeIds,$teamIds,'customer');        
        $result["sale"] = self::filterAnalyzeByPmOrBrseOrCustomerOrSale($startDate, $endDate, $projectTypeIds,$teamIds,'sale');        
        $result["question"] = self::filterAnalyzeByQuestion($startDate, $endDate, $projectTypeIds,$teamIds);        
        
        return response()->view('sales::css.include.table_theotieuchi', $result);
    }
    
    /**
     * show data filter by project type
     * @param string $startDate
     * @param string $endDate
     * @param string $projectTypeIds
     * @param string $teamIds
     * return array
     */
    protected function filterAnalyzeByProjectType($startDate, $endDate, $projectTypeIds,$teamIds){
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $css = array();
        $result = array();
        $no = 0;
        foreach($arrProjectTypeId as $k => $projectTypeId){
            $projectTypeName = ($projectTypeId == 1) ? 'OSDC' : 'Project base';
            $points = array();
            $css = Css::getCssByProjectTypeAndTeam($projectTypeId,$teamIds);
            if(count($css) > 0){
                $countCss = 0;
                foreach($css as $itemCss){
                    $css_result = Css::getCssResultByCssId($itemCss->id,$startDate,$endDate);
                    if(count($css_result) > 0){
                        $countCss += count($css_result);
                        foreach($css_result as $itemCssResult){
                            $points[] = self::getPointCssResult($itemCssResult->id);
                        }
                    }
                }

                if(count($points) > 0){
                    $avgPoint = array_sum($points) / count($points);
                    $no++;
                    $result[] = [
                        "no"                => $no,
                        "projectTypeId"     => $projectTypeId,
                        "projectTypeName"   => $projectTypeName,
                        "countCss"          => $countCss,
                        "maxPoint"          => self::formatNumber(max($points)),
                        "minPoint"          => self::formatNumber(min($points)),
                        "avgPoint"          => self::formatNumber($avgPoint),
                    ];
                }else{
                    $no++;
                    $result[] = [
                        "no"                => $no,
                        "projectTypeId"     => $projectTypeId,
                        "projectTypeName"   => $projectTypeName,
                        "countCss"          => 0,
                        "maxPoint"          => "-",
                        "minPoint"          => "-",
                        "avgPoint"          => "-",
                    ];
                }
            }else{
                $no++;
                $result[] = [
                    "no"                => $no,
                    "projectTypeId"     => $projectTypeId,
                    "projectTypeName"   => $projectTypeName,
                    "countCss"          => 0,
                    "maxPoint"          => "-",
                    "minPoint"          => "-",
                    "avgPoint"          => "-",
                ];
            }
        }
        
        return $result;
    }
    
    protected function filterAnalyzeByQuestion($startDate, $endDate, $projectTypeIds,$teamIds){
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $cssCateList = array();
        foreach($arrProjectTypeId as $key => $projectTypeId){
            $cssCate = self::getListQuestionByProjectType($projectTypeId,$startDate,$endDate,$teamIds);
            $cssCateList[] = [
                "id"    => $projectTypeId,
                "name"   => ($projectTypeId == 1) ? 'OSDC' : 'Project base',
                "cssCate" => $cssCate
            ]; 
        }
        
        return $cssCateList;
    }

    /**
     * Show data filter by team
     * @param string $startDate
     * @param string $endDate
     * @param string $projectTypeIds
     * @param string $teamIds
     * return array
     */
    protected function filterAnalyzeByTeam($startDate, $endDate, $projectTypeIds,$teamIds){
        $arrTeamId = explode(",", $teamIds);
        $css = array();
        $result = array();
        $no = 0;
        foreach($arrTeamId as $k => $teamId){
            $points = array();
            $css = Css::getCssByTeamIdAndListProjectType($teamId,$projectTypeIds);
            $team = Team::find($teamId);
            $teamId = $team->id;
            $teamName = $team->name;
            if(count($css) > 0){
                $countCss = 0;
                foreach($css as $itemCss){
                    $css_result = Css::getCssResultByCssId($itemCss->id,$startDate,$endDate);

                    if(count($css_result) > 0){
                        $countCss += count($css_result);
                        foreach($css_result as $itemCssResult){
                            $points[] = self::getPointCssResult($itemCssResult->id);
                        }
                    }
                }

                if(count($points) > 0){
                    $avgPoint = array_sum($points) / count($points);
                    $no++;
                    $result[] = [
                        "no"                => $no,
                        "teamId"            => $teamId,
                        "teamName"          => $teamName,
                        "countCss"          => $countCss,
                        "maxPoint"          => self::formatNumber(max($points)),
                        "minPoint"          => self::formatNumber(min($points)),
                        "avgPoint"          => self::formatNumber($avgPoint),
                    ];
                }else{
                    $no++;
                    $result[] = [
                        "no"                => $no,
                        "teamId"            => $teamId,
                        "teamName"          => $teamName,
                        "countCss"          => 0,
                        "maxPoint"          => "-",
                        "minPoint"          => "-",
                        "avgPoint"          => "-",
                    ];
                }
            }else{
                $no++;
                $result[] = [
                    "no"                => $no,
                    "teamId"            => $teamId,
                    "teamName"          => $teamName,
                    "countCss"          => 0,
                    "maxPoint"          => "-",
                    "minPoint"          => "-",
                    "avgPoint"          => "-",
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Show data filter by PM or BrSE or Customer or Sale
     * @param string $startDate
     * @param string $endDate
     * @param string $projectTypeIds
     * @param string $teamIds
     * @param string $criteria
     * return array
     */
    protected function filterAnalyzeByPmOrBrseOrCustomerOrSale($startDate, $endDate, $projectTypeIds,$teamIds,$criteria){
        $css = array();
        $result = array();
        $no = 0;
        
        if($criteria == "pm"){
            $listResult = CSS::getListPm();
        }else if($criteria == "brse"){
            $listResult = CSS::getListBrse();
        }else if($criteria == "customer"){
            $listResult = CSS::getListCustomer();
        }else if($criteria == "sale"){
            $listResult = CSS::getListSale();
        }
        if(count($listResult) > 0){
            foreach($listResult as $itemList){
                $points = array();
                if($criteria == "pm"){
                    $css = Css::getCssByPmAndTeamIdsAndListProjectType($itemList->pm_name, $teamIds,$projectTypeIds);
                }else if($criteria == "brse"){
                    $css = Css::getCssByBrseAndTeamIdsAndListProjectType($itemList->brse_name, $teamIds,$projectTypeIds);
                }else if($criteria == "customer"){
                    $css = Css::getCssByCustomerAndTeamIdsAndListProjectType($itemList->customer_name, $teamIds,$projectTypeIds);
                }else if($criteria == "sale"){
                    $css = Css::getCssBySaleAndTeamIdsAndListProjectType($itemList->employee_id, $teamIds,$projectTypeIds);
                }

                $countCss = 0;
                if(count($css) > 0){
                    foreach($css as $itemCss){
                        $css_result = DB::table("css_result")
                        ->where("css_id",$itemCss->id)
                        ->where("created_at", ">=", $startDate)
                        ->where("created_at", "<=", $endDate)
                        ->get();

                        if(count($css_result) > 0){
                            $countCss += count($css_result);
                            foreach($css_result as $itemCssResult){
                                $points[] = self::getPointCssResult($itemCssResult->id);
                            }
                        }
                    }

                    if($criteria == "pm"){
                        $id = $itemList->pm_name;
                        $name = $itemList->pm_name;
                    } else if($criteria == "brse"){
                        $id = $itemList->brse_name;
                        $name = $itemList->brse_name;
                    } else if($criteria == "customer"){
                        $id = $itemList->customer_name; 
                        $name = $itemList->customer_name; 
                    } else if($criteria == "sale"){
                        $employee = Employees::find($itemList->employee_id);
                        $id = $itemList->employee_id;
                        $name = $employee->name; 
                    } 

                    if(count($points) > 0){
                        $avgPoint = array_sum($points) / count($points);
                        $no++;
                        $result[] = [
                            "no"                => $no,
                            "id"                => $id,
                            "name"              => $name,
                            "countCss"          => $countCss,
                            "maxPoint"          => self::formatNumber(max($points)),
                            "minPoint"          => self::formatNumber(min($points)),
                            "avgPoint"          => self::formatNumber($avgPoint),
                        ];
                    }else{
                        $no++;
                        $result[] = [
                            "no"                => $no,
                            "id"                => $id,
                            "name"              => $name,
                            "countCss"          => 0,
                            "maxPoint"          => "-",
                            "minPoint"          => "-",
                            "avgPoint"          => "-",
                        ];
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * ham format number voi 2 so thap phan
     * @param float $number
     * @return float
     */
    protected function formatNumber($number){
        return number_format($number, 2, ".",",");
    }


    /**
     * Tinh diem cua mot bai danh gia (css_result)
     * @param int $cssResultId
     * @return int
     */
    protected function getPointCssResult($cssResultId){
        $cssResult = CssResult::find($cssResultId);
        $cssResultDetailModel = new CssResultDetail();
        $cssResultDetail = $cssResultDetailModel->getResultDetailByCssResult($cssResultId);
        $pointChild = 0;
        $tongSoCauDanhGia = 0;
        foreach($cssResultDetail as $itemDetail){
            $pointChild += $itemDetail->point;
            if($itemDetail->point > 0){
                $tongSoCauDanhGia++;
            }
        }
        if($pointChild == 0){
            $point = $cssResult->avg_point * 20;
        }else{
            $point = $cssResult->avg_point * 4 + $pointChild/($tongSoCauDanhGia * 5) * 80;
        }
        
        return self::formatNumber($point);
    }
    
    /**
     * get team tree option recursive
     * 
     * @param int $id
     * @param int $level
     */
    protected static function getTreeDataRecursive($parentId = 0, $level = 0, $idActive = null) 
    {
        $teamList = Team::select('id', 'name', 'parent_id')
                ->where('parent_id', $parentId)
                ->orderBy('sort_order', 'asc')
                ->get();
        $countCollection = count($teamList);
        if (!$countCollection) {
            return;
        }
        $html = '';
        $i = 0;
        foreach ($teamList as $team) {
            $classLi = '';
            $classLabel = 'icheckbox-container label-normal';
            $optionA = " data-id=\"{$team->id}\"";
            $classA = '';
            if ($i == $countCollection - 1) {
                $classLi = 'last';
            }
            if ($team->id == $idActive) {
                $classA .= 'active';
            }
            $classLi = $classLi ? " class=\"{$classLi}\"" : '';
            $classLabel = $classLabel ? " class=\"{$classLabel}\"" : '';
            $classA = $classA ? " class=\"{$classA}\"" : '';
            
            $htmlChild = self::getTreeDataRecursive($team->id, $level + 1, $idActive);
            $hrefA = route('team::setting.team.view', ['id' => $team->id]);
            $html .= "<li{$classLi}>";
            $html .= "<label{$classLabel}>";
            $html .= "<div class=\"icheckbox\">";
            if($htmlChild == ""){
                $html .= '<input type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;<span>' .$team->name. '</span>';
            }else{
                $html .= '<span>&nbsp;&nbsp;' .$team->name. '</span>';
            }
            
            $html .= '</div>';
            $html .= '</label>';
            
            if ($html) {
                $html .= '<ul>';
                $html .= $htmlChild;
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
    
    /**
     * get questions list
     * @param int $projectTypeId
     * @param string $startDate
     * @param string $endDate
     * @param string $teamIds
     * @return type
     */
    protected function getListQuestionByProjectType($projectTypeId,$startDate,$endDate,$teamIds){
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        $cssResultDetailModel = new CssResultDetail();
        
        $rootCategory = $cssCategoryModel->getRootCategory($projectTypeId);
        $cssCategory = $cssCategoryModel->getCategoryByParent($rootCategory->id);
        $cssCate = array();
        if ($cssCategory) {
            foreach ($cssCategory as $item) {
                $cssCategoryChild = $cssCategoryModel->getCategoryByParent($item->id);
                $cssCateChild = array();
                if ($cssCategoryChild) {
                    foreach ($cssCategoryChild as $itemChild) {
                        $cssQuestionChild = $cssQuestionModel->getQuestionByCategory($itemChild->id);
                        foreach($cssQuestionChild as &$itemQuestionChild){
                            $cssResult = Css::getCssResultByQuestion($itemQuestionChild->id,$startDate, $endDate,$teamIds);
                            if(count($cssResult) > 0){
                                $countCss = 0;
                                $points = array();
                                foreach($cssResult as $itemCssResult){
                                    $cssResultDetail = $cssResultDetailModel->getResultDetailRow($itemCssResult->id,$itemQuestionChild->id);
                                    if(count($cssResultDetail)){
                                        if($cssResultDetail->point > 0){
                                            $points[] = $cssResultDetail->point;
                                            $countCss++;
                                        }
                                    }
                                }
                                $itemQuestionChild->countCss = $countCss;
                                $itemQuestionChild->maxPoint = (count($points) > 0) ? self::formatNumber(max($points)) : "-";
                                $itemQuestionChild->minPoint = (count($points) > 0) ? self::formatNumber(min($points)) : "-";
                                if(count($points) > 0){
                                    $avgPoint = array_sum($points) / count($points);
                                    $itemQuestionChild->avgPoint = self::formatNumber($avgPoint);
                                }else{
                                    $itemQuestionChild->avgPoint = "-";
                                }
                            }else{
                                $itemQuestionChild->countCss = 0;
                                $itemQuestionChild->maxPoint = "-";
                                $itemQuestionChild->minPoint = "-";
                                $itemQuestionChild->avgPoint = "-";
                            }
                        }

                        $cssCateChild[] = array(
                            "id" => $itemChild->id,
                            "name" => $itemChild->name,
                            "parentId" => $item->id,
                            "questionsChild" => $cssQuestionChild,
                        );
                    }
                }

                $cssQuestion = $cssQuestionModel->getQuestionByCategory($item->id);
                foreach($cssQuestion as &$itemQuestion){
                    $cssResult = Css::getCssResultByQuestion($itemQuestion->id,$startDate, $endDate,$teamIds);
                    if(count($cssResult) > 0){
                        $countCss = 0;
                        $points = array();
                        foreach($cssResult as $itemCssResult){
                            $cssResultDetail = $cssResultDetailModel->getResultDetailRow($itemCssResult->id,$itemQuestion->id);
                            if($cssResultDetail->point > 0){
                                $points[] = $cssResultDetail->point;
                                $countCss++;
                            }
                        }
                        //echo count($points);die;
                        $itemQuestion->countCss = $countCss;
                        $itemQuestion->maxPoint = (count($points) > 0) ? self::formatNumber(max($points)) : "-";
                        $itemQuestion->minPoint = (count($points) > 0) ? self::formatNumber(min($points)) : "-";
                        if(count($points) > 0){
                            $avgPoint = array_sum($points) / count($points);
                            $itemQuestion->avgPoint = self::formatNumber($avgPoint);
                        }else{
                            $itemQuestion->avgPoint = "-";
                        }
                    }else{
                        $itemQuestion->countCss = 0;
                        $itemQuestion->maxPoint = "-";
                        $itemQuestion->minPoint = "-";
                        $itemQuestion->avgPoint = "-";

                    }
                }
                $cssCate[] = array(
                    "id" => $item->id,
                    "name" => $item->name,
                    "parentId" => $item->parent_id,
                    "cssCateChild" => $cssCateChild,
                    "questions" => $cssQuestion,
                );
            }

        }
        
        return $cssCate;
    }
    
    /**
     * 
     * @param int $projectTypeId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @param string $questionIds
     * @param string $cssResultIds
     * @return string
     */
    protected function getHtmlQuestionList($projectTypeId,$startDate,$endDate,$teamIds,$questionIds,$cssResultIds){
        $arrQuestionId = explode(",", $questionIds); 
        $cssCate = self::getListQuestionByProjectType($projectTypeId,$startDate,$endDate,$teamIds);
        $html = "";
        foreach($cssCate as $itemCate){
            $html .= "<option class=\"parent\" disabled=\"disabled\">-- ".$itemCate["name"]."</option>";
            foreach($itemCate["cssCateChild"] as $itemCateChild){
                $html .= "<option class=\"parent\" disabled=\"disabled\">---- ".$itemCateChild["name"]."</option>";
                foreach($itemCateChild["questionsChild"] as $itemQuestionChild){
                    if(in_array($itemQuestionChild->id, $arrQuestionId)){
                        $html .= '<option value="'.$itemQuestionChild->id.'" data-token="'.Session::token().'" data-cssresult="'.$cssResultIds.'" >------ '.$itemQuestionChild->content.'</option>';
                    }
                }
            }
            foreach($itemCate["questions"] as $itemQuestion){
                if(in_array($itemQuestion->id, $arrQuestionId)){
                    $html .= '<option value="'.$itemQuestion->id.'" data-token="'.Session::token().'" data-cssresult="'.$cssResultIds.'">------ '.$itemQuestion->content.'</option>';
                }
            }
        }
        
        return $html;
    }
    
    /**
     * Get list less three star by cssResultIds and questionId
     * @param int $questionId
     * @param array $cssResultIds
     * @param int $curPage
     */
    protected function getListLessThreeStarByQuestion($questionId,$cssResultIds,$curPage){
        $offset = ($curPage-1) * self::$perPage;
        $lessThreeStar = Css::getListLessThreeStarByQuestionId($questionId,$cssResultIds,$offset,self::$perPage);
        
        $result = [];
        foreach($lessThreeStar as $item){
            $cssResult = Css::getCssResultById($item->css_id);
            $cssPoint = self::getPointCssResult($item->css_id);
            $question = Css::getQuestionById($item->question_id);
            $css = Css::find($cssResult->css_id);
            
            $result[] = [
                "no"   => ++$offset,
                "projectName"   => $css->project_name,
                "questionName" => $question->content,
                "stars" => $item->point,
                "comment"   => $item->comment,
                "makeDateCss" => date('d/m/Y',strtotime($cssResult->created_at)),
                "cssPoint" => $cssPoint,
            ];
        }
        
        //Get html pagination render
        $count = Css::getCountListLessThreeStarByQuestion($questionId,$cssResultIds);
        $totalPage = ceil($count / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.$i.',\''.Session::token().'\',\''.$cssResultIds.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="next">»</a></li>';
            }
        }
        
        $data = [
            "cssResultdata" => $result,
            "paginationRender" => $html,
        ];
        
        return $data;
    }
    
    /**
     * get customer's proposes
     * @param int $questionId
     * @param array $cssResultIds
     * @param int $curPage
     */
    protected function getProposesByQuestion($questionId,$cssResultIds,$curPage){
        $offset = ($curPage-1) * self::$perPage;
        $proposes = Css::getProposesByQuestion($questionId,$cssResultIds,$offset,self::$perPage);
        
        $result =[];
        foreach($proposes as $propose){
            $css = Css::find($propose->css_id);
            
            $result[] = [
                "no"   => ++$offset,
                "cssPoint"   => self::getPointCssResult($propose->id),
                "projectName"   => $css->project_name,
                "customerComment" => $propose->survey_comment,
                "makeDateCss" => date('d/m/Y',strtotime($propose->created_at)),
            ];
        }
        //Get html pagination render
        $count = Css::getCountProposesByQuestion($questionId,$cssResultIds); 
        $totalPage = ceil($count / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.$i.',\''.Session::token().'\',\''.$cssResultIds.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\');" rel="next">»</a></li>';
            }
        }
        
        $data = [
            "cssResultdata" => $result,
            "paginationRender" => $html,
        ];
        return $data;
    }
    
    /**
     * 
     * @param int $questionId
     * return string
     */
    protected function getNumberQuestion($questionId){
        $question = Css::getQuestionById($questionId);
        $arr = explode(".", $question->content, 2);
        return $arr[0];
    }
    
    public function romanic_number($integer, $upcase = true) 
    { 
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
        $return = ''; 
        while($integer > 0) 
        { 
            foreach($table as $rom=>$arb) 
            { 
                if($integer >= $arb) 
                { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                } 
            } 
        } 

        return $return; 
    }  
}

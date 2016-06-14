<?php

namespace Rikkei\Sales\Http\Controllers;

use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use DB;
use Rikkei\Core\Model\User;
use Rikkei\Sales\Model\ProjectType;
use Rikkei\Sales\Model\Css;
use Rikkei\Team\Model\Team;
use Lang;
use Mail;
use Session;

class CssController extends Controller {
    static $perPage = 5;
    
    /**
     * Hàm hiển thị form tạo CSS
     */
    public function create() {
        $user = Auth::user();
        $projects = ProjectType::all();
        $teams = Team::all();
        $htmlTeam = self::getTreeDataRecursive(0, 0, null);

        return view(
                'sales::css.create_css', 
                [
                    'user'      => $user,
                    "projects"  => $projects,
                    "teams"     => $teams,
                    "htmlTeam"  => $htmlTeam,
                ]
        );
    }

    /**
     * Hàm hiển thị form sửa CSS
     * @param int $id
     */
    public function update($id) {
        $css = Css::where('id', $id)->first();

        //get user tao css       
        $user = User::find($css->user_id);

        //get list projects va teams
        $projects = ProjectType::all();
        $teams = Team::all();

        //get list team_id cua css theo css_id
        $team_ids = array();
        $team_id_by_css_id = DB::table('css_team')->where('css_id', $id)->get();

        foreach ($team_id_by_css_id as $team) {
            $team_ids[] = $team->team_id;
        }

        //get team cua css
        $teams_set = DB::table('team')->whereIn('id', $team_ids)->get();

        //text hien thi cac team cua CSS ra trang update
        //ngan cach nhau bang dau ','
        $str_teams_set_name = array();
        foreach ($teams_set as $team) {
            $str_teams_set_name[] = $team->name;
        }
        $str_teams_set_name = implode(', ', $str_teams_set_name);
        
        $htmlTeam = self::getTreeDataRecursive(0, 0, null);
        return view(
            'sales::css.update_css', 
            [
                'css' => $css, //css by css_id
                'user' => $user, //user by css_id
                "projects" => $projects, // all project type
                "teams" => $teams, //all team
                "teams_set" => $teams_set, // team lien quan cua CSS
                "str_teams_set_name" => $str_teams_set_name, 
                "htmlTeam"  => $htmlTeam,
            ]
        );
    }

    /**
     * Hàm hiển thị trang preview sau khi tạo CSS
     * @param string $token
     * @param int $id
     * @return objects
     */
    public function preview($token, $id) {
        $css = Css::where('id', $id)
                ->where('token', $token)
                ->first();

        if ($css) {
            $user = User::find($css->user_id);
            return view('sales::css.preview_css', [
                'css' => $css,
                "user" => $user
                    ]
            );
        } else {
            return redirect("/");
        }
    }

    /**
     * Hàm save CSS vào database
     * @return void
     */
    public function save() {
        if (Auth::check() && $_POST) {
            $start_date = date('Y-m-d', strtotime($_POST["start_date"]));
            $end_date = date('Y-m-d', strtotime($_POST["end_date"]));

            if ($_POST["create_or_update"] == 'create') {
                $css = new Css;
            } else {
                $css_id = $_POST["css_id"];
                $css = Css::find($css_id);
            }

            //insert hoac vao bang css
            $css->user_id = $_POST["user_id"];
            $css->company_name = $_POST["company_name"];
            $css->customer_name = $_POST["customer_name"];
            $css->project_name = $_POST["project_name"];
            $css->brse_name = $_POST["brse_name"];
            $css->start_date = $start_date;
            $css->end_date = $end_date;
            $css->pm_name = $_POST["pm_name"];
            $css->project_type_id = $_POST["project_type_id"];

            if ($_POST["create_or_update"] == 'create') {
                //tao token
                $css->token = md5(rand());
                //Neu user chua co ten Tieng Nhat thi update
                $user = Auth::user();
                if (!$user->japanese_name) {
                    $user->japanese_name = $_POST["japanese_name"];
                    $user->save();
                }
            } else { // update thi delete css_id tu table css_team
                DB::table('css_team')->where('css_id', $css_id)->delete();
            }

            $css->save();

            //insert vao bang css_team
            $teams = $_POST["teams"];
            foreach ($teams as $k => $v) {
                DB::table('css_team')->insert(
                        array(
                            'css_id' => $css->id,
                            'team_id' => $k
                        )
                );
            }

            return redirect('/css/preview/' . $css->token . '/' . $css->id);
        }
    }

    /**
     * Hàm hiển thị trang Welcome và trang làm CSS
     * @param string $token
     * @param int $id
     * @return objects
     */
    public function make($token, $id) {
        $css = Css::where('id', $id)
                ->where('token', $token)
                ->first();

        if ($css) {
            $user = User::find($css->user_id);
            $cssCategory = DB::table('css_category')->where('parent_id', $css->project_type_id)->get();
            $cssCate = array();
            if ($cssCategory) {
                foreach ($cssCategory as $item) {
                    $cssCategoryChild = DB::table('css_category')->where('parent_id', $item->id)->get();
                    $cssCateChild = array();
                    if ($cssCategoryChild) {
                        foreach ($cssCategoryChild as $item_child) {
                            $cssQuestionChild = DB::table('css_question')->where('category_id', $item_child->id)->get();
                            $cssCateChild[] = array(
                                "id" => $item_child->id,
                                "name" => $item_child->name,
                                "parent_id" => $item->id,
                                "questionsChild" => $cssQuestionChild,
                            );
                        }
                    }

                    $cssQuestion = DB::table('css_question')->where('category_id', $item->id)->get();
                    $cssCate[] = array(
                        "id" => $item->id,
                        "name" => $item->name,
                        "cssCateChild" => $cssCateChild,
                        "questions" => $cssQuestion,
                    );
                }
            }
            
            $arrayValidate = array(
                "nameRequired" => Lang::get('sales::message.Name validate required'),
                "emailRequired" => Lang::get('sales::message.Email validate required'),
                "emailAddress" => Lang::get('sales::message.Email validate address'),
                "totalMarkValidateRequired" => Lang::get('sales::message.Total mark validate required'),
                "questionCommentRequired" => Lang::get('sales::message.Question comment required'),
                "proposedRequired"  => Lang::get('sales::message.Proposed required'),
            );
            if(Auth::check()){}
            return view(
                'sales::css.makecss', [
                    'css' => $css,
                    "user" => $user,
                    "cssCate" => $cssCate,
                    "arrayValidate" => json_encode($arrayValidate)
                ]
            );
        } else {
            return redirect("/");
        }
    }
    
    /**
     * Hàm insert bai lam CSS vao database
     * @return void
     */
    public function saveResult(){
        $arrayQuestion = $_REQUEST['arrayQuestion'];
        $name = $_REQUEST['make_name'];
        $email = $_REQUEST['make_email'];
        $avgPoint = $_REQUEST['totalMark'];
        $comment = $_REQUEST['comment'];
        $survey_comment = $_REQUEST['proposed'];
        $cssId = $_REQUEST['cssId'];
       
        $css_result_id = DB::table('css_result')->insertGetId(
            array(
                'css_id' => $cssId,
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'avg_point' => $avgPoint,
                'name' => $name,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
                'survey_comment' => $survey_comment
            )
        );
       
        if(count($arrayQuestion) > 0){
           $countQuestion = count($arrayQuestion);
           for($i=0; $i<$countQuestion; $i++){
                DB::table('css_result_detail')->insert(
                    array(
                        'css_id' => $css_result_id,
                        'question_id' => $arrayQuestion[$i][0],
                        'point' => $arrayQuestion[$i][1],
                        'comment' => $arrayQuestion[$i][2],
                    )
                );
            }
        }
        
        $css = Css::find($cssId);
        $user = DB::table('users')->where('id', $css->user_id)->first();
        $email = $user->email; 
        $data = array(
            'href' => url('/') . "/css/detail/" . $css_result_id,
            'project_name' => $css->project_name,
        );

        Mail::send('sales::css.sendMail', $data, function ($message) use($email) {
            $message->from('sales@rikkeisoft.com', 'Rikkeisoft');
            $message->to($email)->subject(Lang::get('sales::view.Subject email notification make css'));

        });

        
    }
    
    /**
     * Hien thi danh sach CSS
     * @return void
     */
    public function grid(){
        $css = DB::table('css')->orderBy('id', 'desc')->paginate(10);
        
        $i = ($css->currentPage()-1) * $css->perPage() + 1;
        foreach($css as &$item){
            $item->stt = $i;
            $i++;
            $project_type = DB::table('project_type')->where('id',$item->project_type_id)->first();
            $item->project_type_name = $project_type->name;
            $css_team_list = DB::table('css_team')->where('css_id',$item->id)->get();
            
            $arr_team = array();
            foreach($css_team_list as $css_team_child){
                $team = Team::find($css_team_child->team_id);
                $arr_team[] = $team->name;
            }
            $item->teams_name = implode(",", $arr_team);
            
            $user = User::find($item->user_id);
            $item->sale_name = $user->name;
            $item->start_date = date('d/m/Y',strtotime($item->start_date));
            $item->end_date = date('d/m/Y',strtotime($item->end_date));
            $item->create_date = date('d/m/Y',strtotime($item->created_at));
            
            /* get count css result by cssId */
            $item->countCss = DB::table('css_result')->where("css_id",$item->id)->count();
        }
        
        return view(
            'sales::css.list', [
                'css' => $css,
            ]
        );
    }
    
    /**
     * Hien thi danh sach bai lam CSS theo cssId
     * @param int $cssId
     * @return void
     */
    public function view($cssId){
        $css = Css::find($cssId);
        $css_result_list = DB::table('css_result')->where("css_id",$cssId)->orderBy('id', 'desc')->paginate(10);
        $i = ($css_result_list->currentPage()-1) * $css_result_list->perPage() + 1;
        foreach($css_result_list as &$item){
            $item->stt = $i;
            $i++;
            $item->make_date = date('d/m/Y',strtotime($item->created_at));
            
            $css_result_detail = DB::table('css_result_detail')->where("css_id",$item->id)->get();
            $mark_child = 0;
            $tongSoCauDanhGia = 0;
            foreach($css_result_detail as $item_detail){
                $mark_child += $item_detail->point;
                if($item_detail->point > 0){
                    $tongSoCauDanhGia++;
                }
            }
            if($mark_child == 0){
                $mark = $item->avg_point * 20;
            }else{
                $mark = $item->avg_point * 4 + $mark_child/($tongSoCauDanhGia * 5) * 80;
            }
            
            $item->mark = $mark;
        }
        return view(
            'sales::css.view', [
                'css_result_list' => $css_result_list,
                'css' => $css,
            ]
        );
    }
    
    /**
     * Hien thi chi tiet 1 bai danh gia
     * @param int $result_id
     * @return void
     */
    public function detail($result_id){
        $css_result = DB::table('css_result')->where("id",$result_id)->first();
        $css = Css::find($css_result->css_id);
        $user = User::find($css->user_id);
        $cssCategory = DB::table('css_category')->where('parent_id', $css->project_type_id)->get();
        foreach($cssCategory as $item){
            $cssCategoryChild = DB::table('css_category')->where('parent_id', $item->id)->get();
            $cssCateChild = array();
            if ($cssCategoryChild) {
                foreach ($cssCategoryChild as $item_child) {
                    $cssQuestionChild = DB::table('css_question')->where('category_id', $item_child->id)->get();
                    $questionsChild = array();
                    foreach($cssQuestionChild as &$question){
                        $getQuestionsIdByCssResult = DB::table('css_result_detail')
                                ->where("css_id",$result_id)
                                ->where("question_id",$question->id)
                                ->first();
                        $question->point = $getQuestionsIdByCssResult->point;
                        $question->comment = $getQuestionsIdByCssResult->comment;
                        if($getQuestionsIdByCssResult){
                            $questionsChild[] = $question;
                        }
                    }
                    $cssCateChild[] = array(
                        "id" => $item_child->id,
                        "name" => $item_child->name,
                        "parent_id" => $item->id,
                        "questionsChild" => $questionsChild,
                    );
                }
            }

            $cssQuestion = DB::table('css_question')->where('category_id', $item->id)->get();
            $questions = array();
            foreach($cssQuestion as $question){
                $getQuestionsIdByCssResult = DB::table('css_result_detail')
                        ->where("css_id",$result_id)
                        ->where("question_id",$question->id)
                        ->first();
                $question->point = $getQuestionsIdByCssResult->point;
                $question->comment = $getQuestionsIdByCssResult->comment;
                if($getQuestionsIdByCssResult){
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
        
        $css_result_detail = DB::table('css_result_detail')->where("css_id",$result_id)->get();
        $mark_child = 0;
        $tongSoCauDanhGia = 0;
        foreach($css_result_detail as $item_detail){
            $mark_child += $item_detail->point;
            if($item_detail->point > 0){
                $tongSoCauDanhGia++;
            }
        }
        if($mark_child == 0){
            $mark = $css_result->avg_point * 20;
        }else{
            $mark = $css_result->avg_point * 4 + $mark_child/($tongSoCauDanhGia * 5) * 80;
        }

        $css_result->mark = $mark;
        return view(
            'sales::css.detail', [
                'css' => $css,
                "user" => $user,
                "cssCate" => $cssCate,
                "css_result" => $css_result,
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
        $projectTypes = ProjectType::all();
        $htmlTeam = self::getTreeDataRecursive(0, 0, null);
        return view(
            'sales::css.analyze', [
                'projectTypes' => $projectTypes,
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
    public function applyAnalyze(){
        $projectTypeIds = $_POST["projectTypeIds"]; 
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
        $teamIds = $_POST["teamIds"]; 
        $criteriaType = $_POST["criteriaType"]; 
        $criteriaIds = $_POST["criteriaIds"]; 
        
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
                $projectType = ProjectType::find($projectTypeId);
                $htmlQuestionList .= "<option class=\"parent\" disabled=\"disabled\">$projectType->name</option>";
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
                $pointToHighchart["name"] = '';
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
    public function filterAnalyze(){
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
        $projectTypeIds = $_POST["projectTypeIds"]; 
        $teamIds = $_POST["teamIds"]; 
        
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
        foreach($arrProjectTypeId as $k => $v){
            $points = array();
            
            if($teamIds == ""){
                $css = DB::table("css")->where("project_type_id",$v)->get();
            }else{
                $css = Css::getCssByProjectTypeAndTeam($v,$teamIds);
            }
            
            $projectType = ProjectType::find($v);
            $projectTypeId = $projectType->id;
            $projectTypeName = $projectType->name;
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
                    "maxPoint"          => 0,
                    "minPoint"          => 0,
                    "avgPoint"          => 0,
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
            $projectType = ProjectType::find($projectTypeId);
            $cssCateList[] = [
                "id"    => $projectTypeId,
                "name"   => $projectType->name,
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
            
            $countCss = 0;
            $team = Team::find($teamId);
            $teamId = $team->id;
            $teamName = $team->name;
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
                    "maxPoint"          => 0,
                    "minPoint"          => 0,
                    "avgPoint"          => 0,
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
        
        foreach($listResult as $itemList){
            $points = array();
            if($criteria == "pm"){
                $css = Css::getCssByPmAndTeamIdsAndListProjectType($itemList->pm_name, $teamIds,$projectTypeIds);
            }else if($criteria == "brse"){
                $css = Css::getCssByBrseAndTeamIdsAndListProjectType($itemList->brse_name, $teamIds,$projectTypeIds);
            }else if($criteria == "customer"){
                $css = Css::getCssByCustomerAndTeamIdsAndListProjectType($itemList->customer_name, $teamIds,$projectTypeIds);
            }else if($criteria == "sale"){
                $css = Css::getCssBySaleAndTeamIdsAndListProjectType($itemList->user_id, $teamIds,$projectTypeIds);
            }
            
            $countCss = 0;
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
                $user = User::find($itemList->user_id);
                $id = $itemList->user_id;
                $name = $user->name; 
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
                    "maxPoint"          => 0,
                    "minPoint"          => 0,
                    "avgPoint"          => 0,
                ];
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
        $cssResult = DB::table("css_result")->where("id",$cssResultId)->first();
        
        $css_result_detail = DB::table('css_result_detail')->where("css_id",$cssResultId)->get();
        $point_child = 0;
        $tongSoCauDanhGia = 0;
        foreach($css_result_detail as $item_detail){
            $point_child += $item_detail->point;
            if($item_detail->point > 0){
                $tongSoCauDanhGia++;
            }
        }
        if($point_child == 0){
            $point = $cssResult->avg_point * 20;
        }else{
            $point = $cssResult->avg_point * 4 + $point_child/($tongSoCauDanhGia * 5) * 80;
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
                ->orderBy('position', 'asc')
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
        $cssCategory = DB::table('css_category')->where('parent_id', $projectTypeId)->get();
        $cssCate = array();
        if ($cssCategory) {
            foreach ($cssCategory as $item) {
                $cssCategoryChild = DB::table('css_category')->where('parent_id', $item->id)->get();
                $cssCateChild = array();
                if ($cssCategoryChild) {
                    foreach ($cssCategoryChild as $item_child) {

                        $cssQuestionChild = DB::table('css_question')->where('category_id', $item_child->id)->get();
                        foreach($cssQuestionChild as &$itemQuestionChild){
                            $css_result = Css::getCssResultByQuestion($itemQuestionChild->id,$startDate, $endDate,$teamIds);
                            if(count($css_result) > 0){
                                $countCss = 0;
                                $points = array();
                                foreach($css_result as $itemCssResult){
                                    $css_result_detail = DB::table('css_result_detail')
                                        ->where("css_id",$itemCssResult->id)
                                        ->where("question_id",$itemQuestionChild->id)
                                        ->first();
                                    if($css_result_detail->point > 0){
                                        $points[] = $css_result_detail->point;
                                        $countCss++;
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
                            }

                        }

                        $cssCateChild[] = array(
                            "id" => $item_child->id,
                            "name" => $item_child->name,
                            "parentId" => $item->id,
                            "questionsChild" => $cssQuestionChild,
                        );
                    }
                }

                $cssQuestion = DB::table('css_question')->where('category_id', $item->id)->get();
                foreach($cssQuestion as &$itemQuestion){
                    $css_result = Css::getCssResultByQuestion($itemQuestion->id,$startDate, $endDate,$teamIds);
                    if(count($css_result) > 0){
                        $countCss = 0;
                        $points = array();
                        foreach($css_result as $itemCssResult){
                            $css_result_detail = DB::table('css_result_detail')
                                ->where("css_id",$itemCssResult->id)
                                ->where("question_id",$itemQuestion->id)
                                ->first();
                            if($css_result_detail->point > 0){
                                $points[] = $css_result_detail->point;
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
}

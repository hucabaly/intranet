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

class CssController extends Controller {

    /**
     * Hàm hiển thị form tạo CSS
     * @return objects
     */
    public function create() {
        $user = Auth::user();
        $projects = ProjectType::all();
        $teams = Team::all();

        return view(
                'sales::css.create_css', [
            'user' => $user,
            "projects" => $projects,
            "teams" => $teams
                ]
        );
    }

    /**
     * Hàm hiển thị form sửa CSS
     * @param int $id
     * @return objects
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
        $str_teams_set_name = implode(',', $str_teams_set_name);

        return view(
                'sales::css.update_css', [
            'css' => $css, //css by css_id
            'user' => $user, //user by css_id
            "projects" => $projects, // all project type
            "teams" => $teams, //all team
            "teams_set" => $teams_set, // team lien quan cua CSS
            "str_teams_set_name" => $str_teams_set_name //text hien thi cac team cua CSS ra trang update
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
        //echo "<pre>";        var_dump($css);die;
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
        
        //lay thong tin hien ket qua danh sach du an
        $data = self::applyByProjectType($projectTypeIds,$startDate,$endDate,$teamIds); 
        
        return response()->json($data);
    }
    
    protected function applyByProjectType($projectTypeIds, $startDate, $endDate,$teamIds){
        //lay ra cac ban ghi tu bang css_result theo loai du an, ngay lam du an, team set theo css
        $cssResult = Css::getCssResultByProjectTypeIds($projectTypeIds, $startDate, $endDate,$teamIds);
        $stt = 0;
        
        //$pointToHighchart -> luu diem hien thi tren bieu do all result
        $pointToHighchart = [];
        
        //danh sach id cua css result
        $cssResultIds = [];
        
        //loop cssResult de add them cac thong tin khac vao
        foreach($cssResult as &$itemResult){
            $cssResultIds[] = $itemResult->id;
            $css = DB::table("css")->where("id",$itemResult->css_id)->first();
            
            //get team_id tu bang css_team
            $teamName = "";
            $team = DB::table("css_team")->where("css_id",$itemResult->css_id)->get();
            
            foreach($team as $teamId){
                if($teamName == ""){
                    $teamName = Css::getTeamNameById($teamId->team_id);
                }else{
                    $teamName .= ", " . Css::getTeamNameById($teamId->team_id);
                }
            }
            
            $itemResult->stt = ++$stt;
            $itemResult->project_name = $css->project_name;
            $itemResult->teamName = $teamName;
            $itemResult->pmName = $css->pm_name;
            $itemResult->css_created_at = date('d/m/Y',strtotime($css->created_at));
            $itemResult->created_at = date('d/m/Y',strtotime($itemResult->created_at));
            $itemResult->point = self::getPointCssResult($itemResult->id);
            
            //lay diem de show tren bieu do thoi gian all result
            $pointToHighchart[] = (float)$itemResult->point;
            $dateToHighchart[] = $itemResult->created_at;
        }
        
        //lay diem theo tung loai du an de show tren bieu do phan loai theo tieu chi
        $arrProjectTypeId = explode(",", $projectTypeIds);
        $pointCompareChart = array();
        foreach($arrProjectTypeId as $key => $project_type_id){
            $projectTypeName = Css::getProjectTypeNameById($project_type_id);
            $cssResultByProjectType = Css::getCssResultByProjectTypeId($project_type_id,$startDate,$endDate,$teamIds);
            $pointToHighchartByProjectType = [];
            $pointToHighchartByProjectType["name"] = $projectTypeName;
            foreach($cssResultByProjectType as $item){
                $pointToHighchartByProjectType["data"][] = (float)self::getPointCssResult($item->id);
            }
            $pointCompareChart[] = [
                "name" => $pointToHighchartByProjectType["name"],
                "data" => $pointToHighchartByProjectType["data"]
            ];
        }
        
        //Lay ra cac cau hoi duoi 3 sao theo cssResultIds
        $duoi3Sao = self::layDanhSachCauDuoi3Sao($cssResultIds);
        
        //Lay ra cac de xuat  cua khach hang neu co
        $danhSachDeXuat = self::layDanhSachDeXuat($cssResultIds);
        
        $data = [
            "cssResult" => $cssResult,
            "pointToHighchart" => $pointToHighchart,
            "dateToHighchart" => $dateToHighchart,
            "pointCompareChart" => $pointCompareChart,
            "duoi3Sao" =>$duoi3Sao,
            "danhSachDeXuat" => $danhSachDeXuat,
        ];
        return $data;
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
        
        $result = self::filterAnalyzeByProjectType($startDate, $endDate, $projectTypeIds,$teamIds);
        $data = array(
            "result" => $result,
        );
        
        return response()->view('sales::css.include.table_theotieuchi', $data);
    }
    
    /**
     * trang phan tich css, thuc hien B2. Filter theo loai du an 
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
        $stt = 0;
        foreach($arrProjectTypeId as $k => $v){
            $points = array();
            
            if($teamIds == ""){
                $css = DB::table("css")->where("project_type_id",$v)->get();
            }else{
                $css = Css::getCssByProjectTypeAndTeam($v,$teamIds);
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
            
            if(count($points) > 0){
                $projectType = ProjectType::find($v);
                $avgPoint = array_sum($points) / count($points);
                $stt++;
                $result[] = [
                    "stt"               => $stt,
                    "project_type_id"   => $v,
                    "project_type_name" => $projectType->name,
                    "countCss"          => $countCss,
                    "maxPoint"          => self::formatNumber(max($points)),
                    "minPoint"          => self::formatNumber(min($points)),
                    "avgPoint"          => self::formatNumber($avgPoint),
                ];
            }
            
        }
        
        return $result;
    }
    
    /**
     * lay danh sach cau hoi duoi 3 sao theo cssResultIds
     * @param array $cssResultIds
     */
    protected function layDanhSachCauDuoi3Sao($cssResultIds){
        $cssResultIds = implode(",", $cssResultIds);
        $danhSachDuoi3Sao = Css::layDanhSachCauDuoi3Sao($cssResultIds);
        $result = [];
        foreach($danhSachDuoi3Sao as $itemDuoi3Sao){
            $cssResult = Css::getCssResultById($itemDuoi3Sao->css_id);
            $diemCss = self::getPointCssResult($itemDuoi3Sao->css_id);
            $question = Css::getQuestionById($itemDuoi3Sao->question_id);
            $css = Css::find($cssResult->css_id);
            
            $result[] = [
                "stt"   => 1,
                "tenDuAn"   => $css->project_name,
                "tenCauHoi" => $question->content,
                "soSao" => $itemDuoi3Sao->point,
                "comment"   => $itemDuoi3Sao->comment,
                "ngayLamCss" => date('d/m/Y',strtotime($cssResult->created_at)),
                "diemCss" => $diemCss,
            ];
        }
        
        return $result;
    }
    
    /**
     * lay danh sach de xuat cua khach hang (nhung bai danh gia co de xuat) 
     * @param array $cssResultIds
     */
    protected function layDanhSachDeXuat($cssResultIds){
        $cssResultIds = implode(",", $cssResultIds);
        $danhSachDeXuat = Css::layDanhSachDeXuat($cssResultIds);
        $result =[];
        foreach($danhSachDeXuat as $itemDeXuat){
            $css = Css::find($itemDeXuat->css_id);
            
            $result[] = [
                "stt"   => 1,
                "diemCss"   => self::getPointCssResult($itemDeXuat->id),
                "tenDuAn"   => $css->project_name,
                "commentKhachHang" => $itemDeXuat->survey_comment,
                "ngayLamCss" => date('d/m/Y',strtotime($itemDeXuat->created_at)),
            ];
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
                $html .= '<input type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;' .$team->name;
            }else{
                $html .= '&nbsp;&nbsp;' .$team->name;
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
}

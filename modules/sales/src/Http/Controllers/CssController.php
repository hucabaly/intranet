<?php

namespace Rikkei\Sales\Http\Controllers;

use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use DB;
use Rikkei\Sales\Model\Css;
use Rikkei\Sales\Model\CssTeams;
use Rikkei\Sales\Model\CssQuestion;
use Rikkei\Sales\Model\CssCategory;
use Rikkei\Sales\Model\CssResult;
use Rikkei\Sales\Model\CssResultDetail;
use Rikkei\Team\Model\Team;
use Rikkei\Team\Model\Employees;
use Rikkei\Sales\View\CssPermission;
use Lang;
use Mail;
use Session;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Rikkei\Core\View\Breadcrumb;
use Rikkei\Core\View\Menu;
use Maatwebsite\Excel\Facades\Excel;
use Rikkei\Team\View\Permission;
use Route;

class CssController extends Controller {
    
    /**
     * construct more
     */
    protected function _construct()
    {
        Breadcrumb::add('Sales');
        Breadcrumb::add('Css');
        Menu::setActive('sales', 'sales');
    }
    
    static $perPage = 10;
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
        
        return redirect(url('/css/preview/' . $css->token . '/' . $css->id));
    }
    
    public function welcome($token, $id, Request $request){
        $css = Css::where('id', $id)
                ->where('token', $token)
                ->first();
        
        if ($request->input('submit') !== null) {
            $makeName   = $request->input('make_name'); 
            $token      = $request->input('token'); 
            $id         = $request->input('id'); 
            if($makeName === '') { 
                return view(
                    'sales::css.welcome', [
                        'css' => $css,
                        'token' => $token,
                        'id'    => $id,
                        "nameRequired" => 1, //name is empty
                        'makeName'  => $makeName,
                    ]);
            }elseif(strlen($makeName) > 100){
                return view(
                    'sales::css.welcome', [
                        'css' => $css,
                        'token' => $token,
                        'id'    => $id,
                        'makeName' => $makeName,
                        "nameRequired" => -1, //name with lenght > 100 char
                    ]);
            }else { 
                //Set make name and go to make CSS page
                $request->session()->put('makeName'.$id, $makeName);
                return redirect(url('/css/make/' . $token . '/' . $id));
            }
        }else {
            $name = ($request->session()->get('makeName'.$id) !== null) ? $request->session()->get('makeName'.$id) : '';
            return view(
                'sales::css.welcome', [
                    'css' => $css,
                    'token' => $token,
                    'id'    => $id,
                    "nameRequired" => 0, //name valid
                    'makeName' => $name,
                    
                ]
            );
        }
        
    }
   
    /**
     * Make Css page
     * @param string $token
     * @param int $id
     * @return objects
     */
    public function make($token, $id, Request $request) {
        $cssQuestionModel = new CssQuestion();
        $cssCategoryModel = new CssCategory();
        $css = Css::where('id', $id)
                ->where('token', $token)
                ->first();
        
        //Get CSS information
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
                                "show_brse_name" => $item_child->show_brse_name,
                                "show_pm_name" => $item_child->show_pm_name,
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
                        "noCate"    => $NoOverView, //No. of root cate
                        "show_brse_name" => $item->show_brse_name,
                        "show_pm_name" => $item->show_pm_name,
                    );
                }
            }
            
            //Get overview question
            $overviewQuestion = $cssQuestionModel->getOverviewQuestionByCategory($rootCategory->id,1);
            
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
                    "overviewQuestionId" => $overviewQuestion->id,
                    "overviewQuestionContent" => $overviewQuestion->content,
                    "makeName" => ($request->session()->get('makeName'.$id) !== null) ? $request->session()->get('makeName'.$id) : '',
                ]
            );
        } else {
            return redirect(url('/'));
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
        $proposed       = $request->input('proposed');
        $cssId          = $request->input('cssId');
        
        $dataResult = [
            'css_id' => $cssId,
            'name' => $name,
            'email' => $email,
            'avg_point' => $avgPoint,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
            'proposed' => $proposed
        ];
        
        $cssResultModel = new CssResult();
        $cssResultId = $cssResultModel->insertCssResult($dataResult);
        
        $cssResultDetailModel = new CssResultDetail();
        $cssResultDetailModel->insertCssResultDetail($cssResultId,$arrayQuestion);
        
        $css = Css::find($cssId); 
        $employee = Employees::find($css->employee_id); 
        $email = $employee->email; 
        $data = array(
            'href'      => url("/css/detail/" . $cssResultId) ,
            'css'       => $css,
            'cssResult' => $dataResult,
            'employee'  => $employee,
        );
        
        //Send mail to sale who created this css
        Mail::send('sales::css.sendMail', $data, function ($message) use($email, $css, $avgPoint) {
            $message->from('sales@rikkeisoft.com', 'Rikkeisoft');
            $message->to($email)->subject(Lang::get('sales::view.Subject email notification make css',["company" => $css->company_name, "point" => $avgPoint]));

        });

        
    }
    
    /**
     * View Css list 
     * @return void
     */
    public function grid(){
        $css = CssPermission::getCssListByPermission(self::$perPageCss);
        
        if(count($css) > 0){
            $cssResultModel = new CssResult();
            $teamModel = new Team();
            $i = ($css->currentPage()-1) * $css->perPage() + 1;
            foreach($css as &$item){ 
                $item->stt = $i;
                $i++;
                $item->project_type_name = self::getProjectTypeNameById($item->project_type_id);
                $cssTeams = CssTeams::getCssTeamByCssId($item->id);

                $arr_team = array();
                foreach($cssTeams as $cssTeamChild){
                    $team = $teamModel->getTeamWithTrashedById($cssTeamChild->team_id);
                    $arr_team[] = $team->name;
                }
                sort($arr_team); 
                $item->teamsName = implode(", ", $arr_team);

                $employee = Employees::find($item->employee_id);
                $item->sale_name = $employee->name;
                $item->start_date = date('d/m/Y',strtotime($item->start_date));
                $item->end_date = date('d/m/Y',strtotime($item->end_date));
                $item->create_date = date('d/m/Y',strtotime($item->created_at));
                $item->url =  url('/css/welcome/'. $item->token . '/' . $item->id);
                // get count css result by cssId 
                $item->countCss = $cssResultModel->getCountCssResultByCss($item->id);
                if($item->countCss == 1){
                    $cssResultDetail = $cssResultModel->getCssResultFirstByCss($item->id);
                    $item->hrefToView = url('/css/detail/' . $cssResultDetail->id);
                }else if($item->countCss > 1){
                    $item->hrefToView = url('/css/view/' . $item->id);
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
        $permissionFlag = CssPermission::isCssPermission($cssId,$css->employee_id);
        
        //If hasn't permission
        if(!$permissionFlag){
            return view(
                'core::errors.permission_denied'
            );
        }
        
        //If has permission
        if(count($css)){
            $cssResultModel = new CssResult();
            $cssResults = $cssResultModel->getCssResulByCss($cssId,self::$perPageCss);
            if(count($cssResults)){
                $i = ($cssResults->currentPage()-1) * $cssResults->perPage() + 1;
                foreach($cssResults as &$item){
                    $item->stt = $i;
                    $i++;
                    $item->make_date = date('d/m/Y',strtotime($item->created_at));
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
        $permissionFlag = CssPermission::isCssPermission($cssResult->css_id,$css->employee_id);
        
        //If hasn't permission
        if(!$permissionFlag){
            return view(
                'core::errors.permission_denied'
            );
        }
        
        //If has permission
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        $cssResultDetailModel = new CssResultDetail();
        
        $cssCate = self::getCssDetailPoint($css->project_type_id,$resultId);
        $rootCategory = $cssCategoryModel->getRootCategory($css->project_type_id);
        $overviewQuestion = $cssQuestionModel->getOverviewQuestionByCategory($rootCategory->id,1);
        $resultDetailRow = $cssResultDetailModel->getResultDetailRowOfOverview($resultId, $rootCategory->id);
        return view(
            'sales::css.detail', [
                'css' => $css,
                "employee" => $employee,
                "cssCate" => $cssCate,
                "cssResult" => $cssResult,
                "noOverView" => self::romanic_number(count($cssCate)+1,true),
                "overviewQuestionContent" => $overviewQuestion->content,
                "resultDetailRowOfOverview" => $resultDetailRow,
            ]
        );
    }
    
    public function exportExcel($resultId){
        $model = new Css();
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        $cssResultDetailModel = new CssResultDetail();
        
        $projectInfo = $model->projectMakeInfo($resultId);
        $cssCate = self::getCssDetailPoint($projectInfo->project_type_id,$resultId);
        $rootCategory = $cssCategoryModel->getRootCategory($projectInfo->project_type_id);
        $overviewQuestion = $cssQuestionModel->getOverviewQuestionByCategory($rootCategory->id,1);
        $resultDetailRow = $cssResultDetailModel->getResultDetailRowOfOverview($resultId, $rootCategory->id);
        
        $dataInfo = [];
        $dataInfo[] = array(self::formatNumber($projectInfo->point));
        //Project Info data
        $rowsInfoHead = count($dataInfo) + 2; //Get Row header of Project Info table
        $dataInfo[] = array(Lang::get('sales::view.Project information'));
        $dataInfo[] = array(Lang::get('sales::view.Project name jp'), $projectInfo->project_name, Lang::get('sales::view.Project date jp'),'', date("d/m/Y",strtotime($projectInfo->start_date)) . ' - ' . date("d/m/Y",strtotime($projectInfo->end_date)));
        $dataInfo[] = array(Lang::get('sales::view.Sale name jp'), $projectInfo->japanese_name, Lang::get('sales::view.Customer company name jp'),'', $projectInfo->company_name);
        $dataInfo[] = array(Lang::get('sales::view.PM name jp'), $projectInfo->pm_name, Lang::get('sales::view.Customer name jp'),'', $projectInfo->customer_name);
        $dataInfo[] = array(Lang::get('sales::view.BrSE name jp'), $projectInfo->brse_name, Lang::get('sales::view.Make name jp'),'', $projectInfo->make_name);
        
        
        //Point Detail data
        if(count($cssCate)){
            $rowSpace = count($dataInfo) + 2; //Get Row space
            $dataInfo[] = array('','','',''); 
            $rowsDetailHead = count($dataInfo) + 2; //Get Row header of Point Detail table
            $dataInfo[] = array(Lang::get('sales::view.Question'),'',Lang::get('sales::view.Rating'),Lang::get('sales::view.Comment'),'');
            foreach($cssCate as $item){
                $rowsCateLv1[] = count($dataInfo) + 2; //Get rows Categories lv 1
                $dataInfo[] = array($item["sort_order"] . ". " .$item['name'],''); //Category lv1 with I, II, ... 
                if($item['cssCateChild']){
                    foreach($item['cssCateChild'] as $itemChild){
                        $rowsCateLv2[] = count($dataInfo) + 2; //Get rows Categories lv 2
                        $dataInfo[] = array($itemChild["sort_order"] . ". " .$itemChild['name'],''); //Category lv2 with 1, 2, ... 
                        if($itemChild['questionsChild']){
                            foreach($itemChild['questionsChild'] as $questionChild){
                                $rowsQuestion[] = count($dataInfo) + 2; //Get rows questions
                                $dataInfo[] = array($questionChild->sort_order . ". " .$questionChild->content,'',$questionChild->point,$questionChild->comment,'');
                            }
                        }
                    }
                }elseif($item['questions']){
                    foreach($item['questions'] as $question){
                        $rowsQuestion[] = count($dataInfo) + 2; //Get rows questions
                        $dataInfo[] = array($question->sort_order . ". " .$question->content,'',$question->point,$question->comment,'');
                    }
                }
            }
        }
        
        $rowsCateLv1[] = count($dataInfo) + 2;
        $dataInfo[] = array(self::romanic_number(count($cssCate)+1) . ". " .Lang::get('sales::view.Overview'),'','','','');
        $rowsQuestion[] = count($dataInfo) + 2;
        $dataInfo[] = array($overviewQuestion->content,'',$resultDetailRow->point,$resultDetailRow->comment,'');
        //Proposed
        $rowProposed = count($dataInfo) + 2;
        $dataInfo[] = array(Lang::get('sales::view.Proposed'),$projectInfo->proposed);
        $rows = [
            'rowsInfoHead'      => $rowsInfoHead, 
            'rowsDetailHead'    => $rowsDetailHead,
            'rowsCateLv1'       => $rowsCateLv1,
            'rowsCateLv2'       => $rowsCateLv2,
            'rowsQuestion'      => $rowsQuestion,
            'rowSpace'          => $rowSpace,
            'rowProposed'      => $rowProposed
        ];
        
        //Export excel
        Excel::create('Filename', function($excel)  use($dataInfo,$rows){
            $excel->sheet('ProjectInfo', function($sheet)  use($dataInfo,$rows){
                $sheet->setVerticalCentered(true);
                $sheet->fromArray($dataInfo);
                $sheet->setPageMargin(25);
                // Set width for multiple cells
                $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  30,
                    'C'     =>  10,
                    'D'     =>  20,
                    'E'     =>  30,
                ));
                
                //Set valign and height all rows
                $countData = count($dataInfo);
                for($i=1; $i<=$countData+1;$i++){
                    $sheet->row($i, function($row) {
                        $row->setValignment('center');
                    });
                    $sheet->setHeight($i,30);
                }
                
                $sheet->mergeCells('A2:E2');
                $sheet->setHeight(2,60);
                $sheet->row(2, function($row) {
                    $row->setAlignment('right');
                    $row->setFontSize(30);
                });
                
                /**
                 * Project Info table
                 */
                
                //Merge column C and D Project Info table
                for($i=$rows['rowsInfoHead']+1;$i<=$rows['rowsInfoHead']+4;$i++){
                    $sheet->mergeCells('C'.$i.':D'.$i);
                }
                
                //Set style row Project Information header
                $sheet->row($rows['rowsInfoHead'], function($row) {
                    $row->setBackground('#3dd900');
                    $row->setFontColor('#ffffff');
                    $row->setFontWeight('bold');
                });
                
                /**
                 * End Project Info table
                 */
                
                //Merge A to E row space
                $sheet->mergeCells('A'.$rows['rowSpace'].':E'.$rows['rowSpace']);
                
                /**
                 * Point Detail table
                 */
                
                //Merge A and B, D and E Point Detail header
                $sheet->mergeCells('A'.$rows['rowsDetailHead'].':B'.$rows['rowsDetailHead']);
                $sheet->mergeCells('D'.$rows['rowsDetailHead'].':E'.$rows['rowsDetailHead']);
                
                //Set style row Point Detail header
                $sheet->row($rows['rowsDetailHead'], function($row) {
                    $row->setBackground('#3dd900');
                    $row->setFontColor('#ffffff');
                    $row->setFontWeight('bold');
                    $row->setAlignment('center');
                });
                
                //Category lv1 style
                foreach($rows['rowsCateLv1'] as $k => $rowNum){
                    $sheet->row($rowNum, function($row) {
                        $row->setBackground('#7eff5e');
                        $row->setFontColor('#ffffff');
                        $row->setFontWeight('bold');
                    });
                }
                
                //Category lv2 style
                foreach($rows['rowsCateLv2'] as $k => $rowNum){
                    $sheet->row($rowNum, function($row) {
                        $row->setFontWeight('bold');
                    });
                    
                    $sheet->mergeCells('A'.$rowNum.':E'.$rowNum);
                }
                
                //Merge column A and B, D and E of questions rows in Point Detail table
                foreach($rows['rowsQuestion'] as $k => $rowNum){
                    $sheet->mergeCells('A'.$rowNum.':B'.$rowNum);
                    $sheet->mergeCells('D'.$rowNum.':E'.$rowNum);
                    
                    //Set text align for rating column
                    $sheet->cells('C'.$rowNum, function($cells) {
                        $cells->setAlignment('center');
                    });
                }
                
                //Merge column D and C, D and E proposed row
                $sheet->mergeCells('B'.$rows['rowProposed'].':C'.$rows['rowProposed']);
                $sheet->mergeCells('D'.$rows['rowProposed'].':E'.$rows['rowProposed']);
                
                //Set height proposed row
                $sheet->setHeight($rows['rowProposed'],100);
            });
        })->export('xls');
    }
    
    public function getCssDetailPoint($projectTypeId,$resultId){
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        $cssResultDetailModel = new CssResultDetail();
        
        $rootCategory = $cssCategoryModel->getRootCategory($projectTypeId);
        $cssCategory = $cssCategoryModel->getCategoryByParent($rootCategory->id);
        
        $cssCate = [];
        if($cssCategory){
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
                            "sort_order" => $itemChild->sort_order,
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
                    "sort_order" => self::romanic_number($item->sort_order,true),
                    "cssCateChild" => $cssCateChild,
                    "questions" => $questions,
                );
            }
        }
        
        return $cssCate;
    }
    
    /**
     * Make CSS success to this page
     * @param int $cssId
     * @return void
     */
    public function success(){
        
        return view('sales::css.success'); 
    }
    
    /**
     * Cancel make CSS
     * @return void
     */
    public function cancelMake(){
        return view(
            'sales::css.cancel', []
        );
    }
    
    /**
     * CSS analyze page
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
     * Apply event in CSS analyze page
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
        $cssResultModel = new CssResult();
        if($criteria == 'projectType'){
            $cssResult = CssPermission::getAnalyzeByProjectType($criteriaIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'team'){
            $cssResult = CssPermission::getAnalyzeByProjectType($projectTypeIds, $startDate, $endDate,$criteriaIds);
        }else if($criteria == 'pm'){
            $cssResult = CssPermission::getAnalyzeByPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'brse'){
            $cssResult = CssPermission::getAnalyzeByBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'customer'){
            $cssResult = CssPermission::getAnalyzeByCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'sale'){
            $cssResult = CssPermission::getAnalyzeBySale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }else if($criteria == 'question'){
            $cssResult = CssPermission::getAnalyzeByQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
        }
        
        if(count($cssResult)){
            //display chart all result
            $allResultChart = [];

            //cssResultIds list
            $cssResultIds = [];

            //Get data chart all result 
            foreach($cssResult as $itemResult){
                $cssResultIds[] = $itemResult->id;
                $allResultChart[] = [
                    'date'  => $itemResult->end_date,
                    'point' => (float)self::formatNumber($itemResult->avg_point),
                ];
            }
            $strResultIds = implode(",", $cssResultIds);
            //Get data fill to table project list 
            $cssResultPaginate = self::showAnalyzeListProject($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria,1,'css.end_date','asc');

            //Get data fill to compare charts in analyze page
            $compareChart = self::getCompareCharts($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria);

            //Get data fill to table criteria less 3 star
            $lessThreeStar = self::getListLessThreeStar($strResultIds,1,'result_make','asc');

            //Get data fill to table customer's proposes
            $proposes = self::getProposes($strResultIds,1,'result_make','asc');

            $htmlQuestionList = "<option value='0'>".Lang::get('sales::view.Please choose question')."</option>";
            if($criteria == 'question'){
                $arrProjectType = explode(",", $projectTypeIds);
                $cssCategoryModel = new CssCategory();
                foreach($arrProjectType as $k=>$projectTypeId){
                    $rootCategory = $cssCategoryModel->getRootCategory($projectTypeId);
                    $htmlQuestionList .= "<option data-id='".$rootCategory->id."' class=\"parent\" disabled=\"disabled\">".$rootCategory->name."</option>";
                    $htmlQuestionList .= self::getHtmlQuestionList($projectTypeId,$startDate,$endDate,$teamIds,$criteriaIds,$strResultIds);
                }

            }
            
            $data = [
                "cssResult" => $cssResult,
                "cssResultPaginate" => $cssResultPaginate,
                "allResultChart" => $allResultChart,
                "compareChart" => $compareChart,
                "lessThreeStar" =>$lessThreeStar,
                "proposes" => $proposes,
                "htmlQuestionList" => $htmlQuestionList,
                "strResultIds" => $strResultIds,
            ];
        }else{
            $data = [];
        }
        
        
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
    public function showAnalyzeListProject($criteriaIds,$teamIds,$projectTypeIds,$startDate,$endDate,$criteria,$curPage,$orderBy,$ariaType){
        $cssResultModel = new CssResult(); 
        $teamModel = new Team();
        Paginator::currentPageResolver(function () use ($curPage) {
            return $curPage;
        });
        if($criteria == 'projectType'){
            //all result to show charts
            $cssResult = CssPermission::getAnalyzeByProjectType($criteriaIds, $startDate, $endDate,$teamIds);
            //result by pagination
            $cssResultPaginate = CssPermission::getAnalyzePaginateByProjectType($criteriaIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'team'){
            $cssResult = CssPermission::getAnalyzeByProjectType($projectTypeIds, $startDate, $endDate,$criteriaIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateByProjectType($projectTypeIds, $startDate, $endDate,$criteriaIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'pm'){
            $cssResult = CssPermission::getAnalyzeByPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateByPm($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'brse'){
            $cssResult = CssPermission::getAnalyzeByBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateByBrse($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'customer'){
            $cssResult = CssPermission::getAnalyzeByCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateByCustomer($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'sale'){
            $cssResult = CssPermission::getAnalyzeBySale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateBySale($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }else if($criteria == 'question'){
            $cssResult = CssPermission::getAnalyzeByQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds);
            $cssResultPaginate = CssPermission::getAnalyzePaginateByQuestion($criteriaIds,$projectTypeIds, $startDate, $endDate,$teamIds,self::$perPage,$orderBy,$ariaType);
        }
        
        $offset = ($cssResultPaginate->currentPage()-1) * $cssResultPaginate->perPage() + 1;
        foreach($cssResultPaginate as &$itemResultPaginate){
            //get teams name of Css result
            $teamName = "";
            $team = DB::table("css_team")->where("css_id",$itemResultPaginate->css_id)->get();
            $arrTeam = [];
            foreach($team as $teamId){
                $team = $teamModel->getTeamWithTrashedById($teamId->team_id);
                $arrTeam[] = $team->name;
            }
            rsort($arrTeam);
            $teamName = implode(', ', $arrTeam);
            //end get teams name
            
            $itemResultPaginate->stt = $offset++;
            $itemResultPaginate->teamName = $teamName;
            $itemResultPaginate->css_end_date = date('d/m/Y',strtotime($itemResultPaginate->end_date));
            $itemResultPaginate->css_result_created_at = date('d/m/Y',strtotime($itemResultPaginate->created_at));
            $itemResultPaginate->point = self::formatNumber($itemResultPaginate->avg_point);
        }
        
        //Get html pagination render
        $totalPage = ceil(count($cssResult) / self::$perPage);
        $html = "";
        if($totalPage > 1){
            if($curPage == 1){
                $html .= '<li class="disabled"><span>«</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.($curPage-1).',\''.Session::token().'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.$i.',\''.Session::token().'\',\''.$orderBy.'\',\''.$ariaType.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="showAnalyzeListProject('.($curPage+1).',\''.Session::token().'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="next">»</a></li>';
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
    protected function getListLessThreeStar($cssResultIds,$curPage,$orderBy,$ariaType){
        Paginator::currentPageResolver(function () use ($curPage) {
            return $curPage;
        });
        
        $lessThreeStar = Css::getListLessThreeStar($cssResultIds,self::$perPage,$orderBy,$ariaType);
        $offset = ($lessThreeStar->currentPage()-1) * $lessThreeStar->perPage() + 1;
        $result = [];
        foreach($lessThreeStar as $item){
            $result[] = [
                "no"   => $offset++,
                "projectName"   => $item->project_name,
                "questionName" => $item->question_name,
                "stars" => $item->point,
                "comment"   => $item->comment,
                "makeDateCss" => date('d/m/Y',strtotime($item->result_make)),
                "cssPoint" => self::formatNumber($item->result_point),
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
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.$i.',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStar('.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="next">»</a></li>';
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
    protected function getProposes($cssResultIds,$curPage,$orderBy,$ariaType){
        Paginator::currentPageResolver(function () use ($curPage) {
            return $curPage;
        });
        
        $proposes = Css::getProposes($cssResultIds,self::$perPage,$orderBy,$ariaType);
        $offset = ($proposes->currentPage()-1) * $proposes->perPage() + 1;
        $result =[];
        foreach($proposes as $propose){
            $result[] = [
                "no"   => $offset++,
                "cssPoint"   => self::formatNumber($propose->avg_point),
                "projectName"   => $propose->project_name,
                "customerComment" => $propose->proposed,
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
                $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.$i.',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposes('.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="next">»</a></li>';
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
        $cssResultModel = new CssResult();
        $cssResultDetailModel = new CssResultDetail();
        $criteriaIds = explode(",", $criteriaIds);
        $teamModel = new Team();
        $employeeModel = new Employees();
        
        $pointCompareChart = array();
        foreach($criteriaIds as $key => $criteriaId){
            if($criteria == 'projectType'){
                $name = self::getProjectTypeNameById($criteriaId);
                $cssResultByCriteria = CssPermission::getCompareChartByProjectType($criteriaId,$startDate,$endDate,$teamIds);
            }else if($criteria == 'team'){
                $team = $teamModel->getTeamWithTrashedById($criteriaId);
                $name = $team->name;
                $cssResultByCriteria = CssPermission::getCompareChartByTeam($criteriaId,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'pm'){
                $name = $criteriaId;
                $cssResultByCriteria = CssPermission::getCompareChartByPm($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'brse'){
                $name = $criteriaId;
                $cssResultByCriteria = CssPermission::getCompareChartByBrse($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'customer'){
                $name = $criteriaId;
                $cssResultByCriteria = CssPermission::getCompareChartByCustomer($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'sale'){
                $employee = $employeeModel::find($criteriaId);
                $name = $employee->name;
                $cssResultByCriteria = CssPermission::getCompareChartBySale($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }else if($criteria == 'question'){
                $question = CssQuestion::find($criteriaId);
                $name = $question->content;
                $cssResultByCriteria = CssPermission::getCompareChartByQuestion($criteriaId,$teamIds,$startDate,$endDate,$projectTypeIds);
            }
            
            $pointToHighchart = [];
            $pointToHighchart["data"] = [];
            
            if($criteria == 'question'){
                //get root category
                $model = new CssCategory();
                $cate = $model->getCateByQuestion($criteriaId);
                if($cate->parent_id == 0){
                    $rootCate = $cate;
                }else{
                    $rootCate = self::getRootCateByCate($cate);
                }
                //end get root category
                
                $question = CssQuestion::find($criteriaId);
                if($question->is_overview_question == 0){
                    $pointToHighchart["name"] = $rootCate->name . '.' . $question->sort_order;
                }else{
                    $pointToHighchart["name"] = $rootCate->name . '.' . Lang::get('sales::view.Overview question');
                }
                foreach($cssResultByCriteria as $itemCssResult){
                    $css_result_detail = $cssResultDetailModel->getResultDetailRow($itemCssResult->id,$criteriaId);
                    if($css_result_detail->point > 0){
                        $pointToHighchart["data"][] = [
                            'date'  => $itemCssResult->end_date,
                            'point' => (float)self::formatNumber($css_result_detail->point),
                        ];
                    }
                }
            }else{
                $pointToHighchart["name"] = $name;
                foreach($cssResultByCriteria as $item){
                    $pointToHighchart["data"][] = [
                        'date'  => $item->end_date,
                        'point' => (float)self::formatNumber($item->avg_point),
                    ];
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
        
        return response()->view('sales::css.include.table_criterias', $result);
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
            $projectTypeName = self::getProjectTypeNameById($projectTypeId);
            $points = array();
            $css = CssPermission::getFilterAnalyzeByProjectType($projectTypeId,$teamIds);
            if(count($css) > 0){
                $countCss = 0;
                foreach($css as $itemCss){
                    $css_result = Css::getCssResultByCssId($itemCss->id,$startDate,$endDate);
                    if(count($css_result) > 0){
                        $countCss += count($css_result);
                        foreach($css_result as $itemCssResult){
                            $points[] = self::formatNumber($itemCssResult->avg_point);
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
        $cssCategoryModel = new CssCategory();
        $cssQuestionModel = new CssQuestion();
        foreach($arrProjectTypeId as $key => $projectTypeId){
            $rootCategory = $cssCategoryModel->getRootCategory($projectTypeId);
            $overviewQuestion = $cssQuestionModel->getOverviewQuestionByCategory($rootCategory->id,1);
            $dataQuestion = self::getQuestionInfoAnalyze($overviewQuestion->id,$startDate, $endDate,$teamIds);
            $cssCate = self::getListQuestionByProjectType($projectTypeId,$startDate,$endDate,$teamIds);
            $noOverview = count($cssCate) + 1;
            $cssCateList[] = [
                "id"    => $projectTypeId,
                "name"   => self::getProjectTypeNameById($projectTypeId),
                "cssCate" => $cssCate,
                "overviewQuestionId" => $overviewQuestion->id,
                "overviewQuestionContent" => $overviewQuestion->content,
                "overviewQuestionCountCss"  => $dataQuestion["countCss"],
                "overviewQuestionMaxPoint"  => $dataQuestion["maxPoint"],
                "overviewQuestionMinPoint"  => $dataQuestion["minPoint"],
                "overviewQuestionAvgPoint"  => $dataQuestion["avgPoint"],
                "overviewTitle" => self::romanic_number($noOverview,true) . ". " . Lang::get('sales::view.Overview'),
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
        $teamModel = new Team();
        foreach($arrTeamId as $k => $teamId){
            $points = array();
            $css = CssPermission::getFilterAnalyzeByTeam($teamId,$projectTypeIds);
            $team = $teamModel->getTeamWithTrashedById($teamId);
            $teamId = $team->id;
            $teamName = $team->name;
            if(count($css) > 0){
                $countCss = 0;
                foreach($css as $itemCss){
                    $css_result = Css::getCssResultByCssId($itemCss->id,$startDate,$endDate);

                    if(count($css_result) > 0){
                        $countCss += count($css_result);
                        foreach($css_result as $itemCssResult){
                            $points[] = self::formatNumber($itemCssResult->avg_point);
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
                    $css = CssPermission::getFilterAnalyzeByPm($itemList->pm_name, $teamIds,$projectTypeIds);
                }else if($criteria == "brse"){
                    $css = CssPermission::getFilterAnalyzeByBrse($itemList->brse_name, $teamIds,$projectTypeIds);
                }else if($criteria == "customer"){
                    $css = CssPermission::getFilterAnalyzeByCustomer($itemList->customer_name, $teamIds,$projectTypeIds);
                }else if($criteria == "sale"){
                    $css = CssPermission::getFilterAnalyzeBySale($itemList->employee_id, $teamIds,$projectTypeIds);
                }

                $countCss = 0;
                if(count($css) > 0){
                    foreach($css as $itemCss){
                        $css_result = Css::getCssResultByCssId($itemCss->id,$startDate,$endDate);
                        
                        if(count($css_result) > 0){
                            $countCss += count($css_result);
                            foreach($css_result as $itemCssResult){
                                $points[] = self::formatNumber($itemCssResult->avg_point);
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
     * get team tree option recursive 
     * 
     * @param int $id
     * @param int $level
     */
    protected static function getTreeDataRecursive($parentId = 0, $level = 0, $idActive = null) 
    {
        $userAccount = Auth::user(); 
        $arrTeam = CssPermission::getArrTeamIdByEmployee($userAccount->employee_id);
        $permission = new Permission();
        $currentRoute = Route::getCurrentRoute()->getName();
        
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
                if($currentRoute == 'sales::css.analyze'){
                    if($permission->isScopeTeam()){
                        // If is scrope team -> checked only self team
                        if(in_array($team->id, $arrTeam)){ 
                            $html .= '<input type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;<span>' .$team->name. '</span>';
                        }else{
                            $html .= '<input disabled="disabled" type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;<span>' .$team->name. '</span>';
                        }
                    }else{
                        $html .= '<input type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;<span>' .$team->name. '</span>';
                    }
                }else{
                    $html .= '<input type="checkbox" class="team-tree-checkbox" data-id="'.$team->id.'" parent-id="'.$parentId.'" name="team['.$team->id.']">&nbsp;&nbsp;<span>' .$team->name. '</span>';
                }
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
                            $dataQuestion = self::getQuestionInfoAnalyze($itemQuestionChild->id,$startDate, $endDate,$teamIds);
                            $itemQuestionChild->countCss = $dataQuestion["countCss"];
                            $itemQuestionChild->maxPoint = $dataQuestion["maxPoint"];
                            $itemQuestionChild->minPoint = $dataQuestion["minPoint"];
                            $itemQuestionChild->avgPoint = $dataQuestion["avgPoint"];
                        }

                        $cssCateChild[] = array(
                            "id" => $itemChild->id,
                            "name" => $itemChild->sort_order . ". " . $itemChild->name,
                            "parentId" => $item->id,
                            "questionsChild" => $cssQuestionChild,
                        );
                    }
                }

                $cssQuestion = $cssQuestionModel->getQuestionByCategory($item->id);
                foreach($cssQuestion as &$itemQuestion){
                    $dataQuestion = self::getQuestionInfoAnalyze($itemQuestion->id,$startDate, $endDate,$teamIds);
                    $itemQuestion->countCss = $dataQuestion["countCss"];
                    $itemQuestion->maxPoint = $dataQuestion["maxPoint"];
                    $itemQuestion->minPoint = $dataQuestion["minPoint"];
                    $itemQuestion->avgPoint = $dataQuestion["avgPoint"];
                }
                $cssCate[] = array(
                    "id" => $item->id,
                    "name" => self::romanic_number($item->sort_order,true) . ". " .$item->name,
                    "parentId" => $item->parent_id,
                    "cssCateChild" => $cssCateChild,
                    "questions" => $cssQuestion,
                );
            }

        }
        
        return $cssCate;
    }
    
    /**
     * Get max, min, avg point of question
     * @param int $questionId
     * @param date $startDate
     * @param date $endDate
     * @param string $teamIds
     * @return type
     */
    protected function getQuestionInfoAnalyze($questionId,$startDate, $endDate,$teamIds){
        $cssResult = CssPermission::getFilterAnalyzeByQuestion($questionId,$startDate, $endDate,$teamIds);
        if(count($cssResult) > 0){
            $cssResultDetailModel = new CssResultDetail();
            $countCss = 0;
            $points = array();
            foreach($cssResult as $itemCssResult){
                $cssResultDetail = $cssResultDetailModel->getResultDetailRow($itemCssResult->id,$questionId);
                if(count($cssResultDetail)){
                    if($cssResultDetail->point > 0){
                        $points[] = $cssResultDetail->point;
                        $countCss++;
                    }
                }
            }
            $maxPoint = (count($points) > 0) ? self::formatNumber(max($points)) : "-";
            $minPoint = (count($points) > 0) ? self::formatNumber(min($points)) : "-";
            if(count($points) > 0){
                $avgPoint = array_sum($points) / count($points);
                $avgPoint = self::formatNumber($avgPoint);
            }else{
                $avgPoint = "-";
            }
        }else{
            $countCss = 0;
            $maxPoint = "-";
            $minPoint = "-";
            $avgPoint = "-";
        }
        
        return [
            "countCss"  => $countCss,
            "maxPoint"  => $maxPoint,
            "minPoint"  => $minPoint,
            "avgPoint"  => $avgPoint
        ];
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
        $NoOverView = 0;
        if($cssCate){
            foreach($cssCate as $itemCate){
                $NoOverView++;
                $html .= "<option parent-id='".$itemCate["parentId"]."' class=\"parent\" disabled=\"disabled\" data-id='".$itemCate["id"]."'>-- ".$itemCate["name"]."</option>";
                foreach($itemCate["cssCateChild"] as $itemCateChild){
                    $html .= "<option parent-id='".$itemCate["id"]."' class=\"parent\" disabled=\"disabled\" data-id='".$itemCateChild["id"]."'>---- ".$itemCateChild["name"]."</option>";
                    foreach($itemCateChild["questionsChild"] as $itemQuestionChild){
                        //if is question checked when click apply
                        if(in_array($itemQuestionChild->id, $arrQuestionId)){
                            $html .= '<option data-type="question" parent-id="'.$itemCateChild["id"].'" value="'.$itemQuestionChild->id.'" data-token="'.Session::token().'" data-cssresult="'.$cssResultIds.'" >------ '.$itemQuestionChild->sort_order. ". " .$itemQuestionChild->content.'</option>';
                        }
                    }
                }
                foreach($itemCate["questions"] as $itemQuestion){
                    //if is question checked when click apply
                    if(in_array($itemQuestion->id, $arrQuestionId)){
                        $html .= '<option data-type="question" parent-id="'.$itemCate["id"].'" value="'.$itemQuestion->id.'" data-token="'.Session::token().'" data-cssresult="'.$cssResultIds.'">------ '.$itemQuestion->sort_order. ". " .$itemQuestion->content.'</option>';
                    }
                }
            }
        }    
        
        //Show overview question if chosen
        $cssCategoryModel = new CssCategory();
        $rootCategory = $cssCategoryModel->getRootCategory($projectTypeId);
        $cssQuestionModel = new CssQuestion();
        $overviewQuestion = $cssQuestionModel->getOverviewQuestionByCategory($rootCategory->id,1);
        if(in_array($overviewQuestion->id, $arrQuestionId)){
            $html .= "<option data-type='overview' class=\"parent\" disabled=\"disabled\">-- ".self::romanic_number(++$NoOverView,true) . ". " . Lang::get('sales::view.Overview') ."</option>";
            $html .= '<option value="'.$overviewQuestion->id.'" data-token="'.Session::token().'" data-cssresult="'.$cssResultIds.'">------ '.$overviewQuestion->content.'</option>';
        }
        
        return $html;
    }
    
    /**
     * Get list less three star by cssResultIds and questionId
     * @param int $questionId
     * @param array $cssResultIds
     * @param int $curPage
     */
    protected function getListLessThreeStarByQuestion($questionId,$cssResultIds,$curPage,$orderBy,$ariaType){
        Paginator::currentPageResolver(function () use ($curPage) {
            return $curPage;
        });
        
        $lessThreeStar = Css::getListLessThreeStarByQuestionId($questionId,$cssResultIds,self::$perPage,$orderBy,$ariaType);
        
        $offset = ($lessThreeStar->currentPage()-1) * $lessThreeStar->perPage() + 1;
        $result = [];
        foreach($lessThreeStar as $item){
            $result[] = [
                "no"            => $offset++,
                "projectName"   => $item->project_name,
                "questionName"  => $item->question_name,
                "stars"         => $item->point,
                "comment"       => $item->comment,
                "makeDateCss"   => date('d/m/Y',strtotime($item->result_make)),
                "cssPoint"      => self::formatNumber($item->result_point),
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
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.$i.',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getListLessThreeStarByQuestion('.$questionId.','.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="next">»</a></li>';
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
    protected function getProposesByQuestion($questionId,$cssResultIds,$curPage,$orderBy,$ariaType){
        Paginator::currentPageResolver(function () use ($curPage) {
            return $curPage;
        });
        
        $proposes = Css::getProposesByQuestion($questionId,$cssResultIds,self::$perPage,$orderBy,$ariaType);
        
        $offset = ($proposes->currentPage()-1) * $proposes->perPage() + 1;
        $result =[];
        foreach($proposes as $propose){
            $result[] = [
                "no"   => $offset++,
                "cssPoint"   => self::formatNumber($propose->avg_point),
                "projectName"   => $propose->project_name,
                "customerComment" => $propose->proposed,
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
                $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.($curPage-1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="back">«</a></li>';
            }
            for($i=1; $i<=$totalPage; $i++){
                if($i == $curPage){
                    $html .= '<li class="active"><span>'.$i.'</span></li>';
                }else{
                    $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.$i.',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');">'.$i.'</a></li>';
                }
            }
            if($curPage == $totalPage){
                $html .= '<li class="disabled"><span>»</span></li>';
            }else{
                $html .= '<li><a href="javascript:void(0)" onclick="getProposesQuestion('.$questionId.','.($curPage+1).',\''.Session::token().'\',\''.$cssResultIds.'\',\''.$orderBy.'\',\''.$ariaType.'\');" rel="next">»</a></li>';
            }
        }
        
        $data = [
            "cssResultdata" => $result,
            "paginationRender" => $html,
        ];
        return $data;
    }
    
    /**
     * Get Romanic number
     * @param int $integer
     * @param boolean $upcase
     * @return romanic
     */
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
    
    /**
     * Get Project Type's name
     * @param int $projectTypeId
     * @return string
     */
    public function getProjectTypeNameById($projectTypeId){
        $projectTypeName = "";
        switch($projectTypeId){
            case 1: 
                $projectTypeName = "OSDC";
                break;
            case 2: 
                $projectTypeName = "Project base";
                break;
        }
        
        return $projectTypeName;
    }
    
    /**
     * Get root category by cate
     * @param CssCategory $cate
     */
    public function getRootCateByCate($cate){
        $cateParent = CssCategory::find($cate->parent_id);
        
        if(count($cateParent)){
            if($cateParent->parent_id == 0){
                return $cateParent;
            }else{
                return self::getRootCateByCate($cateParent);
            }
        }else{
            return null;
        }
    }
}

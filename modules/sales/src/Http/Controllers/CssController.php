<?php

namespace Rikkei\Sales\Http\Controllers;
use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use DB;
use Rikkei\Core\Model\User;
use Illuminate\Http\Request;
use Rikkei\Sales\Model\ProjectType;
use Rikkei\Sales\Model\Teams;
use Rikkei\Sales\Model\Css;

class CssController extends Controller
{


    public function create()
    {
        $user = Auth::user();
        $projects = ProjectType::all();
        $teams = Teams::all();
        
        return view(
                    'sales::css.create_css', 
                    [
                        'user'      => $user, 
                        "projects"  => $projects,
                        "teams"     => $teams
                    ]
                );
    }

    public function savecss(){
        if(Auth::check() && $_POST){
            //echo "<pre>"; var_dump($_POST);die;
            $start_date = date('Y-m-d',strtotime($_POST["start_date"]));
            $end_date   = date('Y-m-d',strtotime($_POST["end_date"]));

            //insert vao bang css
            $css = new Css;
            $css->user_id           = $_POST["user_id"];
            $css->company_name      = $_POST["company_name"];
            $css->customer_name     = $_POST["customer_name"];
            $css->project_name      = $_POST["project_name"];
            $css->brse_name         = $_POST["brse_name"];
            $css->start_date        = $start_date;
            $css->end_date          = $end_date;
            $css->pm_name           = $_POST["pm_name"];
            $css->project_type_id   = $_POST["project_type_id"];
            $css->token             = md5(rand());
            $css->save();

            //Neu user chua co ten Tieng Nhat thi update
            $user = Auth::user();
            if(!$user->japanese_name){
                $user->japanese_name = $_POST["japanese_name"];
                $user->save();
            }
            
            //insert vao bang css_team
            $teams = $_POST["teams"];
            foreach($teams as $k => $v){
                DB::table('css_team')->insert(
                     array(
                        'css_id'    =>   $css->id, 
                        'team_id'   =>   $k
                     )
                );
            }

            return redirect('/css/make/'.$css->token.'/'.$css->id);
        }
    }

    public function make($token,$id){
        $css = Css::where('id', $id)
               ->where('token', $token)
               ->first();
        
        if($css){
            $user = User::find($css->user_id);
            return view(
                    'sales::css.makecss', 
                    [
                       'css'   => $css, 
                       "user"  => $user
                    ]
  
                );
        }else{
            return redirect("/");
        }
    }
}

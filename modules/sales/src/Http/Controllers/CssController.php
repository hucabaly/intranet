<?php

namespace Rikkei\Sales\Http\Controllers;

use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use DB;
use Rikkei\Core\Model\User;
use Rikkei\Sales\Model\ProjectType;
use Rikkei\Sales\Model\Css;
use Rikkei\Team\Model\Team;
use Rikkei\Team\View\TeamList;

class CssController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $projects = ProjectType::all();
        $teams = Team::all();
        
        return view(
            'sales::css.create_css', 
            [
                'user'      => $user, 
                "projects"  => $projects,
                "teams"     => $teams
            ]
        );
    }

    public function update($id)
    {
        $css = Css::where('id', $id)->first();

        //get user tao css       
        $user = User::find($css->user_id); 

        //get list projects va teams
        $projects = ProjectType::all();
        $teams = Team::all();

        //get list team_id cua css theo css_id
        $team_ids = array();
        $team_id_by_css_id = DB::table('css_team')->where('css_id', $id)->get();

        foreach($team_id_by_css_id as $team){
            $team_ids[] = $team->team_id;
        }
        
        //get team cua css
        $teams_set = DB::table('team')->whereIn('id', $team_ids)->get();

        //text hien thi cac team cua CSS ra trang update
        //ngan cach nhau bang dau ','
        $str_teams_set_name = array();
        foreach($teams_set as $team){
            $str_teams_set_name[] = $team->name;
        }
        $str_teams_set_name = implode(',', $str_teams_set_name);

        return view(
            'sales::css.update_css', 
            [
                'css'       => $css, //css by css_id
                'user'      => $user, //user by css_id
                "projects"  => $projects, // all project type
                "teams"     => $teams, //all team
                "teams_set" => $teams_set, // team lien quan cua CSS
                "str_teams_set_name"    => $str_teams_set_name //text hien thi cac team cua CSS ra trang update
            ]
        );
    }


    /**
        function hien thi trang preview
    **/
    public function preview($token,$id){
        $css = Css::where('id', $id)
               ->where('token', $token)
               ->first();
        
        if($css){
            $user = User::find($css->user_id);
            return view('sales::css.preview_css', 
                        [
                           'css'   => $css, 
                           "user"  => $user
                        ]
      
                    );
        }else{
            return redirect("/");
        }      
        
    }

    /**
        function create hoac update CSS
    **/
    public function save(){
        if(Auth::check() && $_POST){
            $start_date = date('Y-m-d',strtotime($_POST["start_date"]));
            $end_date   = date('Y-m-d',strtotime($_POST["end_date"]));

            if($_POST["create_or_update"] == 'create'){
                $css = new Css;
            }else{
                $css_id = $_POST["css_id"];
                $css = Css::find($css_id);
            }
            
            //insert hoac vao bang css
            $css->user_id           = $_POST["user_id"];
            $css->company_name      = $_POST["company_name"];
            $css->customer_name     = $_POST["customer_name"];
            $css->project_name      = $_POST["project_name"];
            $css->brse_name         = $_POST["brse_name"];
            $css->start_date        = $start_date;
            $css->end_date          = $end_date;
            $css->pm_name           = $_POST["pm_name"];
            $css->project_type_id   = $_POST["project_type_id"];
            
            if($_POST["create_or_update"] == 'create'){
                //tao token
                $css->token = md5(rand());
                //Neu user chua co ten Tieng Nhat thi update
                $user = Auth::user();
                if(!$user->japanese_name){
                    $user->japanese_name = $_POST["japanese_name"];
                    $user->save();
                }
            }else{ // update thi delete css_id tu table css_team
                DB::table('css_team')->where('css_id', $css_id)->delete();
            }

            $css->save();

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

            return redirect('/css/preview/'.$css->token.'/'.$css->id);
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

<?php

namespace Rikkei\Sales\Http\Controllers;
use Rikkei\Core\Http\Controllers\Controller as Controller;
use Auth;
use Rikkei\Core\Model\User;
use Illuminate\Http\Request;
use Rikkei\Sales\Model\ProjectType;
use Rikkei\Sales\Model\Teams;
use Rikkei\Sales\Model\Css;

class CssController extends Controller
{

    public function make($token,$id){
        $css = Css::where('id', $id)
               ->where('token', $token)
               ->first();
        if($css){
            $user = User::find($css->user_id);

            return view(
                    'sales::makecss', 
                    [
                        'css'   => $css, 
                        "user"  => $user
                    ]
                );
        }
        else{
            return redirect("/");
        }
        
    }
}

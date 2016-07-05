<?php

namespace Rikkei\Core\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Rikkei\Core\View\View;
use Illuminate\Support\Facades\Config;

class UploadController extends Controller
{
    
    public function imageSkill(Request $request)
    {
        if(! $request->ajax()){
            return redirect('/');
        }
        if (Auth::guest()) {
            exit;
        }
        $result = [];
        $image = array_get(Input::all(),'file');
        $type = Input::get('skill_type');
        if (! $type) {
            $type = 'general';
        }
        $pathFolder = 'media/' . $type;
        if ($image) {
            $image = View::uploadFile(
                $image, 
                public_path($pathFolder),
                Config::get('services.image_allow')
            );
            if ($image) {
                $result['image_path'] = trim($pathFolder, '/').'/'.$image;
                $result['image'] = View::getLinkImage($result['image_path']);
            }
        }
        echo \GuzzleHttp\json_encode($result);
        exit;
    }
}
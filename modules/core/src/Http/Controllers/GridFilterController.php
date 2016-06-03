<?php
namespace Rikkei\Core\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class GridFilterController extends Controller
{
    /**
     * add data grid filter follow url
     */
    public function request()
    {
        if (! Input::get('current_url')) {
            return redirect('/');
        }
        $urlEncode = md5(Input::get('current_url'));
        Session::forget('filter.' . $urlEncode);
        Session::push('filter.' . $urlEncode, Input::get('filter'));
        return redirect('/');
    }
    
    public function remove()
    {
        if (! Input::get('current_url')) {
            return redirect('/');
        }
        $urlEncode = md5(Input::get('current_url'));
        Session::forget('filter.' . $urlEncode);
        return redirect('/');
    }
}


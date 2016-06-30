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
        Session::forget('filter_pager.' . $urlEncode . '.page');
        Session::push('filter.' . $urlEncode, Input::get('filter'));
        return redirect('/');
    }
    
    /**
     * add data grid filter follow url
     */
    public function pager()
    {
        if (! Input::get('current_url')) {
            return redirect('/');
        }
        $urlEncode = md5(Input::get('current_url'));
        Session::forget('filter_pager.' . $urlEncode);
        Session::put('filter_pager.' . $urlEncode, Input::get('filter_pager'));
        return redirect('/');
    }
    
    /**
     * remove filter data
     * 
     * @return type
     */
    public function remove()
    {
        if (! Input::get('current_url')) {
            return redirect('/');
        }
        $urlEncode = md5(Input::get('current_url'));
        Session::forget('filter.' . $urlEncode);
        Session::forget('filter_pager');
        return redirect('/');
    }
    
    /**
     * flush filter data
     * 
     * @return type
     */
    public function flush()
    {
        Session::forget('filter');
        Session::forget('filter_pager');
        return redirect('/');
    }
    
    public function forgerPager()
    {
        Session::forget('filter_pager');
    }
}

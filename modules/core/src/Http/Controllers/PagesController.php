<?php

namespace Rikkei\Core\Http\Controllers;

use Auth;
use Rikkei\Core\View\Breadcrumb;
use Rikkei\Core\View\Head;
use Rikkei\Core\View\Menu;

class PagesController extends Controller
{
    /**
     * Display home page
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        if (Auth::guest()) {
            return view('core::welcome');
        }
        
        Breadcrumb::add('dashboard', 'Dashboard');
        Head::setTitle('Dashboard');
        Menu::setActive('home');
        // TODO: get user data
        return view('core::dashbroad');
    }
}

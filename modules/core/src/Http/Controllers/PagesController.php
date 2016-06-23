<?php

namespace Rikkei\Core\Http\Controllers;

use Auth;
use Rikkei\Core\View\Breadcrumb;
use Rikkei\Core\View\Menu;
use URL;
use Illuminate\Support\Facades\Session;

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
            $errors = Session::get('errors', new \Illuminate\Support\MessageBag);
            if ($errors && count($errors) > 0) {
                return view('errors.general');
            }
            return view('core::welcome');
        }
        Breadcrumb::add('Dashboard');
        Menu::setActive('home');
        // TODO: get user data
        return view('core::dashbroad');
    }
}

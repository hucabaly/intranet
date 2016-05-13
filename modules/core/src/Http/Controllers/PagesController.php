<?php

namespace Rikkei\Core\Http\Controllers;

use Auth;

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

        // TODO: get user data
        return view('core::dashbroad');
    }
}

<?php

namespace Rikkei\Core\Http\Controllers;

use Lang;

class ErrorController extends Controller
{
    /**
     * Display error page
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        $code = app('request')->input('code');
        if (!$code) {
            $code = 404;
        }
        switch ($code) {
            case 404:
                $message = Lang::get('core::view.404 - Not found route');
                break;
            default:
                $message = Lang::get('core::view.Error system, view log details');
        }
        return view('core::errors.exception', [
            'message' => $message
        ]);
    }
}

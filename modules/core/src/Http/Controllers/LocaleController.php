<?php

namespace Rikkei\Core\Http\Controllers;

use Config;
use Auth;
use Session;
use Redirect;

class LocaleController extends Controller
{
    /**
     * Change locale
     *
     * @param string $locale
     * @return \Illuminate\Http\Response
     */
    public function change($locale)
    {
        $locales = Config::get('app.locales');
        if (isset($locales[$locale])) {
            Session::put('app.locale', $locale);
            //dd(Session::get('app.locale'));
            if (($user = Auth::user())) {
                // TODO: Save user's locale
            }
        } else {
            // TODO: set flash message
            //dd('error');
        }

        return Redirect::back();
    }
}
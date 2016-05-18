<?php

namespace Rikkei\Core\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Config;
use Auth;
use Socialite;
use Rikkei\Core\Model\User;
use URL;
use Session;
use Redirect;

class AuthController extends Controller
{

    /**
     * Redirect to social's sign in page
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function login($provider)
    {
        $providerKey = Config::get('services.' . $provider);
        if (empty($providerKey)) {
            throw new BadRequestHttpException("Provider '$provider' is invalid");
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handler after social's sign in page callback
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        $user = Socialite::driver($provider)->user();
        
        //add check email rikkei
        $email = $user->email;
        if (!$email) {
            redirect('/')->withErrors('Error Social connect');
        }
        if (!preg_match('/@rikkeisoft\.com$/', $email)) {
            $this->processNewAccount();
            return redirect('/')->withErrors('Please use Rikkisoft\'s Email!');
        }        
        
        $account = User::firstOrNew([
            'email'    => $user->email
        ]);

        $account->name = $user->name;
        $account->nickname = !empty($user->nickname) ? $user->nickname : preg_replace('/@.*$/', '', $user->email);
        $account->token = $user->token;
        $account->save();
        Auth::login($account);
        

        return redirect('/');
    }

    /**
     * User logout
     *
     * @param string $provider
     */
    public function logout()
    {
        // TODO
        $user = Auth::user();
        if($user) {
            $user->token = '';
            $user->save();
        }
        Auth::logout();
        return Redirect::away($this->getGoogleLogoutUrl())
                ->send();
    }
    
    /**
     * google logout url
     * 
     * @param type $redirect
     * @return type
     */
    protected function getGoogleLogoutUrl($redirect = '/')
    {
        return 'https://www.google.com/accounts/Logout' . 
            '?continue=https://appengine.google.com/_ah/logout' . 
            '?continue=' . URL::to($redirect);
    }
    
    /**
     * process if login by not account rikkei
     */
    protected function processNewAccount()
    {
        if(Session::has('google_account_not_rekkei')) {
            Session::forget('google_account_not_rekkei');
            return Redirect::away($this->getGoogleLogoutUrl('auth/connect/google'))
                ->send();
        }
        Session::push('google_account_not_rekkei', 1);
    }
}
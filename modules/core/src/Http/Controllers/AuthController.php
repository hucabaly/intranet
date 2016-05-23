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
use Illuminate\Support\ViewErrorBag;
use Lang;
use Illuminate\Support\MessageBag;

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
        $email = $user->email;
        if (!$email) {
            redirect('/')->withErrors('Error Social connect');
        }
        //add check email allow
        $domainAllow = Config::get('domain_logged');
        if($domainAllow && count($domainAllow)) {
            $matchCheck = false;
            foreach ($domainAllow as $value) {
                if (preg_match('/@'.$value.'$/', $email)) {
                    $matchCheck = true;
                    break;
                }
            }
            if (!$matchCheck) {
                $this->processNewAccount();
                return redirect('/');
            }
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
        $messageError = new MessageBag([
            Lang::get('core::message.Please use Rikkisoft\'s Email!')
        ]);
        Session::flash(
            'errors', Session::get('errors', new ViewErrorBag)->put('default', $messageError)
        );
        return Redirect::away($this->getGoogleLogoutUrl('/'))
                ->send();
    }
}

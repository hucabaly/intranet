<?php

namespace Rikkei\Core\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Config;
use Auth;
use Socialite;
use Rikkei\Core\Model\User;

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
        //Socialite::driver($provider)->deauthorize(Auth::user()->access_token);
        $user = Auth::user();
        if($user) {
            $user->token = '';
            $user->save();
        }
        Auth::logout();
        return redirect('/');
    }
}
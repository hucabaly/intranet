<?php

namespace Rikkei\Core\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Config;
use Auth;
use Socialite;
use Rikkei\Team\Model\User;
use URL;
use Session;
use Redirect;
use Illuminate\Support\ViewErrorBag;
use Lang;
use Illuminate\Support\MessageBag;
use Rikkei\Team\Model\TeamMembers;
use DB;

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
        Session::forget('permission');
        $user = Socialite::driver($provider)->user();
        $email = $user->email;
        if (!$email) {
            redirect('/')->withErrors('Error Social connect');
        }
        //add check email allow
        $domainAllow = Config::get('domain_logged');
        if ($domainAllow && count($domainAllow)) {
            $matchCheck = false;
            foreach ($domainAllow as $value) {
                if (preg_match('/@' . $value . '$/', $email)) {
                    $matchCheck = true;
                    break;
                }
            }
            if (!$matchCheck) {
                $this->processNewAccount();
                return redirect('/');
            }
        }
        
        $account = User::where('email', $user->email)
            ->first();
        if (! $account) {
            DB::beginTransaction();
            try {
                $dataDefault = User::createTeamPositionDefault();
                $account = User::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'nickname' => !empty($user->nickname) ? $user->nickname : preg_replace('/@.*$/', '', $user->email),
                    'token' => $user->token,
                    'avatar' => $user->avatar,
                ]);
                TeamMembers::create([
                    'team_id' => $dataDefault['team']->id,
                    'position_id' => $dataDefault['position']->id,
                    'user_id' => $account->id
                ]);
                DB::commit();
            } catch (Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        } else {
            $account->setData([
                'name' => $user->name,
                'nickname' => !empty($user->nickname) ? $user->nickname : preg_replace('/@.*$/', '', $user->email),
                'token' => $user->token,
                'avatar' => $user->avatar,
            ])->save();
        }
        Auth::login($account);
        return redirect('/');
    }

    /**
     * 
     * @return redirect
     */
    public function logout()
    {
        if (! Auth::check()) {
            return redirect('/');
        }
        $user = Auth::user();
        if($user) {
            $user->token = '';
            $user->save();
        }
        Auth::logout();
        Session::flush();
        return Redirect::away($this->getGoogleLogoutUrl())
            ->send();
    }
    
    /**
     * google logout url
     * 
     * @param string $redirect
     * @return string
     */
    protected function getGoogleLogoutUrl($redirect = '/')
    {
        return 'https://www.google.com/accounts/Logout' . 
            '?continue=https://appengine.google.com/_ah/logout' . 
            '?continue=' . URL::to($redirect);
    }
    
    /**
     * process if login by not account rikkei
     * 
     * @return redirect
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

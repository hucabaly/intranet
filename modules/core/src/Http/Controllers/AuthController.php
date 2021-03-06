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
use Rikkei\Team\Model\Employees;
use Rikkei\Core\View\View;

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
            return redirect('/')->withErrors('Error Social connect');
        }
        //add check email allow
        if (! View::isEmailAllow($email)) {
            $this->processNewAccount();
            return redirect('/');
        }
        
        $nickName = !empty($user->nickname) ? $user->nickname : preg_replace('/@.*$/', '', $user->email);
        $account = User::where('email', $user->email)
            ->first();
        $employee = Employees::where('email', $user->email)
            ->first();
        
        //add employee if is root
        if (! $employee) {
            if (View::isRoot($email)) {
                $employee = new Employees();
                $employee->setData([
                    'email' => $user->email,
                    'name' => $user->name,
                    'nickname' => $nickName
                ]);
                $employee->save();
            } else {
                $this->processNewAccount(Lang::get('core::message.You donot have permission login'));
                return redirect('/');
            }
        } elseif (! View::isRoot($email) && ! $employee->isAllowLogin()) {
            $this->processNewAccount(Lang::get('core::message.You donot have permission login'));
            return redirect('/');
        }
        
        $employeeId = $employee->id;
        //create or update accout
        if (! $account) {
            try {
                $account = User::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'token' => $user->token,
                    'employee_id' => $employeeId,
                    'google_id' => $user->id,
                ]);
                $account->employee_id = $employeeId;
                $account->save();
            } catch (Exception $ex) {
                return redirect('/')->withErrors($ex);
            }
        } else {
            //update information of user
            $account = $account->setData([
                'name' => $user->name,
                'token' => $user->token,
                'google_id' => $user->id,
            ]);
            if (! $account->employee_id) {
                $account->employee_id = $employeeId;
            }
            $account->save();
        }
        $this->storeSessionInformationAccount($user);
        Auth::login($account);
        return redirect('/');
    }

    /**
     * 
     * @return redirect
     */
    public function logout($message = null)
    {
        if (! Auth::check()) {
            return redirect('/');
        }
        $user = Auth::user();
        if ($user) {
            try {
                $user->token = '';
                $user->save();
            } catch (Exception $ex) {
                return redirect('/')->withErrors($ex);
            }
        }
        Auth::logout();
        Session::flush();
        if ($message) {
            $message = new MessageBag([
                $message
            ]);
            Session::flash(
                'errors', Session::get('errors', new ViewErrorBag)->put('default', $message)
            );
        }
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
    protected function processNewAccount($message = null)
    {
        if (! $message) {
            $message = new MessageBag([
                Lang::get('core::message.Please use Rikkisoft\'s Email!')
            ]);
        } else {
            $message = new MessageBag([
                $message
            ]);
        }
        Session::flash(
            'errors', Session::get('errors', new ViewErrorBag)->put('default', $message)
        );
        return Redirect::away($this->getGoogleLogoutUrl('/'))
            ->send();
    }
    
    /**
     * store information of account social into session
     * 
     * @param object $user
     * @return \Rikkei\Core\Http\Controllers\AuthController
     */
    protected function storeSessionInformationAccount($user)
    {
        Session::put(User::AVATAR, $user->avatar);
        return $this;
    }
}

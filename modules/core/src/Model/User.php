<?php

namespace Rikkei\Core\Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Rikkei\Team\Model\Employees;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class User extends CoreModel implements Authenticatable
{
    /**
     * const avatar key store session
     */
    const AVATAR = 'account.logged.avatar';
    /**
     * primary key
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'google_id', 'name', 'email', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];
    
    protected static $employee;

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'token';
    }
    
    /**
     * get Employee
     * 
     * @return model
     */
    public function getEmployee()
    {
        return Employees::where('email', $this->email)
                ->first();
    }
    
    /**
     * get employee of user logged
     * 
     * @return model
     */
    public static function getEmployeeLogged()
    {
        if (! self::$employee) {
            self::$employee = Employees::where('email', Auth::user()->email)
                ->first();
        }
        return self::$employee;
    }
    
    /**
     * get avatar of user logged
     * 
     * @return string
     */
    public static function getAvatar()
    {
        return Session::get(self::AVATAR);
    }
    
    /**
     * get nickname of user logged
     * 
     * @return string
     */
    public static function getNickName()
    {
        return self::getEmployeeLogged()->nickname;
    }
}
